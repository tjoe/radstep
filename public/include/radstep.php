<?php

	// Globabl declarations
	date_default_timezone_set("America/Denver");
	
	ini_set("display_errors", 1);
	ini_set("error_reporting", E_ALL | E_STRICT);

	// Global functions
	function redirect($url){
		header("Location: ".$url);
        exit;
	}








?>