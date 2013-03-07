<html>
	<head>
	<head>
		<meta charset="utf-8"/>
		<title>RadSTEP</title>
	
	
		<link href="css/default.css" rel="stylesheet" type="text/css" />	
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<link href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
			
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	
		<style type="text/css">
			.debug{ display:none; }
		</style>



	<script type="text/javascript">

	jQuery(document).ready(function(){

	}); //close jquery(document).ready
	
	</script>
	
	
	</head>
	
	
	<body style="width:80%; margin:0 auto; padding:0px;">

	<div id="div_mainpage">
	<?php



/*
$json_string = '{		
	"assignment_id" : 1,
	"questionset_id" : 1,
	"questionset_name" : "Breast Imaging 1",
	"created_by" : "thomas.j.oneill@gmail.com",
	"created_datetime" : "201303021705",
	"assigned_by" : "thomas.j.oneill@gmail.com",
	"assigned_to" : "tjoeone@hotmail.com",
	"questions" : [1, 2],
	"responses" : [4, 1],
	"keywords" : "breast,mammography,oncology",
	"difficulty" : "low",
	"assigned_datetime" : "201303021821",
	"started_datetime": "",
	"status" : 1
}';
*/

$json_string = '{"question_id":1,"created_by":"thomas.j.oneill@gmail.com","created_datetime":"201303021700","images":[{"url":"images\u0013r239802309824352q3085.jpg","caption":"Craniocaudal mammogram of the right breast"},{"url":"images\u0000u77ywer87yewr34tasdg.jpg","caption":"Mediolateral oblique mammogram of the right breast"}],"multiple_choices":[{"choice":1,"text":"Tubular carcinoma","explanation":"","correct":true},{"choice":2,"text":"Papillary carcinoma","explanation":"","correct":false},{"choice":3,"text":"Triple negative invasive ductal carcinoma","explanation":"","correct":false},{"choice":4,"text":"Medullary carcinoma","explanation":"","correct":false}],"keywords":"breast,mammography,oncology","difficulty":"low"}';


var_dump(json_decode($json_string));

/*

$json_string = json_encode(
    $assignment = array(
	"assignment_id" => 1,
	"questionset_id" => 1,
	"questionset_name" => "Breast Imaging 1",
	"created_by" => "thomas.j.oneill@gmail.com",
	"created_datetime" => "201303021705",
	"assigned_by" => "thomas.j.oneill@gmail.com",
	"assigned_to" => "tjoeone@hotmail.com",
	"questions" => array( 1, 2),
	"responses" => array(4, 1),
	"keywords" => "breast,mammography,oncology",
	"difficulty" => "low",
	"assigned_datetime" => "201303021821",
	"started_datetime"=> "",
	"status" => 1 ));
*/

echo("<br />");
 
$json_string = json_encode(
array(
	"question_id" => 1,
	"created_by" => "thomas.j.oneill@gmail.com",
	"created_datetime" => "201303021700",
	"images" => array(
		array(
			"url" => "images\23r239802309824352q3085.jpg",
			"caption" => "Craniocaudal mammogram of the right breast"
			),
		array(
			"url" => "images\0u77ywer87yewr34tasdg.jpg",
			"caption" => "Mediolateral oblique mammogram of the right breast"
			)
	),

	"multiple_choices" => array(
		array(
		"choice" => 1,
		"text" => "Tubular carcinoma",
		"explanation" => "",
		"correct" => true
		),

		array(
		"choice" => 2,
		"text" => "Papillary carcinoma",
		"explanation" => "",
		"correct" => false
		),
		
		array(
		"choice" => 3,
		"text" => "Triple negative invasive ductal carcinoma",
		"explanation" => "",
		"correct" => false
		),

		array(
		"choice" => 4,
		"text" => "Medullary carcinoma",
		"explanation" => "",
		"correct" => false
		)
	),
	"keywords" => "breast,mammography,oncology",
	"difficulty" => "low"
));


echo($json_string);



/*
  
// Define the errors.
$constants = get_defined_constants(true);
$json_errors = array();
foreach ($constants["json"] as $name => $value) {
    if (!strncmp($name, "JSON_ERROR_", 11)) {
        $json_errors[$value] = $name;
    }
}

// Show the errors for different depths.
foreach (range(4, 3, -1) as $depth) {
    var_dump(json_decode($json, true, $depth));
    echo 'Last error: ', $json_errors[json_last_error()], PHP_EOL, PHP_EOL;
}
  
 */

?>
	<div id="div_header" style="text-align:center;margin:0 auto;">

	</div><!--div_header-->
	
	<div id="div_main" style="margin:0 auto;">
	
	

		
	</div><!-- div_mainpage -->


	<!-- DEBUGGING -->
	<div id="div_debug" class="debug"></div>
		

	</body>
</html>