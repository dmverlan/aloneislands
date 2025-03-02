<?
	if (@$_POST["comment"]) $_POST["comment"] = str_replace(":",";",$_POST["comment"]);
	#ADD apps duel::
	if (@$_POST["travm"] and $cat==1 and !$pers["apps_id"])
	{
		$c1 = intval($_POST["travm"]);
		$c2 = intval($_POST["oruj"]);
		if ($weared_count) $c2 = 1;
		$c3 = intval($_POST["timeout"]);
		$c4 = str_replace("'","",$_POST["comment"]);
		if ($c4=='описание') $c4 = '[без описания]';
		$bplace = intval($_POST["bplace"]);
		if ($bplace==0);
		elseif ($bplace==1);
		elseif ($bplace==3);
		elseif ($bplace==5);
		else $bplace=0;
		if ($pers["level"]<10)$bplace=0;;
		sql("INSERT INTO `app_for_fight` (`uid` , `oruj` , `travm` , `timeout` , `atime` , `count1` , `count2` , `minlvl1` , `minlvl2` , `maxlvl1` , `maxlvl2` , `pl1` , `pl2` , `comment` , `type` ,`bplace`) VALUES (".$pers["uid"].", ".$c2.", ".$c1.", ".$c3.", ".(time()+300).", 1, 0, ".$pers["level"].", 0, 0, 0, 1, 0, '".$c4."', 1 ,".$bplace.");");
		$id = mysql_insert_id($main_conn);
		$pers["apps_id"] = $id;
		set_vars("apps_id=".$id.",fteam=1",$pers["uid"]);
		echo "da('Заявка удачно подана!<br>');";
	}
	#ADD apps group::
	if (@$_POST["travm"] and $cat==2 and !$pers["apps_id"])
	{
		$c1 = intval($_POST["travm"]);
		$c2 = intval($_POST["oruj"]);
		if ($weared_count) $c2 = 1;
		$c3 = intval($_POST["timeout"]);
		$c4 = str_replace("'","",$_POST["comment"]);
		$c5 = intval($_POST["count1"]);
		$c6 = intval($_POST["count2"]);
		if ($c5<1) $c5=1;
		if ($c6<1) $c6=1;
		$c7 = intval($_POST["minlvl1"]);
		$c8 = intval($_POST["minlvl2"]);
		$c9 = intval($_POST["maxlvl1"]);
		$c10 = intval($_POST["maxlvl2"]);
		$c11 = intval($_POST["atime"]);
		if ($c11<120) $c11=120;
		if ($c4=='описание') $c4 = '[без описания]';
		$bplace = intval($_POST["bplace"]);
		if ($pers["level"]<10)$bplace=0;
		if ($bplace==0);
		elseif ($bplace==1);
		elseif ($bplace==3);
		elseif ($bplace==5);
		else $bplace==0;
		if ($bplace)
		{
			if ($c5>10) $c5=10;
			if ($c6>10) $c6=10;
		}
		else
		{
			if ($c5>50) $c5=50;
			if ($c6>50) $c6=50;
		}
		sql("INSERT INTO `app_for_fight` (`uid` , `oruj` , `travm` , `timeout` , `atime` , `count1` , `count2` , `minlvl1` , `minlvl2` , `maxlvl1` , `maxlvl2` , `pl1` , `pl2` , `comment` , `type` ,`bplace`) VALUES (".$pers["uid"].", ".$c2.", ".$c1.", ".$c3.", ".(time()+$c11).", ".$c5.", ".$c6.", ".$c7.", ".$c8.", ".$c9.", ".$c10.", 1, 0, '".$c4."', 2, ".$bplace.");");
		$id = mysql_insert_id($main_conn);
		$pers["apps_id"] = $id;
		set_vars("apps_id=".$id.",fteam=1",$pers["uid"]);
		echo "da('Заявка удачно подана!<br>');";
	}
	#ADD apps haot::
	if (@$_POST["travm"] and $cat==3 and !$pers["apps_id"])
	{
		$c1 = intval($_POST["travm"]);
		$c2 = intval($_POST["oruj"]);
		if ($weared_count) $c2 = 1;
		$c3 = intval($_POST["timeout"]);
		$c4 = str_replace("'","",$_POST["comment"]);
		$c5 = intval($_POST["count1"]);
		if ($c5<2) $c5=2;
		$c7 = intval($_POST["minlvl1"]);
		$c9 = intval($_POST["maxlvl1"]);
		$c11 = intval($_POST["atime"]);
		if ($c11<120) $c11=120;
		if ($c4=='описание' or $c4=='') $c4 = '[без описания]';
		$bplace = intval($_POST["bplace"]);
		if ($bplace==0);
		elseif ($bplace==1);
		elseif ($bplace==3);
		elseif ($bplace==5);
		else $bplace==0;
		if ($pers["level"]<10)$bplace=0;;
		if ($bplace and $c5>14) $c5=14;
		elseif ($c5>100) $c5=100;
		sql("INSERT INTO `app_for_fight` (`uid` , `oruj` , `travm` , `timeout` , `atime` , `count1` , `minlvl1` , `maxlvl1` , `pl1` , `comment` , `type` ,`bplace`) VALUES (".$pers["uid"].", ".$c2.", ".$c1.", ".$c3.", ".(time()+$c11).", ".$c5.", ".$c7.", ".$c9.", 1, '".$c4."', 3 , ".$bplace.");");
		$id = mysql_insert_id($main_conn);
		$pers["apps_id"] = $id;
		set_vars("apps_id=".$id.",fteam=1",$pers["uid"]);
		echo "da('Заявка удачно подана!<br>');";
	}
?>