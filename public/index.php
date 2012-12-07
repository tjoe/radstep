<?php
	
	//INCLUDE anything

	
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta name="charset" content="utf-8" />
		<meta name="description" content="Radiology Training Application" />
		<meta name="keywords" content="radiology, education" />
		<meta name="author" content="Thomas O'Neill c/o TTUHSC El Paso" />
		<meta name="designer" content="TxJxO" />
		<meta name="robots" content="index, follow" />
		<meta name="googlebot" content="index, follow" />
		
		<title>RadSTEP</title>
	
	
	<link rel="stylesheet" type="text/less" href="css/default.less" />
	<script type="text/javascript" src="lib/less-1.3.1.min.js"></script>
	
	<script type="text/javascript" src="lib/jquery/jquery-1.8.3.min.js"></script>
	
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
	$.ajaxSetup({cache:false}); 
	
	//debugging
	$(document).ajaxError(function(event, xhr, settings, exception) { 
	alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText );
	}); 

	// Constants/Variables
	var viewportWidth = $(window).width();
	var viewportHeight = $(window).height();
	
	</script>
	
	
	</head>
	
	
	<body style="margin:0 auto; padding:0px;">


	<div id="div_mainpage">
	
	<div id="div_main" style="margin:0 auto;">
		<h1 style="text-align:center;letter-spacing: 8px;">RadSTEP</h1>
		<div id="div_login" style="width:50%; margin:0 auto;">
			<form id="form_check_pass" method="post" action="login.php" accept-charset="UTF-8" >
				
				<fieldset>
				<legend>Login</legend>
				
				<label for="txtbox_username">Email Address/Username</label><br />
				<input id="txtbox_username" name="txtbox_username" type="text" style="border:solid 1px #888; height:32px;font:18px sans-serif;" /><br />
				<label for="txtbox_password">Password</label><br />
				<input id="txtbox_password" name="txtbox_password" type="password" style="border:solid 1px #888; height:32px;font:18px sans-serif;" /><br />
				<input id="btn_submit" name="btn_submit" type="submit" />
				</fieldset>
			
			</form>
			<a href="fogot_password.php">Forgot password...</a><br />
			<a href="register.php">Register...</a>

		</div><!--CLOSE div_login-->
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
