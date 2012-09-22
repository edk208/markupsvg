<?php
	//header("Content-Type: text/xml");

	$filename = $_POST['filename'];
	$gpointlist = $_POST['pointlist'];

	function parsePoints($pointstring) {
		$pointstring = explode(" ", $pointstring);
		$counter = 0;
		for($i=0;$i<count($pointstring);$i++) {
			$temppoint = explode(",",$pointstring[$i]);
			if(count($temppoint) > 1){
				$point['x'] = $temppoint[0];
				$point['y'] = $temppoint[1];
			}
			else {
				$point['x'] = $temppoint[0];
				$point['y'] = $pointstring[$i+1];
				$i = $i+1;
			}

			$list[$counter] = $point;
			$counter++;
		}
		return $list;
	}

	
	function listToMatlabString($mergedlist) {
		$pointlist = $mergedlist[0]['x'];
		for($i=1;$i<count($mergedlist);$i++) {
			$xvar = $mergedlist[$i]['x'];
			$pointlist = $pointlist.",".$xvar;
		}
		for($i=0;$i<count($mergedlist);$i++) {
			$yvar = $mergedlist[$i]['y'];
			$pointlist = $pointlist.",".$yvar;
		}
		return $pointlist;
	}

	if(isset($filename)) {
		//UNCOMMENT HERE TO TEST OUT THE CGI SCRIPTS
		///////////////////////////////////////////////
		//$curl = curl_init('http://idealab.cse.lehigh.edu/cgi-bin/snakedb');
		//$options = "filename=/var/www/html/mturk/mturk2/".$filename;
		
		$options = $options."&points1=".listToMatlabString(parsePoints($gpointlist));

		curl_setopt($curl, CURLOPT_POST,1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
		$page = curl_exec($curl);
		$close = curl_close($curl);
		$xml= simplexml_load_string($page);
			
		$xml2 = $xml->polygon;
		$points = $xml2['points'];

		
		echo $points;
	}
?>
