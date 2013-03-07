<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	$USER = new RadStep\RadStepUser();
	
?>

<!DOCTYPE html>
 
<html>
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="description" content="Radiology Training Application" />
		<meta name="keywords" content="radiology, education" />
		<meta name="author" content="Thomas O'Neill c/o TTUHSC El Paso" />
		<meta name="designer" content="TxJxO" />
		<meta name="robots" content="index, follow" />
		<meta name="googlebot" content="index, follow" />
		<title>RadSTEP</title>
		
		
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<script type="text/javascript" src="lib/jquery-1.9.1.min.js"></script>
		<!-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
		
		<!-- from rsauth package...just the styling -->
		<link rel="stylesheet" type="text/css" href="css/rsauth.css" />
		
		<!-- from user.php authentication class -->
		<!-- <link rel="stylesheet" type="text/css" href="css/userstyle.css"></link>-->
		<script type="text/javascript" src="include/RadStep/sha1.js"></script>
		<script type="text/javascript" src="include/RadStep/user.js"></script>
		
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<style type="text/css">
			.debug{ display:none; }
		</style>



	<script type="text/javascript">

	jQuery(document).ready(function(){
	
	/** INITIALIZATION **/
	
	/*
	
	//autmatically adds random num to get ajax requests to prevent browser caching	
	$.ajaxSetup({cache:false}); 
	
	//debugging
	$(document).ajaxError(function(event, xhr, settings, exception){ 
			alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
	});
	*/
	
	
	
	$("#txtbox_username").keyup(function(event){
    if(event.keyCode == 13)
    {
    	
        $("#btn_login").click();
    }});
	$("#txtbox_password").keyup(function(event){
    if(event.keyCode == 13)
    {
        $("#btn_login").click();
    }});
	
	}); //close jquery(document).ready
	
	</script>
	
	
	</head>
	
	
	<body style="margin:0 auto; padding:0px;">


	<div id="div_mainpage">
	
	<?php
	
		insertDivHeader();
	
	?>
	
	<div id="div_main" style="margin:0 auto;">

		<div id="div_login">

		
		
		<?php

			if(!$USER->authenticated) { ?>
				
			<div id="div_rsauthform">
			<!-- Allow a user to log in -->
			<form class="controlbox" name="login" id="login" action="index.php" method="POST">
				
				<fieldset>
				<legend>Login</legend>
		
				<input type="hidden" name="op" value="login"/>
				<input type="hidden" name="sha1" value=""/>
				
				<div class="container">
				<label for="username">E-mail Address (Username)*:</label><br/>
				<input id="txtbox_username" type="text" name="username" maxlength="50" value="" style="border:solid 1px #888; height:32px;font:18px sans-serif;"  />
				</div>
				
				<div class="container">
				<label for="password1" >Password*:</label><br/>
		    	<input id="txtbox_password" type="password" name="password1" value="" style="border:solid 1px #888; height:32px;font:18px sans-serif;" />
				</div>
				
				<div class="container">
				<!--use input type="button" instead of "submit" b/c want to client-side validate before submitting form -->
				<input id="btn_login" type="button" value="Login" onclick="User.processLogin()"/>
				</div>
				
				<div class="short_explanation"><a href="resetpwd.php">Forgot Password...</a></div>
				<div class="short_explanation"><a href="register.php">Register...</a></div>
				
				<div id="div_clientSideError" class="container error"></div>
				

				
				<div><span class="error">
				<?php if($USER->error!="") { ?>
				Error: <?php echo $USER->error; ?>
				<?php } ?>
				</span></div>
				
				</fieldset>
				
			</form>
		
		</div><!--div_rsauthform-->
		
		<?php 		} //endif(!$USER->authenticated) 
		else{ 
			
			// $USER->authenticated == true 
			// then redirect to usermain.php
			redirect("usermain.php");
		}?>
		
		</div><!--CLOSE div_login-->
		
		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug">
		
	</div>
		

		
	</body>
</html>