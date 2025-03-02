<?
include ("./inc/locations/quest/quests.php"); 
$t1 = sqla("SELECT * FROM quest WHERE id = ".TOUR2."");
if(empty($_GET["show_t2"]) and !$t1["finished"]) // Турнир 1 10-15 10x10
{
	$tour2 .= "<a class=wbut href='main.php?show_t2=1'>Турнир 2</a>";
}
if(@$_GET["show_t2"] and !$t1["finished"]) // Турнир 1 10-15 10x10
{
	echo "<table border=0><tr><td><a class=but href='main.php'>Убрать</a></td><td><a class=but href='main.php?show_t2=2'>Обновить</a></td></tr></table>";
}
if(@$_GET["show_t2"] and !$t1["finished"] and $t1["type"]==0) // Турнир 1 10-15 10x10
{
	if(@$_GET["tour2"]=="join" and $pers["level"]>=10 and $pers["level"]<=15 and $pers["tour"]==0)
	{	
		set_vars("tour=2",UID);
		$pers["tour"]=2;
	}	
	if(!mtrunc(T_START+$t1["time"]-tme()))
	{
		$id = start_t1(20,1,2,TOUR2);
		sql("UPDATE quest SET finished=0,time=".tme()."+".T_START.",type=1,lParam=".$id." WHERE id = ".TOUR2);
	}
	else
	{
	$start = "<b class=mfb>Начало через ".mtrunc(T_START+$t1["time"]-tme())." сек.</b>";
	echo "<div class=but>";
	echo '<table border=0 width=98% cellspacing=0 cellspadding=0 style="height:16px;"><tr><td style="background-image: url(\'images/DS/graybg_left.png\'); background-position:bottom left; height:16px; width:12px;"></td><td style="background-image: url(\'images/DS/graybg.png\');" align=center nowrap>';
	echo "<span style='color:#FFFFFF'><b class=lUser>Турнир 2</b> | <b>[10-15]</b> уровни | Травматичность <b>80%</b> | Таймаут <b>90 секунд</b>. | ".$start."</span>";
	echo '</td><td style="background-image: url(\'images/DS/graybg_right.png\'); background-position:bottom right; height:16px; width:12px;"></td></tr></table>';
	$users = sql("SELECT * FROM users WHERE tour=2;");
	$bots = sql("SELECT * FROM bots WHERE level=15 ORDER BY RAND() LIMIT 0,20;");
	$can_join = 0;
	echo "Участники:";
	echo "<center>";
	echo "<table border=0 cellspacing=0 cellspadding=0 class=but width=600>";
	echo "<tr><td>";
	echo "<table border=0 cellspacing=0 cellspadding=0 class=but width=300>";
	for($i=0;$i<20;$i++)
	{
		$u1 = $u;
		$u = mysql_fetch_array($users,MYSQL_ASSOC);
		if(!$u)
		{
			$can_join = 1;
			$u = mysql_fetch_array($bots,MYSQL_ASSOC);
			if(!$u)
				$u = $u1;
			$info = "<a href=binfo.php?".$u["id"]." target=_blank><img src=images/i.gif></a>";
		}
		else
			$info = "<a href=info.php?id=".$u["uid"]." target=_blank><img src=images/i.gif></a> <b class=mfb style='color:#009900'>[человек]</b>";
		echo "<tr>";
		echo "<td>";
		echo "<b>".$u["user"]."</b> [<b class=lvl>".$u["level"]."</b>]".$info;
		echo "</td>";
		echo "</tr>";
	} 
	echo "</table>";
	echo "</td><td>";
	echo "<i>Описание:</i> Начинается классический, хаотический бой 10х10 между участниками турнира.
Победители боя воскресают с полными жизнями и манной. Через некоторое время начинается вторая стадия турнира , 5х5 , между победителями первой стадии. Победители второй стадии выигрывают призы.
<br><i>Призы:</i> 10 пергаментов, 100 LN, 10000 опыта";
	echo "</td></tr></table>";
	
	if($pers["level"]>=10 and $pers["level"]<=15 and $pers["tour"]==0)
		echo "<a class=gbut href='main.php?tour2=join&show_t2=2'>Вступить</a>";
	
	echo "</center>";
	echo "</div>";
	}
}
elseif(@$_GET["show_t2"] and ($t1["type"]==1 or $t1["type"]==2) and !$t1["finished"])
{
	if(!mtrunc(T_START2+$t1["time"]-tme()))
	{
		$id = start_t1(10,2,2,TOUR2);
		sql("UPDATE quest SET finished=0,time=".tme().",type=3,lParam=".$id." WHERE id = ".TOUR2);
	}
	else
	{
	$start = "";
	if($t1["type"]==2)
		$start = "<b class=mfb>Начало через ".mtrunc(T_START2+$t1["time"]-tme())." сек.</b>";
	$log = sql("SELECT time,log FROM fight_log WHERE cfight=".$t1["lParam"]." ORDER BY turn DESC LIMIT 0,2");
	$_LOG = '';
	while ($l = mysql_fetch_array($log))
		$_LOG .= str_replace("'",'"',str_replace("%","<br><font class=timef>".$l["time"]."</font> ","<font class=timef>".$l["time"]."</font> ".$l["log"]))."<hr>";
	$fight_res = sqlr("SELECT result FROM fights WHERE id=".$t1["lParam"]."");
	echo "<div class=but style='text-align:left;'>";
	echo "<div class=but>";
	echo $start." Турнир №1. Этап №1. <a class=nt href='battle_log.php?id=".$t1["lParam"]."' target=_blank>Лог боя</a>";		
	echo "</div>";
	echo $fight_res.$_LOG;	
	echo "</div>";
	}
}
elseif($t1["type"]==3 and !$t1["finished"])
	sql("UPDATE quest SET finished=1,time=".tme()." WHERE id=".TOUR2);
?>