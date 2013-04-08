<?php
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;
	
	/*
	 * Helper php file that may be called by ajax request to update the database mark exam as completed
	 * 
	 */ 			

	$USER = new RadStep\User();

 	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
	
	$assignment_id = $_POST["assignment_id"];

	$assignment = new RadStep\Assignment($assignment_id);


	//check if USER matches assigned_by or assigned_to field
	if($USER->username == $assignment->assigned_by || $USER->username == $assignment->assigned_to)
	{
		
		//responses will be a key-value array where the key is the question_id
		$assignment->status = RadStep\Assignment::COMPLETE;
		
		//add to database
		$assignment->updateInstanceToDb();
		
		echo("Exam completed");
	
	}else{
		echo("Sorry, you are not associated with this assignment");
		
	}//endif username matches assignment
	
	
	}//endif !$USER->authenticated
	 
	 
	 	 
?>
	