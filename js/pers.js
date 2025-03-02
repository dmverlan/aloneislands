document.write('<div style="position:absolute;left:0px; top:-100px; z-index: 65000; width:100; height:30;display:none;" id="description" onmouseover=h_des()></div>');
var curHP, maxHP, intHP, curMA, maxMA, intMA, interv;
var w = 186;
var ZW = 200;
var InFight=0;
var sdTimeOut = -1;
var divchp,divcma;
var CW = parseInt(document.body.clientWidth);
var descr;

//alert(document.body.clientWidth);

function START_HP_MA_INS()
{
	clearInterval(interv);
	interv = setInterval("cha_HP()",1000);
}
function ins_HP(curh, maxh, curm, maxm, hp_int, ma_int)
{ 
 if (maxh<5) maxh=5;
 if (maxm<9) maxm=9;
 intHP = hp_int;
 intMA = ma_int;

 START_HP_MA_INS();
 if(curm < 0) curm = 0;
 if(maxm <= 0) maxm = 9;
 
 curHP = curh;
 curMA = curm;
 maxHP = maxh;
 maxMA = maxm;

document.getElementById("hptitle").title = ''+(Math.round((maxHP/intHP)*1000)/1000)+' здоровья за 1 сек.';
document.getElementById("matitle").title = ''+(Math.round((maxMA/intMA)*1000)/1000)+' маны за 1 сек.';
}

function cha_HP()
{
if(curHP > maxHP) curHP = maxHP;
if(curMA > maxMA) curMA = maxMA;

if(curHP<0) curHP = 0;
if(curMA<0) curMA = 0;
var hp_f = Math.round(w*(1-curHP/maxHP));
var ma_f = Math.round(w*(1-curMA/maxMA));
if (hp_f<0) hp_f=0;
if (ma_f<0) ma_f=0;

if(!hp_f)
	document.images["right_hp"].src = 'images/DS/HM_right_hp.png';
else
	document.images["right_hp"].src = 'images/DS/HM_right_empty.png';
if(!ma_f)
	document.images["right_ma"].src = 'images/DS/HM_right_ma.png';
else
	document.images["right_ma"].src = 'images/DS/HM_right_empty.png';
	
document.images["hp"].width = w-hp_f;
document.images["ma"].width = w-ma_f;

if(divchp!=undefined)
{
	divchp.innerHTML = Math.round(curHP)+'/'+maxHP;
	divcma.innerHTML = Math.round(curMA)+'/'+maxMA;
}
else
{
	divchp = document.getElementById("chp");
	divcma = document.getElementById("cma");
}

curHP = curHP+(maxHP/intHP);
curMA = curMA+(maxMA/intMA);

if((curMA > maxMA) && (curHP > maxHP)) 
	clearInterval(interv);
}

function show_pers_new (sh,shd,oj,ojd,or1,or1d,po,pod,z1,z1d,z2,z2d,z3,z3d,sa,sad,na,nad,pe,ped,or2,or2d,ko1,ko1d,ko2,ko2d,br,brd,pers,inv,sign,nick,level,hp,mhp,ma,mma,tire,kam1,kam2,kam3,kam4,kam1d,kam2d,kam3d,kam4d,i,dil) {
var d=document;
if (tire==undefined) tire=0;
var s = 'style=\'cursor:pointer;\' onclick =\'location="main.php?';
var sha,oja,or1a,poa,z1a,z2a,z3a,saa,naa,pea,or2a,ko1a,ko2a,bra,kam1a,kam2a,kam3a,kam4a,pea;
if (inv==1) 
{
	if (shd!='0')sha = s+'sn='+take_id(shd)+'"\'';
	if (ojd!='0')oja = s+'sn='+take_id(ojd)+'"\'';
	if (or1d!='0')or1a= s+'sn='+take_id(or1d)+'"\'';
	if (pod!='0')poa = s+'sn='+take_id(pod)+'"\'';
	if (z1d!='0')z1a= s+'sn='+take_id(z1d)+'"\'';
	if (z2d!='0')z2a= s+'sn='+take_id(z2d)+'"\'';
	if (z3d!='0')z3a= s+'sn='+take_id(z3d)+'"\'';
	if (or2d!='0')or2a= s+'sn='+take_id(or2d)+'"\'';
	if (ko1d!='0')ko1a= s+'sn='+take_id(ko1d)+'"\'';
	if (ko2d!='0')ko2a= s+'sn='+take_id(ko2d)+'"\'';
	if (brd!='0')bra= s+'sn='+take_id(brd)+'"\'';
	if (kam1d!='0')kam1a= s+'sn='+take_id(kam1d)+'"\'';
	if (kam2d!='0')kam2a= s+'sn='+take_id(kam2d)+'"\'';
	if (kam3d!='0')kam3a= s+'sn='+take_id(kam3d)+'"\'';
	if (kam4d!='0')kam4a= s+'sn='+take_id(kam4d)+'"\'';
	if (sad!='0')saa= s+'sn='+take_id(sad)+'"\'';
	if (nad!='0')naa= s+'sn='+take_id(nad)+'"\'';
	if (ped!='0')pea= s+'sn='+take_id(ped)+'"\'';
}
if (inv==3) 
{
	kam1a= s+'use='+take_id(kam1d)+'"\''; 
	kam2a= s+'use='+take_id(kam2d)+'"\'';
	kam3a= s+'use='+take_id(kam3d)+'"\'';
	kam4a= s+'use='+take_id(kam4d)+'"\'';
}
var prvte='';
if (inv!=2) prvte = '<img src=images/p.gif title="Приват" onclick="top.say_private(\''+nick+'\',1)" style="cursor:pointer"> ';
if (inv==2 || window.opener) prvte = '<img src=images/pr.gif title="Приват" onclick="window.opener.top.say_private(\''+nick+'\',1)" style="cursor:pointer" height=16> ';
if (dil==1 && 0) dil='<img src=images/signs/diler.gif title="Официальный дилер проекта">'; else dil='';
var pers_text = prvte+'<font class=Luser>'+nick+'</font> <font class=Llvl>['+level+']</font>'+dil+' - <font class=Lgreen title=Усталость>'+parseInt(tire)+'%</font>';

if (!InFight)
{
d.write('<div style="position: absolute; width:39; height:369; top:67px; left:192px; z-index:2;"><img src="images/DS/left_column.png"></div>');
d.write('<div style="position: absolute; width:39; height:369; top:67px; left:458px; z-index:2;"><img src="images/DS/right_column.png"></div>');
}

d.write('<table width=220 border="0" cellspacing="0" cellpadding="0"><tr><td align=center background="images/DS/blackbg.jpg" height=39>');
if(InFight)
	d.write('<div class=but>'+pers_text+'</div>');
show_only_hp(hp,mhp,ma,mma);
d.write('</td></tr><tr><td> <table border=0 cellspacing=0 cellpadding=0 width=220> <tr><td width=220 align=center><table border=0 cellspacing=0 cellpadding=0 width=220  bgcolor=#FFFFFF><tr><td width=62 valign=top>');
d.write('<img src="images/weapons/slots/slot1.gif" width=62 height=20><img src="images/weapons/'+na+'.gif" width=62 height=40 onmouseover="s_des(event,\''+nad+'\')" onmouseout="h_des()" '+naa+' onmousemove=move_alt(event)><img  src=images/weapons/'+pe+'.gif width=62 height=40 onmouseover="s_des(event,\''+ped+'\')" onmouseout="h_des()" '+pea+' onmousemove=move_alt(event)><img  src="images/weapons/'+or1+'.gif" width=62 height=91 onmouseover="s_des(event,\''+or1d+'\')" onmouseout="h_des()" '+or1a+' onmousemove=move_alt(event)><img  src="images/weapons/'+br+'.gif" width=62 height=90 onmouseover="s_des(event,\''+brd+'\')" onmouseout="h_des()" '+bra+' onmousemove=move_alt(event)></td> <td align=center width=115><img src="images/persons/'+pers+'.gif" title="'+nick+'" width=115></td><td width=62 valign=top align=right><img src="images/weapons/'+sh+'.gif" width=62 height=65 onmouseover="s_des(event,\''+shd+'\')" onmouseout="h_des()" '+sha+' onmousemove=move_alt(event)><img src="images/weapons/'+oj+'.gif" width=62 height=35 onmouseover="s_des(event,\''+ojd+'\')" onmouseout="h_des()" '+oja+' onmousemove=move_alt(event)><img src="images/weapons/'+or2+'.gif" width=62 height=91 onmouseover="s_des(event,\''+or2d+'\')" onmouseout="h_des()" '+or2a+' onmousemove=move_alt(event)><img src="images/weapons/'+po+'.gif" width=62 height=30 onmouseover="s_des(event,\''+pod+'\')" onmouseout="h_des()" '+poa+' onmousemove=move_alt(event)><img src="images/weapons/'+sa+'.gif" width=62 height=60 onmouseover="s_des(event,\''+sad+'\')" onmouseout="h_des()" '+saa+' onmousemove=move_alt(event)></td></tr><tr><td align=left width=100%><img src="images/weapons/'+kam1+'.gif" width=31 height=31 onmouseover="s_des(event,\''+kam1d+'\')" onmouseout="h_des()" '+kam1a+' onmousemove=move_alt(event)><img src="images/weapons/'+kam2+'.gif" width=31 height=31 onmouseover="s_des(event,\''+kam2d+'\')" onmouseout="h_des()" '+kam2a+' onmousemove=move_alt(event)></td><td align=center><img src="images/weapons/'+ko1+'.gif" width=31 height=31 onmouseover="s_des(event,\''+ko1d+'\')" onmouseout="h_des()" '+ko1a+' onmousemove=move_alt(event)><img src="images/weapons/'+ko2+'.gif" width=31 height=31 onmouseover="s_des(event,\''+ko2d+'\')" onmouseout="h_des()" '+ko2a+' onmousemove=move_alt(event)></td><td align=right><img src="images/weapons/'+kam3+'.gif" width=31 height=31 onmouseover="s_des(event,\''+kam3d+'\')" onmouseout="h_des()" '+kam3a+' onmousemove=move_alt(event)><img src="images/weapons/'+kam4+'.gif" width=31 height=31 onmouseover="s_des(event,\''+kam4d+'\')" onmouseout="h_des()" '+kam4a+' onmousemove=move_alt(event)></td> <td height=32 align=right valign=bottom></td></tr></table></td></tr></table></td></tr></table>');
if (!InFight)
	d.write('<div style="background-image: url(\'images/DS/main_topline.jpg\'); height:16px; width:100%;"></div>');
if(!InFight)
{
if(d.getElementById('_pers'))
	d.getElementById('_pers').innerHTML = pers_text;
else
	if(d.getElementById('_pers1') && !d.getElementById('_pers1').innerHTML)
		d.getElementById('_pers1').innerHTML = pers_text;
	else
		if(d.getElementById('_pers2'))
			d.getElementById('_pers2').innerHTML = pers_text;
}
}

function show_statsn(ss,sl,su,szd,szn,sp)
{
if (ss<1) ss=1;
if (sl<1) sl=1;
if (su<1) su=1;
if (szd<1) szd=1;
if (szn<1) szn=1;
if (sp<1) sp=1;
document.write('<table border="0" width="100%" cellspacing="1" class=LinedTable>	<tr> <td width=80 class=statsn>Сила:</td> <td class=user align=right>'+ss+'</td></tr><tr><td width=80 class=statsn>Реакция:</td><td class=user align=right>'+sl+'</td></tr><tr><td width=80 class=statsn>Удача:</td><td class=user align=right>'+su+'</td></tr><tr><td class=statsn width=80>Здоровье:</td><td class=user align=right>'+szd+'</td></tr><tr><td width=80 class=statsn>Интеллект:</td><td class=user align=right>'+szn+'</td></tr><tr><td width=80 class=statsn>Сила&nbsp;Воли:</td><td class=user align=right>'+sp+'</td></tr></table>');
}


function s_des (event,id,z) {
if (!event) var event = window.event;
descr = document.getElementById('description');
ZW=200;
if (id=='0') 
{
	id = '<b>Пустой слот.</b>';
	ZW=90;
}
else
{
var ww = id.split("|");
id = ww[1];
while (id.indexOf('@')!=-1) id = id.replace('@','<br>');
}
id = '<div style="background-color: #F5F5F5;">'+id+'</div>';
descr.innerHTML = id;
move_alt(event);
clearTimeout(sdTimeOut);
$(descr).fadeIn(100);
}

function h_des () {
sdTimeOut = setTimeout("$('#description').css('display','none')",100);
}

function move_alt(event){
if (!event) var event = window.event;
var x =  event.clientX;
var y =  event.clientY+document.body.scrollTop;
if (x+250 >= CW)
	x = x - 250 + 'px';
else
	x = x + 25 + 'px';
if (y>(document.height-100)) y = document.height-100;
y = y+'px';
if(!descr)
	descr = document.getElementById('description');
descr.style.cssText = 'position:absolute;z-index:5;width:'+ZW+'px;left:'+x+';top:'+y+';';
}

function take_id(id)
{
var ww = id.split("|");
return parseInt(ww[0]);
}

function show_only_hp(hp,mhp,ma,mma)
{
if (mhp<5) mhp=5;
if (mma<5) mma=9;
var d=document;
if (ma<0) ma=0;
if (hp<0) hp=0;
var no_hp = Math.round(w*(1-hp/mhp));
var no_ma = Math.round(w*(1-ma/mma));
if (no_hp<0) no_hp=0;
if (no_ma<0) no_ma=0;
var zhp = w-no_hp;
var zma = w-no_ma;
var rHP = 'images/DS/HM_right_empty.png';
if(mhp==hp)
	rHP = 'images/DS/HM_right_hp.png';
var rMA = 'images/DS/HM_right_empty.png';
if(mma==ma)
	rMA = 'images/DS/HM_right_ma.png';
//d.write('<table border="0" width="220" cellspacing="0" cellpadding="0"> 	<tr> 		<td width="26"><img border="0" src="images/design/hot/hp_left.gif"></td> 		<td align=center> 		<table border="0" width="100%" cellspacing="0" cellpadding="0"> 			<tr> 				<td colspan="2" title="" id=hptitle><img border="0" src="../images/design/new/hp.gif" width="'+zhp+'" height="8" name=hp><img border="0" src="../images/no.png" width='+no_hp+' height="8" name=no_hp></td> 			</tr> 			<tr class=login> 				<td id=chp class=hp>'+hp+'/'+mhp+'</td> <td id=cma class=ma align=right>'+ma+'/'+mma+'</td> 			</tr> 			<tr> 				<td colspan="2" title="" id=matitle><img border="0" src="images/design/new/ma.gif" width="'+zma+'" height="8" name=ma><img border="0" src="images/no.png" width='+no_ma+' height="8" name=no_ma></td> 			</tr> 		</table> 		</td> 		<td width="26" align=left><img border="0" src="images/design/hot/hp_right.gif"></td> 	</tr> </table>');
d.write('<table border="0" cellspacing="0" cellpadding="0"><tr><td><img border="0" src="images/DS/HM_left_hp.png"></td><td width="186" style="background-image: url(\'images/DS/HM_empty.png\');"  id=hptitle><div style="position:relative;top:0;left:0;width:100%;height:15px;"><div style="position:absolute;top:0;left:0;width:100%;height:15px;z-index:1;"><img src="images/DS/HM_hp.png" width="'+zhp+'" height="15" name=hp></div><div id=chp style="width:100%;height:15px;position:absolute; top:3px; left:0; text-align:center; color:#FFFFFF; font-size:8px; font-family: Verdana;overflow:hidden;z-index:2;font-weight: bold;">'+hp+'/'+mhp+'</div></div></td> 	<td><img border="0" src="'+rHP+'" id=right_hp></td>		</tr> 			<tr> 				<td><img border="0" src="images/DS/HM_left_ma.png"></td><td width="186" style="background-image: url(\'images/DS/HM_empty.png\');" id=matitle><div style="position:relative;top:0;left:0;width:100%;height:15px;"><div style="position:absolute;top:0;left:0;width:100%;height:15px;z-index:1;"><img src="images/DS/HM_ma.png" width="'+zma+'" height="15" name=ma></div><div id=cma style="width:100%;height:15px;position:absolute; top:3px; left:0; text-align:center; color:#FFFFFF; font-size:8px; font-family: Verdana;overflow:hidden;z-index:2;font-weight: bold;">'+ma+'/'+mma+'</div></div></td> 	<td><img border="0" src="'+rMA+'" id=right_ma></td>		</tr>		</table>');

}

function show_only_hp2(hp,mhp,ma,mma)
{
if (mhp<5) mhp=5;
if (mma<5) mma=9;
var d=document;
if (ma<0) ma=0;
if (hp<0) hp=0;
var no_hp = Math.round(w*(1-hp/mhp));
var no_ma = Math.round(w*(1-ma/mma));
if (no_hp<0) no_hp=0;
if (no_ma<0) no_ma=0;
var zhp = w-no_hp;
var zma = w-no_ma;

return '<table border="0" width="230" cellspacing="0" cellpadding="0"> 	<tr> 		<td width="26"><img border="0" src="images/design/hot/hp_left.gif"></td> 		<td align=center> 		<table border="0" width="100%" cellspacing="0" cellpadding="0"> 			<tr> 				<td colspan="2" title="" id=hptitle><img border="0" src="../images/design/new/hp.gif" width="'+zhp+'" height="8" name=hp><img border="0" src="../images/no.png" width='+no_hp+' height="8" name=no_hp></td> 			</tr> 			<tr class=login> 				<td id=chp class=hp>'+hp+'/'+mhp+'</td> <td id=cma class=ma align=right>'+ma+'/'+mma+'</td> 			</tr> 			<tr> 				<td colspan="2" title="" id=matitle><img border="0" src="images/design/new/ma.gif" width="'+zma+'" height="8" name=ma><img border="0" src="images/no.png" width='+no_ma+' height="8" name=no_ma></td> 			</tr> 		</table> 		</td> 		<td width="26" align=left><img border="0" src="images/design/hot/hp_right.gif"></td> 	</tr> </table>';
}