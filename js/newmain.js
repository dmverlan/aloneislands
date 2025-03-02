d = document;
s = screen;
d.title = 'Alone Islands - Вселенная в твоих руках!';
var user_cl = 0;
var pass_cl = 0;
var GLOB_ERROR = '';
var SoundsVol = 50;
var SoundsOn = 1;
var SL;
var _played=0;

function co1() {  d.write("<a href='http://www.liveinternet.ru/click' "+ "target=_blank style='display:none;'><img src='http://counter.yadro.ru/hit?t26.5;r"+ escape(document.referrer)+((typeof(screen)=="undefined")?"": ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth? screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+ ";"+Math.random()+ "' alt='' title='LiveInternet: показано число посетителей за сегодня' "+ " width=88 height=15 style='display:none;'></a><br>"); }  function co2() { var a=''; js=10; a+=';j='+navigator.javaEnabled(); js=11; s=screen;a+=';s='+s.width+'*'+s.height; a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth); js=12; js=13; d.write('<a href="http://top.mail.ru/jump?from=1207359"'+ ' target=_blank style="display:none;"><img src="http://dc.c6.b2.a1.top.list.ru/counter'+ '?id=1207359;t=109;js='+js+a+';rand='+Math.random()+ '" alt="Рейтинг@Mail.ru"'+' border=0 height=18 width=88 style="display:none;"></a>'); } function co3() {d.write('<img src="http://yandeg.ru/count/cnt.php?id=107052" style="display:none;">'); }


function index(terror)
{
	var sw = s.width;
	if(sw<800) sw = 800;
	if(sw>1280) sw = 1280;
	if(sw!=800 && sw!=1024 && sw!=1152 && sw!=1280) sw = 1024;
	var error = 'Войти в игру:';
	if (terror=='login') error = 'Неверный логин или пароль.';
	if (terror=='block') error = 'Персонаж заблокирован.';

	var links = '<a class="boxed" href="reg.php" title="Регистрация на AloneIslands" rel="{handler:\'iframe\',size:{x:500,y:450}}" class=Main style="color: #000000">Регистрация</a> | <a class="boxed" href="forum/" title="Форум" rel="{handler:\'iframe\',size:{x:'+(sw-150)+',y:'+(s.height*0.6)+'}}" class=Main style="color: #000000">Форум</a> | <a class="boxed" href="remind.php" title="Форум" rel="{handler:\'iframe\',size:{x:'+(sw-150)+',y:200}}" class=Main style="color: #000000">Забыли пароль?</a>';

	d.write('<body bgcolor="#CFCFCF" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" > <img border="0" src="index/f.jpg" width=100% height=100%><div id=SoundLayer></div> <div style="z-index:1;position:absolute;width:100%;height:100%;top:0;left:0;background-image: url(\'index/'+sw+'/m.png\'); background-position: left bottom; background-repeat: no-repeat;"></div> <div style="z-index:1;position:absolute;width:100%;height:100%;top:0;left:0;background-image: url(\'index/'+sw+'/d.png\'); background-position: right bottom; background-repeat: no-repeat;"></div> <center style="z-index:3;position:absolute;width:100%;height:100%;top:0;left:0;"> <img border="0" src="index/'+sw+'/logo2.png"> <div style="position:relative;top:-19%;left:-1%;"> <form action=game.php method=post name=auth><table border="0" width="20%" cellsplacing=0 cellspadding=0><tr><td class=indexFont align=center>'+error+'</td> 	</tr> 	<tr> 		<td class=indexFont align=center>Логин<br><input class=loginBox type=text name=user></td> 	</tr> 	<tr> 		<td class=indexFont align=center>Пароль<br><input class=loginBox type=password name=pass></td> 	</tr> </table><input type=image src=images/emp.gif></form> <center><img border="0" src="index/'+sw+'/v.png" style="cursor:pointer;" onclick="document.auth.submit();"></center> </div> </center> <div style="z-index:4;position:absolute;width:100%;height:27px;top:90%;left:0;background-image: url(\'index/r.png\'); background-position:center bottom;background-repeat:no-repeat;"> <table height=100% cellpadding=0 cellspacing=0 width=100%><tr><td valign=middle align=center>'+links+'</td></tr></table><center><i style="color:#999999">© Copyright 2006-2009, Alone Islands Ltd. Все права защищены.</i></center></div>');

	co1();
	co2();
	co3();

	soundManager.onload = PauseSound;
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

function rand(a1,a2)
{
	return Math.floor(a1+(Math.random()*10000)%(a2-a1+1));
}


function PlaySound()
{
	_played = 1;
	SL = d.getElementById("SoundLayer");
	SL.innerHTML = '<div style="color:#AAAAAA;cursor:pointer;z-index:6;position:absolute;top:0;left:0;" onclick="PauseSound()"><img src=images/icon_eq.gif><i>Music ON</i></div>';
	Sound("title"+rand(1,3),1,0);
}

function PauseSound()
{
	SL = d.getElementById("SoundLayer");
	SL.innerHTML = '<div style="color:#AAAAAA;cursor:pointer;z-index:6;position:absolute;top:0;left:0;" onclick="ResumeSound()"><img src=images/paused.gif><i>Music OFF</i></div>';
	soundManager.pauseAll();
}

function ResumeSound()
{
	if(_played==0)
		PlaySound();
	else
	{
		soundManager.resumeAll();
		SL.innerHTML = '<div style="color:#AAAAAA;cursor:pointer;z-index:6;position:absolute;top:0;left:0;" onclick="PauseSound()"><img src=images/icon_eq.gif><i>Music ON</i></div>';
	}
}

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
}
	return soundManager.play(a);
}

function Enter(login,pass)
{
	createCookie("uid",login);
	createCookie("hashcode",pass);
	location = 'game.php';
}