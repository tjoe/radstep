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
	
	const TEST_MODE = 2;
	const TUTOR_MODE = 3;
	
	//Class Variables
	var $assignment_id = "";
	var $responses = array();
	var $assigned_by = "";
	var $assigned_to = "";
	var $assigned_datetime = "";
	var $started_datetime = "";
	var $due_datetime = "";
	var $assignment_mode = "";
	var $score = null;
	var $status = "";
	var $json = "";
	var $database = false;
	
	/**
	 * Constructor for Assignment opens PDO connection to sqlite3 database
	 * if assignment_id is provided, that assignment will be retrieved and 
	 * variables set from the embedded json object
	 * @param int $assignment_id creates Assignment by assignment_id, default value is false so that
	 * a warning is not thrown if no question_id is provided as in new Assignment()
	 * 
	 */
	public function __construct($assignment_id = false)
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
	
		if($assignment_id){
			$this->$assignment_id = $assignment_id;
			$sql = "SELECT json FROM assignments WHERE assignment_id=".$assignment_id.";";
			
			foreach($this->database->query($sql) as $data){
				$this->json = $data["json"];	
				$this->setInstanceFromJson($data["json"]);				 
			}
		}
	}	
		
		
	//Functions
	
	/**
	 * Loads the variables (creates a deep clone) of a parent object (QuestionSet)
	 * adapted from stackoverflow.com/questions/12882258
	 * @param $parentObj is a QuestionSet object, of which this class inherits; __clone may also be an option
	 * 
	 */
	 function loadFromQuestionSetObj($parentObj)
	 {
	 	$objValues = get_object_vars($parentObj); // return array of object values
        foreach($objValues as $key=>$value)
        {
             $this->$key = $value;
        }
	 }
	
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
		$question_index = array_search($question_id, $this->questions);
		
		if(is_null($question_index) || $question_index === false )
		{
			// if question doesn't exist return false
			return false;
		}
		else{
			// set corresponding value in responses[question_id] to 
			$this->responses[$question_id] = (int)$response_chosen;
			
			$this->updateScore();
			
			// save $json for this assignment to database
			$this->setJsonFromInstance();
			$updatesql = "UPDATE assignments SET json = ".$this->database->quote($this->json)." WHERE assignment_id=".$this->assignment_id.";";
			$statement = $this->database->prepare($updatesql);
			
			$statement->execute();
			//$this->database->exec(quote($updatesql));
			

			return true;
		}
	}
	
	/**
	 * Updates the score for the assignment
	 * @return new score
	 */
	public function updateScore()
	{
		// check each question for 
		$num_questions = count($this->questions);
		$correct_responses = 0;
		
		foreach($this->questions as $question_id)
		{
			$question = new Question($question_id);
			if($question->getCorrectChoiceIndex() == $this->getResponse($question_id))
				$correct_responses=$correct_responses+1;
		}
		
		$this->score = $correct_responses/$num_questions*100;
		
		return $this->score;
	}
	
	/**
	 * Gets a response for a question in an assignment
	 * @param int $question_id ID of the question that was responded to
	 * @return int choice number for question or null if the question_id doesn't exist in the list of responses
	 */
	public function getResponse($question_id)
	{
			//$response_index = array_search($question_id, array_keys($this->responses));
			$res = false; //$this->responses[$question_id];
			
			/**TODO: for some reason this->responses[$question_id]; DOES NOT WORK on this assoc array...must retrieve value manually */
			foreach($this->responses as $key => $val)
			{
				if($key == $question_id)	$res = $val;
			}
		
			if( $res !== false ) //strict, in case $res = 0
				return $res;
			else 
				return null;
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
			$this->name = $json_obj->name;
			$this->questions = $json_obj->questions;
			$this->created_by = $json_obj->created_by;
			$this->created_datetime = $json_obj->created_datetime;
			$this->keywords = $json_obj->keywords;
			$this->difficulty = $json_obj->difficulty;

			//Native to Assignment
			$this->assignment_id = $json_obj->assignment_id;
			$this->assigned_by = $json_obj->assigned_by;
			$this->assigned_to = $json_obj->assigned_to;
			$this->assigned_datetime = $json_obj->assigned_datetime;
			$this->started_datetime = $json_obj->started_datetime;
			$this->due_datetime = $json_obj->due_datetime;
			$this->status = $json_obj->status;
			$this->score = $json_obj->score;
			$this->assignment_mode = $json_obj->assignment_mode;
			
			//Decode Associative arrays
			$this->responses = (array)($json_obj->responses);
		 
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
		$to_serialize["questionset_id"] = $this->questionset_id;
		$to_serialize["name"] = $this->name;
		$to_serialize["questions"] = $this->questions;
		$to_serialize["created_by"] = $this->created_by;
		$to_serialize["created_datetime"] = $this->created_datetime;
		$to_serialize["keywords"] = $this->keywords;
		$to_serialize["difficulty"] = $this->difficulty;

		//Native to Assignment
		$to_serialize["assignment_id"] = $this->assignment_id;
		$to_serialize["responses"] = $this->responses;
		$to_serialize["assigned_by"] = $this->assigned_by;
		$to_serialize["assigned_to"] = $this->assigned_to;
		$to_serialize["assigned_datetime"] = $this->assigned_datetime;
		$to_serialize["started_datetime"] = $this->started_datetime;
		$to_serialize["due_datetime"] = $this->due_datetime;
		$to_serialize["status"] = $this->status;
		$to_serialize["score"] = $this->score;
		$to_serialize["assignment_mode"] = $this->assignment_mode;

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
	  		
			$this->setJsonFromInstance();
			
			// creates a record just to get an id
			$sql_insert = "INSERT INTO assignments (json) VALUES (null);";
			$this->database->exec($sql_insert);
			$this->assignment_id = $this->database->lastInsertId();
			$this->setJsonFromInstance();
			$this->updateInstanceToDb();
	
			return true;
	  	}
		
		return false;

	  }
	  
	  /**
	  * Updates the database row corresponding to this instance
	  * 	pre: assignment_id for this instance should not be empty
	  * 	record is then updated 
	  * @return true for success, false for fail
	  */
	  function updateInstanceToDb()
	  {
	  	if(!empty($this->assignment_id)){
	  		
			$this->setJsonFromInstance();
			
			$sql_update = "UPDATE assignments SET";
			$sql_update .= " assigned_by=".$this->database->quote($this->assigned_by). ", ";
			$sql_update .= " assigned_to=".$this->database->quote($this->assigned_to). ", ";
			$sql_update .= " json = ".$this->database->quote($this->json);
			$sql_update .= " WHERE assignment_id = ".$this->assignment_id.";";
			
			$this->database->exec($sql_update);
			
			
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