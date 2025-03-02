var menu;

function prov (allod,point) {
document.getElementById('menu').innerHTML = '';
menu = '';
if (point != null) {document.getElementById(point+'t').innerHTML = '';}
var ugs,uts,uns,bgs,bts,bns,s,ujs,bjs,p,g = 0;
ugs = 0;uts = 0;ujs = 0;uns = 0;
bgs = 0;bts = 0;bjs = 0;bns = 0;
var ug=document.boy.ug.value;
var ut=document.boy.ut.value;
var uj=document.boy.uj.value;
var un=document.boy.un.value;
var bg=document.boy.bg.value;
var bt=document.boy.bt.value;
var bj=document.boy.bj.value;
var bn=document.boy.bn.value;
var att=document.boy.att.value;
var bgg=document.boy.bgg.value;
var magic,kid;
magic=0;kid=0;
p=0;
s=0;
if (att=='forv' || att=='aura') p+=allod*0.9;
if (bgg=='back' || bgg=='aura') p+=allod*0.9;
p = Math.round (p);
if (ug=='1') {p = p+1;ugs=1;}if (ug=='2') {p = p+2;ugs=1;}
if (ut=='1') {p = p+1;uts=1;}if (ut=='2') {p = p+2;uts=1;}
if (uj=='1') {p = p+1;ujs=1;}if (uj=='2') {p = p+2;ujs=1;}
if (un=='1') {p = p+1;uns=1;}if (un=='2') {p = p+2;uns=1;}
if (bg=='1') {p = p+1;bgs=1;}if (bg=='2') {p = p+2;bgs=1;}
if (bt=='1') {p = p+1;bts=1;}if (bt=='2') {p = p+2;bts=1;}
if (bj=='1') {p = p+1;bjs=1;}if (bj=='2') {p = p+2;bjs=1;}
if (bn=='1') {p = p+1;bns=1;}if (bn=='2') {p = p+2;bns=1;}
if (ug=='magic') {p = p+2;ugs=1;}if (ug=='kid') {p = p+3;ugs=1;}
if (ut=='magic') {p = p+2;uts=1;}if (ut=='kid') {p = p+3;uts=1;}
if (uj=='magic') {p = p+2;ujs=1;}if (uj=='kid') {p = p+3;ujs=1;}
if (un=='magic') {p = p+2;uns=1;}if (un=='kid') {p = p+3;uns=1;}
if (bg=='5') {p = p+5;bgs=1;}
if (bt=='5') {p = p+5;bts=1;}
if (bj=='5') {p = p+5;bjs=1;}
if (bn=='5') {p = p+5;bns=1;}

if (ugs == 1) document.boy.un.disabled = true;
if (uns == 1) document.boy.ug.disabled = true;
if (bgs == 1) document.boy.bn.disabled = true;
if (bns == 1) document.boy.bg.disabled = true;
if (ugs != 1) document.boy.un.disabled = false;
if (uns != 1) document.boy.ug.disabled = false;
if (bgs != 1) document.boy.bn.disabled = false;
if (bns != 1) document.boy.bg.disabled = false;

if (point != null && document.getElementById(point).value != '') document.getElementById(point+'t').innerHTML ='•';
if (bns!=1) document.getElementById('bnt').innerHTML ='&nbsp;';
if (bts!=1) document.getElementById('btt').innerHTML ='&nbsp;';
if (bjs!=1) document.getElementById('bjt').innerHTML ='&nbsp;';
if (bgs!=1) document.getElementById('bgt').innerHTML ='&nbsp;';
if (bgg=='')document.getElementById('bggt').innerHTML ='&nbsp;';
if (uns!=1) document.getElementById('unt').innerHTML ='&nbsp;';
if (ujs!=1) document.getElementById('ujt').innerHTML ='&nbsp;';
if (uts!=1) document.getElementById('utt').innerHTML ='&nbsp;';
if (ugs!=1) document.getElementById('ugt').innerHTML ='&nbsp;';
if (att=='')document.getElementById('attt').innerHTML ='&nbsp;';

g=0;
g = ugs + uts + ujs + uns;
if (g==0) s+=0;
if (g==1) s+=0;
if (g==2) s+=5;
if (g==3) s+=15;

g=0;
g = bgs + bts + bjs + bns;
if (g==0) s+=0;
if (g==1) s+=0;
if (g==2) s+=5;
if (g==3) s+=15;

p = p+s;
if (p<=allod) document.getElementById('od').innerHTML ='Из них использовано: <font class=user>'+p+'</font>';
if (p>allod) document.getElementById('od').innerHTML ='Из них использовано:<font class=hp> '+p+' Превышение!</font>';
document.boy.odd.value = p;
if (s != 0) document.getElementById('od').innerHTML = document.getElementById('od').innerHTML + '<font class=hp> Штраф ' + s + ' </font>';

if (point != null) {
if (document.getElementById(point).value=='magic') magic=1;
if (document.getElementById(point).value=='kid') kid=1;
if (document.getElementById(point).value=='aura') show_aura(point);
}

if (magic==1) {show_magic(point);
document.getElementById(point+'t').innerHTML = '<font class=hp>Не выбрано</font>';}
if (kid==1) {show_kid(point);
document.getElementById(point+'t').innerHTML = '<font class=hp>Не выбрано</font>';}
}

function show_magic(point){
var i=0;
for (i=1;i<=n;i++) show_m(img[i],id[i],nam[i],point);
document.getElementById('menu').innerHTML = menu;
}
function show_m(img,id,namem,point){
menu +='<img style="cursor:hand;" src="../images/zakl/'+img+'" title="'+namem+'" onclick="set_bit(\''+point+'\',\''+id+'\',\''+namem+'\');">';
}
function set_bit(point,id,name){
document.getElementById('menu').innerHTML = '';
document.getElementById(point+'t').innerHTML = '<input type=hidden name="'+point+'p" value="'+id+'"><font class=ma>'+name+'</font>';
}


function show_kid(point){
var i=0;
for (i=1;i<=nk;i++) show_m(kidimg[i],kidid[i],kidnam[i],point);
document.getElementById('menu').innerHTML = menu;
}



function show_aura(point){
var i=0;
for (i=1;i<=na;i++) show_m(arimg[i],arid[i],arnam[i],point);
document.getElementById('menu').innerHTML = menu;
}

function log_replace () {
var text = document.getElementById('log').innerHTML.split(";");
var i;
document.getElementById('log').innerHTML='';
for(i = 0; i<text.length; i++) document.getElementById('log').innerHTML+=text[i]+'<table width=100%><tr><td><div id=s10'+i+'></div></td></tr></table>';
}

function show_boxes (od,bliz)
{
if (bliz==1) bliz = '<option value=1>Простой[1]</option><option value=2>Прицельный[2]</option>'; else bliz='';
document.write('<br><div id="od">Из них использовано: <font class=user>0</font> </div></font></center><fieldset style="border:1px outset #000000; padding:0; "> <legend><font face="Verdana" size="1">Ход</font></legend> <center> <table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0"> <tr> <td width="20%" id=\'ugt\' align="right">&nbsp;</td> <td align="center" width="60%" rowspan="5"> <select size="1" name="ug" onchange=\'prov('+od+',"ug")\' class=combofight style="text-align: center; width:45%" id="ug" ip="1"> <option selected value=0>Удар в голову</option>'+bliz+' <option value="magic">Магия[2]</option> <option value="kid">Кинуть предмет[3]</option> </select>		 <select size="1" name="bg" style="text-align: center; width:45%" onchange=\'prov('+od+',"bg")\' class=combofight id="bg" ip="1"> <option selected>Блок Головы</option> <option value=1>Простой [1]</option> <option value=2>Усиленый [2]</option> <option value="5">Крепчайший[5]</option> </select> <br>   <select size="1" name="ut" onchange=\'prov('+od+',"ut")\' class=combofight style="text-align: center; width:45%" id="ut" ip="1"> <option selected value=0>Удар в грудь</option>'+bliz+' <option value="magic">Магия[2]</option> <option value="kid">Кинуть предмет[3]</option> </select>		<select size="1" name="bt" style="text-align: center; width:45%" onchange=\'prov('+od+',"bt")\' class=combofight id="bt" ip="1"> <option selected>Блок Груди</option> <option value=1>Простой [1]</option> <option value=2>Усиленый [2]</option> <option value="5">Крепчайший[5]</option>	 </select> <br>  <select size="1" name="uj" onchange=\'prov('+od+',"uj")\' class=combofight style="text-align: center; width:45%" id="uj" ip="1"> <option selected value=0>Удар в живот</option>'+bliz+' <option value="magic">Магия[2]</option> <option value="kid">Кинуть предмет[3]</option> </select>		<select size="1" name="bj" style="text-align: center; width:45%" onchange=\'prov('+od+',"bj")\' class=combofight id="bj" ip="1"> <option selected>Блок живота</option> <option value=1>Простой [1]</option> <option value=2>Усиленый [2]</option> <option value="5">Крепчайший[5]</option> </select> <br>  <select size="1" name="un" onchange=\'prov('+od+',"un")\' class=combofight style="text-align: center; width:45%" id="un" ip="1"> <option selected value=0>Удар в ноги</option>'+bliz+' <option value="magic">Магия[2]</option> <option value="kid">Кинуть предмет[3]</option> </select>		<select size="1" name="bn" style="text-align: center; width:45%" onchange=\'prov('+od+',"bn")\' class=combofight id="bn" ip="1"> <option selected>Блок Ног</option> <option value=1>Простой [1]</option> <option value=2>Усиленый [2]</option> <option value="5">Крепчайший[5]</option></select> <br>');
}

function show_stats(s1,s2,s3,s4,s5,s6)
{
if(s1<1)s1=1;
if(s2<1)s2=1;
if(s3<1)s3=1;
if(s4<1)s4=1;
if(s5<1)s5=1;
if(s6<1)s6=1;
document.write('<table border="0" width="100%" cellspacing="1"><tr> <td><font class=stats>Сила:</font></td> <td><font class=user>'+s1+'</font></td> </tr> <tr> <td><font class=stats>Реакция:</font></td> <td><font class=user>'+s2+'</font></td> </tr> <tr> <td><font class=stats>Удача:</font></td> <td><font class=user>'+s3+'</font></td> </tr> <tr> <td><font class=stats>Здоровье:</font></td> <td><font class=user>'+s4+'</font></td> </tr> <tr> <td><font class=stats>Интелект:</font></td> <td><font class=user>'+s5+'</font></td> </tr> <tr> <td><font class=stats>Сила&nbsp;Воли:</font></td> <td><font class=user>'+s6+'</font></td></tr></table>');
}