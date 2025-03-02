var d=document;
var rep_text='';
d.write ('<META Http-Equiv=Content-Type Content="text/html; charset=windows-1251"><META Http-Equiv=Cache-Control Content=No-Cache><META Http-Equiv=Pragma Content=No-Cache><META Http-Equiv=Expires Content=0><LINK href=main.css rel=STYLESHEET type=text/css><SCRIPT src="js/jquery.js"></SCRIPT><script language=javascript src=js/_pers.js></script><script language=javascript src=js/statsup.js></script><link rel=\'shortcut icon\' href=\'images/icon.ico\'><body class=fightlong><form method=post name=del><input type=hidden value="" name=deleterep id=deleterep></form>');

function sbox2(t,c)
{
	return sbox2b(c)+t+sbox2e(); 
}

function sbox2b(c)
{
	if (c) c = 'text-align:center;';
	return '<table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td style="width: 18px; height: 18px"> <img src="images/left_top.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/top.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_top.png" width="18" height="18"></td> </tr> <tr> <td style="width: 18px;background-image: url(\'images/left.png\');">&nbsp;</td> <td style="background-image: url(\'images/bg.png\');'+c+'">';
}

function sbox2e()
{
	return '</td> <td style="width: 18px;background-image: url(\'images/right.png\');">&nbsp;</td> </tr> <tr> <td style="width: 18px; height: 18px"> <img src="images/left_bottom.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/bottom.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_bottom.png" width="18" height="18"></td> </tr> </table>';
}

function head(onl,nick)
{
var online = '';
var main_onl = '';
var crds = '';
var in_f = '';
if (onl[0])
{
	main_onl = '<font class=onl>ОНЛАЙН</font>';
	online = '';
	online += '<font class=timef> ['+onl[1]+'] </font> | Местоположение: <font class=user><b>'+onl[2]+'</b></font>'+' <font class=items>['+onl[3]+';'+onl[4]+']</font>';
	if (onl[5]>10) online += '<hr><a href=fight.php?id='+onl[5]+' target=_blank class=nt>В битве</a>';
}
else
{
	main_onl = '<font class=ofl>Оффлайн</font>';
}
	var inftxt = '<table 0% border=0><tr><td><input class="login" type="button" value="Игровая" onclick="location=\'info.php?p='+nick+'&no_watch=1\'" style="width: 110; height: 20; cursor:pointer;" id=but3></td><td width=80% align=center>'+main_onl+online+'</td><td><input class="login" type="button" value="Личная" onclick="location=\'info.php?p='+nick+'&no_watch=1&self=1\'" style="width: 110; height: 20; cursor:pointer;" id=but11></td></tr></table>';
	d.write("<center><center style='width:95%'>"+sbox2(inftxt,1)+"</center></center>");
}

function build_pers(sh,shd,oj,ojd,or1,or1d,po,pod,z1,z1d,z2,z2d,z3,z3d,sa,sad,na,nad,pe,ped,or2,or2d,ko1,ko1d,ko2,ko2d,br,brd,pers,inv,sign,nick,level,hp,mhp,ma,mma,tire,kam1,kam2,kam3,kam4,kam1d,kam2d,kam3d,kam4d,curh, maxh, curm, maxm, hp_int, ma_int,ss,sl,su,szd,szn,sp,sup,MONEY,dmoney,KB,mf1,mf2,mf3,mf4,mf5,udmin,udmax,rank_i,calling,victories,losses,experience,peace_experience,exp_to_lvl,zeroing,inv,dil,exp_proc,ws1,ws2,ws3,ws4,ws5,ws6,mpr,ISREP,pns,onl,dont_show_head)
{
var puns = '';
if (pns[0]) puns += '<center class=puns>Персонаж&nbsp;заблокирован.<br>Причина:<hr width=80%>'+pns[0]+'</center>';
if (pns[1]) puns += '<center class=puns>Персонаж&nbsp;в&nbsp;тюрьме.<br>Причина:<hr width=80%>'+pns[1]+'[ещё '+pns[2]+']</center>';
if (pns[3]) puns += '<center class=puns>Кара смотрителей ещё:<hr width=80%>'+pns[3]+'</center>';

if (!mpr) mpr='';
if (parseInt(ss)<1) ss=1;
if (parseInt(sl)<1) sl=1;
if (parseInt(su)<1) su=1;
if (parseInt(szd)<1) szd=1;
if (parseInt(szn)<1) szn=1;
if (parseInt(sp)<1) sp=1;
if (ws1!='0' && ws1!=undefined)ss='<b class=user>'+ss+'</b>'+' ('+(ss-ws1)+'<font color=green>'+ws1+'</font>)';
if (ws2!='0' && ws2!=undefined)sl='<b class=user>'+sl+'</b>'+' ('+(sl-ws2)+'<font color=green>'+ws2+'</font>)';
if (ws3!='0' && ws3!=undefined)su='<b class=user>'+su+'</b>'+' ('+(su-ws3)+'<font color=green>'+ws3+'</font>)';
if (ws4!='0' && ws4!=undefined)szd='<b class=user>'+szd+'</b>'+' ('+(szd-ws4)+'<font color=green>'+ws4+'</font>)';
if (ws5!='0' && ws5!=undefined)szn='<b class=user>'+szn+'</b>'+' ('+(szn-ws5)+'<font color=green>'+ws5+'</font>)';
if (ws6!='0' && ws6!=undefined)sp='<b class=user>'+sp+'</b>'+' ('+(sp-ws6)+'<font color=green>'+ws6+'</font>)';
d.write('<title>['+nick+'] AloneIslands</title>');

if (!dont_show_head)
head(onl,nick);

d.write('<center><table border="0" style="width:90%" cellspacing="0" cellpadding="0"><tr><td align="center" valign="top" width="280">');
d.write(sbox2b());
d.write('<font class=babout>Параметры</font><table border=0 width="90%" cellspacing=1 cellspadding=1 class=table_solid align=center>	<tr> <td  class=stats style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Сила:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=sila>'+ss+'</div></td> </tr> <tr> <td  class=stats style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Реакция:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=lovk>'+sl+'</div></td> </tr> <tr> <td class=stats style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Удача:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=udacha>'+su+'</div></td> </tr> <tr> <td  class=stats style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Здоровье:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=zdorov>'+szd+'</div></td> </tr> <tr> <td  class=stats style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Интеллект:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=znanya>'+szn+'</div></td> </tr> <tr> <td  class=stats  nowrap style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'>Сила&nbsp;Воли:</td> <td style=\'border-bottom-style: solid;border-bottom-color: #DFDFDF;border-bottom-width:1px;\'><div id=power>'+sp+'</div></td> </tr></table>');

d.write('<font class=babout>Модификаторы</font><table border="0" width="90%" cellspacing="1" align=center class=table_solid>');
if (KB!=0) d.write("<tr><td><font class=mf>Класс&nbsp;Брони:</font></td><td class=mfb width=90%>"+KB+"</td></tr>");
if (mf1!=0) d.write("<tr><td class=mf>Сокрушение:</td><td class=mfb width=90%>"+mf1+"%</td></tr>");
if (mf2!=0) d.write("<tr><td class=mf>Уловка:</td><td class=mfb width=90%>"+mf2+"%</td></tr>");
if (mf3!=0) d.write("<tr><td class=mf>Точность:</td><td class=mfb width=90%>"+mf3+"%</td></tr>");
if (mf4!=0) d.write("<tr><td class=mf>Стойкость:</td><td class=mfb width=90%>"+mf4+"%</td></tr>");
if (mf5!=0) d.write("<tr><td class=mf>Ярость:</td><td class=mfb width=90%>"+mf5+"%</td></tr>");
if (udmax>0)d.write("<tr><td class=mf>Удар:</td><td class=mfb width=90%>"+udmin+"-"+udmax+"</td></tr>");
d.write("<tr><td class=mf>Ранк:</font></td><td class=mfb width=90%>"+rank_i+"</td></tr>");
if (calling!="")d.write("<tr><td><font class=mf>Звание:</font></td><td><font class=mf width=90%>"+calling+"</font></td></tr>");
d.write("</table>");
d.write('<br>'+puns+'');
d.write(sbox2e());
d.write('</td>');
d.write('<td valign="top" width=250 rowspan="2" align=center id=mainpers>');
show_pers_new(sh,shd,oj,ojd,or1,or1d,po,pod,z1,z1d,z2,z2d,z3,z3d,sa,sad,na,nad,pe,ped,or2,or2d,ko1,ko1d,ko2,ko2d,br,brd,pers,inv,sign,nick,level,hp,mhp,ma,mma,tire,kam1,kam2,kam3,kam4,kam1d,kam2d,kam3d,kam4d,inv,dil);
d.write('<div id=aurasc class=aurasc></div></td>');
d.write('<td valign=top>'+mpr+d.getElementById('inf_from_php2').innerHTML+'</td></tr></table>');


$(".wttable tr:nth-child(odd)").css("background-color","#000000");
d.write('<SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>');
}

function report()
{
	d.getElementById('report').innerHTML = '<form method=post><textarea name=report class=inv rows=5 cols=50></textarea><br><input type=submit class=login value="Отправить[20 LN]"><hr></form>';
	$("#report").slideUp(1);
	$("#report").slideDown(500);
}

function pr_r(WHO,LVL,SIGN,DATE,text,del)
{
if (SIGN!= 'none') SIGN = '<img src=images/signs/'+SIGN+'.gif>'; else SIGN='';
if (del) del = '<input type=button class=but onclick="delete_rep('+del+')" value="X">'; else del = '';
	text = str_replace('<br>','\n',text);
	rep_text += '<tr><td class=login>'+del+''+SIGN+' <b>'+WHO+'</b>[<font class=lvl>'+LVL+'</font>] <img src="images/i.gif" onclick="window.open(\'info.php?p='+WHO+'\',\'\',\'width=800,height=600,left=10,top=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\');" style="cursor:pointer"> <font class=timef>'+DATE+'</font></td></tr><tr><td align=center><textarea cols=46 rows=3 class=gray style="width:100%;">'+text+'</textarea></td></tr>';
	return true;
}

function delete_rep(del)
{
if (confirm("Вы действительно хотите удалить этот отзыв?"))
{
	document.getElementById('deleterep').value = del;
	d.del.submit();
}
}

function reps_show()
{
	d.getElementById('mpers').style.visibility = 'hidden';
	d.getElementById('mpers').style.position = 'absolute';
	d.getElementById('reports').style.visibility = 'visible';
	d.getElementById('reports').style.position = 'fixed';
}
function reps_hide()
{
	d.getElementById('reports').style.visibility = 'hidden';
	d.getElementById('reports').style.position = 'absolute';
	d.getElementById('mpers').style.visibility = 'visible';
	d.getElementById('mpers').style.position = 'fixed';
}

function str_replace(replacement,replace,str)
{
	var w = str.split(replacement);
	return w.join(replace);
}

function exit()
{
top.window.close();
}

function view_auras(text)
{
	var ars = text.split('|');
	var ar,t;
	for(var i=0;i<ars.length;i++)
	{
	if (ars[i]!='')
	{
	ar = ars[i].split('#');
	if (ar[0].indexOf('.gif')!=-1) ar[0] = ar[0].substr(0,ar[0].length-4);
	t = '<img src="images/magic/'+ar[0]+'.gif" onmouseover="s_des(event,\'0|'+ar[1]+'\')" onmouseout="h_des()" onmousemove=move_alt(event) height=30>';
	if ((i+1)%5==0 && i!=0) t+= '<br>';
	document.getElementById('aurasc').innerHTML += t;
	}
	}
}

function show_presents()
{
	d.getElementById('main').innerHTML += '<br><font class=title>ПОДАРКИ('+prs[0]+')</font>';
	var t='<table>';
	var inf='';
	for (var i=1;i<prs.length;i++)
	{
		inf = '|<font class=user>'+prs[i][0]+'</font>@'+prs[i][4]+'@От:<b>'+prs[i][2]+'</b>@<i class=timef>['+prs[i][3]+']</i>';
		if (i%5==1) t+= '<tr>';
		t += '<td><img src=images/presents/'+prs[i][1]+'.jpg onmouseover="s_des(event,\''+inf+'\')" onmouseout="h_des()" onmousemove=move_alt(event)></td>';
		if (i%5==0) t+= '</tr>';
	}
	t+= '</table>';
	d.getElementById('main').innerHTML += t;
	d.write('<SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT><SCRIPT SRC="js/end.js?3"></SCRIPT>');
}