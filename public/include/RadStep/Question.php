<?php



namespace RadStep;
  
use \PDO;
 /**
 * Class: Question
 * Description: Provides a question object with variables most consistent with a multiple choice type of question and
 * the ability to contain links to multiple images with captions etc. This class will also provide the
 * ability to be natively serialized using JSON.
 * 
 */
class Question
{
	
	//Class Variables
	var $question_id = "";
	var $created_by = "";
	var $created_datetime = "";
	var $prompt = "";
	var $images = array();
	var $multiple_choices = array();
	var $keywords = "";
	var $explanation = "";
	var $difficulty = "";
	var $json = "";
	var $database = false;
			
	/**
	 * Constructs a question object
	 * @param int $question_id ID of the question to create, optional; default value is false so that
	 * a warning is not thrown if no question_id is provided as in new Question()
	 */
	function __construct($question_id = false)
	{
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
		
		if($question_id){
			
			$this->$question_id = $question_id;
			
			$sql = "SELECT json FROM questions WHERE question_id=".$question_id.";";
			
			try{
				$statement = $this->database->prepare($sql);
				$statement->execute();
				$data = $statement->fetch();
				$this->json = $data["json"];	
			}catch(exception $ex){
				echo $ex;
			}
			
			$this->setInstanceFromJson($data["json"]);
			
			/*
			$sql = "SELECT json FROM questions WHERE question_id=".$question_id.";";
			
			
			foreach($this->database->query($sql) as $data){
				$this->json = $data["json"];	
				$this->setInstanceFromJson($data["json"]);				 
			}
			 */
		}
		
	}
	
	
	//Functions
	
	/** 
	 * Gets the index of the correct answer choice as listed in the array multiple_choices
	 * @return index of the correct answer choice as listed in the array multiple_choices, 
	 * 			returns null if no answer choice is listed as correct
	 * 			if multiple choices are listed as correct, then the index of the last 
	 * 			one listed will be returned
	 */
	 function getCorrectChoiceIndex()
	 {
	 	$correct_index = null;
		
	 	foreach($this->multiple_choices as $index => $choice){
	 		
	 		if($choice->correct){ $correct_index=$index; };
	 	}
		
		return $correct_index;
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
		$create = "CREATE TABLE questions (
			question_id INTEGER PRIMARY KEY AUTOINCREMENT, 
			json TEXT);";
		$this->database->exec($create);
		$this->database->commit();
	}
	
	/**
	 * Sets the variables in this instance by the provided json string
	 * @param string $json The appropriately structured json representation of a question
	 * @return if successfully sets the instance variables by the json string
	 */
	function setInstanceFromJson($json)
	{
		$json_obj = json_decode($json);
			
		if(!is_null($json_obj)){
			
			$this->question_id = $json_obj->question_id;
			$this->created_by = $json_obj->created_by;
			$this->created_datetime = $json_obj->created_datetime;
			$this->images = $json_obj->images;
			$this->prompt = $json_obj->prompt;
			$this->multiple_choices = $json_obj->multiple_choices;
			$this->explanation = $json_obj->explanation;
			$this->keywords = $json_obj->keywords;
			$this->difficulty = $json_obj->difficulty;
			
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

		$to_serialize["question_id"] = $this->question_id;
		$to_serialize["created_by"] = $this->created_by;
		$to_serialize["created_datetime"] = $this->created_datetime;
		$to_serialize["prompt"] = $this->prompt;
		$to_serialize["images"] = $this->images;
		$to_serialize["multiple_choices"] = $this->multiple_choices;
		$to_serialize["explanation"] = $this->explanation;
		$to_serialize["keywords"] = $this->keywords;
		$to_serialize["difficulty"] = $this->difficulty;	
		
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

		$to_serialize[] = $this->question_id;
		$to_serialize[] = $this->created_by = "";
		$to_serialize[] = $this->created_datetime;
		$to_serialize[] = $this->prompt;
		$to_serialize[] = $this->images;
		$to_serialize[] = $this->multiple_choices;
		$to_serialize[] = $this->keywords;
		$to_serialize[] = $this->difficulty;	
		
	  	return $to_serialize;
	  } 
	 */
	 
	 /**
	  * Add this object to the database
	  * 	pre: question_id for this instance should be empty and will be assigned upon record creation
	  * 	temporary uniqid used as json on record creation
	  * 	record is then updated 
	  * @return true for success, false for fail
	  */
	  function addInstanceToDb()
	  {

	  	if(empty($this->question_id)){
			
			$this->setJsonFromInstance();

			/**TODO: escape characters for html? */
			
			$sql_insert = "INSERT INTO questions (json) VALUES (null);";
			$this->database->exec($sql_insert);
			$this->question_id = $this->database->lastInsertId();
			//$this->setJsonFromInstance();
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
	  	if(!empty($this->question_id)){
	  		
			$this->setJsonFromInstance();
			
			$sql_update = "UPDATE questions SET json = ".$this->database->quote($this->json)." WHERE question_id = ".$this->question_id.";";

			$this->database->exec($sql_update);
			
			return true;
	  	}
		
		return false;

	  }
	
}
