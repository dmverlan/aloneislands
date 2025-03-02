//snow

var no = 15;
var speed = 36;
var snowflake = "images/snow.gif";
var keith = 1;
var sammmy = 2;

var dx, xp, yp;
var am, stx, sty;
var i, doc_width = screen.width, doc_height = screen.height;
  doc_width = screen.width;
  doc_height = screen.height-300;

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();
for (i = 0; i < no; ++ i) {
  dx[i] = 0;
  xp[i] = Math.random()*(doc_width-50);
  yp[i] = Math.random()*doc_height;
  am[i] = Math.random()*40;
  stx[i] = 0.02 + Math.random()/10;
  sty[i] = 0.7 + Math.random();
  document.write('<div id="dot'+i+'" style="POSITION: absolute; Z-INDEX: 2; VISIBILITY: visible; top: 15px; left: 15px;" class=fader><img src="'+snowflake+'" border=0></div>');
}

function snowIE() {
var tmpx,tmpy;
  for (i = 0; i < no; ++i) {
    yp[i] += sty[i];
    if (yp[i] > doc_height-50) {
      xp[i] = Math.random()*(doc_width-am[i]-30);
      yp[i] = 0;
      stx[i] = 0.02 + Math.random()/10;
      sty[i] = 0.7 + Math.random();
    }
    dx[i] += stx[i];
	 if ((xp[i] + am[i]*Math.sin(dx[i]))>doc_width-50)xp[i] -= am[i];
	 tmpx = parseInt(xp[i] + am[i]*Math.sin(dx[i]));
	 tmpy = parseInt(yp[i]);
	 
	 document.getElementById("dot"+i).style.top = parseInt(yp[i]) + 'px';
	 document.getElementById("dot"+i).style.left = parseInt(xp[i] + am[i]*Math.sin(dx[i])) + 'px';
  }
  setTimeout("snowIE()", speed);
}
snowIE();
