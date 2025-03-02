var playing = -1;
var purl = '';

function listen(url,a)
{
a = url;
url = 'http://'+document.domain+'/'+url;
soundManager.stopAll();

//alert(url);
if (playing != -1)
{
	playing = -1;
}

if (!soundManager.getSoundById(a))
{
	soundManager.createSound({
	 id: a, // required
	 url: url, // required
	 volume: 100,
	 autoPlay: true
	});
}

purl = url;
playing = a;
//$('#'+playing+'').html('<a href="javascript:void(0);" class=timef onmousedown="listen(0,-1)">Остановить!</a>');
return soundManager.play(a);
}

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
document.write('<a href="http://top.mail.ru/jump?from=1207359"'+
' target=_blank style="display:none;"><img src="http://dc.c6.b2.a1.top.list.ru/counter'+
'?id=1207359;t=109;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru"'+' border=0 height=18 width=88 style="display:none;"></a>');
}


function getCnt()
{
	var q = document.getElementById('q').value;
	formSend(q);
}

function submitContent(str)
{
	document.getElementById('qu').value = str;
	document.mform.submit();
}

function prgs(a,b)
{
	document.getElementById('report').innerHTML += a+" "+b+"<br>";
}