<?php 

include ("quests.php");

echo "<b class=about>Квестологи</b>";
$rsds = sql("SELECT * FROM residents");

echo "<table class=fightlong width=100% border=0>";
while($rs = mysql_fetch_array($rsds))
{
	$b = sqla("SELECT * FROM bots WHERE id=".$rs["id_bot"]);
	echo "<tr>";
	echo "<td><b class=user>".$rs["name"]."</b><b class=lvl>[".$b["level"]."]</b><img src=images/info.gif onclick=\"javascript:window.open('binfo.php?".$rs["id_bot"]."','_blank')\" style=\"cursor:point\"></td>";
	echo "<td class=gray>".$rs["description"]."</td>";
	echo "<td><img src='images/persons/".$rs["image"].".gif' height=50></td>";
	echo "<td class=green>".$rs["location"]."</td>";
	echo "<td class=timef>[".$rs["x"].":".$rs["y"]."]</td>";
	echo "<td class=ma>".$rs["kindness"]."</td>";
	echo "<td><a href=main.php?delete=".$rs["id"]." class=nt>Удалить</a></td>";
	echo "</tr>";
}
echo "</table><a class=nt href=main.php?newrs=1>Добавить квестолога</a>";
?>