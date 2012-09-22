<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
	//include "dbauth.php";
	
	$id = $_GET['num'];
	$im = $_GET['im'];
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	<title>Markup SVG</title>
	<script type="text/javascript">
	function getHTTPObject() {
		var xmlhttp;
		if(window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		return xmlhttp;
	}

	function redo(im,height,width) {
		var obj = document.getElementById('container');
		obj.innerHTML = "<div style='float:left;margin-right:25px;'><object id='objectid' data='imsnake.php?im="+im+"' height='"+height+"px' width='"+width+"px' type='image/svg+xml'></object></div><div style='float:left;'><object id='objectid2' data='imsnake2.php?im="+im+"' height='"+height+"px' width='"+width+"px' type='image/svg+xml'></object></div>";			

	}
	function undolast() {
		var obj = document.getElementById('objectid');
		var svgobj = obj.contentDocument;			
		var root = svgobj.getElementById('area');
		var tempg = svgobj.getElementById('tempg');

		var obj2 = document.getElementById('objectid2');
		var svgobj2 = obj2.contentDocument;			
		var root2 = svgobj2.getElementById('area');
		var tempg2 = svgobj2.getElementById('tempg');
		var polygons = svgobj.getElementsByTagName('polygon');
		if(root.pointlistx.length > 0 && polygons.length == 0) {
		root.pointlistx.pop();
		root.pointlisty.pop();
		var lines = svgobj.getElementsByTagName('line');
		var points = svgobj.getElementsByTagName('circle');
		var linelength = lines.length;
		var pointlength = points.length;
		if(linelength >0) {
			tempg.removeChild(lines[linelength-1]);
			tempg.removeChild(points[pointlength-1]);
			
			var lastx = root.pointlistx.pop();
			var lasty = root.pointlisty.pop();
			root.lastclick.Xval = lastx;
			root.lastclick.Yval = lasty;

			root.pointlistx.push(lastx);
			root.pointlisty.push(lasty);
		}
		else {
			tempg.removeChild(points[pointlength-1]);
			
			var lastx = root.pointlistx.pop();
			var lasty = root.pointlisty.pop();
			root.firstnode = null;

		}
		}
		else if(polygons.length > 0) {
			root.removeChild(tempg);
			root2.removeChild(tempg2);
			tempg = document.createElementNS("http://www.w3.org/2000/svg",'g');
			tempg.setAttribute('id', 'tempg');
			root.appendChild(tempg);
			var circle = document.createElementNS("http://www.w3.org/2000/svg",'circle');
			var xpos = root.pointlistx[0];
			var ypos = root.pointlisty[0];
			circle.setAttribute('cx', xpos);
			circle.setAttribute('cy', ypos);
			circle.setAttribute('r', 5);
			circle.setAttribute('fill', 'rgb(255,0,0)');
			circle.setAttribute('stroke', 'green');
			circle.setAttribute('stroke-width', '1');

			root.firstnode = circle;
         		circle.addEventListener("click", svgobj.closeSn, false);
			tempg.appendChild(circle);

			for(i = 0; i< (root.pointlistx).length; i++) {
				if(i >1 ) {
				var circle = document.createElementNS("http://www.w3.org/2000/svg",'circle');
				circle.setAttribute('cx', xpos);
				circle.setAttribute('cy', ypos);
				circle.setAttribute('r', 2);
				circle.setAttribute('fill', 'rgb(255,0,0)');
				circle.setAttribute('stroke', 'green');
				circle.setAttribute('stroke-width', '1');
				tempg.appendChild(circle);
				}
	
				var line = document.createElementNS("http://www.w3.org/2000/svg",'line');
				line.setAttribute('x1', root.pointlistx[i]);
				line.setAttribute('y1', root.pointlisty[i]);
				line.setAttribute('x2', xpos);
				line.setAttribute('y2', ypos);
				line.setAttribute('stroke', 'green');
				line.setAttribute('stroke-width', '1');
				root.lastclick = circle;
	
				tempg.appendChild(line);
				xpos = root.pointlistx[i];
				ypos = root.pointlisty[i];
	
			}
			//var lastx = root.pointlistx.pop();
			//var lasty = root.pointlisty.pop();
			root.lastclick.Xval = xpos;
			root.lastclick.Yval = ypos;
	
		}

	}

	function submit(v) {
		var httpRequestObject = getHTTPObject(); 
		var ptext = document.getElementById('pointtext');
		var mtext = document.getElementById('machinetext');
		var points = ptext.value;
		var machine = mtext.value;
		var url = "postpoints.php";
		var params = "num=<?php echo $id;?>&image=<?php echo $im;?>&points="+points+"&machine="+machine+"&pref="+v;
		httpRequestObject.open("POST", url, true);
		httpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequestObject.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		httpRequestObject.onreadystatechange = function() { 
			if(httpRequestObject.readyState == 4) {
			}
		};
		httpRequestObject.send(params);
	}
	</script>	
</head>
<body>
<h3>Workspace</h3>
 <div id='container'>
<?php
	list($width, $height) = getimagesize("images/$im");
	echo "<div style='float:left;margin-right:25px;'><object id='objectid' data='imsnake.php?im=$im' height='".$height."px' width='".$width."px' type='image/svg+xml'></object></div>";
	echo "<div sytle='float:left;'><object id='objectid2' data='imsnake2.php?im=$im' height='".$height."px' width='".$width."px' type='image/svg+xml'></object></div>";
?>
 </div>
 <div style='clear:both'></div>
 <textarea id="pointtext" cols=1 rows=1 style="visibility:hidden"></textarea>
 <textarea id="machinetext" cols=1 rows=1 style="visibility:hidden"></textarea>
 <input id="notsatisfied2" type="button" value="clear and redo" onclick="redo('<?php echo $im;?>',<?php echo $height?>,<?php echo $width?>)"/>
 <input id="notsatisfied" type="button" value="undo last point" onclick="undolast()"/>
 <p id='done'></p>
 <p>Now that you have finished the segmentation process, which segmentation result do you prefer?<br/>
 <input type="radio" name="radiobutton" onclick='submit(1)' /> My manual segmentation (left image) &nbsp;&nbsp;&nbsp; <input type="radio" name="radiobutton" onclick='submit(2)'/> The computer generated segmenation (right image)</p>
</body>
</html>
