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
	
	
	<body style="background-color:#909090;margin:10px 0px; padding:0px;text-align:center;">


	<div id="div_mainpage">
	
	<div id="div_main" style="width:940px; margin:0 auto;">
		<h1 style="letter-spacing: 4px;">RadSTEP</h1>
		<div id="div_grid" style="width:100% margin:0 auto;">
			
			

		</div>
	</div>

	

	<div id="div_soc" style="height:25px; margin:0 auto;" >
	</div><!-- div_soc -->
		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
