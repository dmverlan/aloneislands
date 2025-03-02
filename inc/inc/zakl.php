<?
if ($id<>''){
$m_str1 = '';
$m_str2 = '';
if ($type<>'all' and $type<>'other') $m_str1 = "`type`='".$type."'"; elseif($type=='other') 
$m_str1 = "`type`<>'blast' and`type`<>'aura' and `type`<>'blast2' and`type`<>'aura2'";

if ($stype<>'all') $m_str2 = "`stype`='".$stype."'";

if ($m_str1<>'' and $m_str2<>'') $m_str = $m_str1." and ".$m_str2;
if ($m_str1=='' and $m_str2<>'') $m_str = $m_str2;
if ($m_str1<>'' and $m_str2=='') $m_str = $m_str1;
if ($m_str1=='' and $m_str2=='') $m_str = ""; else $m_str = " and ".$m_str;

$zak=mysql_fetch_array(sql ("SELECT * FROM `zakl` WHERE `id`='".$id."'".$m_str.";"));
}
if ($zak["type"]=='aura2' or $zak["type"]=='push' or $zak["type"]=='blast2') $proc = '%'; else $proc = '';
$v = $zak;
$key='s1';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='s2';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='s3';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='s4';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='s5';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='s6';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='mf1';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='mf2';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='mf3';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='mf4';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='mf5';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='kb';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='hp';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
$key='ma';if ($v[$key]>0) $v[$key]="+".$v[$key].$proc;
if ($zak["c_c"]<1)$zak["c_c"]=1;
if (@$zak["name"]<>''){
$tmptype = $zak["type"];
if ($zak["type"]=='visov') $zak["type"]= 'Вызов существа';
if ($zak["type"]=='blast') $zak["type"]= 'Магический удар';
if ($zak["type"]=='blast2') $zak["type"]= 'Магический удар';
if ($zak["type"]=='aura' or $zak["type"]=='aura2') $zak["type"]= 'Аура';
if ($zak["type"]=='freeze') $zak["type"]= 'blind';
if ($zak["type"]=='health') $zak["type"]= 'Лечение';
if ($zak["type"]=='push') $zak["type"]= 'Магический толчок';
if ($zak['t_in_c']>0)$zak['time']=$zak['t_in_c'].' ход.'; else $zak['time']=tp($zak['time']);
if ($zak['t_in_c']==99999) $zak['time']='До конца боя';

echo '
<table border="0" cellspacing="0" cellpadding="0" width=100%>
	<tr>
		<td rowspan="4" width="2%">
		<img border="0" src="images/design/abils/bbrdr.gif" width="14" height="172"></td>
		<td colspan="3">
		<img border="0" src="images/design/abils/tbrdr.gif" width="100%" height="6"></td>
		<td rowspan="4" width="2%">
		<img border="0" src="images/design/abils/bbrdr.gif" width="14" height="172"></td>
	</tr>
	<tr>
		<td height="160" rowspan="2" width="24%">'.$zak["name"].'
		<table border="0" width="90" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" width=90>
				<img border="0" src="images/design/abils/zup.gif" width="90" height="16"></td>
			</tr>
			<tr>
				<td rowspan="2" width="23">
				<img border="0" src="images/design/abils/zleft.gif" width="23" height="76"></td>
				<td width=47><img src="images/zakl/'.$zak["image"].'"></td>
				<td width="20">
				<img border="0" src="images/design/abils/zright.gif" width="20" height="61"></td>
			</tr>
			<tr>
				<td colspan="2" width=68>
				<img border="0" src="images/design/abils/zbottom.gif" width="68" height="15"></td>
			</tr>
		</table>
		</td>
		<td height="17" class="brdr" align="center" width="49%">свойства</td>
		<td height="17" class="brdr" align="center" width="21%">требования</td>
	</tr>
	<tr>
		<td height="143" width="49%" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-width: 0px; border-bottom-width: 0px; border-color:#CCCCCC"><i>'.$zak["describe"]."</i><hr>Тип: <b>".$zak["type"]."</b><br>Стоимость использования: <font class=ma>".$zak["mana"]." MA</font>";
		if ($tmptype=="aura" or $tmptype=="aura2" or $tmptype=="freeze") {
		echo "<br>Время Действия: <font class=time>".$zak['time']."</font><font size=2>";
		if ($v["hp"]<>0) echo "<br><font class=hp>".$v["hp"]."HP</font>";
		if ($v["ma"]<>0) echo "<br><font class=ma>".$v["ma"]."MA</font>";
		echo "<table cellpadding=0 cellspasing=0 style=\"border-width: 0px;\" class=items>";
		if ($v["s1"]<>0) echo "<tr><td>Сила:</td><td><b>".$v["s1"]."</b></td></tr>";
		if ($v["s2"]<>0) echo "<tr><td>Реакция:</td><td><b>".$v["s2"]."</b></td></tr>";
		if ($v["s3"]<>0) echo "<tr><td>Удача:</td><td><b>".$v["s3"]."</b></td></tr>";
		if ($v["s4"]<>0) echo "<tr><td>Здоровье:</td><td><b>".$v["s4"]."</b></td></tr>";
		if ($v["s5"]<>0) echo "<tr><td>Интелект:</td><td><b>".$v["s5"]."</b></td></tr>";
		if ($v["s6"]<>0) echo "<tr><td>Сила Воли:</td><td><b>".$v["s6"]."</b></td></tr>";
		if ($v["kb"]<>0) echo "<tr><td class=mf>Класс брони</td><td><b>".$v["kb"]."</b></td></tr>";
		if ($v["mf1"]<>0) echo "<tr><td><font class=mf>Сокрушение: </font></td><td><b>".$v["mf1"]."</b></td></tr>";
		if ($v["mf2"]<>0) echo "<tr><td><font class=mf>Уловка:</font></td><td><b>".$v["mf2"]."</b></td></tr>";
		if ($v["mf3"]<>0) echo "<tr><td><font class=mf>Точность:</font></td><td><b>".$v["mf3"]."</b></td></tr>";
		if ($v["mf4"]<>0) echo "<tr><td><font class=mf>Стойкость:</font></td><td><b>".$v["mf4"]."</b></td></tr>";
		if ($v["mf5"]<>0) echo "<tr><td><font class=mf>Ярость:</font></td><td><b>".$v["mf5"]."</b></td></tr>";
		echo "</table>";
	}
if ($v["udmax"]<>0 and $v["type"]<>"health")	echo "<br><font class=items>Удар: <b>".$v["udmin"].$proc."-".$v["udmax"].$proc."</b></font>";	
		echo "<br><font class=items>Количество целей: <b>".$zak["c_c"]."</b></font>";
		if ($v["stype"]=='light_necr') echo "<br><font class=items><b>Религия</b></font>";
		if ($v["stype"]=='dark_necr') echo "<br><font class=items><b>Некромантия</b></font>";
		if ($v["stype"]=='elements') echo "<br><font class=items><b>Магия стихии</b></font>";
		if ($v["stype"]=='call') echo "<br><font class=items><b>Вызов существ</b></font>";
		if ($v["stype"]=='order') echo "<br><font class=items><b>Магия порядка</b></font>";
		echo '</td>
		<td height="143" width="21%">';
$z = 1;
echo "<table cellpadding=0 style=\"border-width: 0px;\" class=items>";
if ($pers["level"]<$v["tlevel"]) {$p="red";$z=0;} else $p="green";
echo "<tr><td>Уровень:</td><td><font class=".$p."><b>".$zak["tlevel"]."</b></font></td></tr>";
if ($pers["s6"]<$v["ts6"]) {$p="red";$z=0;} else $p="green";
if ($v["ts6"]>0) echo "<tr><td>Сила Воли:</td><td><font class=".$p."><b>".$v["ts6"]."</b></font></td></tr>";
echo "</table>";
echo '</td>
	</tr>
	<tr>
		<td colspan="3">
		<img border="0" src="images/design/abils/tbrdr.gif" width="100%" height="6"></td>
	</tr>
</table>
';


###
/*
echo "<table style=\"border-style: solid; border-width: 1px\" width=80%>
	<tr>
		<td width=20%><b><font class=items>".$zak["name"]."</font></b></td>
		<td class=fightup><b><u>свойства</u></b></td><td class=fightup width=20%><b><u>требования<u></b></td>
	</tr>
	<tr>
		<td align=\"center\"><img src='images/zakl/".$zak["image"]."'></td>
		<td style=\"border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px\" class=items><i>".$zak["describe"]."</i><hr>Тип: <b>".$zak["type"]."</b><br>Стоимость использования: <font class=ma>".$zak["mana"]." MA</font>";
		if ($tmptype=="aura" or $tmptype=="aura2" or $tmptype=="freeze") {
		echo "<br>Время Действия: <font class=time>".$zak['time']."</font><font size=2>";
		if ($v["hp"]<>0) echo "<br><font class=hp>".$v["hp"]."HP</font>";
		if ($v["ma"]<>0) echo "<br><font class=ma>".$v["ma"]."MA</font>";
		echo "<table cellpadding=0 cellspasing=0 style=\"border-width: 0px;\" class=items>";
		if ($v["s1"]<>0) echo "<tr><td>Сила:</td><td><b>".$v["s1"]."</b></td></tr>";
		if ($v["s2"]<>0) echo "<tr><td>Реакция:</td><td><b>".$v["s2"]."</b></td></tr>";
		if ($v["s3"]<>0) echo "<tr><td>Удача:</td><td><b>".$v["s3"]."</b></td></tr>";
		if ($v["s4"]<>0) echo "<tr><td>Здоровье:</td><td><b>".$v["s4"]."</b></td></tr>";
		if ($v["s5"]<>0) echo "<tr><td>Интелект:</td><td><b>".$v["s5"]."</b></td></tr>";
		if ($v["s6"]<>0) echo "<tr><td>Сила Воли:</td><td><b>".$v["s6"]."</b></td></tr>";
		if ($v["kb"]<>0) echo "<tr><td class=mf>Класс брони</td><td><b>".$v["kb"]."</b></td></tr>";
		if ($v["mf1"]<>0) echo "<tr><td><font class=mf>Сокрушение: </font></td><td><b>".$v["mf1"]."</b></td></tr>";
		if ($v["mf2"]<>0) echo "<tr><td><font class=mf>Уловка:</font></td><td><b>".$v["mf2"]."</b></td></tr>";
		if ($v["mf3"]<>0) echo "<tr><td><font class=mf>Точность:</font></td><td><b>".$v["mf3"]."</b></td></tr>";
		if ($v["mf4"]<>0) echo "<tr><td><font class=mf>Стойкость:</font></td><td><b>".$v["mf4"]."</b></td></tr>";
		if ($v["mf5"]<>0) echo "<tr><td><font class=mf>Ярость:</font></td><td><b>".$v["mf5"]."</b></td></tr>";
		echo "</table>";
	}
if ($v["udmax"]<>0 and $v["type"]<>"health")	echo "<br><font class=items>Удар: <b>".$v["udmin"].$proc."-".$v["udmax"].$proc."</b></font>";	
		echo "<br><font class=items>Количество целей: <b>".$zak["c_c"]."</b></font>";
		if ($v["stype"]=='light_necr') echo "<br><font class=items><b>Религия</b></font>";
		if ($v["stype"]=='dark_necr') echo "<br><font class=items><b>Некромантия</b></font>";
		if ($v["stype"]=='elements') echo "<br><font class=items><b>Магия стихии</b></font>";
		if ($v["stype"]=='call') echo "<br><font class=items><b>Вызов существ</b></font>";
		if ($v["stype"]=='order') echo "<br><font class=items><b>Магия порядка</b></font>";
echo"</td><td width=20%>";
$z = 1;
echo "<table cellpadding=0 style=\"border-width: 0px;\" class=items>";
if ($pers["level"]<$v["tlevel"]) {$p="red";$z=0;} else $p="green";
echo "<tr><td>Уровень:</td><td><font class=".$p."><b>".$zak["tlevel"]."</b></font></td></tr>";
if ($pers["s6"]<$v["ts6"]) {$p="red";$z=0;} else $p="green";
if ($v["ts6"]>0) echo "<tr><td>Сила Воли:</td><td><font class=".$p."><b>".$v["ts6"]."</b></font></td></tr>";
echo "</table>";
echo "</td></tr></table>"; */
$zak["type"] = $tmptype;
}
?>
