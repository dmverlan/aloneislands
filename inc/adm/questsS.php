<?php 

include "quests.php";

echo "<br><hr><br><b class=about>Речёвки</b>";
$sps = sql("SELECT * FROM speech WHERE id_from=0");
echo "<table class=fightlong width=100% border=0>";
while($sp = mysql_fetch_array($sps))
{
	echo show_speech($sp);
}
echo "</table><a class=nt href=main.php?newsp=1>Добавить речь</a>";

?>