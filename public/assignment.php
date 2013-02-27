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
	
		<h3><? echo $_SESSION['name_of_user'] ?></h3>
		
		<!-- SELECT qset_id FROM assignments WHERE assignment_id=$_GET['assignment_id'] -->
		<!-- check that user_id in assignment matches the current users id, if completed = set review mode, if not completed = set exam mode -->
		
		<!-- SELECT q_id FROM qset_questions WHERE qset_id = 'assignment_qset_id' -->
		
		<!-- TIMER at top of total time elapsed while on this page, autosubmit assignment if exceeds timelimit -->

		<!-- SIDE NAVBAR of Questions -->
		
		<!-- SELECT * FROM questions WHERE 
		<ol>
			
			
		</ol>
		
		
		<!-- 
		
		<!-- SELECT q_stem, ans_form FROM questions WHERE question_id - current_q -->

		<!--  stem -->
		
		<!--  images -->
		<!-- SELECT stack_num, image_num, label, url FROM images WHERE question_id = current_q 
				by default stack_num and image_num are 0 if only one image 
				url may be a hash of q_id+stack_num+image_num ... all stored in a single directory?
				
				if(stack_num > 0)
				{
					//create carosel on top with middle image from each stack
					
					if(image_num > 0)
					//create ul for stack of images that float on top of each other?
					
					
				}
				else
					//just display single image url
		
		<!-- answer form 
			[STANDARDIZE MCQ RESULT FROM FORM]
			
			-->
		
		<!-- on_submit UPDATE answers SET result=form_result ajax, load next q -->

		
	</div><!--CLOSE div_main-->

		
	</div><!-- div_mainpage -->
	


		

	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

		
	</body>
</html>
