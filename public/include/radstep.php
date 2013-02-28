<?php

	// Globabl declarations
	date_default_timezone_set("America/Denver");
	
	ini_set("display_errors", 1);
	ini_set("error_reporting", E_ALL | E_STRICT);


	/**** ChromePhp Debugger ****/
	include 'ChromePhp.php';
	//ChromePhp::log('hello world');
	ChromePhp::log($_SERVER);
	
	// using labels
	foreach ($_SERVER as $key => $value) {
	    ChromePhp::log($key, $value);
	}
	
	// warnings and errors
	//ChromePhp::warn('this is a warning');
	//ChromePhp::error('this is an error');
	
	/***** END ChromePhp Debugger ****/
	
	
	/**** FirePHP Debugger ****/
	require_once('FirePHPCore/FirePHP.class.php');
	ob_start();
	/***** END FirePHP Debugger ****/




	// Global functions
	function redirect($url){
		header("Location: ".$url);
        exit;
	}










?>