<?
	$punishs = sql("SELECT * FROM puns WHERE uid=".$pers["uid"]." and type=7");
	echo '<table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
	while($punish = mysql_fetch_assoc($punishs))
	{
		echo "<tr>";
		echo "<td bgcolor=#DDFFDD class=timef>".date("d.m.y H:i:s",$punish["date"])."</td>";
		echo "<td class=items>".$punish["who"];
		echo "</td>";
		echo "<td class=return_win>".$punish["reason"]."</td>";
		echo "</tr>";
	}
	echo '</table>';
?>