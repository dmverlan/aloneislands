document.write('<div style="position:absolute; left:0px; top:-500px; z-index: 2; width:300 ; height:400; visibility:visible;" id="ml" class=inv>&nbsp;</div>');
var ml = document.getElementById('ml');
$(ml).fadeOut(1);
var mx,my;
var minlvl,maxlvl;

function show_nature(x,y){
mx = x;
my = y;
x+=22;
y+=26;
var text;
 text = '<img src=images/emp.gif width=560 height=1><table border="0" cellspacing="1" cellpadding="0" style="border-style: solid; border-width: 1px" width=560>';
 var cx,cy;
 var dir;
 var onclick_g;
 var params;
 var ctxt = '';
 for(cy=y-2;cy<=y+2;cy++)
 {
 text+='<tr>';
 for (cx=x-3;cx<=x+3;cx++)
 {
 params = go_str.substr(go_str.indexOf('<'+(cx-22)+'_'+(cy-26)),50).split('@');
 if (params[1])
 params = params[1].split(',');
	else
 params = 0;	
 ctxt = '';
 if (parseInt(params[0]) || 1) 
 {
 ctxt += '<table border=0 width=100%><tr><td>';
 ctxt += '<font class=timef align=center><b>'+params[0]+'</b></font><br>';
 ctxt += '<font color=#FFFFFF align=center>';
 if (parseInt(params[1])) ctxt += '<img src=images/icons/wood.gif>'+params[1]+'<br>';
 if (parseInt(params[2])) ctxt += '<img src=images/icons/herbal.gif>'+params[2]+'<br>';
 if (parseInt(params[3])) ctxt += '<img src=images/icons/fish.gif>'+params[3]+'<br>';
 if (parseInt(params[4])) ctxt += '<img src=images/icons/agriculture.gif>'+params[4]+'<br>';
 ctxt += '</font>';
 ctxt += '</td><td>';
 if (params[6]) ctxt += '<font color=#FFFFFF align=center><img src=images/persons/male_'+params[6]+'.gif height=30>'+params[7]+'-'+params[8]+'</font>';
 ctxt += '</td></tr></table>';
 }
 if (params[5]) ctxt += '<font color=#FFFFFF align=center>'+params[5]+'</font>';
  if (go_str.indexOf('<'+(cx-22)+'_'+(cy-26))==-1) ctxt = '&nbsp;';
 if(cx<50 && (cx>10 || cx%10<5)) dir='map/day'; else dir='map/day';
  onclick_g = 'onclick = "location=\'main.php?gotox='+(cx-22)+'&gotoy='+(cy-26)+'\'" style="cursor:pointer"';
 if (cx==x && cy==y)  text+='<td class=fader background="images/'+dir+'/'+cx+'_'+cy+'.jpg" style="cursor:pointer" onclick="shm_resedit()" width=80 height=80>'+ctxt+'</td>';	
 else
 if (go_str.indexOf('<'+(cx-22)+'_'+(cy-26))>-1)  text+='<td class=go_yes background="/images/'+dir+'/'+cx+'_'+cy+'.jpg" '+onclick_g+'  width=80 height=80>'+ctxt+'</td>';else text+='<td  width=80 height=80 background="/images/'+dir+'/'+cx+'_'+cy+'.jpg" '+onclick_g+'>'+ctxt+'</td>';
 }
 text+='</tr>';
 }
 text+='</table>';
 document.write(text);
}

function return_minicart(x,y)
{
 x+=22;
 y+=26;
 var cx,cy;
 var text;
 text = '<table border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px">';
 var dir='map/day';
 var onclick_g = '';
 for(cy=y-4;cy<=y+4;cy++)
 {
 text+='<tr>';
 for (cx=x-5;cx<=x+5;cx++)
 {
 if(cx<50 && (cx>10 || cx%10<5)) dir='map/day'; else dir='map/day';
 onclick_g = 'onclick = "location=\'main.php?gotox='+(cx-22)+'&gotoy='+(cy-26)+'\'" style="cursor:pointer"';
 if (cx==x && cy==y) text += '<td class=fader><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=20 '+onclick_g+'></td>';	
 else
 if (go_str.indexOf('<'+(cx-22)+'_'+(cy-26))>-1)  text+='<td class=go_yes><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=20 '+onclick_g+'></td>';else text+='<td><img src=/images/'+dir+'/'+cx+'_'+cy+'.jpg width=20 '+onclick_g+'></td>';
 }
 text+='</tr>';
 }
 text+='</table>';
 return text;
}

function init_main_layer()
{
	//ml.style.visibility = 'visible';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 30;
	ml.innerHTML = 'РЕДАКТОР КАРТЫ [Aloneislands.Ru] <a href=javascript:disable_main_layer()>УБРАТЬ</a><hr>';
	$(ml).slideDown(100);
}
function disable_main_layer()
{
	//ml.style.visibility = 'hidden';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 0;
	ml.innerHTML = '';
	$(ml).slideUp(100);
}

function shm_xy()
{
	init_main_layer();
		ml.innerHTML += '<form method=get action=main.php>Перейти на клетку X:<input type=text class=login name=gotox value=0 size=3> - Y:<input type=text class=login name=gotoy value=0 size=3><hr><input type=submit value=Перейти class=login>';
}

function shm_celltype(tt)
{
	init_main_layer();
	var rt='';
	rt += '<select size="1" name="type" class="real">'+
	'	<option '+((tt==0)? 'SELECTED' : '')+' value="a">[0]Помещение</option>'+
	'	<option '+((tt==1)? 'SELECTED' : '')+' value="1">[1]Дорога</option>'+
	'	<option '+((tt==2)? 'SELECTED' : '')+' value="2">[2]Трава</option>'+
	'	<option '+((tt==3)? 'SELECTED' : '')+' value="3">[3]Пустыня</option>'+
	'	<option '+((tt==4)? 'SELECTED' : '')+' value="4">[4]Лес</option>'+
	'	<option '+((tt==5)? 'SELECTED' : '')+' value="5">[5]Огненная местность</option>'+
	'	<option '+((tt==6)? 'SELECTED' : '')+' value="6">[6]Вода</option>'+
	'	<option '+((tt==7)? 'SELECTED' : '')+' value="7">[7]Пещера</option>'+
	'	<option '+((tt==8)? 'SELECTED' : '')+' value="8">[8]Болото</option>'+
	'</select>';
	ml.innerHTML += '<form method=get action=main.php>Тип клетки '+rt+'<hr><input type=submit value=ОК class=login>';
}

function shm_mainparams(p,b,w,t)
{
	init_main_layer();
	var rt='';
	var pa = (p==1)?'CHECKED':'';
	var ba = (b==1)?'CHECKED':'';
	var wa = (w==1)?'CHECKED':'';
	rt += '<input type="checkbox" id="passable" name=passable '+pa+' value=1><label for="passable">Можно пройти</label><Br><input type="checkbox" id="winnable" name=winnable '+wa+' value=1><label for="winnable">Можно завоевать</label><Br><input type="checkbox" id="buildable" name=buildable '+ba+' value=1><label for="buildable">Можно построить</label><Br>Телепорт цена: <input type=text name=teleport value='+t+'>';
	ml.innerHTML += '<form method=post action="main.php?act=emp">Параметры <br>'+rt+'<hr><input type=submit value=ОК class=login>';
}

function shm_cellgoin(locid)
{
	init_main_layer();
		ml.innerHTML += '<form method=get action=main.php>Можно войти в <input type=hidden name=goidd value=1><input type=text class=login name=go_id value="'+locid+'" size=8><hr><input type=submit value=OK class=login>';
}

function shm_cellname(locid)
{
	init_main_layer();
		ml.innerHTML += '<form method=post action=main.php>Название клетки: <input type=text class=login name=name value="'+locid+'" size=20 id=name><hr><input type=submit value=OK class=login>';
		
	$(function(){$("#name").get(0).focus();});
}

function shm_resedit()
{
 params = go_str.substr(go_str.indexOf('<'+(mx)+'_'+(my)),30).split('@');
  params = params[1].split(',');
 var fishing='';
 var herbal = '';
 var agriculture = '';
 var wood = '';
 var i;
 
 herbal = 'ТРАВЫ:<select class=items name=herbal>';
 for (i=0;i<=5;i++)
 if (parseInt(params[2])==i) 
 herbal+= '<option value='+i+' SELECTED>'+i+'</option>';
	else  
 herbal+= '<option value='+i+'>'+i+'</option>';
 herbal+='</select>';
 
 wood = 'ДЕРЕВЬЯ:<select class=items name=wood>';
 for (i=0;i<=8;i++)
 if (parseInt(params[1])==i) 
 wood+= '<option value='+i+' SELECTED>'+i+'</option>';
	else  
 wood+= '<option value='+i+'>'+i+'</option>';
 wood+='</select>';
 
 fishing = 'РЫБА:<select class=items name=fishing>';
 for (i=0;i<=2;i++)
 if (parseInt(params[3])==i) 
 fishing+= '<option value='+i+' SELECTED>'+i+'</option>';
	else  
 fishing+= '<option value='+i+'>'+i+'</option>';
 fishing+='</select>';
 
 agriculture = 'ЗЛАКИ:<select class=items name=agriculture>';
 for (i=0;i<=5;i++)
 if (parseInt(params[4])==i) 
 agriculture+= '<option value='+i+' SELECTED>'+i+'</option>';
	else  
 agriculture+= '<option value='+i+'>'+i+'</option>';
 agriculture+='</select>';
 
 
 	init_main_layer();
	ml.innerHTML += '<form method=post action=main.php?res=1>'+herbal+'<br>'+fishing+'<br>'+wood+'<br>'+agriculture+'<hr><input type=submit value=OK class=login>';
}

function shm_cellbots(n,min,max)
{
	var bots='<select name=bid id=bid class=items onchange="ch_obr()">';
	var slct = '';
	var obr = bn[0][2];
	var id;
	for (var i=0;i<bn.length-1;i++)
	{
		if (bn[i][0]==n) {slct = 'SELECTED';obr=bn[i][2];id=bn[i][1];} else slct = '';
		bots+= '<option value='+bn[i][1]+' '+slct+'>'+bn[i][0]+'</option>';
		minlvl = bn[i][3];maxlvl = bn[i][4];
	}
	if(min<minlvl) min = minlvl;
	if(max<minlvl) max = minlvl;
	if(min>maxlvl) min = maxlvl;
	if(max>maxlvl) max = maxlvl;
	bots+= '</select><br>';
	bots+= 'Частота нападения(1-10) <input type=text class=login name=frq size=3 value=10><br>';
	bots+= 'Мин. Уровень <input type=text class=login name=minlvl size=3 value="'+min+'"><br>';
	bots+= 'Мax. Уровень <input type=text class=login name=maxlvl size=3 value="'+max+'"><hr><a href=binfo.php?'+id+' target=_blank><img src=images/persons/'+obr+'.gif id=obr><div id=lvls></div></a>';
	init_main_layer();
	ml.innerHTML += '<form method=post action=main.php?bts=1>'+bots+'<hr><input type=submit value=OK class=login>';
}

function ch_obr()
{
	var obr = bn[0][2];
	for (var i=0;i<bn.length-1;i++)
		if (bn[i][1]==$('#bid').get(0).value) 
		{
			obr = bn[i][2];
			minlvl = bn[i][3];
			maxlvl = bn[i][4];
		}
	$('#obr').get(0).src = 'images/persons/'+obr+'.gif';
	$('#lvls').html("<b>"+minlvl+"</b> - <b>"+maxlvl+"</b>");
}