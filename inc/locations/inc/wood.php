<?
	$p = '';
	$txt = '';
	$ins = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and p_type=3 and durability>0");
	$time = time();
	if (empty($_GET["take"]))
	{
	if ($pers["waiter"]<$time)
	{
		$p = 'Осмотр деревьев...';
		if ($cell["last_trees_change"]<$time)
		{	
			$count = sqlr("SELECT COUNT(*) FROM trees_cell 
			WHERE x_y='".$cell["x"]."_".$cell["y"]."'",0);
			if ($count == TREE_COUNT)
			{
				sql("DELETE FROM trees_cell WHERE x_y='".$cell["x"]."_".$cell["y"]."'");
				$count = 0;
			}
			while($count<TREE_COUNT and ($count++ or 1))
			{
			$tree = sqla("SELECT * FROM trees WHERE id%8=".($cell["wood"]-1)." ORDER BY RAND()");
			$diff = rand(1,4);
			sql("INSERT INTO `trees_cell` 
			( `x_y` , `name` , `image` , `time` , `count` , `difficult` ,`price`) 
			VALUES 
			( '".$cell["x"]."_".$cell["y"]."', '".$tree["name"]."', '".$tree["id"]."'
			, '".($time-rand(0,100))."', '".rand(1,10)."', '".$diff."' 
			, ".$tree["price"].");");
			
				$tree_grow = TREE_CHANGE;
				if (WEATHER==2) $tree_grow/=2;
				if (WEATHER==3) $tree_grow*=2;
				if (WEATHER==1 and date("m")>5 and date("m")<9) $tree_grow*=3;
				if (WEATHER==6) $tree_grow/=3;
				
			sql("UPDATE nature SET last_trees_change=".($time+$tree_grow)." WHERE x=".$cell["x"]." 
			and y=".$cell["y"]."");
			}
		}
		$trees = sql("SELECT * FROM trees_cell WHERE x_y='".$cell["x"]."_".$cell["y"]."' and count>0");
		$count_tr = 0;
		$txt .= '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#E5E5E5><tr>';
		while($tree = mysql_fetch_array($trees,MYSQL_ASSOC))
		{
			$chance = mtrunc(floor(61-$tree["price"]*2 + $pers["sp13"]/20 + $tree["difficult"]*3));
			$count_tr++;
			$txt .= "<td align=center>";
			$txt .= "<font class=user>".$tree["name"]."</font>[ <font class=green>".$tree["count"]." ШТ.</font> ] <I class=timef>".$chance."% шанс</I><hr noshade>";
			if ($tree["difficult"]==1) $txt .= "<center class=user>Молодое дерево</center>";
			if ($tree["difficult"]==2) $txt .= "<center class=user>Зрелое дерево</center>";
			if ($tree["difficult"]==3) $txt .= "<center class=user>Старое дерево</center>";
			if ($tree["difficult"]==4) $txt .= "<center class=user>Дряхлое дерево</center>";
			$txt .= "<br>";
			$txt .= "<table background='images/design/sum_bg.gif' width=200 height=220><tr><td align=center><img src=images/weapons/trees/".$tree["image"].".gif width=100></td></tr></table>";
			if ($ins)
			{
			if ($tree["count"]>0)
			$txt .= "<center class=but><a href='main.php?wood=on&take=".$tree["time"]."' class=Button>РУБИТЬ</a></center>";
			else
			$txt .= "<a href='#' class=bga>СРУБЛЕНО</a>";
			}
			else
			$txt .= "<center class=but>Нет инструмента</center>";
			$txt .= "</td>";
		}
		if ($count_tr == 0) $txt .= 'Здесь нет деревьев...';
		$txt .= '</table>';
		set_vars("waiter=".($time+TLOOK_TIME).",action=1",UID);
		$pers["waiter"] = ($time+TLOOK_TIME);
	}
	}
	elseif ($pers["action"]==1)
	{
		$p = 'Рубка...';
		$tree = sqla("SELECT * FROM trees_cell WHERE x_y='".$cell["x"]."_".$cell["y"]."' and 
		time=".intval($_GET["take"])." and count>0");
		if ($tree)
		{
			$chance = floor(61-$tree["price"]*2 + $pers["sp13"]/20 + $tree["difficult"]*3);
			$skill_plus = 0;
			if ($chance>98) $chance=98;
			if ($chance<1) $chance=1;
			if ($chance>rand(0,100))
			{
				$skill_plus = round(3/(1+$pers["sp13"]),4);
				$txt .= "<div><b class=green>Удачно срублено <b class=user>\"".$tree["name"]."\"</b>!</b></div>";
				$txt .= "<i>Шанс удачной срубки был: <b>".$chance."%</b></i><br>";
				$txt .= "Мирный опыт <b>+5</b><br>";
				$txt .= "Дровосек <b>+".$skill_plus."</b><br>";
				$dur_out = 5;
				if ($dur_out > $ins["durability"])$dur_out = $ins["durability"];
				$txt .= "Долговечность \"<b>".$ins["name"]."</b>\" <b>-".$dur_out."</b>.<br>";
				$txt .= "Стоимость сруба <b>".(($tree["price"]+2-$tree["difficult"])*2)." LN</b><br>";
				sql("INSERT INTO `wp` 
				( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` 
				, `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` 
				, `max_durability` , `durability` ,`p_type`) 
				VALUES 
				(0, '".$pers["uid"]."', '0','res..tree".$tree["image"]."'
				,'".(($tree["price"]+2-$tree["difficult"])*2)."', 
				'0', 'trees/".$tree["image"]."', '0', 'resources', 'resources', 
				'".$tree["name"]."', '', '10', '0', '1', '1','7');");
				sql("UPDATE trees_cell SET count=count-1 
				WHERE x_y='".$cell["x"]."_".$cell["y"]."' and 
				time=".intval($_GET["take"])." and count>0 LIMIT 1;");
			}
			else
			{
				$txt .= "<div><b class=hp>Неудачная попытка сруба <b class=user>\"".$tree["name"]."\"</b>.</b></div>";
				$txt .= "<i>Шанс удачной срубки был: <b>".$chance."%</b></i><br>";
				$txt .= "Мирный опыт <b>+5</b><br>";
				$dur_out = 5;
				if ($dur_out > $ins["durability"])$dur_out = $ins["durability"];
				$txt .= "Долговечность \"<b>".$ins["name"]."</b>\"  <b>-".$dur_out."</b>.<br>";
				sql("UPDATE trees_cell SET count=count-1 
				WHERE x_y='".$cell["x"]."_".$cell["y"]."' and 
				time=".intval($_GET["take"])." and count>0 LIMIT 1;");
			}
			set_vars("waiter=".($time+150).",tire=tire+5,sp13=sp13+".$skill_plus."
			,peace_exp=peace_exp+5,action=0",UID);
			$dur_out = 5;
			if ($dur_out > $ins["durability"])$dur_out = $ins["durability"];
			$ins["durability"]-=$dur_out;
			sql("UPDATE wp SET durability=durability-".$dur_out."
			WHERE id=".$ins["id"]." LIMIT 1;");
			$pers["waiter"] = ($time+150);
			if (rand(1,100)<3)
			{
				say_to_chat('s','Вы потревожили существ!',1,$pers["user"],'*',0);
				$bb = '';
				for ($i=1;$i<=rand(1,7);$i++)$bb.="bot=".(300+rand(1,20))."|";
				$bb = substr($bb,0,strlen($bb)-1);
				begin_fight ($pers["user"],$bb,"Нападение существ на лесоруба",100,300,1);
			}
		}
		else
		{
			$txt .= "Вас кто-то опередил.";
		}
	}
	if ($ins) 
	{
		$v = $ins;
		$zzz = $p;
		include("inc/inc/weapon2.php");
		unset($v);
		$txt .= "<hr><center class=weapons_box><script>".$text."</script></center>";
		$p = $zzz;
	}
	$_WOOD_RESPONSE = $txt;
	unset($txt);
?>