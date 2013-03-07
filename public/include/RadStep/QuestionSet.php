<?php

/**
 * Class: QuestionSet
 * Description: Provides a structure containing RadStep/Question objects. This class will also provide the
 * ability to be natively serialized using JSON. This is a generic container for questions and may become an
 * Assignment once assigned to a resident. Note that responses are not part of a questionset, only an assignement.
 * 
 */

namespace RadStep;

class QuestionSet
{
	//Class Variables
	var $questionset_id = "";
	var $name = "";
	var $questions = array();
	var $created_by = "";
	var $created_datetime = "";
	var $keywords = "";
	var $difficulty = "";

	var $json = "";
	var $database = false;
	

	/**
	 * Constructor for QuestionSet opens PDO connection to sqlite3 database
	 * if questionset_id is provided, that questionset will be retrieved and 
	 * variables set from the embedded json object
	 * TODO: overload or (pseudo)overload constructor to accept various signatures
	 * @param int $questionset_id creates questionset by questionset_id
	 * 
	 */
	public function __construct($questionset_id)
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
	
		if(isset($questionset_id)){
			$this->questionset_id = $questionset_id;
			$sql = "SELECT * FROM question_sets WHERE questionset_id = ".$questionset_id.";";
			
			foreach($this->database->query($sql) as $data){
				$this->json = $data["json"];	
				$this->setInstanceFromJson($data["json"]);				 
			}
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
		$create = "CREATE TABLE questionsets (
			questionset_id INTEGER PRIMARY KEY AUTOINCREMENT, 
			created_by TEXT,
			json TEXT);";
		$this->database->exec($create);
		$this->database->commit();
	}
	
	//Functions
	
	/**
	 * Sets the variables in this instance by the provided json string
	 * @param string $json The appropriately structured json representation of a questionset
	 * @return if successfully sets the instance variables by the json string
	 */
	function setInstanceFromJson($json)
	{
		$json_obj = json_decode($json);
		if(!is_null($json_obj)){ 
			$this->questionset_id = $json_obj->questionset_id;
			$this->name = $json_obj->questionset_name;
			$this->questions = $json_obj->questions;
			$this->created_by = $json_obj->created_by;
			$this->created_datetime = $json_obj->created_datetime;
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
		
		$to_serialize[] = $this->questionset_id;
		$to_serialize[] = $this->name = "";
		$to_serialize[] = $this->questions;
		$to_serialize[] = $this->created_by;
		$to_serialize[] = $this->created_datetime;
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
		
		$to_serialize[] = $this->questionset_id;
		$to_serialize[] = $this->name = "";
		$to_serialize[] = $this->questions;
		$to_serialize[] = $this->created_by;
		$to_serialize[] = $this->created_datetime;
		$to_serialize[] = $this->keywords;
		$to_serialize[] = $this->difficulty;
		
	  	return $to_serialize;
	  } 
	 */
	 
	 /**
	  * Adds a newly created object to the database, and populates json based on current value of instance variables
	  * 	pre:questionset_id for this instance should be empty and will be assigned upon record creation
	  * 	temporary uniqid used as json on record creation
	  * 	record is then updated 
	  * @return true for success, false for fail
	  */
	  function addInstanceToDb()
	  {
	  	if(empty($this->questionset_id) ){
	  			
	  		$tmp_id = uniqid();
			
			$sql_insert = "INSERT INTO questionsets (json) VALUES (".$tmp_id.");";
			$this->database->exec($insert);
			
			$sql_get_real_id = "SELECT questionset_id FROM questionsets WHERE json = ".$tmp_id.";";
			foreach($this->database->query($query) as $data) {
				$read_id = $data["questionset_id"];
			}
			
			$this->questionset_id = $read_id;
			$this->setJsonFromInstance();
			$sql_update = "UPDATE questionset SET json = ".$this->json." WHERE questionset_id = ".$real_id.";";
			$this->database->exec($update);
			
			
			return true;
	  	}
		
		return false;

	  }
	  
	

}

?>
