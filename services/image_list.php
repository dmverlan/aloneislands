<?
	error_reporting(0);
	include ("../configs/config.php");
	include ("../inc/functions.php");
	$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $main_conn);
	
	$q = "and width=".intval($_GET["width"])." ";
	if (@$_GET["height"]<>'*') $q .= "and height=".intval($_GET["height"])."";
	if (empty($_GET["width"])) $q = '';
	$sql = sql("SELECT address FROM images WHERE type=".intval($_GET["type"])." ".$q."");
	
	$check = 1;
	while($s = mysql_fetch_array($sql,MYSQL_ASSOC) and $check++)
	echo $s["address"].'|';
	
	if ($check==1) echo 'none';
?>