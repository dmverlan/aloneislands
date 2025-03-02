<?
	if ($cat==1 and intval($_GET["id"])<0)
	{
		if ($pers["level"]<20)
		{
			$lb = sqla("SELECT b_frequency FROM configs");
			if (($pers["lb_attack"]+$lb["b_frequency"])<=tme())
			{
				$b = sqla("SELECT id,level,user,rank_i FROM bots WHERE id=".abs(intval($_GET["id"]))."");
				if ($b["level"]>$pers["level"]-2 and $b["level"]<$pers["level"]+2 and $b["rank_i"]<$pers["rank_i"]+140)
				{
					$lb_attack = $pers["level"]*30;
					if ($pers["level"]<5) $lb_attack/= 2;
						else
					$lb_attack += 100;
					$lb_attack += tme();
					$rnd = rand(1,$pers["level"]/3+1);
					$bb = '';
					for ($i=1;$i<=$rnd;$i++)$bb.="bot=".$b["id"]."|";
					$bb = substr($bb,0,strlen($bb)-1);
					begin_fight ($pers["user"],$bb,"Битва на арене",50,300,1,0);
					echo "location='main.php';";
					sql("UPDATE users SET lb_attack=".$lb_attack." WHERE uid=".$pers["uid"]);
				}
			}
		}
	}
	if ($_GET["id"][0]!="!" and intval($_GET["id"])>0)
	{
		$app = sqla("SELECT * FROM app_for_fight WHERE type=".$cat." and id=".intval($_GET["id"])."");
	if ($app)
	{
		if ($cat==1 and $app["pl2"]==0) 
		{
			set_vars("apps_id=".$app["id"].",fteam=2",UID);
			sql("UPDATE app_for_fight SET pl2=1,atime=".(time()+300)." WHERE id=".$app["id"]."");
			sql("UPDATE users SET refr=1 WHERE apps_id=".$app["id"]."");
			$pers["apps_id"] = $app["id"];
		}
		if ($cat==2) 
		{
			if ($_GET["fteam"]==1 and $app["count1"]>$app["pl1"] and
			$pers["level"]>=$app["minlvl1"] and $pers["level"]<=$app["maxlvl1"])
			{
				set_vars("apps_id=".$app["id"].",fteam=1",UID);
				sql("UPDATE app_for_fight SET pl1=pl1+1 WHERE id=".$app["id"]."");
				$pers["apps_id"] = $app["id"];
			}
			if ($_GET["fteam"]==2 and $app["count2"]>$app["pl2"] and
			$pers["level"]>=$app["minlvl2"] and $pers["level"]<=$app["maxlvl2"])
			{
				set_vars("apps_id=".$app["id"].",fteam=2",UID);
				sql("UPDATE app_for_fight SET pl2=pl2+1 WHERE id=".$app["id"]."");
				$pers["apps_id"] = $app["id"];
			}
		}
		if($cat==3) 
		{
			if ($app["count1"]>$app["pl1"] and
			$pers["level"]>=$app["minlvl1"] and $pers["level"]<=$app["maxlvl1"])
			{
				set_vars("apps_id=".$app["id"].",fteam=1",UID);
				sql("UPDATE app_for_fight SET pl1=pl1+1 WHERE id=".$app["id"]."");
				$pers["apps_id"] = $app["id"];
			}		
		}
	}
	}elseif($_GET["id"][0]=="!")
	{
		$uid = intval(substr($_GET["id"],1));
		include("_begin_ghost_fight.php");
		begin_ghost_fight($uid,$pers["user"],"Битва на арене",50,120,1,0);
	}
?>