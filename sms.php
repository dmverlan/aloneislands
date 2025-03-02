<?
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $res);

error_reporting(0);
include ('inc/functions.php');
if (md5('123123123') <> $_GET['skey']) exit();
$_GET["msg"] = strtolower($_GET["msg"]);
$uid = intval(str_replace("okai1 ","",$_GET["msg"]));
if ($uid==0)$uid = intval(str_replace("okai2 ","",$_GET["msg"]));
if ($uid==0)$uid = intval(str_replace("not alone ","",$_GET["msg"]));
if ($uid==0)$uid = intval(str_replace("okai3 ","",$_GET["msg"]));
$s = '';
foreach ($_GET as $key=>$value) $s .= $key.'=>'.$value.";";
sql("UPDATE configs SET sms='".$s."|".$uid."'");
if ($uid>=5)
{
$pers = sqla("SELECT * FROM users WHERE uid='".$uid."'");
sql("UPDATE users SET phone_no='".$_GET["user_id"]."',sms=sms+1,dmoney=dmoney+".round($_GET["cost"]*4,2)." WHERE uid=".$pers["uid"]."");
echo $pers["user"]." +".round($_GET["cost"]*4,2)."y.e; Alone Islands.Ru";
say_to_chat('s','SMS-сервис. Вам начисленно <b>'.round($_GET["cost"]*4,2).' y.e.</b> с <i>'.$_GET["user_id"].'</i> СПАСИБО!',1,$pers["user"],'',0); 
}
?>