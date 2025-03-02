<?
if ($pers["invisible"]>tme()) {$_INV = 1;$pers["cfight"]=0;$pers["online"]=0;} else $_INV = 0;
if ($pers["online"]==1) 
{
if ($pers["location"]<>'out')
$location = sqla("SELECT name FROM `locations` 
WHERE `id`='".$pers["location"]."'");
else 
$location = sqla("SELECT name FROM `nature` 
WHERE `x`='".$pers["x"]."' and `y`='".$pers["y"]."' ;");
$_ONLINE = "[".$pers["online"].",'".tp($curtimeonline=(time()-$pers["lastvisits"]))."','".$location["name"]."',".$pers["x"].",".$pers["y"].",".$pers["cfight"]."]";
}
else
{
	$location["name"] = '';
	if (($you["sign"]=='watchers'or strpos(" ".$you["rank"],"<pv>")>0)) 
	{
		if ($pers["location"]<>'out')
		$location = sqla("SELECT name FROM `locations` 
		WHERE `id`='".$pers["location"]."'");
		else 
		$location = sqla("SELECT name FROM `nature` 
		WHERE `x`='".$pers["x"]."' and `y`='".$pers["y"]."' ;");
		if ($pers["invisible"]>tme()) {$_INV = 1;$pers["cfight"]=0;} else $_INV = 0;
	}
	$_ONLINE = "[".$pers["online"].",'0','".$location["name"]."',0,0,".$pers["cfight"].",".$_INV."]";
}
$INFO_TEXT = '';
$INFO_TEXT .= '<table style="width: 100%"> <tr> <td class="title">ФОТОГРАФИЯ</td> </tr> <tr> <td align=center class=loc>';
if ($pers["photo"])
 $INFO_TEXT .= "<img src='images/photos/".$pers["uid"]."_".$pers["photo"].".jpg'>";
else 
 $INFO_TEXT .= "<font class=puns>Нет фотографии</font>";
$INFO_TEXT .='</td></tr></table>';

$chars = sqla("SELECT about FROM chars WHERE uid=".$pers["uid"]);
$INFO_TEXT .= '<font class=title>ЛИЧНАЯ ИНФОРМАЦИЯ</font>';
$INFO_TEXT .= "Имя:<b> ".htmlspecialchars($pers["name"])."</b><br>";
$INFO_TEXT .= "Город:<b> ".htmlspecialchars($pers["city"])."</b><br>";
$INFO_TEXT .= "Страна:<b> ".htmlspecialchars($pers["country"])."</b><br>";
if ($pers["icq"])$INFO_TEXT .= "ICQ:<b> ".$pers["icq"]."</b><img src='http://web.icq.com/whitepages/online?icq=".$pers["icq"]."&img=26' height=12><br>";
if ($pers["vkid"])$INFO_TEXT .= "ВКонтакте:<b><a class=timef target=_blank href='http://vkontakte.ru/profile.php?id=".$pers["vkid"]."'>".$pers["vkid"]."</a></b><br>";
if ($pers["pol"]=="male") $INFO_TEXT .= "Пол:<b> Мужской</b><br>" ; else $INFO_TEXT .= "Пол:<b> Женский</b><br>";
if ($chars["about"]) $INFO_TEXT .= "<font class=title>О СЕБЕ</font><br>".str_replace ("
","<br>",$chars["about"]);

if ($you) $rep = '<a href="javascript:report();" class=bg>Написать отзыв</a>'; else $rep = '';
echo '<script>head('.$_ONLINE.',\''.$pers["user"].'\');</script><table style="width: 100%"> <tr> <td style="width: 60%" valign=top id=main>'.$INFO_TEXT.'</td> <td style="width: 40%" id=reports align=center valign=top><hr><div id=report>'.$rep.'</div></td> </tr></table> '; 
// Подарки
##################
echo "<script>";
$count_prs = sqlr("SELECT COUNT(*) FROM presents_gived WHERE uid=".$pers["uid"],0);
echo "var prs = [".$count_prs."";
$prs = sql("SELECT * FROM presents_gived WHERE uid=".$pers["uid"]);
while ($p = mysql_fetch_array($prs,MYSQL_ASSOC))
{
	$who = $p["who"];
	if (!($_SESSION["sign"]=='watchers'or strpos(" ".$_SESSION["rank"],"<pv>")>0) 
	and $p["anonymous"]) $who = 'Анонимно';
	$p["name"] = str_replace("\r\n","",$p["name"]);
	echo ",['".str_replace('"','',$p["name"])."','".$p["image"]."','".$who."','".date("d.m.Y H:i",$p["date"])."','".$p["text"]."']";
}
echo "];show_presents();";
echo "</script>";
###################

# Удалить отзыв
if (@$_POST["deleterep"]) 
{
	echo 1;
	$r = sql("SELECT * FROM reports_for_users WHERE uid=".$pers["uid"]." and
	date=".intval($_POST["deleterep"])."");
	if ($r["who"]==$you["user"] or $you["user"]==$pers["user"] or 
	(substr_count($you["rank"],"<pv>") or $you["sign"]=='watchers'))
	sql("DELETE FROM reports_for_users WHERE uid=".$pers["uid"]." and
	date=".intval($_POST["deleterep"])."");
}
#Добавить отзыв:
if (@$_POST["report"] and $you["money"]>=50)
{
	sql("INSERT INTO 
	`reports_for_users` ( `uid` , `lvl` , `sign` , `date` , `who` , `text` ) 
	VALUES ('".$pers["uid"]."', '".$you["level"]."', '".$you["sign"]."', '".time()."'
	, '".$you["user"]."', '".str_replace("'","",$_POST["report"])."');");
	say_to_chat ("s","<font class=user onclick=\"top.say_private(\'".$you["user"]."\')\">".$you["user"]."</font> написал вам отзыв.",1,$pers["user"],'*',0);
	set_vars("money=money-20",$you["uid"]);
}
# Отзывы
echo "<script>";
if (empty($_GET["all_reports"]))
$rep = sql("SELECT * FROM reports_for_users WHERE uid=".$pers["uid"]." 
ORDER BY date DESC LIMIT 7;");
else
$rep = sql("SELECT * FROM reports_for_users WHERE uid=".$pers["uid"]." ORDER BY date DESC");
echo "rep_text +='<table border=0 width=320 cellspacing=0 cellpadding=0 class=fightlong><tr><td class=brdr>ОТЗЫВЫ: <a href=\"info.php?no_watch=1&p=".$pers["user"]."&all_reports=1&self=1\" class=nt>[ВСЕ]</a></td></tr>';";
$k = 0;
while($r = mysql_fetch_array ($rep))
{
	$k++;
	$del = 0;
	$r["text"] = str_replace("\r\n","<br>",$r["text"]);
	$r["text"] = str_replace("\n","<br>",$r["text"]);
	$r["text"] = str_replace("\r","<br>",$r["text"]);
	$r["text"] = str_replace("'","\'",$r["text"]);
	if ($r["who"]==$you["user"] or $you["user"]==$pers["user"] or 
	(substr_count($you["rank"],"<pv>") or $you["sign"]=='watchers'))$del = $r["date"];
	echo "pr_r('".$r["who"]."',".$r["lvl"].",'".$r["sign"]."','".date("d.m.Y H:i",$r["date"])."','".$r["text"]."',".$del.");";
}
if ($k==0) echo "rep_text +='<tr><td class=time>Здесь пока никто не написал</td></tr>';";
echo "rep_text +='</table>';";
echo "document.getElementById('reports').innerHTML += rep_text;";
echo "</script>";
?>