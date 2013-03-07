<?php

	//Global declarations, not associated with namespace
	date_default_timezone_set("America/Denver");
	ini_set("display_errors", 1);
	ini_set("error_reporting", E_ALL | E_STRICT);
	
	function redirect($url){
		header("Location: ".$url);
    	exit;
	}
	
	function insertDivHeader(){
		echo('<div id="div_header" style="text-align:center;margin:0 auto;">');
		echo('<h1 style="text-align:center;letter-spacing: 15px;">RadSTEP</h1>');
		echo('<h3 style="letter-spacing: 10px; font-variant: small-caps;">alpha</h3>');
		echo('</div><!--div_header-->');

	}
?>