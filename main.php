<?
$uid = intval($_COOKIE["uid"]);
if(!$uid)
{
	include("error.html");
	exit;
}
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (empty($_GET["serrors"]) or 1)
	error_reporting(E_ERROR | E_PARSE);
else
	error_reporting(0);

$timer = time() + intval(microtime()*1000)/1000;
include_once ('inc/functions.php');
include_once ('inc/connect.php');

//sql("SELECT COALESCE(GET_LOCK('".intval($_COOKIE["uid"])."', 60));");

################################## LOCK

/*$memcache = new Memcache;
$memcache->connect('localhost', 11211);
$LOCK = $memcache->get('LOCK'.$uid);
$R = 1+microtime();
if($LOCK)## Too fast
{
	$LOCKR = $memcache->get('LOCKR'.$uid);
	if ($LOCKR and intval($LOCKR*10000)!=intval($R*10000))
	{
		$tmp = round($LOCK + $LOCKR - tme() - $r,4);
		echo '<script type="text/javascript" src="js/newup.js?2"></script>';
		echo '<script type="text/javascript">too_fast(\'Конфликт с '.$LOCKR.'. Наш поток: '.$R.', разница '.$tmp.' сек.\');</script>';
		exit;
	}
}
if(!$LOCK)
{
	$memcache->set('LOCK'.$uid, tme(), false, tme()+20);
	$memcache->set('LOCKR'.$uid, $R, false, tme()+20);
}*/

########################################
include_once ('inc/prov.php');
include_once ('inc/up.php');

if ($pers["curstate"]==0) include_once ('inc/pers.php');
if ($pers["curstate"]==1) include_once ('inc/inv.php');
if ($pers["curstate"]==2) {
$row = sqla ("SELECT inc FROM `locations` WHERE `id` = '".$pers["location"]."'");
include_once ("inc/locations/".$row["inc"]);}
if ($pers["curstate"]==3) include ('inc/naddon.php');
if ($pers["curstate"]==4) include ('inc/battle.php');
if ($pers["curstate"]==5) include ('inc/self.php');
if ($pers["curstate"]==6) include ('inc/friends/list.php');
if ($pers["curstate"]==16) include ('inc/adm/map_edit.php');
if ($pers["curstate"]==17) include ('inc/adm/new_add.php');
if ($pers["curstate"]==18) include ('inc/adm/new_tip.php');
if ($pers["curstate"]==20) include ('inc/adm/administration.php');
if ($pers["curstate"]==21) include ('inc/adm/media.php');
if ($pers["curstate"]==22) include ('inc/adm/weapons.php');
if ($pers["curstate"]==23) include ('inc/adm/magic.php');
if ($pers["curstate"]==24) include ('inc/adm/bots.php');
if ($pers["curstate"]==25) include ('inc/adm/ministers.php');
if ($pers["curstate"]==26) include ('inc/adm/users.php');
if ($pers["curstate"]==27) include ('inc/adm/quests.php');
if ($pers["curstate"]==28) include ('inc/adm/questsR.php');
if ($pers["curstate"]==29) include ('inc/adm/questsS.php');
if ($pers["curstate"]==30) include ('inc/adm/questsQ.php');
if ($pers["curstate"]==31) include ('inc/adm/ava_req.php');
if ($pers["curstate"]==32) include ('inc/adm/clans.php');



#############################UNLOCK
/*$memcache->set('LOCK'.$uid, 0);
$memcache->set('LOCKR'.$uid, 0);*/
###################################

	$t = time() + intval(microtime()*1000)/1000 - $timer;
	/*
	$longes_exec = sqla("SELECT longest_exec FROM configs");
	if ($longes_exec[0]<$t)
	{
		sqla("UPDATE configs SET longest_exec=".$t);
		error_reporting(E_ALL & ~E_NOTICE);
	}
	*/
	/*if (($t - $sql_queries_timer)>0.4)
	{
		$str1 = '';
		foreach ($_POST as $key => $v)
			$str1.=$key."=".$v.";";
		$str2 = '';
		foreach ($_GET as $key => $v)
			$str2.=$key."=".$v.";";
		say_to_chat ("a",str_replace("'","",'['.($t - $sql_queries_timer).'] POST:'.$str1.' | GET:'.$str2.''),1,'sL','*');
	}*/
if ($_COOKIE["uid"]==5)
{
	echo "<script>function sysdown(){  jQuery(\"#sysinf\").slideDown(300); }</script>";
	echo "<a href='javascript:sysdown();' class=bga>Системная информация[".$t." | ".$sql_queries_timer."]</a>";
	echo "<div class=fightlong id=sysinf style='display:none;'>";
	echo "<font class=time><center>MySQL :: [".$sql_queries_counter."] > ".$sql_queries_timer." sec. | ALL :: ".$t."</center></font>";
	echo "<font class=time><center>MySQL :: [".$sql_longest_query."] > ".$sql_longest_query_t." sec.</center></font><Br><a href=main.php?serrors=1 class=timef>Показать ошибки </a><hr>";
	$included_files = get_included_files();
foreach ($included_files as $filename) {
    echo "$filename<br>";
}
/*
	echo "<hr><center>";
	echo "<table class=table>";
	$max=0;
	$i=0;
	$ind=0;

	foreach($module_statisticks as $modst_array)
	{
		if ($modst_array["all_exec_time"]>$max)
		{
			$max = $modst_array["all_exec_time"];
			$ind = $i;
		}
		$i++;
	}
		$modst_array = $module_statisticks[$ind];
		foreach ($modst_array as $key=>$value)
		echo "<tr><td class=lbutton>".$key."</td><td class=lbutton>".$value."</td></tr>";
		echo "<tr><td class=lbutton><hr></td><td class=lbutton><hr></td></tr>";
	echo "</table></center>";

	if (isset($php_errormsg))
	{
	echo "<font class=time>Ошибки: ".$php_errormsg."</font>";
	}*/
/*
	foreach($sql_all as $a)
	{
		echo "<a class=inv href=\"main.php?show_q=".$a."\">".$a."</a><br>";
	}

	if (@$_GET["show_q"] and $_COOKIE["uid"]==5)
	{
		$q = explode(";",$_GET["show_q"]);
		$q = $q[0];
		$q = mysql_query($q);
		$sqltitle = '';
		$sqlbody = '';
		while ($i < mysql_num_fields($q)) {
		$meta = mysql_fetch_field($q, $i);
		$sqltitle .= "<td><b>".$meta->name."</b> (".$meta->type."[".$meta->max_length."])</td>";
		$i++;
		}
		while ($a = mysql_fetch_array($q,MYSQL_ASSOC))
		{
			$sqlbody .= "<tr>";
			foreach($a as $v)
			$sqlbody .= "<td>".$v."</td>";
			$sqlbody .= "</tr>";
		}
		echo "<table border=1 width=100% cellspacing=2 cellpadding=2 bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF><tr>".$sqltitle."</tr>".$sqlbody."</table>";
	}

	echo "</div>";*/
}

?><SCRIPT SRC='js/c.js'></SCRIPT><SCRIPT>$(".LinedTable tr:nth-child(odd)").css("background-color","#ECECEC");</SCRIPT>
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/resource/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter184038 = new Ya.Metrika(184038); } catch(e){}
</script>
<noscript><div style="position: absolute;"><img src="//mc.yandex.ru/watch/184038" alt="" /></div></noscript>
<!-- Yandex.Metrika -->
