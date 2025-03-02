<?
 $t=time();
$y = $pers["y"];
$x = $pers["x"];
if ($priv["emap"]==2)
{
if (@$_GET["res"])
{
	sql("UPDATE nature SET wood=".intval($_POST["wood"]).",herbal=".intval($_POST["herbal"]).",fishing=".intval($_POST["fishing"]).",agriculture=".intval($_POST["agriculture"])." WHERE x=".($x)." and y=".($y)."");
}
if (@$_GET["bts"])
{
	sql("UPDATE nature SET bot=".intval($_POST["bid"]).",blvlmin=".intval($_POST["minlvl"]).",blvlmax=".intval($_POST["maxlvl"])." WHERE x=".($x)." and y=".($y)."");
	$buser = sqlr("SELECT user FROM bots WHERE id=".intval($_POST["bid"]));
	$b1 = sqlr("SELECT id FROM bots WHERE user='".$buser."' and level=".intval($_POST["minlvl"]));
	if(!$b1)
		$b1 = sqlr("SELECT MIN(id) FROM bots WHERE user='".$buser."'");
	$b2 = sqlr("SELECT id FROM bots WHERE user='".$buser."' and level=".intval($_POST["maxlvl"]));
	if(!$b2)
		$b2 = sqlr("SELECT MAX(id) FROM bots WHERE user='".$buser."'");
	sql("INSERT INTO nature_bots(`x` ,`y` ,`idmin` ,`idmax` ,`frq` )
VALUES ('".$x."', '".$y."', '".$b1."', '".$b2."', '".intval($_POST["frq"])."')");
}
if (@$_GET["delete"])
{
	sql("DELETE FROM nature WHERE x=".($x)." and y=".($y)."");
}
if (@$_GET["bd"])
{
	sql("DELETE FROM nature_bots WHERE x=".($x)." and y=".($y)." and idmin=".intval($_GET["idmin"])." and idmax=".intval($_GET["idmax"])."");
	sql("UPDATE nature SET bot=0,blvlmin=0,blvlmax=0 WHERE x=".($x)." and y=".($y)."");
}
if (@$_GET["add"])
{
	sql("INSERT INTO nature (x,y) VALUES (".$x.",".$y.")");
}
if (@$_GET["gotox"].$_GET["gotoy"])
{
	set_vars("x=".intval($_GET["gotox"]).",y=".intval($_GET["gotoy"])."",UID);
	$x = intval($_GET["gotox"]);
	$y = intval($_GET["gotoy"]);
}
if (@$_GET["type"])
{
	sql("UPDATE nature SET type=".intval($_GET["type"])." WHERE x=".($x)." and y=".($y)."");
}
if (@$_POST["name"])
{
	sql("UPDATE nature SET name='".$_POST["name"]."' WHERE x=".($x)." and y=".($y)."");
}
if (@$_GET["act"]=='emp')
{
	sql("UPDATE nature SET
		 passable='".intval($_POST["passable"])."',
		 buildable='".intval($_POST["buildable"])."',
		 winnable='".intval($_POST["winnable"])."',
		 teleport='".intval($_POST["teleport"])."'
	 WHERE x=".($x)." and y=".($y)."");
}
if (@$_GET["goidd"])
{
	sql("UPDATE nature SET go_id='".$_GET["go_id"]."' WHERE x=".($x)." and y=".($y)."");
}
}

$cells_around = sql("SELECT x,y,type,wood,fishing,herbal,agriculture,go_id,bot,blvlmin,blvlmax FROM nature WHERE x>=".($x-5)." and x<=".($x+5)." and 
y>=".($y-4)." and y<=".($y+4)."");

$maked_str = '';
while ($cc = mysql_fetch_array($cells_around))
{
 $bimg = sqlr("SELECT obr FROM bots WHERE id=".intval($cc["bot"]));
 $maked_str .= '<'.$cc["x"].'_'.$cc["y"].'@'.$cc["type"].','.$cc["wood"].','.$cc["herbal"].','.$cc["fishing"].','.$cc["agriculture"].','.$cc["go_id"].','.$bimg.','.$cc["blvlmin"].','.$cc["blvlmax"].'@>';
}

$cell = sqla("SELECT * FROM nature WHERE x=".($x)." and y=".($y)."");
$max_bot_lvl = $cell["blvlmax"];
$bts = sql("SELECT * FROM nature_bots WHERE x=".($x)." and y=".($y)."");
$bots = "<table border=0 class=but>";
while($b = mysql_fetch_array($bts))
{
	$b1 = sqla("SELECT user,level,id FROM bots WHERE id=".$b["idmin"]);
	$b2 = sqla("SELECT user,level,id FROM bots WHERE id=".$b["idmax"]);
	$bots .= "<tr>";
	$bots .= "<td class=but2>";
	$bots .= "<b class=user>".$b1["user"]."</b> <font class=lvl>[".$b1["level"]."]</font>";
	$bots .= "</td>";
	$bots .= "<td class=but2>";
	$bots .= "<font class=lvl>[".$b2["level"]."]</font>";
	$bots .= "</td>";
	$bots .= "<td class=but2 onclick=\"location='main.php?bd=1&idmin=".$b["idmin"]."&idmax=".$b["idmax"]."'\">";
	$bots .= "<b class=ma>".$b["frq"]."</b>";
	$bots .= "<b class=hp>[X]</b>";
	$bots .= "</td>";
	$bots .= "</tr>";
}
$bots .= "</table>";
$buser = sqla("SELECT user FROM bots WHERE id='".$cell["bot"]."'");
$bots .= "<a href=\"javascript:shm_cellbots('".$buser["user"]."',".$cell["blvlmin"].",".$cell["blvlmax"].")\" class=button>Добавить бота</a><hr>";


if ($cell["type"]==0)$cell_type = "Город. Здесь вам ничего не угрожает. Но остерегайтесь воров!";
if ($cell["type"]==1)$cell_type = "Дорога. Здесь проходят торговые пути. Возможна встреча с разбойниками.";
if ($cell["type"]==2)$cell_type = "Поля, луга, богатые растительностью.";
if ($cell["type"]==3)$cell_type = "Плоскогорье. Пески, сухой жаркий воздух(Анис, осот, финики...)";
if ($cell["type"]==4)$cell_type = "Лесонасождения. Птички поют, зверушки бегают.";
if ($cell["type"]==5)$cell_type = "Здесь давно не осталось ничего живого, кроме зловещих демонов";
if ($cell["type"]==6)$cell_type = "Вода. Здесь вы можете заняться рыбной ловлей.";
if ($cell["type"]==7)$cell_type = "Пещера. Сумрак, плохая видимость. Сверкают сталактиты.";
if ($cell["type"]==8)$cell_type = "Заболоченная местность. Шагайте осторожно, чтобы не застрять здесь навечно.";
$resources = "";
if ($cell["fishing"])$resources .= "Рыба(<b>".$cell["fishing"]."</b>).";
if ($cell["wood"])$resources .= "Древесина(<b>".$cell["wood"]."</b>).";
if ($cell["agriculture"])$resources .= "Культурные растения(<b>".$cell["agriculture"]."</b>).";
if ($cell["herbal"])$resources .= "Травы для алхимии(<b>".$cell["herbal"]."</b>).";
if ($resources == "") $resources = "нет.";

	if (substr_count($pers["rank"],"<admin>")) $cell_type .= '<a class=bga href=main.php?go=back>Выйти из редактора</a>';
	
$goin = "Можно войти в : <i>".$cell["go_id"]."</i>";
?>
<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>
	<tr>
<td class=loc align=center width=25% valign=top>
<script type="text/javascript" src="js/naturen_adm.js?1"></script>
<script>
<?
echo "var go_str = '".$maked_str."';"; 
echo "document.write(return_minicart(".$x.",".$y."));";
?>
</script>
<?
if ($cell)
{
echo $bots;
echo "<hr>";
echo "<a href=javascript:shm_resedit() class=timef>[".$resources."]</a><hr><a href=\"javascript:shm_cellgoin('".$cell["go_id"]."')\" class=timef>".$goin."</a><hr><a href=javascript:shm_celltype(".$cell["type"].") class=timef>".$cell_type."</a><hr>";
}
else
{
	echo "По этой клетке нельзя пройти!";
}
?>
<form method="POST" action=main.php>
<input type="submit" value="Обновить" class="laar">
</form></td>
<td align=center width=50% class=loc valign=top>
<?
	echo '
	<script>
	show_nature('.$x.','.$y.');
	</script>';
?>
</td>
<td class=loc align=center width=25% valign=top>
<?
echo "<font class=user>".$x." ; ".$y."</font><br>";
if ($cell["go_id"]) $out = sqla("SELECT name FROM `locations` WHERE `id`='".$cell["go_id"]."'");
if ($out["name"])  echo "<font class=items>Вход в ".$out["name"]."</font><br>";
if ($cell) echo "<a href=\"javascript:shm_cellname('".$cell["name"]."')\" class=timef><b>[".$cell["name"]."]</b></a><br>";
echo "<a href=javascript:shm_xy() class=timef>Перейти в XY</a><br>";
if ($cell) 
	echo "<a href=main.php?delete=1 class=timef>Удалить клетку</a><br>";
else
	echo "<a href=main.php?add=1 class=timef>Добавить клетку</a><br>";

echo "<script>";
echo "var bn=[";
$allbots_names = sql("SELECT user,id,obr,pol,MIN(level) as minlvl,MAX(level) as maxlvl FROM bots GROUP BY user;");
while ($bn = mysql_fetch_array($allbots_names,MYSQL_ASSOC))
{
	echo "['".$bn["user"]."',".$bn["id"].",'".$bn["pol"]."_".$bn["obr"]."',".$bn["minlvl"].",".$bn["maxlvl"]."],";
}
echo "''];";
echo "</script>";

echo "<center class=but>";
if ($cell["passable"]) 
	echo "<b class=green>Можно пройти</b>";
else
	echo "<b class=hp>Нельзя пройти</b>";
echo "<BR/>";
if ($cell["winnable"]) 
	echo "<b class=green>Можно завоевать</b>";
else
	echo "<b class=hp>Нельзя завоевать</b>";
echo "<BR/>";
if ($cell["buildable"]) 
	echo "<b class=green>Можно построить</b>";
else
	echo "<b class=hp>Нельзя построить</b>";
if ($cell["teleport"]) 
	echo "<BR/><b class=blue>ТЕЛЕПОРТ[".$cell["teleport"]." LN]</b>";
echo "<BR/>";	
echo "<a class=blocked href='javascript:shm_mainparams(".$cell["passable"].",".$cell["buildable"].",".$cell["winnable"].",".$cell["teleport"].")'>Изменить параметры</a>";
echo "</center>";
		

if ($cell["type"])
for ($i=0;$i<=500;$i+=40)
{
echo "<p class=timef>Скорость перемещения сюда при <b>".$i."</b> равна <b>".mtrunc(floor(($cell["type"]*10+10)-($i/8)))."</b> сек.</p>";
if (mtrunc(floor(($cell["type"]*10+10)-($i/8)))==0) break;
}
?>
</td>
</tr>
</table>

