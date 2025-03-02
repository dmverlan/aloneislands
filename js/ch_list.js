document.write('<META Content=\'text/html; charset=windows-1251\' Http-Equiv=Content-type><LINK href=ch.css rel=STYLESHEET type=text/css><body bgcolor="#C8BBA4" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0"><DIV id="smiles" style="visibility: hidden; top:0; position:absolute;"></DIV><div id="head" style="visibility: visible;"></div><div id="ch" style="visibility: visible;"></div><div style="position:absolute; left:0px; top:0px; z-index: 2; width:80 ; height:40; visibility:hidden;" class="menu" id="description"></div><div id=menu style="visibility:hidden; width:10; height:80; z-index:1; top:-200; left:0; position:absolute;"><SCRIPT LANGUAGE="JavaScript" src="js/ch_msg.js?1"></SCRIPT><TEXTAREA id=cpnick style="display:none;"></TEXTAREA>');

function show_head()
{
document.getElementById('head').innerHTML = '<table border="0" width="250" cellspacing="0" cellpadding="0" height="317"> <tr> <td background="images/design/chlist/leftbg.gif" width="19">&nbsp;</td> <td valign="top" background="images/cofeelogo.gif" style="background-repeat:no-repeat; background-position: top;" id=ch> <table border="0" width="212" id="table2" cellspacing="0" cellpadding="0"> <tr> <td valign="top" rowspan="2"> <img border="0" src="images/design/chlist/left.gif" width="30" height="75"></td> <td width="45" valign="top"><a href="javascript:show_srt_wth()"> <img border="0" src="images/design/chlist/az.gif" width="45" height="51"></a></td> <td width="62" valign="top"><a href="ch.php" title=Обновить> <img border="0" src="images/design/chlist/midle.gif" width="62" height="55"></a></td> <td valign="top"><a href="weather.php?a=1&'+MYrand()+'"> <img border="0" src="images/design/chlist/w.gif" width="46" height="51"></a></td> <td rowspan="2"> <img border="0" src="images/design/chlist/right.gif" width="30" height="75"></td> </tr> <tr> <td valign="top" colspan="3" id="head2">&nbsp;</td> </tr> </table> </td> <td background="images/design/chlist/rightbg.gif" width="19">&nbsp;</td> </tr> </table>';
}

function show_srt_wth()
{
document.getElementById('head2').innerHTML = "<table width=100% class=items><tr><td><a href='javascript:sort_on_fly (1)' class=bnick>lvl+</a></td><td><a href='javascript:sort_on_fly (2)' class=bnick>lvl-</a></td><td><a href='javascript:sort_on_fly (4)' class=bnick>z-a</a></td><td><a href='javascript:sort_on_fly (3)' class=bnick>a-z</a></td></tr></table>";
}

function show_list(sort_type,hr)
{
 var ch = document.getElementById('ch');
 var text;
 sort_type = parseInt(sort_type);
 if (sort_type == 0) sort_type == 2;
 text = '<center class=nums><font class=cord>['+xy+']</font> <a href=ch.php?view=this&sort='+sort_type+'&'+MYrand()+'>'+locname+'</a>:'+zds+' <a href=ch.php?view=all&sort='+sort_type+'&'+MYrand()+'>из</a> '+vsg+'</center> <table border="0" width="100%" cellspadding=0 cellspacing="0">';
 if (zds==0 && vsg==0) text = '<center>'+locname+'</center>';
 var i;
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
 var inviz='';
 var uninviz='';
 if (arr[0].indexOf('n=')>-1)
 {arr[0]=arr[0].replace('n=','');inviz='<i>';uninviz='</i>';}
 if (hr!=1)s = '<td width="10%" align="center"><img src=images/pn.gif onclick="javascript:top.say_private(\''+arr[0]+'\',1)" style="cursor:pointer" title="Приватное сообщение"> </td>';
 s += ' <td align="center"><img src=images/signs/'+arr[2]+'.gif title=\''+arr[3]+'\'>'+inviz+'<font class=user onclick="javascript:top.say_private(\''+arr[0]+'\')" title="Сообщение" style="cursor:pointer">'+arr[0].substr(0,12)+'</font>'+uninviz+'[<font class=lvl>'+arr[1]+'</font>] <img src=images/in.gif onclick="javascript:window.open(\'info.php?p='+arr[0]+'\',\'_blank\')" style="cursor:pointer"></td><td width=20%>';
 if (arr[4]!='') s+=' <img src=images/signs/molch.gif title="Заклинание молчания">';
 if (arr[5]!='') s+=' <img src=images/signs/travm.gif title="'+arr[5]+'">';
 if (arr[8]!='') s+=' <img src=images/art.gif title="Создатели/'+arr[8]+'"  style="cursor:pointer">';
 if (arr[7]!='') s+=' <img src=images/signs/ignore.gif title="Снять игнорирование"  style="cursor:pointer"" onclick="location=\'ch.php?ignore_unset='+arr[0]+'&'+MYrand()+'\'">';
 if (arr[6]==1) s+=' <img src=images/signs/diler.gif title="Официальный дилер проекта">';
 s+='</td></tr>';
 return s;
}

function sortnum(list1)
{
	var tmp = '';
	var w1,w2;
	for (var i=0;i<list1.length;i++)
	 for (var j=0;j<list1.length-1;j++)
		{
			w1 = list1[j].split('|'); w1 = parseInt(w1[1]);
			w2 = list1[j+1].split('|');w2 = parseInt(w2[1]);
			if (w1>w2) 
			 {
			 tmp = list1[j];
			 list1[j] = list1[j+1];
			 list1[j+1]=tmp;
			 }
		}
	list = list1;
	list1 = 0;
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