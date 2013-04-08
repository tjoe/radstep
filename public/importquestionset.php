<?php
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	//use RadStep;
	
	/*
	 * This is a temporary means of importing modules to the database with their associated images.
	 * The directory structure will be as follows, relative to the web root (public folder):
	 * 			Directory containing modules						./modules/	
	 * 			Directory for zip upload and extraction				./modules/MODULE_NAME/
	 * 				* note this directory is created and the name of the zip file is used as the directory name
	 * 			Location of xml file containg QuestionSet			./modules/MODULE_NAME/MODULE_NAME.xml
	 * 			Directory of images associated with questions		./modules/MODULE_NAME/images
	 *
	 * 	The directory structure must be maintained for this to work. This will be easier to maintaine
	 * 		when the QuestionSets can be created and exported programmatically
	 */ 	
	
	
	
	$USER = new RadStep\User();
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
	
	$allowedExts = array("zip");
	$filename_explode = explode(".", $_FILES["file"]["name"]); //need this temp variable b/c end arg is by &reference
	$extension = end($filename_explode);
	if (($_FILES["file"]["type"] == "application/zip") && in_array($extension, $allowedExts))
	  {
	  if ($_FILES["file"]["error"] > 0)
	    {
	    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	    }
	  else
	    {
	    	
			$host_dir = RadStep\UPLOAD_DIR . $filename_explode[0] . "/";
			$host_path = $host_dir . $_FILES["file"]["name"];
			
		    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		    echo "Type: " . $_FILES["file"]["type"] . "<br>";
		    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		
	    	if (file_exists($host_path))
	    	{
		    	echo $host_path . " already exists. ";
		    }
		    else
			{
				//SUCCESSFUL UPLOAD
				
				//MOVE TO ./modules/FILENAME directory
				if(!is_dir($host_dir)){
					 mkdir($host_dir); 
					//chmod($host_dir,0664);
				}
				
				move_uploaded_file($_FILES["file"]["tmp_name"], $host_path);
				echo "Stored in: " . $host_path . "<br />";
				
				//UNZIP to folder
				$zip_file = new ZipArchive();
				$res = $zip_file->open($host_path);
				if ($res === TRUE) {
				    $zip_file->extractTo($host_dir);
				    $zip_file->close();
				    echo "Unzipped to:" . $host_dir;
				
				/* parse xml to empty QuestionSet object and add to database TODO: create function of each class to add new object to db 
				 * 
				 * parse xml of questions and add to database TODO: create function of each class to add new object to db 
				 * 		
				 */
				//FIND XML FILE, must be same name as outside folder
				$xml = simplexml_load_file($host_dir.$filename_explode[0].".xml");
				
				//Get QuestionSet node
				$node_questionSet = $xml;//$xml->xpath("/QuestionSet");
				$obj_questionSet = new RadStep\QuestionSet();
				
				$obj_questionSet->name = (string)$node_questionSet["name"];
				$obj_questionSet->created_by = $USER->username;
				$obj_questionSet->created_datetime = date(DateTime::ISO8601); //formats date/time according to ISO8601
				$obj_questionSet->keywords = (string)$node_questionSet["keywords"];
				$obj_questionSet->difficulty = (string)$node_questionSet["difficulty"];
				
				$node_questions = $xml->xpath("/QuestionSet/Question");
				foreach($node_questions as $index => $question)
				{
					//create Question object
					$obj_question = new RadStep\Question();
					$obj_question->created_by = $USER->username;
					$obj_question->created_datetime = date(DateTime::ISO8601);
					$obj_question->prompt = (string)$question->Prompt;
					$obj_question->images = array();
					$node_images = $question->xpath("Image");
					//$obj_question->image_captions = array();
					foreach($node_images as $image){
						//append path to module to the relative url given in the xml file
						$obj_question->images[] = array( 	"url" => $host_dir.(string)$image["url"], 
															"caption" => (string)$image["caption"] );
					}
					
					$obj_question->multiple_choices = array();
					$node_choices = $question->xpath("Choice");
					foreach($node_choices as $choiceindex => $choice){
						$obj_question->multiple_choices[] = array( 
															"choice" => $choiceindex, 
															"caption" => (string)$choice->Text,
															"explanation" => (string)$choice->Explanation,
															"correct" => (bool)$choice["correct"] );
					}
					
					$obj_question->explanation = (string)$question->Explanation;
					$obj_question->keywords = (string)$question["keywords"];
					$obj_question->difficulty = (string)$question["difficulty"];
					
					//add object to db (autogenerates and updates question_id, updates json according to vars)
					$obj_question->addInstanceToDb();
					
					//append question_id to $obj_questionSet=>questions[]
					$obj_questionSet->questions[] = $obj_question->question_id;
					
				}
				
				//add $obj_questionset add to database
				$obj_questionSet->addInstanceToDb();
				
					
				}else 
				{
			    	echo "Failed to unzip file."; 
				}//endif upload of file succeeded, unzip filed
				
			}//endif file already exists, upload failed
	    }//endif file upload error
	  }//endif not a zip file
	else
	{
		echo "Invalid file. Must be a properly formatted zip file.";
	}//endifelse not a zip file

}//endif !$USER->authenticated
	

	

?>