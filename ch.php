<?
error_reporting(0);
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

$opt = explode ("|",$_COOKIE["options"]);
include ('inc/functions.php');

if (@$_GET["sort"]) {
if ($_GET["sort"]=='2') $opt[2]="0+"; 
if ($_GET["sort"]=='1') $opt[2]="+0"; 
if ($_GET["sort"]=='z') $opt[2]='z';
if ($_GET["sort"]=='a') $opt[2]='a';
setcookie("options",implode ("|",$opt),time()+20000);
}

if ($opt[2]=="0+")$_GET["sort"]='2';
if ($opt[2]=="+0")$_GET["sort"]='1';
if ($opt[2]=="z")$_GET["sort"]='z';

include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $res);
unset($res);

?><SCRIPT LANGUAGE="JavaScript" src='js/newch_list.js?2'></SCRIPT><script><?
$pers = mysql_fetch_array(mysql_query("SELECT x,y,location,block,pass,rank,sign,user,uid,diler,priveleged,level FROM `users` WHERE `uid` = '".addslashes($_COOKIE["uid"])."'"));
if ($pers["block"] or $pers["pass"]<>$_COOKIE["hashcode"]) die("Exit : 0");
$place = $pers["location"];
if (@$_GET["ignore"])
{
	sql("INSERT INTO `ignor` ( `uid` , `nick` ) 
VALUES (
".$pers["uid"].", '".trim(str_replace("'","",$_GET["ignore"]))."'
);");
}
if (@$_GET["ignore_unset"])
{
mysql_query("DELETE FROM ignor WHERE uid=".$pers["uid"]." and nick='".trim(str_replace("'","",$_GET["ignore_unset"]))."'");
}
if (@$_GET["no_tip"])
{
	mysql_query("INSERT INTO `no_tips` ( `uid` , `tip_id` ) VALUES (".$pers["uid"].", ".intval($_GET["no_tip"]).");");
}
$t=time();
$t1=time () - 360 + microtime();

$vsego = mysql_result(mysql_query ("SELECT COUNT(uid) FROM `users` WHERE `online`='1'"),0);
$_max = mysql_fetch_assoc(mysql_query ("SELECT max_online,time_max_online FROM `configs` LIMIT 0,1"));
$max = $_max["max_online"];
$tmax = $_max["time_max_online"];

if ($t%60==0)
{
	if($max<$vsego)
		mysql_query ("UPDATE configs SET max_online=".$vsego.",time_max_online=".$t."");
	mysql_query ("UPDATE `users` SET `online` = '0',timeonline=timeonline+lastom-lastvisits,gain_time=0 WHERE `lasto` < ".$t1." and `lastom` < ".$t1." and online=1;");
}

if ($place<>'out')
$locname = mysql_result(mysql_query ("SELECT name FROM `locations` WHERE `id`='".$place."' ;"),0);
else
$locname = mysql_result(mysql_query ("SELECT name FROM `nature` WHERE `x`='".$pers["x"]."' and `y`='".$pers["y"]."' ;"),0);
echo "var locname = '".$locname."';";
echo "var xy='".$pers["x"]." : ".$pers["y"]."';";

if ($pers["level"])
	echo "var vsg=".$vsego.";";
else
	echo "var vsg=0;";

 
if (substr_count($pers["rank"],"<molch>") or $pers["diler"]=='1' or $pers["priveleged"] or 1)
 echo "var priveleged=1;";
else
 echo "var priveleged=0;";
 
 
if($place=='arena') $dQ = 'or sign=\'watchers\'';
 
if (empty($_GET["view"]) or $_GET["view"]=="this")
{
if ($place<>'out')
$res = mysql_query("SELECT sign,user,level,state,diler,clan_name,uid,priveleged,silence,invisible,clan_state FROM `users` 
WHERE `online`=1 and (`location`='".$place."' ".$dQ.")"); 
else
$res = mysql_query("SELECT sign,user,level,state,diler,clan_name,uid,priveleged,silence,invisible,clan_state FROM `users` WHERE `online`=1 and `location`='out' and x=".$pers["x"]." and y=".$pers["y"]); 
}
else
$res = sql("SELECT sign,user,level,state,diler,clan_name,uid,priveleged,silence,invisible,clan_state FROM `users` WHERE `online`=1");
$i=0;
$s='';
$tyt=0;


$r = '';
if ($place<>'out') 
	$rsds = sql("SELECT * FROM residents WHERE location='".$place."'");
else
	$rsds = sql("SELECT * FROM residents WHERE x=".$pers["x"]." and y=".$pers["y"]." and location='out'");

while($rs = mysql_fetch_array($rsds))
{
 $b = sqlr("SELECT level FROM bots WHERE id=".$rs["id_bot"]);
 $r.= "'".$rs["name"]."|".$b."|".$rs["id"]."|".$rs["id_bot"]."'";
 $r.= ",\n";
 $tyt++;
}


$ignore = '';
$ign  = sql("SELECT nick FROM ignor WHERE uid=".$pers["uid"]."");
while ($ig = mysql_fetch_array($ign,MYSQL_ASSOC))
 $ignore.= $ig["nick"].'|';

while ($row=mysql_fetch_array($res)) 
{
 $tyt++;
 $i++;
 $tr='';
 $trs = sql("SELECT special FROM p_auras WHERE uid=".$row["uid"]." and special>2 and special<6 and esttime>".time()); 
 while($ttt = mysql_fetch_array($trs,MYSQL_ASSOC))
 {
  if ($ttt["special"]==3) $tr .= "Легкая травма.";
  if ($ttt["special"]==4) $tr .= "Средняя травма.";
  if ($ttt["special"]==5) $tr .= "Тяжёлая травма.";
 }
 unset($clan);
 if ($row["sign"]<>'none' and $row["sign"]<>'')
 {
 if (!$pers["clan_name"])
 $clan = mysql_fetch_array(sql("SELECT name,level FROM clans WHERE sign='".$row["sign"]."'"));
 $row["clan_name"] = $clan["name"];
 sql("UPDATE users SET clan_name='".$row["clan_name"]."' WHERE uid=".$row["uid"]."");
 }
 $row["state"] = $row["clan_name"]."[".$clan["level"]."] "._StateByIndex($row["clan_state"])."[".str_replace("|","!",$row["state"])."]";
 if ($row["invisible"]<=time() or $row["user"]==$pers["user"] or $pers["sign"]=='watchers' or substr_count($pers["rank"],"<pv>"))	$inv = 1; else $inv=0;
 if ($row["priveleged"]) 
 $prv = sqlr ("SELECT status FROM `priveleges` WHERE `uid`=".$row["uid"]);
 else $prv = '';
	if($row["user"]=='Soul')
		$prv = 'Дизайнер';
 if ($inv)
 {
 $row["state"] = str_replace("'","&quot;",$row["state"]);
 if ($row["invisible"]>time())$row["user"]="n=".$row["user"];
 $s.= "'".$row["user"]."|".$row["level"]."|".$row["sign"]."|".$row["state"]."|";
 if ($row["silence"]>time()) $s.= ($row["silence"]-time())."|"; else $s.= "|";
 $s.= $tr."|";
 $s.= $row["diler"]."|";
 if (substr_count("|".$ignore,"|".$row["user"]."|")) $s.= ".|"; else $s.= "|";
 $s .= $prv."|";
 $s .= "'";
 }
 if ($inv) $s.= ",\n";
}
mysql_free_result($res);
echo "var list=new Array(\n";
echo substr($s,0,strlen($s)-2);
echo ");\n";
echo "var residents=new Array(\n";
echo substr($r,0,strlen($r)-2);
echo ");\n";

echo "var zds=".$tyt.";show_head();";
echo "show_list ('".intval($_GET["sort"])."','".$_GET["view"]."');";

if (substr_count($pers["rank"],"<molch>") or $pers["diler"]=='1' or $pers["priveleged"])
 echo "var molch=1;";
else
 echo "var molch=0;";
?></script>