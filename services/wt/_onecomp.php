<?
	echo "<a class=timef href=info.php?id=".$pers["uid"]."&do_w=onecomp&last=day>За последний день</a> | <a class=timef href=info.php?id=".$pers["uid"]."&do_w=onecomp&last=3day>За последние 3 дня</a> | <a class=timef href=info.php?id=".$pers["uid"]."&do_w=onecomp&last=week>За последнюю неделю</a> | <a class=timef href=info.php?id=".$pers["uid"]."&do_w=onecomp&last=all>Все</a>";
	if ($_GET["last"]=='day') $last = " and time>".(tme()-86400);
	if ($_GET["last"]=='3day') $last = " and time>".(tme()-3*86400);
	if ($_GET["last"]=='week') $last = " and time>".(tme()-7*86400);
	if (empty($_GET["last"]) or $_GET["last"]=='all') $last = "";
	$bs = sql("SELECT * FROM one_comp_logins WHERE (uid1=".$pers["uid"]." or uid2=".$pers["uid"].") ".$last);
	echo '<table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
	while($b = mysql_fetch_assoc($bs))
	{
		$another = ($pers["uid"]==$b["uid1"])?$b["uid2"]:$b["uid1"];
		$another = sqlr("SELECT user FROM users WHERE uid=".intval($another));
		echo "<tr>";
		echo "<td bgcolor=#DDFFDD class=timef>".date("d.m.y H:i:s",$b["time"])."</td>";
		echo "<td class=but>".$pers["user"];
		echo "</td>";
		echo "<td class=but>".$another."<a href=info.php?p=".$another." target=_blank><img src=images/i.gif></a></td>";
		echo "</tr>";
	}
	echo '</table>';
?>