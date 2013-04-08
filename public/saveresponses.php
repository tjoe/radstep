<?php
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;
	
	/*
	 * Helper php file that performs the action of saving the responses from a question form
	 * pre: POST HTTP request expected to supply the following fields to this script
	 * 			assignment_id,
	 * 			question_id,
	 * 			radio_choices
	 * post: outputs the whether the assignment was created with details and/or errors encountered
	 */ 	

	$USER = new RadStep\User();
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
		
		$assignment_id = $_POST["assignment_id"];
		$question_id = $_POST["question_id"];
		$choice_value = $_POST["radio_choices"];
		
		$assignment = new RadStep\Assignment($assignment_id);


		//check if USER matches assigned_by or assigned_to field
		if($USER->username == $assignment->assigned_by || $USER->username == $assignment->assigned_to)
		{

			//responses will be a key-value array where the key is the question_id
			if($assignment->saveResponse($question_id, $choice_value))
			{ 
				echo("Response saved: ".$assignment->getResponse($question_id));
			}
			else {
				echo("Failed to save response");
			}
		
		}else{
			echo("Sorry, you are not associated with this assignment");
			
		}//endif username matches assignment
		
		

		
		
		
	}//endif !$USER->authenticated


?>