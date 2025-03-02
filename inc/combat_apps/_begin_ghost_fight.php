<?

function begin_ghost_fight($uid,$namesvs,$type,$travm,$timeout,$oruj,$loc,$battle_type = 0){
	$bid = -1*$uid;//floor(sqlr("SELECT MAX(`id`) FROM bots")/100)+100;
	$p = catch_user(intval($uid));

	if ($p["ctip"]==-1 and $p["lb_attack"]<tme())
	{
	sql ("INSERT INTO `bots` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `pol` , `obr` , `wears` , `rank_i` , `id` , `dropvalue` ) 
VALUES (
'".$p["user"]."', '".$p["level"]."', 'none', '".$p["s1"]."', '".$p["s2"]."', '".$p["s3"]."', '".$p["s4"]."', '".$p["s5"]."', '".$p["s6"]."', '".$p["kb"]."', '".$p["mf1"]."', '".$p["mf2"]."', '".$p["mf3"]."', '".$p["mf4"]."', '".$p["mf5"]."', '".$p["udmin"]."', '".$p["udmax"]."', '".$p["hp"]."', '".$p["ma"]."', 'male', '".$p["obr"]."', '', '".$p["rank_i"]."', ".$bid." , ".$uid.");");
	$idf = begin_fight("bot=".$bid."|",$namesvs,$type,$travm,$timeout,$oruj,$loc,$battle_type);
	sql("DELETE FROM `bots` WHERE `id` = ".$bid);
	set_vars("silence=".$idf,$uid);
	}
}

?>