<?php
	
	namespace RadStep;

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
	
	
			
			
	// Namespace declarations
	require_once("User.php");
	require_once("RadStepUser.php");
	require_once("Question.php");
	require_once("QuestionSet.php");
	require_once("Assignment.php");


	// Namespace functions
	
	





?>