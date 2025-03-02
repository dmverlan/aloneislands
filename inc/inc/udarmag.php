<?
if ($zid and substr_count("19|".$_pers["zakl"],$zid)) {
$zakl = sqla ("SELECT * FROM `zakl` WHERE `id`='".$zid."'");
if ($_pers["cma"]>=$zakl["mana"]) {
	$promax=10-$_pers["mf3"]/1000-$_pers[$zakl['stype']]; 
	if ($promax>rand(0,100))
		{
			$zakl_log = $nyou.' промахивается , используя заклинание '."«<font class=user onmouseover=\"s_des('".$zid."',1)\" onmouseout=\"h_des()\" style=\"cursor:hand\" onmousemove=move_alt()>".$zakl["name"]."</font>»";
			$yron=0;
		}
	else {
// ТОЛЧОК
###############################################################################
	if ($zakl["type"]=="push") 
		{
		$hp_all = 0;$hp_alll = 0;
		if ($zakl["c_c"]<1) $zakl["c_c"]=1;
		$subpush_log = '';
		$counter=0;
$ps = sql("SELECT uid,user,xf,yf,aura,chp,hp,level,sb3,s2,mf2,fteam,sign,bg,bn,bj FROM users WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");
$bs = sql("SELECT id,user,xf,yf,aura,chp,hp,level,s2,mf2,fteam,id_skin,rank_i FROM bots_battle WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");

$i=0;
		while($persvs = mysql_fetch_array($ps))
		{
		$i++;
		if ($i>$zakl["c_c"])break; else
		{
		if (substr_count($persvs["aura"],"invisible")){$persvs["sign"]='none';$persvs["user"] = 'невидимка';}
		if ($persvs["sign"]<>'none') $sign = '<img src="images/signs/'.$persvs["sign"].'.gif">'; else $sign='';
			$ylov =5*($persvs["sb3"]/3+$persvs["s2"]+$persvs["mf2"]/10-$_pers["s2"]-$_pers["mf3"]/10-$_pers["sb5"]/3);
			if ($ylov>85)$ylov=85;if ($ylov<3)$ylov=3;
			$hp = round($persvs["hp"]*rand($zakl["udmin"],$zakl["udmax"])/100);
			$hp = floor(($hp+1)*sqrt(($_pers["level"]+1)/($persvs["level"]+1)));
			$block_z='';
			if ($persvs[$bpoint]>0) {$hp=0;$steps=0;$block_z=' <u>(противник заблокировал удар)</u>';}
			if ($ylov>rand(1,100)) {$hp=0;$steps=0;$block_z=' <u>(противник увернулся)</u>';}
			$persvs["xf"]+=$steps;
			if ($persvs["xf"]<1)$persvs["xf"]=1;
			if ($persvs["xf"]>15)$persvs["xf"]=15;
			$hp_true = $hp;
			if ($persvs["chp"]<$hp)$hp=$persvs["chp"];
			$persvs["chp"]-=$hp;
			if ($persvs["chp"]<=0)
		 {
			$die=$sign."<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font> погибает от магии, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
			$_pers["kills"]++;
			include ('inc/inc/fights/travm.php');
		 }
			set_vars("xf=".$persvs["xf"].",chp=".$persvs["chp"],$persvs["uid"]);
			$hp_alll += abs($steps);
			$hp_all+= $hp;
			//Считаем опыт
			$_pers["exp_in_f"]+= experience($hp,$_pers["level"],$persvs["level"],$persvs["uid"]);
			//Закончили опыт
			$shagi = $zakl["steps"];
			if ($shagi == 1) $shagi = $shagi." шаг";
			if ($shagi > 1 and $shagi<5) $shagi = $shagi." шага";
			if ($shagi > 4 or $shagi==0) $shagi = $shagi." шагов";
			if ($hp>0) $hp = "(<font class=hp>-".$hp_true." HP</font>, ".$shagi.")"; else $hp = $block_z;
			$subpush_log .= "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>".$hp.",";
			$counter++;
			}
		}
		
		while($persvs = mysql_fetch_array($bs))
		{
		$i++;
		if ($i>$zakl["c_c"])break; else
		{
			if ($persvs["sign"]<>'none') 
			 $sign = '<img src="images/signs/'.$persvs["sign"].'.gif">'; 
			else 
			 $sign='';
			$ylov =5*($persvs["sb3"]/3+$persvs["s2"]+$persvs["mf2"]/10-$_pers["s2"]-$_pers["mf3"]/10-$_pers["sb5"]/3);
			if ($ylov>85)$ylov=85;if ($ylov<3)$ylov=3;
			$hp = round($persvs["hp"]*rand($zakl["udmin"],$zakl["udmax"])/100);
			$hp = floor(($hp+1)*sqrt(($_pers["level"]+1)/($persvs["level"]+1)));
			$block_z='';
			if ($persvs[$bpoint]>0) {$hp=0;$steps=0;$block_z=' <u>(противник заблокировал удар)</u>';}
			if ($ylov>rand(1,100)) {$hp=0;$steps=0;$block_z=' <u>(противник увернулся)</u>';}
			$persvs["xf"]+=$steps;
			if ($persvs["xf"]<1)$persvs["xf"]=1;
			if ($persvs["xf"]>15)$persvs["xf"]=15;
			$hp_true = $hp;
			if ($persvs["chp"]<$hp)$hp=$persvs["chp"];
			$persvs["chp"]-=$hp;
			if ($persvs["chp"]<=0)
		 {
			$die=$sign."<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font> погибает от магии, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
			$_pers["kills"]++;
			include ('inc/inc/fights/travm.php');
		 }
			set_vars("xf=".$persvs["xf"].",chp=".$persvs["chp"],$persvs["uid"]);
			$hp_alll += abs($steps);
			$hp_all+= $hp;
			//Считаем опыт
			$_pers["exp_in_f"]+= experience($hp,$_pers["level"],$persvs["level"],$persvs["uid"]);
			//Закончили опыт
			$shagi = $zakl["steps"];
			if ($shagi == 1) $shagi = $shagi." шаг";
			if ($shagi > 1 and $shagi<5) $shagi = $shagi." шага";
			if ($shagi > 4 or $shagi==0) $shagi = $shagi." шагов";
			if ($hp>0) $hp = "(<font class=hp>-".$hp_true." HP</font>, ".$shagi.")"; else $hp = $block_z;
			$subpush_log .= "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>".$hp.",";
			$counter++;
			}
		}
		
		while($persvs = mysql_fetch_array($bs))
		{
		$i++;
		if ($i>$zakl["c_c"])break; else
		{
			$ylov =5*($persvs["s2"]+$persvs["mf2"]/10-$_pers["s2"]-$_pers["mf3"]/10-$_pers["sb5"]/3);
			if ($ylov>85)$ylov=85;if ($ylov<3)$ylov=3;
			$steps=signum($persvs["xf"]-$_pers["xf"])*$zakl["steps"];
			$hp = round($persvs["hp"]*rand($zakl["udmin"],$zakl["udmax"])/100);
			$hp = floor(($hp+1)*sqrt(($_pers["level"]+1)/($persvs["level"]+1)));
			$block_z='';
			if (rand(1,10)<3) {$hp=0;$steps=0;$block_z=' <u>(противник заблокировал удар)</u>';}
			if ($ylov>rand(1,100)) {$hp=0;$steps=0;$block_z=' <u>(противник увернулся)</u>';}
			$persvs["xf"]+=$steps;
			if ($persvs["xf"]<1)$persvs["xf"]=1;
			if ($persvs["xf"]>15)$persvs["xf"]=15;
			$hp_true = $hp;
			if ($persvs["chp"]<$hp)$hp=$persvs["chp"];
			$persvs["chp"]-=$hp;
			if ($persvs["chp"]<=0)
		 {
			$die="<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font> погибает от магии, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
			$_pers["kills"]++;
			include ('inc/inc/bots/drop.php');
			$die .= $str;
		 }
			sql("UPDATE bots_battle SET xf=".$persvs["xf"].",chp=".$persvs["chp"]." WHERE id=".$persvs["id"]."");
			$hp_alll += abs($steps);
			$hp_all+= $hp;
			//Считаем опыт
			if(!$persvs["id_skin"])
			$_pers["exp_in_f"]+= experience($hp,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			else
			$_pers["exp_in_f"]+= experience($hp*0.3,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			//Закончили опыт
			$shagi = $zakl["steps"];
			if ($shagi == 1) $shagi = $shagi." шаг";
			if ($shagi > 1 and $shagi<5) $shagi = $shagi." шага";
			if ($shagi > 4 or $shagi==0) $shagi = $shagi." шагов";
			if ($hp>0) $hp = "(<font class=hp>-".$hp_true." HP</font>, ".$shagi.")"; else $hp = $block_z;
			$subpush_log .= "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>".$hp.",";
			$counter++;
			}
		}
		if ($counter>0)
		{
		if ($hp_all>0) $push_log = $nyou.' оттолкнул'.$male.' '.substr($subpush_log,0,strlen($subpush_log)-1). " от себя с помощью заклинания «<font class=user onmouseover=\"s_des('".$zid."',1)\" onmouseout=\"h_des()\" style=\"cursor:hand\" onmousemove=move_alt()>".$zakl["name"]."</font>»";
		else $push_log = $nyou.' '.$pitalsa.' оттолкнуть '.substr($subpush_log,0,strlen($subpush_log)-1). " от себя с помощью заклинания «<font class=user onmouseover=\"s_des('".$zid."',1)\" onmouseout=\"h_des()\" style=\"cursor:hand\" onmousemove=move_alt()>".$zakl["name"]."</font>»";
		} else $push_log = '';
		$_pers["fexp"] += $hp_all;
		$yron = $hp_all;
		$z=0;
		}
###########################################################################
		if ($zakl["type"]=="blast" or $zakl["type"]=="blast2") 
		{
		$hp_all = 0;
		if ($zakl["c_c"]<1) $zakl["c_c"]=1;
		$subpush_log = '';
		$counter=0;
$ps = sql("SELECT uid,user,xf,yf,aura,chp,hp,level,sb3,s2,mf2,fteam,sign,bg,bn,bj FROM users WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");
$bs = sql("SELECT id,user,xf,yf,aura,chp,hp,level,s2,mf2,fteam,id_skin,rank_i FROM bots_battle WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");

$i=0;
		while($persvs = mysql_fetch_array($ps))
		{
		$i++;
		if ($i>$zakl["c_c"])break; else
		{
		if (substr_count($persvs["aura"],"invisible")){$persvs["sign"]='none';$persvs["user"] = 'невидимка';}
		if ($persvs["sign"]<>'none') $sign = '<img src="images/signs/'.$persvs["sign"].'.gif">'; else $sign='';
			$ylov =5*($persvs["sb3"]/3+$persvs["s2"]+$persvs["mf2"]/10-$_pers["s2"]-$_pers["mf3"]/10-$_pers["sb5"]/3);
			if ($ylov>85)$ylov=85;if ($ylov<3)$ylov=3;
			if ($zakl["type"]=="blast2")
			$hp = round($persvs["hp"]*rand($zakl["udmin"],$zakl["udmax"])/100);
			else
			$hp = round((rand($zakl["udmin"],$zakl["udmax"])*rand($zakl["udmin"],$zakl["udmax"])/9)/(sqrt($persvs["kb"]*2+$persvs["sb10"])+1)+sqrt(sqrt($_pers[$zakl['stype']])));
			$hp = floor(($hp+1)*sqrt(($_pers["level"]+1)/($persvs["level"]+1)));
			$block_z='';
			if ($persvs[$bpoint]>0) {$hp=0;$block_z=' <u>(противник заблокировал удар)</u>';}
			if ($ylov>rand(1,100)) {$hp=0;$block_z=' <u>(противник увернулся)</u>';}
			$hp_true = $hp;
			if ($persvs["chp"]<$hp)$hp=$persvs["chp"];
			$persvs["chp"]-=$hp;
			if ($persvs["chp"]<=0)
		 {
			$die=$sign."<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font> погибает от магии, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
			$_pers["kills"]++;
			include ('inc/inc/fights/travm.php');
		 }
			set_vars("chp=".$persvs["chp"],$persvs["uid"]);
			$hp_all+= $hp;
			//Считаем опыт
			$_pers["exp_in_f"]+= experience($hp,$_pers["level"],$persvs["level"],$persvs["uid"]);
			//Закончили опыт
			if ($hp>0) $hp = "(<font class=hp>-".$hp_true." HP</font>)"; else $hp = $block_z;
			$subpush_log .= "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>".$hp.",";
			$counter++;
			}
		}
		while($persvs = mysql_fetch_array($bs))
		{
		$i++;
		if ($i>$zakl["c_c"])break; else
		{
			$ylov =5*($persvs["s2"]+$persvs["mf2"]/10-$_pers["s2"]-$_pers["mf3"]/10-$_pers["sb5"]/3);
			if ($ylov>85)$ylov=85;if ($ylov<3)$ylov=3;
			if ($zakl["type"]=="blast2")
			$hp = round($persvs["hp"]*rand($zakl["udmin"],$zakl["udmax"])/100);
			else
			$hp = round((rand($zakl["udmin"],$zakl["udmax"])*rand($zakl["udmin"],$zakl["udmax"])/8)/(sqrt($persvs["kb"]*2+$persvs["sb10"])+1)+sqrt(sqrt($_pers[$zakl['stype']])));
			$hp = floor(($hp+1)*sqrt(($_pers["level"]+1)/($persvs["level"]+1)));
			$block_z='';
			if (rand(1,10)<3) {$hp=0;$block_z=' <u>(противник заблокировал удар)</u>';}
			if ($ylov>rand(1,100)) {$hp=0;$block_z=' <u>(противник увернулся)</u>';}
			$hp_true = $hp;
			if ($persvs["chp"]<$hp)$hp=$persvs["chp"];
			$persvs["chp"]-=$hp;
			if ($persvs["chp"]<=0)
		 {
			$die="<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font> погибает от магии, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
			$_pers["kills"]++;
			include ('inc/inc/bots/drop.php');
			$die .= $str;
		 }
			sql("UPDATE bots_battle SET chp=".$persvs["chp"]." WHERE id=".$persvs["id"]."");
			$hp_all+= $hp;
			//Считаем опыт
			if(!$persvs["id_skin"])
			$_pers["exp_in_f"]+= experience($hp,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			else
			$_pers["exp_in_f"]+= experience($hp*0.3,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			//Закончили опыт
			if ($hp<>0) $hp = "(<font class=hp>-".$hp_true." HP</font>)"; else $hp = $block_z;
			$subpush_log .= "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>".$hp.",";
			$counter++;
			}
		}
		if ($counter>0)
		{
		if ($hp_all>0) $push_log = $nyou.' поразил'.$male.' '.substr($subpush_log,0,strlen($subpush_log)-1). " с помощью заклинания «<font class=user onmouseover=\"s_des('".$zid."',1)\" onmouseout=\"h_des()\" style=\"cursor:hand\" onmousemove=move_alt()>".$zakl["name"]."</font>»";
		else $push_log = $nyou.' '.$pitalsa.' поразить '.substr($subpush_log,0,strlen($subpush_log)-1). " с помощью заклинания «<font class=user onmouseover=\"s_des('".$zid."',1)\" onmouseout=\"h_des()\" style=\"cursor:hand\" onmousemove=move_alt()>".$zakl["name"]."</font>»";
		} else $push_log = '';
		$_pers["fexp"] += $hp_all;
		$yron = $hp_all;
		$z=0;
		}
############################################################################
		$zakl_log = $push_log;
}
		$_pers["cma"]=$_pers["cma"] - $zakl["mana"];
		$_pers[$zakl['stype']] += 1/($_pers[$zakl['stype']]+1); 
		$z=2;
		$no_mana=false;
}else {$z=2;$no_mana=true;$zakl_log="<font class=bnick color=".$colors[$_pers["fteam"]].">".$_pers["user"].'</font> нехватает маны для хода';}


if ($zakl["stype"]<>'')
sql ("UPDATE `users` SET `cma`='".$_pers["cma"]."',`".$zakl["stype"]."`=".$_pers[$zakl["stype"]].",fexp=fexp+".intval($hp_all).",exp_in_f=".$_pers["exp_in_f"]." WHERE `uid`='".$_pers["uid"]."'; ");

unset($persvs);
mysql_free_result($ps);
mysql_free_result($bs);
$_pers = catch_user($_pers["uid"]);

if ($_pers["chp"]>0)
{
if (@$_GET["vs_id"])
{
	if ($_GET["vs_id"]>0) $_persvs = sqla("SELECT * FROM users WHERE uid=".intval($_GET["vs_id"])." and chp>0");
	else $_persvs = sqla("SELECT * FROM bots_battle WHERE id=".intval($_GET["vs_id"])."  and chp>0");
}
if (empty($_GET["vs_id"]) or !$_persvs["user"])
{
	$_persvs = sqla("SELECT * FROM users WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");
	if (!$_persvs["uid"]) 
	$_persvs = sqla("SELECT * FROM bots_battle WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0");
}
 $_persvs["chp"] = floor ($_persvs["chp"]);
 $_persvs["cma"] = floor ($_persvs["cma"]);
}
}
?>