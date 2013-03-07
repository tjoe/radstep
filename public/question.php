<?php

	require_once("./include/debuggers.php");
	require_once("./include/globals.php");
	
	require_once("./include/RadStep/RadStepCommon.php");
	
	use RadStep;

	$question = new RadStep\Question($_POST["question_id"]);

?>

<style type="text/css">
	
	.div_question_image{
		
	}
	.div_question_image_caption{
		text-align:center;
	}
	
</style>

<form id="form_question">

	<?php	foreach ($question->images as $image){  ?>
			<div class="div_question_image">			
				<img src="<? echo $image->url ?>" />
				<div class="div_question_image_caption">
					<? echo $image->caption ?>
				</div>
			</div>		
	<?php	} ?>
	<br />
	<div class="div_question_prompt">
		<?php echo($question->prompt); ?>
	</div>
	<?php foreach($question->multiple_choices as $choice){ ?>
		<input type="radio" name="radio_choices" value="<?php echo $choice->choice ?>" /><?php echo $choice->text ?><br />
	<?php } ?>
	
	<div class="div_question_btn">
	<input type="button" value="Save" />
	</div>
</form>
