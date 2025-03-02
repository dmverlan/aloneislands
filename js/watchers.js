if (frameResizer==undefined)
var frameResizer = '<a class=blocked href="javascript:show_wtch()" id=wtch>[Показать]Дополнительная информация для смотрителей о персонаже</a>';

document.write('<script type="text/javascript" src="js/jquery.js?2"></script><center><table border="0" width="90%" cellspacing="0" cellpadding="0" class=but><tr> <td colspan="5" class="but2" align="center">'+frameResizer+'</td> </tr> <tr> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=mpb&'+rnd()+'" class=bg>Заклинание молчания, тюрьма, блок ,пометка.</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=sells&'+rnd()+'"class=bg>Продажи/передачи вещей и денег</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=pass&'+rnd()+'"class=bg>Смены пароля</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=onecomp&'+rnd()+'"class=bg>Заходы с одного комп.</a></td></tr> <tr> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=ip"class=bg>Просмотр IP адресов</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=rmpb&'+rnd()+'"class=bg>Наложенные молч., тюрьмы, блоки</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=w_z&'+rnd()+'"class=bg>Заметки смотрителей</a></td> <td class="but2" align="center" width="30%"><a href="info.php?p='+nick+'&do_w=battles&'+rnd()+'"class=bg>Бои</a></td></tr> <tr> <td class="inv" align="center" width="90%" colspan="9">');
var r=0;
function show_mpb(m,p,b,w,i,d,u){
document.write('<form action="info.php?p='+nick+'&do_w=mpb" method=post name=mpb onsubmit="subm();return false;"><b class=about>Инструменты:</b><table border="0" cellpadding="0" cellspacing="0" id=tbl_main><tr><td align="center" width="450" class=gray>Молчание</td><td>');
if (m>0) document.write('<select style="width:140px" size="1" name="molch" class=items><option selected value="">Время</option><option  value="-1">Снять</option><option value="5">5 минут</option><option value="10">10 минут</option><option value="15">15 минут</option><option value="30">30 минут</option><option value="60">1 час</option><option value="120">2 часa</option><option value="180">3 часa</option><option value="360">6 часов</option><option value="1440">24 часа</option></select><input type=text class=but2 name=reason1 title="Причина">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Кара</td><td width="500">');
if (u>0) document.write('<select style="width:140px" size="1" name="punishment" class=items><option selected value="">Время</option><option value="-1">Снять</option><option value="5">5 минут</option><option value="10">10 минут</option><option value="15">15 минут</option><option value="30">30 минут</option><option value="60">1 час</option><option value="360">6 часов</option><option value="1440">24 часа</option><option value="2880">48 часов</option></select><input type=text class=but2 name=reason2 title="Причина">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr>');
document.write('<tr><td align="center" width="450" class=gray>Тюрьма</td><td>');
if (p>0) document.write('<select style="width:140px" size="1" name="prisontime" class=items><option selected value="">Время</option><option value="-1">Выпустить</option><option value="1">1 день</option><option value="3">3 дня</option><option value="7">1 неделя</option><option value="14">2 недели</option><option value="30">1 месяц</option><option value="60">2 месяца</option><option value="365">1 год</option></select><input type=text class=but2 name=prison title="Причина">');else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Блокирование</td><td width="500" colspan="2">');
if (b>0) document.write('<select style="width:140px" size="1" name="blockt" class=items><option selected value="">Выбор</option><option value="1">Заблокировать</option><option value="2">Разблокировать</option></select><input type=text class=but2 name=block title="Причина">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Блокирование информации</td><td width="500" colspan="2">');
if (i>0) document.write('<select style="width:140px" size="1" name="blockit" class=items><option selected value="">Выбор</option><option value="1">Заблокировать</option><option value="2">Разблокировать</option></select><input type=text class=but2 name=blocki title="Причина">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Выгнать</td><td colspan="2">');
if (b>0 && cc!='none') document.write('<input type=button class=but value="Выгнать из клана" onclick="if (confirm(\'Выгнать?\'))location = \'info.php?p='+nick+'&clan_go_out=1&do_w=1\'">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Раздеть</td><td colspan="2">');
if (b>0) document.write('<input type=button class=but value="Раздеть" onclick="if(confirm(\'Раздеть?\'))location = \'info.php?p='+nick+'&wear_out=1&do_w=1\'">'); else document.write('<i>Недоступно</i>');
document.write('</td></tr><tr><td align="center" width="450" class=gray>Пометка</td><td width="500" colspan="2">');
if (w>0) document.write('<input type=text class=but2 name=pometka>'); else document.write('<i>Недоступно</i>');
if (d>0) document.write('<tr><td align="center" width="450" class=about>Продать Бриллианты<br>[Доступно для продажи: <B>'+r+' Бр.</B>]</td><td width="500" colspan="2"><input type=text class=but2 name=d_num title="Количество"></td>');
if (b>0)
document.write('<tr><td align="center" width="450" class=gray>Вытащить из бага</td><td width="500" align=center><input type=button class=but  value="Вытащить" onclick="location = \'info.php?p='+nick+'&bug=1&do_w=1\'" style="width:90%"></td></td></tr>');
document.write('<tr><td align="center" width="914" colspan="3"><input type=submit class=login value="Применить" style="width:100%;height:50px;cursor:pointer;"></td>');
document.write('</tr></table></form></td></tr></table>');
}
function subm()
{
	if (document.mpb.d_num)
	 if (document.mpb.d_num.value)
	  if (!confirm("Вы действительно хотите продать валюту???"))return;
	document.mpb.submit();
}
function show_message(message){
document.write(message);
document.write('</td></tr></table>');
}
function zametki(str)
{
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	var lines = str.split('@');
	var s;
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		document.write('<td><font class=time>'+s[0]+'</font></td><td> '+s[1]+' </td><td>(<b>'+s[2]+'</b>)</td>');
		}
		document.write('</tr>');
	}
document.write('</table></td></tr></table>');
}
function rmpb(molch,blocks)
{
document.write('Молчания:<br>');
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	var lines = molch.split('@');
	var s;
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		document.write('<td><font class=time>'+s[0]+'</font></td><td> '+s[1]+'</td><td> (<b>'+s[2]+'</b>)</td>');
		}
		document.write('</tr>');
	}
document.write('</table><hr>Блоки / тюрьмы:<br>');
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	lines = blocks.split('@');
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		if (s[1]>5)
		document.write('<td><font class=time>'+s[0]+'</font></td><td> Тюрьма, причина '+s[2]+'</td><td> (<b>'+s[3]+'</b>)</td>');
		if (s[1]==0)
		document.write('<td><font class=time>'+s[0]+'</font></td><td> Выпуск из тюрьмы </td><td>(<b>'+s[3]+'</b>)</td>');
		if (s[1]==1)
		document.write('<td><font class=time>'+s[0]+'</font></td><td> Блок, причина '+s[2]+' </td><td>(<b>'+s[3]+'</b>)</td>');
		if (s[1]==2)
		document.write('<td><font class=time>'+s[0]+'</font></td><td> Разблок </td><td>(<b>'+s[3]+'</b>)</td>');
		}
		document.write('</tr>');
	}
document.write('</table></td></tr></table>');
}
function ip(str)
{
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	var lines = str.split('@');
	var s;
	for (var i=lines.length-1;i>=0;i--) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		document.write('<td><font class=time>'+s[0]+'</font></td><td> '+s[1]+' </td>');
		}
		document.write('</tr>');
	}
document.write('</table></td></tr></table>');
}
function pass(str)
{
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	var lines = str.split('@');
	var s;
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!='')
		document.write('<td><font class=time>'+lines[i]+'</font></td><td> Смена </td>');
		document.write('</tr>');
	}
document.write('</table></td></tr></table>');
}
function sells(sales,tr)
{
document.write('Продажи:<br>');
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	var lines = sales.split('@');
	var s;
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		document.write('<td><font class=time>'+s[0]+'</font></td><td>'+s[1]+'</td>');
		}
		document.write('</tr>');
	}
document.write('</table><hr>Передачи:<br>');
document.write('<table border="0" cellpadding="0" cellspacing="3">');
	lines = tr.split('@');
	for (var i=0;i<lines.length;i++) 
	{
		document.write('<tr>');
		if (lines[i]!=''){
		s=lines[i].split('|');
		if (s[1]==0)
		document.write('<td><font class=time>'+s[0]+'</font></td><td><<< Передано денег <b>'+s[2]+' LN</b> для '+s[3]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[3]+'\',\'_blank\')" src=images/info.gif></td>');
		if (s[1]==3)
		document.write('<td><font class=time>'+s[0]+'</font></td><td>>>> Принято денег <b>'+s[2]+' LN</b> от '+s[3]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[3]+'\',\'_blank\')" src=images/info.gif></td>');
		if (s[1]==1)
		document.write('<td><font class=time>'+s[0]+'</font></td><td><<< Передано <b>'+s[2]+'</b>(гос '+s[3]+') для '+s[4]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[4]+'\',\'_blank\')" src=images/info.gif></td>');
		if (s[1]==2)
		document.write('<td><font class=time>'+s[0]+'</font></td><td>>>> Принято <b>'+s[2]+'</b>(гос '+s[3]+') от '+s[4]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[4]+'\',\'_blank\')" src=images/info.gif></td>');
		if (s[1]==4)
		{
		 if (s[2]==20) var travm='лёгкой';
		 if (s[2]==50) var travm='средней';
		 if (s[2]==80) var travm='тяжёлой';
			document.write('<td><font class=time>'+s[0]+'</font></td><td><<< Излечение '+travm+'   травмы для '+s[3]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[4]+'\',\'_blank\')" src=images/info.gif></td>');
		}
		if (s[1]==5)
		{
		 if (s[2]==20) var travm='лёгкой';
		 if (s[2]==50) var travm='средней';
		 if (s[2]==80) var travm='тяжёлой';
			document.write('<td><font class=time>'+s[0]+'</font></td><td>>>> Излечение '+travm+'   травмы от '+s[3]+'<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='+s[4]+'\',\'_blank\')" src=images/info.gif></td>');
		}
				 
		}
		document.write('</tr>');
	}
document.write('</table></td></tr></table>');
}

function rnd()
{
	return 'rand='+Math.random();
}

function rmpb_filter(nick,c1,c2,c3,c4,c5,from,to)
{
c1 = (c1) ? 'CHECKED' : '';
c2 = (c2) ? 'CHECKED' : '';
c3 = (c3) ? 'CHECKED' : '';
c4 = (c4) ? 'CHECKED' : '';
c5 = (c5) ? 'CHECKED' : '';
document.write('<center class=but2><form method=post action="info.php?p='+nick+'&do_w=rmpb" name=rmpb><i>Фильтр</i> <table border="1" width="100%" cellspacing="0" cellpadding="0"> <tr> <td align="center" width="20%">Молчанки<input type="checkbox" name="C1" value="1"'+c1+'></td> <td align="center" width="20%">Блоки<input type="checkbox" name="C2" value="1"'+c2+'></td> <td align="center" width="20%">Тюрьмы<input type="checkbox" name="C3" value="1"'+c3+'></td> <td align="center" width="20%">Кары<input type="checkbox" name="C4" value="1"'+c4+'></td> <td align="center" width="20%">Блоки инфы<input type="checkbox" name="C5" value="1"'+c5+'></td> </tr> <tr> <td align="center" width="20%"></td> <td align="center" width="20%"></td> <td align="center" width="20%"><input type="text" name="from" size="8" value="'+from+'" class=but>-<input type="text" name="to" size="8" value="'+to+'" class=but></td> <td align="center" width="20%"></td> <td align="center" width="20%"> </td> </tr> <tr> <td align="center" width="100%" colspan="5"> <input type="button" value="Применить" class=login onclick="rmpb_submit();" style="width:90%"></td> </tr></table></form></center>');
}
function rmpb_submit()
{
	document.rmpb.submit();
}
function transfer(nick,c1,c2,c3,from,to)
{
c1 = (c1) ? 'CHECKED' : '';
c2 = (c2) ? 'CHECKED' : '';
c3 = (c3) ? 'CHECKED' : '';
document.write('<form method=post action="info.php?p='+nick+'&do_w=sells" name=rmpb> <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolordark="#FFFFFF" bordercolorlight="#DDDDDD" bgcolor="#F0EFEF"> <tr> <td colspan="5" align="center">:фильтр:</td> </tr> <tr> <td align="center" width="33%">Продажи<input type="checkbox" name="C1" value="1"'+c1+'></td> <td align="center" width="33%">Передачи<input type="checkbox" name="C2" value="1"'+c2+'></td> <td align="center" width="33%">Передачи денег<input type="checkbox" name="C3" value="1"'+c3+'></td></tr> <tr> <td align="center" width="33%">ДАТА:</td> <td align="center" width="33%">от <input type="text" name="from" size="20" value="'+from+'"> </td>  <td align="center" width="33%"> до <input type="text" name="to" size="20" value="'+to+'"></td> </tr> <tr> <td align="center" width="100%" colspan="5"> <input type="button" value="Применить" class=login onclick="rmpb_submit();"></td> </tr></table></form>');
}

function show_wtch()
{
	top.document.getElementById('frmset').rows = "*,500";
	document.getElementById('wtch').innerHTML = '[Скрыть]Дополнительная информация для смотрителей о персонаже';
	document.getElementById('wtch').href = 'javascript:hide_wtch()';
}
function hide_wtch()
{
	top.document.getElementById('frmset').rows = "*,20";
	document.getElementById('wtch').innerHTML = '[Показать]Дополнительная информация для смотрителей о персонаже';
	document.getElementById('wtch').href = 'javascript:show_wtch()';
}

function select_all_checks()
{
	$(":checkbox").each(function(){this.checked=true;});
}