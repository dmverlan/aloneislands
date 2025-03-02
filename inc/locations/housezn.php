<center><?
if (@$_GET["la"])
{
	$a = sqla("SELECT id,price,dprice,name FROM auras WHERE
	id=".intval($_GET["la"])." and 
	tlevel<=".$pers["level"]." and 
	ts6<=".$pers["s6"]." and 
	tm1<=".$pers["m1"]." and 
	tm2<=".$pers["m2"]." and
	price<=".$pers["money"]." and
	dprice<=".$pers["dmoney"]." and
	where_buy<2");
	
	if ($a)
	{
		insert_aura($a["id"],$pers["uid"]);
		$pers["money"]-=$a["price"];
		$pers["dmoney"]-=$a["dprice"];
		set_vars("money=".$pers["money"].",dmoney=".$pers["dmoney"],$pers["uid"]);
		echo "<center class=but>Успешно изучено \"<b>".$a["name"]."</b>\"</center>";
	}
}

if (@$_GET["lb"])
{
	$a = sqla("SELECT id,price,dprice,name FROM blasts WHERE
	id=".intval($_GET["lb"])." and 
	tlevel<=".$pers["level"]." and 
	ts6<=".$pers["s6"]." and 
	tm1<=".$pers["m1"]." and 
	tm2<=".$pers["m2"]." and
	price<=".$pers["money"]." and
	dprice<=".$pers["dmoney"]." and
	where_buy<2");
	
	if ($a)
	{
		insert_blast($a["id"],$pers["uid"]);
		$pers["money"]-=$a["price"];
		$pers["dmoney"]-=$a["dprice"];
		set_vars("money=".$pers["money"].",dmoney=".$pers["dmoney"],$pers["uid"]);
		echo "<center class=but>Успешно изучено \"<b>".$a["name"]."</b>\"</center>";
	}
}


$types = Array ("Нейтральное","Религия","Некромантия","Стихийная магия","Магия порядка","Вызовы существ");

if (empty($_FILTER["h_zn_show"])) {
$_POST["minlevel"]=0;
$_POST["maxlevel"]=$pers["level"];
$_POST["maxcena"]=10000;
$_POST["sort"]="price";
}
if ($_FILTER["lavkamaxlevel"]>100) $_FILTER["lavkamaxlevel"]=100;

$ti=time();
echo '<center><table border="0" width=750 cellspacing="9" cellpadding="0" class=weapons_box>
	<tr>
		<td align="center">
		<img border="0" src="images/locations/mage.jpg" width=600></td>
	</tr>
	<tr>
		<td align=center class=but>'; 
echo "У вас с собой <b>".round($pers["money"],2)." LN</b><hr>";
echo "<center style='width:80%' class=lbutton>Здесь показаны лишь те заклинания, которые вы можете выучить уже сейчас. Учитывая требования заклинания и ваши текущие параметры, работники гильдии подобрали вам список доступных заклинаний.</center>";
echo "
<table width=80% border=0><tr>
<td width=50% align='center' class=inv><a class=ma href=main.php?filter_f3=blast>Магические удары</a></td>
<td width=50% align='center' class=inv><a class=ma href=main.php?filter_f3=aura>Ауры</a></td>
</tr></table>
<table width=100% border=0><tr>
<td width=30% align='center' class=inv><a class=ma href=main.php?filter_f2=1>Религия</a></td>
<td width=30% align='center' class=inv><a class=ma href=main.php?filter_f2=2>Некромантия</a></td>
<td width=30% align='center' class=inv><a class=ma href=main.php?filter_f2=a>Нейтральные</a></td>
</tr></table>
<hr>
";

switch ($_FILTER["show_z"]){
case 'blast': $type = $_FILTER["show_z"];break;
case 'aura': $type = $_FILTER["show_z"];break;
default : $type = 'blast';break;
}

switch ($_FILTER["h_zn_show"]){
case '1': $stype = $_FILTER["h_zn_show"];break;
case '2': $stype = $_FILTER["h_zn_show"];break;
case 'a': $stype = intval($_FILTER["h_zn_show"]);break;
default : $stype = 'all';break;
}

include ("./inc/magic/view_blast.php");
include ("./inc/magic/view_aura.php");

if ($type=="blast")
{
	$q = 'type='.intval($stype).' and where_buy<2';
	$bls = sql("SELECT * FROM blasts WHERE ".$q);
	echo "<font class=title>МАГИЧЕСКИЕ УДАРЫ</font>";
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 style='border-left-style: solid; border-width: 1px; border-color:silver'>";
	$cnt = 0;
	while ($bl = mysql_fetch_array($bls))
	{
		$cnt++;
		$tmp = sqlr("SELECT id FROM u_blasts 
		WHERE uidp=".$pers["uid"]." and id_in_w=".$bl["id"]."");
		if ($tmp) continue;
		vblast($bl,$pers);
		echo "<tr>";
		echo "<td class=but colspan=3>";
		if ($bl["dprice"]<=$pers["dmoney"] and $bl["price"]<=$pers["money"]
		   and $bl["tlevel"]<=$pers["level"]
		   and $bl["ts6"]<=$pers["s6"]
		   and $bl["tm1"]<=$pers["m1"]
		   and $bl["tm2"]<=$pers["m2"])
		 echo "<input type=button class=login value=Изучить style='width:200px' onclick=
		 \"location='main.php?lb=".$bl["id"]."'\">";
		echo "Стоимость изучения:";
		if ($bl["dprice"])
		 echo "<b>".$bl["dprice"]."y.e.</b>";
		else
		 echo "<b>".$bl["price"]."LN</b>";
		echo "</td>";
		echo "</tr>";
	}
	if (!$cnt)
	 echo "<tr><td class=timef align=center>В этом разделе для вас ничего нет.</td></tr>";
	echo "</table>";
}
if ($type=="aura")
{
	$q = 'type='.intval($stype).' and where_buy<2';
	$bls = sql("SELECT * FROM auras WHERE ".$q);
	echo "<font class=title>АУРЫ</font>";
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 style='border-left-style: solid; border-width: 1px; border-color:silver'>";
	$cnt = 0;
	while ($bl = mysql_fetch_array($bls))
	{
		$tmp = sqlr("SELECT id FROM u_auras 
		WHERE uidp=".$pers["uid"]." and id_in_w=".$bl["id"]."");
		if ($tmp) continue;
		$cnt++;
		vaura($bl,$pers,0);
		echo "<tr>";
		echo "<td class=but colspan=3>";
		if ($bl["dprice"]<=$pers["dmoney"] and $bl["price"]<=$pers["money"]
		   and $bl["tlevel"]<=$pers["level"]
		   and $bl["ts6"]<=$pers["s6"]
		   and $bl["tm1"]<=$pers["m1"]
		   and $bl["tm2"]<=$pers["m2"])
		 echo "<input type=button class=login value=Изучить style='width:200px' onclick=
		 \"location='main.php?la=".$bl["id"]."'\">";
		echo "Стоимость изучения:";
		if ($bl["dprice"])
		 echo "<b>".$bl["dprice"]."y.e.</b>";
		else
		 echo "<b>".$bl["price"]."LN</b>";
		echo "</td>";
		echo "</tr>";
	}
	if (!$cnt)
	 echo "<tr><td class=timef align=center>В этом разделе для вас ничего нет.</td></tr>";
	echo "</table>";
}
?></td></tr></table>