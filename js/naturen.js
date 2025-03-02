var c_showed = 0;
var wX=0,wY=0;
var waiterMode = 1;
var D2=0;
var day = 1;

document.write('<div style="position:absolute; left:-2px; top:-2px; z-index: 65200; width:0px; height:0px; visibility:visible;" id="center"></div><div style="position:absolute; left:-2px; top:-2px; z-index: 65300; width:0px; height:0px;display:none;;vertical-align:center;" id="info"></div><div style="position:absolute; left:-2px; top:-2px; z-index: 65200; width:0px; height:0px;" id="zcenter"></div><div style="position:absolute; left:0px; top:0px; z-index: 65100; width:100%; height:100%; display:none; text-align:center;" id="center2" class=news>&nbsp;</div><div style="position:absolute; left:0px; top:0px; z-index: 65200; width:100%; height:100%; display:none; text-align:center;" id="center3" class=news>&nbsp;</div>');
$("#zcenter").hide(1);
$("#center").hide(5);

var map_load = 1;

function show_mmap(x,y)
{
	$("#center").css({left:'40%',top:'100',width:'300px',height:'300px'});
	if (!c_showed)
	{
	 $("#center2").css("display","block");
	 $("#center").show(300);
	 c_showed++;
	 $("#center").html(sbox('<table style="width: 100%"> <tr> <td class=title>МИНИКАРТА</td> <td style="height: 30px; width: 30px"> <img src="images/closebox.png" width="30" height="30" onclick="show_mmap()" title=Close></td> </tr> <tr> <td colspan=3 class=items align=center id=tmp_layer>&nbsp;</td></tr> </table>')); 	
	 setTimeout("$(\"#tmp_layer\").html('"+return_minicart(x,y)+"')",300);
	 }
	else
	{
	 $("#tmp_layer").html('');
	 $("#center2").css("display","none");
	 $("#center").hide(300);
	 c_showed--;
	}
}

function return_minicart(x,y)
{
 x+=22;
 y+=26;
 var cx,cy;
 var text;
 text = '<table border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px">';
 var dir='map/night';
 if(day) dir = 'map/day';
 for(cy=y-5;cy<=y+5;cy++)
 {
 text+='<tr>';
 for (cx=x-6;cx<=x+6;cx++)
 {
 //if(cx<50 && (cx>10 || cx%10<5)) dir='map'; else dir='map';
 if (cx==x && cy==y)  text+='<td class=go_yes><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg  width=18></td>';else
 if (cx==x-1 && cy==y)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x+1 && cy==y)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x && cy==y-1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x && cy==y+1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x-1 && cy==y-1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x+1 && cy==y+1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x+1 && cy==y-1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';else
 if (cx==x-1 && cy==y+1)text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';
 else
 text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=18></td>';
 }
 text+='</tr>';
 }
 text+='</table>';
 return text;
}


function view_nature(params)
{
	top.frames['updater'].location = 'services/_nature.php?'+params+'&'+Math.random();
	wX = aX;
	wY = aY;
}

function ready_nature(a1,a2,a3,a4,a5,a6,_day)
{
	document.getElementById('_top').innerHTML = "<table border=0 cellspacing=0 cellspadding=0 width=100%><tr><td style='width:350px;' nowrap>"+top.frames['updater']._U+"</td><td align=right style='width:50%;' class=white nowrap>"+top.frames['updater']._W+" | "+top.frames['updater']._M+"</td><td style='width:200px;'><a class=Lbg href='javascript:void(0);' onclick=\'show_mmap("+aX+","+aY+")\'>Миникарта</a></td></tr></table>";
	day = _day;
	//document.getElementById("d1").innerHTML = '';
	document.getElementById("d2").innerHTML = sbox2(top.frames['updater'].document.getElementById("d2").innerHTML
	+ top.frames['updater'].document.getElementById("error").innerHTML,1);
	if (map_load)
	{
	var column = ''+
	'<div style="position: absolute; width:39; height:337; top:67px; left:80px; z-index:2;background-image: url(\'images/DS/left_column.png\');"></div>'+
	'<div style="position: absolute; width:39; height:337; top:67px; left:640px; z-index:2;background-image: url(\'images/DS/right_column.png\');"></div>';
	column = '';
	document.getElementById("map").innerHTML = column+
	"<div id=mapl style='width: 640px;height: 320px;display: block;overflow: hidden;'></div>";
	map_load = 0;
	ready_map();
	}
	else
	ready_map();
	
	if (document.getElementById("outer").innerHTML)
	{
		waiterMode = 0;
		$("#info").css({left:'21%',top:'70px',width:'60%',height:'0px'});
		document.getElementById("info").innerHTML = sbox3('<center class=but style="overflow-y:auto;z-index:10;"><a href="javascript:hide_info()" class=blocked>Убрать</a><hr>'+document.getElementById("outer").innerHTML+'</center>');
		$("#info").fadeIn(500);
		$("#center3").css("visibility","visible");
	}
}

function hide_info()
{
	document.getElementById("outer").innerHTML = '';
	document.getElementById("info").innerHTML = '';
	$("#center3").css("visibility","hidden");
	$("#info").fadeOut(300);
}

function hps(a1,a2,a3,a4,a5,a6)
{
	document.getElementById("d1").innerHTML = show_only_hp2(a1,a2,a3,a4)+document.getElementById("d1").innerHTML;
	ins_HP(a1,a2,a3,a4,a5,a6);
}

function ready_map()
{
	scrollmap();
}

function go_nature(x,y)
{
	view_nature("go_nature=1&gox="+x+"&goy="+y);
	top.chlistref = 1;
}

function waiterSPEC(t,info)
{
	if (info==undefined) info = '';
	allTime = t;
	if (!t) return;
	wtwt();
	setTimeout("wtwt()",t*1000);
	waiter(t,1,info);
}


function wtwt()
{
	if (!c_showed)
	{
	 $("#zcenter").css({left:'40%',top:'60px',width:'210px',height:'30px'});
	 $("#center2").css("display","block");
	 c_showed=1;
	 $("#zcenter").html('<center id=waiter class=but></center>'); 	
	 $("#zcenter").slideDown(300);
	 }
	else
	{
	 $("#center2").css("display","none");
	 $("#zcenter").slideUp(300);
	 c_showed=0;
	}
}


//###########################################
//###########################################
//###########################################
//###########################################
//###########################################
//###########################################
//###########################################
var go_str;

function Minus(a)
{
	if(a<0) a = "M"+Math.abs(a);
	return a;
}

function show_nature(x,y,A){
var f='"';
var tmp = '#FFFFFF';
if (top.frames["updater"].m_name) 
{
	f='cursor:pointer" title="'+top.frames["updater"].m_name+'" '+top.frames["updater"].m_code;
	tmp = '#AAFFAA';
}

if (top.frames["updater"].go_str)
	eval(top.frames["updater"].go_str);
if (top.frames["updater"].bd_str)
	eval(top.frames["updater"].bd_str);
x+=22;
y+=26;
var text;
var colorTd,tmpTd,tipTd;
var bdTd,btmpTd;
var tmpStr;
var onclicktxt = '';
var name = '';

 text = '<table border="0" cellspacing="0" cellpadding="0" style="width:800px;height:560px;">';
 var cx,cy;
 var dir = 'map/night';
 if(day) dir = 'map/day';
 for(cy=y-3;cy<=y+3;cy++)
 {
 text+='<tr>';
 for (cx=x-5;cx<=x+4;cx++)
 {
 name = '';
 eval("if(typeof NAME"+Minus(cx-22)+"_"+Minus(cy-26)+" != 'undefined')name=NAME"+Minus(cx-22)+"_"+Minus(cy-26)+";");
 eval("if(typeof X"+Minus(cx-22)+"_"+Minus(cy-26)+" != 'undefined')tmpTd=X"+Minus(cx-22)+"_"+Minus(cy-26)+"; else tmpTd=-1;");
 eval("if(typeof B"+Minus(cx-22)+"_"+Minus(cy-26)+" != 'undefined')btmpTd=B"+Minus(cx-22)+"_"+Minus(cy-26)+"; else btmpTd=-1;");
	if (cx==x && cy==y)
	{
		colorTd = tmp;
		if ((tmpTd&3)==1) colorTd = '#FFFFFF';
		if ((tmpTd&3)==2) colorTd = '#0000FF';
		if ((tmpTd&3)==3) colorTd = '#FFFFFF';
		if ((tmpTd&4)==4) colorTd = '#00FFFF';
		
		if (btmpTd!=-1) 
			building = '<div class=fader style="position:absolute;z-index:3;width:80px;height:80px;background-color:'+colorTd+';border-style:none;'+f+'>&nbsp;</div><img src=../images/buildings/'+btmpTd+'.gif>';
		else 
			building = '<div class=fader title="Ваше местоположение['+name+']" style="width:80px;height:80px;background-color:'+colorTd+';border-style:none;'+f+'>&nbsp;</div>';
		text+='<td style=\'width:80px;height:80px;cursor:pointer;background-image: url("../images/'+dir+'/'+cx+'_'+cy+'.jpg");\' valign=top>'+building+'</td>';
	}
	else 
	if (tmpTd!=-1 && ((cx-x)*(cx-x)+(cy-y)*(cy-y))<=(50+A*6))
	{
		colorTd = '#FFFFFF';
		tipTd = "";
		if ((tmpTd&3)==1) {colorTd = '#FFFFFF';tipTd = 'Дикая местность';}
		if ((tmpTd&3)==2) {colorTd = '#0000FF';tipTd = 'Пригодна для строительства';}
		if ((tmpTd&3)==3) {colorTd = '#FFFFFF';tipTd = 'Дикая местность[Пригодна для строительства]';}
		if ((tmpTd&4)==4) {colorTd = '#00FFFF';tipTd = 'Ваша местность';}
		
		if (btmpTd!=-1) 
			building = '<div class=ngo_yes style="position:absolute;z-index:3;width:80px;height:80px;background-color:'+colorTd+';border-style:none;">&nbsp;</div><img src=../images/buildings/'+btmpTd+'.gif>';
		else 
			building = '<div class=ngo_yes style="width:80px;height:80px;background-color:'+colorTd+';border-style:none;">&nbsp;</div>';
			
		if(tmpTd!=-1 && ((cx-x)*(cx-x)+(cy-y)*(cy-y))<=2)	
			onclicktxt = 'onclick="go_nature('+(cx-22)+','+(cy-26)+')"';
		else
			onclicktxt = '';
			
			
		if (((cx-x)*(cx-x)+(cy-y)*(cy-y))<=2)
		text+='<td '+onclicktxt+' style=\'width:80px;height:80px;cursor:pointer;background-image:url("../images/'+dir+'/'+cx+'_'+cy+'.jpg");\' valign=top title="['+name+']Перейти'+tipTd+'">'+building+'</td>';
		else if(colorTd!='#FFFFFF')
		{
			if (btmpTd!=-1) 
				building = '<div class=ngo_yes style="width:80px;height:80px;background-color:'+colorTd+';border-style:none;position:absolute;z-index:3;">&nbsp;</div><img src=../images/buildings/'+btmpTd+'.gif>';
			else 
				building = '<div class=ngo_yes style="width:80px;height:80px;background-color:'+colorTd+';border-style:none;" title="'+tipTd+'">&nbsp;</div>';
			text+='<td title="['+name+']Недоступно" style=\'width:80px;height:80px;background-image:url("../images/'+dir+'/'+cx+'_'+cy+'.jpg");\'>'+building+'</td>';
		}else
		{
			if (btmpTd!=-1) 
				building = '<img src=../images/buildings/'+btmpTd+'.gif>';
			else 
				building = '&nbsp;';
			text+='<td title="['+name+']Недоступно" style=\'width:80px;height:80px;background-image:url("../images/'+dir+'/'+cx+'_'+cy+'.jpg");\'>'+building+'</td>';
		}
	}
	else
	{
		if (btmpTd!=-1) 
			building = '<img src=../images/buildings/'+btmpTd+'.gif>';
		else 
			building = '<div style="width:80px;height:80px;border-style:none;">&nbsp;</div>';
		text+='<td title="['+name+']Недоступно" style=\'width:80px;height:80px;background-image:url("../images/'+dir+'/'+cx+'_'+cy+'.jpg");\'>'+building+'</td>';
	}
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
if (top.frames["updater"].m_name) 
{
f='cursor:pointer" title="'+top.frames["updater"].m_name+'" '+top.frames["updater"].m_code;
tmp = '#AAFFAA';
}
document.getElementById("mapl").innerHTML +='<div class=fader style="position:absolute;z-index:4;width:80px;height:80px;left:'+(240+$("#mapl").offset().left)+';top:'+(120+$("#mapl").offset().top)+';background-color:'+tmp+';border-style:none;'+f+' id=ml>&nbsp;</div>';
}

var allTime;
var zx,zy;
var Interval = -1;

function signum(a)
{
	if (a>0) return 1;
	else if(a==0) return 0;
	else return -1;
}

function scrollmap()
{
			zx = signum(aX-wX)*80;
			zy = signum(aY-wY)*80;
			if (allTime)
				$("#mapl").scrollTo({left:120+zx, top: 120+zy},{duration:allTime*1000,axis:'xy'});
			if(allTime!=0)
				setTimeout("reset_map()",allTime*1000);
			else
				reset_map();
}

function reset_map()
{
	document.getElementById("mapl").innerHTML = show_nature(aX,aY,0);
	$(function(){scroll_def();});
}

function scroll_def()
{
	resize_f = 1;
	document.getElementById("mapl").scrollLeft = 120;
	document.getElementById("mapl").scrollTop = 120;
	//alert(1);
}