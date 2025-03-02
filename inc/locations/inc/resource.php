<?
	if (rand(0,50)<1) set_vars("action=-1",UID);
	$r = intval($_GET["beginr"]);
	$r_get='<hr>';
	if (($r==$tunnel["r1id"] and $k=$tunnel["r1k"]) or
	($r==$tunnel["r2id"] and $k=$tunnel["r2k"]) or
	($r==$tunnel["r3id"] and $k=$tunnel["r3k"]))
	{
		$inst2 = sqla("SELECT id FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and p_type=13");
		$r_get .= "<i class=user>Добыча</i><br>";
		$r = sqla("SELECT * FROM resources WHERE image='".$r."'");
		$r["k"]=$k;
		$instp = $inst["durability"]/$inst["price"];
		if ($inst2["id"])
			$kk = mtrunc(floor((rand($inst["udmin"],$inst["udmax"])*sqrt($pers["sp12"]*100)/350)/sqrt($r["price"]*4) + rand(1,2)));
		else 
			$kk = mtrunc(floor((rand($inst["udmin"],$inst["udmax"])*sqrt($pers["sp12"]*100)/700)/sqrt($r["price"]*4) + rand(1,2)));
		$mdur = mtrunc($instp*$kk*($r["price"]/mtrunc(sqrt($pers["sp12"]/50)+1)))+1;
		if ($mdur>$inst["durability"]) $mdur = $inst["durability"];
			$za = 0;
		if (rand(1,500)==1)
		{
			$v = sqla("SELECT * FROM weapons 
			WHERE type='rune' and price/10<".$pers["sp7"]." and dprice=0 ORDER BY RAND()");
			if ($v)
			{
			insert_wp($v["id"],$pers["uid"],-1,0,$pers["user"]);
			$r_get .= "Разрывая клоки земли, вы откопали руну!<hr>";
			$pers["waiter"]=$t+$timed;
			sql("UPDATE wp SET durability=durability-".($kk/2+1)." WHERE id='".$inst["id"]."'");
			set_vars("waiter=".$pers["waiter"]."",$pers["uid"]);
			include("inc/inc/weapon2.php");
			$r_get .= "<script>".$text."</script>";
			$za = 1;
			}
		}
		if ($za==0)
		{
		if (rand(sqrt($pers["sp12"]),$pers["sp12"]*2)>$r["price"])
		{
		if ($kk>$r["k"]) $kk=$r["k"];
		$r_get .= "<font class=user>[".$r["name"]."]</font> : <b>".$kk."</b> ед.<br><font class=timef>".$kk*$r["price"]." LN</font><br>Долговечность кирки понизилась на ".round($mdur).".<br> Мирный опыт +3.<br>Добыча камней +".round((10/($pers["sp12"]+1)),3).".<br>Шахтёрство +".round((8/($pers["sp7"]+1)),3).".";
		if (!$inst2["id"])
		$r_get .= '<hr><div class=return_win><i>Совет: Без телеги количество добываемого ресурса в 2 раза меньше чем с телегой.</i></div><hr>';
		$pers["waiter"]=$t+$timed;
		sql("UPDATE wp SET durability=durability-".($mdur)." WHERE id='".$inst["id"]."'");
		set_vars("sp12=sp12+".round((10/($pers["sp12"]+1)),3).",sp7=sp7+".round((8/($pers["sp7"]+1)),3).",peace_exp=peace_exp+3
		,waiter=".$pers["waiter"].",tire=tire+10",$pers["uid"]);
		$rr = sqla("SELECT * FROM wp WHERE uidp='".$pers["uid"]."' and id_in_w='res..".$r["image"]."'");
		if ($rr["id"])
		sql("UPDATE wp SET price=price+".$kk*$r["price"].",weight=weight+".$kk.",durability=durability+".$kk.",max_durability=max_durability+".$kk." WHERE id=".$rr["id"]."");
		else
		{
		sql("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` ,`p_type`) 
VALUES (
0, '".$pers["uid"]."', '0','res..".$r["image"]."','".$kk*$r["price"]."', '0', 'resources/".$r["image"]."', '0', 'resources', 'resources', '".$r["name"]."', '', '".$kk."', '0', '".$kk."', '".$kk."','7');");
		}
		if ($r["image"]==$tunnel["r1id"])
		{sql("UPDATE mine SET r1k=r1k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r1k"]-=$kk;}
		if ($r["image"]==$tunnel["r2id"])
		{sql("UPDATE mine SET r2k=r2k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r2k"]-=$kk;}
		if ($r["image"]==$tunnel["r3id"])
		{sql("UPDATE mine SET r3k=r3k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r3k"]-=$kk;}
		}else
{		$r_get .= "Неудачным ударом кирки, вы испортили ресурс.<br>Долговечность кирки понизилась на ".round($kk/2+1).".<br> Мирный опыт +1.<br>Добыча камней -".round((5/($pers["sp12"]+1)),3).".<hr><i>Совет: Это происходит из-за слишком малого количества вашего умения \"Добыча камней\". Поднять это умение можно в университете.</i><hr>";
		$pers["waiter"]=$t+$timed;
		$mm = round((5/($pers["sp12"]+1)),3);
		if ($pers["sp12"]<1) $mm=0;
		sql("UPDATE wp SET durability=durability-".($kk/2+1)." WHERE id='".$inst["id"]."'");
		set_vars("sp12=sp12-".$mm.",peace_exp=peace_exp+1
		,waiter=".$pers["waiter"].",tire=tire+8",$pers["uid"]);
		if ($r["image"]==$tunnel["r1id"])
		{sql("UPDATE mine SET r1k=r1k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r1k"]-=$kk;}
		if ($r["image"]==$tunnel["r2id"])
		{sql("UPDATE mine SET r2k=r2k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r2k"]-=$kk;}
		if ($r["image"]==$tunnel["r3id"])
		{sql("UPDATE mine SET r3k=r3k-".$kk." WHERE x=".$tunnel["x"]." and 
		y=".$tunnel["y"]." and mine=".$tunnel["mine"]."");  $tunnel["r3k"]-=$kk;}
}
}
}
?>