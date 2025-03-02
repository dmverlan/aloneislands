<META Content="text/html; Charset=utf-8" Http-Equiv=Content-type>
<META Http-Equiv=Cache-Control Content=No-Cache>
<META Http-Equiv=Pragma Content=No-Cache>
<META Http-Equiv=Expires Content=0>
<LINK href=main.css rel=STYLESHEET type=text/css>
<title>Alone Islands [Информация о предмете]</title>
<SCRIPT LANGUAGE='JavaScript' SRC='js/w.js'></SCRIPT>
<body topmargin="15" leftmargin="15" rightmargin="15" bottommargin="15" class=inv style="overflow:hidden;">
<?
include ('inc/functions.php');
error_reporting (0);
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);

if ($_COOKIE["uid"])$pers = sqla("SELECT * FROM `users` WHERE `uid`=".intval($_COOKIE["uid"])."");
$sale = sqla("SELECT * FROM salings WHERE id=".intval($_GET["id"]));
if ($sale["uidwho"]!=$pers["uid"]) die("Hacking Attempt");
$persto = sqla("SELECT * FROM `users` WHERE `uid`=".intval($sale["uidp"])."");
if(intval($sale["idw"]))
{
$vesh =  sqla("SELECT * FROM wp WHERE id=".intval($sale["idw"]));
echo "<center><div style='width:80%' class=weapons_box>";
include ('inc/inc/weapon.php');
echo "</div></center>";
echo "<p class=gray>Персонаж <b class=user>".$persto["user"]."</b>[<font class=lvl>".$persto["level"]."</font>]<img src='images/info.gif' onclick=\"javascript:window.open('info.php?p=".$persto["user"]."','_blank')\" style=cursor:pointer> предлогает вам сделку.<br>
Цена предложения <b>".$sale["price"]." LN</b><br><center>";
if ($pers["money"]>=$sale["price"]) echo "<input type=button class=but2 value=Принять onclick=\"top.frames['main_top'].location = 'main.php?sell=yes&hash=".$sale["id"]."';top.FuncyOff();\">";
else echo "<input type=button class=but2 value='Не хватает средств' DISABLED><font class=hp></font>";
echo "<input type=button class=but2 value=Отказать onclick='top.FuncyOff()'></center>";
echo "</p>";
}
else //НАСТАВНИЧЕСТВО
{
	$cnt = sqlr("SELECT COUNT(*) FROM users WHERE instructor = ".$persto["uid"]);
	if($cnt) 
		echo "<script>top.FuncyOff();</script>";
	else
	if(empty($_GET["say"]))
	{
	echo "<table border=0 width=100% height=100%> <tr><td width=100% height=100% valign=center align=center>";
	echo "<center style='width:90%;' class=fightlong>";
	echo "<b class=about>ВНИМАНИЕ</b><br>";
//	echo "<i class=gray>";
	echo "Персонаж <b class=user>".$persto["user"]."</b> <b class=lvl>[".$persto["level"]."]</b> <img src=images/i.gif onclick=\"javascript:window.open('info.php?p=".$persto["user"]."','_blank')\" style='cursor:pointer' height=16> предлагает начать ваше обучение. Он будет подсказывать вам и помогать всем посильным и непосильным трудом. Вы так же получите 10 LN и +50% опыта за бои при согласии.";
//	echo "</i>";
	echo "<hr>";
	echo "<input type=button class=login value='Согласиться' onclick=\"location = 'salingFORM.php?say=yes&id=".intval($_GET["id"])."';\" style='float:left;width:40%;cursor:pointer;'>";
	echo "<input type=button class=login value='Отказаться' onclick=\"location = 'salingFORM.php?say=no&id=".intval($_GET["id"])."';\" style='float:right;width: 40%;cursor:pointer;'>";
	echo "</center>";
	echo "</td></tr></table>";
	}elseif($_GET["say"]=='yes')
	{
		echo "<table border=0 width=100% height=100%> <tr><td width=100% height=100% valign=center align=center>";
		echo "<center style='width:90%;' class=fightlong>";
		echo "<b class=about>ВНИМАНИЕ</b><br>";
	//	echo "<i class=gray>";
		echo "Вы успешно приняли заявку. Вам начислено 10 LN и +50% опыта за каждый  следующий бой.";
	//	echo "</i>";
		echo "<br><input type=button class=login value='Закрыть' onclick=\"top.FuncyOff()\" style='width:80%;cursor:pointer;'>";
		echo "</center>";
		echo "</td></tr></table>";
		set_vars("money=money+10,instructor=".$persto["uid"],$pers["uid"]);
		say_to_chat ('^',"Персонаж <b>".$pers["user"]."</b>[".$pers["level"]."] отныне ваш ученик.",1,$persto["user"],'*',0);
		set_vars("money=money-20",$persto["uid"]);
	}else
	{
		echo "<table border=0 width=100% height=100%> <tr><td width=100% height=100% valign=center align=center>";
		echo "<center style='width:90%;' class=fightlong>";
		echo "<b class=about>ВНИМАНИЕ</b><br>";
	//	echo "<i class=gray>";
		echo "Вы отказались от обучения.";
	//	echo "</i>";
		echo "<br><input type=button class=login value='Закрыть' onclick=\"top.FuncyOff()\" style='width:80%;cursor:pointer;'>";
		echo "</center>";
		echo "</td></tr></table>";
		say_to_chat ('^',"Персонаж <b>".$pers["user"]."</b>[".$pers["level"]."] отказался от обучения.",1,$persto["user"],'*',0);
	}
}
?><SCRIPT LANGUAGE='JavaScript' SRC='js/c.js'></SCRIPT>