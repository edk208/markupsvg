<?php
	include "dbauth.php";

	$num = $_POST['num'];
	$im = $_POST['image'];
	$points = $_POST['points'];
	$machine = $_POST['machine'];
	$pref = $_POST['pref'];

	$insert = "INSERT INTO svgsnake VALUES('',$num, '$im', '$points', '$machine', $pref)";
	$result = mysql_query($insert) or die("insert failed! ".mysql_error());

?>
