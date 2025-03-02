<?
	sql("DELETE FROM app_for_fight WHERE id=".$app["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$app["id"]."");
	$us1 = Array();
	$r = Array();
	while($a = mysql_fetch_array($p,MYSQL_ASSOC))
	{
		$us1[] = $a["user"];
		$r[] = $a["rank_i"];
	}
	$c1 = count($us1);
	for ($i=0;$i<$c1;$i++)
	 for($j=0;$j<$i;$j++)
	 if ($r[$j]<$r[$j+1])
	 {
		$tmp = $r[$j];
		$r[$j] = $r[$j+1];
		$r[$j+1] = $tmp;
		$tmp = $us1[$j];
		$us1[$j] = $us1[$j+1];
		$us1[$j+1] = $tmp;
	 }
	$p1 = '';
	$p2 = '';
	for ($i=0;$i<$c1;$i++)
		if ($i%2==0)  		$p1.=$us1[$i]."|";
		else				$p2.=$us1[$i]."|";
	begin_fight ($p1,$p2,"Хаотический бой на арене [".$app["comment"]."]",$app["travm"]
	,$app["timeout"],$app["oruj"],$app["bplace"]);
?>