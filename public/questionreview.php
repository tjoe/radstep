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
	    target: "#div_saveresponses_result", //where to send the result
	}); 
	
	// submit form to save responses when the radio buttons are changed
	$("input[name='radio_choices']").change(function(){ 
		$("#form_question").submit(); 
	});

});

</script>	

<style type="text/css">
	
	#div_question_image{

		
	}
	#div_question_btn{
		width:90%;
		float:right;
		padding:5px;
	}
	.img_question{
		max-height: 20em;
		max-width: 20em;
	}
	.div_question_image_caption{
		text-align:center;
	}

	
</style>


<div id="div_question">

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
		<?php 
			echo($question->prompt); 
		?>
	</div>
	<br />
	
	<!-- MULTIPLE CHOICES -->
	<?php 

		$chosen_response = $assignment->getResponse($question->question_id);
		
		$is_correct_response = false;
		
		foreach($question->multiple_choices as $choice)
		{
			if(!is_null($chosen_response) && $choice->choice === $chosen_response && $choice->correct )
				$is_correct_response = true;

			if($choice->correct)
				echo("<img src='img/green_check.png' style='height:15px;width:15px' />");
			else 
				echo("<img src='img/red_x.png' style='height:15px;width:15px' />");
			
			echo($choice->caption);
			echo("<br />");
 		} ?>

	<br />
	<div id="div_explanation">
		<?php 
			
			if($is_correct_response){
				echo("You chose the CORRECT answer.");
			}else
			{
				echo("You chose the INCORRECT answer.".PHP_EOL);
			}
			
			echo("<br />");
			
			echo($question->explanation); 
		
		?>
	</div><!--close div_explanation -->
	
</div><!--close div_question -->


