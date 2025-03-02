<?
if ($pers["invisible"]>tme()){$pers["user"] = '<i>невидимка</i>';$invyou=1;$pers["pol"]='female';} else $invyou=0;
	if (!$invyou)
		$nyou = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
	else 
		$nyou = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";

	$a = sqla("SELECT * FROM u_auras WHERE uidp=".$pers["uid"]." and id=".intval($aid)."");
	if ($a and 
	$a["manacost"]<=$pers["cma"] and 
	$a["tlevel"]<=$pers["level"]	and 
	$a["ts6"]<=$pers["s6"] and 
	$a["tm1"]<=$pers["m1"] and 
	$a["tm2"]<=$pers["m2"] and 
	$a["cur_colldown"]<=tme() and 
	$a["cur_turn_colldown"]<=$pers["f_turn"])
	{
	if ($a["forenemy"])
	 $ps = sql("SELECT * FROM users WHERE cfight=".$pers["cfight"]." and fteam<>".$pers["fteam"]." and chp>0");
	else
	 $ps = sql("SELECT * FROM users WHERE cfight=".$pers["cfight"]." and fteam=".$pers["fteam"]." and chp>0");

$text2 = '';
	$p1 = false;
	while (true)
	{
		if (!$p1)
		 $p1 = mysql_fetch_array($ps);
		else
		 $p1 = $p2;
		$p2 = mysql_fetch_array($ps);
		if ($p1) 
		 $textFill = 1;
		
		if ($p1["invisible"]<=tme())
			$nvs = "<font class=bnick color=".$colors[$p1["fteam"]].">".$p1["user"]."</font>[".$p1["level"]."]";
		else 
			$nvs = "<font class=bnick color=".$colors[$p1["fteam"]]."><i>невидимка</i></font>[??]";
		
		if ($p2)
		 {
			aura_on($a["id"],$pers,$p1,0);
			$text2 .= $nvs.",";
		 }
		else
		 {
			aura_on($a["id"],$pers,$p1);
			$text2 .= $nvs.".";
			break;
		 }
	}
	}
	
	if ($textFill)
	$text .= $nyou." накладывает заклинание «<img src='images/magic/".$a["image"].".gif' height=12><font class=user>".$a["name"]."</font>» на ".$text2;
?>