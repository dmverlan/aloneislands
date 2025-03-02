<?
	include ("configs/config.php");
	$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $res);
	
	mysql_query ("UPDATE `users` SET `online` = '0',timeonline=timeonline+(".time()."-lastvisits) WHERE 
	uid='".intval($_COOKIE["uid"])."';");
	include ("index.php");
?>