<?php
//header('Content-Type: image/png');
$points = $_POST['points'];
$width = $_POST['width'];
$height = $_POST['height'];

$points = explode(";",$points);
$regions = array();

$num = count($points);
for($i=0;$i<$num;$i++) {
	array_push($regions,$points[$i]);
}
$numofregions = $num;
$im = imagecreatetruecolor($width,$height);
$white = imagecolorallocate($im,255,255,255);
$black = imagecolorallocate($im,0,0,0);
for($rg=0; $rg < $numofregions; $rg++) {
	$apoints = explode(" ",$regions[$rg]);
	$numofpoints = count($apoints);

	$polyarray = array();
	for($i=0;$i<$numofpoints;$i++) {
		$point = explode(",",$apoints[$i]);
		array_push($polyarray,$point[0]);
		array_push($polyarray,$point[1]);
	}

	imagesetthickness($im, 2);
	imagefilledpolygon($im, $polyarray, $numofpoints, $white);
}

$im2 = imagecreatetruecolor($width,$height);
$startx = 0;
$starty = 0;
for($x=$width-1;$x>=0;$x--) {
	for($y=$height-1;$y>=0;$y--) {
		$rgb = imagecolorat($im,$x,$y);
		$r = ($rgb >> 16) & 0xFF;

		if($r == 255) {
			if($x == 0 || $y == 0 || $x==$width-1 || $y==$height-1) {
				imagesetpixel($im2, $x, $y, $white);
			}
			else {
				$top = imagecolorat($im,$x,$y-1);
				$bottom = imagecolorat($im,$x,$y+1);
				$left = imagecolorat($im,$x-1,$y);
				$right = imagecolorat($im,$x+1,$y);

				$top = ($top >> 16) & 0xFF;
				$bottom = ($bottom >> 16) & 0xFF;
				$left = ($left >> 16) & 0xFF;
				$right = ($right >> 16) & 0xFF;

				if($top==255 && $bottom==255 && $left==255 && $right==255) {
					imagesetpixel($im2, $x, $y, $black);
				}
				else {
					imagesetpixel($im2, $x, $y, $white);
					$startx = $x;
					$starty = $y;
				}
			}
		}
	}
}
//imagefilltoborder($im2, $startx+1, $starty+1,$white,$white);
//imagepng($im2);/*
$nx = $startx;
$ny = $starty;
$combinedpoly = "";
$counter = 0;
while($counter < 50000) {
	$combinedpoly = $combinedpoly.$nx.",".$ny." ";
	$top = imagecolorat($im2,$nx,$ny-1);
	$bottom = imagecolorat($im2,$nx,$ny+1);
	$left = imagecolorat($im2,$nx-1,$ny);
	$right = imagecolorat($im2,$nx+1,$ny);

	$top = ($top >> 16) & 0xFF;
	$bottom = ($bottom >> 16) & 0xFF;
	$left = ($left >> 16) & 0xFF;
	$right = ($right >> 16) & 0xFF;

	$topright = imagecolorat($im2,$nx+1,$ny-1);
	$bottomright = imagecolorat($im2,$nx+1,$ny+1);
	$topleft = imagecolorat($im2,$nx-1,$ny-1);
	$bottomleft = imagecolorat($im2,$nx-1,$ny+1);

	$topright = ($topright >> 16) & 0xFF;
	$bottomright = ($bottomright >> 16) & 0xFF;
	$topleft = ($topleft >> 16) & 0xFF;
	$bottomleft = ($bottomleft >> 16) & 0xFF;
	if(!($startx==$nx && $starty==$ny))
		imagesetpixel($im2, $nx, $ny, $black);

	if($top == 255) {
		$nx = $nx;
		$ny = $ny-1;
	}	
	else if($topright == 255) {
		$nx = $nx+1;
		$ny = $ny-1;
	}	
	else if($right == 255) {
		$nx = $nx+1;
		$ny = $ny;
	}	
	else if($bottomright == 255) {
		$nx = $nx+1;
		$ny = $ny+1;
	}	
	else if($bottom == 255) {
		$nx = $nx;
		$ny = $ny+1;
	}	
	else if($bottomleft == 255) {
		$nx = $nx-1;
		$ny = $ny+1;
	}	
	else if($left == 255) {
		$nx = $nx-1;
		$ny = $ny;
	}	
	else if($topleft == 255) {
		$nx = $nx-1;
		$ny = $ny-1;
	}	
	$counter++;
	if($nx == $startx && $ny == $starty)
		break;
}

echo $combinedpoly;
imagedestroy($im);
imagedestroy($im2);
?>
