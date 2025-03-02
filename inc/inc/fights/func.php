<?

function begin_fight($names,$namesvs,$type,$travm,$timeout,$oruj){
error_reporting (E_ALL & ~E_NOTICE);
$lt = date("d.m.Y H:i");
GLOBAL $k;

if ($names[strlen($names)-1]=='|') $names = substr($names,0,strlen($names)-1);
if ($namesvs[strlen($namesvs)-1]=='|') $namesvs = substr($namesvs,0,strlen($namesvs)-1);
$rr=mysql_fetch_array (mysql_query ("SELECT MAX(id) FROM `fights`"));
$idf=$rr[0]+1;

$all = '<font class=time>'.$lt.'</font> Бой между ';
unset ($turns);
$turns[0] = '';
unset ($exps);
$exps[0] = 0;
$n = -1;$i=0;

$tmp1 = explode("|",$names);
foreach ($tmp1 as $tmp) {
if (strpos(" ".$tmp,"bot=")>0) 
 {
	$e = explode("=",$tmp);
	$p = mysql_fetch_array (mysql_query("SELECT * FROM `bots` WHERE `id`='".$e[1]."'"));
	if (@$p["id"])
	{
	unset ($e);
	$e='';
	$e[0] = 'bot';
	$e[1] = $p["id"];
	$e[2] = $p['health'];
	$e[3] = $p['mana'];
	$e[4] = 0;
	$e[5] = $p["user"];
	$e[6] = $p['level'];
	$e[7] = time()+microtime();
	$p["lib"] = implode ("=",$e).'=';
	$tmp1[$i] = $p["lib"];
	}else
	array_splice($tmp,$i,1);
 }
else
 {
	$p = mysql_fetch_array (mysql_query("SELECT user,level,sign,rank_i,chealth,health,cmana,mana,sphp,spma,lastom,uid FROM `users` WHERE `user`='".$tmp."';"));
	mysql_query ("UPDATE `users` SET `posfight`=0,".hp_ma_up($p["chealth"],$p["health"],$p["cmana"],$p["mana"],$p["sphp"],$p["spma"],$p["lastom"])." WHERE `uid`='".$p["uid"]."';");
	$p["lib"] = $p["user"];
 }

$all .= "<img src='images/signs/".$p['sign'].".gif'><font class=green>".$p["user"]."</font>[<font class=lvl>".$p["level"]."</font>] ,";
$n++;$i++;
$turns[$n] = $p["lib"];
$exps[$n] = $p["rank_i"];
}
$all = substr ($all,0,strlen ($all)-1);
$all .= 'и ';
$tmp2 = explode("|",$namesvs);$i=0;
foreach ($tmp2 as $tmp) {
if (strpos(" ".$tmp,"bot=")>0) 
 {
	$e = explode("=",$tmp);
	$p = mysql_fetch_array (mysql_query("SELECT * FROM `bots` WHERE `id`='".$e[1]."'"));
	if (@$p["id"])
	{
	unset ($e);
	$e='';
	$e[0] = 'bot';
	$e[1] = $p["id"];
	$e[2] = $p['health'];
	$e[3] = $p['mana'];
	$e[4] = 10;
	$e[5] = $p["user"];
	$e[6] = $p['level'];
	$e[7] = time()+microtime();
	$p["lib"] = implode ("=",$e).'=';
	$tmp2[$i] = $p["lib"];
	}else
	array_splice($tmp2,$i,1);
 }
else
 {
	$p = mysql_fetch_array (mysql_query("SELECT user,level,sign,rank_i,chealth,health,cmana,mana,sphp,spma,lastom,uid FROM `users` WHERE `user`='".$tmp."';"));
	mysql_query ("UPDATE `users` SET `posfight`=10,".hp_ma_up($p["chealth"],$p["health"],$p["cmana"],$p["mana"],$p["sphp"],$p["spma"],$p["lastom"])." WHERE `uid`='".$p["uid"]."';");
	$p["lib"] = $p["user"];
 }

$all .= "<img src='images/signs/".$p['sign'].".gif'><font class=blue>".$p["user"]."</font>[<font class=lvl>".$p["level"]."</font>] ,";
$n++;$i++;
$turns[$n] = $p["lib"];
$exps[$n] = $p["rank_i"];
}
$all = substr ($all,0,strlen ($all)-1).".(".$type.")";

for ($i=0;$i<=$n;$i++)
for ($j=0;$j<=$n-1;$j++)
 if ($exps[$j]>$exps[$j+1])
 {
	$tmp = $turns[$j];
	$temp = $exps[$j];
	$turns[$j] = $turns[$j+1];
	$exps[$j] = $exps[$j+1];
	$turns[$j+1] = $tmp;
	$exps[$j+1] = $temp;
 }
 
$turns = implode ("|",$turns)."|";

$names = implode ("|",$tmp1);
$namesvs = implode ("|",$tmp2);
if (substr_count("|",$names)==0) $names = $names."|";
if (substr_count("|",$namesvs)==0) $namesvs = $namesvs."|";
mysql_query ("INSERT INTO `fights` (`name`,`namevs`,`id`,`turn`,`oruj`,`travm`,`all`,`timeout` , `ltime` , `stones`) VALUES ('".$names."','".$namesvs."',".$idf.",'".$turns."','".$oruj."','".$travm."','".addslashes($all)."' ,".$timeout." ,".time().", ".rand(0,16).") ");


$names = $tmp1;
$namesvs = $tmp2;
$query = '';
foreach ($names as $n) 
$query .= "`user`='".$n."' or";
foreach ($namesvs as $n) 
$query .= "`user`='".$n."' or";
$query = substr ($query,0,strlen ($query)-2);
mysql_query ("UPDATE `users` SET `cfight`='".$idf."',`curstate`=4,`refr`=1 WHERE ".$query."");
$k=1;
}



?>