<script>
<?
	error_reporting(0);
	include ("../configs/config.php");
	$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $main_conn);

	$pers = mysql_fetch_array(mysql_query("SELECT pass,uid,s1,s2,s3,s4,s5,s6,free_stats,hp,ma,kb,level FROM users
											WHERE uid=".intval($_COOKIE["uid"]).""));
	if ($pers["pass"]<>$_COOKIE["hashcode"]) {echo "top.show_return('Ошибка');</script>";exit;}
	
if ($pers["level"]<5)
{
	$_POST["s5"]=$pers["s5"];
}	
if (@$_POST["stats"] and !$pers["cfight"]) {
$stats = $_POST["s1"] + $_POST["s2"] + $_POST["s3"] + $_POST["s4"] + $_POST["s5"] + $_POST["s6"];
$stats += $_POST["ups"];

$stats2 = $pers["s1"] + $pers["s2"] + $pers["s3"] + $pers["s4"] + $pers["s5"] + $pers["s6"];
$stats2 += $pers["free_stats"];

$y=0;
if ($_POST["s1"]<$pers["s1"] or $_POST["s2"]<$pers["s2"] or $_POST["s4"]<$pers["s4"] or $_POST["s3"]<$pers["s3"] or $_POST["s5"]<$pers["s5"] or $_POST["s6"]<$pers["s6"] or $_POST["ups"]<0) $y=1;
if ($stats == $stats2 and $y==0) {
$pers["ma"] = $pers["ma"]+($_POST["s6"]-$pers["s6"])*9;
$pers["hp"] = $pers["hp"]+($_POST["s4"]-$pers["s4"])*5;
$pers["kb"]+= $_POST["s4"]-$pers["s4"];
$pers["s1"] = $_POST["s1"] ;
$pers["s2"] = $_POST["s2"] ;
$pers["s3"] = $_POST["s3"] ;
$pers["s4"] = $_POST["s4"] ;
$pers["s5"] = $_POST["s5"] ;
$pers["s6"] = $_POST["s6"] ;

for ($i=1;$i<7;$i++) 
{
	$pers["st".$i] += $_POST["s".$i] - $pers["st".$i];
	$str.="`st".$i."`=".$pers["st".$i].",";
}

$pers["free_stats"] = $_POST["ups"] ;
$fstate = 1;
if($pers["s1"]<$pers["s6"])
	$fstate = 3;
mysql_query ("UPDATE `users` SET ".$str."`s1`='".$pers["s1"]."' ,`s2`='".$pers["s2"]."' ,`s3`='".$pers["s3"]."' ,`s4`='".$pers["s4"]."' ,`s5`='".$pers["s5"]."' , `free_stats`='".$pers["free_stats"]."' ,`ma`='".$pers["ma"]."' ,`hp`='".$pers["hp"]."' , `s6`='".$pers["s6"]."',`kb`='".$pers["kb"]."', fstate=".$fstate." WHERE `uid` = ".$pers["uid"]."");
}
}


?>
<? if (@$_POST and $pers["free_stats"]) echo "top.show_return('Параметры удачно сохранены!<a onclick=\"save()\" class=but href=\"javascript:void(0)\">Сохранить</a>');";
 elseif(@$_POST)
	echo "top.show_return('Параметры удачно сохранены!');";
 else
 	echo "document.write('Forbidden');";
echo "top.frames['main_top'].maxHP = ".$pers["hp"].";";
echo "top.frames['main_top'].maxMA = ".$pers["ma"].";";
if($pers["free_stats"])
	echo "top.frames['main_top'].START_HP_MA_INS();";
else
	echo "top.frames['main_top'].location = '../main.php';";
?>
</script>