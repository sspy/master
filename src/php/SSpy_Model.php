<?php

class SSpy_Model
{
	/** @var PDO */
	public $db = null;
 	public $error = null;
	const EMPTY_HTML = '<html></html>';

	public function __construct()
	{
		$this->dbConnect();
	}

	protected function dbConnect()
	{
		$sqliteFname = cfg('DB_SQLITE');
		$dsn = 'sqlite:' . $sqliteFname;
		$this->db = new PDO($dsn);
		$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->createDb();
	}

	protected function sql( $sql, $params = null )
	{
		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute($params);
			return $stmt;
		}
		catch (PDOException $e)
		{
			p($e->getMessage());
		}
	}

	protected function select( $sql, $params = null )
	{
		$stmt = $this->sql( $sql, $params );
		return $stmt;
	}

	protected function selectOne( $sql, $params = null )
	{
		if ( $stmt = $this->select( $sql, $params ) )
		{
			$row = $stmt->fetch(PDO::FETCH_OBJ);
			return $row;
		}

		return null;
	}

	public function saveSitemap( $baseurl )
	{
		//$sitemapXmlPath = $baseurl . '/sitemap.xml';
		$sitemapXmlPath = cfg('TEST_SITEMAP_XML');
		$sitemap = new SimpleXMLElement( $sitemapXmlPath, null, true );

		$this->insertOrUpdate( $baseurl, '' );
		$parentId = $this->db->lastInsertId();

		foreach ($sitemap as $url) {
			$this->insertOrUpdate( $url->loc, $url->lastmod, $parentId );
		}
	}

	public function insertOrUpdate( $url, $lastMod, $parentId = 0 )
	{
		/* @var $stmt PDOStatement */
		p("url $url");
		$stmt = null;
		$sql = "SELECT id, lastMod FROM html WHERE url = :url";
		$params = array( ':url' => $url );

		/** @var $result PDOStatement */
		if ( $result = $this->select($sql, $params) ) {
			if ( $row = $result->fetch(PDO::FETCH_OBJ) ) {
				if ($row->lastMod == $lastMod) {
					return true;
				}
				$content = $this->getRemoteContent($url);
				$sql = "UPDATE `html` SET `content` = :content, `lastMod` = :lastMod WHERE `id` = :id";
				$params = array( ':content' => $content, ':lastMod' => $lastMod, ':id' => $row->id);
				$this->sql($sql, $params);
			}
			else
			{
				$content = $this->getRemoteContent($url);
				$sql = "INSERT INTO `html` (`parentId`, `url`, `lastMod`, `content`) VALUES ( :parentId, :url, :lastMod, :content )";
				$params = array( ':parentId' => $parentId, ':url' => $url, ':lastMod' => $lastMod, ':content' => $content );
				$this->sql($sql, $params);
			}
		}

		return true;
	}

	public function getRemoteContent( $url )
	{
		$content = str_replace("'", '\'', file_get_contents($url));
		$content = $this->stripScripts($content);
		return $content;
	}

	public function getSavedContent( $url )
	{
		$sql = "SELECT `content` FROM `html` WHERE url = :url";
		$params = array( ':url' => $url );

		if ( $row = $this->selectOne($sql, $params) )
		{
			$content = $row->content;
			$content = str_replace("'", '\'', $content);
			$content = $this->stripScripts($content);
			return $content;
		}

		return self::EMPTY_HTML;
	}

	public function echoSavedContent( $url = null )
	{
		$content = self::EMPTY_HTML;

		if ( ! isset($_GET['url']) )
		{
			if ( ! empty($_GET['url']) )
			{
				$content = $this->getSavedContent($_GET['url']);
			}
		}
		else
		{
			$content = $this->getSavedContent($url);
		}

		echo $content;
		exit();
	}

	protected function getIdByUrl( $url )
	{
		$sql = 'SELECT `id` FROM `html` WHERE url = :url';
		$params = array( ':url' => $url );
		$row = $this->selectOne( $sql, $params );
		return $row ? $row->id : null;
	}

	public function getChildUrlsByParentUrl( $url )
	{
		$parentId = $this->getIdByUrl($url);
		if ( ! $parentId ) { return false; }
		$sql = 'SELECT `url` FROM `html` WHERE parentId = :parentId';
		$params = array( ':parentId' => $parentId );
		$result = $this->select($sql, $params);
		$urls = array();

		while ( $row = $result->fetch(PDO::FETCH_OBJ) )
		{
			array_push( $urls, "'{$row->url}'" );
		}

		$urlsJson = implode( ',', $urls );
		echo "{ 'urls' : [$urlsJson] }";
		exit();
	}

	protected function createDb()
	{
		$sql = <<<EOSQL

			CREATE TABLE IF NOT EXISTS `html`
			(
					 `id` INTEGER PRIMARY KEY,
			   `parentid` INTEGER NOT NULL DEFAULT (0),
					`url` TEXT NOT NULL,
				`lastmod` TEXT NOT NULL,
				`content` TEXT
			)
EOSQL;

		$this->sql($sql);
	}

	protected function stripScripts($html)
	{
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
		$script_tags = $doc->getElementsByTagName('script');
		$length = $script_tags->length;

		for ($i = 0; $i < $length; $i++)
		{
			if ( isset($script_tags->item($i)->parentNode) )
			{
				$script_tags->item($i)->parentNode->removeChild($script_tags->item($i));
			}
		}

		$no_script_html_string = $doc->saveHTML();
		return $no_script_html_string;
	}
}