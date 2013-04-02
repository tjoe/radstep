<?php
	
	namespace RadStep;

	use PDO;
	
	// Namespace constants
	
	/*
	 *  DATABASE_LOCATION should point to a private directory, not accessible to web users
	 *  Database file should be a sqlite3 file and does not need to exist before instantiating
	 *  the User object, however it should exist before instantiating the other objects of this 
	 *  namespace. 
	 * 
	 *  Database file will be located at DATABASE_LOCATION+DATABASE_NAME+.db 
	 * 		note that the .db extension is appended automatically.
	 */

	const DATABASE_LOCATION = "/home/ttuhscrads/ttuhscrads.com/private/";
	const DATABASE_NAME = "rsusers"; 
	
	const DOMAIN_NAME = "ttuhscrads.com";
	
	// if you want people to be able to reply to a real address, override
	// this variable to "yourmail@somedomain.ext" here.
	const MAILER_NAME = "noreply@ttuhscrads.com"; 
	const MAILER_REPLYTO = "noreply@ttuhscrads.com";
	
	const UPLOAD_DIR = "modules/";
			
			
	// Namespace declarations
	require_once("User.php");
	require_once("RadStepUser.php");
	require_once("Question.php");
	require_once("QuestionSet.php");
	require_once("Assignment.php");


	// Namespace functions
	
	/**
	 * Gets an array containing usernames of all the residents
	 * @return array of the usernames of all residents
	 */
	function getAllResidents()
	{
		$dbfile = DATABASE_LOCATION  . DATABASE_NAME . ".db";	
			
		$sql = "SELECT username FROM users;";
		$all_users = array();
		
		try{ 
			$database = new PDO("sqlite:" . $dbfile);
			foreach($database->query($sql) as $result){
				$all_users[] = $result["username"];
			}
			return $all_users;
		}
		catch(exception $ex){
			return $ex;
		}
	}
	
	/**
	 * Gets an array containing usernames of all the questionsets
	 * @return array containing names of all questionsets with questionset_id for the key
	 */
	function getAllQuestionSets()
	{
		$dbfile = DATABASE_LOCATION  . DATABASE_NAME . ".db";
		
		$sql = "SELECT questionset_id, json FROM questionsets;";
		$all_questionsets = array();
		
		try{ 
			$database = new PDO("sqlite:" . $dbfile);
			foreach($database->query($sql) as $result)
			{
				$questionset_id = $result["questionset_id"];
				$questionset = new QuestionSet($questionset_id);
				$all_questionsets[$questionset_id] = $questionset->name;
			}
			
			return $all_questionsets;
			
		}
		catch(exception $ex){
			return $ex;
		}

	}



?>