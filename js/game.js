var ruslat = 0;
var fr_size = 200;
var ChatTimerID = -1;
var ChatDelay = 10;
var ChatClearTimerID = -1;
var ChatClearDelay = 600;
var ChatClearSize = 12228;
var ChatFyo=0;
var is_ctrl = 0;
var is_alt = 0;
var chlistref=0;
var RefresherID = -1;
var MRefresherID = -1;
var p = 0;
var button = 0;
var refer = 0;
var clearer = 0;
var chlistr=0;
var smiles_text = '';
var error_ch=0;
var ch_menu_opened=0;
var ChatTxt_ALL = '';
var ChatTxt_ECONOM = '';
var ChatTxt_BATTLE = '';
var chat_turn=1;
var RETURN_win = '';
var loading_interval = -1;
var loading_faze = 0;
var ID_return='';
var statusMSG=1;
var HOURS = 0;
var MINUTES = 0;
var SECONDS = 0;
var latency=0;
var latency_m = 0;
var refreshers = 0;
var SERVER_STATE = 0;
var latencyTM = -1;
var Smiles_OPENED = false;
var mode = 1;
var Msg_Sended = false;
var scroll_progress=0;
var intervscroll = -1;
var maxscroll = 80;
var step=5;
var _duration = 120;
var game_tips = 1;
var laws_tips = 1;
var ready = 1;
var ch_size_interv = -1;
var Resizing = 0;
var SoundsOn = 1;
var SoundsVol = 50;
var DH = document.body.clientHeight;
var PrevMessage = '';
var ResizeVal;
var logo_showed = 1;
var clock_showed = 1;
var ScreenWidth = screen.width;
var prc=0;
var loading = 0;
var load_int = -1; 
var DWidth;

var map_en = new Array('s`h','S`h','S`H','s`Х','sh`','Sh`','SH`',"'o",'yo',"'O",'Yo','YO','zh','w','Zh','ZH','W','ch','Ch','CH','sh','Sh','SH','e`','E`',"'u",'yu',"'U",'Yu',"YU","'a",'ya',"'A",'Ya','YA','a','A','b','B','v','V','g','G','d','D','e','E','z','Z','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P','r','R','s','S','t','T','u','U','f','F','h','H','c','C','`','y','Y',"'")
var map_ru = new Array('сх','Сх','СХ','сХ','щ','Щ','Щ','ё','ё','Ё','Ё','Ё','ж','ж','Ж','Ж','Ж','ч','Ч','Ч','ш','Ш','Ш','э','Э','ю','ю','Ю','Ю','Ю','я','я','Я','Я','Я','а','А','б','Б','в','В','г','Г','д','Д','е','Е','з','З','и','И','й','Й','к','К','л','Л','м','М','н','Н','о','О','п','П','р','Р','с','С','т','Т','у','У','ф','Ф','х','Х','ц','Ц','ъ','ы','Ы','ь')

var GetById = function (id) {return document.getElementById(id);}

function set_return_win(ID_r)
{
	ID_return=ID_r;
	top.frames["main_top"].document.getElementById(ID_return).innerHTML = '<div class=but>Загрузка<br><img src=images/spinner.gif></div>';
}

function show_return(text)
{
	top.frames["main_top"].document.getElementById(ID_return).innerHTML = text;
}

function say_private(login,privat)
{
	 var actionlog = top.frames['main_top'].ActionFormUse;
	 if((actionlog != null) && (actionlog != ""))
	 {
		top.frames['main_top'].document.getElementById(actionlog).value=login;
		top.frames['main_top'].document.getElementById(actionlog).focus();
	 }
	 else
	  if(is_ctrl)
	  {
		while(login.indexOf(' ') >=0) login = login.replace (' ', '%20');
		while(login.indexOf('+') >=0) login = login.replace ('+', '%2B');
		while(login.indexOf('#') >=0) login = login.replace ('#', '%23');
		while(login.indexOf('=') >=0) login = login.replace ('=', '%3D');
		window.open('info.php?p='+login, '_blank');
	  }
	  else
	  {
		if(is_alt) 
			top.frames['ch_buttons'].ch_ttype('',1); 
		else 
			top.frames['ch_buttons'].ch_ttype('',0);
		top.frames['ch_buttons'].document.mess.message.focus();
		if(top.frames['ch_buttons'].document.mess.message.value.length < 255)
			top.frames['ch_buttons'].document.mess.message.value = login+'|'+top.frames['ch_buttons'].document.mess.message.value;
	  }
	if (privat==1) top.frames['ch_buttons'].ch_ttype('',1);
	if (privat==2) top.frames['ch_buttons'].ch_ttype('z',2);
	is_ctrl = 0;
	is_alt = 0;
}

function group_private(group)
{
	top.frames['ch_buttons'].ch_ttype('',1);
	if(top.frames['ch_buttons'].document.mess.message.value.length < 255)
	  top.frames['ch_buttons'].document.mess.message.value = group+top.frames['ch_buttons'].document.mess.message.value;
	top.frames['ch_buttons'].document.mess.message.focus();
}

function start()
{  
	if (readCookie("ChatDelay"))
	{
		ChatDelay = readCookie("ChatDelay");
		change_chatspeed();
	}
	if (readCookie("Translit"))
	{
		ruslat = readCookie("Translit");
		ruslat_c();
	}
	if (readCookie("ChatFyo"))
	{
		ChatFyo = readCookie("ChatFyo");
		change_chatsetup();
	}
	if (readCookie("SoundsVol"))
	{
		SoundsVol = readCookie("SoundsVol");
	}
	
	//setTimeout("top.frames['ch_list'].location = 'weather.php'",5000);
	MRefresherID = setInterval('main_refresher()', 1000);
	window.defaultStatus = 'Alone Islands - Вселенная в твоих руках!';
}

function ch_list_ref() 
{
	if (chlistr == 1) 
		chlistr=0; 
	else 
		chlistr=1;
}

function ch_refresh()
{      
	if(ChatFyo != 2 && statusMSG) 
	{
		if (!(refreshers%90))
		top.frames['ChatRefresh'].location = 'msg.php?fio='+ChatFyo+'&timer=1&rand='+Math.random();
		else
		top.frames['ChatRefresh'].location = 'msg.php?fio='+ChatFyo+'&rand='+Math.random();
		refreshers++;
		statusMSG = 0;
	}
}

function ruslat_c()
{
	createCookie("Translit",ruslat);
	if(ruslat == 0)
	{
		ruslat = 1;
		top.frames['ch_buttons'].document.getElementById('translit').innerHTML = 'Lat On';
		top.frames['ch_buttons'].document.getElementById('translit').title = 'Транслит включён';
	}
	else
	{
		ruslat = 0;
		top.frames['ch_buttons'].document.getElementById('translit').innerHTML = 'Lat Off';
		top.frames['ch_buttons'].document.getElementById('translit').title = 'Транслит выключен';
	}
	top.frames['ch_buttons'].document.mess.message.focus();
}

function main_refresher()
{      	
		SECONDS++;
		if (SECONDS>59) {SECONDS=0;MINUTES++;}
		if (MINUTES>59) {MINUTES=0;HOURS++;}
		if (HOURS>23) HOURS=0;
		document.getElementById('TIME').innerHTML = '<b>'+transform_time(HOURS)+':'+
		transform_time(MINUTES)+':'
		+transform_time(SECONDS)+'</b>';
		/*if(ScreenWidth != screen.width)
			setTimeout("location = 'game.php'",4000);*/
		
		if(ChatTimerID == -1)
		{
			ChatTimerID = setInterval('ch_refresh()', ChatDelay*1000);
		}
		if(chlistref)
		{
			top.frames['ch_list'].location = 'ch.php';
			chlistref = 0;
		}
}	 
  
function transform_time(intt)
{
if (intt<10) return '0'+intt; else return intt;
}

function change_chatspeed()
{
	 createCookie("ChatDelay",ChatDelay);
	 if(ChatDelay == 10) ChatDelay = 30;
	 else if(ChatDelay == 30) ChatDelay = 60;
	 else ChatDelay = 10;
	 clearInterval(ChatTimerID);
	 ChatTimerID = setInterval('ch_refresh()', ChatDelay*1000);
	 top.frames['ch_buttons'].document.getElementById('chatspeed').innerHTML = ChatDelay;
	 top.frames['ch_buttons'].document.getElementById('chatspeed').title = 'Скорость обновления (раз в '+ChatDelay+' секунд)';
	 top.frames['ch_buttons'].document.mess.message.focus();
}

function mess(){
if(PrevMessage == top.frames['ch_buttons'].document.mess.message.value) 
{
	top.frames['ch_buttons'].document.mess.message.value = '';
	return false;
}
if (!Msg_Sended && top.frames['ch_buttons'].document.mess.message.value)
{
PrevMessage = top.frames['ch_buttons'].document.mess.message.value;
var str = top.frames['ch_buttons'].document.mess.message.value;
top.frames['chmain'].changeChatOrientation(top.frames['ch_buttons'].document.mess.type.value);
if (ruslat == 1) 
 {
		var exploded = str.split("|");
		str = exploded[exploded.length-1];
		for (var i = 0; i <map_en.length; ++i)
		while (str.indexOf (map_en[i]) >= 0)
		str = str.replace (map_en[i], map_ru[i]);
		exploded[exploded.length-1] = str;
		str = exploded.join("|");
 }
top.frames['ch_buttons'].document.mess.message.value = str;
clearInterval (ChatTimerID);
ChatTimerID = setInterval('ch_refresh()', ChatDelay*1000);
	
Msg_Sended = true;
top.frames['ch_buttons'].document.mess.message.focus();
return true;
}
}

function cl_chat(){
if (confirm("Вы точно хотите стереть чат?"))
{
if (chat_turn == 1) top.frames['chmain'].document.getElementById('c1').innerHTML = '';
else if (chat_turn == 2) top.frames['chmain'].document.getElementById('c2').innerHTML = '';
else if (chat_turn == 3) top.frames['chmain'].document.getElementById('c3').innerHTML = '';
}
}

function change_chatsetup()
{
		 createCookie("ChatFyo",ChatFyo);
       if(ChatFyo == 0)
       {
         ChatFyo = 1;
         top.frames['ch_buttons'].document.getElementById('chatfyo').innerHTML = 'Мои';
         top.frames['ch_buttons'].document.getElementById('chatfyo').title = 'Показывать только личные сообщения';
       }
       else if(ChatFyo == 1)
       {
          ChatFyo = 2;
          top.frames['ch_buttons'].document.getElementById('chatfyo').innerHTML = 'Вык';
          top.frames['ch_buttons'].document.getElementById('chatfyo').title = 'Не показывать сообщения';
       }
       else
       {
          ChatFyo = 0;
          top.frames['ch_buttons'].document.getElementById('chatfyo').innerHTML = 'Все';
          top.frames['ch_buttons'].document.getElementById('chatfyo').title = 'Показывать все сообщения';
       }
	   top.frames['ch_buttons'].document.mess.message.focus();
}

function index(){
top.location ='index.php';
}

function goloc(location,time) {
top.frames['main_top'].location='main.php?goloc='+location+'&time='+time;
chlistref = 1;
}

function sm_ins (sm)
{
top.frames['ch_buttons'].document.mess.message.value += '//'+sm+' ';
}

function re_up_ref () {
top.frames['main_top'].location='main.php';	  
}

function add_msg (msg,ttt,add) {
if (!top.frames['chmain'].document.getElementById('chat')) return false;
if ((msg != "")) {
if (clearer == 1) 
{
  top.frames['ch_buttons'].document.mess.message.value = "";
  Msg_Sended = false;
 clearer=0;
}
  top.frames['ch_buttons'].document.mess.message.disabled = false;

if (ttt == 1)
{
	var tmpLen = top.frames['chmain'].jQuery("#c1 > *").length;
	var tmpI = 0;
	if (tmpLen>52)
	{
		top.frames['chmain'].jQuery("#c1 > *").each(function(){tmpI++;if (tmpI<(tmpLen-52)){top.frames['chmain'].jQuery(this).remove();}});
	}
	top.frames['chmain'].document.getElementById('c1').innerHTML += msg;
}
else if (ttt == 2)
{
	var tmpLen = top.frames['chmain'].jQuery("#c2 > *").length;
	var tmpI = 0;
	if (tmpLen>32)
	{
		top.frames['chmain'].jQuery("#c2 > *").each(function(){tmpI++;if (tmpI<(tmpLen-32)){top.frames['chmain'].jQuery(this).remove();}});
	}
	top.frames['chmain'].document.getElementById('c2').innerHTML += msg;
}
else if (ttt != 0)
top.frames['chmain'].document.getElementById('c3').innerHTML += msg;

if (ttt==0)
top.frames['chmain'].document.getElementById('c1').innerHTML += msg;

}

if (add)
{
if (chat_turn == 1) 
{
top.frames['chmain'].document.getElementById('c1').style.display = 'block';
top.frames['chmain'].document.getElementById('c2').style.display = 'none';
top.frames['chmain'].document.getElementById('c3').style.display = 'none';
}
else if (chat_turn == 2) 
{
top.frames['chmain'].document.getElementById('c2').style.display = 'block';
top.frames['chmain'].document.getElementById('c1').style.display = 'none';
top.frames['chmain'].document.getElementById('c3').style.display = 'none';
}
else if (chat_turn == 3) 
{
top.frames['chmain'].document.getElementById('c3').style.display = 'block';
top.frames['chmain'].document.getElementById('c2').style.display = 'none';
top.frames['chmain'].document.getElementById('c1').style.display = 'none';
}
top.frames['chmain'].scroll_chat();
}
}

function helpwin(page)
{
       url = 'http://aloneislands.ru/help/'+page;
       viewwin = open(url,"helpWindow","width=420, height=400, status=no, toolbar=no, menubar=no, resizable=no, scrollbars=yes");
}

function show_smiles(){
var sm = top.frames['ch_list'].document.getElementById('smiles');
var ch = top.frames['ch_list'].document.getElementById('head');
var ch2 = top.frames['ch_list'].document.getElementById('ch');
if (sm.style.visibility == 'visible') {hide_smiles();Smiles_OPENED=false;return false;}
if (smiles_text == ''){
var i=0;
var num=0;
for (i=1;i<268;i++){num=i;
if (num/100<1) if (num/10<1) num='00'+num;
else if (num/100<1) if (num/10>=1) num='0'+num;
smiles_text += '<img src="images/smiles/smile_'+num+'.gif" onclick="top.sm_ins(\''+num+'\');" style="cursor:hand;" title="//'+num+'">';
}
}
sm.innerHTML = '<br><br><hr>'+smiles_text;
sm.top = 0;
sm.style.visibility = 'visible';
ch.style.visibility = 'hidden';
ch2.style.visibility = 'hidden';
Smiles_OPENED=true;;
}


function hide_smiles(){
var sm = top.frames['ch_list'].document.getElementById('smiles');
var ch = top.frames['ch_list'].document.getElementById('head');
var ch2 = top.frames['ch_list'].document.getElementById('ch');
sm.style.visibility = 'hidden';
ch.style.visibility = 'visible';
ch2.style.visibility = 'visible';
sm.top = -10000;
top.frames['ch_list'].scrollBy(0,-65000);
}

function flog_clear()
{
	top.frames['chmain'].document.getElementById('c3').innerHTML = '';
}

function flog_set()
{
top.frames['chmain'].changeChatOrientation(3);
}

function flog_unset()
{
top.frames['chmain'].changeChatOrientation(1);
}

function aready ()
{
	ready = 1;
}

function dw(t)
{
	document.write(t);
}

function main_top_update()
{
	if(top.frames["main_top"])
		top.frames["main_top"].location = 'main.php';
}

function view_frames(a)
{
	DWidth = document.body.clientWidth-12;
	if(DWidth<960) DWidth = 960;
	if(DWidth>1200) DWidth = 1200;
	var bg = 'god.jpg';
	if(a)
		bg = 'god'+rand(2,3)+'.jpg';
	
	dw('<center style="background-image: url(\'images/DS/'+bg+'\');width:100%;height:100%;background-position:bottom center;" onload="load(10);">');
	dw('<div id=frames>');
	dw('<div id=tl style="position: absolute; width: 52px; height: 57px; z-index: 2; left: -1000px; top: 0px"><img border="0" src="images/DS/tl_corner.png" width="52" height="57"></div>');
	dw('<div id=tr style="position: absolute; width: 52px; height: 57px; z-index: 2; left: -1000px; top: 0px"><img border="0" src="images/DS/tr_corner.png" width="52" height="57"></div>');
	dw('<div id=bl style="position: absolute; width: 52px; height: 57px; z-index: 2; left: -1000px; top: 0px"><img border="0" src="images/DS/bl_corner.png" width="52" height="57"></div>');
	dw('<div id=br style="position: absolute; width: 52px; height: 57px; z-index: 2; left: -1000px; top: 0px"><img border="0" src="images/DS/br_corner.png" width="52" height="57"></div>');
	
	dw('<div id=bandl style="position: absolute; width: 97px; height: 86px; z-index: 0; left: -1000px; top: 30px"><img border="0" src="images/DS/left_band.png" width="97" height="86"></div>');
	dw('<div id=bandr style="position: absolute; width: 97px; height: 86px; z-index: 0; left: -1000px; top: 30px"><img border="0" src="images/DS/right_band.png" width="97" height="86"></div>');
	
	dw('<div id=gl style="position: absolute; width: 38px; height: 369px; z-index: 1; left: -1000px; top: 0px"><img border="0" src="images/DS/green_left.png" width="38" height="369"></div>');
	dw('<div id=gr style="position: absolute; width: 42px; height: 369px; z-index: 1; left: -1000px; top: 0px"><img border="0" src="images/DS/green_right.png" width="42" height="368"></div>');
	
	dw('<div id=left_picture1 style="position: absolute; width: 100px; height: 120px; z-index: 2; left: -1000px; top: 0px;background-image: url(\'images/DS/left_picture.png\');"></div>');
	dw('<div id=left_picture2 style="position: absolute; width: 141px; height: 80px; z-index: 2; left: -1000px; top: 0px;background-image: url(\'images/DS/left_picture.png\'); background-position:bottom left;"></div>');
	
	dw('<div id=right_green_down style="position: absolute; width: 348px; height: 27px; z-index: 2; left: -1000px; top: 0px;background-image: url(\'images/DS/right_green_down.png\');"></div>');
	dw('<div id=sword1 style="position: absolute; width: 147px; height: 48px; z-index: 3; left: -1000px; top: 0px;background-image: url(\'images/DS/sword.png\'); background-position:bottom left;"></div>');
	dw('<div id=sword2 style="position: absolute; width: 100px; height: 83px; z-index: 3; left: -1000px; top: 0px;background-image: url(\'images/DS/sword.png\'); background-position:top right;"></div>');
	dw('<div id=right_green_up style="position: absolute; width: 368px; height: 43px; z-index: 4; left: -1000px; top: 0px;background-image: url(\'images/DS/right_green_up.png\');"></div>');
	
	
	dw('<div id=clock style="position: absolute; width: 134px; height: 43px; z-index: 2; left: -1000px; top: 0px; background-image: url(\'images/DS/clock_bg.png\')">');
	dw('<table height=100% width=100%><tr><td align=center valign=center id=TIME style="color:#FFFFFF;cursor:pointer;" onclick="top.frames[\'main_top\'].location=\'main.php\'" title="Часы показывают серверное время(Россия>Москва), при нажатии обновит игровое окно.">Загрузка...</td></tr></table>');
	dw('</div>');
	
	dw('<div id=title style="background-image: url(\'images/DS/title_bg.jpg\'); background-positin: center top; position: absolute; width: '+(DWidth-36)+'px; height: 50px; z-index: 1; left: -1000px; top: 18px"></div>');
	dw('<div id=logo style="background-image: url(\'images/DS/logo_bg.gif\'); background-position: bottom; background-repeat:repeat-x; position: absolute; width: '+(DWidth-36)+'px; height: 90px; z-index: 1; left: -1000px; top: -20px; text-align: center; cursor:pointer;" onclick="main_top_update();"><img border="0" src="images/DS/logo.png" width="924" height="92"></div>');
	dw('<div id=logo_down style="position: absolute; width: 206px; height: 29px; z-index: 1; left: -1000px; top: 72px"><img border="0" src="images/DS/logo_down.png" width="206" height="29"  onclick="main_top_update();"></div>');
	dw('<div id=title_buttons style="position: absolute; width: '+(DWidth-36)+'pxpx; height: 42px; z-index: 1; left: -1000px; top: 18px"></div>');
	
	dw('<table border="0" cellspacing="0" cellpadding="0" style="width:'+DWidth+'px; height:100%;">');
	dw('<tr>');
	dw('<td id=maintd valign=top>');
	dw('<table border="0" height=100% width='+DWidth+' style="width:'+DWidth+'px;height:100%" cellspacing="0" cellpadding="0" id=maintbl>');
	dw('<tr>');
	dw('<td height="18" colspan="3" background="images/DS/top_border.gif">&nbsp;</td>');
	dw('</tr>');
	dw('<tr>');
	dw('<td width="18" background="images/DS/left_border.gif" style="width:18px;">&nbsp;</td>');
	dw('<td valign=top align=center background="images/DS/main_bg.png" style="width:'+(DWidth-36)+'px;">');
	dw("<iframe src='main.php' id=main_top name=main_top class=iframe scrolling=auto noResize frameborder=0 border=0 framespacing=0 marginwidth=0 marginheight=0 style='width:100%;height:100%;background-color:transparent;' allowtransparency=\"true\">Обновите браузер.</iframe>");
	dw('</td>');
	dw('<td width="18" background="images/DS/right_border.gif" style="width:18px;">&nbsp;</td>');
	dw('</tr>');
	dw('</table>');
	dw('</td>');
	dw('</tr>');
	dw('<tr>');
	dw('<td>');
	dw('<table border="0" cellspacing="0" cellpadding="0" style="width:100%;height:100%;" height=100% width=100%>');
	dw('<tr>');
	dw('<td height="22" colspan="3" background="images/DS/c_vside_border.gif" onmousedown=\'InitResize();\' style="cursor:move;">&nbsp;</td>');
	dw('<td height="22" valign=top width="300" background="images/DS/chlist_bg.jpg"><div style="background-image: url(\'images/DS/top_border.gif\');width:100%;height:18px;"></div></td>');
	dw('<td width="18" rowspan="3" background="images/DS/right_border.gif">&nbsp;</td>');
	dw('</tr>');
	dw('<tr>');
	dw('<td width="22" background="images/DS/c_side_border.gif">&nbsp;</td>');
	dw('<td valign=top style="width:'+(DWidth-336)+'px;">');
	dw('<table border="0" cellspacing="0" cellpadding="0" style="width:100%;height:100%" background="images/DS/chat_bg.jpg">');
	dw('<tr><td valign=top>');
	dw("<iframe src='chat.php' id=chmain name=chmain scrolling=auto noResize frameborder=0 border=0 framespacing=0 marginwidth=0 marginheight=0 style='width:100%;height:100%;border:0;' class=iframe3 allowtransparency=\"true\">Обновите браузер.</iframe>");
	dw('</td></tr>');
	dw('<tr><td style="height:30px;" height=20>');
	dw("<iframe src='but.php' id='ch_buttons' name='ch_buttons' scrolling=no noResize frameborder=0 border=0 framespacing=0 marginwidth=0 marginheight=0 style='width:100%;height:100%;border:0;' allowtransparency=\"true\">Обновите браузер.</iframe>");
	dw('</td></tr>');
	dw('</table>');
	dw('</td>');
	dw('<td width="22" background="images/DS/c_side_border.gif">&nbsp;</td>');
	dw('<td width="320" valign=top bgcolor="#000" style="overflow:hidden;">');
	dw("<iframe src='ch.php' id=ch_list name=ch_list scrolling=auto noResize frameborder=0 border=0 framespacing=0 marginwidth=0 marginheight=0 style='width:320px;height:100%;border:0;' class=iframe2 allowtransparency=\"true\">Обновите браузер.</iframe>");
	dw('</td>');
	dw('</tr>');
	dw('<tr>');
	dw('<td width="600" height="22" colspan="3" background="images/DS/c_vside_border.gif">&nbsp;</td>');
	dw('<td height="22" valign=bottom width="320" background="images/DS/chlist_bg.jpg"><div style="background-image: url(\'images/DS/top_border.gif\');width:100%;height:18px;"></div></td>');
	dw('</tr>');
	dw('</table>');
	dw('</td>');
	dw('</tr>');
	dw('</table>');
	dw('</div>');
	dw('</center>');
	
	
	//document.getElementById('logo_down').style.top = document.getElementById('logo').style.top + document.getElementById('logo').style.height;
		
	
	
		dw("<iframe style='display:none;' src='' name='updater' id='updater'></iframe>");
		dw("<iframe style='display:none;' src='' name='ChatRefresh' id='ChatRefresh'></iframe>");
		dw("<iframe style='display:none;' src='' name='returner' id='returner'></iframe>");
		
		dw('<div class=news style="position:absolute;top:0px;left:0px;width:100%;height:100%;display:none;z-index:5;opacity:0.2;filter:alpha(opacity=20);cursor:move;" id=bgblack onmouseup="DestrResize()"  onMouseMove="Resizer(event)"></div>');
		
		dw('<div class=news style="position:absolute;top:0px;left:0px;width:100%;height:100%;display:none;z-index:5;opacity:0.4;filter:alpha(opacity=70);cursor:pointer;" id=bgblack2 onclick="FuncyOff()"></div>');
		dw('<div class=inv style="position:absolute;top:10%;left:10%;width:80%;height:80%;display:none;z-index:6;" id=frame></div>');
		dw('<div style="position:absolute;top:45%;left:45%;width:15%;height:10%;display:none;z-index:6;" id=frame2></div>');
		dw('<div style="position:absolute;top:10%;left:'+((document.body.clientWidth-445)/2)+'px;width:445px;height:445px;display:none;z-index:65000;" id=GreenBox></div>');
		
		dw('<SCRIPT type="text/javascript" src="js/tools/sm2.js"></SCRIPT>');
		dw('<SCRIPT type="text/javascript" src="js/soundMixes.js"></SCRIPT>');
		
		dispose_layers();
		
		document.body.onresize = dispose_layers;

		ChatTimerID = setInterval('ch_refresh()', ChatDelay*1000);		
		prc = 0;
		load(0);
}

function dispose_layers()
{
	var MWindowLeft = document.body.clientWidth/2-DWidth/2;
	var MWindowBottom = document.body.clientHeight;
	GetById('tl').style.left = MWindowLeft;
	GetById('tr').style.left = MWindowLeft+DWidth-52;
	GetById('bl').style.left = MWindowLeft;
	GetById('br').style.left = MWindowLeft+DWidth-52;
	GetById('bl').style.top = MWindowBottom-57;
	GetById('br').style.top = MWindowBottom-57;
	GetById('gl').style.left = MWindowLeft-38;
	GetById('gr').style.left = MWindowLeft+DWidth;
	GetById('bandl').style.left = MWindowLeft-97;
	GetById('bandr').style.left = MWindowLeft+DWidth;
	
	GetById('clock').style.top = MWindowBottom-44;
	GetById('clock').style.left = MWindowLeft-160+DWidth;
	
	GetById('left_picture1').style.left = MWindowLeft-100;
	GetById('left_picture2').style.left = MWindowLeft-100;
	GetById('left_picture1').style.top = 200;
	GetById('left_picture2').style.top = 320;
	
	GetById('right_green_down').style.left = MWindowLeft-350+DWidth;
	GetById('right_green_up').style.left = MWindowLeft-370+DWidth;
	GetById('sword1').style.left = MWindowLeft - 150+DWidth;
	GetById('sword2').style.left = MWindowLeft - 150+147+DWidth;
	
	
	GetById('logo').style.left = MWindowLeft+18;
	GetById('title').style.left = MWindowLeft+18;
	GetById('title_buttons').style.left = MWindowLeft+18;
	GetById('logo_down').style.left = MWindowLeft+(DWidth-206)/2+20;
	
	DH = document.body.clientHeight;
	ResizeVal = DH*0.7;
	GetById('maintd').style.height = ResizeVal-18+'px';
	GetById('maintbl').style.height = ResizeVal-18+'px';
	GetById('left_picture1').style.top = ResizeVal-200;
	GetById('left_picture2').style.top = ResizeVal-80;
	GetById('right_green_down').style.top = ResizeVal - 26;
	GetById('right_green_up').style.top = ResizeVal - 16;
	GetById('sword1').style.top = ResizeVal - 20;
	GetById('sword2').style.top = ResizeVal - 20 - 35;
	jQuery("iframe.iframe2").css({height:(DH-ResizeVal-26+'px')});
	jQuery("iframe.iframe3").css({height:(DH-ResizeVal-56+'px')});
}


function load(a,b)
{
	prc += a;
	if(b && loading)
	{	
		jQuery("#bgblack2").css({display:"none"});
		jQuery("#frame2").fadeOut(500);
		jQuery("#frames").css({display:"block"});
		loading = 0;
		start();
	}
	if(prc == 0 && !loading)
	{
		jQuery("#bgblack2").css({display:"block"});
		jQuery("#frame2").fadeIn(500);
		jQuery("#frames").css({display:"none"});
		jQuery("#frame2").html('<table cellspacing="0" cellpadding="0" style="position:relative;top:-8px;width: 100%;"> <tr> <td style="width: 18px; height: 18px"> <img src="images/left_top.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/top.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_top.png" width="18" height="18"></td> </tr> <tr> <td style="width: 18px;background-image: url(\'images/left.png\');">&nbsp;</td> <td style="background-image: url(\'images/bg.png\');" align=center>   <img src=\'images/bluespinner.gif\'><br><div id=load>Загрузка...['+prc+'%]</div></td> <td style="width: 18px;background-image: url(\'images/right.png\');">&nbsp;</td> </tr> <tr> <td style="width: 18px; height: 18px"> <img src="images/left_bottom.png" width="18" height="18"></td> <td style="height: 18px;background-image: url(\'images/bottom.png\');">&nbsp;</td> <td style="width: 18px; height: 18px"> <img src="images/right_bottom.png" width="18" height="18"></td> </tr> </table>');
		loading = 1;
	}
	if(prc<101)
		jQuery("#load").html("Загрузка...["+prc+"%]");
	if(prc >= 100)
	{
		load_int = setTimeout("load(0,1)",3000);
		jQuery("#load").html("Загрузка...[100%]");
	}
	else
		load_int = setTimeout("load(1)",200);
}

function hide_clock()
{
	if(!clock_showed) return;
	jQuery("#clock").css("display","none");
	clock_showed = 0;
}

function show_clock()
{
	if(clock_showed) return;
	jQuery("#clock").css("display","block");
	clock_showed = 1;
}

function hide_logo()
{
	if(!logo_showed) return;
	jQuery("#title").css("display","none");
	jQuery("#title_buttons").css("display","none");
	jQuery("#logo").css("display","none");
	jQuery("#logo_down").css("display","none");
	logo_showed = 0;
}

function show_logo()
{
	if(logo_showed) return;
	jQuery("#title").css("display","block");
	jQuery("#title_buttons").css("display","block");
	jQuery("#logo").css("display","block");
	jQuery("#logo_down").css("display","block");
	logo_showed = 1;
}

function Funcy(url)
{
	jQuery("#bgblack2").css({display:"block"});
	jQuery("#frame").fadeIn(500);
	jQuery("#frame").html("<iframe src='"+url+"' scrolling=auto noResize frameborder=0 border=0 framespacing=0 marginwidth=0 marginheight=0 style='width:100%;height:100%' class=iframe>Обновите браузер.</iframe>");
}

function FuncyOff()
{
	jQuery("#bgblack2").css({display:"none"});
	jQuery("#frame").css({display:"none"});
}

function GreenBox(text)
{
	jQuery("#GreenBox").fadeIn(500);
	jQuery("#GreenBox").html("<table border=0 cellspacing=0 cellspadding=0 width=100% height=100%>" +
			"<tr>" +
			"<td>" +
			"</td>" +
			"<td onclick='GreenBoxOff()'><img src='images/DS/green_top.png'></td>" +
			"<td>" +
			"</td>" +
			"</tr>" +
			"<tr>" +
			"<td><img src='images/DS/green_left.png'>" +
			"</td>" +
			"<td align=center valign=center style='background-image: url(\"images/DS/green_bg.jpg\")'>" + text +
			"</td>" +
			"<td><img src='images/DS/green_right.png'>" +
			"</td>" +
			"</tr>" +
			"<tr>" +
			"<td>" +
			"</td>" +
			"<td><img src='images/DS/green_bottom.png'></td>" +
			"<td>" +
			"</td>" +
			"</tr>" +
			"<tr>" +
			"</table>");
}

function GreenBoxOff()
{
	jQuery("#GreenBox").css({display:"none"});
}

function InitResize()
{
	Resizing = 1;
	jQuery("#bgblack").css({display:"block"});
	jQuery("#main_top").css({display:"none"});
	DH = (DH > document.height)?document.height:DH;
}

function DestrResize()
{
	Resizing = 0;
	jQuery("#bgblack").css({display:"none"});
	jQuery("#main_top").css({display:"block"});
	top.frames['ch_buttons'].document.mess.message.focus();
	if(top.frames['chmain'].document.getElementById('chat'))
		top.frames['chmain'].document.getElementById('chat').scrollTop += 6500;
}

function Resizer(event)
{
	var Y = event.clientY;
	if (!event) var event = window.event;
	if (Y>(DH-60))
		Y = (DH-60);
	if (Y>(DH-100))
		hide_clock();
	else
		show_clock();
	if (Y<200)
		Y = 200;
	if (Resizing)
	{
		document.getElementById('maintd').style.height = Y-18+'px';
		document.getElementById('maintbl').style.height = Y-18+'px';
		jQuery("#main_top").css({height:(Y-36+'px')});
		jQuery("iframe.iframe2").css({height:(DH-Y-26+'px'),width:'320px'});
		jQuery("iframe.iframe3").css({height:(DH-Y-56+'px')});
		document.getElementById('left_picture1').style.top = Y-200;
		document.getElementById('left_picture2').style.top = Y-80;
		document.getElementById('right_green_down').style.top = Y - 26;
		document.getElementById('right_green_up').style.top = Y - 16;
		document.getElementById('sword1').style.top = Y - 20;
		document.getElementById('sword2').style.top = Y - 20 - 35;

	}
}
function soundsVol(a)
{
	SoundsVol = parseFloat(SoundsVol);
	var tmp = SoundsVol;
	var ttmp;
	SoundsVol += a/20;
	if (SoundsVol<=0) 
	{
		SoundsVol = 0;
		StopMixes();
		top.frames['chmain'].document.getElementById('VolumeDown').className = 'Fader';
		return true;
	}
	else
		top.frames['chmain'].document.getElementById('VolumeDown').className = '';
	
	if (SoundsVol>10) 
	{
		SoundsVol = 10;
		top.frames['chmain'].document.getElementById('VolumeUp').className = 'Fader';
	}
	else
		top.frames['chmain'].document.getElementById('VolumeUp').className = '';
		
	for (var i=0;i<SoundCount;i++)
	{
		ttmp = (tmp!=0)?soundManager.getSoundById(SoundsList[i]).volume*SoundsVol/tmp:SoundsVol;
		if (ttmp>100) ttmp = 100;
		if (ttmp<1) 
		{
			soundManager.pauseAll();
			break;
		}
		else
		{
			soundManager.resumeAll();
		}
		soundManager.getSoundById(SoundsList[i]).setVolume(ttmp);
	}
	
	createCookie("SoundsVol",SoundsVol,0);
}


SoundsList = new Array();
var SoundCount = 0;

function Sound(a,b,loop)
{
if (b==undefined) b = 1;
if (!SoundsOn) return;
if (!soundManager.getSoundById(a))
{
	soundManager.createSound({
 id: a, // required
 url: 'sounds/'+a+'.mp3', // required
 // optional sound parameters here, see Sound Properties for full list
 volume: b*SoundsVol,
 autoPlay: true,
 onfinish: (loop==1)?function(){soundManager.play(a);}:null
	});
	SoundsList[SoundCount] = a;SoundCount++;	
}
if (soundManager.getSoundById(a).playState)
{
		soundManager.createSound({
 id: a+'_2', // required
 url: 'sounds/'+a+'.mp3', // required
 // optional sound parameters here, see Sound Properties for full list
 volume: b*SoundsVol,
 autoPlay: true,
 onfinish: (loop==1)?function(){soundManager.play(a);}:null
	});
	SoundsList[SoundCount] = a+'_2';SoundCount++;
	return soundManager.play(a+'_2');
}
else 
	return soundManager.play(a);
}

function rand(a1,a2)
{
	return Math.floor(a1+(Math.random()*10000)%(a2-a1+1));
}