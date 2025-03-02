<?
		include("inc/balance.php");
				
		echo '<script type="text/javascript" src="js/adm_bots.js?3"></script>';
		
		if (@$_GET["deletebot"])
		{
			sql("DELETE FROM bots WHERE id>=".$_GET["start"]." and id<=".$_GET["finish"]."");
		}

	if (@$_GET["wear"])
	{
		$p = $_POST;
		for($i=intval($p["minlvl"]);$i<=intval($p["maxlvl"]);$i++)
		{
		$r = _MakeItem($i,$p["class"],$p["stype"],$p["power"],$p["tpower"]);
		if($r===false) continue;
		$r["price"] = CalculatePrice($r);
		$r["name"] = $p["name"];
		$r["image"] = $p["image"];
		$r["id"] = 1;
		
		$bid = sqlr("SELECT id FROM bots WHERE user='".$p["user"]."' and level=".$i);
		if ($bid)
			insert_wp($r,-1*$bid,-1,1,$p["user"]);	
		}
	}
	
	if (@$_GET["unwear"])
	{
		$p = $_POST;
		for($i=intval($p["minlvl"]);$i<=intval($p["maxlvl"]);$i++)
		{
		$bid = sqlr("SELECT id FROM bots WHERE user='".$p["user"]."' and level=".$i);
		if ($bid)
			sql("DELETE FROM wp WHERE weared=1 and uidp=".(-1*$bid)." and stype='".$p["stype"]."'");
		}
	}
	
	if (@$_GET["attack"])
	{
			$bid = sqlr("SELECT id FROM bots WHERE user='".$_POST["name"]."' and level=".intval($_POST["lvl"])."");
			begin_fight ($pers["user"],"bot=".$bid."|","Тестирующий бой",50,300,1,0);
			echo "<script>location='main.php';</script>";
	}
	if (@$_GET["added"] and $_POST["balance"]!=1)
	{
		$p = $_POST;
		$koef = 1.5;
		$s1 = floor($p["s1"]*$koef);
		$s2 = floor($p["s2"]*$koef);
		$s3 = floor($p["s3"]*$koef);
		$s4 = floor($p["s4"]*$koef);
		$s5 = floor($p["s5"]*$koef);
		$s6 = floor($p["s6"]*$koef);
		$mf1 = floor($p["mf1"]*$koef);
		$mf2 = floor($p["mf2"]*$koef);
		$mf3 = floor($p["mf3"]*$koef);
		$mf4 = floor($p["mf4"]*$koef);
		$mf5 = floor($p["mf5"]*$koef);
		$kb = floor($p["kb"]*$koef);
		$udmin = mtrunc(floor($p["ud"]*$koef-3));
		$udmax = mtrunc(floor($p["ud"]*$koef+3));
		$hp = $s4*5;
		$ma = $s6*9;
		
		$botlastid = sqla("SELECT MAX(id) FROM bots");
		$botlastid = floor($botlastid[0]/100)*100+100;
		
		$exp = explode("_",$p["image"]);
		$pol = substr($p["image"],0,strpos($p["image"],"_"));
		$obr = str_replace($pol."_",'',$p["image"]);
		
		for ($i = 0;$i<=$p["maxlvl"];$i++)
		{
			if ($i>=$p["minlvl"])
			{
				$rank_i = ($s1+$s2+$s3+$s4+$s5+$s6+$kb)*0.3 + ($mf1+$mf2+$mf3+$mf4)*0.03 + ($hp+$ma)*0.04+($udmin+$udmax)*0.3;
				sql ("INSERT INTO `bots` 
(`id`,`user`,`s1`,`s2`,`s3`,`s4`,`s5`,`s6`,`mf1`,`mf2`,`mf3`,`mf4`,`mf5`,`kb`,`hp`,`ma`,`udmin`,`udmax`,`level`,`obr`,`sm4`,`pol`,`id_skin`,`droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`,`rank_i`) 
VALUES 
('".($botlastid+$i-intval($p["minlvl"]))."','".$p["user"]."','".$s1."','".$s2."','".$s3."','".$s4."','".$s5."','".$s6."','".$mf1."','".$mf2."','".$mf3."','".$mf4."','".$mf5."','".$kb."','".$hp."','".$ma."','".$udmin."','".$udmax."','".$i."','".$obr."','2','".$pol."',".intval($p["skin_id"]).",".intval($p["droptype"]).",".intval($p["dropvalue"]).",".intval($p["dropfrequency"]).",".intval($p["magic_resistance"]).",".$rank_i.");");

				echo "<br><font class=user>".$p["user"]."</font>[<font class=lvl>".$i."</font>]<img src=images/info.gif onclick=\"javascript:window.open('binfo.php?".($botlastid+$i-intval($p["minlvl"]))."','_blank')\" style=\"cursor:point\">";
			}
			$koef = 2.5 + $i*$p["power"]/100;
			$s1 = floor($p["s1"]*$koef);
			$s2 = floor($p["s2"]*$koef);
			$s3 = floor($p["s3"]*$koef);
			$s4 = floor($p["s4"]*$koef);
			$s5 = floor($p["s5"]*$koef);
			$s6 = floor($p["s6"]*$koef);
			$mf1 = floor($p["mf1"]*$koef);
			$mf2 = floor($p["mf2"]*$koef);
			$mf3 = floor($p["mf3"]*$koef);
			$mf4 = floor($p["mf4"]*$koef);
			$mf5 = floor($p["mf5"]*$koef);
			$kb = floor($p["kb"]*$koef);
			$udmin = mtrunc(floor($p["ud"]*$koef-3));
			$udmax = mtrunc(floor($p["ud"]*$koef+3));
			$hp = $s4*5;
			$ma = $s6*9;
		}
	echo "<br><a href=main.php?deletebot=1&start=".$botlastid."&finish=".($botlastid+intval($_POST["maxlvl"])-intval($_POST["minlvl"]))." class=timef>Откатить</a>";
	}
	elseif(@$_GET["added"])
	{
		$p = $_POST;
		$class = mtrunc($_POST["class"]-2)+1;
		$botlastid = sqla("SELECT MAX(id) FROM bots");
		$botlastid = floor($botlastid[0]/100)*100+100;
		
		$exp = explode("_",$p["image"]);
		$pol = substr($p["image"],0,strpos($p["image"],"_"));
		$obr = str_replace($pol."_",'',$p["image"]);
		
		for ($i = $p["minlvl"];$i<=$p["maxlvl"];$i++)
		{
			$summ = sqlr("SELECT SUM(stats) FROM exp WHERE level<=".$i)+20;
			$params = Class_Params($i,$class,"mech",$p["power"]/100);
			$params["s1"] += 0.3*$summ;
			$params["s2"] += 0.2*$summ;
			$params["s3"] += 0.2*$summ;
			$params["s4"] += 0.3*$summ;
			$params["hp"] += $params["s4"]*5;
			$params["kb"] += $params["s4"];
			
			
			sql ("INSERT INTO `bots` 
(`id`,`user`,`s1`,`s2`,`s3`,`s4`,`s5`,`s6`,`mf1`,`mf2`,`mf3`,`mf4`,`mf5`,`kb`,`hp`,`ma`,`udmin`,`udmax`,`level`,`obr`,`sm4`,`pol`,`id_skin`,`droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`) 
VALUES 
('".($botlastid+$i-intval($p["minlvl"]))."','".$p["user"]."','".$params["s1"]."','".$params["s2"]."','".$params["s3"]."','".$params["s4"]."',1,1,'".$params["mf1"]."','".$params["mf2"]."','".$params["mf3"]."','".$params["mf4"]."','".$params["mf5"]."','".$params["kb"]."','".$params["hp"]."','9','".($params["udmin"]+1)."','".($params["udmax"]+1)."','".$i."','".$obr."','2','".$pol."',".intval($p["skin_id"]).",".intval($p["droptype"]).",".intval($p["dropvalue"]).",".intval($p["dropfrequency"]).",".intval($p["magic_resistance"]).");");

			echo "<br><font class=user>".$p["user"]."</font>[<font class=lvl>".$i."</font>]<img src=images/info.gif onclick=\"javascript:window.open('binfo.php?".($botlastid+$i-intval($p["minlvl"]))."','_blank')\" style=\"cursor:point\">";
		}
		
	}
			
	if(@$_GET["skin"])
	{
			$BN = sqlr("SELECT user FROM bots WHERE id='".intval($_GET["skin"])."'");
			sql("UPDATE bots SET id_skin=".intval($_POST["skin"])." WHERE user='".$BN."'");
	}
	
	if (@$_GET["add"])
	{
		echo "<script>var image='male_0';</script>";
		echo "<form action=main.php?added=1 method=post><center><table class=but2 border=0 width=60%>";
		echo "<tr>";
		echo "<td class=timef>Название</td><td width=50%><input type=text class=login style='width:100%;' name=user></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Образ</td><td width=50%><input type=hidden name=image id=image value='male_0'><img id=img src='images/persons/male_0.gif' onclick='change_img()' height=80></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Уровни</td><td width=50%><input type=text class=login style='width:40%;' name=minlvl value=0>-<input type=text class=login style='width:40%;' name=maxlvl value=99></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Сила бота</td><td width=50%><input type=text class=login style='width:100%;' name=power value=100></td>";
		echo "<tr>";
		echo "<td class=timef>Автоконфиг</td><td width=50%><script>autoconfig();</script><input type=checkbox name=balance value=1>Использовать Сбалансированную систему(Коэффицинты ниже - не учитываются)</td>";
		echo "</tr>";
		echo "</tr>";
		for ($i=1;$i<7;$i++)
		{
				echo "<tr>";
				echo "<td class=timef>Шаг \"<b>".name_of_skill('s'.$i)."</b>\"</td><td width=50%><input type=text class=login style='width:100%;' name=s".$i." value=1></td>";
				echo "</tr>";
		}
		for ($i=1;$i<6;$i++)
		{
				echo "<tr>";
				echo "<td class=timef>Шаг \"".name_of_skill('mf'.$i)."\"</td><td width=50%><input type=text class=login style='width:90%;' name=mf".$i." value=10>%</td>";
				echo "</tr>";
		}
				echo "<tr>";
				echo "<td class=timef>Шаг \"<b><i>Удар</i></b>\"</td><td width=50%><input type=text class=login style='width:90%;' name=ud value=1></td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td class=timef>Шаг \"<b><i class=green>Класс брони</i></b>\"</td><td width=50%><input type=text class=login style='width:90%;' name=kb value=5></td>";
				echo "</tr>";
				
		echo "<tr>";
		echo "<td class=timef>Тип дропа</td><td width=50%><select name=droptype class=real id=droptype>
		<option value=0>Ничего</option>
		<option value=1>Деньги</option>
		<option value=2>Пергаменты</option>
		<option value=3>Вещь навсегда</option>
		<option value=4>Вещь на день</option>
		<option value=5>Вещь на 3 дня</option>
		<option value=6>Вещь на неделю</option>
		<option value=7>Вещь на месяц</option>
		</select></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td class=timef>Значение дропа(Для вещи надо указать ID)(Для пергаментов и денег надо указать среднее значение кол-ва)</td><td width=50%><input type=text class=login style='width:90%;' name=dropvalue value=5></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td class=timef>Частота дропа в процентах.</td><td width=50%><input type=text class=login style='width:90%;' name=dropfrequency value=5></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td class=timef>Невоспреимчивость к магии</td><td width=50%><select name=magic_resistance class=real>
		<option value=0>Нет</option>
		<option value=1>Да</option>
		</select></td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td colspan=4><input type=submit class=login style='width:100%;' value='Добавить'></td>";
		echo "</tr>";
		echo "</table></center>";
	}
	
	if (@$_GET["delete"])
	{
		$u = sqlr("SELECT user FROM bots WHERE id=".intval($_GET["delete"])."");
		sqla("DELETE FROM bots WHERE user='".$u."'");
	}
	
	$allbots_names = sql("SELECT * FROM bots GROUP BY user;");
	
	$JS = '';	
	$SKIN_SEL = '<option value="0">Нет</option>';
	$SKINS = sql("SELECT * FROM skins");
	while($SKIN = mysql_fetch_array($SKINS))
	{
		$SKIN_SEL .= '<option value="'.$SKIN["id"].'">'.$SKIN["name"].'['.$SKIN["price"].']</option>';
	}
		
	echo "<center class=but><a class=bg href=main.php?add=1>Добавить новую линейку ботов</a></center>";
	echo "<center class=fightlong><table class=LinedTable border=0 width=100%>";
	while ($bn = mysql_fetch_array($allbots_names,MYSQL_ASSOC))
	{
		$lvls = sqla("SELECT MAX(level) as maxlvl, MIN(level) as minlvl,MAX(rank_i) as maxrank, MIN(rank_i) as minrank FROM bots WHERE user='".$bn["user"]."'");
		echo "<tr>";
		echo "<td class=timef>";
		echo "<img src=images/drop.gif onclick='if(confirm(\"УДАЛИТЬ???\")) location=\"main.php?delete=".$bn["id"]."\"' style='cursor:pointer'>".$bn["id"];
		echo "</td>";
		echo "<td><img src=images/persons/male_".$bn["obr"].".gif height=50 onclick=\"javascript:window.open('binfo.php?".$bn["id"]."','_blank')\" style=\"cursor:point\">";
		echo "</td>";
		echo "<td class=user>".$bn["user"];
		echo "<input type=button value='Hапасть' class=but onclick=\"Attack('".$bn["user"]."',".$lvls["minlvl"].",".$lvls["maxlvl"].");\">";
		echo "<input type=button value='Надеть вещь' class=but onclick=\"Wear('".$bn["user"]."',".$lvls["minlvl"].",".$lvls["maxlvl"].");\">";
		echo "<input type=button value='Снять вещь' class=but onclick=\"UNWear('".$bn["user"]."',".$lvls["minlvl"].",".$lvls["maxlvl"].");\">";
		echo "</td>";
		echo "<td class=ym><b>".$lvls["minlvl"]."</b>[<i>".intval($lvls["minrank"])."</i>] - <b>".$lvls["maxlvl"]."</b>[<i>".intval($lvls["maxrank"])."</i>]</td>";
		
		echo "<td nowrap><form method=post action=main.php?skin=".$bn["id"]."><Select name=skin onchange='skin_ch(\"sk_".$bn["id"]."\")' id=sel_sk_".$bn["id"].">".$SKIN_SEL."</select><div id=sk_".$bn["id"]." class=but style='display:inline'></div><input class=login type=submit value='Применить'></form></td>";
		
		echo "</tr>\n";
		
		$JS .= "document.getElementById('sel_sk_".$bn["id"]."').value = '".$bn["id_skin"]."';skin_ch('sk_".$bn["id"]."');";
	}
	echo "</table></center>";
	
	echo "<script>".$JS."</script>";
?>