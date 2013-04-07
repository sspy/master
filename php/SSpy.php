<?php

require('SSpy_Util.php');
require('SSpy_Config.php');
require('SSpy_Exception.php');
require('SSpy_Model.php');

class SSpy
{
	/* @var \SSpy_Model */
	protected $model;
	protected $urlList = array();
	protected $opts = null;

	public function __construct()
	{
		$this->model = new SSpy_Model();
	}

	public function runExec( $cmd ) {
		passthru($cmd);
	}

	public function writeFile( $fName, $data ) {
		$fd = fopen($fName, 'w');
		fwrite( $fd, "var sel = '{$this->css}';" );
	}

	public function runPhantomJs() {
		$mainJsPath = cfg('JS_DIR') . '/' . cfg('MAIN_JS');
		$phantomJsCmd = cfg('PHANTOMJS_CMD') . ' ' . cfg('CLI_OPTS') . ' ' . $mainJsPath;
		$this->runExec( $phantomJsCmd );
	}

	public function save( $url ) {
		$this->model->saveSitemap( $url );
	}

	public function searchCss( $css ) {
		$externsJsPath = cfg('TMP_DIR') . '/' . cfg('EXTERNS_JS');
		$this->writeFile( $externsJsPath, "var css = '$css'" );
		$this->runPhantomJs();
	}

	protected function usage()
	{
		p("sspy [-s][--css][--url]");
		exit();
	}

	public function echoSavedContent()
	{
		return $this->model->echoSavedContent();
	}

	public function handleAjax()
	{
		if ( isset($_GET['url']) )
		{
			if ( isset($_GET['list']) )
			{
				return $this->model->getChildUrlsByParentUrl( $_GET['url'] );
			}

			return $this->model->echoSavedContent( $_GET['url'] );
		}
	}

	public function main()
	{
		$shortopts = 's::';

		$longopts = array(
			"css:",
			"url:"
		);

		$this->opts = getopt( $shortopts, $longopts );

		$save = false;
		$css = null;

		foreach ($this->opts as $opt => $optVal)
		{
			switch ($opt)
			{
				case 's':
					$save = true;
					break;
				case 'css':
					$css = $this->opts['css'];
					break;
			}
		}

		if ($save)
		{
			$this->save( $this->opts['url'] );
			exit();
		}
		else if ($css)
		{
//			$this->
		}


		$this->usage();
	}
}
