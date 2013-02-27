<?php
require_once("./include/authentication_config.php");

if(isset($_POST['submitted']))
{
   if($rsauth->Login())
   {
        $rsauth->RedirectToURL("usermain.php");
   }
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
	
	
	<link rel="stylesheet" type="text/css" href="css/default.css" />
	<script type="text/javascript" src="lib/jquery-1.8.3.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/rsauth.css" />
    <script type='text/javascript' src='lib/gen_validatorv4.js'></script>
	
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
		<h1 style="text-align:center;letter-spacing: 1.5em;">RadSTEP</h1>
		<h3 style="letter-spacing: 1em; font-variant: small-caps;">alpha build</h3>
	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">

		<div id="div_login">
				
		<!-- Form Code Start -->
		<div id='div_rsauthform'>
		<form id='login' action='<?php echo $rsauth->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Login</legend>
		
		<input type='hidden' name='submitted' id='submitted' value='1'/>
		
		<div class='short_explanation'>* required fields</div>
		
		<div><span class='error'><?php echo $rsauth->GetErrorMessage(); ?></span></div>
		<div class='container'>
		    <label for='username_email' >Username/E-mail*:</label><br/>
		    <input type='text' name='username_email' id='username_email' value='<?php echo $rsauth->SafeDisplay('username_email') ?>' maxlength="50" style="border:solid 1px #888; height:32px;font:18px sans-serif;" /><br/>
		    <span id='login_username_errorloc' class='error'></span>
		</div>
		<div class='container'>
		    <label for='password' >Password*:</label><br/>
		    <input type='password' name='password' id='password' maxlength="50" style="border:solid 1px #888; height:32px;font:18px sans-serif;" /><br/>
		    <span id='login_password_errorloc' class='error'></span>
		</div>
		
		<div class='container'>
		    <input type='submit' name='Submit' value='Submit' />
		</div>
		<div class='short_explanation'><a href='reset-pwd-req.php'>Forgot Password?</a></div>
		</fieldset>
		</form>
		<!-- client-side Form Validations:
		Uses the excellent form validation script from JavaScript-coder.com-->
		
		<script type='text/javascript'>
		// <![CDATA[
		
		    var frmvalidator  = new Validator("login");
		    frmvalidator.EnableOnPageErrorDisplay();
		    frmvalidator.EnableMsgsTogether();
		
		    frmvalidator.addValidation("username_email","req","Please provide your username");
		    
		    frmvalidator.addValidation("password","req","Please provide the password");
		
		// ]]>
		</script>
		</div>
		<!--
		Form Code End
		-->
		</div><!--CLOSE div_login-->
		
		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
