<?
$req["magic_koef"] = mtrunc(floor($req["magic_koef"]));
if ($req["magic_koef"]<1) $req["magic_koef"]=1;
if ($req["magic_koef"]>($_pers["level"]/2+1)) $req["magic_koef"] = 1;
$magic_koef = $req["magic_koef"]*0.7;
$zakl = sqla ("SELECT * FROM `u_blasts` WHERE `id`='".$zid."' and uidp=".$_pers["uid"]." and tlevel<=".$_pers["level"]." and ts6<=".$_pers["s6"]." and cur_colldown<=".tme()." and cur_turn_colldown<=".$_pers["f_turn"]);
//$LOG .= $_pers["f_turn"]." ".$zakl["turn_colldown"]." |";
if ($zakl and $zakl["manacost"]<=$_pers["cma"]/$req["magic_koef"])
{
		if ($zakl["type"]==0) $zakl["type"] = 3;
		$hp_all = 0;
		if ($zakl["targets"]<1) $zakl["targets"]=1;
		$LOG = '';
		$i=0;
		$counter=0;
		
$ps = sql("SELECT uid,user,xf,yf,chp,hp,level,sb3,s2,mf2,mf4,fteam,sign,bg,bn,bj,bt,is_art,kb,invisible FROM users WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0 and uid<>".intval($_persvs["uid"]));
$bs = sql("SELECT id,user,xf,yf,chp,hp,level,s2,mf2,fteam,id_skin,rank_i,kb,magic_resistance,dropfrequency,droptype FROM bots_battle WHERE cfight=".$_pers["cfight"]." and fteam<>".$_pers["fteam"]." and chp>0 and id<>".intval($_persvs["id"]));

		while($i<$zakl["targets"])
		{
		$i++;
		if ($i==1 and $_persvs["uid"]) 
			$persvs = $_persvs;
		else
			$persvs = mysql_fetch_array($ps);
			
		if (!$persvs) break;
		if ($persvs["invisible"]<=tme())
			$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>[".$persvs["level"]."]";
		else 
			$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]]."><i>невидимка</i></font>[??]";
			
			$ylov = ylov($_pers,$persvs)*0.9;
			$sokr = sokr($_pers,$persvs);
			$ydar = rand($zakl["udmin"]*10,$zakl["udmax"]*10)/10*$magic_koef;
			$ydar = $ydar*sqrt($ydar);
			$ydar = floor(mtrunc(($_pers["m".$zakl["type"]]/5+rand($zakl["udmin"]*10,$zakl["udmax"]*10+10)/12*sqrt($_pers["s6"]*2)*$req["magic_koef"]+$ydar)*(1+$_pers["sb9"]/50)));
			
			###Остальных в 2 раза слабже
			if($_persvs["uid"]!=$persvs["uid"])
				$ydar *= 0.5;
			
			$kb   = mtrunc($persvs["kb"]+$persvs["sb11"]); 
			if ($kb<1) $kb = 1;
			$ydar = $ydar*(pow(0.9,sqrt($kb))+0.2);
			$ydar = mtrunc(rand($ydar-3,$ydar+3));
			
			$shield = sqlr("SELECT COUNT(*) FROM wp WHERE stype='shit' and weared=1 and uidp=".$persvs["uid"]."");
			if ($shield) $ydar = floor($ydar/2);
			
			$ylov *= $persvs["is_art"]/$_pers["is_art"];
			$sokr *= $_pers["is_art"]/$persvs["is_art"];
			$ydar *= $_pers["is_art"]/$persvs["is_art"];
			
			if ($ylov>70) $ylov = 70;
			if ($sokr>70) $sokr = 70;
			
			$ksokr = 2;
			$CRITISISED = 0;
			if (rand(0,100)<$sokr)
			{
				$ydar=round($ydar*$ksokr);
				$CRITISISED = 1;
			}

			$block = '';
			$blocked = 0;
			if ($persvs[$bpoint]==1)
			 {	
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>2)
				{$ydar*=0.3;
				$block="<i class=timef>пробивая простой блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }
			if ($persvs[$bpoint]==2)
			 {
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>3)
				{$ydar*=0.2;
				$block="<i class=timef>пробивая усиленный блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }

			if ($persvs[$bpoint]==5)
			 {	
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>5)
				{$ydar*=0.1;
				$block="<i class=timef>пробивая крепчайший блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }
			$ydar = floor($ydar);
			if ($ylov>rand(1,100)) 
			{
				$ydar=0;
				$block='<b class=timef>увернулся</b>';
			}
			elseif ($blocked == 1)
			{
				$ydar=0;
				$block='<b class=timef>заблокировал</b>';
			}
			else
			{
			if ($CRITISISED)
				$block.='<font class=red>«cокрушительный удар»</font>,';
			aura_on2($zakl["aura_id"],$persvs,$req["magic_koef"]);
			}
			
			if ($persvs["chp"]<$ydar)$ydar=$persvs["chp"];
			$persvs["chp"]-=$ydar;
			
			if ($persvs["chp"]<=0)
			{
				$die=$nvs." <b>погибает от магии</b>, ".$nyou." опыт <font class=green>+".($_pers["level"]*10)."</font>.%".$die;
				$_pers["kills"]++;
				include ('inc/inc/fights/travm.php');
			}
			$ALL_DAMAGE += $ydar;
			
			//Считаем опыт
			$_pers["exp_in_f"]+= experience($ydar,$_pers["level"],$persvs["level"],$persvs["uid"]);
			//Закончили опыт
			
			if ($ydar>0) 
				$lg = "(".$block."<font class=hp>-".$ydar." HP</font>)"; 
			else 
				$lg = "(".$block.")";
			$LOG .= $nvs.$lg.",";
			set_vars("chp=".$persvs["chp"],$persvs["uid"]);
			
			$counter++;
		}
		
		$i--;
		
		while($i<$zakl["targets"])
		{
		$i++;
		if ($i==1 and !$_persvs["uid"]) 
			$persvs = $_persvs;
		else
			$persvs = mysql_fetch_array($bs);
			
			$_persvs["id"] = $_persvs["id"];
		if (!$persvs) break;		
			$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>[".$persvs["level"]."]";
					
			$ylov = ylov($_pers,$persvs)*0.9;
			$sokr = sokr($_pers,$persvs);
			$ydar = rand($zakl["udmin"]*10,$zakl["udmax"]*10)/10*$magic_koef;
			$ydar = $ydar*sqrt($ydar);
			$ydar = floor(mtrunc(($_pers["m".$zakl["type"]]/5+rand($zakl["udmin"]*10,$zakl["udmax"]*10+10)/12*sqrt($_pers["s6"]*2)*$req["magic_koef"]+$ydar)*(1+$_pers["sb9"]/50)));
			###Остальных в 2 раза слабже
			if($_persvs["id"]!=$persvs["id"])
				$ydar *= 0.5;
				
			$kb = mtrunc($persvs["kb"]+$persvs["sb11"]); 
			if ($kb<1) $kb = 1;
			$ydar = $ydar*(pow(0.9,sqrt($kb))+0.2);
			$ydar = mtrunc(rand($ydar-3,$ydar+3));
			
			$ylov *= 1/$_pers["is_art"];
			$sokr *= $_pers["is_art"];
			$ydar *= $_pers["is_art"];
			
			$shield = sqlr("SELECT COUNT(*) FROM wp WHERE stype='shit' and weared=1 and uidp=".(-1*$persvs["bid"])."");
			if ($shield) $ydar = floor($ydar/2);
			
			if ($ylov>70) $ylov = 70;
			if ($sokr>70) $sokr = 70;
			
			$ksokr = 2;
			$CRITISISED = 0;
			if (rand(0,100)<$sokr)
			{
				$ydar=round($ydar*$ksokr);
				$CRITISISED = 1;
			}
			
			$persvs[$bpoint] = rand(-6,2);
			$block = '';
			$blocked = 0;
			if ($persvs[$bpoint]==1)
			 {	
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>2)
				{$ydar*=0.3;
				$block="<i class=timef>пробивая простой блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }
			if ($persvs[$bpoint]==2)
			 {
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>3)
				{$ydar*=0.2;
				$block="<i class=timef>пробивая усиленный блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }

			if ($persvs[$bpoint]==5)
			 {	
				if ($ydar/(mtrunc($persvs["kb"])/3+3)>5)
				{$ydar*=0.1;
				$block="<i class=timef>пробивая крепчайший блок</i> ,";}
				else
				{
				$ydar=0;
				$blocked = 1;
				}
			 }
			$ydar = floor($ydar);
			if ($ylov>rand(1,100)) 
			{
				$ydar=0;
				$block='<b class=timef>увернулся</b>';
			}
			elseif ($blocked == 1)
			{
				$ydar=0;
				$block='<b class=timef>заблокировал</b>';
			}
			elseif ($persvs["magic_resistance"])
			{
				$ydar=0;
				$block='<b class=timef>невосприимчив к магии</b>';
			}
			elseif ($CRITISISED)
			{
				$block.='<font class=red>«cокрушительный удар»</font>,';
			}
			
			if ($persvs["chp"]<$ydar)$ydar=$persvs["chp"];
			$persvs["chp"]-=$ydar;
			
			if ($persvs["chp"]<=0)
			{
				include ('inc/inc/bots/drop.php');
				$die=$nvs." <b>погибает от магии</b>. %".$str."%".$die;
			}
			$ALL_DAMAGE += $ydar;
			
			//Считаем опыт
			if(!$persvs["id_skin"])
			$_pers["exp_in_f"]+= experience($ydar,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			else
			$_pers["exp_in_f"]+= experience($ydar*0.3,
				$_pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
			//Закончили опыт
			
			if ($ydar>0) 
				$lg = "(".$block."<font class=hp>-".$ydar." HP</font>)"; 
			else 
				$lg = "(".$block.")";
			$LOG .= $nvs.$lg.",";
			sql("UPDATE bots_battle SET chp=".$persvs["chp"]." WHERE id=".$persvs["id"]."");
			
			$counter++;
		}
		
		$MAGIC_LOG = '';
		if ($counter>0)
		{
		$LOG = substr($LOG,0,strlen($LOG)-1);
		$die = substr($die,0,strlen($die)-1);
		if ($ALL_DAMAGE>0) 
			$MAGIC_LOG = $nyou." поразил".$male." ".$LOG. " с помощью заклинания <b>".$req["magic_koef"]."</b>x«<img src='images/magic/".$zakl["image"].".gif' height=12><font class=user>".$zakl["name"]."</font>» <font class=timef>«".$ypoint."»</font>";
		else 
			$MAGIC_LOG = $nyou." ".$pitalsa." поразить ".$LOG. " с помощью заклинания <b>".$req["magic_koef"]."</b>x«<img src='images/magic/".$zakl["image"].".gif' height=12><font class=user>".$zakl["name"]."</font>» <font class=timef>«".$ypoint."»</font>";
		}
		$_pers["fexp"] += $ALL_DAMAGE;
		$yron = $ALL_DAMAGE;
############################################################################
		$_pers["cma"]=$_pers["cma"] - $zakl["manacost"]*$req["magic_koef"];
		$_pers['m'.$zakl['type']] += 1/($_pers['m'.$zakl['type']]+1); 
		$z=2;
		$no_mana=false;

set_vars("`cma`='".$_pers["cma"]."',`m".$zakl["type"]."`=".$_pers['m'.$zakl["type"]].",fexp=fexp+".intval($ALL_DAMAGE).",exp_in_f=".$_pers["exp_in_f"].",kills=".$_pers["kills"],$_pers["uid"]);
sql ("UPDATE `u_blasts` SET cur_colldown=".tme()."+colldown, cur_turn_colldown=turn_colldown+".$_pers["f_turn"]." WHERE id=".$zakl["id"]);
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
elseif($zakl["manacost"]>$_pers["cma"]/$req["magic_koef"])
{
	$MAGIC_LOG = $nyou.' <i class=timef>Не хватает маны</i>';
	$z = 2;
}
else
{
	$MAGIC_LOG = $nyou.' <i class=timef>Нельзя использовать это заклинание</i>';
	$z = 2;
}
?>