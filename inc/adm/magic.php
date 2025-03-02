<div class=inv>
<?
if ($priv["emagic"]>1)
{
	if (isset($_GET["delete"]))
	{
	if (sql("DELETE FROM blasts WHERE id='".$_GET["delete"]."'")) echo "<center class=return_win>Удалено!</center>";
	}
	if (isset($_GET["adelete"]))
	{
	if (sql("DELETE FROM auras WHERE id='".$_GET["adelete"]."'")) echo "<center class=return_win>Удалено!</center>";
	}
	if (@$_GET["new_wp"])
	{
		$mid = sqlr("SELECT MAX(id) FROM blasts",0)+1;
		sql("INSERT INTO blasts(`id`,`name`,`image`) VALUES
		('".$mid."','Новая магия ".$mid."',1)");
		echo "<center class=return_win>Добавлено.</center>";
	}
	if (@$_GET["new_ap"])
	{
		$mid = sqlr("SELECT MAX(id) FROM auras",0)+1;
		sql("INSERT INTO auras(`id`,`name`,`image`) VALUES
		('".$mid."','Новая магия ".$mid."',1)");
		echo "<center class=return_win>Добавлено.</center>";
	}
	if (@$_GET["edit"] and @$_GET["c"]!=2)
	{
		include("edit_blast.php");
		die("<hr>");
	}
	if (@$_GET["edit"] and @$_GET["c"]==2)
	{
		include("edit_aura.php");
		/*$memcache->set('LOCK'.$uid, 0);
		$memcache->set('LOCKR'.$uid, 0);*/
		die("<hr>");
	}

	if (@$_GET["dall"] and @$_GET["c"]!=2)
	{
		sql("DELETE FROM u_blasts WHERE id_in_w='".intval($_GET["dall"])."'");
		echo "<center class=return_win>Удалено у всех</center>";
		sql("UPDATE blasts SET learnall=0 WHERE id='".intval($_GET["dall"])."'");
	}
	if (@$_GET["dall"] and @$_GET["c"]==2)
	{
		sql("DELETE FROM u_auras WHERE id_in_w='".intval($_GET["dall"])."'");
		sql("UPDATE auras SET learnall=0 WHERE id='".intval($_GET["dall"])."'");
		echo "<center class=return_win>Удалено у всех</center>";
	}

	if (@$_GET["lall"] and @$_GET["c"]!=2)
	{
		sql("UPDATE blasts SET learnall=1 WHERE id='".intval($_GET["lall"])."'");
		echo "<center class=return_win>Обучат все как только перезайдут в игру</center>";
	}
	if (@$_GET["lall"] and @$_GET["c"]==2)
	{
		sql("UPDATE auras SET learnall=1 WHERE id='".intval($_GET["lall"])."'");
		echo "<center class=return_win>Обучат все как только перезайдут в игру</center>";
	}
}
if ($priv["emagic"]>0)
{
$s1 = 'bg'; $s2 = 'bg';
if (@$_GET["c"]!=2) $s1 = 'bga'; else $s2 = 'bga';
echo "<table border=0 width=100%><tr><td width=50%><a href=main.php?c=1 class=".$s1.">Маг.Удары.</a></td><td width=50%><a href=main.php?c=2 class=".$s2.">Ауры</a></td></tr></table>";
if (@$_GET["c"]!=2)
 {
	$zz = sql("SELECT * FROM blasts");
echo '<a href="main.php?new_wp=1" class=bga>НОВЫЙ маг.Удар В ЭТОМ РАЗДЕЛЕ</a><table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF class=LinedTable>';
	while($z = mysql_fetch_array($zz))
	{
		echo "<tr>";
		echo "<td width=10><img src=images/drop.gif onclick='if(confirm(\"УДАЛИТЬ???\")) location=\"main.php?delete=".$z["id"]."\"' style='cursor:pointer'></td>";
		echo "<td class=timef width=50>id:".$z["id"]."</td>";
		echo "<td><a href=main.php?edit=".$z["id"]."><img src=images/magic/".$z["image"].".gif></a></td><td class=user>".$z["name"]."<a href=main.php?lall=".$z["id"]." class=bga>Обучить всех</a><a href=main.php?dall=".$z["id"]." class=bga>Убрать у всех</a></td>";
		if ($z["where_buy"]==0) echo "<td class=green>купить в храме</td>";
		else echo "<td class=red><b>нигде</b></td>";
		echo "<td class=lvl>Уровень: ".$z["tlevel"].", С/В: ".$z["ts6"]."</td>";
		echo "<td class=lvl>Manacost: ".$z["manacost"]."</td>";
		echo "<td class=time>".$z["price"]." LN</td>";
		echo "<td class=timef>".$z["udmin"]."-".$z["udmax"]."</td>";
		echo "</tr>";
	}
echo "</table>";
 }
 else
 {
 	$zz = sql("SELECT * FROM auras");
	echo '<a href="main.php?new_ap=1&c=2" class=bga>НОВая аура В ЭТОМ РАЗДЕЛЕ</a><table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF class=LinedTable>';
	while($z = mysql_fetch_array($zz))
	{
		echo "<tr>";
		echo "<td width=10><img src=images/drop.gif onclick='if(confirm(\"УДАЛИТЬ???\")) location=\"main.php?adelete=".$z["id"]."&c=2\"' style='cursor:pointer'></td>";
		echo "<td class=timef width=50>id:".$z["id"]."</td>";
		echo "<td><a href=main.php?edit=".$z["id"]."&c=2><img src=images/magic/".$z["image"].".gif></a></td><td class=user>".$z["name"]."<a href=main.php?lall=".$z["id"]."&c=2 class=bga>Обучить всех</a><a href=main.php?dall=".$z["id"]."&c=2 class=bga>Убрать у всех</a></td>";
		if ($z["where_buy"]==0) echo "<td class=green>купить в храме</td>";
		else echo "<td class=red><b>нигде</b></td>";
		echo "<td class=lvl>Уровень: ".$z["tlevel"].", С/В: ".$z["ts6"]."</td>";
		echo "<td class=lvl>Manacost: ".$z["manacost"]."</td>";
		echo "<td class=timef>Длительность: ".tp(intval($z["esttime"])).", ".intval($z["turn_esttime"])." ходов.</td>";
		if ($z["forenemy"]==0)
			echo "<td class=green>На свою команду</td>";
		elseif ($z["forenemy"]==1)
			echo "<td class=red><b>На чужую команду</b></td>";
		else
			echo "<td class=blue>На любую команду</td>";
		echo "<td class=time>".$z["price"]." LN</td>";
		echo "</tr>";
	}
	echo "</table>";
 }
}
?>
</div>