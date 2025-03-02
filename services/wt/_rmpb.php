<?
	if ($_POST["C1"].$_POST["C2"].$_POST["C3"].$_POST["C4"].$_POST["C5"])
	{
		$c1 = intval($_POST["C1"]);
		$c2 = intval($_POST["C2"]);
		$c3 = intval($_POST["C3"]);
		$c4 = intval($_POST["C4"]);
		$c5 = intval($_POST["C5"]);
		$from = explode(".",$_POST["from"]);
		$to = explode(".",$_POST["to"]);
		$from = mktime(0,0,0,$from[1],$from[0],$from[2]);
		$to = mktime(0,0,0,$to[1],$to[0],$to[2]);
	}else
	{
		$c1 = 1;
		$c2 = 1;
		$c3 = 1;
		$c4 = 1;
		$c5 = 1;
		$from = mktime(0,0,0,1,1,2007);
		$to = time()+86400;
	}
	echo "<script>rmpb_filter('".$pers["user"]."',$c1,$c2,$c3,$c4,$c5,'".date("d.m.y",$from)."','".date("d.m.y",$to)."');</script>";
	
	$q = "date>".$from." and date<".$to." and (";
	if ($c1) $q.='type=1 or type=11 or ';
	if ($c2) $q.='type=2 or type=9 or ';
	if ($c3) $q.='type=3 or type=8 or ';
	if ($c4) $q.='type=4 or type=10 or ';
	if ($c5) $q.='type=5 or ';
	$q = substr($q,0,strlen($q)-3).")";
	$punishs = sql("SELECT * FROM puns WHERE uid=".$pers["uid"]." and ".$q." ORDER BY `date` DESC");
	
	echo '<table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
	$dur = Array();
	$dur[1] = 0;
	$dur[2] = 0;
	$dur[3] = 0;
	$dur[4] = 0;
	$dur[5] = 0;
	while($punish = mysql_fetch_assoc($punishs))
	{
		if ($punish["type"]==1) {$color = '#FFDDDD';$tt = 'МОЛЧАНИЕ';}
		if ($punish["type"]==11) {$color = '#FFEEEE';$tt = 'Снято МОЛЧАНИЕ';}
		if ($punish["type"]==2) {$color = '#DDAAAA';$tt = 'БЛОК';}
		if ($punish["type"]==9) {$color = '#EEBBBB';$tt = 'Снято БЛОК';}
		if ($punish["type"]==3) {$color = '#AADDAA';$tt = 'ТЮРЬМА';}
		if ($punish["type"]==8) {$color = '#BBEEBB';$tt = 'Снято Тюрьма';}
		if ($punish["type"]==4) {$color = '#AAAADD';$tt = 'КАРА';}
		if ($punish["type"]==10) {$color = '#BBBBEE';$tt = 'Снято КАРА';}
		if ($punish["type"]==5) {$color = '#AAAAFF';$tt = 'БЛОК ИНФЫ';}
		$dur[$punish["type"]] += $punish["duration"];
		echo "<tr>";
		echo "<td bgcolor=".$color." class=timef>".date("d.m.y H:i:s",$punish["date"])."</td>";
		echo "<td class=items>".$tt."</td>";
		echo "<td class=user>".$punish["who"];
		echo " <a href=info.php?p=".$punish["who"]." target=_blank>";
		echo "<img src=images/i.gif></a>&nbsp;";
		echo "</td>";
		echo "<td class=timef>".tp($punish["duration"])."</td>";
		echo "<td class=return_win>".$punish["reason"]."</td>";
		echo "</tr>";
	}
	echo '</table>';
	echo "Суммарная длительность:<br><b>МОЛЧАНИЕ</b>:".tp($dur[1]);
	echo "<br><b>ТЮРЬМА</b>:".tp($dur[3]);
	echo "<br><b>БЛОКИ</b>:".tp($dur[2]);
	echo "<br><b>КАРА</b>:".tp($dur[4]);
	echo "<hr>";
?>