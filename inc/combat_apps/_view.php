<?
	#View all apps::
	$allapps = sql("SELECT * FROM app_for_fight WHERE type=".$cat."");
	$s = '';
	$UNIXtime = time();
	$counter = 0;
	while($app = mysql_fetch_array($allapps,MYSQL_ASSOC))
	{
		if (!$app["id"]) continue;
		if (intval($_FILTER["apps"])==0)
		{
			if ($app["type"]==1 and $pers["level"]<>$app["minlvl1"]) continue;
			if ($app["type"]==2 and 
			($pers["level"]<$app["minlvl1"] or $pers["level"]>$app["maxlvl1"]) and
			($pers["level"]<$app["minlvl2"] or $pers["level"]>$app["maxlvl2"])
			) continue;
			if ($app["type"]==3 and 
			($pers["level"]<$app["minlvl1"] or $pers["level"]>$app["maxlvl1"])
			)continue;
		}
		$write_this = false;
		$p1 = '';
		$p2 = '';
		$p = sql("SELECT sign,invisible,user,level,state,clan_name,fteam,rank_i FROM users 
		WHERE apps_id=".$app["id"]."");
		if ($app["pl1"]==$app["count1"] and $app["pl2"]==$app["count2"] 
		and $app["type"]==2) include ("_begin_group.php");
		elseif ($app["pl1"]==$app["count1"] 
		and $app["type"]==3) include ("_begin_haot.php");
		else
		{
		while($a = mysql_fetch_array($p))
		{
			$write_this = true;
			if ($a["invisible"]>tme() and $a["user"]<>$pers["user"])
			{
				$a["sign"] = 'none';
				$a["user"] = 'невидимка';
				$a["level"] = '??';
			}
			$a["state"] = $a["clan_name"].'['.$a["state"].']';
			if ($a["fteam"]==1)
				$p1 .= $a["sign"].'|'.$a["user"].'|'.$a["level"].'|'.$a["state"].'•';
			else
				$p2 .= $a["sign"].'|'.$a["user"].'|'.$a["level"].'|'.$a["state"].'•';
			$counter++;
		}
		
		if ($app["atime"]>$UNIXtime and $write_this) 
		{
		$s .= "'".$app["travm"].':'.$app["oruj"].':'.$app["timeout"].':';
		$s .= $app["count1"].':'.$app["count2"].':'.$app["minlvl1"].':';
		$s .= $app["minlvl2"].':'.$app["maxlvl1"].':'.$app["maxlvl2"].':';
		$s .= ($app["atime"]-$UNIXtime).':'.$app["comment"].':';
		$s .= substr($p1,0,strlen($p1)-1).':'.substr($p2,0,strlen($p2)-1).':'.$app["id"]."".':'.$app["bplace"]."'".',';
		}
		elseif (!$write_this) sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
		elseif ($app["atime"]<=$UNIXtime)
		{
			if ($app["type"]==1) 
			 {
				sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
				sql("UPDATE users SET apps_id=0,refr=1 WHERE apps_id=".$app["id"]."");
			 }
			 if ($app["type"]==2) 
			 {
				if ($app["pl2"]) 
					{
					$p = sql("SELECT sign,aura,user,level,state,clan_name,fteam FROM users 
						WHERE apps_id=".$app["id"]."");
					include ("_begin_group.php");
					}
				else
				{
				sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
				sql("UPDATE users SET apps_id=0,refr=1 WHERE apps_id=".$app["id"]."");
				}
			 }
			 if ($app["type"]==3) 
			 {

				if ($app["pl1"]>1) 
				{
					$p = sql("SELECT user,rank_i 
					FROM users WHERE apps_id=".$app["id"]."");
					include ("_begin_haot.php");
				}
				else
				{
				sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
				sql("UPDATE users SET apps_id=0,refr=1 WHERE apps_id=".$app["id"]."");
				}
			 }
		}
		}
	}
	
	$lb_attack = 0;
	if ($cat == 1)
	{
		if ($pers["level"]<20 and $pers["level"]>1)
		{
			$lb = sqlr("SELECT b_frequency FROM configs");
			if (($pers["lb_attack"]+$lb)<=tme())
			{
				$bts = sql("SELECT id,user,level FROM bots WHERE level>".($pers["level"]-2)." and level<".($pers["level"]+2)." and rank_i<".($pers["rank_i"]+140)." and special=0 ORDER BY RAND() LIMIT 0,3");
				while($bt = mysql_fetch_array($bts))
				{
					$s .= '\'50:1:300:';
					$s .= '1:1:1:';
					$s .= '1:1:1:';
					$s .= '0:Тренировочный бой:';
					$s .= 'none|'.$bt["user"].'|'.$bt["level"].'|'.'::-'.$bt["id"]."".':0'."'".',';
				}
			}
			else
			{
				$lb_attack = $pers["lb_attack"] + $lb - tme();
			}
		}
		if ($pers["level"]<=2 and $counter<2)
		{
			$bts = sql('SELECT uid,user,level,ctip FROM users WHERE ctip=-1 and level='.($pers["level"]).' and silence=0 LIMIT 0,'.rand(2,3).'');
			if(!$bts)
				$bts = sql("SELECT uid,user,level,ctip FROM users WHERE level=".($pers["level"])." and block<>'' and rank_i>5 and s6=1 and s5=1 and silence = 0 LIMIT 0,".rand(2,3)."");
			while($bt = mysql_fetch_array($bts))
			{
				$s .= '\'50:1:120:';
				$s .= '1:1:1:';
				$s .= '1:1:1:';
				$s .= '0::';
				$s .= 'none|'.$bt["user"].'|'.$bt["level"].'|'.'::!'.$bt["uid"]."".':0'."'".',';
				if($bt["ctip"]!=-1)
					set_vars("location='arena',x=-1,y=-3,ctip=-1",$bt["uid"]);
				set_vars("online=1,lasto=".tme(),$bt["uid"]);
			}
		}
	}
	
	if($pers["sign"]=='watchers' or $pers["diler"]==1 or $pers["priveleged"])
	if($cat==4)
	{
		include("_testing_view.php");
	}
	
	echo "\nvar lb_attack = ".$lb_attack.";";	
	echo "\nvar apps=new Array(";
	echo substr($s,0,strlen($s)-1);
	echo ");\n";
	echo "show_apps_".$cat."();\n";
?>
