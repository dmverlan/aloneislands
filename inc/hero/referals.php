<?
	$z = sqlr("SELECT COUNT(*) FROM `users` WHERE referal_uid=".UID."");
	echo "Вы привели в игру <b>".$z."</b> персонажа.<hr>";
	if ($pers["refc"] != $z)
	{
		set_vars("refc=".$z,UID);
	}
	echo "<i>Список рефералов:</i>";
	if (@$_GET["page"]) $c = intval($_GET["page"]);
	for ($i=0;$i<$z/30;$i++)
	{
		if ($c==$i)
 		 echo "<a class=pagerS href=main.php?gopers=referals&page=".$i.">".($i+1)."</a>";
		else
		 echo "<a class=pager href=main.php?gopers=referals&page=".$i.">".($i+1)."</a>";
	}
	$sort = 'uid';
	if (@$_FILTER["pers_sort"]=='level') $sort = 'level';
	if (@$_FILTER["pers_sort"]=='rank_i') $sort = 'rank_i';
	echo "<center class=but>";
	if ($sort == 'level')
		echo "<b><a class=timef href=main.php?gopers=referals&pers_sort=level>Отсортировать по уровню</a></b> | ";
	else
		echo "<a class=timef href=main.php?gopers=referals&pers_sort=level>Отсортировать по уровню</a> | ";
	if ($sort == 'uid')	
		echo "<b><a class=timef href=main.php?gopers=referals&pers_sort=uid>Отсортировать по дате</a></b> | ";
	else
		echo "<a class=timef href=main.php?gopers=referals&pers_sort=uid>Отсортировать по дате</a> | ";
	if ($sort == 'rank_i')
		echo "<b><a class=timef href=main.php?gopers=referals&pers_sort=rank_i>Отсортировать по ранку</a></b>";
	else
		echo "<a class=timef href=main.php?gopers=referals&pers_sort=rank_i>Отсортировать по ранку</a>";
	echo "</center>";
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class=LinedTable>';
	$frs = sql("SELECT user,online,location,state,level,uid,rank_i,sign,ds FROM `users` WHERE referal_uid=".UID." ORDER BY `".$sort."` DESC LIMIT ".($c*30+1).",30");
	$count = 1;
	while ($perssost = mysql_fetch_array($frs)) 
	{
	echo"<tr>";
	echo "<td class=ym>".($c*30+$count).".</td>";
	echo "<td class=timef>".$perssost["ds"]."</td>";
	echo "<td class=user>";
	echo "<img src='images/signs/".$perssost["sign"].".gif' title='".$perssost["state"]."'>";
	echo " ".$perssost["user"]."[<font class=lvl>".$perssost["level"]."</font>]";
	echo "<img src='images/info.gif' onclick=\"javascript:window.open('info.php?p=".$perssost["user"]."','_blank')\" style=cursor:pointer>";
	echo "</td>";
	if ($perssost["online"]==1) 
	 {
		$loc = sqla ("SELECT name FROM `locations` WHERE `id`='".$perssost['location']."'");
		echo "<td class=green>&nbsp;".$loc["name"]."</td>";
	 }
	else 
	 echo "<td class=items> оффлайн</td>";
	echo "</tr>";
	$count++;
	}
	echo '</table>';
?>