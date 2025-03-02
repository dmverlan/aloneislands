<table border=0 width=100% bgcolor=#F5F5F5 class=LinedTable cellspacing=0>
<tr><td colspan=6><? if (($pers["free_f_skills"] + $pers["free_p_skills"] + $pers["free_m_skills"])>0) echo "<center><b><a href='javascript:document.ym.submit()' class=but>Сохранить</a></b></center>"; ?></td></tr>
<? if ($pers['free_f_skills']<>0)echo '<tr bgcolor="D5D5D5"><td align="center"><i class=user>Свободные боевые</i></td><td class=babout><div id=nymen class=user></div></td></tr>';?>
<? if ($pers['free_m_skills']<>0)echo '<tr ><td align="center"><i class=user>Свободные второстепенные</i></td><td class=babout><div id=nsymen class=user></div></td></tr>';?>
<tr>
<td colspan="2" class=but align=center><b class=user>Боевые Умения</b></td>
</tr>
<?

$_sb1 = "Позволяет совершать больше движений в бою.";
$_sb2 = "Увеличивает базовый физический удар персонажа в бою за 1 пункт на 1%.";
$_sb3 = "Каждая единица умения увеличивает базовый удар ножа на 0.5%.";
$_sb4 = "Увеличивает защиту Вашего щита на 3% за единицу изученного умения.";
$_sb5 = "Каждая единица умения увеличивает базовый удар меча на 0.5%.";
$_sb6 = "Каждая единица умения увеличивает базовый удар топора на 0.5%.";
$_sb7 = "Каждая единица умения увеличивает базовый удар булавы на 0.5%.";
$_sb8 = "Позволяет использовать в бою книгу.";
$_sb9 = "Увеличивает магический урон на 2% за единицу изученного умения.";

$_sm1 = "Увеличивает количество ваших жизненных сил. Одно повышенное умение = 4 HP";
$_sm2 = "Увеличивает количество вашей магической энергии. Одно повышенное умение = 3 MP";
$_sm3 = "Увеличивает максимальную вместимость вашего рюкзака";
$_sm4 = "Недоступно";
$_sm5 = "Недоступно";
$_sm6 = "Увеличивает скорость восстановления ваших жизненных сил";
$_sm7 = "Увеличивает скорость восстановления вашей магической энергии";

$worked = '<b class=green>*</b>';
$nworked = '<b class=red>*</b>';
	for($i=1;$i<=9;$i++)
	{
		echo "<tr>";
		echo "<td width='70%' height=20 onmouseover=\"s_des(event,'|<b>".${"_sb".$i}."</b>')\" onmouseout='h_des()' onmousemove=move_alt(event)>".$worked.name_of_skill("sb".$i)."</td>";
		echo "<td width='30%' height=20 align=right><div id=b".$i."></div></td>";
		echo "</tr>";
	}
?>
<tr>
<td colspan="2" class=but align=center><b class=user>Второстепенные умения</b></td>
</tr>
<?
	for($i=1;$i<=7;$i++)
	{
		echo "<tr>";
		if($i!=4 and $i!=5)
			echo "<td width='70%' height=20 onmouseover=\"s_des(event,'|<b>".${"_sm".$i}."</b>')\" onmouseout='h_des()' onmousemove=move_alt(event)>".$worked.name_of_skill("sm".$i)."</td>";
		else
			echo "<td width='70%' height=20 onmouseover=\"s_des(event,'|<b>".${"_sm".$i}."</b>')\" onmouseout='h_des()' onmousemove=move_alt(event)>".$nworked.name_of_skill("sm".$i)."</td>";
		echo "<td width='30%' height=20 align=right><div id=s".$i."></div></td>";
		echo "</tr>";
	}
?>
<tr>
<td colspan="2" class=but align=center><b class=user>Сопротивления</b></td>
</tr>
<tr >
<td width="70%"><?=$worked.name_of_skill("sb10");?></td>
<td width="30%" align=right><div id=b10></div></td>
</tr>
<tr>
<td width="70%"><?=$worked.name_of_skill("sb11");?></td>
<td width="30%" align=right><div id=b11></div></td>
</tr>
<tr >
<td width="70%"><?=$nworked.name_of_skill("sb12");?></td>
<td width="30%" align=right><div id=b12></div></td>
</tr>
<tr>
<td width="70%"><?=$nworked.name_of_skill("sb13");?></td>
<td width="30%" align=right><div id=b13></div></td>
</tr>
<tr >
<td width="70%"><?=$nworked.name_of_skill("sb14");?></td>
<td width="30%" align=right><div id=b14></div></td>
</tr>
<tr>
<td colspan="2" class=but align=center><b class=user>Мирные умения</b></td>
</tr>
<tr >
<td width="70%"><?=$worked.name_of_skill("sp1");?></td>
<td width="30%" align=right><div id=m1></div></td>
</tr>
<tr>
<td width="70%"><?=$worked.name_of_skill("sp2");?></td>
<td width="30%" align=right><div id=m2></div></td>
</tr>
<tr >
<td width="70%"><?=$nworked.name_of_skill("sp3");?></td>
<td width="30%" align=right><div id=m3></div></td>
</tr><tr>
<td width="70%"><?=$nworked.name_of_skill("sp4");?></td>
<td width="30%" align=right><div id=m4></div></td>
</tr><tr >
<td width="70%"><?=$worked.name_of_skill("sp5");?></td>
<td width="30%" align=right><div id=m5></div></td>
</tr><tr>
<td width="70%"><?=$worked.name_of_skill("sp6");?></td>
<td width="30%" align=right><div id=m6></div></td>
</tr><tr >
<td width="70%"><?=$worked.name_of_skill("sp7");?></td>
<td width="30%" align=right><div id=m7></div></td>
</tr><tr>
<td width="70%"><?=$worked.name_of_skill("sp8");?></td>
<td width="30%" align=right><div id=m8></div></td>
</tr><tr >
<td width="70%"><?=$worked.name_of_skill("sp9");?></td>
<td width="30%" align=right><div id=m9></div></td>
</tr>
<tr>
<td width="70%"><?=$worked.name_of_skill("sp10");?></td>
<td width="30%" align=right><div id=m10></div></td>
</tr>
<tr >
<td width="70%"><?=$worked.name_of_skill("sp11");?></td>
<td width="30%" align=right><div id=m11></div></td>
</tr>
<tr>
<td width="70%"><?=$worked.name_of_skill("sp12");?></td>
<td width="30%" align=right><div id=m12></div></td>
</tr>
<tr >
<td width="70%"><?=$worked.name_of_skill("sp13");?></td>
<td width="30%" align=right><div id=m13></div></td>
</tr>
<tr>
<td width="70%"><?=$worked.name_of_skill("sp14");?></td>
<td width="30%" align=right><div id=m14></div></td>
</tr>
<tr><td colspan=6><? if (($pers["free_f_skills"] + $pers["free_p_skills"] + $pers["free_m_skills"])>0) echo "<center><b><a href='javascript:document.ym.submit()' class=bga>Сохранить</a></b></center>"; ?></td></tr>
</table>
<form action='main.php?gopers=um' method=post name=ym>
<?
	for ($i=1;$i<=14;$i++)
	echo "<input type=hidden name=bs".$i." id=bs".$i." value=".floor($pers["sb".$i])."><input type=hidden name=bf".$i." id=bf".$i." value=".floor($pers["sb".$i]).">";
	for ($i=1;$i<=14;$i++)
	echo "<input type=hidden name=ms".$i." id=ms".$i." value=".floor($pers["sp".$i])."><input type=hidden name=mf".$i." id=mf".$i." value=".floor($pers["sp".$i]).">";
	for ($i=1;$i<=7;$i++)
	echo "<input type=hidden name=ss".$i." id=ss".$i." value=".floor($pers["sm".$i])."><input type=hidden name=sf".$i." id=sf".$i." value=".floor($pers["sm".$i]).">";
?>
<input type=hidden name=nbs id=nbs value=<? echo $pers["free_f_skills"];?>><input type=hidden name=nbh id=nbh value=<? echo $pers["free_f_skills"];?>>
<input type=hidden name=nss id=nss value=<? echo $pers["free_m_skills"];?>><input type=hidden name=nsh id=nsh value=<? echo $pers["free_m_skills"];?>>
<input type=hidden name=hjkl value=1>
</form>
<script>
<?
	echo "var level = ".$pers["level"].";";
?>
s_y();
</script>