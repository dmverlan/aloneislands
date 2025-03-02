<?
if (isset($_POST["zakl"]) and isset($_POST["fornickname"])) 
{
	$v = sqla("SELECT `id`,`index`,p_type FROM wp 
		WHERE uidp=".$pers["uid"]." and weared=0 and type='zakl' and id=".intval($_POST["zakl"])."");
	$persto = sqla ("SELECT uid,user,location FROM `users` 
		WHERE `user` = '".$_POST["fornickname"]."'");
	if ($persto["location"]==$pers["location"]) 
	{
		if ($v["p_type"]>=10 and $v["p_type"]<=12)
		{
			$special = $v["p_type"]-7;
			$all = sqlr("SELECT name FROM p_auras
			WHERE uid=".$persto["uid"]." and special=".$special." and esttime>".tme()."");
			if ($all)
			{
			sql("UPDATE p_auras SET esttime=0 
			WHERE uid=".$persto["uid"]." and special=".$special." and esttime>".tme()." LIMIT 1;");
			say_to_chat ('s','Персонаж <font class=user>'.$pers["user"].'</font> исцелил вас от травмы'
			.'(<b>'.$all.'</b>).',1,$persto["user"],'*',0);
			say_to_chat ('s','Вы исцелили <font class=user>'.$persto["user"].'</font> от травмы'
			.'(<b>'.$all.'</b>).',1,$pers["user"],'*',0);
			sql("UPDATE wp SET durability=durability-1 WHERE id=".$v["id"]."");
			$pers["kindness"] += 1/(1+mtrunc($pers["kindness"]));
			set_vars("kindness=".$pers["kindness"],$pers["uid"]);
			}
		}
		else
		{
			$not_error = aura_on2($v["index"],$persto["uid"]);
			if ($not_error)
			{
				say_to_chat ('s','Персонаж <font class=user>'.$pers["user"].'</font> накладывает на вас'.' <font class=user>'.$not_error["name"]."</font>.",1,$persto["user"],'*',0);
				sql("UPDATE wp SET durability=durability-1 WHERE id=".$v["id"]."");
				if($persto["uid"] == $pers["uid"])
					$pers = catch_user($pers["uid"]);
			}
		}
	}
	else
	 $_RETURN .= "<font class=puns>Нет такого персонажа<b>(".$_POST["fornickname"].")</b> в данном месте</font>";
		
unset($v);
unset($persto);
}

// Зелье
if (!empty($_POST["potion"])) 
{
	sql("START TRANSACTION;");
	$kl=1;
	$zakl = intval($_POST["potion"]);
	$zakl = sqla("SELECT `index`,name,id,image,durability FROM `wp` 
		WHERE `id`='".$zakl."' and durability>0");
	$acount = sqlr("SELECT COUNT(*) FROM p_auras WHERE uid=".$pers["uid"]." and special=13");
	$Ppers = $pers;
	$potions = sql("SELECT params FROM p_auras WHERE uid=".$pers["uid"]." and special=13");
	while($p = mysql_fetch_array($potions,MYSQL_ASSOC))
	{
		$_p = explode("@",$p["params"]);
		foreach($_p as $__p)
		{
			$ep = explode("=",$__p);
			$Ppers[$ep[0]] -= $ep[1];
		}
	}
	if ($acount>4)
	{
		$_RETURN .= "Достигнут лимит зелий.";
	}
	elseif ($pers["level"]<5) $_RETURN .= "Вы не сможете вынести таких нагрузок из-за зелья, т.к. не достигли ещё 5ого уровня.";
	elseif ($zakl and $zakl["durability"]>0) 
	{
		$param = explode("|",$zakl["index"]);
	if (($potionID=str_replace("potions/","",$zakl["image"]))<14 or $potionID==20)
	{
		$pers[$param[1]]+=$param[2];
		if ($param[1]=="s1")$sk = "Сила";
		if ($param[1]=="s2")$sk = "Реакция";
		if ($param[1]=="s3")$sk = "Удача";
		if ($param[1]=="s4")$sk = "Здоровье";
		if ($param[1]=="s6")$sk = "Сила воли";
		if ($param[1]=="kb")$sk = "Класс брони";
		if ($param[1]=="hp")$sk = "HP";
		if ($param[1]=="ma")$sk = "МАНА";
		if ($param[1]=="udmax")$sk = "Удар";
		if ($param[1]=="mf1")$sk = "Сокрушение";
		if ($param[1]=="mf2")$sk = "Уловка";
		if ($param[1]=="mf3")$sk = "Точность";
		if ($param[1]=="mf4")$sk = "Стоикость";
		if ($param[1]=="mf5")$sk = "Ярость";
		$sk = $sk." ".$param[2];
		$a[$param[1]]=$param[2];
		if ($param[1]=="udmax")
		{
		$a["udmin"]=$param[2];
		$pers["udmin"]+=$param[2];
		}
	}
	else
	{
		if ($potionID==14) 
		{
			$p = floor(0.01*$param[2]*$Ppers["udmin"]);
			if ($p>50) $p=50;
			$pers["udmin"]+= $p;
			$pers["udmax"]+= $p;
			$a["udmin"] = $p;
			$a["udmax"] = $p;
		}
		if ($potionID==15) 
		{
			$p = floor(0.01*$param[2]*$Ppers["kb"]);
			if ($p>100) $p=100;
			$pers["kb"]+=$p;
			$a["kb"] = $p;
		}
		if ($potionID==16) 
		{
			$p1 = floor(0.01*$param[2]*$Ppers["st6"]);
			$p2 = floor(0.01*($param[2]-5)*$Ppers["ma"]);
			if ($p1>12) $p1=12;
			if ($p2>250) $p2=250;
			$pers["s6"]+=$p1;
			$pers["ma"]+=$p2;
			$a["s6"] = $p1;
			$a["ma"] = $p2;
		}
		if ($potionID==17) 
		{
			$p1 = floor(0.01*$param[2]*$Ppers["st1"]);
			$p2 = floor(0.01*($param[2]-5)*$Ppers["ma"]);
			if ($p1>12) $p1=12;
			if ($p2>250) $p2=250;
			$pers["s1"]+=$p1;
			$pers["hp"]+=$p2;
			$a["s1"] = $p1;
			$a["hp"] = $p2;
		}
		if ($potionID==18) 
		{
			$mf1 = floor(0.01*$param[2]*$Ppers["mf1"]);
			$mf2 = floor(0.01*$param[2]*$Ppers["mf2"]);
			$p1 = floor(0.01*$param[2]*$Ppers["s2"]);
			$p2 = floor(0.01*$param[2]*$Ppers["s3"]);
			if ($mf1>200) $mf1=200;
			if ($mf2>200) $mf2=200;
			if ($p1>12) $p1=12;
			if ($p2>12) $p2=12;
			$pers["s2"]+=$p1;
			$pers["s3"]+=$p2;
			$pers["mf1"]+=$mf1;
			$pers["mf2"]+=$mf2;
			$a["s2"]=$p1;
			$a["s3"]=$p2;
			$a["mf1"]=$mf1;
			$a["mf2"]=$mf2;
		}
		if ($potionID==21) 
		{
			$pers["tire"]-=floor($param[2]);
			if ($pers["tire"]<0) $pers["tire"] = 0;
		}
		if ($potionID==19) 
		{
			if ($pers["invisible"]<tme()+$param[0]) $pers["invisible"] = tme()+$param[0];
		}
	}
		$a["hp"] += $a["s4"]*5;
		$a["ma"] += $a["s6"]*9;
		$pers["hp"]+= $a["s4"]*5;
		$pers["ma"]+= $a["s6"]*9;
		$z["image"] = '86';
		$z["params"] = '';
		foreach ($a as $key=>$value)
		$z["params"] .= $key.'='.$value.'@';
		$z["esttime"] = $param[0];
		$z["name"] = $zakl[1];
		$z["special"] = 13;
		light_aura_on($z,$pers["uid"]);
		set_vars(aq($pers),$pers["uid"]);
		say_to_chat ('s','Вы выпили '.
		' <font class=user>'.$zakl[1]."</font>.",1,$pers["user"],'*',0);
		sql("UPDATE wp SET durability=durability-1 WHERE id=".intval($_POST["potion"])."");
	}
	sql("COMMIT;");
}

if (@$_POST["teleport"])
{
	$cell = sqla("SELECT * FROM nature WHERE x=".intval($_POST["X"])." and y=".intval($_POST["Y"])."");
	$v = sqla("SELECT weight,uidp,where_buy,dprice FROM wp WHERE id=".intval($_POST["teleport"])." and uidp=".$pers["uid"]."");
	if (isset($cell["name"]) and isset($v["uidp"]))
	{
		sql("UPDATE users SET x=".intval($_POST["X"])." , y=".intval($_POST["Y"]).", curstate=2 , location='out' WHERE uid=".$pers["uid"]."");
		sql("UPDATE wp SET durability=durability-1 WHERE id=".intval($_POST["teleport"])."");
		$pers["x"]=intval($_POST["X"]);
		$pers["y"]=intval($_POST["Y"]);
		$pers["curstate"]=2;
		$pers["location"]="out";
	}elseif (empty($cell["name"])) $_RETURN .=  "Проход на эту локацию закрыт!";
}

?>