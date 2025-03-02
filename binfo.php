<?
include ('inc/functions.php');
error_reporting(0);
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);
foreach ($_GET as $id => $a) break;
$id = intval($id);
if ($id>0)
{
	$pers = sqla("select * from `bots` where `id`='".$id."' ");
	$pers["bid"] = $pers["id"];
}
else
	$pers = sqla("select * from `bots_battle` where `id`='".$id."' ");

if (!$pers['chp'])$pers['chp'] = $pers['hp'];
if (!$pers['cma'])$pers['cma'] = $pers['ma'];

if (empty($pers["id"])) {
echo "<font color=red>Нет Такого Существа.</font>";
exit;}
echo "<title>[".$pers["user"]."] Информация</title>";

//rank_i
$rank_i = ($pers["s1"]+$pers["s2"]+$pers["s3"]+$pers["s4"]+$pers["s5"]+$pers["s6"]+$pers["kb"])*0.3 + ($pers["mf1"]+$pers["mf2"]+$pers["mf3"]+$pers["mf4"])*0.03 + ($pers["hp"]+$pers["ma"])*0.04+($pers["udmin"]+$pers["udmax"])*0.3;
if ($rank_i<>$pers["rank_i"] and $pers["rank_i"]=$rank_i)
sqla ("UPDATE bots SET rank_i='".$pers["rank_i"]."' WHERE id='".$pers["id"]."'");
//

$pers["dmoney"]=-1;
?><div id=inf_from_php2 style='visibility:hidden;position:absolute;top:0px;height:0;'> <i class=timef>Это существо не управляется игроками.</i><?
if ($pers["magic_resistance"]) echo "<br><b><i class=timef>Это существо невосприимчиво к магии.</i></b>";
?></div>
<div id=inf_from_php style='visibility:hidden;position:absolute;top:0px;height:0;'></div>
<script type="text/javascript" src="js/info.js?3"></script>
<script><?
include('inc/inc/p_clothes.php');
$hp = $pers["chp"];
$ma = $pers["cma"];
$sphp = 9999;
$spma = 9999;
$pers["money"]=-1;
$pers["dmoney"]=-1;
echo "build_pers('".$sh["image"]."','".$sh["id"]."','".$oj["image"]."','".$oj["id"]."','".$or1["image"]."','".$or1["id"]."','".$po["image"]."','".$po["id"]."','".$z1["image"]."','".$z1["id"]."','".$z2["image"]."','".$z2["id"]."','".$z3["image"]."','".$z3["id"]."','".$sa["image"]."','".$sa["id"]."','".$na["image"]."','".$na["id"]."','".$pe["image"]."','".$pe["id"]."','".$or2["image"]."','".$or2["id"]."','".$ko1["image"]."','".$ko1["id"]."','".$ko2["image"]."','".$ko2["id"]."','".$br["image"]."','".$br["id"]."','".$pers["pol"]."_".$pers["obr"]."',0,'".$pers["sign"]."','".$pers["user"]."','".$pers["level"]."','".$pers["chp"]."','".$pers["hp"]."','".$pers["cma"]."','".$pers["ma"]."',0,'".$kam1["image"]."','".$kam2["image"]."','".$kam3["image"]."','".$kam4["image"]."','".$kam1["id"]."','".$kam2["id"]."','".$kam3["id"]."','".$kam4["id"]."',".$hp.",".$pers["hp"].",".$ma.",".$pers["ma"].",".$sphp.",".$spma.",".$pers["s1"].",".$pers["s2"].",".$pers["s3"].",".$pers["s4"].",".$pers["s5"].",".$pers["s6"].",0,".$pers["money"].",0,".$pers["kb"].",".$pers["mf1"].",".$pers["mf2"].",".$pers["mf3"].",".$pers["mf4"].",".$pers["mf5"].",".$pers["udmin"].",".$pers["udmax"].",".$pers["rank_i"].",'Существо',0,0,0,0,0,0,2,0,0,'".$ws1."','".$ws2."','".$ws3."','".$ws4."','".$ws5."','".$ws6."',0,0,0,0,1,0,0,0);";
?>
</script>