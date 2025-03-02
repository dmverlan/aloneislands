<div class=fightlong style="position:absolute;z-index:1;left:1%;top:60px;width:98%">
<center><img src='images/locations/pr_shop.jpg' width=512></center>
<?
/*
if (substr_count($pers["rank"],"<molch>") or $pers["diler"]=='1' or $pers["priveleged"]);else
{
	echo "Мастерская находится в режиме тестирования.";
	exit;
}*/

define("UP_COST",120);
define("MAX_PRICE",2000);
define("MAX2_PRICE",20000);
define("MAX3_PRICE",100000);
define("MIN_PRICE",20);


	$UPGR = "upgrated=0";
	if ($pers["sp5"]<500) $MAX_PRICE = MAX_PRICE;
	elseif ($pers["sp5"]<1000) $MAX_PRICE = MAX2_PRICE;
	else 
	{
		$MAX_PRICE = MAX3_PRICE;
		$UPGR = "upgrated  < 2";
	}
	
	
// sp5 - кузнец

echo "<center class=but><I class=timef>Кузнец <b>".round($pers["sp5"])."</b></I></center>";

if (empty($_POST) and empty($_GET["id"]))
{
	echo "<p class=lbutton>Информация : <i>Для улучшения вещей требуются ресурсы из шахт. Среднее усиление первой степени стоит <b>".UP_COST." LN</b> для любой вещи. Усилять вещи можно если у вас есть хотябы 20 умения кузнец. Шанс удачного усиления пишется при выборе последней ступени усиления. При неудачном усилении возвращается половина потраченного ресурса...</i></p>";
	
	echo "<center>Вы можете улучшать вещи ценой не более <b>".$MAX_PRICE." LN</b> и не менее <b>".MIN_PRICE." LN</b><ul><li>до 500 умения - ".MAX_PRICE." LN</li><li>от 500 до 1000 умения - ".MAX2_PRICE." LN</li><li>от 1000 умения - ".MAX3_PRICE." LN, а так же возможность улучшать вещь повторно!</li></ul></center>";
	$vs = sql("SELECT * FROM wp WHERE uidp=".UID." and dprice=0 and where_buy=0 and weared=0  and ".$UPGR." and price>".MIN_PRICE." and price<=".$MAX_PRICE."");
	echo "<center class=but><I class=timef>Ваши вещи, доступные для улучшения:</I></center>";
	echo "<center>";
	echo "<table border=0 width=80%>";
	while($v = mysql_fetch_array($vs,MYSQL_ASSOC))
	{
		if (!IsWearing($v)) continue;
		$vesh = $v;
		echo "<tr>";
		echo "<td width=80% class=fightlong>";
		include("./inc/inc/weapon.php");
		echo "</td>";
		echo "<td align=center>";
		echo "<a class=NormalLink href='main.php?id=".$v["id"]."'><img src='images/icons/upload_big.png' style='cursor:pointer;'><br>Улучшить</a>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</center>";
}
else
if (empty($_POST) and isset($_GET["id"]) and empty($_GET["param"]))
{
	$v = sqla("SELECT * FROM wp WHERE uidp=".UID." and dprice=0 and where_buy=0 and weared=0  and ".$UPGR." and price>".MIN_PRICE." and price<=".$MAX_PRICE." and id='".intval($_GET["id"])."'");
	if (!$v) 
		echo "<script>location='main.php';</script>";
	else
	{
		echo "<center>";
		echo "<div class=but>Вы выбрали для улучшения «<img height=12 src='images/weapons/".$v["image"].".gif'><b class=user>".$v["name"]."</b>». Пожалуйста, выберите параметр для улучшения:</div>";
		echo "<table border=0 width=80% class=LinedTable>";
		$r = all_params();
		foreach ($r as $a)
		{
			if ($v[$a]!= 0 and ($a[1]!='p' || $a=='hp'))
			{
				echo "<tr>";
				echo "<td><a href='main.php?id=".$v["id"]."&param=".base64_encode($a)."' class=bg>".name_of_skill($a)."</a></td>";
				if ($a[0]=='m' and $a[1]=='f')
					echo "<td class=timef> [".plus_param($v[$a])."%] </td>";
				if ($a[0]=='s')
					echo "<td class=timef> [<b>".plus_param($v[$a])."</b>] </td>";
				if ($a=='hp')
					echo "<td class=timef> [<b class=hp>".plus_param($v[$a])."</b>] </td>";
				if ($a=='ma')
					echo "<td class=timef> [<b class=ma>".plus_param($v[$a])."</b>] </td>";
				if ($a=='kb')
					echo "<td class=timef> [<b class=green>".plus_param($v[$a])."</b>] </td>";
				echo "</tr>";
			}
		}
		echo "</table>";
	}
}
else
if (empty($_POST) and isset($_GET["id"]) and isset($_GET["param"]) and empty($_GET["up"]))
{
	$param = base64_decode($_GET["param"]);
	$v = sqla("SELECT * FROM wp WHERE uidp=".UID." and dprice=0 and where_buy=0 and weared=0  and ".$UPGR." and price>".MIN_PRICE." and price<=".$MAX_PRICE." and id='".intval($_GET["id"])."'");
	if($v["upgrated"]==0) $UP_COST = UP_COST;
	if($v["upgrated"]==1) $UP_COST = UP_COST*20;
	if (!$v or $v[$param]==0) 
		echo "<script>location='main.php';</script>";
	else
	{
		echo "<center>";
		echo "<div class=but>Вы выбрали для улучшения «<img height=12 src='images/weapons/".$v["image"].".gif'><b class=user>".$v["name"]."</b>». Улучшение на <b>\"".name_of_skill($param)."\"</b>.</div>";	
		
		$eq = EqualValueOfSkill($param);
		
		$mRes = sqlr("SELECT MAX(image) FROM resources");
		$z = uncrypt(md5($param.$v["stype"]));
		$z = floor($z/100 + $z%10000);
		$z = $z%$mRes;
		if ($z==0) $z=$mRes;
		$r = sqla("SELECT * FROM resources WHERE image='".$z."'");
		$yours = intval(sqlr("SELECT SUM(durability) FROM wp WHERE uidp=".UID." and type='resources' and image='resources/".$r["image"]."'"));
		$k = floor($UP_COST/$r["price"]);
		$chance = round((1 - (pow(0.9,sqrt($pers["sp5"]))+0.1))*100);
		$r_text = '<font class=user><img src=images/weapons/resources/'.$r["image"].'.gif><br>['.$r["name"].']</font>';
		echo "<table border=0 width=80%>";
		echo "<tr>";
		//
		echo "<td width=33% class=but align=center>";
		echo "<b>".name_of_skill($param)." +".($eq*1).", Шанс ".$chance."%</b>";		
		echo "<hr>";
		echo $r_text;
		echo "<hr>";
		echo "<i class=user>".($k*1)." Штук для улучшения</i>";
		if ($yours>$k*1 and $pers["sp5"]>=20)
			echo "<hr><a href='main.php?id=".$v["id"]."&param=".base64_encode($param)."&up=1' class=bg>Улучшить</a>";
		elseif($pers["sp5"]<20)
			echo "Не хватает умений для усиления. Минимум 20 умения кузнец.";
		echo "</td>";
		//
		echo "<td width=33% class=but align=center>";
		echo "<b>".name_of_skill($param)." +".($eq*2).", Шанс ".mtrunc($chance-10)."%</b>";		
		echo "<hr>";
		echo $r_text;
		echo "<hr>";
		echo "<i class=user>".($k*2)." Штук для улучшения</i>";
		if ($yours>$k*2 and $pers["sp5"]>=20)
			echo "<hr><a href='main.php?id=".$v["id"]."&param=".base64_encode($param)."&up=2' class=bg>Улучшить</a>";
		elseif($pers["sp5"]<20)
			echo "Не хватает умений для усиления. Минимум 20 умения кузнец.";
		echo "</td>";
		//
		echo "<td width=33% class=but align=center>";
		echo "<b>".name_of_skill($param)." +".($eq*3).", Шанс ".mtrunc($chance-20)."%</b>";		
		echo "<hr>";
		echo $r_text;
		echo "<hr>";
		echo "<i class=user>".($k*3)." Штук для улучшения</i>";
		if ($yours>$k*3 and $pers["sp5"]>=20)
			echo "<hr><a href='main.php?id=".$v["id"]."&param=".base64_encode($param)."&up=3' class=bg>Улучшить</a>";
		elseif($pers["sp5"]<20)
			echo "Не хватает умений для усиления. Минимум 20 умения кузнец.";
		
		
		echo "</td>";
		//
		echo "</tr>";
		echo "</table>";
		
		echo "<div class=but>У вас в рюкзаке <b>".$yours." ".$r["name"]."</b></div>";
		echo "</center>";
	}
	
}
else
if (empty($_POST) and isset($_GET["id"]) and isset($_GET["param"]) and isset($_GET["up"]))
{
	$param = base64_decode($_GET["param"]);
	$v = sqla("SELECT * FROM wp WHERE uidp=".UID." and dprice=0 and where_buy=0 and weared=0  and ".$UPGR." and price>".MIN_PRICE." and price<=".$MAX_PRICE." and id='".intval($_GET["id"])."'");
	if (!$v or $v[$param]==0)
		echo "<script>location='main.php';</script>";
	else
	{
		$kk = intval($_GET["up"]);
		if ($kk>3 or $kk<1) $kk = 1;
		$chance = round((1 - (pow(0.9,sqrt($pers["sp5"]))+0.1))*100 - $kk*10 + 10);
		echo "<center>";
		echo "<div class=but>Вы выбрали для улучшения «<img height=12 src='images/weapons/".$v["image"].".gif'><b class=user>".$v["name"]."</b>». Улучшение на <b>\"".name_of_skill($param)."\"</b>.</div>";	
		
		$eq = EqualValueOfSkill($param);
		
		$mRes = sqlr("SELECT MAX(image) FROM resources");
		$z = uncrypt(md5($param.$v["stype"]));
		$z = floor($z/100 + $z%10000);
		$z = $z%$mRes;
		if ($z==0) $z=$mRes;
		$r = sqla("SELECT * FROM resources WHERE image='".$z."'");
		$yours = intval(sqlr("SELECT SUM(durability) FROM wp WHERE uidp=".UID." and type='resources' and image='resources/".$r["image"]."'"));
		$k = floor(UP_COST/$r["price"])*$kk;
		if ($k>$yours) 
			echo "<script>location='main.php';</script>";
		elseif ($chance>rand(1,100))
		{
			$yours = sql("SELECT id,durability FROM wp WHERE uidp=".UID." and type='resources' and image='resources/".$r["image"]."'");
			$tmp = 0;
			while($yr = mysql_fetch_array($yours) and $tmp<$k)
			{
				$tmp += $yr["durability"];
				if ($tmp<$k)
					sql("UPDATE wp SET durability=0 WHERE id=".$yr["id"]."");
				else
					sql("UPDATE wp SET durability=".($tmp-$k).",weight=weight-".$k." WHERE id=".$yr["id"]."");
			}
			
			$v["name"] .= '(ап)';
			sql("UPDATE wp SET `".$param."` = `".$param."` + ".($kk*$eq).",upgrated=upgrated+1,name='".$v["name"]."',price=price+".UP_COST."*".$kk." WHERE id=".$v["id"]."");
			$v[$param] += $kk*$eq;
			$v["price"] += UP_COST * $kk;
			$v["upgrated"]++;
			$vesh = $v;
			echo "<b class=green>Удача!</b>";
			echo "<div class=but>";
			include("./inc/inc/weapon.php");
			echo "</div>";
		}
		else
		{
			$yours = sql("SELECT id,durability FROM wp WHERE uidp=".UID." and type='resources' and image='resources/".$r["image"]."'");
			$tmp = 0;
			$k = round($k/2);
			while($yr = mysql_fetch_array($yours) and $tmp<$k)
			{
				$tmp += $yr["durability"];
				if ($tmp<$k)
					sql("UPDATE wp SET durability=0 WHERE id=".$yr["id"]."");
				else
					sql("UPDATE wp SET durability=".($tmp-$k).",weight=weight-".$k." WHERE id=".$yr["id"]."");
			}
			$skill_up = round(10/$pers["sp5"],2);
			echo "<b class=red>Неудача!</b>";
			echo "<br><i class=green>Умение кузнец выросло на +".$skill_up.".</i>";
			set_vars("sp5=sp5+10/sp5",UID);
			$vesh = $v;
			echo "<div class=but>";
			include("./inc/inc/weapon.php");
			echo "</div>";
		}
	}
	
}
?>
</div>