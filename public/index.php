<?php

	require_once("./include/radstep.php");
	require_once("./include/user.php");
	$USER = new User();
	
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
		<meta charset="utf-8"/>
		
		<title>RadSTEP</title>
	
	
	<link rel="stylesheet" type="text/css" href="css/default.css" />
	<script type="text/javascript" src="lib/jquery-1.8.3.min.js"></script>
	
	<!-- from rsauth package...just the styling -->
	<link rel="stylesheet" type="text/css" href="css/rsauth.css" />
    
    <!-- from user.php authentication class -->
    <!-- <link rel="stylesheet" type="text/css" href="css/userstyle.css"></link>-->
    <script type="text/javascript" src="lib/sha1.js"></script>
	<script type="text/javascript" src="lib/user.js"></script>
	
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
	$(document).ajaxError(function(event, xhr, settings, exception){ 
			alert('error in: ' + settings.url + ' \n'+'error:' + xhr.responseText ); 
	});
	
	
	
	}); //close jquery(document).ready
	
	</script>
	
	
	</head>
	
	
	<body style="margin:0 auto; padding:0px;">


	<div id="div_mainpage">
	
	<div id="div_header" style="text-align:center;margin:0 auto;">
		<h1 style="text-align:center;letter-spacing: 1.1em;">RadSTEP</h1>
		<h3 style="letter-spacing: 1em; font-variant: small-caps;">alpha build</h3>
	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">

		<div id="div_login">

		
		
		<?php

			if(!$USER->authenticated) { ?>
				
			<div id='div_rsauthform'>
			<!-- Allow a user to log in -->
			<form class="controlbox" name="log in" id="login" action="index.php" method="POST">
				
				<fieldset >
				<legend>Login</legend>
		
				<input type="hidden" name="op" value="login"/>
				<input type="hidden" name="sha1" value=""/>
				
				<div class="container">
				<label for="username" >Username/E-mail*:</label><br/>
				<input type="text" name="username" maxlength="50" value="" style="border:solid 1px #888; height:32px;font:18px sans-serif;"  />
				</div>
				
				<div class="container">
				<label for="password1" >Password*:</label><br/>
		    	<input type="password" name="password1" value=""  maxlength="50" style="border:solid 1px #888; height:32px;font:18px sans-serif;" />
				</div>
				
				<div class="container">
				<input type="button" value="log in" onclick="User.processLogin()"/>
				</div>
				
				<div class="short_explanation"><a href="resetpwd.php">Forgot Password?</a></div>
				
				</fieldset>
				
				<div><span class="error">
				<?php if($USER->error!="") { ?>
				Error: <?php echo $USER->error; ?>
				<?php } ?>
				</span></div>
				
				
				
			</form>
		
		</div><!--div_rsauthform-->
		
		<?php 		} //endif(!$USER->authenticated) 
		else{ 
			
			// $USER->authenticated == true 
			// then redirect to usermain.php
			redirect("./usermain.php");
		}?>
		
		</div><!--CLOSE div_login-->
		
		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug">
		
	</div>
		

		
	</body>
</html>