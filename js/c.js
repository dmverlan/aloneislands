document.write("<div style='position:absolute;z-index:0;top:-10px;left:-100px;'><a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='http://counter.yadro.ru/hit?t26.5;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число посетителей за сегодня' "+
" width=88 height=15></a><br>");
var js=10;
var a=';j='+navigator.javaEnabled();
js=11;
var s=screen;a+=';s='+s.width+'*'+s.height;
a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);
js=12;
js=13;
document.write('<a href="http://top.mail.ru/jump?from=1207359"'+
' target=_blank><img src="http://dc.c6.b2.a1.top.list.ru/counter'+
'?id=1207359;t=109;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru" height=18 width=88></a></div>');
