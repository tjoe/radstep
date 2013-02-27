<?php
require_once("./include/authentication.php");

$rsauth = new RadStepAuthentication();

/*
//Provide your site name here
$rsauth->SetWebsiteName('ttuhscrads.com');

//Provide the email address where you want to get notifications
$rsauth->SetAdminEmail('thomas.j.oneill@gmail.com');

*/

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$rsauth->SetRandomKey('TETnNcgIFl7HP2b');


//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$rsauth->InitDB(
	'/home/ttuhscrads/ttuhscrads.com/private/radstep.db', //db path 
	'users' 												//users tablename
	 );

?>