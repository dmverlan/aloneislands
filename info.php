<?
error_reporting(0);
if (@$_GET["do_w"]) {include ("watchers.php");exit;}
include ('inc/functions.php');

$UNAME = $_SERVER["argv"][0];
if (isset($_GET["p"]))
	$UNAME = $_GET["p"];
$UNAME = str_replace("'","",$UNAME);
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $res);

if (@$_GET["id"])
$pers = sqla ("SELECT * FROM `users` WHERE `uid`=".intval($_GET["id"])."");
else
$pers = sqla ("SELECT * FROM `users` WHERE `smuser`=LOWER('".$UNAME."')");
$locname = sqla ("SELECT * FROM `locations` WHERE `id`='".$pers["location"]."' ;");


#### Призраки для битвы на арене:
if ($pers["ctip"]==-1)
{
	$pers["block"] = '';
	$pers["prison"] = '';
	$pers["online"] = 1;
	$pers["tire"] = 0;
	$pers["lastom"] = tme();
	$pers["timeonline"] = 3600;
	$pers["lastvisits"] = tme()-800-rand(100,500);
	$pers["cfight"] = $pers["silence"];
	$pers["action"] = 0;
}

if (substr_count($pers["aura"],"invisible"))
{
	$pers["online"]=0;
	$pers["chp"]=$pers["hp"];
	$pers["cma"]=$pers["hp"];
	$pers["cfight"]=0;
}
$you = catch_user(intval($_COOKIE["uid"]),$_COOKIE["hashcode"],1);
if ($you["block"]) unset($you);
if ($you["pass"]==$_COOKIE["hashcode"])
{
$_SESSION["sign"] = $you["sign"];
$_SESSION["user"] = $you["user"];
if ($you["diler"]) $you["rank"].="<diler><molch><pv>";
$_SESSION["rank"] = $you["rank"];
}

if (($pers["action"]==-10 or $pers["action"]==-11) and $you["uid"]<>5 and $you["sign"]<>'watchers')
{echo "<LINK href=main.css rel=STYLESHEET type=text/css><font class=timef>Запрещёно. Ожидаем входа в игру данного персонажа.</font><SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";exit;}

if (empty($pers["uid"])) {echo "<LINK href=main.css rel=STYLESHEET type=text/css><center class=but><center class=puns><br><br>Нет Такого персонажа.[".$UNAME."]<br><br><br></center></center><SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";exit;}

if ((substr_count($you["rank"],"<pv>") or $you["sign"]=='watchers') and empty($_GET["no_watch"]))
{
echo '<title>['.$pers["user"].'] AloneIslands</title><frameset rows="*,20" FRAMEBORDER=0 FRAMESPACING=2 BORDER=0 id=frmset>';
echo '<frame src="info.php?id='.$pers["uid"].'&no_watch=1" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0 style="border-bottom-width: 2px; border-bottom-style: solid; border-bottom-color: #666666">';
echo '<frame src="watchers.php?id='.$pers["uid"].'" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0>';
echo '</frameset>';
exit;
}else echo '<script type="text/javascript" src="js/info.js?4"></script>';

if (empty($_GET["self"])) include('info/game.php');
else
 include('info/self.php');
?>