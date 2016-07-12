var padleft = 0;
var padtop = 0;

function manualPoly(ev) {
	var svgobj = document;
    var ex = ev.pageX;
    var ey = ev.pageY;
    
    var childPos = $("#area").position();
    var parentPos = $("#area").parent().position();
    
    var difftop = childPos.top - parentPos.top;
    var diffleft = childPos.left - parentPos.left;
    
    ex = ex - diffleft;
    ey = ey - difftop;
    
	var g = ev.target.parentNode;
	var xpos = ex;
	var ypos = ey;
    //alert (ex + ', ' + ey);
    //alert(ey);
    
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
         	circle.addEventListener("click", closePoly, false);
		tempg = document.createElementNS("http://www.w3.org/2000/svg",'g');
		tempg.setAttribute('id', 'tempg');
		tempg.setAttribute('transform', 'translate('+padleft+','+padtop+')');
		ev.target.parentNode.appendChild(tempg);
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

function closePoly(ev) {
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
	poly.setAttribute('fill','none');
	
	var tempg = document.createElementNS("http://www.w3.org/2000/svg",'g');
	tempg.setAttribute('id', 'tempg');
	tempg.setAttribute('transform', 'translate('+padleft+','+padtop+')');
	tempg.appendChild(poly);
	imel.appendChild(tempg);
	relaypoints(points);
}

function funcmanual() {

	var g2 = document.getElementById('area');

	g2.pointlistx = null;
	g2.pointlisty = null;
	g2.lastclick = null;
	g2.firstnode = null;
	g2.firstChild.addEventListener("mousedown", manualPoly, false);
}

function initialmanual() {
	funcmanual();

}

onload=initialmanual;