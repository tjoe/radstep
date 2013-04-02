<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	$USER = new RadStep\User();
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
	
		$is_faculty = (strpos($USER->role, "Faculty") !== false);
		$is_resident = (strpos($USER->role, "Resident") !== false);
		
		$assignment_id=$_GET["assignment_id"];
		$assignment = new RadStep\Assignment($assignment_id);
		
		$review_mode = false;	
		if($is_faculty || $assignment->status == RadStep\Assignment::COMPLETE)
			$review_mode = true;
		
		//check if USER matches assigned_by or assigned_to field
		if($USER->username == $assignment->assigned_by || $USER->username == $assignment->assigned_to)
		{
		
			if($assignment->status == RadStep\Assignment::ASSIGNED_NOT_STARTED)
			{
				$assignment->started_datetime = date(DateTime::ISO8601);
				$assignment->updateInstanceToDb();
			}
			
			$question_list = $assignment->questions;
	
			//load current question_index
			$current_question = 1;
		
		
		}else{
			echo("Sorry, you are not associated with this assignment");
			
		}//endif username matches assignment
		
	}//endif authenticated
?>

<!DOCTYPE html>
 
<html>
	<head>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta charset="utf-8"/>
		
		<title>RadSTEP</title>
	
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<script type="text/javascript" src="lib/jquery-1.9.1.min.js"></script>
		<!-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
		
		<!-- from rsauth package...just the styling -->
		<link rel="stylesheet" type="text/css" href="css/rsauth.css" />
		
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	
		<style type="text/css">
		
			#div_question{
				float:left;
				width:65em;
				margin-left: 110px;
    			margin-top: 5px;
			}
			#div_question_list{
				float:left;
				width:80px
			}
			#ul_question_list{
				list-style: none;
				width:100%;
			}
			#ul_question_list li{
				height:12pt;
				background-color:#6D7B8D;
				color:#ffffff;
				margin:2px;
				padding: 4px;
				font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
				text-align:center;
				vertical-align: middle;
				cursor:pointer;
			}
			#ul_question_list li:hover {
				background-color:#B4BEC3;
			}
			#ul_question_list li.active {
				background-color:#6D8D7F;
			}
			
			.debug{ display:none; }
		</style>



	<script type="text/javascript">


	jQuery(document).ready(function(){
	
	/** INITIALIZATION **/
	
	//autmatically adds random num to get ajax requests to prevent browser caching	
	$.ajaxSetup({ cache: false });
	
	//debugging
	$(document).ajaxError(function(event, xhr, settings, exception){ 
		alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
	});
	

	
	$("#ul_question_list li").click(function(){
		// var question_index = $(this).index();
		var q_id = $(this).attr("id").replace("q_",""); //removes the prefix added to each question id
		$("#div_question").load("question.php", {"question_id": q_id , "assignment_id": '<?php echo $assignment->assignment_id ?>' }); 
    	
    	//remove active class from all of them
    	$("#ul_question_list li").removeClass("active");
    	//add active class to the one thats actually active
    	$(this).addClass("active");
    	
    	//save responses to currently loaded question form
    	
    	
	});
	 

	
	}); //close jquery(document).ready
	
	</script>
	
	
	</head>
	
	
	<body style="width:80%; margin:0 auto; padding:0px;">

	<div id="div_mainpage">
	
	<?php
	
		insertDivHeader();
	
	?>
	
	<div id="div_main" style="margin:0 auto;">
	
		<div style="clear:both;"></div>
		
		<div id="div_question_list">
		<!-- Question List -->
		<ul id="ul_question_list">
		<!-- TODO: if status = completed = set review mode, if not completed = set exam mode -->
		<!-- TODO: TIMER at top of total time elapsed while on this page, autosubmit assignment if exceeds timelimit -->	
		<?php foreach($question_list as $question_number => $question_id){ ?>
			<li id="<?="q_".$question_id ?>"><?= $question_number+1 ?></li>
		<?php } ?>
		</ul>
		</div><!--CLOSE div_question_list-->
		
		<!-- Question -->
		
		<div id="div_question">
		</div>
		
	
		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
