<center><?
$sps = Array("sp1","sp2","sp5","sp6","sp7","sp9","sp11","sp12","sp13");
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
#########
$cat = base64_decode($_GET["zero_skill"]);
if (isset($_GET["zero_skill"]) and substr_count(implode("@",$sps)."@",$cat."@") and $pers["skill_zeroing"])
{
	$wp_sk = intval(sqlr("SELECT SUM(`".$cat."`) FROM wp WHERE uidp=".$pers["uid"]." and weared=1"));
	$pers[$cat] -= $wp_sk;
	$kolvo = round(((1+($pers[$cat]-10)/10)/2)*($pers[$cat]/40));
	set_vars("coins=coins+".$kolvo." , `".$cat."`=".$wp_sk." , skill_zeroing=skill_zeroing-1",UID);
	echo "<center class=but><b class=green>".name_of_skill($cat)." удачно обнулен.</b></center>";
	$pers["coins"] += $kolvo;
	$pers["skill_zeroing"]--;
	$pers[$cat] = $wp_sk;
}
#########
######
if (empty($_GET["learn"]))
echo "<p class=items>Приветствуем вас в нашем университете! Вы решили посетить факультет мирных умений? Тогда вы пришли правильно! У вас с собой <b>".$pers["coins"]."</b> пергаментов.</p><hr>";
if ($pers["waiter"]<tme())
{
if (@$_GET["learn"] and substr_count(implode("@",$sps)."@",$_GET["learn"]."@"))
{
	$cat = $_GET["learn"];
	$wp_sk = intval(sqlr("SELECT SUM(`".$cat."`) FROM wp WHERE uidp=".$pers["uid"]." and weared=1"));
	$pers[$cat] -= $wp_sk;
	$ps = floor($pers[$cat]/10+1);

	if ($pers["money"]<intval($pers[$cat]))
	 echo "<center class=puns>Не хватает денег</center>";
	elseif ($pers["coins"]<$ps)
	 echo "<center class=puns>Не хватает пергаментов</center>";
	elseif (intval($pers[$cat]+10)>(100+70*$pers["level"]))
	 echo "<center class=puns>Вы слишком умны в этой сфере! Подходите когда получите уровень.</center>";
	else
	{
		echo "Вас обучают: \"<b>".name_of_skill($cat)."</b>\".";
		echo "Стоимость обучения составила <b>".intval($pers[$cat])."</b> LN и <b>".$ps."</b> пергаментов.";
		$cat = 'sp'.intval(str_replace('sp','',$cat));
		set_vars("`".$cat."`=`".$cat."`+20,money=money-".intval($pers[$cat]).",coins=coins-".$ps.",waiter=".tme()."+300"
		,$pers["uid"]);
		$pers["waiter"] = tme()+300;
		echo "<div id=waiter class=but align=center></div>";
		echo "<script>waiter(300);</script>";
	}
}
else
if (@$_GET["Dlearn"] and substr_count(implode("@",$sps)."@",$_GET["learn"]."@"))
{
	$cat = $_GET["Dlearn"];
	$wp_sk = intval(sqlr("SELECT SUM(`".$cat."`) FROM wp WHERE uidp=".$pers["uid"]." and weared=1"));
	$pers[$cat] -= $wp_sk;

	if ($pers["dmoney"]<3)
	 echo "<center class=puns>Не хватает денег</center>";
	elseif (intval($pers[$cat]+10)>(100+70*$pers["level"]))
	 echo "<center class=puns>Вы слишком умны в этой сфере! Подходите когда получите уровень.</center>";
	else
	{
		echo "Вас обучают: \"<b>".name_of_skill($cat)."</b>\".";
		echo "Стоимость обучения составила <b>3</b> БР.";
		$cat = 'sp'.intval(str_replace('sp','',$cat));
		set_vars("`".$cat."`=`".$cat."`+100,dmoney=dmoney-3",$pers["uid"]);
		$pers["waiter"] = tme()+4;
		echo "<div id=waiter class=but align=center></div>";
		echo "<script>waiter(4);</script>";
	}
}
else
{
echo "Наши преподаватели готовы обучить вас многим мирным профессиям и умениям! Они попросят небольшое количество денег за это, но мы надеемся вас это не затруднит. Для обучения вам понадобятся пергаменты.";
echo "<center><table style='width: 60%' border=0 cellspasing=1 class=but>";
foreach($sps as $value)
{
	$wp_sk = intval(sqlr("SELECT SUM(`".$value."`) FROM wp WHERE uidp=".$pers["uid"]." and weared=1"));
	echo "<tr><td style='width: 80%' class=but2><a href=main.php?cat=".$value." class=bg>".name_of_skill($value)."</a></td><td class=but2><i class=timef>".intval($pers[$value]-$wp_sk)."</i></td></tr>";
}
echo "</table></center>";

if (@$_GET["cat"] and substr_count(implode("@",$sps)."@",$_GET["cat"]."@"))
{
	$cat = $_GET["cat"];
	$wp_sk = intval(sqlr("SELECT SUM(`".$cat."`) FROM wp WHERE uidp=".$pers["uid"]." and weared=1"));
	$pers[$cat] -= $wp_sk;
	$ps = floor($pers[$cat]/10+1);
	echo "Вы выбрали категорию \"<b>".name_of_skill($cat)."</b>\".";
	echo "Стоимость обучения составит <b>".intval($pers[$cat])."</b> LN и <b>".$ps."</b> пергаментов.";
	
	if ($pers["money"]<intval($pers[$cat]))
	 echo "<center class=puns>Не хватает денег</center>";
	elseif ($pers["coins"]<$ps)
	 echo "<center class=puns>Не хватает пергаментов</center>";
	elseif (intval($pers[$cat]+10)>(100+70*$pers["level"]))
	 echo "<center class=puns>Вы слишком умны в этой сфере! Приходите когда получите уровень.</center>";
	else
	{
		if(!mtrunc($pers[$cat]))
		if($pers["dmoney"]>=3)
			echo "<center class=but><input class=inv_but type=button value='Учить 100 умений [Моментально] за 3 БР' style='width:80%' onclick=\"location='main.php?Dlearn=".$cat."'\"></center>";
		else
			echo "<center class=but><input class=inv_but type=button value='Учить 100 умений [Моментально] за 3 БР' style='width:80%' onclick=\"location='main.php?Dlearn=".$cat."'\" DISABLED></center>";
		echo "<center class=but><input class=inv_but type=button value='Учить 20 умений [5 минут]' style='width:80%' onclick=\"location='main.php?learn=".$cat."'\"></center>";
	}
	
	if ($pers["skill_zeroing"])
	{
		$kolvo = round(((1+($pers[$cat]-10)/10)/2)*($pers[$cat]/40));
		echo "<center class=but><a class=timef href='main.php?zero_skill=".base64_encode($cat)."'>Обнулить</a>[<b>".$pers["skill_zeroing"]."</b>] <I>".name_of_skill($cat)."</I>. Вернется <b>".$kolvo."</b> пергаментов.</center>";
	}
}
}
}
else
{
		echo "<div id=waiter class=but align=center></div>";
		echo "<script>waiter(".($pers["waiter"]-tme()).");</script>";
}

######
echo "</td>";
echo "</tr>";
echo '</table>'; 
?></center>