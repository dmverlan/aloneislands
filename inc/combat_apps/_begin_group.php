<?
	sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$app["id"]."");
	$p1 = '';
	$p2 = '';
	while($a = mysql_fetch_array($p,MYSQL_ASSOC))
	{
		if ($a["fteam"]==1)  $p1.=$a["user"]."|";
		else				 $p2.=$a["user"]."|";
	}
	begin_fight ($p1,$p2,"Групповой бой на арене [".$app["comment"]."]",$app["travm"]
	,$app["timeout"],$app["oruj"],$app["bplace"]);
?>