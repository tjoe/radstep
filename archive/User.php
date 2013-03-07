<?php
	namespace RadStep;
	
	use \PDO;
	
	/**
	 *	A framework for simple user authentication.
	 *
	 *	Users are recorded using {username, password, token} triplets.
	 *	Whenever a user logs in successfully, his or her database
	 *	entry is assigned a new random token,  which is used in
	 * salting subsequent password checks.
	 * 
	 * 
	 */
	class User
	{
		var $db_handle;
		var $db_path = "";
		var $username = "";
		var $authenticated = false;
		var $username = ""; 
		var $roles = "";
		
		
		/**
		 * Constructor
		 */
		 public function __construct()
		 {
		 	//Connect to db
		 	
		 	//
		 	
		 }
		 
		 public function connectToDatabase(){
		 	
		 }
		

		
	
	
	
	
	
	}




?>
