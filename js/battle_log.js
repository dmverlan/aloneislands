var d=document;
var upSc = '12';
d.write ('<SCRIPT src="js/jquery.js?'+upSc+'"></SCRIPT><LINK href=css/main.css?'+upSc+' rel=STYLESHEET type=text/css>');

d.write ('<title>Лог Боя['+bid+'] - AloneIslands.Ru / Страница '+(page+1)+'</title><META Content=\'text/html; charset=windows-1251\' Http-Equiv=Content-type><body class=fightlong>');

var txt = '';
txt += '<center class=logbox>';
txt += '<a class=timef href="aloneislands.ru">AloneIslands.Ru</a> лог боя [№'+bid+']';
txt += '<img src="images/arena/blood_'+inj+'.gif" title="Травматичность" />';
txt += '<img src="images/arena/zayor_'+ins+'.gif" title="Тип боя" />';
if (ltime>0) txt += 'До таймаута <b class=timef>'+ltime+'</b> сек.';
txt += '</center>';
if (fin)
	txt += '<center class=logbox><a class=timef href=battle_log.php?id='+bid+'&results=1>Результаты боя</a></center>'
txt += '<center class=logbox>';
for (var i=0;i<=pages;i++)
{
	if (i==page) 
		txt += ('<a class=pagerS href=battle_log.php?id='+bid+'&page='+i+'>'+(i+1)+'</a>');
	else
		txt += ('<a class=pager href=battle_log.php?id='+bid+'&page='+i+'>'+(i+1)+'</a>');
	if (i<pages) txt += ' | ';
}	
txt += '</center>';

d.write(sbox2(txt));

if (!results)
{
	txt = '';
	var t = '';
	for (var i=0;i<log.length;i++)
	{
		t = log[i][1].split('%');
		txt += '<div class=logbox>';
		for (var j=0;j<t.length;j++)
		{
			txt += '<font class=timef>'+log[i][0]+'</font>'+' '+t[j];
			if (j<t.length-1)
				txt += '<br />';
		}
		txt += '</div>';
	}
	d.write(sbox2(txt));
}
else
{
	d.write(sbox2(d.getElementById('info').innerHTML));
}




d.write('</body>');
/* */
function sbox(t)
{
	return '<div align=left><div class="corners"><div class="inner"><div class="content">'+t+'</div></div></div></div>';
}

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