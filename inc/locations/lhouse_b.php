<center><?
$explain[1] = "Увеличенный урон";
$explain[2] = "Увеличенный шанс сокрушительного удара";
$explain[3] = "Увеличенная точность";
$explain[4] = "Выжигание маны";

echo '<table border="0" width="700" cellspacing="9" cellpadding="0" class=weapons_box> <tr> <td align="center"><img src=images/locations/university.jpg width=600></td> </tr>'; 	
echo "<tr>";
echo "<td class=but align=center>";
echo "<table style='width: 100%' border=0 cellspasing=1>
	<tr>
		<td style='width: 25%' class=but2><a href='javascript:void(0)' ".build_go_string('lhouse_t',$lastom_new)." class=bg>Технический факультет</a></td>
		<td style='width: 25%' class=but2><a href='javascript:void(0)' ".build_go_string('lhouse_m',$lastom_new)." class=bg>Магический факультет</a></td>
		<td style='width: 25%' class=but2><a href='javascript:void(0)' ".build_go_string('lhouse_b',$lastom_new)." class=bg>Боевые искусства</a></td>
		<td style='width: 25%' class=but2><a href='javascript:void(0)' ".build_go_string('lhouse_p',$lastom_new)." class=bg>Мирные умения</a></td>
	</tr>
</table>
";

echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
######
if (empty($_GET["learn"]))
echo "<p class=items>Приветствуем вас в нашем университете! Вы решили посетить факультет боевых искусств? Тогда вы пришли правильно! У вас с собой <b>".$pers["coins"]."</b> пергаментов.</p><hr>";

{
if ($pers["waiter"]<tme())
{
if (@$_GET["learn"])
{
	$cat = intval($_GET["learn"]);
	$your = sqla("SELECT * FROM u_special_dmg WHERE uid=".$pers["uid"]." and wod=".$cat."");
	$def = sqla("SELECT * FROM special_dmg WHERE od=".$cat."");
	$z = 0;
	if ($your)
	{
		$step = intval($your["od"]-$your["wod"]/10+1);
		$money = $step*$step*100;
		$ps = $your["coins"]*$def["coins"]/10;
		if ($money<=$pers["money"] and $ps<=$pers["coins"])
		{
			sql("UPDATE _special_dmg SET od=od+10,value=value+".$def["value"]." 
			WHERE uid=".$pers["uid"]." and wod=".$cat."");
			echo "Вы успешно начали улучшать \"".$def["name"]."\"";
			$z = 1;
		}
	}
	else
	{
		$money = 100;
		$ps = $def["coins"];
		if ($money<=$pers["money"] and $ps<=$pers["coins"])
		{
			sql("INSERT INTO `u_special_dmg` ( `uid` , `od` , `name` , `type` , `value` , `coins` , `wod` )
VALUES (
'".$pers["uid"]."', '".$def["od"]."', '".$def["name"]."', '".$def["type"]."', '".$def["value"]."', '".$def["coins"]."', '".$def["od"]."');");
			echo "Вы успешно начали изучать \"".$def["name"]."\"";
			$z = 1;
		}
	}
	
	if ($z)
	{
	$your = sqla("SELECT * FROM u_special_dmg WHERE uid=".$pers["uid"]." and wod=".$cat."");
	echo "<p class=submit>Описание приёма:<br>";
	echo "<b>\"".$your["name"]."\"</b> требует для использования ".$your["od"]." очков действия.<br>";
	echo "Польза: <b>".$explain[$def["type"]]." ".$your["value"];
	if ($def["type"]<4) 
	 echo "%";
	echo "</b>";
	echo "</p>";
	set_vars("money=money-".$money.",coins=coins-".$ps.",waiter=".tme()."+600",$pers["uid"]);
	$pers["waiter"] = tme()+600;
	echo "<div id=waiter class=but align=center></div>";
	echo "<script>waiter(600);</script>";
	}

}
else
{
echo "Наши преподаватели готовы обучить вас многим боевым приёмам! Они попросят небольшое количество денег за это, но мы надеемся вас это не затруднит. Для обучения вам понадобятся пергаменты.";
echo "<center><table style='width: 80%' border=0 cellspasing=1 class=but>";
$spcls = sql("SELECT * FROM special_dmg");
while($sp = mysql_fetch_array($spcls,MYSQL_ASSOC))
{
	echo "<tr><td class=but2><a href=main.php?cat=".$sp["od"]." class=bg>".$sp["name"]."</a></td></tr>";
}
echo "</table></center>";

if (@$_GET["cat"])
{
	$cat = intval($_GET["cat"]);
	$your = sqla("SELECT * FROM u_special_dmg WHERE uid=".$pers["uid"]." and wod=".$cat."");
	$def = sqla("SELECT * FROM special_dmg WHERE od=".$cat."");
	if ($your)
	{
		echo "Вы уже владете данным приёмом. У вас есть возможность улучшить этот приём.<br>";
		$step = intval($your["od"]-$your["wod"]/10+1);
		$money = $step*$step*100;
		$ps = $your["coins"]*$def["coins"]/10;
		echo "Стоимость обучения составит <b>".$money."</b> LN и <b>".$ps."</b> пергаментов.";
		echo "<center>";
		echo "<p class=submit style='width:60%'>Пояснение к улучшению:<br>Улучшенный приём будет требовать на 10 очков действия больше. Эффект приёма увеличится на ".$def["value"]."</p>";
		echo "</center>";
	}
	else
	{
		echo "Вы сможете отлично владеть данным приёмом если выучите его у нас!<br>";
		$money = 100;
		$ps = $def["coins"];
		echo "Стоимость обучения составит <b>".$money."</b> LN и <b>".$ps."</b> пергаментов.";
	}
	
	echo "<p class=submit>Описание приёма:<br>";
	echo "<b>\"".$def["name"]."\"</b> требует для использования ".$def["od"]." очков действия.<br>";
	echo "Польза: <b>".$explain[$def["type"]]." ".$def["value"];
	if ($def["type"]<4) 
	 echo "%";
	echo "</b>";
	echo "</p>";
	
	if ($pers["money"]<$money)
	 echo "<center class=puns>Не хватает денег</center>";
	elseif ($pers["coins"]<$ps)
	 echo "<center class=puns>Не хватает пергаментов</center>";
	else
	{
		echo "<center class=but><input class=inv_but type=button value='Начать обучение [10 минут]' style='width:80%' onclick=\"location='main.php?learn=".$cat."'\"></center>";
	}
}
}
}
else
{
		echo "<div id=waiter class=but align=center></div>";
		echo "<script>waiter(".($pers["waiter"]-tme()).");</script>";
}
}

######
echo "</td>";
echo "</tr>";
echo '</table>'; 
?></center>