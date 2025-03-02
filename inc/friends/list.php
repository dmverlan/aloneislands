<?
	if (@$_POST["friend_nick"])
	{
		$p = sqla("SELECT uid,user FROM users WHERE user='".$_POST["friend_nick"]."'");
		if ($p)
		{
		 sql("INSERT INTO `friendship` 
		 ( `uid1` , `uid2` , `type` ) VALUES ('".UID."', '".$p["uid"]."', '1');");
		 echo "<center class=return_win>Персонаж <b>".$p["user"]."</b> получил уведомление что вы его друг. Ждите согласия.</center>";
		 say_to_chat('s',"Персонаж <b>".$pers["user"]."</b> предлагает вам дружбу.",1,$p["user"],'*',0);
		}
	}
	
	if (@$_GET["get_friendship"])
	{
		sql("UPDATE friendship SET type=0 WHERE uid1=".intval($_GET["get_friendship"]));
	}
		
	if (@$_GET["cans_friendship"])
	{
		sql("DELETE FROM friendship WHERE uid1=".intval($_GET["cans_friendship"])." and uid2=".intval($pers["uid"]));
		sql("DELETE FROM friendship WHERE uid2=".intval($_GET["cans_friendship"])." and uid1=".intval($pers["uid"]));
	}
?>
<SCRIPT src="js/self.js"></SCRIPT>
<table style="width: 100%">
	<tr>
		<td class="title">Список друзей:</td>
	</tr>
	<tr>
		<td align=center>
		<table border="0" width="80%" cellspacing="0" cellpadding="0" class=LinedTable>
<?
	$q = '';
	$q2 = '';
	$fl = sql("SELECT * FROM friendship WHERE uid1=".UID." or uid2=".UID);
	while($f = mysql_fetch_array($fl))
	{
		if ($f["type"]==0)
		{
		if ($f["uid1"]==UID) 
		 $q .= 'uid='.$f["uid2"]." or ";
		else
		 $q .= 'uid='.$f["uid1"]." or ";
		}
		else
		{
		if ($f["uid1"]!=UID) 
		 $q2 .= 'uid='.$f["uid1"]." or ";
		}
	}
	$q = substr($q,0,strlen($q)-3);
	$q2 = substr($q2,0,strlen($q2)-3);
	#1
	if ($q)
	{
	$frs = sql("SELECT user,online,location,state,level,aura,uid,rank_i,sign FROM `users` WHERE ".$q." ORDER BY `rank_i` DESC");
	while ($perssost = mysql_fetch_array($frs)) 
	{
	echo"<tr><td class=user>";
	echo"<img src='images/pr.gif' onclick=\"javascript:top.say_private('".$perssost["user"]."')\" style=cursor:pointer> &nbsp;&nbsp;";
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
	echo "<td style='width:100'><a class=bg href=main.php?cans_friendship=".$perssost["uid"].">Убрать</a></td>";
	echo "</tr>";
	}
	}	
	else
	 {
		echo "<tr>";
		echo "<td class=puns>";
		echo "У вас нет друзей, пока...";
		echo "</td>";
		echo "</tr>";
	 }
?>
		</table>
		</td>
	</tr>
	<tr>
		<td class="title">Заявки на дружбу с вами:</td>
	</tr>
	<tr>
		<td align=center>
		<table border="0" width="80%" cellspacing="0" cellpadding="0" class=LinedTable>
	<?
	if ($q2)
	{
	$frs = sql("SELECT user,online,location,state,level,aura,uid,rank_i,sign FROM `users` WHERE ".$q2." ORDER BY `rank_i` DESC");
	while ($perssost = mysql_fetch_array($frs)) 
	{
	echo"<tr><td class=user>";
	echo"<img src='images/pr.gif' onclick=\"javascript:top.say_private('".$perssost["user"]."')\" style=cursor:pointer> &nbsp;&nbsp;";
	echo "<img src='images/signs/".$perssost["sign"].".gif' title='".$perssost["state"]."'>";
	echo " ".$perssost["user"]."[<font class=lvl>".$perssost["level"]."</font>]";
	echo "<img src='images/info.gif' onclick=\"javascript:window.open('info.php?p=".$perssost["user"]."','_blank')\" style=cursor:pointer>";
	if (strpos(" |".$perssost["aura"],"|molch|")<>0) 
	 echo "<img src='images/signs/molch.gif' title='Заклинание Молчания'>";
	echo "</td>";
	if ($perssost["online"]==1) 
	 {
		$loc = sqla ("SELECT name FROM `locations` WHERE `id`='".$perssost['location']."'");
		echo "<td class=green>&nbsp;".$loc["name"]."</td>";
	 }
	else 
	 echo "<td class=items> </td>";
	echo "<td style='width:200'><a class=bg href=main.php?get_friendship=".$perssost["uid"].">Дружить</a>
	<a class=bg href=main.php?cans_friendship=".$perssost["uid"].">Отказать</a></td>";
	echo "</tr>";
	}
	}	
	else
	 {
		echo "<tr>";
		echo "<td class=puns>";
		echo "У вас нет заявок на дружбу с вами.";
		echo "</td>";
		echo "</tr>";
	 }
	?>
		</table>
		</td>
	</tr>
	<tr>
		<td><a class=bga href='javascript:add_friend()'>Добавить в друзья</a></td>
	</tr>
</table>
