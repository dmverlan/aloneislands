function showmaxhp () {
document.getElementById("hips").innerHTML ='<font onmouseout="showcurhp();">'+Math.round(maxHP)+'</font>';
}
function showcurhp () {
document.getElementById("hips").innerHTML ='<font onmouseover="showmaxhp();">'+Math.round(curHP)+'</font>';
}
function showmaxma () {
document.getElementById("mans").innerHTML ='<font onmouseout="showcurma();">'+Math.round(maxMA)+'</font>';
}
function showcurma () {
document.getElementById("mans").innerHTML ='<font onmouseover="showmaxma();">'+Math.round(curMA)+'</font>';
}

function show_hp (chp,hp,cma,ma) {
maxHP = hp;
maxMA = ma;
curHP = chp;
curMA = cma;
var h,m;
h = chp;
m = cma;
if (chp > hp ) chp = hp;
if (cma > ma ) cma = ma;
if (chp == hp) chp = chp - hp/44;
if (cma == ma) cma = cma - ma/44;
if (chp == 0) chp = hp/88;
if (cma == 0) cma = ma/88;
if (Math.round(88*(ma-cma)/ma)+Math.round(88*cma/ma)<88) cma = cma+ma/88;
if (Math.round(88*(hp-chp)/hp)+Math.round(88*chp/hp)<88) chp = chp+hp/88;
document.write ('<table border="0" id="table2" cellspacing="0" cellpadding="0" height="151"><tr><td width="54" height="126"><table border="0" width="54" id="table3" cellspacing="0" cellpadding="0" height="126"><tr><td width="54" colspan="3" height="27"><img border="0" src="images/design/borderhp_top.jpg" width="54" height="27"></td></tr><tr><td width="12" rowspan="2"><img border="0" src="images/design/borderhp_left.jpg" width="12" height="88"></td><td width="27"><img src="images/design/hpno.jpg" width=27 height="'+Math.round(88*(ma-cma)/ma)+'"  name=mano></td><td width="15" rowspan="2"><img border="0" src="images/design/borderhp_right.jpg" width="15" height="88"></td></tr><tr><td width="27"><img src="images/design/mais.jpg" width=27 height="'+Math.round(88*cma/ma)+'" name=mais></td></tr><tr><td width="54" colspan="3" height="11"><img border="0" src="images/design/borderhp_bottom.jpg" width="54" height="11"></td></tr></table></td><td height="126"><table border="0" width="54" id="table4" cellspacing="0" cellpadding="0" height="126"><tr><td width="54" colspan="3" height="27"><img border="0" src="images/design/borderhp_top.jpg" width="54" height="27"></td></tr><tr><td width="12" rowspan="2"><img border="0" src="images/design/borderhp_left.jpg" width="12" height="88"></td><td width="27"><img src="images/design/hpno.jpg" width=27 height="'+Math.round(88*(hp-chp)/hp)+'"  name=hpno></td><td width="15" rowspan="2"><img border="0" src="images/design/borderhp_right.jpg" width="15" height="88"></td></tr><tr><td width="27"><img src="images/design/hpis.jpg" width=27 height="'+Math.round(88*chp/hp)+'" name=hpis></td></tr><tr><td width="54" colspan="3" height="11"><img border="0" src="images/design/borderhp_bottom.jpg" width="54" height="11"></td></tr></table></td></tr><tr><td width="54" background="images/design/hp_back.jpg" style="vertical-align: middle; color: #00FFFF; font-family: Arial Black; font-size: 7pt"><div id="mans" align="center"><font onmouseover="showmaxma();">'+m+'</font></div></td><td background="images/design/hp_back.jpg" style="color: #FF0000; font-family: Arial Black; font-size: 7pt"><div id="hips" align="center"><font onmousover="showmaxhp();">'+h+'</font></div></td></tr></table>');
}

function show_hp_on_div (chp,hp,cma,ma,div_id) {
maxHP = hp;
maxMA = ma;
curHP = chp;
curMA = cma;
var h,m;
h = chp;
m = cma;
if (chp > hp ) chp = hp;
if (cma > ma ) cma = ma;
if (chp == hp) chp = chp - hp/44;
if (cma == ma) cma = cma - ma/44;
if (chp == 0) chp = hp/88;
if (cma == 0) cma = ma/88;
if (Math.round(88*(ma-cma)/ma)+Math.round(88*cma/ma)<88) cma = cma+ma/88;
if (Math.round(88*(hp-chp)/hp)+Math.round(88*chp/hp)<88) chp = chp+hp/88;
document.getElementById(div_id).innerHTML = '<table border="0" id="table2" cellspacing="0" cellpadding="0" height="151"><tr><td width="54" height="126"><table border="0" width="54" id="table3" cellspacing="0" cellpadding="0" height="126"><tr><td width="54" colspan="3" height="27"><img border="0" src="images/design/borderhp_top.jpg" width="54" height="27"></td></tr><tr><td width="12" rowspan="2"><img border="0" src="images/design/borderhp_left.jpg" width="12" height="88"></td><td width="27"><img src="images/design/hpno.jpg" width=27 height="'+Math.round(88*(ma-cma)/ma)+'"  name=mano></td><td width="15" rowspan="2"><img border="0" src="images/design/borderhp_right.jpg" width="15" height="88"></td></tr><tr><td width="27"><img src="images/design/mais.jpg" width=27 height="'+Math.round(88*cma/ma)+'" name=mais></td></tr><tr><td width="54" colspan="3" height="11"><img border="0" src="images/design/borderhp_bottom.jpg" width="54" height="11"></td></tr></table></td><td height="126"><table border="0" width="54" id="table4" cellspacing="0" cellpadding="0" height="126"><tr><td width="54" colspan="3" height="27"><img border="0" src="images/design/borderhp_top.jpg" width="54" height="27"></td></tr><tr><td width="12" rowspan="2"><img border="0" src="images/design/borderhp_left.jpg" width="12" height="88"></td><td width="27"><img src="images/design/hpno.jpg" width=27 height="'+Math.round(88*(hp-chp)/hp)+'"  name=hpno></td><td width="15" rowspan="2"><img border="0" src="images/design/borderhp_right.jpg" width="15" height="88"></td></tr><tr><td width="27"><img src="images/design/hpis.jpg" width=27 height="'+Math.round(88*chp/hp)+'" name=hpis></td></tr><tr><td width="54" colspan="3" height="11"><img border="0" src="images/design/borderhp_bottom.jpg" width="54" height="11"></td></tr></table></td></tr><tr><td width="54" background="images/design/hp_back.jpg" style="vertical-align: middle; color: #00FFFF; font-family: Arial Black; font-size: 7pt"><div id="mans" align="center"><font onmouseover="showmaxma();">'+m+'</font></div></td><td background="images/design/hp_back.jpg" style="color: #FF0000; font-family: Arial Black; font-size: 7pt"><div id="hips" align="center"><font onmousover="showmaxhp();">'+h+'</font></div></td></tr></table>';
}