<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	$USER = new RadStep\RadStepUser();

	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
		
	$is_faculty = (strpos($USER->role, "Faculty") !== false);
	$is_resident = (strpos($USER->role, "Resident") !== false);
	
	$username = $USER->username;
	$assignments_assigned_to_complete = array();
	$assignments_assigned_to_incomplete = array();
	$assignments_assigned_by_complete = array();
	$assignments_assigned_by_incomplete = array();
	
	if($is_faculty)
	{

		foreach ($USER->getAssignmentsAssignedByMe() as $assignment_id)
		{
			$assignment = new RadStep\Assignment($assignment_id);
			if($assignment->status == RadStep\Assignment::COMPLETE)
				$assignments_assigned_by_complete[] = $assignment;
			else 
				$assignments_assigned_by_incomplete[] = $assignment;

		}
		
	}
	
	if($is_resident)
	{

		foreach ($USER->getAssignmentsAssignedToMe() as $assignment_id)
		{
			//echo($assignment_id);
			$assignment = new RadStep\Assignment($assignment_id);
			if($assignment->status == RadStep\Assignment::COMPLETE)
				$assignments_assigned_to_complete[] = $assignment;
			else 
				$assignments_assigned_to_incomplete[] = $assignment;

		}
		
	}//endif is_resident
	
	}//endelse not authenticated
?> 


<html>
	<head>
	<head>
		<meta charset="utf-8"/>
		
		<title>RadSTEP</title>
	
	
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="lib/jquery-1.9.1.min.js"></script>
	
	<link href="css/smoothness/jquery-ui-1.10.1.min.css" rel="stylesheet" type="text/css" />
	<script src="lib/jquery-ui-1.10.1.custom.min.js"></script>
	
	<script type="text/javascript" src="lib/jquery.form.js"></script>

	<script type="text/javascript" src="lib/timepicker/jquery.timepicker.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="lib/timepicker/jquery.timepicker.css" />
	
	<!-- USE jquery +  jqueryui CDNs
		
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<link href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
		
	-->
	
	<!-- from user.php authentication class -->
    <!-- <link rel="stylesheet" type="text/css" href="css/userstyle.css"></link>-->
    <script type="text/javascript" src="include/RadStep/sha1.js"></script>
	<script type="text/javascript" src="include/RadStep/user.js"></script>

	<link href="css/rsauth.css" rel="stylesheet" type="text/css" />
	
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<style type="text/css">
		
		.assignments_table
		{
			font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
			font-size:0.9em;
			width:100%;
			border-collapse:collapse;
		}
		.assignments_table td, .assignments_table th 
		{
			border:1px solid #666666;
			padding:3px 7px 2px 7px;
			color:#444;
			background-color: #DDDDDD;
		}
		.assignments_table th 
		{
			background-color:#6D7B8D;
			color:#ffffff;
		}
		.assignments_table tr.alt td 
		{
			color:#000000;
			background-color:#FFFFFF;
		}
		
		.debug{ display:none; }
	</style>



	<script type="text/javascript">

	/** GENERAL jquery(document).ready() for all users **/
	jQuery(document).ready(function(){

		//autmatically adds random num to get ajax requests to prevent browser caching	
		$.ajaxSetup({ cache: false });
		
		//debugging
		$(document).ajaxError(function(event, xhr, settings, exception){ 
			alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
		});
		
	
		$( "#div_tabs" ).tabs();
	
	}); //close jquery(document).ready
	
	</script>
	
	
	
	<?php if($is_resident) { ?>
	<script type="application/javascript">
		/** RESIDENT jquery(document).ready() **/
		jQuery(document).ready(function(){
			$( "#accordion_resident" ).accordion({heightStyle: "content"});
		});
	</script><?php } //endif resident ?>
	
	
	<?php if($is_faculty) { ?>
	<script type="application/javascript">
		/** FACULTY jquery(document).ready() **/
		jQuery(document).ready(function(){
			
			$( "#accordion_faculty" ).accordion({heightStyle: "content"});
		
			//SETUP ajax file upload for import question set
			var bar = $('.bar');
			var percent = $('.percent');
			$("#form_import_questionset").ajaxForm({
			    beforeSend: function() {
			        $('#import_questionset_status').empty();
			        var percentVal = '0%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
			    uploadProgress: function(event, position, total, percentComplete) {
			        var percentVal = percentComplete + '%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
			    success: function() {
			        var percentVal = '100%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
				complete: function(xhr) {
					$('#import_questionset_status').html(xhr.responseText);
				}
			}); 
		    	
		    
		    // SETUP create assignment form
			$("#form_create_assignment").ajaxForm({
			    target: "#create_assignment_status", //where to send the result
			}); 
			
			$("#datepicker_duedate").datepicker({dateFormat:"D M dd yy"}); // 
				var today = new Date();
				today.setDate(today.getDate()+1); //sets default date to tomorrow
				$("#datepicker_duedate").val(today.toDateString());
				
				$("#timepicker_duedate").timepicker();
				$("#timepicker_duedate").timepicker( 'setTime', new Date()); //sets default time to now
		
		
		}); //close jquery(document).ready
	</script><?php } //endif faculty ?>
	
	
	</head>
	
	
	<body style="width:80%; margin:0 auto; padding:0px;">

	<div id="div_mainpage">
	
	<?php
	
		insertDivHeader();
	
	?>
	
	<div id="div_main" style="margin:0 auto;">
	
		<div id="div_top">
		
		<h2 style="float:left;width:70%"><?php echo $USER->email; ?></h2>
		
		<div id="div_logout" style="float:right;width:10%;margin-top:10px;margin-right:5px;">
			<!-- Log out option -->
			<form name="logout" id="logout" action="usermain.php" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<input type="submit" value="Logout"/>
			</form>
		</div><!-- CLOSE div_logout-->
		
		</div><!-- CLOSE div_top -->
		
		<!-- Tabs -->
		<div id="div_tabs" style="clear:both;">


		
			<ul>
				<?php if ($is_faculty) { ?><li><a href="#tab_faculty">Faculty</a></li><?php } ?>
				<?php if ($is_resident) { ?><li><a href="#tab_resident">Resident</a></li><?php } ?>
				<li><a href="#tab_account">Account Options</a></li> 
			</ul>
			
			<!-- FACULTY TAB -->
			<?php if ($is_faculty) { ?>
			<div id="tab_faculty">
				
				<div id="accordion_faculty">
				<h3>Incomplete asssignments assigned by you</h3>
				<div>
					<!-- FACULTY TAB INCOMPLETE ASSIGNMENTS TABLE -->
					<table id="tbl_faculty_incomplete" class="assignments_table">
						<tr>
							<th>Assigned To</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>Mode</th>
							<th>QuestionSet Name</th>
							<th>Delete</th>
						</tr>
						<?php foreach($assignments_assigned_by_incomplete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_to); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php 
								switch($assignment->status){
									case RadStep\Assignment::COMPLETE: echo("Completed"); break;
									case RadStep\Assignment::ASSIGNED_NOT_STARTED: echo("Assigned"); break;
									case RadStep\Assignment::ASSIGNED_STARTED: echo("Started"); break;
								}	
							 ?></td>
							<td><?php 
								switch($assignment->assignment_mode){
									case RadStep\Assignment::TEST_MODE: echo("Test"); break;
									case RadStep\Assignment::TUTOR_MODE: echo("Tutor"); break;
								}	
							 ?></td>
							<td><?php echo($assignment->name); ?></td>
							<td><a href="<?php echo("deleteAssignment.php?assignment_id=".$assignment_id); ?>">Delete</a></td>
						</tr>
					
						<?php } ?>
					</table>
					
					
					
				</div>
				<h3>Completed assignments assigned by you</h3>
				<div>
					
					<!-- FACULTY TAB COMPLETE ASSIGNMENTS TABLE -->
					<table id="tbl_faculty_complete" class="assignments_table">
						<tr>
							<th>Assigned To</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>Mode</th>
							<th>QuestionSet Name</th>
							<th>Score</th>
							<th>Review</th>
						</tr>
						<?php foreach($assignments_assigned_by_complete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_to); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php 
								switch($assignment->status){
									case RadStep\Assignment::COMPLETE: echo("Completed"); break;
									case RadStep\Assignment::ASSIGNED_NOT_STARTED: echo("Assigned"); break;
									case RadStep\Assignment::ASSIGNED_STARTED: echo("Started"); break;
								}	
							 ?></td>
							 <td><?php 
								switch($assignment->assignment_mode){
									case RadStep\Assignment::TEST_MODE: echo("Test"); break;
									case RadStep\Assignment::TUTOR_MODE: echo("Tutor"); break;
								}	
							 ?></td>
							<td><?php echo($assignment->name); ?></td>
							<td><?php echo($assignment->score); ?></td>
							<td><a href="<?php echo("assignment.php?assignment_id=".$assignment_id); ?>">Review</a></td>
						</tr>
					
						<?php } ?>
					</table>

				</div>
				<h3>Create Assignment</h3>
				<div id="div_create_assignment">
					<form id="form_create_assignment" action="createassignment.php" method="post" >
						Assign To: 
							<select id="select_assigned_to" name="assigned_to">
							<?php foreach(RadStep\getAllResidents() as $resident){ ?>		
								<option><?php echo($resident); ?></option>
							<?php } ?>
							</select>
						<br />
						Due Date:
							<input type="text" id="datepicker_duedate" name="date_duedate" />
							<input type="text" id="timepicker_duedate" name="time_duedate" class="time" />

						<br />
						Module/QuestionSet:
							<select id="select_questionset" name="questionset_id">
							<?php 
							foreach(RadStep\getAllQuestionSets() as $key => $questionset){ ?>		
								<option value="<?php echo($key)?>"><?php echo($questionset); ?></option>
							<?php } ?>
							</select>
						<br />
							Test Mode<input type="radio" id="radio_assignment_mode_test" name="assignment_mode" value="<?php echo RadStep\Assignment::TEST_MODE ?>" checked="checked" />
							Tutor Mode<input type="radio" id="radio_assignment_mode_tutor" name="assignment_mode" value="<?php echo RadStep\Assignment::TUTOR_MODE ?>" />
						<div>
						<input type="submit" name="submit" value="Create" id="btn_submit_createassignment" />
						</div>
						<div id="create_assignment_status"></div>
					</form>
				</div><!--CLOSE div_import_questionset-->
				
				
				<h3>Import Question Set</h3>
				<div id="div_import_questionset">
					<!--TODO: Make this an AJAX style upload 
						many solutions, see http://stackoverflow.com/questions/2320069/jquery-ajax-file-upload -->
					<form id="form_import_questionset" action="importquestionset.php" accept="application/x-zip-compressed,application/zip" method="post" enctype="multipart/form-data">
						<input type="file" name="file" style="width:400px" id="file_import_questionset" />
						<br />
						<div>
						<input type="submit" name="submit" id="btn_submit_importquestionset" value="Import"/>
						</div>
						
						<style type="text/css">
							.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
							.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
							.percent { position:absolute; display:inline-block; top:3px; left:48%; }
						</style> 
						<div class="progress">
					        <div class="bar"></div >
					        <div class="percent">0%</div >
					    </div>
					    <div id="import_questionset_status"></div>
						
						
					</form>
				</div><!--CLOSE div_import_questionset-->
				
				<h3>Results</h3>
				<div>
				<form id="form_get_results">
					<select id="select_get_results_for" name="resident_username">
					<?php foreach(RadStep\getAllResidents() as $resident){ ?>		
						<option><?php echo($resident); ?></option>
					<?php } ?>
					</select>
				</form>
				
				<div id="div_faculty_results_resident"></div>
				
				</div><!--CLOSE accordion_faculty -->
				
			</div><!--CLOSE tab_faculty -->
			<?php } ?>
			
			
			<!-- RESIDENT TAB -->
			<?php if ($is_resident) { ?>
			<div id="tab_resident">

				<div id="accordion_resident">
				<h3>Incomplete Assignments</h3>
				<div>
					<!-- RESIDENT TAB INCOMPLETED ASSIGNMENTS TABLE -->
					<table id="tbl_resident_incomplete" class="assignments_table">
						<tr>
							<th>Assigned By</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>Mode</th>
							<th>QuestionSet Name</th>
							<th>Start</th>
						</tr>
						<?php 
						
						foreach($assignments_assigned_to_incomplete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_by); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php 
								switch($assignment->status){
									case RadStep\Assignment::COMPLETE: echo("Completed"); break;
									case RadStep\Assignment::ASSIGNED_NOT_STARTED: echo("Assigned"); break;
									case RadStep\Assignment::ASSIGNED_STARTED: echo("Started"); break;
								}	
							 ?></td>
							 <td><?php 
								switch($assignment->assignment_mode){
									case RadStep\Assignment::TEST_MODE: echo("Test"); break;
									case RadStep\Assignment::TUTOR_MODE: echo("Tutor"); break;
								}	
							 ?></td>
							<td><?php echo($assignment->name); ?></td>
							<td><a href="<?php echo("assignment.php?assignment_id=".$assignment_id); ?>">Start</a></td>
						</tr>
					
						<?php } ?>
					</table>
					
					
					
					</div>
				<h3>Completed Assignments</h3>
				<div>
					<!-- RESIDENT TAB COMPLETED ASSIGNMENTS TABLE -->
					<table id="tbl_resident_complete" class="assignments_table">
						<tr>
							<th>Assigned By</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>Mode</th>
							<th>QuestionSet Name</th>
							<th>Score</th>
							<th>Review</th>
						</tr>
						<?php 
						
						foreach($assignments_assigned_to_complete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_by); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php 
								switch($assignment->status){
									case RadStep\Assignment::COMPLETE: echo("Completed"); break;
									case RadStep\Assignment::ASSIGNED_NOT_STARTED: echo("Assigned"); break;
									case RadStep\Assignment::ASSIGNED_STARTED: echo("Started"); break;
								}	
							 ?></td>
							 <td><?php 
								switch($assignment->assignment_mode){
									case RadStep\Assignment::TEST_MODE: echo("Test"); break;
									case RadStep\Assignment::TUTOR_MODE: echo("Tutor"); break;
								}	
							 ?></td>
							<td><?php echo($assignment->name); ?></td>
							<td><?php echo($assignment->score); ?></td>
							<td><a href="<?php echo("assignment.php?assignment_id=".$assignment_id); ?>">Review</a></td>
						</tr>
					
						<?php } ?>
					</table>
				</div>
				
				<h3>Results</h3>
				<div>Generate some sort of graphical depiction of resident's overall results.</div>
				</div><!--CLOSE accordion_resident -->
				
			</div><!--CLOSE tab_resident -->
			<?php } ?>
			
			<!-- ACCOUNT MGMT for EVERY USER -->
			<div id="tab_account">
			
			<!-- Log out option -->
			<h3>Logout</h3>
			<form class="controlbox" name="log out" id="logout" action="usermain.php" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username" value="<?php echo $_SESSION["username"]; ?>" />
				<input type="submit" value="Logout"/>
			</form>
			
			<!-- Request a new password from the system -->
			<h3>Reset Password</h3>
			<form class="controlbox" name="forgotten passwords" id="reset" action="usermain.php" method="POST">
				<input type="hidden" name="op" value="reset"/>
				<input type="hidden" name="email" value="<?php echo $USER->email; ?>" />
				<input type="submit" value="Reset Password"/>
			</form>


			<!-- If a user is logged in, her or she can modify their email and password -->
			<h3>Update Email Address and Password</h3>
			<form class="controlbox" name="update" id="update" action="usermain.php" method="POST">
				<input type="hidden" name="op" value="update"/>
				<input type="hidden" name="sha1" value=""/>
				<p>Update your email address and/or password here</p>
				<table>
					<tr><td>email address </td><td><input type="text" name="email" value="<?php echo $USER->email; ?>" /></td></tr>
					<tr><td>new password </td><td><input type="password" name="password1" value="" /></td></tr>
					<tr><td>new password (again) </td><td><input type="password" name="password2" value="" /></td></tr>
				</table>
				<input type="button" value="Update" onclick="User.processUpdate()"/>
			</form>

			<!-- If a user is logged in, they can elect to unregister -->
			<h3>Unregister/Delete Account</h3>
			<h4>WARNING: CANNOT BE UNDONE</h4>
			<form class="controlbox" name="unregister" id="unregister" action="index.php" method="POST">
				<input type="hidden" name="op" value="unregister"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<input type="submit" value="Unregister"/>
			</form>
			</div><!--CLOSE div tab_account -->
			
			
		</div><!--CLOSE div_tabs -->
		
	</div><!--CLOSE tab_account-->

		
	</div><!-- div_mainpage -->


	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

	</body>
</html>