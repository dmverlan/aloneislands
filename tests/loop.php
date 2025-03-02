<?
$mysqlhost = "localhost";
$mysqluser = "root";
$mysqlpass = "";
$mysqlbase = "testing";

$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $main_conn);



for($i=0;$i<1000;$i++) include("index.php");
?>