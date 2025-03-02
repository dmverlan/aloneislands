function show_w (name,sht,img,d,m_d,cena,pric,dprice,art,attr,describe,present,clan_sign,clan_name,slots,radius,arrows,arrows_max,arrow_name,z_time,weight,index,trbs)
{
var text = '';
if (sht==1 || sht=='') sht=''; else sht = sht+'шт.';
text +=  ('<table width=100% cellpadding="0" cellspacing=0 border=0 Style="background-image:url(\'images/DS/chat_bg.jpg\')"><tr><td class=mfb width=50% height=10 align=center Style="background-image:url(\'images/bg.png\')">свойства</td><td width=70 align="center" height=10 Style="background-image:url(\'images/bg.png\')">&nbsp;</td><td width=40% class=mfb align=center height=10 Style="background-image:url(\'images/bg.png\')">требования</td></tr><tr>');
text +=  ('<td>');		

text +=  ('<b class=User>'+name+'</b><br><div>Цена: '+cena+'</div>');
if (art==1 && present=='') text +=  ('<br><img src=images/art.gif><font class=hp>Особая вещь</font>');
else if (present!='') text +=  ('<br><img src=images/art.gif>Подарок от <b>'+present+'</b>');
text +=  (attr);
if (slots!=0) text +=  ('<font class=items> Слотов для заклинаний или рун: <b>'+slots+'</b></font><br>');
if (radius!=0) text +=  ('<font class=items> Радиус поражения: <b>'+radius+'</b></font><br>');
if (arrows_max) text +=  ('<font class=items> Заряды: <b>'+arrows+'</b></font><br>');
if (arrows_max) text +=  ('<font class=items> Вмещаемость: <b>'+arrows_max+'</b></font><br>');
if (arrows_max) text +=  ('<font class=items> Тип заряда: <b>'+arrow_name+'</b></font><br>');

if (describe!='') text +=  ("<br><center class=but>"+describe+"</center>");
if (z_time!=0)   text +=  ("<br><font class=time>Время Действия: <font class=timef>"+z_time+"</font></font>");
text +=  ('</td>');
		text += ('<td align="center" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px; border-color:silver"><img src="images/weapons/'+img+'.gif"><div id=\''+img+'_'+d+'_'+pric+'_'+index+'\' class=time>'+sht+'</div>');
		if (m_d!=0 && d>0) 
		{
			text += ('<div><img src="images/DS/expline.gif" height=3 width='+(62*d/m_d)+'><img src="images/DS/expline_empty.gif" height=3 width='+(62-62*d/m_d)+'></div>'); 
			text += ('<font class=time>['+d+'/'+m_d+']</font>'); 
		}
		else if (m_d==0) text += ('<br><font class=time>вечная вещь</font>'); 
		else if (m_d!=0 && d==0) text += ('<br><font class=time>иcпорчена</font>'); 
		text += ('</td>');
text += ('<td>'+trbs+'</td></tr></table>');

document.write(text);
}