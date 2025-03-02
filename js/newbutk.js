document.write('<LINK href=css/ch_buttons.css rel=STYLESHEET type=text/css><body topmargin="0" style="word-spacing: 0; margin-left: 0; margin-right: 0" leftmargin=0 bgcolor=#333333><form action="msg.php" target="ChatRefresh" method=POST name=mess onsubmit="return top.mess();">');
function cler() {
document.mess.message.value = "";
}
function show_buttons(sign,h,m,s){
	if(top.loading)
		top.load(10);
var send = "onclick=\"document.mess.submit();\" style=\"cursor:pointer\" title='Отправить'";
var smiles = "onclick=\"top.show_smiles();\" style=\"cursor:pointer\" title='Смайлы'";
var refresh = "onclick=\"top.ch_refresh()\" style=\"cursor:pointer\" title='Обновить чат'";
var erase = "onclick=\"erase()\" style=\"cursor:pointer\" title='Очистить строку ввода'";
var clear = "onclick=\"top.cl_chat()\" style=\"cursor:pointer\" title='Очистить чат'";
var tref = "<span onclick=\"top.change_chatspeed();\" id=chatspeed title=\"Скорость обновления (раз в 10 секунд)\" class=ch_mode style='cursor:pointer;'>10</span>";
var lat = "<span onclick=\"top.ruslat_c();\" id=translit title=\"Транслит выключен\" class=ch_mode style='cursor:pointer;'>Lat Off</span>";
var setup = "<span id=chatfyo onclick=\"top.change_chatsetup();\" title=\"Показывать все сообщения\" class=ch_mode style='cursor:pointer;'>Все</span>";
var sizec = 300;

document.write('<table border="0" width="'+(top.DWidth-373)+'" cellspacing="0" cellpadding="0" background="images/DS/chbtn_bg.png" height="30"> 	<tr> 		<td background="images/DS/chbtn_line.png" height="9" width="'+(top.DWidth-373)+'" colspan="9"></td> 	</tr> 	<tr> 		<td width="66" style="cursor:pointer;" id=ttype onclick="ch_ttype(\''+sign+'\')" valign=center align=center>&nbsp;</td> 		<td width="36"> 		<img border="0" src="images/DS/chbtn_leftb.png" width="36" height="23"></td> 		<td width="'+(top.DWidth-700)+'"><input type=text name="message" class=message><input type="image" height="0" width="0" src="images/emp.gif" size="0"><input type=hidden name="ttype" value="0"><input type=hidden name=type value=1></td> 		<td width="36"><img border="0" src="images/DS/chbtn_rightb.png" width="36" height="23"></td> 		<td nowrap> <img border="0" src="images/DS/chbtn_send.png" width="16" height="15" '+send+'> <img border="0" src="images/DS/chbtn_erase.png" width="16" height="16" '+smiles+'> <img border="0" src="images/DS/chbtn_clear.png" width="16" height="16" '+clear+'> <img border="0" src="images/DS/chbtn_refresh.png" width="14" height="16" '+refresh+'> </td> 		<td width="10" style="background-repeat:no-repeat;background-position:center;"> 		<p align="center">&nbsp;</td> 		<td width="30" background="images/DS/chbtn_durationbg.png" style="background-repeat:no-repeat;background-position:center;" align=center>'+tref+'</td> 		<td width="44" background="images/DS/chbtn_modebg.png" style="background-repeat:no-repeat;background-position:center;" align=center>'+setup+'</td> 		<td width="43" background="images/DS/chbtn_latbg.png" style="background-repeat:no-repeat;background-position:center;" align=center> 		'+lat+'</td> 	</tr> </table>');
s = s+10;
if (s>59) {m++;s%=60;}
top.HOURS = h;
top.MINUTES = m;
top.SECONDS = s;
ch_ttype(sign);
}

function ch_ttype(sign,m)
{
	if (m===0)
	{
		document.mess.ttype.value = 'all';
		document.getElementById('ttype').innerHTML = '<b class=lall>ВСЕМ:</b>';
	}else
	if (document.mess.ttype.value == 'priv' && sign || m==2)
	{
		document.mess.ttype.value = 'clan';
		document.getElementById('ttype').innerHTML = '<b class=lclan>КЛАН:</b>';
	}else
	if (document.mess.ttype.value == 'all' || m==1)
	{
		document.mess.ttype.value = 'priv';
		document.getElementById('ttype').innerHTML = '<b class=lpriv>ПРИВАТ:</b>';
	}else
	{
		document.mess.ttype.value = 'all';
		document.getElementById('ttype').innerHTML = '<b class=lall>ВСЕМ:</b>';
	}
}