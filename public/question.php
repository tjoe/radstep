<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;

	$assignment = new RadStep\Assignment($_POST["assignment_id"]);
	$question = new RadStep\Question($_POST["question_id"]);
	
?>

<script type="text/javascript" src="lib/jquery.form.js"></script>

<script type="text/javascript">
	$("#form_question").ajaxForm({
	    target: "#div_saveresponses_result", //where to send the result
	}); 
</script>	

<style type="text/css">
	
	#div_question_image{

		
	}
	#img_question{
		max-height: 40em;
		max-width: 40em;
	}
	#div_question_image_caption{
		text-align:center;
	}
	#div_question_btn{
		width:90%;
		float:right;
		padding:5px;
	}
	
</style>

<form id="form_question" action="saveresponses.php" method="post">
		<input type="hidden" name="question_id" value="<?php echo $question->question_id ?>" />
		<input type="hidden" name="assignment_id" value="<?php echo $assignment->assignment_id ?>" />
				
		<!-- IMAGES -->
		<div class="div_question_image">
	<?php	foreach ($question->images as $image){  ?>
						
				<img class="img_question" src="<? echo $image->url ?>" />
				<!-- <div class="div_question_image_caption"></div>-->
					<? echo $image->caption ?>

	<?php	} ?>
		</div>	
	<br />
	
	<!-- PROMPT -->
	<div id="div_question_prompt">
		<?php echo($question->prompt); ?>
	</div>
	
	<!-- MULTIPLE CHOICES -->
	<?php foreach($question->multiple_choices as $choice){ ?>
		<input type="radio" name="radio_choices" value="<?php echo $choice->choice ?>" /><?php echo $choice->caption ?><br />
	<?php } ?>
	
	
	<div id="div_question_btn">
		<input type="submit" name="save" value="Save" id="btn_submit_createassignment" />
	</div>
	
	<div id="div_saveresponses_result">
		
	</div>
</form>


