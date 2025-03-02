<?
$bot=mysql_fetch_array (sql("SELECT * FROM `bots` WHERE `id`='".$p[1]."'"));
$bot["chp"] = $p[2];
$bot["cma"] = $p[3];
$xy = explode("_",$p[4]);
$bot["x"] = $xy[0];
$bot["y"] = $xy[1];
$bot['botlib'] = $botlib;
if (strpos (" ".$fight["name"] , $botlib)<>0) $color="green"; else $color="blue";
//

$od = $bot["level"];
include ('inc/inc/bots/chooser.php');
$persvs["chp"]=0;
$ran=-1;
$temp1 = explode ("|",$fight["namevs"]);
$temp2 = explode ("|",$fight["name"]);
$c = count(explode("|",$fight["namevs"]))+count(explode("|",$fight["name"]));

$l = 90;
while ($persvs["chp"]==0 and $ran<=($c+1)) {
$ran++;
if ($temp1[$ran])
{
if ($color=='green') {
$temp = $temp1[$ran];
if (!substr_count($temp,'bot=')) 
$persvs = sqla('SELECT xf,yf,chp,aura FROM `users` WHERE `user`="'.$temp.'"');
if (substr_count($temp,'bot=')) {
$p = explode ('=',$temp);
$persvs["chp"] = $p[2];
$xy = explode("_",$p[4]);
$persvs["xf"] = $xy[0];
$persvs["yf"] = $xy[1];
}
if ($persvs["chp"] and ($k=sqrt(sqr($pers["x"]-$persvs["xf"])+sqr($pers["y"]-$persvs["yf"])))<$l)
{
	$temppvs = $temp;
	$l = $k;
}
}

if ($color=='blue') {
$temp = $temp2[$ran];
if (!substr_count($temp,'bot=')) 
$persvs = sqla('SELECT xf,yf,chp,aura FROM `users` WHERE `user`="'.$temp.'"');
if (substr_count($temp,'bot=')) {
$p = explode ('=',$temp);
$persvs["chp"] = $p[2];
$xy = explode("_",$p[4]);
$persvs["xf"] = $xy[0];
$persvs["yf"] = $xy[1];
}
if ($persvs["chp"] and ($k=sqrt(sqr($pers["x"]-$persvs["xf"])+sqr($pers["y"]-$persvs["yf"])))<$l)
{
	$temppvs = $temp;
	$l = $k;
}
}
}
}

$PVS_NICK = $persvs["user"];

if (strpos(" ".$temppvs,'bot=')==0) 
$persvs = sqla('SELECT * FROM `users` WHERE `user`="'.$temppvs.'"');
if (strpos(" ".$temppvs,'bot=')>0) {
$botlib = $temppvs;
$temp = explode ('=',$temppvs);
$persvs = sqla('SELECT * FROM `bots` WHERE `id`='.$temp[1].'');
$persvs['cfight'] = $cfight;
$persvs["posfight"] = $temp[4];
$persvs["chp"] = $temp[2];
$persvs["cma"] = $temp[3];
$persvs["bg"] = $_POST["bg"];
$persvs["bj"] = $_POST["bj"];
$persvs["bt"] = $_POST["bt"];
$persvs["bn"] = $_POST["bn"];
$persvs["bot"]=1;
$persvs["botlib"]=$botlib;
$persvs["uid"]='bot';
}

//
$pers = $bot;
$pers["cfight"] = $cfight;
$persvs['cfight'] = $cfight;

if ($pers["chp"]>0) {
$PVS_NICK = $persvs["user"];
include("udar.php");
}
?>