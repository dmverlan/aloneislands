document.write('<div style="position:absolute; left:0px; top:0px; z-index: 2; width:300 ; height:300; visibility:hidden;" id="ml" class=loc>&nbsp;</div>');
var ml = document.getElementById('ml');
var i=0;
var $ = function(id){
if (document.getElementById(id)) return document.getElementById(id);
else return document.getElementsByName(id);
};

function editw()
{
	
	d.write('<a class=bga href=main.php?c=2>НАЗАД</a><input class=login type=button value="[  OK  ]" align=center style="width:100%" onclick="sbmt()"><form method=post action=main.php>');
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
	var _params = vparams.split('@');
	var np,o,t,par;
	var i1=0,i2=0,chckd;
	var CLS='';
	o='<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	t='<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	var noss;
	var opts = 0,reqs = 0;
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
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
		if (np[0].substr(0,1)=='s' && parseInt(par)>0 && np[0].length==2) {opts+=parseInt(par);par = '+'+par;CLS = 'blue';}
		o += 
		'<tr style="background:#'+((i1%2)?"EEEEEE":"DDDDDD")+'"><td width=10><img src=images/drop.gif onclick="par_set2(\''+np[0]+'\',0);params_upd();" style="cursor:pointer"></td>';
		if (np[1].substr(np[1].length-1,1)=='%') chckd='CHECKED'; else chckd = '';
		o += '<td width=50% class="'+CLS+'"><i>'+nos(np[0])+'</i>:</td><td> <b onclick="par_div_set(\''+np[0]+'\','+parseInt(np[1])+')" class=ym style="width:100%;cursor:pointer">'+par+'</b></td><td width=50> '+fast_up2(np[0],np[1])+'</td><td width=10><input type=checkbox id="'+np[0]+'" value=1 onclick="perc_set(\''+np[0]+'\')" '+chckd+'></td></tr>';
		}
	}
_params = params.split('@');
	for (i=0;i<=_params.length;i++)
	if (_params[i])
	{
		np = _params[i].split('=');
		if (np[0]=='tlevel' || np[0]=='ts6' || np[0]=='tm1' || np[0]=='tm2')
		{
		if (parseInt(np[1]) || np[0]=='tlevel')
			{
			i2++;
			par = np[1];
			if (np[0].substr(1,1)=='s') {reqs += parseInt(par);}
			t += 
			'<tr style="background:#'+((i2%2)?"EEEEEE":"DDDDDD")+'"><td width=10><img src=images/drop.gif onclick="par_set(\''+np[0]+'\',0);params_upd();" style="cursor:pointer"></td>';
			t += '<td width=50%><i>'+nos(np[0].substr(1,np[0].length-1))+'</i>:</td><td> <b onclick="par_div_set(\''+np[0]+'\','+parseInt(np[1])+')" class=ym style="width:100%;cursor:pointer">'+par+'</b></td><td width=50> '+fast_up(np[0],np[1])+'</td></tr>';
			}
		}
	}
	o+= '</table><hr><center><a href="javascript:new_opt()" class=gBut>Нов свойство</a></center>';
	t += '</table><hr><center><a href="javascript:new_tb()" class=gBut>Нов Требование</a></center>';
	$('o').innerHTML = o;
	$('t').innerHTML = t;
	$('opts').innerHTML = 'СВОЙСТВА (суммарно <b>'+opts+'</b> статов)';
	$('reqs').innerHTML = 'ТРЕБОВАНИЯ (суммарно <b>'+reqs+'</b> статов)';
	sec_inf();
}

function perc_set(par)
{
	if ($(par).checked)
	 par_set2(par,parseInt(par_val2(par))+'%');
	else
	 par_set2(par,parseInt(par_val2(par)));
}

function main_inf()
{
	var z;
	z = '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
	z += '<tr><td align=center colspan=10>';
	z += '<center class=user onclick="ch_name()">'+par_val('name')+'</center>';
	z += '</td></tr>';
	z += '<tr><td width=50% id=ptype align=center>&nbsp;</td>';
	z += '<td align=center width=62><img src=images/magic/'+par_val('image')+'.gif onclick="change_img()"></td>';
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
	z += '&nbsp;';
	z += '</td></tr><tr><td align=center colspan=10 width=90%>';
	z += '<i onclick="ch_describe()">Описание: <b>'+par_val('describe')+'</b></i>';
	z += '</td></tr><tr><td align=center colspan=10 width=90%>';
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

function par_val2(par)
{
	var _params = vparams.split('@');
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

function par_set2(par,val)
{
	if (par_val2(par)===false) 
	 vparams += par+'='+val+'@';
	else
	 {
	var _params = vparams.split('@');
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
	vparams = _params.join('@');
	params_upd();
	 }
	return true;
}

function init_main_layer()
{
	ml.style.visibility = 'visible';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 50;
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
	//if (parseInt(par_val2(all_params[i]))==0)
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
	par_set2($('param').value,parseInt($('_addopt').value));
	disable_main_layer();
	params_upd();
}

function add_opt2()
{
	par_set($('param').value,parseInt($('_addopt').value));
	disable_main_layer();
	params_upd();
}


function new_tb()
{
	init_main_layer();
	var slctd = '';
	var cls='items';
	slctd = '<select id=param class=items>';
	for (var i=0;i<all_params.length;i++)
	if (all_params[i]=='level' || all_params[i]=='s6' || all_params[i]=='m1' || all_params[i]=='m2')
		{
			cls = 'DDDDDD';
			if (all_params[i].substr(0,1)=='s' && all_params[i].length==2) cls='DDFFDD';
			if (all_params[i].substr(0,1)=='s' && all_params[i].substr(0,1)=='b') cls = 'DFDFDF';
			slctd += '<option value="t'+all_params[i]+'" style=\'background:#'+cls+'\'>'+nos(all_params[i])+'</option>';
		}
	slctd += '</select>';
	ml.innerHTML += '<form onsubmit="add_opt2();return false;">'+slctd+'<input class=login type=text value=0 id="_addopt"><hr><input class=login type=submit value=[OK]></form>';
}

function fast_up(par,val)
{
	val = parseInt(val);
	return '<img src=images/fixed_on.gif onclick="par_set(\''+par+'\','+(val*2)+')" ondblclick="par_set(\''+par+'\','+(val*3)+')"><img src=images/battle/down.gif onclick="par_set(\''+par+'\','+(val-1)+')" ondblclick="par_set(\''+par+'\','+(val-3)+')"><img src=images/battle/up.gif onclick="par_set(\''+par+'\','+(val+1)+')" ondblclick="par_set(\''+par+'\','+(val+3)+')"><img src=images/fixed_off.gif onclick="par_set(\''+par+'\','+(val/2)+')" ondblclick="par_set(\''+par+'\','+(val/3)+')">';
}

function fast_up2(par,val)
{
	val = parseInt(val);
	return '<img src=images/fixed_on.gif onclick="par_set2(\''+par+'\','+(val*2)+')" ondblclick="par_set2(\''+par+'\','+(val*3)+')"><img src=images/battle/down.gif onclick="par_set2(\''+par+'\','+(val-1)+')" ondblclick="par_set2(\''+par+'\','+(val-3)+')"><img src=images/battle/up.gif onclick="par_set2(\''+par+'\','+(val+1)+')" ondblclick="par_set2(\''+par+'\','+(val+3)+')"><img src=images/fixed_off.gif onclick="par_set2(\''+par+'\','+(val/2)+')" ondblclick="par_set2(\''+par+'\','+(val/3)+')">';
}

function change_img(par)
{
	init_main_layer();
	if (par) 
	{
		par_set('image',par.substr(0,par.length-4));
		main_inf();
	}
	ml.innerHTML += '<form onsubmit="show_images();return false;"><img src=images/magic/'+par_val('image')+'.gif><br>Длина:<input class=login type=text value=48 id="_width" size=4>Высота:<input class=login type=text value=60 id="_height" size=4><hr><input class=login type=submit value="[Показать все рисунки]"></form><a href=imgs.php?'+Math.random()+' target=updater class=bga>Обновить библиотеку рисунков.</a>';
}
function show_images()
{
	ml.innerHTML += '<img src=images/progress.gif>';
	xmlhttp.open('get', 'services/image_list.php?type=2&width=48&height=60&rand='+Math.random()+'');
	xmlhttp.onreadystatechange = ajax_response;
	xmlhttp.send(null);
}
function ajax_response()
{
	if(xmlhttp.readyState == 4)
			{
				if(xmlhttp.status == 200)
				{
					init_main_layer();
					var response = xmlhttp.responseText;
					var z = '';
					if (response == 'none') ml.innerHTML += 'Рисунков не найдено.';
					else
					{
					response = response.split('|');
					for (var i=0;i<response.length;i++)
					if (response[i])
					{
						z+= '<img src=images/magic/'+response[i]+' onclick="change_img(\''+response[i]+'\')" width=40> ';
					}
					ml.innerHTML += z;
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
function ch_times()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_timesM();return false;">Перезарядка(сек)<input class=login type=text value='+par_val('colldown')+' name=colldown id=colldown><br>Перезарядка(ход)<input class=login type=text value='+par_val('turn_colldown')+' name=turn_colldown id=turn_colldown><br>Время действия(сек)<input class=login type=text value='+par_val('esttime')+' name=esttime id=esttime><br>Время действия(ход)<input class=login type=text value='+par_val('turn_esttime')+' name=turn_esttime id=turn_esttime><br><hr><input class=login type=submit value=[OK]></form>';
}

function ch_timesM()
{
	par_set('colldown',$('colldown').value);
	par_set('turn_colldown',$('turn_colldown').value);
	par_set('esttime',$('esttime').value);
	par_set('turn_esttime',$('turn_esttime').value);
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

function ch_manacost()
{
	init_main_layer();
	ml.innerHTML += '<form onsubmit="ch_manacostM();return false;">Стоимость маны<input class=login type=text value="'+par_val('manacost')+'" id="manacost" size=8><hr>Кол-во целей<input class=login type=text value="'+par_val('targets')+'" id="targets" size=6><hr><input class=login type=submit value=[OK]></form>';
}

function ch_manacostM()
{
	par_set('manacost',parseInt($('manacost').value));
	par_set('targets',parseInt($('targets').value));
	disable_main_layer();
	main_inf();
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

function sh_types()
{
	var a = '';
	a = '<select size="1" id="special" class="real" onchange="up_types()"><option value="0">Обычное</option><option value="1">Молчанка</option><option value="2">Невидимость</option><option value="3">Лёгкая травма</option><option value="4">Средняя травма</option><option value="5">Тяжёлая травма</option><option value="6">Торговая лицензия</option><option value="14">Лицензия шахтёра</option><option value="15">Отдышка шахтёра</option><option value="16">Супер-регенерация</option><option value="17">Ступр</option></select><select size="1" id="forenemy" class="real" onchange="up_types()"><option value="0">На свою команду</option><option value="1">На чужую команду</option><option value="2">На любого персонажа</option></select><hr><font class=items onclick=ch_times()>Перезарядка <b>'+par_val('colldown')+'</b> сек./ <b>'+par_val('turn_colldown')+'</b> ход. | Время действия <b>'+par_val('esttime')+'</b> сек/ <b>'+par_val('turn_esttime')+'</b> ход.</font>';
	$('ptype').innerHTML = a;
	a = '<center class=inv onclick=ch_manacost()><font class=ma>Стоимость маны '+par_val('manacost')+' MA</font> |';
	a += '<font class=items>Кол-во целей '+par_val('targets')+'</font></center><br>';
	a += '<select size="1" id="type" class="real" onchange="up_types()"><option value="0">Нейтрал</option><option value="1">Религия</option><option value="2">Некромантия</option></select><select size="1" id="where_buy" class="real" onchange="up_types()"><option value="0">Изучить в гильдии магов</option><option value="1">Дилер</option><option value="2">Нигде</option></select>';
	$('pstype').innerHTML = a;

	var objtype = $('forenemy');
	var type = par_val('forenemy');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	
	var objtype = $('special');
	var type = par_val('special');
	for (i=0;i<=objtype.length;i++)
	{
		if (objtype.options[i].value == type) 
			{
				objtype.options[i].selected = true;
				break;
			}
	}
	
	objtype = $('type');
	type = par_val('type');
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
	par_set('special',$('special').value);
	par_set('type',$('type').value);
	par_set('forenemy',$('forenemy').value);
	par_set('where_buy',$('where_buy').value);
}

function sbmt()
{
ml.innerHTML='<img src=images/progress.gif><form method=post name=sbmtform><textarea cols=0 rows=0 name=params style="visibility:hidden">'+params+'</textarea><textarea cols=0 rows=0 name=vparams style="visibility:hidden">'+vparams+'</textarea></form>';
d.sbmtform.submit();
}
