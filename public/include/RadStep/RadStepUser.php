<?php
	namespace RadStep;
	
	use \PDO;
	
	/**
	 * RadStepUser extends User
	 * Adds functionality specific to RadStep such as getting assignments associated with a user and 
	 * getting stats etc.
	 */
	 
	 class RadStepUser extends User
	 {
	 	
	 	function __construct($registration_callback=false)
		{
			parent::__construct($registration_callback);		
			
			
			//TODO: workaround for connection lost with inheritence for some reason
			if(is_null($this->database))
			{
				$dbfile = DATABASE_LOCATION . DATABASE_NAME . ".db";
				// RECONNECT DB (already connected in parent constructor, so will not need to rebuild)
				$this->database = new PDO("sqlite:" . $dbfile);
			}
			
		}
		
		/**
		 * Gets a list of the assignments that were assigned by a user (faculty) to another user (resident)
		 * @return array of assignment_id's, false if none or if unsuccessful
		 *
		 */
		public function getAssignmentsAssignedByMe()
		{
	
			$query = "SELECT assignment_id FROM assignments WHERE assigned_by = '".$this->username."';";
			$results = $this->database->query($query);
			$assignments = array();
			foreach($results as $row){
				$assignments[] = $row["assignment_id"];
			}
			
			return $assignments;
		}
		
		/**
		 * Gets a list of the assignments that were assigned to a user (resident) from another user (faculty)
		 * @return array of assignment_id's, false if none or if unsuccessful
		 *
		 */
		public function getAssignmentsAssignedToMe()
		{

			$query = "SELECT assignment_id FROM assignments WHERE assigned_by = '".$this->username."';";
			$results = $this->database->query($query);
			$assignments = array();
			foreach($results as $row){
				$assignments[] = $row["assignment_id"];
			}
			
			return $assignments;
		}
		
	 }

?>