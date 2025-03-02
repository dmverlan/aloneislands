function build_pers(sh,shd,oj,ojd,or1,or1d,po,pod,z1,z1d,z2,z2d,z3,z3d,sa,sad,na,nad,pe,ped,or2,or2d,ko1,ko1d,ko2,ko2d,br,brd,pers,inv,sign,nick,level,hp,mhp,ma,mma,tire,kam1,kam2,kam3,kam4,kam1d,kam2d,kam3d,kam4d,curh, maxh, curm, maxm, hp_int, ma_int,ss,sl,su,szd,szn,sp,sup,MONEY,dmoney,KB,gray1,gray2,gray3,gray4,gray5,udmin,udmax,rank_i,calling,victories,losses,experience,peace_experience,exp_to_lvl,zeroing,inv,dil,exp_proc,ws1,ws2,ws3,ws4,ws5,ws6,free_skills,help,ref,coins)
{
//document.body.style.overflow = 'hidden';
if(typeof _SHOW_EXP == 'undefined') 
{
	var __SHOW_EXP = 0;
}else
{
	if(_SHOW_EXP)
		var __SHOW_EXP = 1;
}
if(inv!=2 && inv!=1)
	document.getElementById('_top').innerHTML = "<table border=0 cellspacing=0 cellspadding=0><tr><td style='width:200px;'></td><td align=center style='width:250px;' id=_pers></td><td width="+(top.DWidth-880)+"></td><td width=100 class=Luser>Помощь</td><td width=100 class=Luser>Умения</td><td width=100 class=Luser>Пароль</td><td width=100 class=Luser>Настройки</td></tr></table>";
else
	document.getElementById('_top').innerHTML = "<table border=0 cellspacing=0 cellspadding=0><tr><td style='width:200px;'></td><td align=center style='width:250px;' id=_pers></td><td width=80></td></tr></table>";

var ss1=ss;
var sl1=sl;
var su1=su;
var szd1=szd;
var szn1=szn;
var sp1=sp;

var TEXT = '';

var ExpText = '';

if (parseInt(ss)<1) ss=1;
if (parseInt(sl)<1) sl=1;
if (parseInt(su)<1) su=1;
if (parseInt(szd)<1) szd=1;
if (parseInt(szn)<1) szn=1;
if (parseInt(sp)<1) sp=1;
if (ws1!='0' && ws1!=undefined)ss='<b class=Lstat>'+ss+'</b>'+' <span class=small color=#fffddf>('+(ss-ws1)+'<font color='+((ws1>0)?'#EEFFEE':"FFEEEE")+'>'+ws1+'</font>)</span>';
else ss='<b class=Lstat>'+ss+'</b>';
if (ws2!='0' && ws2!=undefined)sl='<b class=Lstat>'+sl+'</b>'+' <span class=small color=#fffddf>('+(sl-ws2)+'<font color='+((ws2>0)?'#EEFFEE':"FFEEEE")+'>'+ws2+'</font>)</span>';
else sl='<b class=Lstat>'+sl+'</b>';
if (ws3!='0' && ws3!=undefined)su='<b class=Lstat>'+su+'</b>'+' <span class=small color=#fffddf>('+(su-ws3)+'<font color='+((ws3>0)?'#EEFFEE':"FFEEEE")+'>'+ws3+'</font>)</span>';
else su='<b class=Lstat>'+su+'</b>';
if (ws4!='0' && ws4!=undefined)szd='<b class=Lstat>'+szd+'</b>'+' <span class=small color=#fffddf>('+(szd-ws4)+'<font color='+((ws4>0)?'#EEFFEE':"FFEEEE")+'>'+ws4+'</font>)</span>';
else szd='<b class=Lstat>'+szd+'</b>';
if (ws5!='0' && ws5!=undefined)szn='<b class=Lstat>'+szn+'</b>'+' <span class=small color=#fffddf>('+(szn-ws5)+'<font color='+((ws5>0)?'#EEFFEE':"FFEEEE")+'>'+ws5+'</font>)</span>';
else szn='<b class=Lstat>'+szn+'</b>';
if (ws6!='0' && ws6!=undefined)sp='<b class=Lstat>'+sp+'</b>'+' <span class=small color=#fffddf>('+(sp-ws6)+'<font color='+((ws6>0)?'#EEFFEE':"FFEEEE")+'>'+ws6+'</font>)</span>';
else sp='<b class=Lstat>'+sp+'</b>';
var d=document;
TEXT += ('<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>');
var givem = 'javascript:peredatm()';
if (level<5) givem = 'javascript:void(0)';
if (MONEY) MONEY='<a href="'+givem+'" class=Lblocked>&nbsp;'+MONEY+'</a>'; else MONEY='<a href="'+givem+'" class=Lblocked>&nbsp;'+MONEY+'</a>';

var MoneyText = '<b class=about>&nbsp;Деньги<br><img src="images/emp.gif" width=180 height=3><img src="images/DS/hr.png" width=180 height=3></b><table border=0 cellspacing=0 cellspadding=0> <tr> <td class=white width=15 nowrap align=right><img src="images/gameplay/1_2.png"></td><td>'+MONEY+'</td> </tr>';
if (dmoney>0) MoneyText += '<tr> <td class=white width=15 align=right><img src="images/gameplay/1_1.png"></td><td><a href=main.php?go=pers&gopers=service class=Lblocked title="Бриллианты">'+dmoney+'</a></td> </tr>';
if (coins>0) MoneyText += ' <tr> <td class=white width=15 title="Количество ваших пергаментов , полученных за проведение отличных боёв. Они могут вам понадобиться в университете." align=right><img src="images/gameplay/1_3.png"></td><td><span class=Lstat>'+coins+'</span></td> </tr>';
MoneyText += '</table><img src=images/emp.gif height=5><br>';
TEXT +=('<td valign="top" width="200" align=center style="background-image: url(\'images/DS/main_green_column_left.png\'); background-position: right top; background-repeat: no-repeat; height:100%;"><div style="background-image: url(\'images/DS/main_bg.png\'); height:100%;"><div style="text-align:left; width:90%;">');
//d.write(sbox2b());
//d.write(''+MONEY+'');

var TIP_s1 = 'onmouseover="s_des(event,\'|Сила влияет на урон, наносимый при физическом контакте в бою.\')" onmouseout="h_des()" onmousemove=move_alt(event)';
var TIP_s2 = 'onmouseover="s_des(event,\'|Реакция влияет на шанс увернуться в бою от ударов противника, а также уменьшает шанс противника увернуться от вашей атаки.\')" onmouseout="h_des()" onmousemove=move_alt(event)';
var TIP_s3 = 'onmouseover="s_des(event,\'|Удача влияет на шанс нанести сокрушительный удар в бою.\')" onmouseout="h_des()" onmousemove=move_alt(event)';
var TIP_s4 = 'onmouseover="s_des(event,\'|Здоровье повышает вашу жизнь, броню и влияет на массу, которую может носить ваш персонаж.\')" onmouseout="h_des()" onmousemove=move_alt(event)';
var TIP_s5 = 'onmouseover="s_des(event,\'|Интеллект позволяет осваивать мирные профессии. Интеллект не имеет значения во время боя.\')" onmouseout="h_des()" onmousemove=move_alt(event)';
var TIP_s6 = 'onmouseover="s_des(event,\'|Сила воли повышает количество маны и увеличивает урон от ваших заклинаний.\')" onmouseout="h_des()" onmousemove=move_alt(event)';

TEXT +=(MoneyText);
TEXT +=('<b class=about>&nbsp;Основные<br><img src="images/emp.gif" width=180 height=3><img src="images/DS/hr.png" width=180 height=3></b><table border=0 width=100% cellspacing=0 cellspadding=0><tr> <td width=24><img src="images/DS/stats_s1.png"></td><td width=50% class=white height=23 '+TIP_s1+'>Сила</td> <td class=Lstat align=right><div id=sila>'+ss+'</div></td> </tr> <tr></tr> <tr><td><img src="images/DS/stats_s2.png"></td><td width=50% class=white height=23 '+TIP_s2+'>Реакция</td> <td class=Lstat align=right><div id=lovk>'+sl+'</div></td> </tr><tr></tr> <tr> <td><img src="images/DS/stats_s3.png"></td><td width=50% class=white height=23 '+TIP_s3+'>Удача</td> <td class=Lstat align=right><div id=udacha>'+su+'</div></td> </tr> <tr></tr> <tr><td><img src="images/DS/stats_s4.png"></td><td width=50% class=white height=23 '+TIP_s4+'>Здоровье</td> <td class=Lstat align=right><div id=zdorov>'+szd+'</div></td> </tr><tr></tr> <tr><td><img src="images/DS/stats_s5.png"></td> <td width=50% class=white height=23 '+TIP_s5+' nowrap>Интеллект</td> <td class=Lstat align=right><div id=znanya>'+szn+'</div></td> </tr> <tr><td><img src="images/DS/stats_s6.png"></td> <td width=50% class=white height=23 '+TIP_s6+' nowrap>Сила&nbsp;Воли</td> <td class=Lstat align=right><div id=power>'+sp+'</div></td> </tr> <tr></tr> <tr> <td colspan=6 align=center><div id=ups class=timef></div></td></tr></table></center>');
if (sup>0 && inv!=2) TEXT +=('<div id=SAVEstats class=white align=center><a onclick="save()" class=but href="javascript:void(0)">Сохранить</a></div>');
/*if (DecreaseDamage)
	KB += ' [<i class=timef title="Понижение физического урона '+DecreaseDamage+'%">'+DecreaseDamage+'%</i>]';*/
TEXT +=('<b class=about>&nbsp;Модификаторы<br><img src="images/emp.gif" width=180 height=3><img src="images/DS/hr.png" width=180 height=3></b><table border="0" width="100%" cellspacing="0">');
TEXT += ("<tr><td class=Swhite>Класс&nbsp;Брони:</td><td class=Lstat width=90% align=center>"+KB+"</td></tr>");
if (udmax>2) TEXT +=("<tr><td class=Swhite>Удар:</td><td class=Lmfb width=90%>"+udmin+"-"+udmax+"</td></tr>");
if (gray1!=0) TEXT +=("<tr><td class=Swhite>Сокрушение:</td><td class=Lmfb width=90%>"+gray1+"%</td></tr>");
if (gray2!=0) TEXT +=("<tr><td class=Swhite>Уловка:</td><td class=Lmfb width=90%>"+gray2+"%</td></tr>");
if (gray3!=0) TEXT +=("<tr><td class=Swhite>Точность:</td><td class=Lmfb width=90%>"+gray3+"%</td></tr>");
if (gray4!=0) TEXT +=("<tr><td class=Swhite>Стойкость:</td><td class=Lmfb width=90%>"+gray4+"%</td></tr>");
if (gray5!=0) TEXT +=("<tr><td class=Swhite>Ярость:</td><td class=Lmfb width=90%>"+gray5+"%</td></tr>");
if (rank_i>15)TEXT +=("<tr><td class=Swhite>Ранк:</font></td><td class=Lmfb width=90%>"+rank_i+"</td></tr>");
if (calling!="")TEXT +=("<tr><td><font class=white>Звание:</font></td><td align=center class=white>"+calling+"</td></tr>");
TEXT +=("</table>");
if(inv!=2)
{
if (exp_proc>100) exp_proc=100;
if(__SHOW_EXP)ExpText += ("<br><center class=dark><b>Линия Опыта  ["+(100-exp_proc)+"%]</b></center>");
exp = exp_proc;
if (exp<0) exp=0;

if(__SHOW_EXP)
{
ExpText +=('<center><br><table border="0" width="200" cellspacing="0" cellpadding="0"><tr><td align=center valign=center height=20 nowrap><img src="images/DS/expline_circle.png"><img src="images/DS/expline.gif" width='+(96-exp)+'% height=6><img src="images/DS/expline_empty.gif" width='+(exp-3)+'% height=3><img src="images/DS/expline_circle.png"></td></tr></table></center><br>');
ExpText += ("<center><table border=0 width=300 cellspacing=0 cellspadding=0><tr><td class=dark><b>Побед:</b></td><td align=right><b class=dark>"+victories+"</b></td></tr><tr><td colspan=3 align=center><img src='images/DS/hr.png' width=290 height=3></td></tr><tr><td class=dark><b>Поражений:</b></td><td class=dark align=right><b>"+losses+"</b></td></tr><tr><td colspan=3 align=center><img src='images/DS/hr.png' width=290 height=3></td></tr><tr><td class=dark><font color=#800000><b>Боевой опыт:</b></td><td class=dark align=right><font color=#800000><b>"+experience+"</b></font></td></tr><tr><td colspan=3 align=center><img src='images/DS/hr.png' width=290 height=3></td></tr><tr><td class=dark><font color=#334BBB><b>Мирный опыт:</b></td><td class=dark align=right><font color=#004BBB><b>"+peace_experience+"</b></font></td></tr><tr><td colspan=3 align=center><img src='images/DS/hr.png' width=290 height=3></td></tr><tr><td class=dark><font color=#0A8900><b>До уровня:</b></td><td class=dark align=right><font color=#0A8900><b>"+exp_to_lvl+"</b></font></td></tr></table></center>");
}
}
//d.write(sbox2e());
TEXT +=('</div></div></td><td width=25 style="background-image: url(\'images/DS/main_bg.png\');"></td>');
TEXT +=('<td valign="top" width=250 style="background-image: url(\'images/DS/main_bg.png\');">');
d.write(TEXT);
if (inv!=2 && sup>0)start(ss1,sl1,su1,szd1,szn1,sp1,sup,level);
show_pers_new(sh,shd,oj,ojd,or1,or1d,po,pod,z1,z1d,z2,z2d,z3,z3d,sa,sad,na,nad,pe,ped,or2,or2d,ko1,ko1d,ko2,ko2d,br,brd,pers,inv,sign,nick,level,hp,mhp,ma,mma,tire,kam1,kam2,kam3,kam4,kam1d,kam2d,kam3d,kam4d,inv,dil);
TEXT = '';
TEXT +=('<div id=aurasc class=aurasc style="background-image: url(\'images/bg.png\'); text-align:center;"></div>'+down_white_table('100%','aura_down',0)+'</td><td width=10 style="background-image: url(\'images/DS/main_bg.png\');"></td>');

TEXT +=('<td align="left" valign="top" height=100% style="background-image: url(\'images/DS/main_green_column_right.png\'); background-position: left top; background-repeat: no-repeat;"><div style="background-image: url(\'images/bg.png\');height:100%;"><div style="overflow-y: auto; height: 460px;" id=weapons>');
if(inv!=1 && inv!=2) {
var helpimg = '';
if (help == 0) helpimg = '<img src="images/design/warningred.gif" width=10/>';
var lawimg = '';
if (help == 1) lawimg = '<img src="images/design/warningred.gif" width=10/>';
var warnimg = '';
if (!sup && free_skills) warnimg = '<img src="images/DS/attention.png" />';
TEXT +=('<div style="width:100%;background-image: url(\'images/DS/blackbg.jpg\');"><table border=0 width=100% height=39 cellspacing=0 cellspadding=0 style="height:39px;"><tr><td align=center></div><td width=80 align=center><a href=main.php?gopers=info><img src="images/DS/help.png"></a></td><td width=100 align=center><a href=main.php?gopers=um><img src="images/DS/skills.png"></a> </td><td width=100 align=center> <a href=main.php?gopers=parol><img src="images/DS/password.png"></a> </td><td width=100 align=center> <a href=main.php?gopers=options><img src="images/DS/options.png"></a></td></tr></table></div>');
TEXT +=('<center><table border=0 width=98% cellspacing=0 cellspadding=0 style="height:16px;"><tr><td style="background-image: url(\'images/DS/graybg_left.png\'); background-position:bottom left; height:16px; width:12px;"></td><td style="background-image: url(\'images/DS/graybg.png\');" align=center nowrap><b>');

TEXT +=('<a href=forum/ target=_blank class=nt>Форум</a> | <a href=main.php?gopers=law class=nt>'+lawimg+'Законы</a>  ');
if (zeroing>0)TEXT +=("| <a href='javascript:void(0)' onclick=\"obnyl()\" class=nt>Обнулиться [<b class=hp>"+zeroing+"</b>]</a> ");
if (sign!='none')TEXT +=("| <a href=main.php?go=addon&action=addon&gopers=clan class=nt>Клан </a> ");
TEXT +=('| <a href=main.php?go=self class=nt>Личное</a> ');
TEXT +=('| <a href=main.php class=nt>Назад</a>');
if(REF_COMP)
	TEXT +=(' | <a href=main.php?gopers=ref_competition class=nt style="color:#AA0000">Конкурс</a>');
	
TEXT +=('</b></td><td style="background-image: url(\'images/DS/graybg_right.png\'); background-position:bottom right; height:16px; width:12px;"></td></tr></table></center>');

if(level > 1) 
{
TEXT +=('<center><table border=0 width=60% cellspacing=0 cellspadding=0 style="height:16px;"><tr><td style="background-image: url(\'images/DS/graybg_left.png\'); background-position:bottom left; height:16px; width:12px;"></td><td style="background-image: url(\'images/DS/graybg.png\');" align=center nowrap><b>');
TEXT +=(" <a href=main.php?gopers=service class=nt>Сервис [<img src=images/signs/diler.gif>]</a> ");
if (ref>0)	TEXT +=("| <a href=main.php?gopers=referals class=nt>Рефералы</a> ");
if(level > 9) TEXT +=('| <a href=main.php?gopers=student class=nt>Ученик</a> ');
TEXT +=('</b></td><td style="background-image: url(\'images/DS/graybg_right.png\'); background-position:bottom right; height:16px; width:12px;"></td></tr></table></center>');
}
TEXT +=('<center><div style="width:98%;overflow-y: auto; height: 380px;" id=information></div></center>');
}
TEXT +=('</div></div>'+down_white_table('100%',0,1)+'</td></tr></table>');
d.write(TEXT);
if (inv!=1 && inv!=2)
d.getElementById('information').innerHTML = ExpText+d.getElementById('inf_from_php').innerHTML;
else
d.getElementById('weapons').innerHTML = d.getElementById('inf_from_php').innerHTML;
d.getElementById('inf_from_php').innerHTML = '';

if (inv!=2) ins_HP(curh, maxh, curm, maxm, hp_int, ma_int);
}
function down_white_table(a,b,v)
{
if (b) b = ' id='+b+' ';
else b = '';
if(v) v = '';
else v = 'display:none';
return '<div '+b+' style="'+v+'"><table style="width:'+a+';height:12px;" border=0 cellspadding=0 cellspacing=0><tr><td style="width:8px;height:12px;background-image:url(\'images/left_bottom.png\');background-position:right top;"></td><td style="height:12px;background-image:url(\'images/bottom.png\');"></td><td style="width:8px;height:12px;background-image:url(\'images/right_bottom.png\');background-position:left top;"></td></tr></table></div>';
}
function obnyl () {
if (confirm ('Вы действительно хотите обнулиться?')) {location='main.php?gopers=obnyl';}
}
function conf(url) {
if (confirm('Вы действительно хотите выкинуть этот предмет?')) location = url;
}
function confc(url) {
if (confirm('Вы действительно хотите пожертвовать клану этот предмет?')) location = url;
}
function conf_sale(url) {
if (confirm('Вы действительно хотите сдать этот предмет?')) location = url;
}

var AuraCounts = 0;
function view_auras(text,where)
{
	var ars = text.split('|');
	var ar,t;
	if(ars.length>1 && document.getElementById('aura_down'))
		document.getElementById('aura_down').style.display = 'block';
	for(var i=0;i<ars.length;i++)
	{
	if (ars[i]!='')
	{
	ar = ars[i].split('#');
	if (ar[0].indexOf('.gif')!=-1) ar[0] = ar[0].substr(0,ar[0].length-4);
	t = '<img src="images/magic/'+ar[0]+'.gif" onmouseover="s_des(event,\'0|<img src=images/magic/'+ar[0]+'.gif align=left>'+ar[1]+'\')" onmouseout="h_des()" onmousemove=move_alt(event) height=30>';
	AuraCounts++;
	if ((i+1)%10==0 && i!=0) t+= '<br>';
	if (where && document.getElementById(where))
	 document.getElementById(where).innerHTML += t;
	else
		if(document.getElementById('aurasc'))
			document.getElementById('aurasc').innerHTML += t;
	}
	}
}