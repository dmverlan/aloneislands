var attack_ident = 0;
var zz1='',zz2='';
var a='';
var fs=0;
var aura_od = 0;
var aura_text = '';
freq = new Array();
t1u=new Array();
t2u=new Array();
t1s=new Array();
t2s=new Array();
t1c=new Array();
t2c=new Array();
t1cm=new Array();
t2cm=new Array();
t1m=new Array();
t2m=new Array();
t1x=new Array();
t2x=new Array();
t1y=new Array();
t2y=new Array();
t1l=new Array();
t2l=new Array();
t1h=new Array();
t2h=new Array();
t1b=new Array();
t2b=new Array();
t1bot=new Array();
t2bot=new Array();
var menu='';
var XOD=0;
var all_fighters='';
var field;
var ug=0,uj=0,un=0,bg=0,bj=0,bn=0,ut=0,bt=0;
var od_use=0;

function show_fight_head(oruj,travm,timeout)
{
if (ffreq.indexOf(':'))
freq = ffreq.split(":");
else
{
	freq[0]=1;
	freq[1]=1;
	freq[2]=0;
	freq[3]=1;
}

var f_f = document.getElementById('fight');
var vs_link;
p=new Array();
p = team1.split('@');
p_e = new Array();
for (var i=0;i<p.length-1;i++)
 {
	p_e = p[i].split("|");
	t1s[i] = p_e[0];
	t1u[i] = p_e[1];
	t1l[i] = p_e[2];
	t1c[i] = parseInt(p_e[3]);
	t1h[i] = parseInt(p_e[4]);
	if (t1c[i]>t1h[i]) t1c[i]=t1h[i];
	t1cm[i] = parseInt(p_e[5]);
	t1m[i] = parseInt(p_e[6]);
	if (t1cm[i]>t1m[i]) t1cm[i]=t1m[i];
	t1x[i] = parseInt(p_e[7]);
	t1y[i] = parseInt(p_e[8]);
	t1b[i] = p_e[9];
 }
p = team2.split('@');
for (i=0;i<p.length-1;i++)
 {
	p_e = p[i].split("|");
	t2s[i] = p_e[0];
	t2u[i] = p_e[1];
	t2l[i] = p_e[2];
	t2c[i] = parseInt(p_e[3]);
	t2h[i] = parseInt(p_e[4]);
	if (t2c[i]>t2h[i]) t2c[i]=t2h[i];
	t2cm[i] =parseInt( p_e[5]);
	t2m[i] = parseInt(p_e[6]);
	if (t2cm[i]>t2m[i]) t2cm[i]=t2m[i];
	t2x[i] = parseInt(p_e[7]);
	t2y[i] = parseInt(p_e[8]);
	t2b[i] = p_e[9];
 }
var s='';
if (travm<10) travm=10;
if (oruj==1)s='<img title="С оружием" src="images/arena/zayor_1.gif" height=22>';else
s='<img title="Рукопашная" src="images/arena/zayor_0.gif" height=22>';
s = '<table width=100% cellspadding=0 cellspacing=0 style="height:30px;"><tr><td>'+s;
s+='<img title="Травматичность '+travm+'%" src="images/arena/blood_'+travm+'.gif" height=22>';
if(timeout)
	setTimeout("location='main.php'",timeout*1000);
setInterval("if(document.getElementById('timeout').innerHTML>0)document.getElementById('timeout').innerHTML--;",1000);
if(_closed)
	s+= '<b class=hp title="Никто не может вмешаться">[ЗАКРЫТЫЙ]</b>';
s+='<a href="fight.php?id='+logid+'" target=_blank class=timef><font title="Таймаут[Лог боя]" class=timef id=timeout>'+timeout+'</font></a></td><td><a href="main.php?vs_id='+persvs_id+'" class=but>Обновить</a></td><td id="odd" width=50% align=right class=SmallBox></td></tr></table>';
f_f.innerHTML = sbox2('<hr><div id=TOP>'+s+'</div>',1,2);
var t='';
t='<table border=1 width=98% cellspacing=0 cellspadding=0><tr><td width=50% align=center class=_but><span class=gray onclick="top.group_private(\''+t1u.join("|")+'|\')" style="cursor:pointer;">Сообщение</span></td><td width=50% align=center class=_but><span class=gray onclick="top.group_private(\''+t2u.join("|")+'|\')"  style="cursor:pointer;">Сообщение</span></td></tr>';
var mmax = t1u.length;
if (mmax<t2u.length) mmax=t2u.length;
for (i=0;i<mmax;i++)
{
t+= '<tr>';
if (i<t1u.length)
{
	if (whatteam == 2) vs_link = 'onclick="location=\'main.php?vs_id='+t2b[i]+'\'"'+
	'style="cursor:pointer"';
	t+='<td width=50% class=but>';
 	t+='<img src="images/signs/'+t1s[i]+'.gif"><font class=green '+vs_link+'>'+t1u[i]+'</font>[<font class=lvl>'
	+t1l[i]+
	'</font>]';
	if (t1b[i]<0) t+=' <img src=images/_i.gif onclick="window.open(\'binfo.php?'+t1b[i]
	+'\',\'_blank\')"> ';
	else
	t+=' <img src=images/_i.gif onclick="window.open(\'info.php?p='+t1u[i]
	+'\',\'_blank\')" style="cursor:pointer"> ';
	t+='<div class=but2><font class=hp_in_f>'+t1c[i]+'/'+t1h[i]+'</font> | <font class=ma_in_f>'+t1cm[i]+'/'+t1m[i]+'</font></div>';
	t+='</td>';
}else t+='<td width=50% class=but>&nbsp;</td>';

if (i<t2u.length)
{
	if (whatteam == 1) vs_link = 'onclick="location=\'main.php?vs_id='+t2b[i]+'\'"'+
	'style="cursor:pointer"';
	t+='<td width=50% class=but>';
 	t+='<img src="images/signs/'+t2s[i]+'.gif"><font class=blue '+vs_link+'>'+t2u[i]+'</font>[<font class=lvl>'
	+t2l[i]+
	'</font>]';
	if (t2b[i]<0) t+=' <img src=images/_i.gif onclick="window.open(\'binfo.php?'+t2b[i]
	+'\',\'_blank\')" style="cursor:pointer"> ';
	else
	t+=' <img src=images/_i.gif onclick="window.open(\'info.php?p='+t2u[i]
	+'\',\'_blank\')" style="cursor:pointer"> ';
	t+='<div class=but2><font class=hp_in_f>'+t2c[i]+'/'+t2h[i]+'</font> | <font class=ma_in_f>'+t2cm[i]+'/'+t2m[i]+'</font></div>';
	t+='</td>';
}else t+='<td width=50% class=but>&nbsp;</td>';
t+= '</tr>';
}
t+= ' </table>';
all_fighters = t;
ch_od(0);
}
function show_all_fighters()
{
	//document.getElementById('all_fighters').innerHTML = '<table border=0 width=100% class=return_win><tr><td width=30% title="Получено урона"><font class=hp>'+damage_get+'</font></td><td width=40% align=center><a href="javascript:hide_all_fighters();" class=bga>Скрыть участников боя.</a></td><td width=30% align=right title="Нанесено урона"><font class=green>'+damage_give+'</font></td></table><div id=allwarriors>'+all_fighters+'</div>';
	$("#shaf").text('Скрыть участников боя');
	$("#shaf").attr("href","javascript:hide_all_fighters()");
	$("#allwarriors").html(all_fighters);
	$("#allwarriors").slideDown(300);
}
function hide_all_fighters()
{
	//document.getElementById('all_fighters').innerHTML = '<table border=0 width=100%><tr><td width=30% class=hp title="Получено урона">'+damage_get+'</td><td width=40% align=center><a href="javascript:show_all_fighters();" class=bga>Показать участников боя.</a></td><td width=30% align=right title="Нанесено урона" class=green>'+damage_give+'</td></table>';
	$("#allwarriors").slideUp(300);
	$("#allwarriors").html('');
	$("#shaf").text('Показать участников боя');
	$("#shaf").attr("href","javascript:show_all_fighters()");
}

function element_in_array(ar,e)
{
for (var i=0;i<ar.length;i++) if (ar[i]==e) return (i+1);
return false;
}

function show_boxes_and_form(addon,od_addon,fstate)
{
var f_f = document.getElementById('fight');
//######################
var minimap = '';
var tmpC;
var tmpB;
var Button_HEIGHT = maxy*10;
if(Button_HEIGHT<40) Button_HEIGHT = 40;
var bplaces = go_no.split("@");
bplaces = '|'+bplaces[0];
if ((maxx+maxy)>2) tmpB = 'none'; else tmpB = 'block';
minimap = '';
minimap += '<table width='+maxx*10+' height='+maxy*10+' border=0 cellspadding=0 cellspacing=0 id="minimap">';
for (var _y=0;_y<maxy;_y++)
{
	minimap += '<tr>';
	for (var _x=0;_x<=maxx;_x++)
		{	
			if ((bplaces).indexOf('|'+_x+'_'+_y+'|')!=-1) 
				tmpC = 'style="background-color:#AAAAAA;"'; 
			else 
				tmpC = '';
			minimap += '<td id="mm_'+_x+'_'+_y+'" width=10 height=10 '+tmpC+'></td>';
		}
	minimap += '</tr>';
}

minimap += '</table>';
//####################
fs = fstate;
if (fstate==1 || fstate==0) var j1 = "items"; else var j1 = "white";
if (fstate==2) var j2 = "items"; else var j2 = "white";
if (fstate==3) var j3 = "items"; else var j3 = "white";
if (fstate==4) var j4 = "items"; else var j4 = "white";

if (mid>0)
{
field='<center id=map>';
field += '<table style="width:392px;" border=0 cellspadding=0 cellspacing=0>';
var i,j,xf,yf;
xf=0;
yf=0;
if (x>=4 && x<(maxx-4)) xf=x-3;
if (x>=(maxx-4))xf=7;
//if (y>2) yf=1;
else yf=0;
for (i=yf;i<yf+5;i++)
{
field += "<tr>"; 
for (j=xf;j<xf+7;j++)
{
 if (((j-x)*(j-x)+(i-y)*(i-y))<(speed*speed) && go_no.indexOf('|'+j+'_'+i+'|')==-1 && can_turn)
 field += "<td class=go_yes width=56 height=56 background='images/battle/map"+mid+"/"+j+'_'+i+".jpg' align=center onclick=\"location='main.php?gotox="+j+"&gotoy="+i+"&vs_id="+persvs_id+"'\" style='cursor:pointer' id='"+j+"_"+i+"'><img src='images/emp.gif' width=56></td>"; 
 else
 field += "<td width=56 height=56 background='images/battle/map"+mid+"/"+(j)+'_'+(i)+".jpg' align=center id='"+j+"_"+i+"'><img src='images/emp.gif' width=56></td>"; 
}
field += "</tr>"; 
}
field += "</table>";
}
if (!noone) noone='<input class=login type=button value="Ничья" onclick="defence();" style="width:100%; height:'+Button_HEIGHT+'px; FONT-WEIGHT: bold; cursor:pointer;">';
else noone='<input class=login type=button value="Ничья" DISABLED style="width:100%; height:'+Button_HEIGHT+'px; FONT-WEIGHT: bold;">';
var can_turn_text = '';
if(can_turn) can_turn_text = '<tr> <td align=center id=buttons_f></td></tr></table><table border=0 width=100% cellspacing=0 cellpadding=0 style="cursor:pointer;"> <tr><td width=40%><input class=login type=button value="Атака" onclick="attack();" id=attack_button style="width:100%; height:'+Button_HEIGHT+'px; FONT-WEIGHT: bold; cursor:pointer;"></td><td id=_container>'+minimap+'<table width=100% border=0 cellspacing=0 cellspadding=0 id="ftypes" style="display:'+tmpB+';"><tr><td class='+j1+' title="Ближний бой" onclick="location=\'main.php?fstate=1\'"><img border=0 src="images/arena/15.gif" width=80></td> <td class='+j3+' title="Магия" onclick="location=\'main.php?fstate=3\'"><img border=0 src="images/arena/44.gif" width=80></td></tr></table></td> <td width=40% align=right>'+noone+'</td></tr>';
f_f.innerHTML +='<table width=100% border=0 cellspacing=0 cellspadding=0> <tr><td id=field align=center>'+field+'</td></tr>'+can_turn_text+'</table><div id="information" class=ma align=center></div></form>';
if (mid>0)put_heroes();
else 
	attack();
	
	//<img border=0 src="images/arena/15.gif" width=80></td> <td class='+j2+' title="Дальний бой" onclick="location=\'main.php?fstate=2\'"><td class='+j4+' title="Кинуть предмет" onclick="location=\'main.php?fstate=4\'"><img border=0 src="images/arena/3.gif" width=80></td>

}

function put_heroes()
{
var p,i,j;
var tiptxt = '';
var is_it;
p = team1.split('@');
for (i=0;i<p.length-1;i++)
 {
 if (document.getElementById("mm_"+t1x[i]+'_'+t1y[i]))
	document.getElementById("mm_"+t1x[i]+'_'+t1y[i]).style.backgroundColor = "#009900";
 if (document.getElementById(t1x[i]+'_'+t1y[i]))
 {
 	t1cm[i]++;
	t1m[i]++;
	if (persvs_id && t1b[i]==persvs_id) is_it='pass'; else is_it='td onclick="location=\'main.php?vs_id='+t1b[i]+'\'"';
	tiptxt = '|<b class=user>'+t1u[i]+'</b><b class=lvl>['+t1l[i]+']</b><br><i class=hp>HP: ['+t1c[i]+'/'+t1h[i]+']</i><br><i class=ma>MA: ['+t1cm[i]+'/'+t1m[i]+']</i>';
 if (whatteam==1)
 	document.getElementById(t1x[i]+'_'+t1y[i]).innerHTML='<table background=images/battle/friend.gif style="cursor:pointer" border=0 cellspacing=0 cellspadding=0 width=56 height=56 onmouseover="s_des(event,\''+tiptxt+'\')" onmouseout="h_des()" onmousemove=move_alt(event)> <tr><td align=center valign=top><font color=#AAFFAA>'+t1u[i].substr(0,7)+'</font></td></tr><tr><td height=6><img src=images/design/new/hp.gif height=6 width='+(52*(t1c[i]/t1h[i]))+' title="HP: ['+t1c[i]+'/'+t1h[i]+']"><img src=images/no.png height=6 width='+(52-52*(t1c[i]/t1h[i]))+' title="HP: ['+t1c[i]+'/'+t1h[i]+']"></td></tr><tr><td height=6><img src=images/design/new/ma.gif height=6 width='+(52*(t1cm[i]/(t1m[i])))+' title="MA: ['+t1cm[i]+'/'+t1m[i]+']"><img src=images/no.png height=6 width='+(52-52*(t1cm[i]/(t1m[i])))+' title="MA: ['+t1cm[i]+'/'+t1m[i]+']"></td></tr></table>';
 else
	document.getElementById(t1x[i]+'_'+t1y[i]).innerHTML='<table background=images/battle/enemy.gif style="cursor:pointer" border=0 cellspacing=0 cellspadding=0 width=56 height=56 ondblclick="db_click()"  onmouseover="s_des(event,\''+tiptxt+'\')" onmouseout="h_des()" onmousemove=move_alt(event)> <tr><td align=center valign=top><font color=#AAFFAA class='+is_it+'>'+t1u[i].substr(0,7)+'</font></td></tr><tr><td height=6><img src=images/design/new/hp.gif height=6 width='+(52*(t1c[i]/t1h[i]))+' title="HP: ['+t1c[i]+'/'+t1h[i]+']"><img src=images/no.png height=6 width='+(52-52*(t1c[i]/t1h[i]))+' title="HP: ['+t1c[i]+'/'+t1h[i]+']"></td></tr><tr><td height=6><img src=images/design/new/ma.gif height=6 width='+(52*(t1cm[i]/(t1m[i])))+' title="MA: ['+t1cm[i]+'/'+t1m[i]+']"><img src=images/no.png height=6 width='+(52-52*(t1cm[i]/(t1m[i])))+' title="MA: ['+t1cm[i]+'/'+t1m[i]+']"></td></tr></table>';
 }
 }
 
p = team2.split('@');
for (i=0;i<p.length-1;i++)
 {
  if (document.getElementById("mm_"+t2x[i]+'_'+t2y[i]))
	document.getElementById("mm_"+t2x[i]+'_'+t2y[i]).style.backgroundColor = "#000099";
 if (document.getElementById(t2x[i]+'_'+t2y[i]))
 {
 	t2cm[i]++;
	t2m[i]++;
	if (persvs_id && t2b[i]==persvs_id) is_it='pass'; else is_it='td onclick="location=\'main.php?vs_id='+t2b[i]+'\'"';
	tiptxt = '|<b class=user>'+t2u[i]+'</b><b class=lvl>['+t2l[i]+']</b><br><i class=hp>HP: ['+t2c[i]+'/'+t2h[i]+']</i><br><i class=ma>MA: ['+t2cm[i]+'/'+t2m[i]+']</i>';
 if (whatteam==2)
 	document.getElementById(t2x[i]+'_'+t2y[i]).innerHTML='<table background=images/battle/friend.gif style="cursor:pointer" border=0 cellspacing=0 cellspadding=0 height=56 width=56 onmouseover="s_des(event,\''+tiptxt+'\')" onmouseout="h_des()" onmousemove=move_alt(event)> <tr><td  title="'+t2u[i]+'" align=center valign=top><font color=#BBBBFF>'+t2u[i].substr(0,7)+'</font></td></tr><tr><td height=6><img src=images/design/new/hp.gif height=6 width='+(52*(t2c[i]/t2h[i]))+' title="HP: ['+t2c[i]+'/'+t2h[i]+']"><img src=images/no.png height=6 width='+(52-52*(t2c[i]/t2h[i]))+' title="HP: ['+t2c[i]+'/'+t2h[i]+']"></td></tr><tr><td height=6><img src=images/design/new/ma.gif height=6 width='+(52*(t2cm[i]/(t2m[i])))+' title="MA: ['+t2cm[i]+'/'+t2m[i]+']"><img src=images/no.png height=6 width='+(52-52*(t2cm[i]/(t2m[i])))+' title="MA: ['+t2cm[i]+'/'+t2m[i]+']"></td></tr></table>';
 else
	document.getElementById(t2x[i]+'_'+t2y[i]).innerHTML='<table background=images/battle/enemy.gif style="cursor:pointer" border=0 cellspacing=0 cellspadding=0 height=100% ondblclick="db_click()" height=56 width=56 onmouseover="s_des(event,\''+tiptxt+'\')" onmouseout="h_des()" onmousemove=move_alt(event)> <tr><td  align=center valign=top><font color=#BBBBFF class='+is_it+'>'+t2u[i].substr(0,7)+'</font></td></tr><tr><td height=6><img src=images/design/new/hp.gif height=6 width='+(52*(t2c[i]/t2h[i]))+' title="HP: ['+t2c[i]+'/'+t2h[i]+']"><img src=images/no.png height=6 width='+(52-52*(t2c[i]/t2h[i]))+' title="HP: ['+t2c[i]+'/'+t2h[i]+']"></td></tr><tr><td height=6><img src=images/design/new/ma.gif height=6 width='+(52*(t2cm[i]/(t2m[i])))+' title="MA: ['+t2cm[i]+'/'+t2m[i]+']"><img src=images/no.png height=6 width='+(52-52*(t2cm[i]/(t2m[i])))+' title="MA: ['+t2cm[i]+'/'+t2m[i]+']"></td></tr></table>';
  }
 } 
}
function sel(id)
{
	document.getElementById(id).innerHTML='<img src=images/p.gif>';
}
function outsel(id)
{
	document.getElementById(id).innerHTML='&nbsp;';
}

function submitform()
{
if (document.battle)
{
document.battle.attack.disabled = false;
document.battle.defence.disabled = false;
if (document.battle.magic_koef)
 document.battle.magic_koef.disabled = false;
document.battle.submit();
document.getElementById('yourturn').disabled = true;
}
}

function show_message_in_f(message)
{
document.getElementById('fight').innerHTML += message;
}

function show_magic(){
var i=0;
var nameTHIS = '',desrcTHIS = '';
var tmp;
var text='<table border=0 width=100%><tr><td colspan=6 align=center>Магия повреждений</td></tr>';
for (i=1;i<=n;i++)
{
	if (i%5==1) text += '<tr>';
	tmp = nam[i].split("|");
	desrcTHIS = tmp[1];
	nameTHIS = tmp[0];
	text += '<td align=center width=20%><img style="cursor:pointer;" src="images/magic/'+img[i]+'.gif" onclick="set_bit(\''+id[i]+'\',\''+nameTHIS+'\',\''+img[i]+'\');" onMouseOver="s_des(event,\'0|'+desrcTHIS+'\');this.style.borderColor=\'#45688E\'" onMouseOut="h_des();this.style.borderColor=\'\'" class=m onmousemove=move_alt(event)></td>';
	if (i%5==0) text += '</tr>';
	if(freq[2]==id[i])
		set_bit(id[i],nameTHIS,img[i]);
}
if (n==0) text += '<tr><td align=center width=20% class=hp>Нет доступной магии.</td></tr>';

var ars = auras.split('|');
var ar,t='';
for(i=0;i<ars.length;i++)
{
	if (ars[i]!='')
	{
	ar = ars[i].split('#');
	if (ar[0].indexOf('.gif')!=-1) ar[0] = ar[0].substr(0,ar[0].length-4);
	t += '<img src="images/magic/'+ar[0]+'.gif" onmouseover="s_des(event,\'0|'+ar[2]+'\');this.style.borderColor=\'#45688E\';" onmouseout="h_des();this.style.borderColor=\'\';" onmousemove=move_alt(event) class=m onclick="set_bita(\''+ar[1]+'\',\''+ar[0]+'|'+ar[2]+'\');"> ';
	}
}
text += '</table>';
if (up_health) t = '<a href=main.php?up_health=1><img src="images/magic/68.gif" title="Полное излечение ('+up_health+' маны)" onmouseover="this.style.borderColor=\'#45688E\';" onmouseout="this.style.borderColor=\'\';" class=m></a>'+t;
text += '<center class=but>'+t+'</center>';
return text;
}

function set_bit(id,name,img){
aura_od=0;
od_use = 0;
attack_ident = 2;
var sel = '<select class=items name=magic_koef onchange=change_magic_od()>';
for (var i=1;i<=(level/2)+1;i++)
	if (i==freq[3])
		sel += '<option value='+i+' SELECTED>'+i+'x'+'</option>';
	else
		sel += '<option value='+i+'>'+i+'x'+'</option>';
sel+= '</select>';
zz1 = '<center>'+sel+'<font class=user>'+name+' <img src="images/magic/'+img+'.gif" height=18></font><input type=hidden name="p" value="'+id+'"><input type=hidden name=attack value=3></center>';
ch_od(s_od());
attack();
}

function set_bita(id,name){
var ww = name.split("|");
name = ww[1];
while (name.indexOf('@')!=-1) name = name.replace('@','<br>');
aura_text = '<img src="images/magic/'+ww[0]+'.gif">'+sbox2(name,1)+'<input type=hidden name="aura_id" value="'+id+'">';
zz1 = '<input type=hidden name=attack value='+parseInt(od*0.9)+'>';
od_use = parseInt(od*0.9);
ch_od(od_use);
aura_od = parseInt(od*0.9);
attack_ident = 3;
attack();
}

function change_magic_od()
{
	document.battle.attack.value = 3*document.battle.magic_koef.value;
}

function show_kid(){
var i=0;
for (i=1;i<=nk;i++) show_m(kidimg[i],kidid[i],kidnam[i]);
if (nk==0) menu = 'У вас нет предметов для броска.';
document.getElementById('menu').innerHTML = menu;
}

function aura(){
var i=0;
for (i=1;i<=na;i++) show_m(arimg[i],arid[i],arnam[i]);
if (na==0) menu = 'У вас нет заклинаний.';
document.getElementById('menu').innerHTML = menu;
}

function log_replace () {
var ttx = str_replace(' &nbsp;',' ',document.getElementById('log').innerHTML);
var text = ttx.split(";");
document.getElementById('log').innerHTML='';
document.getElementById('fight').innerHTML+='<p align="left">';
for(var i = 0; i<text.length; i++) 
if (text[i]!='') 
{	
	document.getElementById('fight').innerHTML+= text[i]+'<hr>';
}
document.getElementById('fight').innerHTML+='</p>';
}

function show_finish(fexp,exp_in_f,f_turn)
{
document.getElementById('fight').innerHTML += '<center id="all_fighters" class=UTable></center>';
document.getElementById('fight').innerHTML += '<center class=UTable>Ход №<b>'+(f_turn+1)+'</b><br>Нанесено урона: <font class=user>'+fexp+'</font><br>Ожидается опыта: <font class=user>'+exp_in_f+'</font></center>';
document.getElementById('all_fighters').innerHTML = '<center><table border=0 width=80%><tr><td width=40 align=right title="Нанесено урона"><font class=green>'+damage_give+'</font></td><td align=center><a href="javascript:hide_all_fighters();" class=bg id=shaf>Скрыть участников боя.</a></td><td width=50 title="Получено урона"><font class=hp>'+damage_get+'</font></td></table><div id=allwarriors style="width:100%;display:none;"></div></center>';
show_all_fighters();
}

function str_replace(replacement,substr,str)
{
while(str.indexOf(replacement)!=-1) str=str.replace(replacement,substr);
return str;
}

function attack ()
{
h_des();
if($("#minimap").get(0))
	$("#minimap").get(0).style.display = 'none';
$("#ftypes").fadeIn(500);
var repeat = '';
if(before && (attack_ident==3 || NEAR))
	repeat = '<input class=submit style="width:20%; height:20px;" type=button value=Повторить onclick="location=\'main.php?repeat=1\';">';
if(document.getElementById('buttons_f'))
	document.getElementById('buttons_f').innerHTML = '<input class=submit style="width:30%; height:20;" type=button value=Ход onclick="submitform()" id=yourturn DISABLED> '+repeat+' <input class=submit type=reset value=Сброс onclick="location=\'main.php\'" style="width:30%; height:20;">';

if(document.getElementById('attack_button'))
	document.getElementById('attack_button').disabled = true;
if (attack_ident != 3 && document.getElementById('yourturn')) document.getElementById('yourturn').disabled = true;
/////////////
	var spl,spl_od,ii,text;
if (fs==2 && arrow_name=='ARROW_NAME') {document.getElementById('information').innerHTML = 'Наденьте метательное оружие.'; return false;}
if (fs==1)
{
	if(bliz!='' && !zz1)
	{
	spl = bliz.split("|");
	spl_od = bliz_od.split("|");
	zz1	= '<select name=attack class=items style="width:80%">';
	for (ii=0;ii<spl.length;ii++)
	{
		spl_od[ii] = parseInt(spl_od[ii]);
		if (freq[0]==parseInt(spl_od[ii]+od_udar))
		zz1 += '<option value='+parseInt(spl_od[ii]+od_udar)+' SELECTED>'+spl[ii]+' ['+(spl_od[ii]+od_udar)+']</option>';
		else
		zz1 += '<option value='+parseInt(spl_od[ii]+od_udar)+'>'+spl[ii]+' ['+parseInt(spl_od[ii]+od_udar)+']</option>';
	}
	zz1 += '</select>';
	}
}
	if(block!='' && !zz2)
	{
	spl = block.split("|");
	spl_od = block_od.split("|");
	zz2	= '<select name=defence class=items style="width:80%">';
	for (ii=0;ii<spl.length;ii++)
	{
		if (freq[1]==spl_od[ii])
		zz2 += '<option value='+spl_od[ii]+' SELECTED>'+spl[ii]+' ['+spl_od[ii]+']</option>';
		else
		zz2 += '<option value='+spl_od[ii]+'>'+spl[ii]+' ['+spl_od[ii]+']</option>';
	}
	zz2 += '</select>';
	}
if (fs==3 && attack_ident!=2 && attack_ident!=3) document.getElementById('information').innerHTML = show_magic();
///////////////	
if (fs==2) {zz1 = arrow_name; attack_ident=1;}
	text = '<form method=POST name=battle action=main.php?vs_id='+persvs_id+'&rand='+Math.random()+'><input type=hidden name=vs value='+persvs_id+'><table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center" width="50%" class=but>'+zz1+'</td><td align="center" width="50%" class=but>'+zz2+'</td></tr><tr><td align="center" width="25%">';
if (attack_ident!=3 && NEAR)
	text += '<table border="0" width="90%" cellspacing="0" cellpadding="0" height="150" style="background-color:#F5EEEE"><tr height=100%><td width="100"> <img border="0" src="images/design/attack.gif"></td> <td width="100" align=right> <img border="0" src="images/persons/'+vs_img+'.gif" height="160"></td> <td> <table border="0" width="50" cellspacing="0" cellpadding="0" height="160"><tr height=25%><td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'ug\')" title="Удар в голову" id=ug class="inv">&nbsp;</td></tr> <tr height=25%> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'ut\')" title="Удар в грудь" id=ut class="inv">&nbsp;</td> </tr> <tr height=25%> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'uj\')" title="Удар по животу" id=uj class="inv">&nbsp;</td> </tr> <tr height=25%> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'un\')" title="Удар по ногам" id=un class="inv">&nbsp;</td> </tr></table></td></tr></table>';
else if(attack_ident!=3)
	text += '<b class=ma>Подойдите к сопернику</b>';
if (attack_ident==3)
{
	text += aura_text;
	 document.getElementById('yourturn').disabled = false;
}
text += '	</td><td align="center" width="25%"><table border="0" width="90%" cellspacing="0" cellpadding="0"  style="background-color:#EEEEF5"><tr>  <td align=right width="50"> <table border="0" width="50" cellspacing="0" cellpadding="0" height=160><tr><td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'bg\')" title="Блок головы" id=bg class="inv">&nbsp;</td> </tr> <tr> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'bt\')" title="Блок груди" id=bt class="inv">&nbsp;</td> </tr> <tr> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'bj\')" title="Блок живота" id=bj class="inv">&nbsp;</td> </tr> <tr> <td bgcolor="#F8F8F8" style="cursor:pointer" onclick="set_turn(\'bn\')" title="Блок ног" id=bn class="inv">&nbsp;</td> </tr> </table></td><td width="10"> <img border="0" src="images/persons/'+your_img+'.gif" height="160"></td><td width="100" align=right> <img border="0" src="images/design/defence.gif"></td> </tr></table>		</td></tr></table></form>';
	document.getElementById('field').innerHTML = text;
}
function defence()
{
	if (confirm("Вы действительно хотите сдаться и предложить ничью?")) location='main.php?noone=1';
}

function set_turn(point)
{
var od_this=0,od_thisc;
var _attack_class = 'attack_class';
od_thisc = parseInt(document.battle.attack.value);
if (point.indexOf('u')!=0) 
	{
	_attack_class = 'defence_class';
	od_thisc = parseInt(document.battle.defence.value);
	}
	else
	document.getElementById('yourturn').disabled = false;
			
if (!document.battle.attack) return;

	if (document.getElementById(point).innerHTML.length<10)
	{

		if (point=='ug')ug=parseInt(document.battle.attack.value);
		if (point=='ut')ut=parseInt(document.battle.attack.value);
		if (point=='uj')uj=parseInt(document.battle.attack.value);
		if (point=='un')un=parseInt(document.battle.attack.value);
		if (point=='bg')bg=parseInt(document.battle.defence.value);
		if (point=='bt')bt=parseInt(document.battle.defence.value);
		if (point=='bj')bj=parseInt(document.battle.defence.value);
		if (point=='bn')bn=parseInt(document.battle.defence.value);

		if (s_od()<=od) 
		{
		if (point.indexOf('b')==0) 
			document.battle.defence.disabled = true;
		else 
		{
		if (document.battle.magic_koef)
			document.battle.magic_koef.disabled = true;
			document.battle.attack.disabled = true;
		}
		document.getElementById(point).innerHTML = '&nbsp;<input type=hidden name='+point+' value='+od_thisc+'>';
		document.getElementById(point).className = _attack_class;
		}
		else
		{
			if (point=='un')un=0;
			if (point=='ut')ut=0;
			if (point=='uj')uj=0;
			if (point=='ug')ug=0;
			if (point=='bn')bn=0;
			if (point=='bt')bt=0;
			if (point=='bj')bj=0;
			if (point=='bg')bg=0;
		}
	}
	else 
	{
			if (point=='un')un=0;
			if (point=='ut')ut=0;
			if (point=='uj')uj=0;
			if (point=='ug')ug=0;
			if (point=='bn')bn=0;
			if (point=='bt')bt=0;
			if (point=='bj')bj=0;
			if (point=='bg')bg=0;
			
		if ((ug+ut+uj+un)==0) 
		{
			if (document.battle.magic_koef)
			document.battle.magic_koef.disabled = false;
			document.battle.attack.disabled = false;
			document.getElementById('yourturn').disabled = true;
		}
		if ((bg+bt+bj+bn)==0)
		document.battle.defence.disabled = false;

	document.getElementById(point).innerHTML = '&nbsp;';
	document.getElementById(point).className = 'inv';
	}

	
ch_od(s_od());
}


function signum(a)
{
	if (a>0) return 1;
	else if(a==0) return 0;
	else return -1;
}

function s_od()
{
var sd=0;
sd = ug+uj+un+ut+bg+bj+bt+bn+aura_od;
if ((signum(ug)+signum(un)+signum(uj)+signum(ut))==2) sd+=10;
if ((signum(ug)+signum(un)+signum(uj)+signum(ut))==3) sd+=35;
if ((signum(ug)+signum(un)+signum(uj)+signum(ut))==4) sd+=55;

if ((signum(bg)+signum(bn)+signum(bj)+signum(bt))==2) sd+=0;
if ((signum(bg)+signum(bn)+signum(bj)+signum(bt))==3) sd+=35;
if ((signum(bg)+signum(bn)+signum(bj)+signum(bt))==4) sd+=55;

return sd;
}

function db_click()
{
	attack();
}

function show_exp(expstr)
{
	document.getElementById('fight').innerHTML += sbox2('<center>'+expstr+'</center>');
	$("#exp_table tr:nth-child(odd)").css("background-color","#DDDDDD");
}

function ch_od(odc)
{
 var w1,w2;
	if (odc>od)
	{
		document.getElementById('odd').innerHTML = '<table width=100%><tr><td class=red align=center><span class=items>Превышение['+odc+'/'+od+']</span></td></tr><tr><td align=center><img src=images/design/new/2hp.gif width=60 height=8></td></tr></table>';
	}
	else if (!document.getElementById('_odn'))
	{
		w1 = (odc/od)*100;
		w2 = 100-w1;
		document.getElementById('odd').innerHTML = '<table width=100%><tr><td align=center><span class=items id=odtext>Очки действия[<b class=user>'+odc+'</b>/<b class=user>'+od+'</b>]</span></td></tr><tr><td align=center><table border=0 cellspacing=0 cellspadding=0 width=100><tr><td align=right><img src=images/design/new/2ma.gif width='+w1+' height=8 id=_odn></td><td><img src=images/no.png width='+w2+' height=8 id=_odz></td></tr></table></td></tr></table>';
	}
	else
	{
		w1 = (odc/od)*100;
		w2 = 100-w1;
		$("#_odz").animate({width:w2},100);
		$("#_odn").animate({width:w1},100);
		$("#odtext").get(0).innerHTML = 'Очки действия[<b class=user>'+odc+'</b>/<b class=user>'+od+'</b>]';
	}
}


function UP_TOP(a)
{
document.getElementById('TOP').innerHTML = a;
}