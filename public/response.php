<?php
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;
	
	/*
	 * Helper php file that 
	 * pre: 
	 * post: 
	 */ 	

	$USER = new RadStep\User();
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
		
	
		
		
	}//endif !$USER->authenticated


?>