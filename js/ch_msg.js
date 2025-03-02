var el = document.getElementById("menu");
var interv = -1;
el.onmouseout = function () {interv = setTimeout("ch_hmenu()",500);}
el.onmouseover = function () {clearTimeout(interv);}
document.oncontextmenu = ch_open_menu;
document.ondblclick = ch_open_menu;
if (molch==undefined) var molch=0;
function ch_open_menu(event)
{
  var watcher_tool='';
  if (!event) var event = window.event;
  top.is_ctrl = event.ctrlKey;
  top.is_alt = event.altKey;
  var x, y, login, login2;
  var o;
  o = (event.srcElement)?event.srcElement:event.target;
  if (o.className != 'user') return false;
  login = o.innerHTML;
  if (login.length<2 || login=='&nbsp;') return false;
  if (login.indexOf(" ")==0) 
  login = login.substr(1,login.length-1);
  event.returnValue=false;
  if (login.length>30) return false;
  login2 = login;
  while (login2.indexOf(' ') >=0) login2 = login2.replace (' ', '%20');
  while (login2.indexOf('+') >=0) login2 = login2.replace ('+', '%2B');
  while (login2.indexOf('#') >=0) login2 = login2.replace ('#', '%23');
  while (login2.indexOf('?') >=0) login2 = login2.replace ('?', '%3F');
 if (molch) watcher_tool = '<tr><td><A class=bg href="javascript:top.Funcy(\'watchers.php?p='+login+'\')">Смотрителям</A></td></tr><tr><td><A HREF="javascript:silence(\''+login+'\');" class=bg>Молчания</A></td></tr>';
  el.innerHTML = '<table border=0 width=100 class=alt align=center><tr><td class=user align=center style="color:#FFF;">'+login+'</td></tr><tr><td width=100><A HREF="javascript:s_private(\''+login+'\');ch_hmenu();" class=bga>Приват</A></td></tr><tr><td width=100><A HREF="info.php?p='+login2+'" onclick="ch_hmenu();return true;" target=_blank class=bga>Информация</A></td></tr><tr><td><A HREF="javascript:set_ignore(\''+login2+'\');" class=bga width=100>Игнор</A></td></tr>'+watcher_tool+'</table>';
  var upper = 100;
  if (molch) upper += 30;
  
  x = event.clientX-8;
  y = (event.clientY > document.body.clientHeight-upper) ? document.body.clientHeight-upper : event.clientY;
  y += document.body.scrollTop;
  el.style.left = x + "px";
  el.style.top  = y + "px";
  jQuery(el).fadeIn(200);
  
 
  return false;
}

function silence(login)
{
var login2 = login;
  while (login2.indexOf(' ') >=0) login2 = login2.replace (' ', '%20');
  while (login2.indexOf('+') >=0) login2 = login2.replace ('+', '%2B');
  while (login2.indexOf('#') >=0) login2 = login2.replace ('#', '%23');
  while (login2.indexOf('?') >=0) login2 = login2.replace ('?', '%3F');
  el.innerHTML = '<table border=0 width=100 class=alt align=center><tr><td class=user align=center>'+login+'</td></tr><tr><td width=100><A HREF="javascript:ch_silence(\''+login2+'\',-1);ch_hmenu();" class=bga>Снять</A></td></tr><tr><td width=100><A HREF="javascript:ch_silence(\''+login2+'\',5);ch_hmenu();" class=bga>5 минут</A></td></tr><tr><td><A HREF="javascript:ch_silence(\''+login2+'\',30);ch_hmenu();" class=bga>30 минут</A></td></tr><tr><td><A HREF="javascript:ch_silence(\''+login2+'\',360);ch_hmenu();" class=bga>6 часов</A></td></tr></table>';
}

function ch_silence(login,d)
{
	top.frames["updater"].location = 'info.php?p='+login+'&do_w=mpb&ch_silence='+d+'&'+Math.random();
}

function ch_close_menu()
{
  el.style.visibility = "hidden";
  el.style.top="0px";
  top.frames['ch_buttons'].document.mess.message.focus();
}

function ch_hmenu()
{
  jQuery(el).fadeOut(300);
  top.frames['ch_buttons'].document.mess.message.focus();
}

function s_private (login)
{
	top.say_private(login,1);
}

function open_info()
{
	ch_hmenu();
	window.open('info.php?'+document.getElementById('nick').value,'','width=800,height=600,left=10,top=10,toolbar=no,scrollbars=yes,resizable=yes,status=no');
}

function set_ignore(login)
{
	ch_hmenu();
	top.frames['ch_list'].location = 'ch.php?ignore='+login+'&rand'+Math.random();
}