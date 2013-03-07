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
	var $difficulty = "";
	var $json = "";
	
	/**
	 * Constructs a question object
	 * @param int $question_id ID of the question to create, optional
	 */
	function __construct($question_id)
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
	
		if(isset($question_id)){
			$this->$question_id = $question_id;
			$sql = "SELECT json FROM questions WHERE question_id=".$question_id.";";
			
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
			
			// $this->question_id = $json_obj->question_id;
			$this->created_by = $json_obj->created_by;
			$this->created_datetime = $json_obj->created_datetime;
			$this->images = $json_obj->images;
			$this->prompt = $json_obj->prompt;
			$this->multiple_choices = $json_obj->multiple_choices;
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

		$to_serialize[] = $this->question_id;
		$to_serialize[] = $this->created_by = "";
		$to_serialize[] = $this->created_datetime;
		$to_serialize[] = $this->prompt;
		$to_serialize[] = $this->images;
		$to_serialize[] = $this->multiple_choices;
		$to_serialize[] = $this->keywords;
		$to_serialize[] = $this->difficulty;	
		
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
	  			
	  		$tmp_id = uniqid();
			
			$sql_insert = "INSERT INTO questions (json) VALUES (".$tmp_id.");";
			$this->database->exec($insert);
			
			$sql_get_real_id = "SELECT question_id FROM questions WHERE json = ".$tmp_id.";";
			foreach($this->database->query($query) as $data) {
				$read_id = $data["question_id"];
			}
			
			$this->question_id = $read_id;
			$this->setJsonFromInstance();
			$sql_update = "UPDATE questions SET json = ".$this->json." WHERE question_id = ".$real_id.";";
			$this->database->exec($update);
			
			return true;
	  	}
		
		return false;

	  }
	
}
