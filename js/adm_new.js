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
		
		
document.write('<div style="position:absolute; left:0px; top:0px; z-index: 2; width:300 ; height:400; visibility:hidden;" id="ml" class=loc>&nbsp;</div>');
var ml = document.getElementById('ml');
var i=0;
var $ = function(id){
if (document.getElementById(id)) return document.getElementById(id);
else 
{
var a=document.getElementsByName(id);
return a[0];
}
};

function editw()
{
	
	d.write('<a class=bga href=main.php>НАЗАД</a><input class=login type=button value="[  OK  ]" align=center style="width:100%" onclick="sbmt()"><form method=post action=main.php>');
	var _params = params.split('@');
	var np;
	d.write('<div class=return_win id=main></div>');
	d.write('<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>');
	d.write('<tr><td class=login align=center width=50% id=opts>СВОЙСТВА</td><td class=login width=50% align=center id=reqs>ТРЕБОВАНИЯ</td></tr>');
	d.write('<tr><td class=inv id=o width=50% valign=bottom></td><td class=inv id=t width=50% valign=bottom></td></tr>');
	d.write('</table><div class=return_win id=sec_params></div>');
	d.write('</form>');
	params_upd();
	main_inf();
}

function params_upd()
{
	var _params = params.split('@');
	var np,o,t,par;
	var i1=0,i2=0;
	var CLS='';
	o='<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	t='<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	var noss;
	var opts = 0,reqs = 0;
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
		if (np[0].substr(0,1)!='t')
		{
		if (parseInt(np[1]) && nos(np[0])!=np[0])
		{
		i1++;
		CLS = 'ym';
		par = np[1];
		if (np[0].substr(0,2)=='kb') {par += ' кб';CLS = 'green';}
		if (np[0].substr(0,2)=='mf') {par += '%';CLS = 'mf';}
		if (np[0].substr(0,2)=='ud') {par += '!';CLS = 'timef';}
		if (np[0].substr(0,2)=='hp') {par = '<font class=hp>+'+par+' HP</font>';}
		if (np[0].substr(0,2)=='ma') {par = '<font class=ma>+'+par+' MP</font>';}
		if (np[0].substr(0,1)=='s' && par>0 && np[0].length==2) {opts+=parseInt(par);par = '+'+par;CLS = 'blue';}
		o += 
		'<tr style="background:#'+((i1%2)?"EEEEEE":"DDDDDD")+'"><td width=10><img src=images/drop.gif onclick="par_set(\''+np[0]+'\',0);params_upd();" style="cursor:pointer"></td>';
		o += '<td width=50% class="'+CLS+'"><i>'+nos(np[0])+'</i>:</td><td> <b onclick="par_div_set(\''+np[0]+'\','+parseInt(np[1])+')" class=ym style="width:100%;cursor:pointer">'+par+'</b></td><td width=50> '+fast_up(np[0],np[1])+'</td></tr>';
		}
		}
		else
		{
		if (parseInt(np[1]) || np[0]=='tlevel')
			{
			i2++;
			par = np[1];
			if (np[0].substr(0,2)=='mf') par += '%';
			if (np[0].substr(0,2)=='ud') par += '!';
			if (np[0].substr(0,2)=='hp') par = '<font class=hp>'+par+'</font>';
			if (np[0].substr(0,2)=='ma') par = '<font class=ma>'+par+'</font>';
			if (np[0].substr(1,1)=='s') {reqs += parseInt(par);}
			t += 
			'<tr style="background:#'+((i2%2)?"EEEEEE":"DDDDDD")+'"><td width=10><img src=images/drop.gif onclick="par_set(\''+np[0]+'\',0);params_upd();" style="cursor:pointer"></td>';
			t += '<td width=50%><i>'+nos(np[0].substr(1,np[0].length-1))+'</i>:</td><td> <b onclick="par_div_set(\''+np[0]+'\','+parseInt(np[1])+')" class=ym style="width:100%;cursor:pointer">'+par+'</b></td><td width=50> '+fast_up(np[0],np[1])+'</td></tr>';
			}
		}
	}
	o+= '</table><hr><a href="javascript:new_opt()" class=bga>Новое свойство</a>';
	t += '</table><hr><a href="javascript:new_tb()" class=bga>Новое Требование</a>';
	$('o').innerHTML = o;
	$('t').innerHTML = t;
	$('opts').innerHTML = 'СВОЙСТВА (суммарно <b>'+opts+'</b> статов)';
	$('reqs').innerHTML = 'ТРЕБОВАНИЯ (суммарно <b>'+reqs+'</b> статов)';
	sec_inf();
}

function main_inf()
{
	var z;
	z = '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	z += '<tr><td align=center colspan=10>';
	z += '<center class=user onclick="ch_name()">'+par_val('name')+'</center>';
	z += '</td></tr>';
	z += '<tr><td width=50% id=ptype align=center>&nbsp;</td>';
	z += '<td align=center width=62><img src=images/weapons/'+par_val('image')+'.gif onclick="change_img()"></td>';
	z += '<td width=50% id=pstype align=center>&nbsp;</td></tr></table>';
	$('main').innerHTML = z;
	sh_types();
}

function sec_inf()
{
	var z;
	z = '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	z += '<tr><td align=Left width=50%>';
	z += '<img src="images/money.gif" width=10>Стоимость | <strong class=user onclick="ch_price()"> '+par_val('price')+' LN<br></strong><img src=images/signs/diler.gif  width=10>Стоимость |<strong class=user onclick="ch_price()"> '+par_val('dprice')+' y.e.<br></strong>';
	z += '</td><td width=50%>';
	z += 'МАКС. Долговечность <strong class=user onclick="ch_dur_qs()"> '+par_val('max_durability')+'<br></strong>Текущее кол-во в лавке <strong class=user onclick="ch_dur_qs()"> '+par_val('q_s')+' шт.<br></strong>';
	z += '</td></tr><tr><td align=center colspan=10 width=90%>';
	z += '<i onclick="ch_describe()">Описание: <b>'+par_val('describe')+'</b></i>';
	z += '</td></tr><tr><td align=center colspan=10 width=90%>';
	z += 'Название заряда: <b onclick="ch_arrows()">'+par_val('arrow_name')+'</b><br>';
	z += 'Цена заряда: <b onclick="ch_arrows()">'+par_val('arrow_price')+'</b><br>';
	z += 'Максимально зарядов: <b onclick="ch_arrows()">'+par_val('arrows')+'</b><br>';
	z += 'Радиус поражения: <b onclick="ch_arrows()">'+par_val('radius')+'</b><br>';
	z += 'Кол-во слотов: <b onclick="ch_arrows()">'+par_val('slots')+'</b><br>';
	z += '</td></tr><tr><td align=center colspan=10 width=90%>';
	z += '<a href="javascript:void(0)" onclick="all_pars()" class=bga>ПЕРЕЧЕНЬ ВСЕХ ПАРАМЕТРОВ ВРУЧНУЮ</a>';
	z += '</table>';
	$('sec_params').innerHTML = z;
}

function nos(id)
{
var a;
if ((a=par_names[array_pos(all_params,id)])!=undefined) return a; else return id;
}

function array_pos(array,elem)
{
	for (var i=0;i<array.length;i++)
	if (elem == array[i]) return i;
	return -1;
}

function par_val(par)
{
	var _params = params.split('@');
	var np;
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
		if (np[0]==par) return np[1];
	}
	return false;
}

function par_set(par,val)
{
	var _params = params.split('@');
	var np;
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
		if (np[0]==par)
		{
		np[1]=val;
		_params[i] = np.join('=');
		break;
		}
	}
	params = _params.join('@');
	params_upd();
	return true;
}

function init_main_layer()
{
	ml.style.visibility = 'visible';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = document.body.scrollTop+30;
	ml.innerHTML = 'РЕДАКТОР ВЕЩЕЙ [Aloneislands.Ru] <a href=javascript:disable_main_layer() class=timef><b>[УБРАТЬ]</b></a><hr>';
}
function disable_main_layer()
{
	ml.style.visibility = 'hidden';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 0;
	ml.innerHTML = '';
}
function par_div_set(par,val)
{
		init_main_layer();
		ml.innerHTML += '<b>'+nos(par)+'</b>: <form onsubmit="par_div_set_main(\''+par+'\');return false;"><input class=login type=text value='+val+' id="_'+par+'"><hr><input class=login type=submit value=[OK]></form>';
}

function par_div_set_main(par)
{
	par_set(par,$('_'+par).value);
	disable_main_layer();
}

function new_opt()
{
	init_main_layer();
	var slctd = '';
	var cls='items';
	slctd = '<select id=param class=items>';
	for (var i=0;i<all_params.length;i++)
	if (parseInt(par_val(all_params[i]))==0)
		{
			cls = 'DDDDDD';
			if (all_params[i].substr(0,1)=='s' && all_params[i].length==2) cls='DDFFDD';
			if (all_params[i].substr(0,1)=='m' && all_params[i].length==3) cls='FFDDDD';
			if (all_params[i]=='hp') cls = 'FFAAAA';
			if (all_params[i]=='ma') cls = 'AAAAFF';
			if (all_params[i]=='udmin') cls = 'FFFFFF';
			if (all_params[i]=='udmax') cls = 'FFFFFF';
			if (all_params[i]=='kb') cls = 'DDFFFF';
			if (all_params[i].substr(0,1)=='s' && all_params[i].substr(1,1)=='b') cls = 'DFDFDF';
			slctd += '<option value="'+all_params[i]+'" style=\'background:#'+cls+'\'>'+nos(all_params[i])+'</option>';
		}
	slctd += '</select>';
	ml.innerHTML += '<form onsubmit="add_opt();return false;">'+slctd+'<input class=login type=text value=0 id="_addopt"><hr><input class=login type=submit value=[OK]></form>';
}

function add_opt()
{
	par_set($('param').value,$('_addopt').value);
	disable_main_layer();
}

function new_tb()
{
	init_main_layer();
	var slctd = '';
	var cls='items';
	slctd = '<select id=param class=items>';
	for (var i=0;i<all_params.length;i++)
	if (parseInt(par_val('t'+all_params[i]))==0 && par_val('t'+all_params[i])!==false)
		{
			cls = 'DDDDDD';
			if (all_params[i].substr(0,1)=='s' && all_params[i].length==2) cls='DDFFDD';
			if (all_params[i].substr(0,1)=='s' && all_params[i].substr(0,1)=='b') cls = 'DFDFDF';
			slctd += '<option value="t'+all_params[i]+'" style=\'background:#'+cls+'\'>'+nos(all_params[i])+'</option>';
		}
	slctd += '</select>';
	ml.innerHTML += '<form onsubmit="add_opt();return false;">'+slctd+'<input class=login type=text value=0 id="_addopt"><hr><input class=login type=submit value=[OK]></form>';
}

function fast_up(par,val)
{
	val = parseInt(val);
	return '<img src=images/fixed_on.gif onclick="par_set(\''+par+'\','+(val*2)+')" ondblclick="par_set(\''+par+'\','+(val*3)+')"><img src=images/battle/down.gif onclick="par_set(\''+par+'\','+(val-1)+')" ondblclick="par_set(\''+par+'\','+(val-3)+')"><img src=images/battle/up.gif onclick="par_set(\''+par+'\','+(val+1)+')" ondblclick="par_set(\''+par+'\','+(val+3)+')"><img src=images/fixed_off.gif onclick="par_set(\''+par+'\','+(val/2)+')" ondblclick="par_set(\''+par+'\','+(val/3)+')">';
}

function change_img(par)
{
	init_main_layer();
	if (par) 
	{
		par_set('image',par.substr(0,par.length-4));
		main_inf();
	}
	var hg = '*';
	var wg = '62';
	var a = par_val('type');
	if (a=='orujie') hg = 91;
	if (a=='bronya') hg = 90;
	if (a=='naruchi') hg = 40;
	if (a=='perchatki') hg = 40;
	if (a=='shlem') hg = 65;
	if (a=='sapogi') hg = 60;
	if (a=='poyas') hg = 30;
	if (a=='ojerelie') hg = 35;
	if (a=='kolco') {hg = 31;wg=31;}
	if (a=='zakl') {hg = 30;wg=50;}
	if (a=='kam') {hg = 31;wg=31;}
	if (a=='fishing') {hg = 60;wg=60;}
	ml.innerHTML += '<form onsubmit="show_images();return false;"><img src=images/weapons/'+par_val('image')+'.gif><br>Длина:<input class=login type=text value='+wg+' id="_width" size=4>Высота:<input class=login type=text value='+hg+' id="_height" size=4><hr><input class=login type=submit value="[Показать все рисунки]"></form><a href=imgs.php?'+Math.random()+' target=updater class=bga>Обновить библиотеку рисунков.</a>';
}
function show_images()
{
	ml.innerHTML += '<img src=images/progress.gif>';
	xmlhttp.open('get', 'services/image_list.php?type=1&width='+$('_width').value+'&height='+$('_height').value+'&rand='+Math.random()+'');
	xmlhttp.onreadystatechange = ajax_response;
	xmlhttp.send(null);
}
function ajax_response()
{
	if(xmlhttp.readyState == 4)
			{
				if(xmlhttp.status == 200)
				{
					var response = xmlhttp.responseText;
					var z = '';
					if (response == 'none') ml.innerHTML += 'Рисунков не найдено.';
					else
					{
					response = response.split('|');
					for (var i=0;i<response.length;i++)
					if (response[i])
					{
						z+= '<img src=images/weapons/'+response[i]+' onclick="change_img(\''+response[i]+'\')" width=30> ';
					}
					ml.innerHTML = z;
					}
				}
			}
}

function ch_name()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_nameM();return false;"><input class=login type=text value="'+par_val('name')+'" id="wpname" size=30><hr><input class=login type=submit value=[OK]></form>';
}

function ch_nameM()
{
	par_set('name',$('wpname').value);
	disable_main_layer();
	main_inf();
}
function ch_weight()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_weightM();return false;"><input class=login type=text value="'+par_val('weight')+'" id="wpweight" size=10><hr><input class=login type=submit value=[OK]></form>';
}

function ch_weightM()
{
	par_set('weight',$('wpweight').value);
	disable_main_layer();
	main_inf();
}
function ch_price()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_priceM();return false;"><input class=login type=text value="'+par_val('price')+'" id="wpprice" size=30> LN<hr><input class=login type=text value="'+par_val('dprice')+'" id="wpdprice" size=30> y.e.<hr><input class=login type=submit value=[OK]></form>';
}

function ch_priceM()
{
	par_set('price',parseInt($('wpprice').value));
	par_set('dprice',parseInt($('wpdprice').value));
	disable_main_layer();
	sec_inf();
}

function ch_dur_qs()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_dqM();return false;">Долговечность <input class=login type=text value="'+par_val('max_durability')+'" id="max_durability" size=5><hr>Кол-во в лавке<input class=login type=text value="'+par_val('q_s')+'" id="q_s" size=6> шт.<hr><input class=login type=submit value=[OK]></form>';
}

function ch_dqM()
{
	par_set('max_durability',parseInt($('max_durability').value));
	par_set('q_s',parseInt($('q_s').value));
	disable_main_layer();
	sec_inf();
}

function ch_describe()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_descrM();return false;"><textarea class=newsm type=text id="wpdescr" cols=30 rows=6>'+par_val('describe')+'</textarea><hr><input class=login type=submit value=[OK]></form>';
}

function ch_descrM()
{
	par_set('describe',$('wpdescr').value);
	disable_main_layer();
	main_inf();
}

function ch_arrows()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_arrowsM();return false;">Название заряда <input class=login type=text value="'+par_val('arrow_name')+'" id="arrow_name" size=8><hr>Макс кол-во<input class=login type=text value="'+par_val('arrows')+'" id="arrows" size=6> шт.<hr>Цена одного заряда<input class=login type=text value="'+par_val('arrow_price')+'" id="arrow_price" size=6> LN.<hr>Радиус поражения<input class=login type=text value="'+par_val('radius')+'" id="radius" size=6> клеток<hr>Кол-во слотов<input class=login type=text value="'+par_val('slots')+'" id="slots" size=6> шт.<hr><input class=login type=submit value=[OK]></form>';
}

function ch_arrowsM()
{
	par_set('arrow_name',parseInt($('arrow_name').value));
	par_set('arrows',parseInt($('arrows').value));
	par_set('arrow_price',parseInt($('arrow_price').value));
	par_set('slots',parseInt($('slots').value));
	par_set('radius',parseInt($('radius').value));
	disable_main_layer();
	sec_inf();
}

function all_pars()
{
	init_main_layer();
	var _params = params.split('@');
	var np;
	var slctd = '';
	slctd = '<select id=params class=items onchange="any_par_show()">';
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
		slctd += '<option value="'+np[0]+'">'+np[0]+'</option>';
	}
	slctd += '</select>';
	ml.innerHTML += '<form onsubmit="any_par_set();return false;">'+slctd+'<input class=login type=text value=0 id="par"><hr><input class=login type=submit value=[OK]></form>';
}

function any_par_show()
{
	$('par').value = par_val($('params').value);
}

function any_par_set()
{
	par_set($('params').value,$('par').value);
	disable_main_layer();
}

function rest_hpma()
{
	par_set('index',$('restore_type').value+'$'+$('restore').value);
}

function spell_heal()
{
	par_set('p_type',$('p_type').value);
}
function sh_types()
{
	var a = '';
	a = '<select size="1" id="type" class="items" onchange="up_types()"><option value="shlem">Шлем</option><option value="ojerelie">Кулон</option><option value="orujie">Оружие</option><option value="poyas">Пояс</option><option value="zelie">Зелье/камень</option><option value="sapogi">Сапоги</option><option value="naruchi">Наручи</option><option value="perchatki">Перчатки</option><option value="kolco">Кольцо</option><option value="bronya">Броня</option><option value="napad">нападалка</option><option value="zakl">свиток</option><option value="kam">Зелье/Свиток</option><option value="fishing">Рыболовные снасти</option><option value="teleport">Телепорт</option><option value="rune">Руна</option></select>';
	if (par_val('type')=='kam')
	{
		var rest = par_val('index').split('$');
		var rest_sel='';
		if (rest[0]=='ma') rest_sel='SELECTED';
		rest = rest[1];
		a+='Восст.<input type=text class=login id=restore value="'+rest+'" onchange=rest_hpma()><select id=restore_type class=items onchange=rest_hpma()><option value=hp>HP</option><option value=ma '+rest_sel+'>MA</option></select>'
	}
	if (par_val('type')=='zakl')
	{
		a += '<select size="1" id="index" class="items" onchange="up_types()">';
		a+= '<option value=0>Аура использования:нет</option>';
		for(i=1;i<auras.length;i++)
		{
			a+= '<option value='+auras[i][0]+'>'+auras[i][1]+'</option>';
		}
		a += '</select>';
		a += '<br><select size="1" id="p_type" class="items" onchange="spell_heal()"><option value="0">Обычный свиток</option><option value="10">Лечит лёгкие травмы</option><option value="11">Лечит средние травмы</option><option value="12">Лечит тяжёлые травмы</option></select>';
	}
	a += '<hr><select size="1" id="where_buy" class="items"  onchange="up_types()"><option value="0">Лавка</option><option value="1">Дом Дилеров</option><option value="2">Нигде</option></select><select size="1" id="p_type" class="items" onchange="up_types()"> <option value="0">Это не инструмент для работы</option> <option  value="1">Им можно ловить рыбу</option> <option value="2">Им можно резать траву</option> <option value="3">Им можно рубить лес</option> <option value="4">Им можно снимать шкуру</option> <option value="5">Им можно шахтерить</option> <option value="7">Ресурс</option> <option value="10">Лечит лёгкую травму</option><option value="11">Лечит среднюю травму</option><option value="12">Лечит тяжёлую травму</option><option value="13">Телега для шахтёрства</option>';
	a += '<option value="14">Можно срезать шкуру</option>';
	a += '<option value="15">Закрытая нападалка</option>';
	a += '<option value="16">Боевая нападалка</option>';
	a += '</select>';
	$('ptype').innerHTML = a;
	a = '<select size="1" id="stype" class="items" onchange="up_types()"><option value="shle">Шлем</option><option value="kylo">Кулон</option><option value="mech">Меч</option><option value="noji">Нож</option><option value="shit">Щит</option><option value="topo">топор</option><option value="drob">Дробящее</option><option value="poya">Пояс</option><option value="zeli">Зелье/камень</option><option value="sapo">Сапоги</option><option value="naru">Наручи</option><option value="perc">Перчатки</option><option value="kolc">Кольцо</option><option value="bron">Броня</option><option value="napadk">Нападалка[Класс]</option><option value="napadt">Нападалка[Такт]</option><option value="zakl">свиток</option><option value="kam">Зелье/свиток</option><option value="fishing">Рыболовные снасти</option><option value="book">Книга</option><option value="teleport">Телепорт</option><option value="instrument">Инструмент</option><option value="rune">Руна</option></select><hr>Вес : <b onclick="ch_weight()"> '+par_val('weight')+' </b>';
	$('pstype').innerHTML = a;

	var objtype = $('type');
	var type = par_val('type');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	
	if ($('index'))
	{
	var objtype = $('index');
	var type = par_val('index');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	}
	
	if (par_val('type')=='zakl' && $('p_type'))
	{
	var objtype = $('index');
	var type = par_val('index');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	}
	
	objtype = $('stype');
	type = par_val('stype');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	
	objtype = $('p_type');
	type = par_val('p_type');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	
	objtype = $('where_buy');
	type = par_val('where_buy');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
}

function up_types()
{
	par_set('type',$('type').value);
	par_set('p_type',$('p_type').value);
	if ($('index'))
	 par_set('index',$('index').value);
	par_set('stype',$('stype').value);
	par_set('where_buy',$('where_buy').value);
}

function sbmt()
{
ml.innerHTML='<img src=images/progress.gif><form method=post name=sbmtform><textarea cols=0 rows=0 name=params style="visibility:hidden">'+params+'</textarea></form>';
d.sbmtform.submit();
}
