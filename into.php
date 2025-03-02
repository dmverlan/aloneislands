<?
error_reporting(0);
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);
setcookie("referalUID",intval($_GET["id"]),time()+3600);

include ('index.php');
?>