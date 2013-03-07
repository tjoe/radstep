<?php
	
	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	$USER = new RadStep\User();

?>

<!DOCTYPE html>
 
<html>
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
	<script type="text/javascript" src="lib/jquery-1.9.1.min.js"></script>
	<!-- USE jquery +  jqueryui CDNs
		
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<link href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
		
	-->
	
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
				
			<div id='div_rsauthform'>
			<!-- Request a new password from the system -->
			<form class="controlbox" name="forgotten passwords" id="reset" action="resetpwd.php" method="POST">
				<fieldset>
				<legend>Reset Password</legend>
				
				<input type="hidden" name="op" value="reset"/>
				
				<div class="container">
				<label for="email">E-mail Address (Username)*:</label><br/>
				<input type="text" name="email" value="<?php $USER->email; ?>" />
				</div>
				
				<div class="container">
				<input type="submit" value="Reset Password"/>
				</div>
				
				<div id="div_clientSideError" class="container error"></div>
				
				
				<div>
				<p class="error">
				<?php if($USER->error!="") { ?>
				Error: <?php echo $USER->error; ?>
				<?php } ?>
				</p>	
				</div>
				
				</fieldset>
				
				
				
				
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