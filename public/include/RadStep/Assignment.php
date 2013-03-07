<?php

namespace RadStep;

use \PDO;

/**
 * Class: Assignment
 * Description: Extends RadStep/QuestionSet, providing a structure containing RadStep/Question objects with
 * the added provisions of an individual assignment. This class will also provide the
 * ability to be natively serialized using JSON. Assignment extends QuestionSet since all assignments are questionsets, but questionsets are not assignments
 * An assignment can be borne from a questionset with the addition of the particular assignment details
 * Furthermore, assignments are associated with individual users and their responses.
 * 
 */
class Assignment extends QuestionSet
{
	//Enum for assignments
	const COMPLETE = 1;
	const ASSIGNED_NOT_STARTED = 2;
	const ASSIGNED_STARTED = 3;
	
	//Class Variables
	var $assignment_id = "";
	var $responses = array();
	var $assigned_by = "";
	var $assigned_to = "";
	var $assigned_datetime = "";
	var $started_datetime = "";
	var $status = "";
	var $json = "";
	
	/**
	 * Constructor for Assignment opens PDO connection to sqlite3 database
	 * if assignment_id is provided, that assignment will be retrieved and 
	 * variables set from the embedded json object
	 * @param int $assignment_id creates Assignment by assignment_id
	 * 
	 */
	public function __construct($assignment_id)
	{
		
		/*
		 * Can't directly inherit the parent's constructor beause want to use assignment_id rather
		 * than questionset_id, so the sql query is different. Overloading of constructors
		 * is not permitted in the current version of PHP5
		 */
		
		// file location for the user database
		$dbfile = DATABASE_LOCATION  . DATABASE_NAME . ".db";
	
		// do we need to build a new database?
		$rebuild = false;
		if(!file_exists($dbfile)) { $rebuild = true; }
	
		// bind the database handler
		$this->database = new PDO("sqlite:" . $dbfile);
		
		// If we need to rebuild, the file will have been automatically made by the PDO call,
		// but we'll still need to define the user table before we can use the database.
		if($rebuild) { $this->rebuildDatabase($dbfile); }
	
		if(isset($assignment_id)){
			$this->$assignment_id = $assignment_id;
			$sql = "SELECT * FROM assignments WHERE assignment_id=".$assignment_id.";";
			
			foreach($this->database->query($sql) as $data){
				$this->json = $data["json"];	
				$this->setInstanceFromJson($data["json"]);				 
			}
		}
	}	
		
		
	//Functions
	
	/**
	 * Rebuilds the database if there is no database to work with yet.
	 * Creates the table assignments
	 * @param $dbfile the complete path to the sqlite database
	 * 
	 */
	function rebuildDatabase($dbfile)
	{
		$this->database->beginTransaction();
		$create = "CREATE TABLE assignments (
			assignment_id INTEGER PRIMARY KEY AUTOINCREMENT, 
			assigned_by TEXT,
			assigned_to TEXT,
			json TEXT);";
		$this->database->exec($create);
		$this->database->commit();
	}
	
	/**
	 * Saves a response to an assignment
	 * @param int $question_id ID of the question that was responded to
	 * @param int $response_chosen Number corresponding to the ordered answer choices as initially written in the question
	 * the order of possible answer choices may be shuffled on display
	 * @return bool successfully saved or not
	 */
	public function saveResponse($question_id, $response_chosen)
	{
		// find location of the question_id in the questions array
		$question_index = false;
		$question_index = array_search($question_id, $this->questions);
		if(!$question_index)
		{
			// if question doesn't exist return false
			return false;
		}
		else{
			// set corresponding value in responses[index] to 
			$this->responses[$question_index] = $response_chosen;
			
			/* DEBUG:
			 * update $json, might have problems with this??? especially with
			 * the $json object variable which doesn't need to be included in the 
			 * json output
			 */
			
			$this->json = json_encode($this);
		
			// save $json for this assignment to database
			$updatesql = "UPDATE assignments SET json = '".$this->json."' WHERE assignment_id=".$this->assignment_id.";";
			$this->database->exec($update);
		
			return true;
		}
		
		
	}
	
	/**
	 * Sets the variables in this instance by the provided json string
	 * @param string $json The appropriately structured json representation of a questionset
	 * @return if successfully sets the instance variables by the json string
	 */
	function setInstanceFromJson($json)
	{
		//echo(json_encode($this));
		
		$json_obj = json_decode($json);
		
		//var_dump($json_obj);

		
		if(!is_null($json_obj)){
			//Inherited from QuestionSet 
			$this->questionset_id = $json_obj->questionset_id;
			$this->name = $json_obj->questionset_name;
			$this->questions = $json_obj->questions;
			$this->created_by = $json_obj->created_by;
			$this->created_datetime = $json_obj->created_datetime;
			$this->keywords = $json_obj->keywords;
			$this->difficulty = $json_obj->difficulty;

			//Native to Assignment
			$this->assignment_id = $json_obj->assignment_id;
			$this->responses = $json_obj->responses;
			$this->assigned_by = $json_obj->assigned_by;
			$this->assigned_to = $json_obj->assigned_to;
			$this->assigned_datetime = $json_obj->assigned_datetime;
			$this->started_datetime = $json_obj->started_datetime;
			$this->status = $json_obj->status;
		 
		return true;
		}
		else {
			return false;
		}
	}
	
		/**
	 * Encodes existing instance variables to JSON and stores in the json instance variable as string
	 * 
	 * @return json representation | false (if unsuccesful)	
	 * 
	 */
	 function setJsonFromInstance()
	 {
	 	$to_serialize = array();

		//Inherited from QuestionSet 
		$to_serialize[] = $this->questionset_id;
		$to_serialize[] = $this->name = "";
		$to_serialize[] = $this->questions;
		$to_serialize[] = $this->created_by;
		$to_serialize[] = $this->created_datetime;
		$to_serialize[] = $this->keywords;
		$to_serialize[] = $this->difficulty;

		//Native to Assignment
		$to_serialize[] = $this->assignment_id;
		$to_serialize[] = $this->responses;
		$to_serialize[] = $this->assigned_by;
		$to_serialize[] = $this->assigned_to;
		$to_serialize[] = $this->assigned_datetime;
		$to_serialize[] = $this->started_datetime;
		$to_serialize[] = $this->status;

		$this->json = json_encode($to_serialize);
		
		return $this->json;
		
	 }
	 
	 /**
	  * TODO: after updating to PHP 5.4.0 this function may be uncommented 
	  * 	to have a class implement JsonSerializable
	  * Serializes the object to a value that can be serialized natively by json_encode(). 
	  * @return an array of the objects that should be serialized 
	  */ 
	  /*
	  public function jsonSerialize()
	  {
	  	
		//basically just serialize everything but the json string b/c don't want a recursive loop on encode
	  	$to_serialize = array();

		//Inherited from QuestionSet 
		$to_serialize[] = $this->questionset_id;
		$to_serialize[] = $this->name = "";
		$to_serialize[] = $this->questions;
		$to_serialize[] = $this->created_by;
		$to_serialize[] = $this->created_datetime;
		$to_serialize[] = $this->keywords;
		$to_serialize[] = $this->difficulty;

		//Native to Assignment
		$to_serialize[] = $this->assignment_id;
		$to_serialize[] = $this->responses;
		$to_serialize[] = $this->assigned_by;
		$to_serialize[] = $this->assigned_to;
		$to_serialize[] = $this->assigned_datetime;
		$to_serialize[] = $this->started_datetime;
		$to_serialize[] = $this->status;
	
		
	  	return $to_serialize;
	  } 
	 */
	 
	 /**
	  * Add this object to the database
	  * 	pre: assignment_id for this instance should be empty and will be assigned upon record creation
	  * 	temporary uniqid used as json on record creation
	  * 	record is then updated 
	  * @return true for success, false for fail
	  */
	  function addInstanceToDb()
	  {
	  	if(empty($this->assignment_id)){
	  			
	  		$tmp_id = uniqid();
			
			$sql_insert = "INSERT INTO assignments (json) VALUES (".$tmp_id.");";
			$this->database->exec($insert);
			
			$sql_get_real_id = "SELECT assignment_id FROM assignments WHERE json = ".$tmp_id.";";
			foreach($this->database->query($query) as $data) {
				$read_id = $data["assignment_id"];
			}
			
			$this->assignment_id = $read_id;
			$this->setJsonFromInstance();
			$sql_update = "UPDATE assignments SET json = ".$this->json." WHERE assignment_id = ".$real_id.";";
			$this->database->exec($update);
			
			return true;
	  	}
		
		return false;

	  }
	
	
	/**
	 * Gets an array of questions in this assignment
	 * @return array of questions 

	function getQuestions(){
		
		$query = "SELECT json FROM assignments WHERE assignment_id = ".$this->assignment_id.";";
		foreach($this->database->query($query) as $data){
				
				
				return $data->questions;
			}
	
	}
	*/
	
}


?>