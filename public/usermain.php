<?php

	require_once("./include/radstep.php");
	require_once("./include/user.php");
	$USER = new User();

	
	if(!$USER->authenticated){
		redirect("./index.php");
		exit;
	}else{
	
	$is_faculty = (strpos("Faculty",$USER->role) !== false);
	$is_resident = (strpos("Resident",$USER->role) !== false);
	
	}
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="description" content="Radiology Training Application" />
		<meta name="keywords" content="radiology, education" />
		<meta name="author" content="Thomas O'Neill c/o TTUHSC El Paso" />
		<meta name="designer" content="TxJxO" />
		<meta name="robots" content="index, follow" />
		<meta name="googlebot" content="index, follow" />
		
		<title>RadSTEP</title>
	
	
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<script src="lib/jquery-1.8.3.min.js" type="text/javascript"></script>
	<link href="css/smoothness/jquery-ui-1.9.2.min.css" rel="stylesheet" type="text/css" />
	<script src="lib/jquery-ui-1.9.2.min.js"></script>
	<link href="css/rsauth.css" rel="stylesheet" type="text/css" />
	
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<style type="text/css">
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
	
	$( "#accordion_faculty" ).accordion();
	$( "#accordion_resident" ).accordion();
	
	}); //close jquery(document).ready
	
	</script>
	
	
	</head>
	
	
	<body style="width:80%; margin:0 auto; padding:0px;">

	<div id="div_mainpage">
	
	<div id="div_header" style="text-align:center;margin:0 auto;">
		<h1 style="text-align:center;letter-spacing: 15px;">RadSTEP</h1>
		<h3 style="letter-spacing: 10px; font-variant: small-caps;">alpha build</h3>
	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">
	
		<h2>Welcome, <? echo $USER->email ?> </h2>

		<!-- Tabs -->
		<div id="div_tabs">
			<!-- Log out option -->
			<div id="div_logout" style="float:right;margin-top:5px;margin-right:10px;">
			<form class="controlbox" name="log out" id="logout" action="index.php" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<input type="submit" value="Logout"/>
			</form>
			</div><!--div_logout-->
			
			<ul>
				<li><a href="#tab_resident">Resident</a></li>
				<?php if ($is_faculty) { ?> <li><a href="#tab_faculty">Faculty</a></li> <?php } ?>
				<?php if ($is_resident) { ?> <li><a href="#tab_account">Account Options</a></li> <?php } ?>
			</ul>
			
			<!-- FACULTY TAB -->
			<?php if ($is_faculty) { ?>
			<div id="tab_faculty">
				
				<div id="accordion_faculty">
				<h3>Incomplete Assignments</h3>
				<div>List of last 10 assignments with percent complete, due date, who it is assigned to and option to edit/delete assignment.</div>
				<h3>Completed Assignments</h3>
				<div>List of last 10 completed assignments with date completed, who completed it and score/percent correct.</div>
				<h3>Create Assignment</h3>
				<div>Quick assignment by allowing selection of resident, due date and module to assign and link to advanced assignment editor.</div>
				</div>

			</div>
			<?php } ?>
			
			
			<!-- RESIDENT TAB -->
			<?php if ($is_resident) { ?>
			<div id="tab_resident">

				<div id="accordion_resident">
				<h3>Incomplete Assignments</h3>
				<div>List of assignments with percent complete, due date and who assigned it.</div>
				<h3>Completed Assignments</h3>
				<div>List of completed assignments with score/percent correct listed.</div>
				<h3>Results</h3>
				<div>Generate some sort of graphical depiction of resident's overall results.</div>
				</div>
			
			</div>
			<?php } ?>
			
			<!-- ACCOUNT MGMT for EVERY USER -->
			<div id="tab_account">
			
			<!-- Log out option -->
			<h3>Logout</h3>
			<form class="controlbox" name="log out" id="logout" action="index.php" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username" value="<?php echo $_SESSION["username"]; ?>" />
				<input type="submit" value="Logout"/>
			</form>
			
			<!-- Request a new password from the system -->
			<h3>Reset Password</h3>
			<form class="controlbox" name="forgotten passwords" id="reset" action="index.php" method="POST">
				<input type="hidden" name="op" value="reset"/>
				<input type="hidden" name="email" value="<?php $USER->email; ?>" />
				<input type="submit" value="Reset Password"/>
			</form>


			<!-- If a user is logged in, her or she can modify their email and password -->
			<h3>Update Email Address and Password</h3>
			<form class="controlbox" name="update" id="update" action="index.php" method="POST">
				<input type="hidden" name="op" value="update"/>
				<input type="hidden" name="sha1" value=""/>
				<p>Update your email address and/or password here</p>
				<table>
					<tr><td>email address </td><td><input type="text" name="email" value="<?php $USER->email; ?>" /></td></tr>
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
			</div>
		</div><!--CLOSE div_tabs -->

		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
