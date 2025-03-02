<? 
error_reporting(0);
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);
$pers = mysql_fetch_array(mysql_query("SELECT sign FROM users WHERE uid=".intval($_COOKIE["uid"]).""));
?><script type="text/javascript" src="js/newbutk.js"></script><script>show_buttons('<?if ($pers["sign"]<>'none') echo 1; ?>',<?=date("H")?>,<?=date("i")?>,<?=date("s")?>);</script>