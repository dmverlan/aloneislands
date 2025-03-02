<?
//Нападение.
if (isset($_POST['napad']) and $pers['cfight']==0 and $_POST['fornickname']<>$pers["user"])
{
	$v = sqla ("SELECT * FROM `wp` WHERE `id`='".intval($_POST['napad'])."' and uidp=".$pers["uid"]." and weared=0");
	if ($v["type"]=='napad') 
	{
		if ($v["index"]==100) $travma=100; else $travma=30;
		$za = intval($_POST['za']);
		$perstowho = sqla("SELECT * FROM `users` WHERE `user`='".$_POST['fornickname']."' and online=1");
		if ($perstowho["cfight"]>10)
			$fight = sqla("SELECT * FROM `fights` WHERE `id`='".$perstowho["cfight"]."'");
		$k = 0;
		if($fight["closed"])
			$_RETURN = 'Нельзя вмешаться в закрытый бой';
		else	
		if ((($pers["location"]==$perstowho["location"] and $perstowho["location"]!='out') or
		$perstowho["location"]=='out' and $pers["x"]==$perstowho["x"] and $pers["y"]==$perstowho["y"]) 
		and $pers["user"]<>$perstowho["user"]
		) 
		{
			if ($perstowho["cfight"]>10 && $fight["type"]!='f')
			{
				if ($pers["invisible"]<=tme())
				$nyou = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
					else 
				$nyou = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";

				if ($fight["type"]<>'f' and $fight["id"]) 
				{
					$pers["curstate"] = 4;
					$pers["cfight"] = $fight["id"];
					sql ("UPDATE `fights` SET players=players+1 WHERE id=".$fight["id"]."");
					if (($za==1 and $perstowho["fteam"]==1) or ($za==0 and $perstowho["fteam"]==2))
					{
						sql ("UPDATE `users` SET `curstate`=4 , `cfight`='".$fight["id"]."', fteam=1,refr=1 WHERE `uid`='".$pers["uid"]."'");
						$fteam = 1;
					}
					else
					{
						sql ("UPDATE `users` SET `curstate`=4 , `cfight`='".$fight["id"]."', fteam=2,refr=1 WHERE `uid`='".$pers["uid"]."'");
						$fteam = 2;
					}
					if($fight["bplace"])
					{
						$bplace = sqla("SELECT * FROM battle_places WHERE id=".$fight["bplace"]);
						if($fteam==1) 
							$xf=4;
						else
							$xf=11;
						$yf=floor(15/2)-1;
						while ($xf>0 and $xf<15)
						{
							$yf++;
							if ($yf%$maxy==0)
							{
								$yf=0;
								if($fteam==1)
									$xf++;
								else
									$xf--;
							}
							$bcount = sqlr("SELECT COUNT(*) FROM users WHERE cfight=".$fight["id"]." and chp>0 and xf=".$xf." and yf=".$yf);
							$bcount += sqlr("SELECT COUNT(*) FROM bots_battle WHERE cfight=".$fight["id"]." and chp>0 and xf=".$xf." and yf=".$yf);
							
							if(!substr_count($bplace["xy"],"|".$xf."_".$yf."|") and $bcount==0) 
								break;
						}
						sql ("UPDATE `users` SET `yf`=".$yf." , `xf`='".$xf."' WHERE `uid`='".$pers["uid"]."'");
					}
					
					$k=1;
					add_flog($nyou." вмешивается в бой!",$perstowho["cfight"]);
				}
			}
			elseif($perstowho["chp"]>0)
			{
				if($fight["type"]=='f')
				{
					$perstowho = end_battle($perstowho);	
				}
				if ($perstowho["sign"]=='watchers' and date("H")%2==0)
				{
					say_to_chat('<font class=att>Внимание</font>','Персонаж <b>'.$pers["user"].'</b> попал в тюремное заточение за попытку нападения по чётным часам на власти на 10 минут.(<b>World Spawn</b>)',0,'','*',0); 
					set_vars ("location='prison',prison='".(time()+600)."|".htmlspecialchars("Попытка нападения на власти")."',curstate=2",$pers["uid"]);
				}
				elseif ($za==0)
				{
					if ($travma<>100) 
					{
						$travma=30;
						$na='Нападение';
					}
					else
						$na='Кровавое нападение';
						
					$place = 0;
					if($v["stype"]=='napadt')
						$place = rand(1,5);
					$closed = 0;
					if($v["p_type"]==15) 
					{
						$closed = 1;
						$na .= '[ЗАКРЫТОЕ]';
					}
					begin_fight ($pers["user"],$perstowho["user"],$na,$travma,180,1,$place,0,$closed);
					if($perstowho["kindness"]*signum($pers["kindness"])>-7)
						$pers["kindness"] -= 1/(1+mtrunc(-1*$pers["kindness"]));
					set_vars("kindness=".$pers["kindness"],$pers["uid"]);
					$k=1;
				}
			}
			else
				$_RETURN .= "<font class=hp>Цель слишком слаба</font>";
		}
		else 
			$_RETURN .= "<font class=hp>Нет такого персонажа в данном месте</font>";

		if ($k==1)
		sql("UPDATE wp SET durability=durability-1 WHERE id=".$v["id"]."");
		$pers = catch_user(UID);
	}
}elseif($_POST['fornickname']==$pers["user"])
{
	$_RETURN .= "Нельзя напасть на себя.";
}
?>