document.write('<div style="position:absolute; left:0px; top:0px; z-index: 2; width:300 ; height:500; visibility:hidden;" id="ml" class=loc>&nbsp;</div>');
var ml = document.getElementById('ml');
var i=0;
var $ = function(id){
if (document.getElementById(id)) return document.getElementById(id);
else {var v = document.getElementsByName(id); return v[0];};
};
var iml;
var TT = 1;

function change_img()
{
	init_main_layer();
	ml.innerHTML += '<img src=images/persons/'+image+'.gif><hr><input class=login type=button value="[Показать все рисунки]" onclick="show_images(3);"><a href=imgs.php?'+Math.random()+' target=updater class=bga>Обновить библиотеку рисунков.</a><div id=images></div>';
}

function Wear(id,minl,maxl)
{
	init_main_layer();
	var t = '';
	t += '<select class=real name=class>';
	t += '<option value=1>Критовик</option>';
	t += '<option value=2>Уворотчик</option>';
	t += '<option value=3>Танк</option>';
	t += '<option value=4>Маг-Крит</option>';
	t += '<option value=5>Маг-Уворот</option>';
	t += '<option value=6>Маг-Танк</option>';
	t += '</select>';
	var image = 'slots/pob1';
	ml.innerHTML += '<b class=user>'+id+'</b><form name=wear action=main.php?wear=1 method=post><center class=inv><img src=images/weapons/'+image+'.gif id=img></center><center class=but2><select size="1" id="type" class="items" name=type><option value="shlem">Шлем</option><option value="ojerelie">Кулон</option><option value="orujie">Оружие</option><option value="poyas">Пояс</option><option value="zelie">Зелье/камень</option><option value="sapogi">Сапоги</option><option value="naruchi">Наручи</option><option value="perchatki">Перчатки</option><option value="kolco">Кольцо</option><option value="bronya">Броня</option></select><select name="stype" class="items"><option value="shle">Шлем</option><option value="kylo">Кулон</option><option value="mech">Меч</option><option value="noji">Нож</option><option value="shit">Щит</option><option value="topo">топор</option><option value="drob">Дробящее</option><option value="poya">Пояс</option><option value="zeli">Зелье/камень</option><option value="sapo">Сапоги</option><option value="naru">Наручи</option><option value="perc">Перчатки</option><option value="kolc">Кольцо</option><option value="bron">Броня</option><option value="book">Книга</option></select></center><center class=but>'+t+'</center><center class=but><input type=text name=name value="Название" class=but2></center><center class=but>Мощь<input type=text name=power value=1.5 class=but2 size=3></center>'+
	'<center class=but>Требование<input type=text name=tpower value=1 class=but2 size=3></center>'+
	'<center class=but>Уровни<br><input type=text name=minlvl value='+minl+' class=but2 size=3>-<input type=text name=maxlvl value='+maxl+' class=but2 size=3></center><input type=hidden name=image id=image value="'+image+'"><input type=hidden name=user value="'+id+'"><hr><input class=login type=button value="[Показать все рисунки]" onclick="show_images(1);"><center class=but><input class=login type=submit value="Надеть" style="width:90%"></center></form><a href=imgs.php?'+Math.random()+' target=updater class=bga>Обновить библиотеку рисунков.</a><div id=images style="overflow-y:scroll;height:200px;width:100%"></div>';
}
function UNWear(id,minl,maxl)
{
	init_main_layer();
	
	ml.innerHTML += '<b class=user>'+id+'</b><form name=wear action=main.php?unwear=1 method=post><center class=but2><select name="stype" class="items"><option value="shle">Шлем</option><option value="kylo">Кулон</option><option value="mech">Меч</option><option value="noji">Нож</option><option value="shit">Щит</option><option value="topo">топор</option><option value="drob">Дробящее</option><option value="poya">Пояс</option><option value="zeli">Зелье/камень</option><option value="sapo">Сапоги</option><option value="naru">Наручи</option><option value="perc">Перчатки</option><option value="kolc">Кольцо</option><option value="bron">Броня</option><option value="book">Книга</option></select></center><center class=but>Уровни<br><input type=text name=minlvl value='+minl+' class=but2 size=3>-<input type=text name=maxlvl value='+maxl+' class=but2 size=3></center><input type=hidden name=user value="'+id+'"><center class=but><input class=login type=submit value="Снять" style="width:90%"></center></form>';
}
function show_images(type)
{
	TT = type;
	var hg,wg=62,a;
	if(type==1)
	{
		a = document.wear.type.value;
		if (a=='orujie') hg = 91;
		if (a=='bronya') hg = 90;
		if (a=='naruchi') hg = 40;
		if (a=='perchatki') hg = 40;
		if (a=='shlem') hg = 65;
		if (a=='sapogi') hg = 60;
		if (a=='poyas') hg = 30;
		if (a=='ojerelie') hg = 35;
		if (a=='kolco') {hg = 31;wg=31;}
		xmlhttp.open('get', 'services/image_list.php?type='+type+'&rand='+Math.random()+'&width='+wg+'&height='+hg);
	}
	else
		xmlhttp.open('get', 'services/image_list.php?type='+type+'&rand='+Math.random());
	xmlhttp.onreadystatechange = ajax_response;
	xmlhttp.send(null);
	iml = document.getElementById('images');
	iml.innerHTML = '<img src=images/progress.gif>';
}

function ajax_response()
{
	if(xmlhttp.readyState == 4)
			{
				if(xmlhttp.status == 200)
				{
					var response = xmlhttp.responseText;
					var z = '';
					if (response == 'none') iml.innerHTML += 'Рисунков не найдено.';
					else
					{
					response = response.split('|');
					for (var i=0;i<response.length;i++)
					if (response[i])
					{
						response[i] = response[i].substr(0,response[i].length-4);
						if(TT==3)
							z+= '<img src=images/persons/'+response[i]+'.gif onclick="set_img(\''+response[i]+'\')" width=50> ';
						else
							z+= '<img src=images/weapons/'+response[i]+'.gif onclick="set_img(\''+response[i]+'\')" width=50> ';
					}
					iml.innerHTML = z;
					}
				}
			}
}

function set_img(i)
{
	image = i;
	if(TT==3) change_img();
	d.getElementById('image').value = i;
	if(TT==3) 
		$('img').src = 'images/persons/'+image+'.gif';
	else
		$('img').src = 'images/weapons/'+image+'.gif';
	iml.innerHTML = '';
}

function init_main_layer()
{
	ml.style.visibility = 'visible';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = document.body.scrollTop+30;
	ml.innerHTML = 'РЕДАКТОР [Aloneislands.Ru] <a href=javascript:disable_main_layer() class=timef><b>[УБРАТЬ]</b></a><hr>';
}
function disable_main_layer()
{
	ml.style.visibility = 'hidden';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 0;
	ml.innerHTML = '';
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
		
		
function autoconfig()
{
	var t = '';
	t += '<select onchange="set_cfg()" id=cfgval class=real name=class>';
	t += '<option value=0 SELECTED>Ручная настройка</option>';
	t += '<option value=1>Силовик</option>';
	t += '<option value=2>Критовик</option>';
	t += '<option value=3>Уворотчик</option>';
	t += '<option value=4>Танк</option>';
	t += '<option value=5>Дамагер</option>';
	t += '</select>';
	document.write(t);
}		

function set_cfg()
{
var sw = document.getElementById('cfgval').value;
if (sw==1)
{
			setv('s1',6);setv('mf1',6);
			setv('s2',1);setv('mf2',2);
			setv('s3',2);setv('mf3',8);
			setv('s4',7);setv('mf4',8);
			setv('s5',0);setv('mf5',1);
			setv('s6',0);setv('kb',7);
			setv('ud',4);
}
if (sw==2)
{
			setv('s1',2);setv('mf1',12);
			setv('s2',1);setv('mf2',1);
			setv('s3',6);setv('mf3',8);
			setv('s4',5);setv('mf4',8);
			setv('s5',0);setv('mf5',5);
			setv('s6',0);setv('kb',5);
			setv('ud',4);
}
if (sw==3)
{
			setv('s1',2);setv('mf1',1);
			setv('s2',6);setv('mf2',12);
			setv('s3',1);setv('mf3',8);
			setv('s4',4);setv('mf4',8);
			setv('s5',0);setv('mf5',1);
			setv('s6',0);setv('kb',4);
			setv('ud',4);
}
if (sw==4)
{
			setv('s1',4);setv('mf1',1);
			setv('s2',4);setv('mf2',1);
			setv('s3',4);setv('mf3',14);
			setv('s4',9);setv('mf4',14);
			setv('s5',0);setv('mf5',1);
			setv('s6',0);setv('kb',12);
			setv('ud',4);
}
if (sw==5)
{
			setv('s1',3);setv('mf1',8);
			setv('s2',3);setv('mf2',8);
			setv('s3',3);setv('mf3',8);
			setv('s4',4);setv('mf4',8);
			setv('s5',0);setv('mf5',1);
			setv('s6',0);setv('kb',8);
			setv('ud',8);
}
}

function setv(name,val)
{
	var v = document.getElementsByName(name);
	for (i=0;i<v.length;i++)
	v[i].value = val;
}

function Attack(name,lvlmin,lvlmax)
{
	init_main_layer();
	var sel='<select name=lvl class=real>';
	for (var i=lvlmin;i<=lvlmax;i++)
	{
		sel += '<option value='+i+'>'+i+'</option>';
	}
	sel+='</select>';
	document.getElementById('ml').innerHTML += 'Напасть на '+name+'?<form method=post action="main.php?attack=1"><input type=hidden name=name value=\''+name+'\'>'+sel+'<input type=submit value="Напасть" class=login></form>';
}

function skin_ch(a)
{
	var b = parseInt(document.getElementById('sel_'+a).value);
	if(b)
		document.getElementById(a).innerHTML = '<img src=images/weapons/skin/skin'+b+'.gif width=50>';
}

