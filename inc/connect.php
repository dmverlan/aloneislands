<?
include ("configs/config.php");
$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $main_conn);

$options = explode ("|",$_COOKIE["options"]);
if (isset($_COOKIE["filter1"]))
$f = explode("|",$_COOKIE["filter1"]);
else $f = explode("|",'shle|0|100|1000000|tlevel||elements||||||||');
if (@$_GET["set_type"]) $f[0] = $_GET["set_type"];
if (isset($_POST["minlevel"])) {
$f[1] = $_POST["minlevel"];
$f[2] = $_POST["maxlevel"];
$f[3] = $_POST["maxcena"];
$f[4] = $_POST["sort"];
}
if (@$_GET["filter_f1"]) $f[5]=$_GET["filter_f1"];
if (@$_GET["filter_f2"]) $f[6]=$_GET["filter_f2"];
if (@$_GET["filter_f3"]) $f[7]=$_GET["filter_f3"];
if (@$_GET["filter_f4"]) $f[8]=$_GET["filter_f4"];
if (@$_GET["filter_f5"]) $f[9]=$_GET["filter_f5"];
if (@$_GET["filter_apps"]) $f[10]=$_GET["filter_apps"]-1;
if (@$_GET["cat"]) $f[11]=$_GET["cat"];
if (@$_GET["ar_loc"]) $f[12]=$_GET["ar_loc"];
if (@$_GET["filter_f6"]) $f[13]=$_GET["filter_f6"];
if (@$_GET["pers_sort"]) $f[14]=$_GET["pers_sort"];
$f = implode("|",$f);
if (empty($_COOKIE["filter1"]) or $f<>$_COOKIE["filter1"])
{
	setcookie("filter1",$f,time()+20000);
	$_COOKIE["filter1"]=$f;
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
?>