<?PHP
require_once("./include/authentication_config.php");

if(!$rsauth->CheckLogin())
{
    $rsauth->RedirectToURL("index.php");
    exit;
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
	
	
	<link rel="stylesheet" type="text/less" href="css/default.less" />
	<script type="text/javascript" src="lib/less-1.3.1.min.js"></script>
	<script type="text/javascript" src="lib/jquery-1.8.3.min.js"></script>
	<link rel="STYLESHEET" type="text/css" href="css/rsauth.css" />
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
	$.ajaxSetup({ cache: false });
	
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
		<h1 style="text-align:center;letter-spacing: 15px;">RadSTEP</h1>
		<h3 style="letter-spacing: 10px; font-variant: small-caps;">alpha build</h3>
	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">

		Nothing to see here.
		
		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
