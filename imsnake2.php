<?php
	header('Content-Type: image/svg+xml');
	if(isset($_GET['im'])) {

		$filename = $_GET['im'];
		list($width,$height) = getimagesize("images/".$filename);

		$doc = "<?xml version=\"1.0\" standalone=\"no\"?>\n";
		$doc = $doc."<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"".$width."px\" height=\"".$height."px\" viewBox=\"0 0 $width $height\" version=\"1.1\" baseProfile=\"full\">\n";
		$doc = $doc."<script xmlns:xlink=\"http://www.w3.org/1999/xlink\" type=\"text/javascript\" xlink:href=\"relay.js\" ></script>\n";
		$doc = $doc."<filename>images/$filename</filename>\n";
		$doc = $doc."<g xlmns='http://www.w3.org/2000/svg' id='area'><image xmlns:xlink=\"http://www.w3.org/1999/xlink\" id=\"rawimage\" xlink:href=\"images/$filename\" x=\"0\" y=\"0\" width=\"$width\" height=\"$height\"/><g xlmns='http://www.w3.org/2000/svg/' id='manual' transform='translate(0,0)'><title>Manual</title></g></g>\n";
		$doc = $doc."</svg>";

		$xml= simplexml_load_string($doc);

		echo $xml->asXML();
	}
?>
