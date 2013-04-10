<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	$USER = new RadStep\User();
	
	/**
	 * review_mode determined by whether or not the assignment is completed or if the person 
	 * that assigned it is viewing the assignment
	 * NOTE: this is separate from assignment_mode which is a property of the assignment, whether
	 * it was assigned as an actual test or just a learning tool
	 */
	
	$review_mode = false;
	
	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
	
		$is_faculty = (strpos($USER->role, "Faculty") !== false);
		$is_resident = (strpos($USER->role, "Resident") !== false);
		
		$assignment_id=$_GET["assignment_id"];
		$assignment = new RadStep\Assignment($assignment_id);
		
			
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
			
			$assignment_mode = $assignment->assignment_mode;
			$question_list = $assignment->questions;	
		
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
		
			#div_question_list{
				float:left;
				width:80px
			}
			#div_nav_btns_left{
				float:left;
				width:80px;
				margin:2px;
			}
			
			#div_question_box{
				float:left;
				width:40em;
				margin-left: 10px;
    			margin-top: 2px;

			}

			#div_nav_btns_top{
				float:left;
				margin-top:0px;
				margin-bottom:10px;
				width:100%;
			}
			
			.divbtn_side_nav{
				height:20px;
				vertical-align:middle;
				cursor:pointer;
			}
			.divbtn_top_nav{
				height:20px;
				vertical-align:middle;
				cursor:pointer;
			}
			.divbtn_end_exam{
				
				background-color:#797595;
				color:white;
				text-align:center;
			}
			.divbtn_next_q{
				background-color:#233C5C;
				color:white;
				text-align:center;
			}
			.divbtn_prev_q{
				background-color:#58606A;
				color:white;
				text-align:center;
				
			}
			#ul_question_list{
				list-style: none;
				width:100%;
				padding: 0;
				margin: 0 auto;
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

	/**************
	 * declare and assign vars from php
	 */
	var assignment_id = '<?php echo $assignment->assignment_id ?>';
	var review_mode = Boolean('<?php echo $review_mode ?>');
	var question_id_list = [];
	

	jQuery(document).ready(function(){
	
	/** INITIALIZATION **/
	//autmatically adds random num to get ajax requests to prevent browser caching	
	$.ajaxSetup({ cache: false });
	
	//debugging
	$(document).ajaxError(function(event, xhr, settings, exception){ 
		alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
	});
	
	//question_id_list is populated by php (see #ul_question_list)
	var current_question_index = 0;
	var current_question_id = question_id_list[current_question_index];

	// load first question
	loadQuestion(current_question_id, assignment_id, review_mode);

	
	$("#ul_question_list li").click(function(){
		
		var question_index = $(this).index();
		var question_id = $(this).attr("id").replace("q_",""); //removes the prefix added to each question id
		
		/*TODO: don't rely on hack of getting the actual question id from the tag's id */
		//var question_id = current_question_id;
		
		loadQuestion(question_id, assignment_id, review_mode);

	});
	
	$(".divbtn_end_exam").click(function(){
		
		//mark assignment as complete
		$.post("endexam.php", { assignment_id: assignment_id },  
			function(data){
				$("#div_question").html(data);
			}).success(
				function(){ 
					$.delay(3000)
					//redirect to usermain
					window.location.href = "usermain.php" 
				}
			);

	});
	
	$(".divbtn_next_q").click(function(){
		var goto_question = current_question_index+1;
		if(goto_question < question_id_list.length)
		$("#ul_question_list > li:eq( " + goto_question + ")").click();	
	});
	
	$(".divbtn_prev_q").click(function(){
		var goto_question = current_question_index-1;
		if(goto_question >= 0)
			$("#ul_question_list > li:eq( " + goto_question + ")").click();	
	});
	 
	/**
	 * Loads a question into the div element #div_question
	 * - question_id: question_id to be loaded from the database
	 * - assignment_id: currently loaded assignment to load the question from
	 * - review_mode: whether or not the assignment is currently loaded in review mode or not
	 * 					of note, question.php will decide if the assignment is assigned as tutor mode or not
	 * 					and display the question appropriately
	 * * @typedef {{question_id: number, assignment_id: number, review_mode: bool }}
	 */
	function loadQuestion(question_id, assignment_id, review_mode){

		/*TODO: SECURITY BUG; this is not the safest way of calling the question since a smart person could modify
		 * the question_url to get to review mode
		 */ 
		 var question_url = "";
		 if(review_mode) question_url = "questionreview.php";
		 else question_url = "question.php";
		 
		 $("#div_question").load(question_url, 
		 	{	"question_id": question_id , 
		 		"assignment_id": assignment_id
		 	}); 
			

		
		//get index of question in question_id_list	
		var question_index = question_id_list.indexOf(question_id);

		//set all li as inactive
		$("#ul_question_list li").removeClass("active");
		
		//set only li at index as active
		$("#ul_question_list > li:eq( " + question_index + ")").addClass("active");
		
		current_question_index = question_index;
			
	}// close function loadQuestion
	
	
	
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
			<!-- TODO: TIMER at top of total time elapsed while on this page, auto end exam assignment if exceeds timelimit -->	
			<?php foreach($question_list as $question_number => $question_id){ ?>
				<li id="<?="q_".$question_id ?>"><?= $question_number+1 ?></li>
				<script type="application/javascript">
					question_id_list.push( '<?= $question_id ?>' );
				</script>
			<?php } ?>
			
			</ul>
			
			<div id="div_nav_btns_left">
				<div id="divbtn_prev_q_side" class="divbtn_side_nav divbtn_prev_q" style="width:48%;float:left;">Prev</div>
				<div id="divbtn_next_q_side" class="divbtn_side_nav divbtn_next_q" style="width:48%;float:left;">Next</div>
				<div style="clear:both; height:2px;"></div>
				<div id="divbtn_end_side" class="divbtn_side_nav divbtn_end_exam" style="width:96%;">End Exam</div>
			</div><!--close div_nav_btns_left-->
		
		</div><!--CLOSE div_question_list-->
		

		
		<div id="div_question_box">
		
			<div id="div_nav_btns_top">
				<!-- <div id="divbtn_end_top" class="divbtn_top_nav divbtn_end_exam" style="width:96%;">End Exam</div>
				<div style="clear:both;height:10px;"></div>-->
				<div id="divbtn_prev_q_top" class="divbtn_top_nav divbtn_prev_q" style="width:48%;float:left;">Previous Question</div>
				<div id="divbtn_next_q_top" class="divbtn_top_nav divbtn_next_q" style="width:48%;float:left;">Next Question</div>
				
				
			</div><!--close div_nav_btns_top-->
			
			<!-- Question -->
			<div id="div_question">
			</div>
		</div><!--close div_question_box-->
		
	
		
	</div><!--close div_main-->

		
	</div><!--close div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
