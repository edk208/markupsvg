var padleft = 0;
var padtop = 0;
var snakehttpRequestObject;

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


function snakeCancel() {
	var dialog = document.getElementById('dialog'); 
       	if(dialog != null) {dialog.parentNode.removeChild(dialog);}
}

function snakeUndo() {

}

function snakePoly(ev) {
	var svgobj = document;

       	var ex = ev.clientX;
       	var ey = ev.clientY;
	var g = ev.target.parentNode;
	var xpos = ex;
	var ypos = ey;
	//alert(ex+"x "+ey+"y "+xpos+"sx "+ypos+"sy "+xval+"transx "+yval+"transy");

	var circle = document.createElementNS("http://www.w3.org/2000/svg",'circle');
	circle.setAttribute('cx', xpos);
	circle.setAttribute('cy', ypos);
	circle.setAttribute('r', 2);
	circle.setAttribute('fill', 'rgb(255,0,0)');
	circle.setAttribute('stroke', 'green');
	circle.setAttribute('stroke-width', '1');
		
	var tempg = null;

	if(g.firstnode == null) {
		g.pointlistx = new Array();
		g.pointlisty = new Array();
		g.firstnode = circle;
		g.lastclick = circle;
		circle.setAttribute('r', 5);
		g.lastclick.Xval = xpos;
		g.lastclick.Yval = ypos;
         	circle.addEventListener("click", closeSnake, false);
		if(svgobj.getElementById('tempg') == null) {
		tempg = document.createElementNS("http://www.w3.org/2000/svg",'g');
		tempg.setAttribute('id', 'tempg');
		tempg.setAttribute('transform', 'translate('+padleft+','+padtop+')');
		ev.target.parentNode.appendChild(tempg);
		}
		else {
			tempg = svgobj.getElementById('tempg');
		}
	}
	else {
		tempg = svgobj.getElementById('tempg');
		var line = document.createElementNS("http://www.w3.org/2000/svg",'line');
		line.setAttribute('x1', g.lastclick.Xval);
		line.setAttribute('y1', g.lastclick.Yval);
		line.setAttribute('x2', xpos);
		line.setAttribute('y2', ypos);
		line.setAttribute('stroke', 'green');
		line.setAttribute('stroke-width', '1');
		g.lastclick = circle;
		g.lastclick.Xval = xpos;
		g.lastclick.Yval = ypos;
		tempg.appendChild(line);
		
	}
	g.pointlistx.push(xpos);
	g.pointlisty.push(ypos);
	tempg.appendChild(circle);
	
}		

document.closeSn = closeSnake;

function closeSnake(ev) {

	var svgobj = document;
	var filename = svgobj.getElementsByTagName('filename');

	var g = ev.target.parentNode.parentNode;
	g.firstnode = null;
	g.lastclick = null;
	var scale = 1;
	var imel = ev.target.parentNode.parentNode;
		
	ev.target.parentNode.parentNode.removeChild(ev.target.parentNode);
	//annotateManual(ev,g.id);

	var poly = document.createElementNS("http://www.w3.org/2000/svg",'polygon');	
	var points = "";
	for(i =0; i< (g.pointlistx).length; i++) {
		points = points + (Math.round(g.pointlistx[i])).toString() + "," + (Math.round(g.pointlisty[i])).toString()+ " ";
	}

	g.pointlist = points;
	poly.setAttribute('points', points);
	poly.setAttribute('stroke', 'rgb(0,255,0)');
	poly.setAttribute('stroke-width', '2');
	poly.setAttribute('fill','green');
	poly.setAttribute('opacity','0.4');
	
	relaypoints(points);
	var tempg = document.createElementNS("http://www.w3.org/2000/svg",'g');
	tempg.setAttribute('id', 'tempg');
	tempg.setAttribute('transform', 'translate('+padleft+','+padtop+')');
	tempg.appendChild(poly);
	imel.appendChild(tempg);

	var namer = filename[0].firstChild.nodeValue;
	var params = "filename="+namer+"&pointlist="+points;
	snakehttpRequestObject.open("POST", "./snakeCompute.php", true);
	snakehttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	snakehttpRequestObject.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	snakehttpRequestObject.onreadystatechange = function(){if(snakehttpRequestObject.readyState==4){previewSnake();}};
	snakehttpRequestObject.send(params);
	var xpos = 50;
	var ypos = 20;
	var dialog = document.createElementNS("http://www.w3.org/2000/svg",'g');
	var rect= document.createElementNS("http://www.w3.org/2000/svg",'rect');
	var header = document.createElementNS("http://www.w3.org/2000/svg",'rect');
	var textel = document.createElementNS("http://www.w3.org/2000/svg",'text');
	textel.setAttribute('x','4');
	textel.setAttribute('y','14');
	textel.setAttribute('font-size','8px');
	textel.setAttribute('fill','#dddddd');
	var textval = document.createTextNode("Please wait");
	textel.appendChild(textval);
		
	dialog.setAttribute('id','dialog');
	dialog.setAttribute('transform','translate('+xpos+','+ypos+')');
	header.setAttribute('width','260');
	header.setAttribute('height','20');
	header.setAttribute('fill','#111111');
	header.setAttribute('cursor','move');
	header.setAttribute('stroke','black');
	header.setAttribute('stroke-width','3');
	header.style.opacity = 0.9;
	rect.setAttribute('width','260');
	rect.setAttribute('height','150');
	rect.setAttribute('rx', '6');
	rect.setAttribute('fill','#eeeeee')
	rect.setAttribute('stroke','black');
	rect.setAttribute('stroke-width','3');
	rect.style.opacity = 0.9;
	
	var fo = document.createElementNS("http://www.w3.org/2000/svg",'foreignObject');
	var xdiv = document.createElementNS("http://www.w3.org/1999/xhtml", 'div');
	fo.setAttribute('width','240');
	fo.setAttribute('height','130');
	fo.setAttribute('x','50');
	fo.setAttribute('y','30');
	xdiv.innerHTML = "<p style='font-size:0.7em;'>Computing contours... please wait...</p>";
	
	fo.appendChild(xdiv);
	dialog.appendChild(rect);
	dialog.appendChild(header);
	dialog.appendChild(textel);
	dialog.appendChild(fo);
	g.appendChild(dialog);



}
function previewSnake() {

	var svgobj = document;
	var embedded = parent.document.getElementById('objectid2');
	var svgobj2 = embedded.contentDocument;
	var root = svgobj2.getElementById('area');
	//var spinner = svgobj.pgetById(gid,'g','spinnerg'); 
	//spinner.parentNode.removeChild(spinner);
	//clearTimeout(spinner.vTimeout);
	snakeCancel();
	
	var response = snakehttpRequestObject.responseText;
	var g = svgobj2.getElementById('area');
	var oldtempg = svgobj2.getElementById('tempg');
	if(oldtempg != null) {
		root.removeChild(oldtempg);
	}

	var tempg = svgobj2.createElementNS("http://www.w3.org/2000/svg",'g');
	tempg.setAttribute('id', 'tempg');
	tempg.setAttribute('transform', 'translate('+padleft+','+padtop+')');

	var outline = svgobj2.createElementNS("http://www.w3.org/2000/svg",'polygon');	
	outline.setAttribute('fill-opacity','0.4');
	outline.setAttribute('fill','green');
	outline.setAttribute('stroke','rgb(0,255,0)');
	outline.setAttribute('stroke-width','1');
	outline.setAttribute('points',response);
	//alert(response);
	tempg.appendChild(outline);
	root.appendChild(tempg);
	relaymachine(response);
}

function infosnake(name, level) {
}
function funcsnake() {

	var g2 = document.getElementById('area');

	g2.pointlistx = null;
	g2.pointlisty = null;
	g2.lastclick = null;
	g2.firstnode = null;
	g2.firstChild.addEventListener("mousedown", snakePoly, false);
}

function initialsnake() {
	snakehttpRequestObject=getHTTPObject();
	funcsnake();

}

onload=initialsnake;
