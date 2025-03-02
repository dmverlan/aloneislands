d.write('<div  class=but style="position:absolute; left:-5px; top:-5px; z-index: 65000; width:0px; height:0px;display:none;" id="ec">&nbsp;</div>');
var ActionFormUse = 0;
var ec = $("#ec");
//$("#ec").fadeOut(1);
var Give = '';
var Give_names = '';
var inited = 0;

function Sinit_main_layer()
{
	if(inited) return;
	inited = 1;
	$("#ec").fadeOut(1);
	$("#ec").css({left:'225px',top:'105px',width:'220px',height:'200px',display:'block'});
	d.getElementById('ec').innerHTML = '<div style="margin:10px"><div class=but>[Aloneislands.Ru] <img src="images/closebox.png" onclick="closesellingform()" style="cursor:pointer;float:right;position:relative; top:-20px; left:30px;"></div><img src="images/DS/hr.png" height=3 width=200><div id=transfer style="display:block;background-color:#EEEEEE;"></div></div>';
	$("#ec").show(300);
	setTimeout("Focus()",1000);
}
function Sdisable_main_layer()
{
	if(!inited) return;
	inited = 0;
	d.getElementById('ec').innerHTML = '&nbsp;';
	$("#ec").toggle(300);
}
function Focus()
{
	if(ActionFormUse)
		document.getElementById(ActionFormUse).focus();
}

function closesellingform()
{
top.frames['ch_buttons'].document.mess.message.focus();
ActionFormUse = '';
Sdisable_main_layer();
Give = '';
Give_names = '';
}

function sellingform(wuid,wnametxt) 
{
		Sinit_main_layer();
       $('#transfer').html('<form action=main.php method=POST><input type=hidden name=id value='+wuid+'><b>Продать "'+wnametxt+'"?</b><table border=0 class=but2><tr><Td><b>Кому:</b></td><td> <INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=laar></td></tr><tr><td> <b>Цена:</b></td><td> <INPUT TYPE="text" name=forprice  maxlength=5 class=laar></td></tr></table> <input type=submit value="Продать" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornickname';
}

function giveallH(count) 
{
		Sinit_main_layer();
       $('#transfer').html('<form action=main.php?giveallH=1 method=POST><b>Передать все травы['+count+']?</b><table border=0 class=but2><tr><Td><b>Кому:</b></td><td> <INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=laar></td></tr></table> <input type=submit value="Передать" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornickname';
}

function str_replace(replacement,substr,str)
{
while(str.indexOf(replacement)!=-1) str=str.replace(replacement,substr);
return str;
}

function peredat(wuid,wnametxt) 
{
		Sinit_main_layer();
		if(wuid && Give.indexOf(wuid+'!') == -1)
		{
			Give += wuid+'!';
			Give_names += wnametxt+'~';
		}
		var table = '<table border=1 class=but width=100%>';
		var ar = Give_names.split("~");
		for(var i=0;i<ar.length-1;i++)
			table += '<tr><td>'+ar[i]+'</td><td style="cursor:pointer;" onclick=delete_p(\''+i+'\') class=but2><center class=hp>X</center></td></tr>';
		table += '</table>';
      $('#transfer').html('<form action=main.php?ids='+Give+' method=POST>Передать '+table+'<table border=0 class=but2 width=100%><tr><Td><b>Кому:</b></td><td><INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=login></td></tr><tr><td colspan=9 class=but align=center><input type=submit value="Передать" class=login style="width:90%"></td></tr></table></FORM>');
       ActionFormUse = 'fornickname';
}

function delete_p(k)
{
	var ar = Give_names.split("~");
	var arg = Give.split("!");
	Give = '';
	Give_names = '';
	for(var i=0;i<ar.length-1;i++)
		if(i!=k) 
		{
			Give += arg[i]+'!';
			Give_names += ar[i]+'~';
		}
	peredat(0,0);
}

function peredatm() 
{		
		Sinit_main_layer();
       $('#transfer').html('<form action=main.php method=POST><input type=hidden name=money value=1><b>Передать Деньги?</b><table border=0 class=but2><tr><Td><b>Кому:</b></td><td>  <INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=laar> </td></tr><tr><td> <b>Сколько:</b> </td><td><INPUT TYPE="text" name=kolvo  maxlength=6 class=laar></td></tr><tr><td> [Причина:]</td><td><INPUT TYPE="text" name=reason  maxlength=50 class=laar></td></tr></table><input type=submit value="Передать" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornickname';
}

function napad(id) 
{		
		Sinit_main_layer();
		$('#transfer').html('<form action=main.php method=POST><input type=hidden name=napad value='+id+'><b>Напасть/Вмешаться?</b><center class=but2>На кого? <INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=login style="width:90%"><br><select size="1" name="za" class=real style="width:90%"><option value="0" selected>Против</option><option value="1">За</option></select></center> <input type=submit value="OK" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornickname';
}

function zakl(id,name,index) 
{
		Sinit_main_layer();
		$('#transfer').html('<form action=main.php method=POST><input type=hidden name=zakl value='+id+'><b>Использовать "'+name+'"?</b><br><b>На кого:</b> <INPUT TYPE="text" name=fornickname id=fornickname  maxlength=25 class=laar><input type=submit value="OK" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornickname';
}

function potion(id,name) 
{	
	Sinit_main_layer();
	$('#transfer').html('<form action=main.php method=POST><input type=hidden name=potion value='+id+'><b>Выпить "'+name+'"?</b><br><input type=submit value="OK" class=login style="width:100%"></FORM>');
       ActionFormUse = 'fornicka';
}

function teleport(id,name) 
{	
Sinit_main_layer();
$('#transfer').html('<form action=main.php method=POST><input type=hidden name=teleport value='+id+'><b>Использовать "'+name+'"?</b><br> Введите координаты для перемещения.<b><br>X: <INPUT TYPE="text" name=X  maxlength=5 class=laar><br>Y: </b><INPUT TYPE="text" name=Y  maxlength=5 class=laar><br><input type=submit value="OK" class=login style="width:100%"></FORM>');
}

function show_imgs_sell(inp)
{
	document.write('<table cellSpacing="0" cellPadding="0" border="0" cellspacing=5 cellspadding=5 width=660> 	<tr> 		<td align="middle"> 		 <img title="Ножи" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=noji\'" height="50" src="images/gameplay/shop_icons/noz.png" width="40" border="0"> <img title="Мечи" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=mech\'" height="50" src="images/gameplay/shop_icons/me4i.png" width="40" border="0"> <img title="Дробящее" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=drob\'" height="50" src="images/gameplay/shop_icons/drobja6ee.png" width="40" border="0"> <img title="Топоры" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=topo\'" height="50" src="images/gameplay/shop_icons/topory.png" width="40" border="0"> <img title="Книги заклинаний" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=book\'" height="50" src="images/gameplay/shop_icons/book.png" width="40" border="0"> <img title="Щиты" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=shit\'" height="50" src="images/gameplay/shop_icons/6it.png" width="40" border="0"> <img title="Оружие дальнего действия" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=kid\'" height="50" src="images/gameplay/shop_icons/metatelnoe.png" width="40" border="0"> <img title="Шлемы" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=shle\'" height="50" src="images/gameplay/shop_icons/6lemi.png" width="40" border="0"> <img title="Брони" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=bron\'" height="50" src="images/gameplay/shop_icons/bronja.png" width="40" border="0"> <img title="Наручи" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=naru\'" height="50" src="images/gameplay/shop_icons/naru4i.png" width="40" border="0"> <img title="Перчатки" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=perc\'" height="50" src="images/gameplay/shop_icons/per4atki.png" width="40" border="0"> <img title="Сапоги" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=sapo\'" height="50" src="images/gameplay/shop_icons/sapogi.png" width="40" border="0"> <img title="Кольца" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=kolc\'" height="50" src="images/gameplay/shop_icons/kolco.png" width="40" border="0"> <img title="Кулоны" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=kylo\'" height="50" src="images/gameplay/shop_icons/kulon.png" width="40" border="0"> <img title="Пояса" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=poya\'" height="50" src="images/gameplay/shop_icons/pojas.png" width="40" border="0"></td> 	</tr> 	<tr> 		<td align="middle"> 		 <img title="Свитки нападения" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=napad\'" height="50" src="images/gameplay/shop_icons/napadenija.png" width="40" border="0"> <img title="Свитки заклинаний и лицензии" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=zakl\'" height="50" src="images/gameplay/shop_icons/svitki.png" width="40" border="0"> <img title="Фляги восстановления в бою" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=kam\'" height="50" src="images/gameplay/shop_icons/zaklinanija.png" width="40" border="0"> <img title="Зелья алхимические" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=potion\'" height="50" src="images/gameplay/shop_icons/zelja.png" width="40" border="0"> <img title="Руны" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=rune\'" height="50" src="images/gameplay/shop_icons/rune.png" width="40" border="0"> <img title="Травы алхимические" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=herbal\'" height="50" src="images/gameplay/shop_icons/travy.png" width="40" border="0"> <img title="Телепорт" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=teleport\'" height="50" src="images/gameplay/shop_icons/teleport.png" width="40" border="0"> <img border="0" src="images/gameplay/shop_icons/fish.png" width="40" height="50" title="Рыба и снасти" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=fishing\'"> <img border="0" src="images/gameplay/shop_icons/instruments.png" width="40" height="50" title="Инструменты" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=instrument\'"> <img border="0" src="images/gameplay/shop_icons/resources.png" width="40" height="50" title="Ресурсы" style="CURSOR: pointer" onclick="location=\'main.php?'+inp+'&set_type=resources\'"></td> 	</tr> </table>');
}