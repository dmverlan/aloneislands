var d=document;
var HELP = 0;
var upSc = '112';
var resize_f = 0;

d.write('<SCRIPT src="js/jquery.js?'+upSc+'"></SCRIPT>');
d.write ('<script type="text/javascript" src="js/yourpers.js?1'+upSc+'"></script><LINK href=css/main.css?'+upSc+' rel=STYLESHEET type=text/css><LINK href=css/selectbox.css?'+upSc+' rel=STYLESHEET type=text/css><script language=javascript src=js/pers.js?'+upSc+'></script><script language=javascript src=js/statsup.js?'+upSc+'></script><SCRIPT language=javascript src="js/sell.js?'+upSc+'"></SCRIPT><SCRIPT  language=javascript SRC="js/w.js?'+upSc+'"></SCRIPT><SCRIPT src="js/fightn.js?'+upSc+'"></SCRIPT><SCRIPT src="js/tools/scrollto.js"></SCRIPT>');


function BodyScroll() {if(document.body.scrollTop>10)	top.hide_logo();
if(document.body.scrollTop<=10)	top.show_logo();}

function exit()
{
if (confirm("Вы действительно хотите выйти из игры?"))top.location='exit.php?rand='+Math.random();
}

var curTimeFor;
var curTimeInt;
var allTime;

function waiter(time,upd,info)
{
	if (!time) return;
	if (!info) info = '';
	   clearInterval(curTimeInt);
	if (!upd) upd = 1; else upd = 0;
	   allTime = time;
       curTimeFor = time;
	   //if (time>10 && top.ctip && top._duration) setTimeout("show_tip(0)",7000);
		//$('.head').get(7).disabled = true;
		var addtxt = '';
		  addtxt = '<table width=190 border=0 cellspacing=0 cellspadding=0><tr><td align=right><img src=images/skill.gif height=8 width=0 id=waiter_on></td><td align=left>';
		  addtxt += '<img src=images/no.png height=8 width=190 id=waiter_off></td></table>';
		  if (info!=undefined && info!='') addtxt+= '<br>'+info;
        document.getElementById("waiter").innerHTML = 'Действие, ещё <i><b id=waiter_time>'+allTime+'</b> сек...</i><br>'+addtxt;
		  
        curTimeFor = curTimeFor-1;
		$(function(){$("#waiter_on").animate({width:190},1000*allTime);$("#waiter_off").animate({width:0},1000*allTime);});

       curTimeInt = setInterval("winterv("+upd+",'"+info+"')",1000);
		 
		/*if($('.head').get(0))
		{
		$('.head').get(0).disabled = true;
		$('.head').get(1).disabled = true;
		$('.head').get(2).disabled = true;
		$('.head').get(4).disabled = true;
		$('.head').get(5).disabled = true;
		}*/
}

function winterv(upd,info)
{
	if (!document.getElementById("waiter") || !document.getElementById("waiter_time")) 
		{
			clearInterval(curTimeInt);
			return;
		}
       if(curTimeFor>0 || (!upd && curTimeFor==0))
       {
         document.getElementById("waiter_time").innerHTML = Math.round(curTimeFor);
	      curTimeFor = curTimeFor - 1;
       }
       else if (upd)
       {
		 	//top.Sound("misc8",1,0);
          clearInterval(curTimeInt);
	      document.getElementById("waiter").innerHTML = '<a href=main.php class=timef>Обновление...</a></i>';
	      window.location = "main.php";
       }
	   else 
       {
			clearInterval(curTimeInt);
			document.getElementById("waiter").innerHTML = '';
			/*$('.head').get(0).disabled = false;
			$('.head').get(1).disabled = false;
			$('.head').get(2).disabled = false;
			$('.head').get(4).disabled = false;
			$('.head').get(5).disabled = false;*/
			//$('.head').get(7).disabled = false;
       }
}

function set_apps(on)
{
if (on) 
{
for (var i=1;i<=7;i++)
	if ($('but'+i)) $('but'+i).disabled = false;
}
else
{
for (var i=1;i<=7;i++)
	if ($('but'+i)) $('but'+i).disabled = true;
}
}

function show_head(curstate,fourthname,code,apps,trvm,help)
{
d.write ('<body topmargin="0" style="word-spacing: 0; margin-left: 0; margin-right: 0; overflow-x:hidden;" leftmargin=0 onresize="on_resize()">');
if (curstate!=4)
{
var pers='';
var inv='';
var add='';
var fght='';
var fourth='';
var back = '';
if (fourthname!='' && !trvm && !apps)
	fourth = 'src="images/DS/tbuttons_3.png" '+code+' title="'+fourthname+'" onmouseout="this.src=\'images/DS/tbuttons_3.png\'" onmouseover="this.src=\'images/DS/tbuttons_3_hover.png\'" style="position:relative;top:-10px;cursor:pointer;"';
else
	fourth = 'src="images/DS/tbuttons_3_disabled.png" style="position:relative;top:-10px;"';

if (curstate==0 || apps) 
	pers = 'src="images/DS/tbuttons_1_disabled.png" style="position:relative;top:-10px;"';
else
	pers = 'src="images/DS/tbuttons_1.png" onclick="top.frames[\'main_top\'].location=\'main.php?go=pers\'" onmouseout="this.src=\'images/DS/tbuttons_1.png\'" onmouseover="this.src=\'images/DS/tbuttons_1_hover.png\'" style="position:relative;top:-10px;cursor:pointer;"';
if (curstate==1 || apps) 
	inv = 'src="images/DS/tbuttons_2_disabled.png" style="position:relative;top:-10px;"';
else
	inv = 'src="images/DS/tbuttons_2.png" onclick="top.frames[\'main_top\'].location=\'main.php?go=inv\'" onmouseout="this.src=\'images/DS/tbuttons_2.png\'" onmouseover="this.src=\'images/DS/tbuttons_2_hover.png\'" style="position:relative;top:-10px;cursor:pointer;"';
if (curstate==5 || apps || trvm) fght='DISABLED';
if (curstate==3 || apps || trvm) add='DISABLED';
if (curstate==2 || apps || trvm) 
	back = 'src="images/DS/tbuttons_4_disabled.png" style="position:relative;top:-10px;"';
else
	back = 'src="images/DS/tbuttons_4.png" onclick="top.frames[\'main_top\'].location=\'main.php?go=back\'" onmouseout="this.src=\'images/DS/tbuttons_4.png\'" onmouseover="this.src=\'images/DS/tbuttons_4_hover.png\'" style="position:relative;top:-10px;cursor:pointer;"';

var $buttons = '';
$buttons += ('<table border="0" width="'+top.DWidth+'"  cellspacing="0" cellpadding="0">');
$buttons += ('<tr>');
$buttons += ('<td align="center" width="190"><img border="0" '+pers+'></td>');
$buttons += ('<td align="center" width="169"><img border="0" '+inv+'></td>');
$buttons += ('<td align="center" width="'+(top.DWidth-730)+'">&nbsp;</td>');
$buttons += ('<td align="center" width="123"><img border="0" '+back+'></td>');
$buttons += ('<td align="center" width="185"><img border="0" '+fourth+'></td>');
$buttons += ('</tr>');
$buttons += ('</table>');
top.document.getElementById('title_buttons').innerHTML = $buttons;

d.write("<div style='width:1px;height:50px;'></div>");
d.write("<div id=_top style='width:100%; height:18px; background-image: url(\"images/DS/main_topline.jpg\"); margin-top:2px;'></div>");


/*document.onscroll = function(){if(document.body.scrollTop>50)	top.hide_logo();
	if(document.body.scrollTop<=50)	top.show_logo();}*/
	
top.show_logo();
document.body.onscroll = BodyScroll;
document.onscroll = BodyScroll;
}
else
	top.hide_logo();
	
	/*
if(help==2)
{
	document.getElementById('but5td').innerHTML += '<img src="images/design/warningred.gif" />';
}*/
HELP = help;
if(top.loading)
	top.load(30);
}

function sbox(t)
{
	return '<div align=left><div class="corners"><div class="inner"><div class="content">'+t+'</div></div></div></div>';
}

function sbox2(t,c,b)
{
	return sbox2b(c,b)+t+sbox2e(); 
}

function sbox2b(c,b)
{
	if (c) c = 'text-align:center;';
	if (!b) 
		b = '<tr> <td style="width: 18px; height: 18px"> <img src="images/left_top.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/top.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_top.png" width="18" height="18"></td> </tr>';
	else 
		b = '';
 	return '<table cellspacing="0" cellpadding="0" style="position:relative;top:-8px;width: 100%;">'+b+'  <tr> <td style="width: 18px;background-image: url(\'images/left.png\');">&nbsp;</td> <td style="background-image: url(\'images/bg.png\');'+c+'">';
}

function sbox2e()
{
	return '</td> <td style="width: 18px;background-image: url(\'images/right.png\');">&nbsp;</td> </tr> <tr> <td style="width: 18px; height: 18px"> <img src="images/left_bottom.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/bottom.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_bottom.png" width="18" height="18"></td> </tr> </table>';
}

function sbox3(t,c)
{
	return sbox3b(c)+t+sbox3e(); 
}

function sbox3b(c)
{
	if (c) c = 'text-align:center;';
	return '<table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td class=sbox3> </td> <td class=sbox3>&nbsp;</td> <td class=sbox3></td> </tr> <tr> <td class=sbox3>&nbsp;</td> <td class=sbox3c '+c+'">';
}

function sbox3e()
{
	return '</td> <td class=sbox3>&nbsp;</td> </tr> <tr> <td class=sbox3></td> <td class=sbox3>&nbsp;</td> <td class=sbox3> </td> </tr> </table>';
}

function too_fast(a)
{
	d.write("<div class=but><br><br><br><br><br><br><br><br><center style='width:100%;height:100%;'><center class=but><center class=puns>Слишком часто!</center>Сервер не справляется с нагрузкой, скоро всё нормализуется.["+a+"]<a href='main.php?"+a+"' class=but>Назад</a></center></center></div>");
	d.write("<SCRIPT SRC='js/c.js'></SCRIPT>"+
			'<script src="//mc.yandex.ru/resource/watch.js" type="text/javascript"></script>'+
			'<script type="text/javascript">'+
			'try { var yaCounter184038 = new Ya.Metrika(184038); } catch(e){}'+
			'</script>'+
			'<noscript><div style="position: absolute;"><img src="//mc.yandex.ru/watch/184038" alt="" /></div></noscript>');
}

function sbox4b(c){}
function sbox4e(){}

function on_resize()
{
	if(resize_f)
		scroll_def();
}