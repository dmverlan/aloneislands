<?php

if(@$_GET["deny"])
{
	$id = intval($_GET["deny"]);
	$r = sqla("SELECT * FROM avatar_request WHERE uid=".$id);
	if($r)
	{
		//echo dirname("../../../images/tmp/");
		unlink("./images/tmp/ava_".$r["uid"].".gif");
		sql("DELETE FROM avatar_request WHERE uid=".$id);
		set_vars("dmoney=dmoney+30",$r["uid"]);
	}
}
if(@$_GET["accept"])
{
	$id = intval($_GET["accept"]);
	$r = sqla("SELECT * FROM avatar_request WHERE uid=".$id);
	if($r)
	{
		$p = sqla("SELECT user,level,pol FROM users WHERE uid=".$r["uid"]);
		//echo dirname("../../../images/tmp/");
		rename("./images/tmp/ava_".$r["uid"].".gif","./images/persons/".$p["pol"]."_-".$r["uid"].".gif");
		sql("DELETE FROM avatar_request WHERE uid=".$id);
		set_vars("obr='-".$r["uid"]."'",$r["uid"]);
	}
}

$req = sql("SELECT * FROM avatar_request");
echo "<center>";
echo "<table class=but width=80%><tr>";
$i = 0;
while($r = mysql_fetch_array($req))
{
	$i++;
	$p = sqla("SELECT user,level FROM users WHERE uid=".$r["uid"]);
	echo "<td width=20% class=but2><b class=user>".$p["user"]." <b class=lvl>[".$p["level"]."]</b><a target=_blank href='info.php?id=".$r["uid"]."'><img src=images/i.gif></a>
	<br><img src='images/tmp/ava_".$r["uid"].".gif'>
	<br><a class=button href='main.php?accept=".$r["uid"]."'>Одобрить</a>
	<a class=button href='main.php?deny=".$r["uid"]."'>Удалить</a></td>";
	if($i%5==4)
		echo "</tr><tr>";
}
echo "</tr></table>";
echo "<center>";

?>