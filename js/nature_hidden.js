var go_str;

function show_nature(x,y,A){
if (top.top.frames["updater"].go_str)
go_str = top.top.frames["updater"].go_str;
x+=22;
y+=26;
var text;
 text = '<table border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px">';
 var cx,cy;
 var dir;
 for(cy=y-3;cy<=y+3;cy++)
 {
 text+='<tr>';
 for (cx=x-4;cx<=x+4;cx++)
 {
 if(cx<50 && (cx>10 || cx%10<5)) dir='map'; else dir='map';
	if (go_str.indexOf('<'+(cx-22)+'_'+(cy-26))>-1 && ((cx-x)*(cx-x)+(cy-y)*(cy-y))<=(2+A*6))
		text+='<td class=go_yes onclick="go_nature('+(cx-22)+','+(cy-26)+')" style=\'width:80px;height:80px;cursor:pointer\'><img src=../images/'+dir+'/'+cx+'_'+cy+'.jpg width=80 height=80></td>';
	else
		text+='<td title="Недоступно" style="width:80px;height:80px;"><img src=../images/'+dir+'/'+cx+'_'+cy+'.jpg width=80 height=80></td>';
 }
 text+='</tr>';
 }
 text+='</table>';
 return text;
}

function upd_square()
{
var f='"';
var tmp = '#FFFFFF';
if (top.top.frames["updater"].m_name) 
{
f='cursor:pointer" title="'+top.top.frames["updater"].m_name+'" '+top.top.frames["updater"].m_code;
tmp = '#AAFFAA';
}
document.getElementById("mapl").innerHTML +='<div class=fader style="position:absolute;z-index:3;width:80px;height:80px;left:320;top:240;visibility:visible;background-color:'+tmp+';border-style:none;'+f+' id=ml>&nbsp;</div>';
}

function go_nature(x,y)
{
	if (top.top.frames['main_top'])
		top.top.frames['main_top'].go_nature(x,y);
}

var aX,aY,wX,wY;
var scrollmX;
var scrollmy;
var c_scrollmX=90;
var c_scrollmY=80;
var allTime;
var step=0;
var zz;
var zx,zy;
var koef=1;
var Interval = -1;

function signum(a)
{
	if (a>0) return 1;
	else if(a==0) return 0;
	else return -1;
}

function scrollmap()
{
		if (step==0)
		{
			koef = 2;
			if (zz>100)
			{
				koef = 1;
			}	
			zx = Math.floor(signum(aX-wX)*koef);
			zy = Math.floor(signum(aY-wY)*koef);
			zz = zz*koef;
			if (parseInt(zz)<4)	zz = 4;
			Interval = setInterval("scrollmap()",zz);
			if (top.top.frames["updater"].go_str)
				go_str = top.top.frames["updater"].go_str;
		}
/*	
	if (!zx && !zy) 
	{
		document.getElementById("mapl").innerHTML = show_nature(aX,aY,0);
		upd_square();
		scrollTo(90,80);
		c_scrollmX=90;
		c_scrollmY=80;
		return;
	}
*/
	scrollBy(zx,zy);
	c_scrollmX += zx;
	c_scrollmY += zy;

	if (document.getElementById("ml"))
	{
		document.getElementById("ml").style.left = c_scrollmX+230+'px';
		document.getElementById("ml").style.top  = c_scrollmY+160+'px';
	}
	
	if (step>=80/koef || (!top.top.frames['main_top'].c_showed && zz>4)) 
	{
		clearInterval(Interval);
		document.getElementById("mapl").innerHTML = show_nature(aX,aY,0);
		upd_square();
		c_scrollmX=90;
		c_scrollmY=80;
		scrollTo(90,80);
		step = 0;
	}
	
	step++;
}