<?php
	$database = "mturk";
	$host = "localhost";
	$dbuser = "INSERUSERNAMEHERE"; 
	$dbpassword = "INSERTPASSWORDHERE";

	$connection = mysql_connect($host, $dbuser, $dbpassword) or die("error!".mysql_error());	
	mysql_select_db($database);

?>
