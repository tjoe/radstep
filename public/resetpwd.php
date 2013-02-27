<?PHP
require_once("./include/authentication.php");


if(isset($_POST['submitted'])) //form has not yet been submitted, get case 
{
	$success = false;

	$get_email = htmlspecialchars($_GET['email']);
	$get_code = htmlspecialchars($_GET['code']);	
	

   if(isset($get_email) && isset($get_code))
   {
   		$success=$rsauth->ResetPassword();
        $rsauth->RedirectToURL("resetpwdsuccess.html");
        exit;
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


	
	</head>
	
	
	<body style="margin:0 auto; padding:0px;">


	<div id="div_mainpage">
	
	<div id="div_header" style="text-align:center;margin:0 auto;">
		<h1 style="text-align:center;letter-spacing: 1.5em;">RadSTEP</h1>
		<h3 style="letter-spacing: 1em; font-variant: small-caps;">alpha build</h3>
	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">
		
		
		
		<!-- Form Code Start -->
		<div id='div_rsauthform'>	
		<form id='resetreq' action='<?php echo $rsauth->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Reset Password for <?php echo $getemail ?></legend>
		
		<input type='hidden' name='submitted' id='submitted' value='1'/>
		
		<div class='short_explanation'>* required fields</div>
		
		<div><span class='error'><?php echo $rsauth->GetErrorMessage(); ?></span></div>
		<div class='container'>
		    <label for='pw1' >Password*:</label><br/>
		    <noscript>
		    <input type='password' name='pw1' id='pw1' maxlength="50" /><br/>
		    </noscript>
		    
			<label for='pw2' >Password*:</label><br/>
		    <noscript>
		    <input type='password' name='pw2' id='pw2' maxlength="50" /><br/>
		    </noscript>
		    
		
		
		</div>
		
		<div class='container'>
		    <input type='submit' name='Submit' value='Submit' />
		</div>
		
		
		<?php
		if($success){
		?>
		<h2>Password is Reset Successfully</h2>
		Your new password is sent to your email address.
		<?php
		}else{
		?>
		<h2>Error</h2>
		<span class='error'><?php echo $rsauth->GetErrorMessage(); ?></span>
		<?php
		}
		?>
		
		
		</fieldset>
		</form>
		<!-- client-side Form Validations:
		Uses the excellent form validation script from JavaScript-coder.com-->
		
		<script type='text/javascript'>
		// <![CDATA[
		
		    var frmvalidator  = new Validator("resetreq");
		    frmvalidator.EnableOnPageErrorDisplay();
		    frmvalidator.EnableMsgsTogether();
		
		    frmvalidator.addValidation("email","req","Please provide the email address used to sign-up");
		    frmvalidator.addValidation("email","email","Please provide the email address used to sign-up");
		
		// ]]>
		</script>
		
		</div>
		<!--
		Form Code End (see html-form-guide.com for more info.)
		-->
		
	</div><!--div_main-->
	
	</div><!--div_mainpage-->

</body>
</html>