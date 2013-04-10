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



jQuery(document).ready(function(){
	
	$("#form_question").ajaxForm({
		url: "response.php",
	    target: "#div_result", //where to send the result
	}); 
	
	// submit form to save responses when the radio buttons are changed
	$("input[name='radio_choices']").change(function(){ 
		$("#form_question").submit(); 
	});

});

</script>	

<style type="text/css">
	

	.img_question{
		max-height: 20em;
		max-width: 20em;
	}
	.div_question_image_caption{
		text-align:center;
	}

	
</style>


<form id="form_question" method="post">
		<input type="hidden" name="question_id" value="<?php echo $question->question_id ?>" />
		<input type="hidden" name="assignment_id" value="<?php echo $assignment->assignment_id ?>" />
				
		<!-- IMAGES -->
		<div id="div_question_image">
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
	
	<br />
	
	<!-- MULTIPLE CHOICES -->
	<?php 
		$selected = false;
		$chosen_response = $assignment->getResponse($question->question_id);
		
		foreach($question->multiple_choices as $choice)
		{
			if(!is_null($chosen_response))
				$selected = (bool)($choice->choice === $chosen_response); ?>
			<input type="radio" name="radio_choices" value="<?php echo $choice->choice ?>" <?php if($selected) echo('checked="checked"'); ?>/><?php echo $choice->caption ?><br />
	<?php } ?>

	
	<div id="div_result">
		
	</div>

</form>


