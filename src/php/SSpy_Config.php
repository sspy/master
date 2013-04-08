<?php

class SSpy_Config
{
	public static $cfgNameVals = array(
		'TMP_DIR'          => '/tmp',
		'JS_DIR'           => '../js',
		'EXTERNS_JS'       => 'externs.js',
		'MAIN_JS'          => 'main.js',
		'PHANTOMJS_CMD'    => 'phantomjs',
		'DB_SQLITE'        => '/Users/jazzmongrel/SSpy.sqlite',
		'TEST_SITEMAP_XML' => '../xml/sitemap.xml',
		'CLI_OPTS'         => '--ignore-ssl-errors=yes --load-images=no --local-to-remote-url-access=no'

	);

	public static function get($key) {
		$val = SSpy_Config::$cfgNameVals[$key];
		return $val;
	}
}