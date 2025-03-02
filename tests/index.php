<?

$mysqlhost = "localhost";
$mysqluser = "test";
$mysqlpass = "lanaya";
$mysqlbase = "test";

$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $main_conn);

$time = microtime() + time();
/*for($i=0;$i<1000;$i++)
{
*/
mysql_query("SELECT COALESCE(GET_LOCK('1', 60));");
$a = mysql_result(mysql_query("SELECT `int1` FROM main"),0)+1;
mysql_query("UPDATE main SET `int1`=".$a.";");
mysql_query("UPDATE main SET `int2`=`int2`+1;");
//mysql_query("SELECT RELEASE_LOCK ('1');");
/*
}*/
/*
echo mysql_result(mysql_query("SELECT int1 FROM main"),0);
echo "<hr>";
echo mysql_result(mysql_query("SELECT int2 FROM main"),0);
echo "<hr>";
echo (microtime() + time() - $time);*/
?>