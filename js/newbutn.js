document.write('<LINK href=ch_main.css rel=STYLESHEET type=text/css><body topmargin="0" style="word-spacing: 0; margin-left: 0; margin-right: 0" leftmargin=0><form action="msg.php" target="ch_refr" method=POST name=mess onsubmit="if (!top.ch_menu_opened) top.mess(); else return false;">');
function cler() {
document.mess.message.value = "";
}
function show_buttons(sign,h,m,s){
var send = "<img src=images/design/ice/1.gif title='Отправить' onclick=\"document.mess.submit();\">";
var smiles = "<img src=images/design/ice/3.gif onclick=\"top.show_smiles();\">";
var refresh = "<img src=images/design/ice/4.gif onclick=\"top.ch_refresh()\">";
var clear = "<img src=images/design/ice/2.gif onclick=\"top.cl_chat()\">";
var tref = "<img src=images/design/ice/10.gif onclick=\"top.change_chatspeed();\" name=chatspeed title=\"Скорость обновления (раз в 10 секунд)\">";
var lat = "<img src=images/design/ice/7.gif onclick=\"top.ruslat_c();\" name=translit title=\"Транслит выключен\">";
var setup = "<img src=images/design/ice/11.gif name=chatfyo onclick=\"top.change_chatsetup();\" title=\"Показывать все сообщения\">";
var sizec = 300;
document.write('<table style="width: 100%; height: 27px; background-image: url(\'images/design/ice/fg.gif\')" cellspacing="0" cellpadding="0"> <tr style="width: 41px; text-align: center"> <td style="width: 76px; background-image: url(\'images/design/ice/but_left.gif\')" id=ttype onclick="ch_ttype(\''+sign+'\')">&nbsp;</td> <td><input type=hidden name="ttype" value="0"><input type=hidden name=type value=1><input type=image width=0 height=0 size=0 src="images/emp.gif"><input class="laar" title="Сообщение" style="width: '+(screen.width - 206 - sizec)+'; height: 20; background:transparent" size="256" name="message"><input type="image" height="0" width="0" src="images/emp.gif" size="0"></td> <td style="width: 101px; text-align: right; background-image: url(\'images/design/ice/but_submit_bg.gif\')"> '+send+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+clear+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+refresh+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+smiles+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+tref+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+setup+'</td> <td style="width: 41px; background-image: url(\'images/design/ice/but_but_bg.gif\')"> '+lat+'</td> <td style="text-align: center" width="100" id=TIME class=buttonc onclick="top.frames[\'main_top\'].location=\'main.php\'" title="Часы показывают серверное время(Россия>Москва), при нажатии обновит игровое окно."><b>'+h+':'+m+':'+s+'</b></td> </tr> </table> '); 
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
		document.getElementById('ttype').innerHTML = '<li class=lall>ВСЕМ:</li>';
	}else
	if (document.mess.ttype.value == 'priv' && sign || m==2)
	{
		document.mess.ttype.value = 'clan';
		document.getElementById('ttype').innerHTML = '<li class=lclan>КЛАН:</li>';
	}else
	if (document.mess.ttype.value == 'all' || m==1)
	{
		document.mess.ttype.value = 'priv';
		document.getElementById('ttype').innerHTML = '<li class=lpriv>ПРИВАТ:</li>';
	}else
	{
		document.mess.ttype.value = 'all';
		document.getElementById('ttype').innerHTML = '<li class=lall>ВСЕМ:</li>';
	}
}