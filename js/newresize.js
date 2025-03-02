var d = document; d.write('<table cellspacing="0" cellpadding="0" style="width: 100%; height: 16px; background-image: url(\'images/design/ice/re.gif\'); border-style:none;"> <tr> <td style="width: 40%" valign=bottom>');
d.write('<a class=ActiveBc href="javascript:changeChatOrientation(1)" id=ch1>Общий</a> <a class=ActiveBc href="javascript:changeChatOrientation(2)" id=ch2>Торговый</a> <a class=ActiveBc href="javascript:changeChatOrientation(3)" id=ch3>Лог Боя</a>');
d.write('</td> <td style="text-align: right; width: 40px;"> <img border="0" src="images/design/ice/up.gif" onclick="javascript:top.change_chatsize(\'0\');"> <img border="0" src="images/design/ice/dow.gif" onclick="javascript:top.change_chatsize(\'1\');"></td> <td class=buttonc id=dday title=\'\' style="width: 200px" align=right valign=top>Загрузка...</td> <td align=right style="width: 50px" valign=center><input type=checkbox name=ref onclick=\'top.ch_list_ref()\'  border=0 title=\'Автообновление списка чата\'><img src=\'images/gameplay/latency/hi.gif\' name=latency  width=16 title="Скорость соединения с проектом :  Высокая.  (0 ms.)" onclick="top.frames[\'ch_list\'].location=\'ch.php\';"></td></tr> </table>');

function changeChatOrientation(t)
{
	$("a").attr("class","ActiveBc");
	$("#ch"+t).addClass("nActiveBc");
	if (top.frames['ch_buttons'].document.mess.message)
	top.frames['ch_buttons'].document.mess.message.focus();
	if (top.frames['ch_buttons'].document.mess.type)
		top.frames['ch_buttons'].document.mess.type.value=t;
	top.chat_turn=t;
	top.add_msg('',0,t);
	top.frames['chmain'].scrollBy (0,65000);
	top.Msg_Sended = false;
}
changeChatOrientation(1);