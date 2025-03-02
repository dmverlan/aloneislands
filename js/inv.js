var c_showed = 0;
document.write('<div style="position:absolute; left:-2px; top:-2px; z-index: 4; width:0px; height:0px; visibility:visible;" id="center"></div><div style="position:absolute; left:0px; top:0px; z-index: 3; width:100%; height:100%; display:none;" id="center2" class=news>&nbsp;</div>');
$("#center").hide(1);

function ssubm()
{
	document.inv.submit();
}

function show_presents()
{
	d.write('<br><font class=title>ПОДАРКИ('+prs[0]+')</font>');
	var t='<table>';
	var inf='';
	for (var i=1;i<prs.length;i++)
	{
		inf = '|<font class=user>'+prs[i][0]+'</font>@'+prs[i][4]+'@От:<b>'+prs[i][2]+'</b>@<i class=timef>['+prs[i][3]+']</i><hr><i>[Кликните для удаления]</i>';
		if (i%5==1) t+= '<tr>';
		t += '<td><img src=images/presents/'+prs[i][1]+'.jpg onmouseover="s_des(event,\''+inf+'\')" onmouseout="h_des()" onmousemove=move_alt(event) onclick="delete_pr(\''+prs[i][5]+'\')" style="cursor:pointer"></td>';
		if (i%5==0) t+= '</tr>';
	}
	t+= '</table>';
	d.write(t);
}

function delete_pr(id)
{
if (confirm("Вы действительно хотите выкинуть этот подарок?")) location = 'main.php?inv=presents&delpr='+id;
}



function inv_conf()
{
$("#center").css({left:'30%',top:'100',width:'450px',height:'200px'});
	if (!c_showed)
	{
	 //$("#center2").fadeIn(500);
	 $("#center").fadeIn(500);
	 c_showed++;

	 
	var f6_ch1 = 'CHECKED';
	var f6_ch2 = 'CHECKED';
	if(_group==2) 
		f6_ch1 = '';
	else
		f6_ch2 = '';
		
	var f5_ch1 = 'CHECKED';
	var f5_ch2 = 'CHECKED';
	if(_sort=='price') 
		f5_ch1 = '';
	else
		f5_ch2 = '';
	
	 var text = '<form name=inv method=get>';
	 text += '<div class=but><input type=radio value=1 name=filter_f6 onchange="ssubm()" '+f6_ch1+'> Группировать <span style="float:right"><input type=radio value=2 name=filter_f6 onchange="ssubm()" '+f6_ch2+'> Не группировать</span></div>';
	 text += '<div class=but><input type=radio value=tlevel name=filter_f5 onchange="ssubm()" '+f5_ch1+'> Сортировать по уровню <span style="float:right"><input type=radio value=price name=filter_f5 onchange="ssubm()" '+f5_ch2+'> Сортировать по цене</span></div>';
	 
	 text += '<center class=but2><select size="1" name="filter_f4" onchange="ssubm()"><option value="all">Все</option>';
	 var w = __types.split('|');
	 var g;
	 
	 for(var i=0;i<w.length-1;i++)
	 {
		g = w[i].split('=');
		text += '<option value='+g[0]+' ';
		if(_type==g[0]) text += 'SELECTED';
		text += '>'+g[1]+'</option>';
	 }
	 text += '</select></center>';
	 
	 text += '</form>';
	 $("#center").html(sbox('<table style="width: 100%"> <tr> <td class=ma>Настройки инвентаря</td> <td style="height: 30px; width: 30px;cursor:pointer;" onclick="inv_conf()" title=Close> <img src="images/closebox.png" width="30" height="30" ></td> </tr> <tr> <td colspan=3 class=combofight>'+text+'</td></tr> </table>'));
	 }
	else
	{
	 //$("#center2").fadeOut(500);
	 $("#center").fadeOut(500);
	 c_showed--;
	}
}

var all_link = '';
if(_type=='herbal' || _type=='resources' || _type=='fish')
	all_link = '<a href=main.php?filter_f4=all class=nt>Весь инвентарь</a>';


if(_herbal+_resources+_fish)
$("#container1").html("<table border=0 width=78% style=\"width:78%\"><tr><td align=center onclick=\"location='main.php?filter_f4=herbal'\" style='cursor:pointer;'><img src=images/gameplay/shop_icons/travy.png><div class=gray>Травы["+_herbal+"]</div></td><td align=center onclick=\"location='main.php?filter_f4=resources'\" style='cursor:pointer;'><img src=images/gameplay/shop_icons/resources.png><div class=gray>Ресурсы["+_resources+"]</div></td><td align=center onclick=\"location='main.php?filter_f4=fish'\" style='cursor:pointer;'><img src=images/gameplay/shop_icons/fish.png><div class=gray>Рыба["+_fish+"]</div></td></tr></table>"+all_link);
else 
	$("#container1").html(all_link);

