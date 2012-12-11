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
	
	
	<link href="css/default.css" rel="stylesheet" type="text/css" />
	<script src="lib/jquery-1.8.3.min.js" type="text/javascript"></script>
	<link href="css/smoothness/jquery-ui-1.9.2.min.css" rel="stylesheet" type="text/css" />
	<script src="lib/jquery-ui-1.9.2.min.js"></script>
	<link href="css/rsauth.css" rel="stylesheet" type="text/css" />
    <script src='lib/gen_validatorv4.js' type='text/javascript'></script>
	
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
	
	//if multiple roles:
	$( "#div_tabs" ).tabs();
	//if resident
	$( "#accordion_resident" ).accordion();
	//if attending
	$( "#accordion_attending" ).accordion();
	
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
	
		<h2>Welcome, <? echo $_SESSION['name_of_user'] ?>. </h2>

		<!-- Tabs -->
		<h2>RadSTEP Roles</h2>
		<div id="div_tabs">
			<ul>
				<li><a href="#tabs-1">Resident</a></li>
				<li><a href="#tabs-2">Attending</a></li>
				<li><a href="#tabs-3">Administrator</a></li>
			</ul>
			<div id="tabs-1">
				
				<div id="accordion_resident">
				<h3>Incomplete Assignments</h3>
				<div>List of assignments with percent complete, due date and who assigned it.</div>
				<h3>Completed Assignments</h3>
				<div>List of completed assignments with score/percent correct listed.</div>
				<h3>Results</h3>
				<div>Generate some sort of graphical depiction of resident's results.</div>
				</div>
				
				
			</div>
			<div id="tabs-2">
				
				<div id="accordion_attending">
				<h3>Incomplete Assignments</h3>
				<div>List of last 10 assignments with percent complete, due date, who it is assigned to and option to edit/delete assignment.</div>
				<h3>Completed Assignments</h3>
				<div>List of last 10 completed assignments with date completed, who completed it and score/percent correct.</div>
				<h3>Create Assignment</h3>
				<div>Quick assignment by allowing selection of resident, due date and module to assign and link to advanced assignment editor.</div>
				</div>

			</div>
			<div id="tabs-3">
			
				List of links to administrative functions...
			
			</div>
		</div><!--CLOSE div_tabs -->

		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
