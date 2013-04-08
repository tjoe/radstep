<?php
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;
	
	/*
	 * This performs the action of creating an assignment when given a set of variables
	 * pre: POST HTTP request expected to supply the following fields to this script
	 * 			date_duedate
	 * 			time_duedate
	 * 			assigned_to
	 * 			questionset_id
	 * post: outputs the whether the assignment was created with details and/or errors encountered
	 */ 	

	$USER = new RadStep\User();
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
		
		//clone the questionset object to the new assignment YAY OOP and inheritance
		$assignment = new RadStep\Assignment();
		$questionset = new RadStep\QuestionSet($_POST["questionset_id"]);
		$assignment->loadFromQuestionSetObj($questionset);
		
		$assignment->assigned_by = $USER->username;
		$assignment->assigned_to = htmlspecialchars($_POST["assigned_to"]);
		$assignment->assigned_datetime = date(DateTime::ISO8601);
		$assignment->due_datetime = date(DateTime::ISO8601,strtotime($_POST["date_duedate"]." ".$_POST["time_duedate"]));
		$assignment->status = RadStep\Assignment::ASSIGNED_NOT_STARTED;
		if($_POST["assignment_mode"] == "Tutor")
			$assignment->assignment_mode = RadStep\Assignment::TUTOR_MODE;
		else
			$assignment->assignment_mode = RadStep\Assignment::TEST_MODE;
		
		//add to database
		$assignment->addInstanceToDb();
		
		echo("Successfully created an assignment based on the ". $assignment->name . " module for ". $assignment->assigned_to.", due by ".$assignment->due_datetime);
		
		
		
	}//endif !$USER->authenticated


?>