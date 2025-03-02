var d=document;
var interval = -1;
var sw = screen.width*0.25;
var $ = function(id){
	return d.getElementById(id);
};
var ready = top.ready;
var showed = 0;

var ctip = top.ctip;
d.write('<div style="position:absolute; left:-500px; top:100px; z-index: 65535; width:500 ; height:200; visibility:visible; display:block;" id="news" bgcolor=#000000 class=news> <table border=0 width=500 height=200> <tr><td valign=top class=newsm id=tipp height=80% align=center>&nbsp;</td><td width=20 onclick="show_div()" valign=top style="cursor:pointer;color:#FFFFFF">[X]</td></tr><tr><td id=setup>&nbsp;</td></tr> </table> </div>');
if (ctip)
change_tips_opt(0);

function change_tips_opt(type)
{
var _duration = top._duration;
var game_tips = top.game_tips;
var laws_tips = top.laws_tips;
if (type==1)
{
	_duration*=2;
	if (_duration > 1800) _duration = 60;
}else
if (type==2)
{
	_duration = (_duration) ? 0 : 60;
}else
if (type==3)
{
	game_tips = !game_tips;
}else
if (type==4)
{
	laws_tips = !laws_tips;
}

	$('setup').innerHTML = '<a href="javascript:change_tips_opt(1)" class=timef>Скорость показа подсказок <b>'+_duration+' сек.</b></a><br>';
	if (_duration) 
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(2)" class=timef>Не показывать подсказки</a><br>';
	else
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(2)" class=timef>Показывать подсказки</a><br>';
	if (game_tips)
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(3)" class=timef>Не показывать подсказки по игре</a> | ';
	else 
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(3)" class=timef>Показывать подсказки по игре</a> | ';
	
	if (laws_tips)
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(4)" class=timef>Не показывать подсказки по закону</a> <hr> ';
	else 
	$('setup').innerHTML += '<a href="javascript:change_tips_opt(4)" class=timef>Показывать подсказки по закону</a> <hr> ';
	$('setup').innerHTML += '<a href="javascript:show_tip(-2)" class=timef>Больше никогда не показывать подсказки</a><br>';
top._duration = _duration;
top.game_tips = game_tips;
top.laws_tips = laws_tips;	
}

function show_tip(step)
{
var _duration = top._duration;
var game_tips = top.game_tips;
var laws_tips = top.laws_tips;
if (step!=-2)
{
	if (!step) step = 0;
	ctip+=step;
	ctip++;
	top.frames['updater'].location = 'services/_tip_view.php?ctip='+ctip+'&gtt='+game_tips+'&ltt='+laws_tips+'&rand='+Math.random();
	if (_duration) interval = setTimeout("show_tip(0)",_duration*1000);
}
else
{
	show_div();
	top.frames['updater'].location = 'services/_tip_view.php?ctip=-1'+'&rand='+Math.random();
	_duration = 0;
}
top._duration = _duration;
top.ctip = ctip;
}
function init_tip(text)
{
var _duration = top._duration;
var game_tips = top.game_tips;
var laws_tips = top.laws_tips;
	if (top.ready)
	{
	$('tipp').innerHTML = text;
	show_div();
	}
	var z = setTimeout("top.aready()",_duration*1000);
}

function show_div()
{
	top.ready = 0;
	var n = $('news');
	if (!showed) 
	{
		n.style.left = sw+'px';
		fadeOpacity('news', 'oR1');
		showed = 1;
	}
	else 
	{
		fadeOpacity.back('news');
		showed = 0;
		n.style.left = -500;
	}
}

$ = jQuery;