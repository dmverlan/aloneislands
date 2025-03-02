var lines_count = 0;

document.write('<LINK href=css/chlist.css rel=STYLESHEET type=text/css><script type="text/javascript" language="javascript" src="js/jquery.js"></script>'+
		'<body style="background-image: url(\'images/DS/chlist_bg.jpg\');" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0"><div style=" background-image: url(\'images/DS/r.png\'); height:100%; width:100%; "><DIV id="smiles" style="visibility: hidden; top:0; position:absolute;"></DIV><center id="head" style="visibility: visible;"></center> <div id="ch" style="text-align:center;width:100%;">&nbsp;</div><div style="position:absolute; left:0px; top:0px; z-index: 2; width:80 ; height:40; display:none;" class="menu" id="description"></div><div id=menu class="menu"></div></div><SCRIPT LANGUAGE="JavaScript" src="js/ch_msg.js"></script><SCRIPT LANGUAGE="JavaScript" src="js/jquery.js"></SCRIPT><TEXTAREA id=cpnick style="display:none;"></TEXTAREA>');

function show_head()
{
	if(top.loading)
		top.load(20);
var _text = '<br><table style="width: 100%;height:60;" cellspacing="0" cellpadding="0"> <tr> <td style="width: 5%" valign=top>';
	_text += '<img src="images/DS/chlist_sort.png" onclick="show_srt_wth()" style="cursor:pointer" onmouseover="this.src=\'images/DS/chlist_sort_hover.png\'" onmouseout="this.src=\'images/DS/chlist_sort.png\'">';
	_text += '</td> <td style="width: 80%;" id="head2" align="center">';
	_text += '<img src="images/DS/chlist_refresh.png" onclick="location=\'ch.php?a=1&'+MYrand()+'\'" style="cursor:pointer" onmouseover="this.src=\'images/DS/chlist_refresh_hover.png\'" onmouseout="this.src=\'images/DS/chlist_refresh.png\'">';
	_text += '</td> <td style="text-align: right; width: 5%" align=right  valign=top>';
	_text += '<img src="images/DS/chlist_weather.png" onclick="location=\'weather.php?a=1&'+MYrand()+'\'" style="cursor:pointer" onmouseover="this.src=\'images/DS/chlist_weather_hover.png\'" onmouseout="this.src=\'images/DS/chlist_weather.png\'">';
	_text += '</td> </tr> </table>';
document.getElementById('head').innerHTML = _text;
}

function show_srt_wth()
{
document.getElementById('head2').innerHTML = "<table width=100% class=items><tr><td><a href='javascript:sort_on_fly (1)' class=bnick>lvl+</a></td><td><a href='javascript:sort_on_fly (2)' class=bnick>lvl-</a></td><td><a href='javascript:sort_on_fly (4)' class=bnick>z-a</a></td><td><a href='javascript:sort_on_fly (3)' class=bnick>a-z</a></td></tr></table>";
}

function show_list(sort_type,hr)
{
 var ch = document.getElementById('ch');
 var text;
 var _all = '';
 sort_type = parseInt(sort_type);
 if (sort_type == 0) sort_type == 2;
 if (priveleged && vsg) 
 {
			_all = '<a href=ch.php?view=all&sort='+sort_type+'&'+MYrand()+'>Всего:</a>'+' <b class=cord>'+vsg+'</b>';
 }
 else 
 {
		if (vsg)
			_all = '<b class=locname>Всего:</b>'+' <b class=cord>'+vsg+'</b>';
 }
 document.getElementById('head').innerHTML += '<font class=cord>('+xy+')</font><b> <a href=ch.php?view=this&sort='+sort_type+'&'+MYrand()+'>'+locname+':</a></b> <b class=cord>'+zds+'</b> <br> '+_all;
 text = '<table border="0" width="100%" cellspadding=0 cellspacing="0">';
 if (zds==0 && vsg==0) text = '<center class=locname>'+locname+'</center>';
 var i;
 for (var i=residents.length-1;i>=0;i--) text += resident_string(residents[i],hr); 
 if (sort_type == 4 || sort_type == 3) list.sort();
 else sortnum(list);
 if (sort_type == 3) 
  	for (var i=0;i<list.length;i++) text += hero_string(list[i],hr);
 else if (sort_type == 4) 
  	for (var i=list.length-1;i>=0;i--) text += hero_string(list[i],hr); 
 else if (sort_type == 1)
  	for (var i=0;i<list.length;i++) text+= hero_string(list[i],hr); 
 else
	for (var i=list.length-1;i>=0;i--) text += hero_string(list[i],hr); 
 text += '</table>';
 ch.innerHTML += text;
 if (hr==1) ch.innerHTML+='<hr>';
}

function hero_string (element,hr) 
{
 var arr = element.split("|");
 var s='<tr>';
 //if (lines_count%2 == 0) s = '<tr style="background-color: #EAEAEA">';
 lines_count++;
 var inviz='';
 var uninviz='';
 if (arr[0].indexOf('n=')>-1)
 {arr[0]=arr[0].replace('n=','');inviz='<i>';uninviz='</i>';}
 if (hr!=1)s += '<td width="15" align="center"><img src=images/_p.gif onclick="javascript:top.say_private(\''+arr[0]+'\',1)" style="cursor:pointer" title="Приватное сообщение"></td>';
 s += ' <td align="left"><img src=images/emp.gif width=10 height=1><img src=images/signs/'+arr[2]+'.gif title=\''+arr[3]+'\'>'+inviz+'<font class=user onclick="javascript:top.say_private(\''+arr[0]+'\')" title="Сообщение" style="cursor:pointer">'+arr[0].substr(0,20)+'</font>'+uninviz+' <b class=cord>[<font class=lvl>'+arr[1]+'</font>]</b> <a class=empty href=\'info.php?p='+arr[0]+'\' target=_blank> <img src=images/_i.gif border=0> </a>&nbsp; &nbsp; ';
 if (arr[4]!='') s+=' <img src=images/signs/molch.gif title="Заклинание молчания, ещё '+arr[4]+' сек.">';
 if (arr[5]!='') s+=' <img src=images/signs/travm.gif title="'+arr[5]+'">';
 if (arr[8]!='') s+=' <img src=images/p-root.png title="Создатели/'+arr[8]+'"  style="cursor:pointer">';
 if (arr[7]!='') s+=' <img src=images/signs/ignore.gif title="Снять игнорирование"  style="cursor:pointer"" onclick="location=\'ch.php?ignore_unset='+arr[0]+'&'+MYrand()+'\'">';
 if (arr[6]==1) s+=' <img src=images/signs/diler.gif title="Официальный дилер проекта">';
 s+='</td></tr>';
 return s;
}

function resident_string (element,hr) 
{
 var arr = element.split("|");
 var s='<tr>';
 lines_count++;
 s += '<td width="15" align="center" onclick="javascript:speech(\''+arr[2]+'\')"><img src=images/icons/eyeChat.png height=16></td>';
 s += ' <td align="left"><img src=images/emp.gif width=10 height=1><span class=user onclick="javascript:speech(\''+arr[2]+'\')" title="Сообщение" style="cursor:pointer;color:#FF6666;">'+arr[0].substr(0,20)+'</span><b class=cord>[<font class=lvl>'+arr[1]+'</font>]</b> <img src=images/_i.gif onclick="javascript:window.open(\'binfo.php?'+arr[3]+'\',\'_blank\')" style="cursor:pointer"> &nbsp; &nbsp; ';
 s+='</td></tr>';
 return s;
}

function sortnum(list1)
{
	var tmp;
	var w1,w2;
	for (var i=list1.length-1;i>0;i--)
	 for (var j=0;j<i;j++)
		{
			w1 = list1[j].substr(list1[j].indexOf("|")+1,3); w1 = parseInt(w1);
			w2 = list1[j+1].substr(list1[j+1].indexOf("|")+1,3); w2 = parseInt(w2);
			if (w1>w2) 
			 {
			 tmp = list1[j];
			 list1[j] = list1[j+1];
			 list1[j+1]=tmp;
			 }
		}
	list = list1;
}

function MYrand()
{
	return 'rand='+Math.random();
}

function sort_on_fly (sorttype)
{
	document.getElementById('ch').innerHTML = '';
	show_head();show_list (sorttype,'');
}


function speech(id)
{
	top.Funcy("speech.php?id="+id);
}