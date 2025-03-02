<?

	error_reporting(0);
	include ("../configs/config.php");
	include ("../inc/functions.php");

################################## LOCK
$uid = intval($_COOKIE["uid"]);
/*if($uid)
{
$memcache = new Memcache;
$memcache->connect('localhost', 11211);
$LOCK = $memcache->get('LOCK'.$uid);
if($LOCK>time()-10)
{
	unset($_POST);
	unset($_GET);
}
$memcache->set('LOCK'.$uid, time(), false, time()+20);
}*/
########################################

	$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $main_conn);
	$DONT_CHECK = 1;
	include ("../inc/prov.php");

if (isset($_GET["buy"]) and $_GET["kolvo"]>0 and $_GET["kolvo"]<100)
{
$v = sqla("SELECT price,q_s,where_buy,name,id,max_durability FROM `weapons` WHERE `id`='".$_GET["buy"]."' ;");
$kolvo = intval($_GET["kolvo"]);
if ($kolvo>$v['q_s']) $kolvo = $v['q_s'];
if ($v["where_buy"]==0 and $v["q_s"]>0) {

	if ($pers["money"]<($v["price"]*$kolvo))
		echo "<b><font class=hp>Не хватает денег.</b></font>";
	else
	{
		for ($i=1;$i<=$kolvo;$i++)
			insert_wp($v["id"],$pers["uid"],$v["max_durability"],0,$pers["user"]);
		$pers["money"]-= $v["price"]*$kolvo;
		set_vars("money=money-".($v["price"]*$kolvo),$pers["uid"]);
		sql ("UPDATE `weapons` SET `q_s`=q_s - ".$kolvo." WHERE `id`='".$v["id"]."'");
		echo "<script>top.frames['main_top'].success('".$v["name"]."',".$v["price"].",".$kolvo.");</script>";
	}
}
}

#############################UNLOCK
//$memcache->set('LOCK'.$uid, 0, false, time()+20);
###################################
?>
