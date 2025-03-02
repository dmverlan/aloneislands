var $ = function(id){
	return document.getElementById(id);
};

function dw(txt)
{
	return document.write(txt);
}

function df(txt)
{
	$('app').innerHTML += txt;
}

function dfm(txt)
{
	$('apps_m').innerHTML += txt;
}

function da(txt)
{
	$('apps_m').innerHTML += '<div class=but2><div class=but>'+txt+'</div></div>';
}

function dam(txt)
{
	$('app').innerHTML += '<font class=timef>'+txt+'</font>';
}

function str_replace(replacement,substr,str)
{
while(str.indexOf(replacement)!=-1) str=str.replace(replacement,substr);
return str;
}

function apps_head(type,chp,hp,cma,ma,sort_apps)
{
var cat1,cat2,cat3,cat4;
if (type==1) cat1 = 'class=Lbg'; else cat1 = 'class=bga';
if (type==2) cat2 = 'class=Lbg'; else cat2 = 'class=bga';
if (type==3) cat3 = 'class=Lbg'; else cat3 = 'class=bga';
if (type==4) cat4 = 'class=Lbg'; else cat4 = 'class=bga';
var s1,s2;
var TEXT = '';
if (sort_apps) {s2 = ' class=bga'; s1 = ' class=bg';}else{s1 = ' class=bga'; s2 = ' class=bg';}
var sort_lvl = '<a href=main.php?cat='+type+'&filter_apps=1 '+s1+'>Ваш уровень</a>';
sort_lvl += '<a href=main.php?cat='+type+'&filter_apps=2 '+s2+'>Все уровни</a>';
TEXT += ('<table border="0" width="100%" cellspacing="0" cellpadding="0">');
TEXT +=('<tr>');
var imgtmp = '';
if (HELP == 3) imgtmp = '';
TEXT +=('<td align="center" width="25%"><a href="main.php?cat=1" '+cat1+'>'+imgtmp+'Дуэли</a></td>');
TEXT +=('<td align="center" width="25%"><a href="main.php?cat=2" '+cat2+'>Групповые бои</a></td>');
TEXT +=('<td align="center" width="25%"><a href="main.php?cat=3" '+cat3+'>Хаотические бои</a></td>');
if(testing)TEXT +=('<td align="center" width="25%"><a href="main.php?cat=4" '+cat4+'>Тестирование</a></td>');
TEXT +=('</tr>');
TEXT +=('</tr></table>');
document.getElementById('_top').innerHTML = TEXT;
dw('<table border="0" width="100%" cellspacing="0" cellpadding="0">');
dw('<tr  bgcolor="#EEEEEE"><td width=25% align=center style="border: 0px">');
show_only_hp(chp,hp,cma,ma);
dw('</td><td colspan="2" width="20%" height="40" align=left class=user>'+your_nick+'[<font class=lvl>'+your_lvl+'</font>]</td><td><h2>АРЕНА</h2></td><td style="width:25%" colspan="9" height="40" align=center class=user style="cursor:pointer" onclick="location=\'main.php?cat='+type+'\'">Заявки на поединки '+sort_lvl+'</td></tr>');
dw('</table>');
dw('<table border="0" width="100%" cellspacing="0" cellpadding="0" style="background-color: #EEEEEE;"> <tr> <td align=center valign=center id=apps_m></td> </tr> <tr> <td align=center height=40 valign=center>'+sbox2('<center id=app>&nbsp;</center>')+'</td> </tr> </table>');
}

var oruj = 0;
var travm=10;
var timeout=120;
var bplace = '';

function do_app_1(is_can)
{
var tip = '';
 tip = '<center><i>+50% опыта за подачу заявки.</i></center>';
if (your_lvl>9) bplace = '<select name=bplace class=real><option value="0">Классический</option><option value=1 SELECTED>Тактический: Поле зелени</option><option value=5>Тактический: Вода</option> <option value=3>Тактический: Пустыня</option></select>';
if (orujd==1) oruj=1;
dfm('<center style="width:60%">'+sbox2('<form method="POST" action="main.php?cat=1" name=apps><table border="0" width="100%"  cellspacing="0" cellpadding="0" bordercolorlight="#DDDDDD" bordercolordark="#FFFFFF" style="cursor:pointer;"> <tr> <td class=user align=center colspan=8>Подать заявку:</td></tr><td width="60%" align=right>'+bplace+'<img border="0" src="images/arena/blood_10.gif" width="17" height="22" name=travm></td><td width=18><img border="0" src="images/arena/zayor_'+oruj+'.gif" width="17" height="22" name=oruj></td> <td width="10" class="user" id=timeout onclick="change_timeout();">&nbsp;2&nbsp;</td> <td align="left"><input type=hidden name=travm value=10><input type=hidden name=oruj value='+oruj+'><input type=hidden name=timeout value=120><input type="submit" value="Подать" class="login"></td></tr></table>'+tip+'</form>')+'</center>');
document.images['oruj'].onclick = change_oruj;
document.images['travm'].onclick = change_travm;
if(your_lvl<5) {change_travm();change_travm();change_travm();}
}

function do_app_2(is_can)
{
if (your_lvl>9) bplace = '<select name=bplace class=real><option value="0">Классический</option><option value=1 SELECTED>Тактический: Поле зелени</option><option value=5>Тактический: Вода</option> <option value=3>Тактический: Пустыня</option></select>';
if (orujd==1) oruj=1;
var minlvl1,minlvl2,maxlvl1,maxlvl2;
your_lvl = parseInt(your_lvl);
for (var i=0;i<=(your_lvl+8);i++)
if (i>-1)
{
	if (i!=your_lvl) minlvl1 += '<option value='+i+'>'+i+'</option>';
	else minlvl1 += '<option value='+i+' SELECTED>'+i+'</option>';
}
minlvl2 = minlvl1;
maxlvl1 = minlvl1;
maxlvl2 = minlvl1;

dfm('<form name="apps" method="post" action="main.php?cat=2"> <table border="0" width="100%" height="55" bgColor="#f8f8f8"> <tr> <td width="141">Подать свою заявку:</td> <td width="339"> <table border="0" width="350" cellspacing="0" cellpadding="0" height="48"> <tr> <td width="339" height="25">Команда 1: Кол-во: <input type="text" name="count1" size="5" class="laar" value="1"> , уровни <select size="1" name="minlvl1" class="real"> '+minlvl1+' </select>-<select size="1" name="maxlvl1" class="real">'+maxlvl1+'</select></td> </tr> <tr> <td width="339" height="23">Команда 2: Кол-во: <input type="text" name="count2" size="5" class="laar" value="1"> , уровни <select size="1" name="minlvl2" class="real"> '+minlvl2+' </select>-<select size="1" name="maxlvl2" class="real">'+maxlvl2+'</select></td> </tr> </table> </td> <td width="184"> <table style="CURSOR: pointer" cellSpacing="0" borderColorDark="#FFFFFF" cellPadding="0" width="110%"  borderColorLight="#DDDDDD" border="0"> <tr> <td width="17"> <img height="22" src="images/arena/blood_'+travm+'.gif" width="17" border="0" name="travm"></td> <td width="17"> <img height="22" src="images/arena/zayor_'+oruj+'.gif" width="17" border="0" name="oruj"></td> <td class="user" id="timeout" width="35">2</td> <td align="left"> <input type="hidden" value="10" name="travm"> <input type="hidden" value="0" name="oruj"> <input type="hidden" value="120" name="timeout"> <input class="inv" size="20" value="описание" name="comment" style="text-align: center"><br>'+bplace+' </td> </tr> </table> </td> <td align="center"><select size="1" name="atime" class="real"> <option selected>Ожидание</option> <option value="120">2 мин</option> <option value="300">5 мин</option> <option value="600">10 мин</option> <option value="1200">20 мин</option> <option value="2400">40 мин</option> </select> | <input type="submit" value="Подать" class="login"></td> <td>&nbsp;</td></tr></table></form>');
document.images['oruj'].onclick = change_oruj;
document.images['travm'].onclick = change_travm;
document.apps.comment.onclick = clear_comment;
$('timeout').onclick = change_timeout;
}

function do_app_3(is_can)
{
if (your_lvl>9) bplace = '<select name=bplace class=real><option value="0">Классический</option><option value=1 SELECTED>Тактический: Поле зелени</option><option value=5>Тактический: Вода</option> <option value=3>Тактический: Пустыня</option></select>';
if (orujd==1) oruj=1;
var minlvl1,maxlvl1;
your_lvl = parseInt(your_lvl);
for (var i=0;i<=(your_lvl+8);i++)
if (i>-1)
{
	if (i!=your_lvl) minlvl1 += '<option value='+i+'>'+i+'</option>';
	else minlvl1 += '<option value='+i+' SELECTED>'+i+'</option>';
}
maxlvl1 = minlvl1;

dfm('<form name="apps" method="post" action="main.php?cat=3"> <table border="0" width="100%" height="55" bgColor="#f8f8f8"> <tr> <td width="141">Подать свою заявку:</td> <td width="339"> <table border="0" width="350" cellspacing="0" cellpadding="0" height="48"> <tr> <td width="339" height="25">Кол-во: <input type="text" name="count1" size="5" class="laar" value="2"> , уровни <select size="1" name="minlvl1" class="real"> '+minlvl1+' </select>-<select size="1" name="maxlvl1" class="real">'+maxlvl1+'</select></td> </tr> </table> </td> <td width="184"> <table style="CURSOR: pointer" cellSpacing="0" borderColorDark="#FFFFFF" cellPadding="0" width="110%"  borderColorLight="#DDDDDD" border="0"> <tr> <td width="17"> <img height="22" src="images/arena/blood_'+travm+'.gif" width="17" border="0" name="travm"></td> <td width="17"> <img height="22" src="images/arena/zayor_'+oruj+'.gif" width="17" border="0" name="oruj"></td> <td class="user" id="timeout" width="35">2</td> <td align="left"> <input type="hidden" value="10" name="travm"> <input type="hidden" value="0" name="oruj"> <input type="hidden" value="120" name="timeout"> <input class="inv" size="20" value="описание" name="comment" style="text-align: center"><br>'+bplace+' </td> </tr> </table> </td> <td align="center"><select size="1" name="atime" class="real"> <option selected>Ожидание</option> <option value="120">2 мин</option> <option value="300">5 мин</option> <option value="600">10 мин</option> <option value="1200">20 мин</option> <option value="2400">40 мин</option> </select> | <input type="submit" value="Подать" class="login"></td> <td>&nbsp;</td></tr></table></form>');
document.images['oruj'].onclick = change_oruj;
document.images['travm'].onclick = change_travm;
document.apps.comment.onclick = clear_comment;
$('timeout').onclick = change_timeout;
}

function change_oruj()
{
	if (orujd==0)
	{
		oruj++;
		oruj%=2;
		document.images['oruj'].src = 'images/arena/zayor_'+oruj+'.gif';
		document.apps.oruj.value = oruj;
	}
}

function change_travm()
{
		if (travm==10) travm=30;
		else if (travm==30) travm=50;
		else if (travm==50) travm=80;
		else if (travm==80) travm=10;
		document.images['travm'].src = 'images/arena/blood_'+travm+'.gif';
		document.apps.travm.value = travm;
}

function change_timeout()
{
		if (timeout==120) timeout=180;
		else if (timeout==180) timeout=240;
		else if (timeout==240) timeout=300;
		else if (timeout==300) timeout=120;
		$('timeout').innerHTML = '&nbsp;'+timeout/60+'&nbsp;';
		document.apps.timeout.value = timeout;
}

function clear_comment()
{
	document.apps.comment.value = '';
}

function show_apps_1()
{
	if(lb_attack!=0) da("Вы сможете начать бой с существом через "+lb_attack+" сек.");
	
df("<b>Заявки на дуэли:</b><br>");
if (!apps.length) 
{
	dam('Здесь нет ни одной заявки на поединок.');
	return false;
}
	var w,ds,p1,p2,pt1,pt2,ptm,sign,nick,info,maintxt,radio,txt,bplace;
	pt1 = '';
	pt2 = '';
	sign = '';
	info = '';
	nick = '';
	maintxt = '';
	radio = '';
	txt='';
	for (i=0;i<apps.length;i++)
	{
		w = apps[i].split(':');
		ds = str_replace(' ','&nbsp;',w[10]);
		p1 = w[11].split('•');
		p2 = w[12].split('•');
		bplace = '';
		if (w[14]==1) bplace = 'Поле зелени';
		else if (w[14]==3) bplace = 'Пустыня';
		else if (w[14]==5) bplace = 'Вода';
		pt1 = '';
		pt2 = '';
		for (j=0;j<p1.length;j++)
			{
				ptm = p1[j].split('|');
				sign = ptm[0];
				if (sign!='none' && sign) sign = '<img src="images/signs/'+sign+'.gif" width=15'+
				' height=12 title="'+ptm[3]+'">'; else sign = '';
				if (ptm[2]!='??') info = info_icon(ptm[1])+' '; else info = '';
				if (w[13]<0) info = binfo_icon(w[13])+' ';
				nick = ptm[1];
				if (nick==your_nick) nick='<font color=#994444 class=items>'+nick+'</font>';
				else nick='<font  color=#449944 class=items>'+nick+'</font>';
				nick = '<b>'+nick+'</b>[<font class=lvl>'+ptm[2]+'</font>]';
				pt1 += sign+nick+info;
			}
		for (j=0;j<p2.length && p2[j];j++)
			{
				ptm = p2[j].split('|');
				sign = ptm[0];
				if (sign!='none' && sign) sign = '<img src="images/signs/'+sign+'.gif" width=15'+
				' height=12 title="'+ptm[3]+'">'; else sign = '';
				if (ptm[2]!='??') info = info_icon(ptm[1])+' '; else info = '';
				nick = ptm[1];
				if (nick==your_nick) nick='<font color=#994444 class=items>'+nick+'</font>';
				else nick='<font  color=#444499 class=items>'+nick+'</font>';
				nick = '<b>'+nick+'</b>[<font class=lvl>'+ptm[2]+'</font>]';
				pt2 += sign+nick+info;
			}
		radio = '<div style="width:100px;"><a href=main.php?cat=1&ar_loc=2&id='+w[13]+' class=blocked>ПРИНЯТЬ</a></div>';
		if (!((orujd==0 || orujd==w[1]) && can_join)) radio = '<div style="width:100px;display:inline;"><a class=blocked>принять</a></div>';
		if (pt2=='') pt2 = '<b>нет соперника</b>';
		maintxt = pt1+' </td><td class=but><i class=timef>против</i></td><td class=but> '+pt2;
		txt += '<tr> <td class=but valign=center><img border="0" src="images/arena/blood_'+w[0]+'.gif" width="17" height="22"> <img border="0" src="images/arena/zayor_'+w[1]+'.gif" width="17" height="22"> '+(w[2]/60)+' <i class=timef>'+ds+'</i><i class=timef>'+bplace+'</i>  '+maintxt+' </td><td width=100 class=but>'+radio+'</td> </tr>';
	}
	df('<table border="0" width="80%"  cellspacing="0" cellpadding="2" bordercolorlight="#DDDDDD" bordercolordark="#FFFFFF">'+txt+'</table>');
}

function show_apps_2()
{
df("<b>Заявки на групповой бой:</b><br>");
if (!apps.length) 
{
	dam('Здесь нет ни одной заявки на поединок.');
	return false;
}
	var w,ds,p1,p2,pt1,pt2,ptm,sign,nick,info,maintxt,radio1,radio2,txt,IN1=0,IN2=0,bplace;
	pt1 = '';
	pt2 = '';
	sign = '';
	info = '';
	nick = '';
	maintxt = '';
	radio = '';
	txt='';
	for (i=0;i<apps.length;i++)
	{
		w = apps[i].split(':');
		ds = str_replace(' ','&nbsp;',w[10]).substr(0,20);
		p1 = w[11].split('•');
		p2 = w[12].split('•');
		bplace = '';
		if (w[14]==1) bplace = 'Поле зелени';
		else if (w[14]==3) bplace = 'Пустыня';
		else if (w[14]==5) bplace = 'Вода';
		pt1 = '';
		pt2 = '';
		for (j=0;j<p1.length;j++)
			{
				IN1++;
				ptm = p1[j].split('|');
				sign = ptm[0];
				if (sign!='none' && sign) sign = '<img src="images/signs/'+sign+'.gif" width=15'+
				' height=12 title="'+ptm[3]+'">'; else sign = '';
				if (ptm[2]!='??') info = info_icon(ptm[1])+' '; else info = '';
				nick = ptm[1];
				if (nick==your_nick) nick='<font color=#994444 class=items>'+nick+'</font>';
				else nick='<font  color=#449944 class=items>'+nick+'</font>';
				nick = '<b>'+nick+'</b>[<font class=lvl>'+ptm[2]+'</font>]';
				pt1 += sign+nick+info+',';
			}
		for (j=0;j<p2.length && p2[j];j++)
			{
				IN2++;
				ptm = p2[j].split('|');
				sign = ptm[0];
				if (sign!='none' && sign) sign = '<img src="images/signs/'+sign+'.gif" width=15'+
				' height=12 title="'+ptm[3]+'">'; else sign = '';
				if (ptm[2]!='??') info = info_icon(ptm[1])+' '; else info = '';
				nick = ptm[1];
				if (nick==your_nick) nick='<font color=#994444 class=items>'+nick+'</font>';
				else nick='<font  color=#444499 class=items>'+nick+'</font>';
				nick = '<b>'+nick+'</b>[<font class=lvl>'+ptm[2]+'</font>]';
				pt2 += sign+nick+info+',';
			}
		pt1 = pt1.substr(0,pt1.length-1);
		pt2 = pt2.substr(0,pt2.length-1);
		radio1 = '<a href=main.php?cat=2&ar_loc=2&id='+w[13]+'&fteam=1 class=bg>принять</a>';
		radio2 = '<a href=main.php?cat=2&ar_loc=2&id='+w[13]+'&fteam=2 class=bg>принять</a>';
		if (!((orujd==0 || orujd==w[1]) && can_join)) 
			{
				radio1 = '<b>принять</b>';
				radio2 = '<b>принять</b>';
			}
		if (your_lvl<w[5] || your_lvl>w[7] || IN1>=w[3]) radio1 = '<b>принять</b>';
		if (your_lvl<w[6] || your_lvl>w[8] || IN2>=w[4]) radio2 = '<b>принять</b>';
		if (pt2=='') pt2 = '<b>нет соперника</b>';
		maintxt = pt1+' против '+pt2;
		if (w[9]>60) w[9] = w[9]+'cек'; else w[9] = 'Меньше минуты';
		txt += '<tr> <td width="17">'+radio1+'</td><td>'+bplace+'</td><td width="17"> <img border="0" src="images/arena/blood_'+w[0]+'.gif" width="17" height="22"></td> <td width="17"> <img border="0" src="images/arena/zayor_'+w[1]+'.gif" width="17" height="22"></td> <td width="10" class="user">'+(w[2]/60)+'</td> <td width="20" class="time">'+ds+'</td> <td width="60" class="items"><b>'+w[3]+'</b>['+w[5]+'-'+w[7]+']</td> <td align="center" class=items> '+maintxt+' </td>  <td width="60" class="items"><b>'+w[4]+'</b>['+w[6]+'-'+w[8]+']</td> <td align="center" class=items> '+w[9]+' </td>  <td width="17">'+radio2+'</td></tr>';
	}
	df('<table border="0" width="100%"  cellspacing="0" cellpadding="2" bordercolorlight="#DDDDDD" bordercolordark="#FFFFFF">'+txt+'</table>');
}

function show_apps_3()
{
df("<b>Заявки на хаотический бой:</b><br>");
if (!apps.length) 
{
	dam('Здесь нет ни одной заявки на поединок. Пока...');
	return false;
}
	var w,ds,p1,pt1,ptm,sign,nick,info,maintxt,radio1,txt,IN1=0,bplace;
	pt1 = '';
	pt2 = '';
	sign = '';
	info = '';
	nick = '';
	maintxt = '';
	radio = '';
	txt='';
	for (i=0;i<apps.length;i++)
	{
		w = apps[i].split(':');
		ds = str_replace(' ','&nbsp;',w[10]).substr(0,20);
		p1 = w[11].split('•');
		bplace = '';
		if (w[14]==1) bplace = 'Поле зелени';
		else if (w[14]==3) bplace = 'Пустыня';
		else if (w[14]==5) bplace = 'Вода';
		pt1 = '';
		for (j=0;j<p1.length;j++)
			{
				IN1++;
				ptm = p1[j].split('|');
				sign = ptm[0];
				if (sign!='none' && sign) sign = '<img src="images/signs/'+sign+'.gif" width=15'+
				' height=12 title="'+ptm[3]+'">'; else sign = '';
				if (ptm[2]!='??') info = info_icon(ptm[1])+' '; else info = '';
				nick = ptm[1];
				if (nick==your_nick) nick='<font color=#994444 class=items>'+nick+'</font>';
				else nick='<font  color=#449944 class=items>'+nick+'</font>';
				nick = '<b>'+nick+'</b>[<font class=lvl>'+ptm[2]+'</font>]';
				pt1 += sign+nick+info+',';
			}
		pt1 = pt1.substr(0,pt1.length-1);
		radio1 = '<a href=main.php?cat=3&ar_loc=2&id='+w[13]+' class=bg>принять</a>';
		if (!((orujd==0 || orujd==w[1]) && can_join))	radio1 = '<b>принять</b>';
		if (your_lvl<w[5] || your_lvl>w[7] || IN1>=w[3]) radio1 = '<b>принять</b>';
		maintxt = pt1;
		if (w[9]>60) w[9] = w[9]+'cек'; else w[9] = 'Меньше минуты';
		txt += '<tr> <td width="17">'+radio1+'</td><td>'+bplace+'</td><td width="17"> <img border="0" src="images/arena/blood_'+w[0]+'.gif" width="17" height="22"></td> <td width="17"> <img border="0" src="images/arena/zayor_'+w[1]+'.gif" width="17" height="22"></td> <td width="10" class="user">'+(w[2]/60)+'</td> <td width="20" class="time">'+ds+'</td> <td width="60" class="items"><b>'+w[3]+'</b>['+w[5]+'-'+w[7]+']</td> <td align="center" class=items> '+maintxt+' </td> <td align="center" class=items width=50> '+w[9]+' </td></tr>';
	}
	df('<table border="0" width="100%"  cellspacing="0" cellpadding="2" bordercolorlight="#DDDDDD" bordercolordark="#FFFFFF">'+txt+'</table>');
}

function info_icon(nick)
{
	return '<img src="images/info.gif" onclick="window.open(\'info.php?p='+nick+'\',\'\',\'width=800,height=600,left=10,top=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\');" style="cursor:pointer">';
}

function binfo_icon(nick)
{
	return '<img src="images/info.gif" onclick="window.open(\'binfo.php?'+Math.abs(nick)+'\',\'\',\'width=800,height=600,left=10,top=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\');" style="cursor:pointer">';
}

function show_apps_4()
{
	df('Тестирование это специальный временный модуль, позволяющий администрации настроить баланс в игре.<br>Опыт и вещи за тестовые бои с ботами не получаются. Спасибо за желание помочь проекту!');
	df(text);
}
