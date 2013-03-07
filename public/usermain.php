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

	jQuery(document).ready(function(){
	
	/** INITIALIZATION **/
	
	
	//autmatically adds random num to get ajax requests to prevent browser caching	
	$.ajaxSetup({ cache: false });
	
	//debugging
	$(document).ajaxError(function(event, xhr, settings, exception){ 
		alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
	});
	

	$( "#div_tabs" ).tabs();
	
	$( "#accordion_faculty" ).accordion({heightStyle: "content"});
	$( "#accordion_resident" ).accordion({heightStyle: "content"});
	
	}); //close jquery(document).ready
	
	</script>
	
	
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
							<th>QuestionSet Name</th>
							<th>Delete</th>
						</tr>
						<?php foreach($assignments_assigned_by_incomplete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_to); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php echo($assignment->status); ?></td>
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
							<th>QuestionSet Name</th>
							<th>Review</th>
						</tr>
						<?php foreach($assignments_assigned_by_complete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_to); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php echo($assignment->status); ?></td>
							<td><?php echo($assignment->name); ?></td>
							<td><a href="<?php echo("assignment.php?assignment_id=".$assignment_id); ?>">Review</a></td>
						</tr>
					
						<?php } ?>
					</table>

				</div>
				<h3>Create Assignment</h3>
				<div>Quick assignment by allowing selection of resident, due date and module to assign and link to advanced assignment editor.</div>
				
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
					</form>
				</div><!--CLOSE div_import_questionset-->
				
				
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
							<th>Assigned To</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>QuestionSet Name</th>
							<th>Start</th>
						</tr>
						<?php 
						
						foreach($assignments_assigned_to_incomplete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_by); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php echo($assignment->status); ?></td>
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
							<th>Assigned To</th>
							<th>Assigned On</th>
							<th>Status</th>
							<th>QuestionSet Name</th>
							<th>Review</th>
						</tr>
						<?php 
						
						foreach($assignments_assigned_to_complete as $assignment){ ?>
					
						<tr>
							<td><?php echo($assignment->assigned_by); ?></td>
							<td><?php echo($assignment->assigned_datetime); ?></td>
							<td><?php echo($assignment->status); ?></td>
							<td><?php echo($assignment->name); ?></td>
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