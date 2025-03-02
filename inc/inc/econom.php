<?
$status = $pers["clan_state"];
define("DD_STND_KOEF",1);
define("DD_CLAN_KOEF",1);
$_RETURN = '';
$time = time();
//Фильтры
if (@$_POST["fornickname"]) $_POST["fornickname"] = trim($_POST["fornickname"]);
if (@$_POST["forprice"]) $_POST["forprice"] = abs(intval($_POST["forprice"]));
##
if ($pers["punishment"]>=$time)$_RETURN .= "На вас наложена кара смотрителей. Некоторые действия недоступны.";


$tmp = sqlr("SELECT COUNT(*) FROM p_auras 
WHERE uid=".$pers["uid"]." and special=6 and esttime>".tme());
$_ECONOMIST = ($tmp)?1:0;

include ("economics/weapons.php");
include ("economics/money.php");
include ("economics/attack.php");
include ("economics/auras.php");
?>
</font>