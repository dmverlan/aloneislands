<?php

if(empty($_GET["inv"]))
{
$cl = sql("SELECT * FROM clans");
echo "<table class=but border=0>";
$i = 1;
while($c = mysql_fetch_array($cl))
{
	echo "<tr>";
	echo "<td>".($i++)."</td>";
	echo "<td class=user><img src=images/signs/".$c["sign"].".gif>".$c["name"]."[".$c["level"]."]</td>";
	echo "<td class=green><a href=info.php?p=".$c["glav"]." target=_blank>".$c["glav"]."</a></td>";
	echo "<td><a class=bg href=http://".$c["sait"]." target=_blank>".$c["sait"]."</a></td>";
	echo "<td><a class=bga href=main.php?inv=".$c["sign"].">Казна</a></td>";
	echo "</tr>";
}
echo "</table>";
}
else
{
	if(@$_GET["delete"])
	{
		sql("UPDATE wp SET durability=0 WHERE id=".intval($_GET["delete"]));
		echo "Удалено.";
	}
	$sign = $_GET["inv"];
	$clan = sqla("SELECT * FROM clans WHERE sign='".$sign."'");
	$delete_button1 = "<input type=button class=but onclick=\"location='main.php?inv=".$clan["sign"]."&delete=";
	$delete_button2 = "'\" value='Удалить'>";
	include("./inc/inc/clans/inv.php");
}
?>