<?

$INFO_TEXT = '';
//rank_i
$rank_i = ($pers["s1"]+$pers["s2"]+$pers["s3"]+$pers["s4"]+$pers["s5"]+$pers["s6"]+$pers["kb"])*0.3 + ($pers["mf1"]+$pers["mf2"]+$pers["mf3"]+$pers["mf4"])*0.03 + ($pers["hp"]+$pers["ma"])*0.04+($pers["udmin"]+$pers["udmax"])*0.3;
if ($rank_i<>$pers["rank_i"] and $pers["rank_i"]=$rank_i)
//
?>
<div id=inf_from_php style='visibility:hidden;position:absolute;top:0px;height:0;'>
<?
$prison = explode ('|',$pers["prison"]);
if ($pers["punishment"]>=time()) $punished = tp($pers["punishment"]-time()); else $punished = '';
$_PUNISHMENT = "['".$pers["block"]."','".$prison[1]."','".tp($prison[0]-time())."','".$punished."']";

if ($pers["invisible"]>tme()) 
{
	$_INV = 1;
	$pers["cfight"]=0;
	$pers["online"]=0;
	$pers["chp"]=$pers["hp"];
	$pers["cma"]=$pers["ma"];
	$pers["lastvisits"] = tme()-86410;
	$pers["lastom"] = tme()-86410;
}else 
	$_INV = 0;

if ($pers["curstate"]<>4 and $pers["cfight"]<5 and $_INV==0) 
{
	hp_ma_up($pers["chp"],$pers["hp"],$pers["cma"],$pers["ma"],$pers["sm6"],$pers["sm7"],$pers["lastom"],$pers["tire"]);
	$pers["chp"] = $hp;
	$pers["cma"] = $ma;
}
	
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
		if ($pers["invisible"]>tme()) $_INV = 1; else $_INV = 0;
	}
	$_ONLINE = "[".$pers["online"].",'0','".$location["name"]."',0,0,".$pers["cfight"].",0]";
}
	
if($pers["level"]>9)
{
	$pupil = sqla("SELECT * FROM users WHERE instructor = ".$pers["uid"]);
	if($pupil)
{
	$INFO_TEXT .= "<center>";
	$INFO_TEXT .= "<center style='width:90%' class=combofight>";
	$INFO_TEXT .= "<i class=gray>";
	$INFO_TEXT .= "Обучает персонажа <b class=ma>[".$pupil["level"]."] уровня</b>";
	$INFO_TEXT .= "</i>";
	$INFO_TEXT .= "<div class=but><b class=user>".$pupil["user"]."</b> <b class=lvl>[".$pupil["level"]."]</b> <img src=images/i.gif onclick=\"javascript:window.open('info.php?p=".$pupil["user"]."','_blank')\" style='cursor:pointer' height=16></div>";
	$INFO_TEXT .= "</center>";
	$INFO_TEXT .= "</center>";
}
}

if($pers["level"]<5 and $pers["instructor"])
{
	$pupil = sqla("SELECT * FROM users WHERE uid = ".$pers["instructor"]);
	if($pupil)
{
	$INFO_TEXT .= "<center>";
	$INFO_TEXT .= "<center style='width:90%' class=combofight>";
	$INFO_TEXT .= "<i class=gray>";
	$INFO_TEXT .= "Обучается у персонажа <b class=hp>[".$pupil["level"]."] уровня</b>";
	$INFO_TEXT .= "</i>";
	$INFO_TEXT .= "<div class=but><b class=user>".$pupil["user"]."</b> <b class=lvl>[".$pupil["level"]."]</b> <img src=images/i.gif onclick=\"javascript:window.open('info.php?p=".$pupil["user"]."','_blank')\" style='cursor:pointer' height=16></div>";
	$INFO_TEXT .= "</center>";
	$INFO_TEXT .= "</center>";
}
}

 if ($pers["sign"]<>'none')
 $clan = sqla("SELECT name,dmoney,sait,level FROM clans WHERE sign='".$pers["sign"]."'");
  $pres["state"] = "[".$row["state"]."]";


  $color = '#333333';
if ($pers["clan_state"]=='g') $color = '#990000';
if ($pers["clan_state"]=='z') $color = '#DD0000';
if ($pers["clan_state"]=='c') $color = '#009900';
if ($pers["clan_state"]=='k') $color = '#000099';
if ($pers["clan_state"]=='b') $color = '#009999';
if ($pers["clan_state"]=='p') $color = '#00DDDD';
if ($pers["sign"]<>'none')$INFO_TEXT .= "<font class=babout>Клан</font><center class=but><font class=items>Персонаж состоит в клане <b><img src='images/signs/".$pers["sign"].".gif'>".$clan["name"]."</b>[".$clan["level"]."].</font><br>Должность:<b style='color:".$color."'>"._StateByIndex($pers["clan_state"])."</b>[".$pers['state']."]<br><a href='http://".$clan["sait"]."/' class=blocked target=_blank>Сайт клана</a></center>";


$INFO_TEXT .= "<font class=babout>Время онлайн</font><div class=laar><font class=gray>Последний визит:<b> ".time_echo(time()-$pers["lastom"])."</b></font><br><font class=gray>Время онлайн:<b> ".tp($pers["timeonline"]+$curtimeonline)."</b></font></div>";

if (($_SESSION["sign"]=='watchers' or strpos(" ".$_SESSION["rank"],"<pv>")>0))
{
	$level = sqla("SELECT exp FROM exp WHERE level=".($pers["level"]+1));
$INFO_TEXT .= "<font class=babout>Смотрителям</font>
<table border=0 cellspacing=0 cellspadding=0 class=LinedTable id=wttable>
<tr>
<td class=gray>ID:</td><td class=user>".$pers["uid"]."</td></tr><tr>
<td class=gray>Текущий IP:</td><td class=babout><a href=http://www.ripe.net/fcgi-bin/whois?form_type=simple&full_query_string=&searchtext=".$pers["lastip"]." target=_blank><b>".$pers["lastip"]."</b></a></td></tr><tr>
<td class=gray>Дата рождения:</td><td class=babout>".$pers["dr"]."</td></tr><tr>
<td class=gray>Дата регистрации:</td><td class=babout>".$pers["ds"]."</td></tr><tr>
<td class=gray>E-mail:</td><td class=ma>".$pers["email"]."</td></tr><tr>
<td class=gray>Деньги:</td><td class=babout>".$pers["money"]." LN</td></tr><tr>
<td class=gray>Валюта:</td><td class=babout>".$pers["dmoney"]." Бр.</td></tr><tr>
<td class=gray>Обнуления:</td><td class=hp>".$pers["zeroing"]."</td></tr><tr>
<td class=gray>Опыт:</td><td class=ma>".($pers["exp"]+$pers["peace_exp"])."</td></tr><tr>
<td class=gray>До уровня:</td><td class=green>".($level["exp"] - $pers["exp"]-$pers["peace_exp"])."</td></tr><tr>
<td class=hp>Пергаменты:</td><td class=babout>".$pers["coins"]."</td></tr><tr>
<td class=gray>Старший реферал:</td><td class=babout>".$pers["referal_nick"]."</td></tr><tr>
<td class=gray>Рефералов:</td><td class=babout>".$pers["refc"]."</td></tr><tr>
<td class=gray>Просматриваемая страница:</td><td class=babout>";
if ($pers["curstate"]==0) $INFO_TEXT .= "Персонаж";
elseif ($pers["curstate"]==1) $INFO_TEXT .= "Инвентарь";
elseif ($pers["curstate"]==2) $INFO_TEXT .= "Локация";
elseif ($pers["curstate"]==3) $INFO_TEXT .= "Возможности";
elseif ($pers["curstate"]==4) $INFO_TEXT .= "Бой";
$INFO_TEXT .= "</td></tr><tr>";
if ($pers["sms"]) $INFO_TEXT .= "<td class=gray>Пользовался смс-сервисом</td><td class=babout>".$pers["sms"]."</b> раз. Последний мобильный номер <b>+".$pers["phone_no"]."</b></td></tr><tr>";
if ($clan["dmoney"]) $INFO_TEXT .= "<td class=gray>Денег на счету клана:</td><td class=babout>".$clan["dmoney"]."</td></tr><tr>";

for($sp = 1;$sp<15; $sp++)
{
	$color = "#DDDDDD";
	if($sp%2==0) $color = "#EEEEEE";
$INFO_TEXT .= "<tr bgcolor=".$color."><td class=timef nowrap>".name_of_skill("sp".$sp).":</td><td class=ma>".round($pers["sp".$sp],2)."</td></tr>";
}


$INFO_TEXT .= "</table>";
}
//if ($pers["image_in_info"]) $INFO_TEXT .= "<center><img src='".$pers["image_in_info"]."'></center>";
?>
</div><div id=inf_from_php2 style='visibility:hidden;position:absolute;top:0px;height:0;'><? echo$INFO_TEXT;?></div>
<script><?

if ($_SESSION["sign"]=='watchers'or strpos(" ".$_SESSION["rank"],"<pv>")>0)
	$_WT = true;
else
	$_WT = false;

include('inc/inc/p_clothes.php');
$zv=sqla ("SELECT name FROM `zvanya` WHERE `id` = '".$pers["zvan"]."'");
$hp = $pers["chp"];
$ma = $pers["cma"];
$sphp = 9999;
$spma = 9999;
$pers["money"]=-1;
$pers["dmoney"]=-1;
$pers["udmax"]=-1;
if ($pers["main_present"]==1) $pers["main_present"]='<img src="images/presents/m.jpg" title="Мужицкая медаль +15% к опыту.">';
elseif ($pers["main_present"]==2) $pers["main_present"]='<img src="images/presents/58.jpg" title="Женская медаль +15% к опыту.">';
elseif ($pers["main_present"])
 $pers["main_present"] = '<img src="images/presents/'.$pers["main_present"].'.jpg" title="Новогодняя медаль">';
 else $pers["main_present"]='';
echo "build_pers('".$sh["image"]."','".$sh["id"]."','".$oj["image"]."','".$oj["id"]."','".$or1["image"]."','".$or1["id"]."','".$po["image"]."','".$po["id"]."','".$z1["image"]."','".$z1["id"]."','".$z2["image"]."','".$z2["id"]."','".$z3["image"]."','".$z3["id"]."','".$sa["image"]."','".$sa["id"]."','".$na["image"]."','".$na["id"]."','".$pe["image"]."','".$pe["id"]."','".$or2["image"]."','".$or2["id"]."','".$ko1["image"]."','".$ko1["id"]."','".$ko2["image"]."','".$ko2["id"]."','".$br["image"]."','".$br["id"]."','".$pers["pol"]."_".$pers["obr"]."',0,'".$pers["sign"]."','".$pers["user"]."','".$pers["level"]."','".$pers["chp"]."','".$pers["hp"]."','".$pers["cma"]."','".$pers["ma"]."',".$pers["tire"].",'".$kam1["image"]."','".$kam2["image"]."','".$kam3["image"]."','".$kam4["image"]."','".$kam1["id"]."','".$kam2["id"]."','".$kam3["id"]."','".$kam4["id"]."',".$hp.",".$pers["hp"].",".$ma.",".$pers["ma"].",".$sphp.",".$spma.",".$pers["s1"].",".$pers["s2"].",".$pers["s3"].",".$pers["s4"].",".$pers["s5"].",".$pers["s6"].",".$pers["free_stats"].",".$pers["money"].",0,".$pers["kb"].",".$pers["mf1"].",".$pers["mf2"].",".$pers["mf3"].",".$pers["mf4"].",".$pers["mf5"].",".$pers["udmin"].",".$pers["udmax"].",".$pers["rank_i"].",'".$zv["name"]."',".$pers["victories"].",".$pers["losses"].",0,0,0,".$pers["zeroing"].",2,".intval($pers["diler"]).",0,'".$ws1."','".$ws2."','".$ws3."','".$ws4."','".$ws5."','".$ws6."','".$pers["main_present"]."',".intval($you["uid"]).",".$_PUNISHMENT.",".$_ONLINE.");";
?>
</script>
<?
if (!$_INV)
{
$as = sql("SELECT * FROM p_auras WHERE uid=".$pers["uid"]." and (esttime>".time()." or turn_esttime>".$pers["f_turn"].") and special<>2");
$txt = '';
while($a = mysql_fetch_array($as))
{
	$txt .= $a["image"].'#<b>'.$a["name"].'</b>@';
	$txt .= 'Осталось <i class=timef>'.tp($a["esttime"]-time()).'</i>';
	$params = explode("@",$a["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			$perc = '';
			if (substr($p[0],0,2)=='mf') $perc = '%';
			if ($p[1] and $p[0]<>'cma' and $p[0]<>'chp')
			$txt .= '@'.name_of_skill($p[0]).':<b>'.plus_param($p[1]).$perc.'</b>';
		}
	$txt .= '|';
}
echo "<script>view_auras('".$txt."');</script>";
}
?>