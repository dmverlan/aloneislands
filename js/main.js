d = document;
s = screen;
d.title = 'Alone Islands - Вселенная в твоих руках!';
var user_cl = 0;
var pass_cl = 0;
var GLOB_ERROR = '';

function co1() {

document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank style='display:none;'><img src='http://counter.yadro.ru/hit?t26.5;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число посетителей за сегодня' "+
" width=88 height=15 style='display:none;'></a><br>");
}

function co2() {
var a='';
js=10;
a+=';j='+navigator.javaEnabled();
js=11;
s=screen;a+=';s='+s.width+'*'+s.height;
a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);
js=12;
js=13;
d.write('<a href="http://top.mail.ru/jump?from=1207359"'+
' target=_blank style="display:none;"><img src="http://dc.c6.b2.a1.top.list.ru/counter'+
'?id=1207359;t=109;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru"'+' border=0 height=18 width=88 style="display:none;"></a>');
}

function show_message(message)
{
document.getElementById('legend2').innerHTML = message;
jQuery('#legend2').slideDown(300);
}

function index(error,z,time)
{
GLOB_ERROR = error;
swc = s.width-50;
sw = s.width/2 - 50;
sh = s.height/2;
d.write('<center style="position:absolute;z-index:2;top:0;left:0;width:100%;height:100%;"><table width=100% height=100% border=0 cellspacing="0" cellpadding="0"><tr><td valign=bottom><table border="0" width="100%" cellspacing="0" cellpadding="0" class=footer height=40> <tr> <td><script>co2();</script></td><td class="mfooter" style="cursor:pointer"><a class="boxed" href="reg.php" title="Регистрация на AloneIslands" rel="{handler:\'iframe\',size:{x:500,y:450}}" class=Main style="color: #000000">Регистрация</a></td> <td width="11"> <img border="0" src="images/index/bullet3.png" width="8" height="8"></td> <td class=mfooter><a class="boxed" href="forum/" title="Форум" rel="{handler:\'iframe\',size:{x:'+sw*2+',y:'+sh*1.2+'}}" class=Main style="color: #000000">Форум</a></td> <td width="11"> <img border="0" src="images/index/bullet3.png" width="8" height="8"></td> <td class="mfooter" style="cursor:pointer" onclick="this.style.behavior=\'url(#default#homepage)\'; this.setHomePage(\'http://www.AloneIslands.Ru\');"><a href=# class=Main style="color: #000000">Сделать Стартовой</a></td> <td width="11"> <img border="0" src="images/index/bullet3.png" width="8" height="8"></td> <td class=mfooter><a class="boxed" href="remind.php" title="Форум" rel="{handler:\'iframe\',size:{x:'+sw*2+',y:200}}" class=Main style="color: #000000">Забыли пароль?</a></td><td align=right><script>co1();</script></td> </tr>  <tr> <td width="100%" align="center" class="but" colspan="12" style="color: #AAAAAA"> © Copyright 2008-2009, Alone Islands Ltd. Все права защищены.</td></tr></table></td></tr></table></center>');
d.write('<form method="post" action="game.php" name="login_form"><div style="position:absolute;top:'+sh/3+'px;left:'+(s.width/2-155)+'px;width:301px;height:174px;display:none;z-index:3;background-image:url(../images/login/index.png);text-align:center;" id=loginbox ><center><input type=image src="images/emp.gif"><table border="0" cellspacing="0" cellpadding="0" style="height:170px;width:80%;"> <tr> <td class=wTitle align=center>Войти в игру.</td> </tr><tr><td id=legend2 class=but style="display:none;overflow:hidden;" align=center>&nbsp;</td></tr> <tr> <td class=sTitle>Никнейм<br><input class=login2 type=text value="" name=user id=user style="width:100%;"><input type=submit style="display:none"></td> </tr> <tr> <td class=sTitle>Пароль<br><input class=login2 type=password value="" name=pass id=pass style="width:100%;"><input type=submit style="display:none"></td> </tr> <tr><td align=right onclick="document.login_form.submit();" class=wTitle style="cursor:pointer;" valign=center>Войти<input type=image src=images/login/enter.png title="Вход"></td></tr></table></div></div><div class=news style="position:absolute;top:0px;left:0px;width:100%;height:100%;display:none;z-index:1;opacity:0.2;filter:alpha(opacity=20);" id=bgblack></div><input type=submit style="display:none"></form>'); 
s_des();

jQuery("body").keypress(function (e) {
if (e.which == 13) document.login_form.submit();
});
}

function handleResponse(){
var dd = document.getElementById('maina');
if(xmlhttp.readyState == 4)
			{
				if(xmlhttp.status == 200)
				{
					var ww = screen.width;
					if (ww>800) ww=20;
					else ww=10;
					var response = xmlhttp.responseText;
					if (dd)
					dd.innerHTML = response;
					document.getElementById('login').align = "right";
					document.getElementById('legend').valign = "top";
					document.getElementById('news').valign = "top";
					/*document.getElementById('login').innerHTML += '<form method=post action=game.php name=login_form><input class=login type=text value="Логин" name=user size='+ww+' id=user><input class=login type=password value="Пароль" name=pass size='+ww+' id=pass><input type=image width=0 height=0 src=images/emp.gif></form>';*/
					loginInit();
/*					document.getElementById('user').onclick = function(){if (!user_cl)document.getElementById('user').value='';user_cl++}
					document.getElementById('pass').onclick = function(){if (!pass_cl)document.getElementById('pass').value='';pass_cl++}*/
					if (GLOB_ERROR=="block") show_message("<b class=blue>Персонаж Заблокирован.</b>"); 
					if (GLOB_ERROR=="login") show_message("<b class=hp>Неверный логин или пароль.</b>");
					//document.getElementById('news').innerHTML = document.getElementById('mnews').innerHTML;
					i//f (ww==10) document.getElementById('mlegend').innerHTML = document.getElementById('mlegend').innerHTML.substr(0,60)+'...';
					//document.getElementById('legend').innerHTML += document.getElementById('mlegend').innerHTML;
				}
			}
}


var xmlhttp;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
			try
			{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (E)
				{
					xmlhttp = false;
				}
			}
			@else xmlhttp = false;
		@end @*/
		if(!xmlhttp && typeof XMLHttpRequest != 'undefined')
		{
			try
			{
				xmlhttp = new XMLHttpRequest();
			}
			catch (e)
			{
				xmlhttp = false;
			}
		}

function s_des(){
			var ww = screen.width;
			if (ww<640) ww=640;
			if (ww>1280) ww=1280;
			xmlhttp.open('get', 'mpage/'+(ww-25)+'/'+(ww-25)+'.html');
			xmlhttp.onreadystatechange = handleResponse;
			xmlhttp.send(null);
}
// This code was written by Tyler Akins and has been placed in the
// public domain.  It would be nice if you left this header intact.
// Base64 code from Tyler Akins -- http://rumkin.com

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function encode64(input) {
	var output = "";
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;
	
	do {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);
		
		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;
		
		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}
		
		output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
		keyStr.charAt(enc3) + keyStr.charAt(enc4);
	} while (i < input.length);
	
	return output;
}

function decode64(input) {
	var output = "";
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;
	
	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
	
	do {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));
		
		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;
		
		output = output + String.fromCharCode(chr1);
		
		if (enc3 != 64) {
			output = output + String.fromCharCode(chr2);
		}
		if (enc4 != 64) {
			output = output + String.fromCharCode(chr3);
		}
	} while (i < input.length);
	
	return output;
}

function fixpng(img) {
	var arVersion = navigator.appVersion.split("MSIE")
	var version = parseFloat(arVersion[1])
	
	if ((version >= 5.5) && (document.body.filters)) 
	{
		var imgName = img.src.toUpperCase()
		if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
		{
			var imgID = (img.id) ? "id='" + img.id + "' " : ""
			var imgClass = (img.className) ? "class='" + img.className + "' " : ""
			var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
			var imgStyle = "display:inline-block;" + img.style.cssText 
			if (img.align == "left") imgStyle = "float:left;" + imgStyle
				if (img.align == "right") imgStyle = "float:right;" + imgStyle
					if (img.parentElement.href) imgStyle = "cursor:pointer;" + imgStyle
						var strNewHTML = "<span " + imgID + imgClass + imgTitle
						+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
						+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
						+ "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>" 
						img.outerHTML = strNewHTML
						i = i-1
		}
	}
}


var move_interv = -1;
var move_timeout = 0;

function show_news()
{
	var n = document.getElementById('news');
	if (parseInt(n.style.left)<0) 
	{
		move_timeout = 0;
		move_interv = setTimeout("move_it('news',0)",move_timeout);
	}
	else 
	{
		move_timeout = 0;
		move_interv = setTimeout("move_it('news',1)",move_timeout);
	}
}

function move_it(g,b)
{
n = document.getElementById(g);
if (!n) return;
var left = parseInt(n.style.left);
if (!b)
{
if (left<0)
{
left += 20;
move_timeout += 1;
move_interv = setTimeout("move_it('"+g+"',0)",move_timeout);
}else n.className = 'newsm';
}else
{
if (left>-380)
{
left -= 20;
move_timeout += 1;
move_interv = setTimeout("move_it('"+g+"',1)",move_timeout);
}else n.className = 'news';
}
n.style.left = left+'px';
}

function sbm_login()
{
	if (!user_cl || !document.getElementById('pass').value || !document.getElementById('user').value)
	alert('Введите корректные данные для входа в игру.'); else document.login_form.submit();
}

function loginInit()
{
	setTimeout("jQuery('#loginbox').slideDown(400);",300);
	setTimeout("jQuery('#bgblack').fadeIn(400);",400);
}