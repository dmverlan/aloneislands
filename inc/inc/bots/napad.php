<?
$lb = sqla("SELECT b_frequency FROM configs");
if (($pers["lb_attack"]+2*$lb["b_frequency"])<tme() and $pers["cfight"]==0 and $pers["apps_id"]==0 and $pers["curstate"]<5)
{
$cell = sqla("SELECT bot,blvlmin,blvlmax,type FROM `nature` WHERE `x`='".$pers["x"]."' and `y`='".$pers["y"]."'");
$lb_attack = tme()-rand(0,$lb["b_frequency"]*2);
$mine = 0;
$user = sqla("SELECT user FROM bots WHERE id='".$cell["bot"]."'");
$bot_id2 = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id3 = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id2 = $bot_id2["id"];
$bot_id3 = $bot_id3["id"];
$bot_id = $bot_id["id"];

$f_type = 0;
if ($cell["type"]==0) $f_type = 1;
if ($cell["type"]==1) $f_type = 1;
if ($cell["type"]==2) $f_type = 4;
if ($cell["type"]==6) $f_type = 5;
if ($cell["type"]==8) $f_type = 2;
if ($cell["type"]==3) $f_type = 3;
//$f_type = 0;

sql("UPDATE users SET lb_attack=".$lb_attack." WHERE uid=".$pers["uid"]);
$perstowho = $pers["user"];
$bots=sql("SELECT id FROM bots WHERE id='".$bot_id."' or id='".$bot_id2."' or id='".$bot_id3."'");
$bot = mysql_fetch_array($bots);
$bot2= mysql_fetch_array($bots);
$bot3= mysql_fetch_array($bots);
$b = 0;
if (@$bot["id"]) $b = $bot["id"];
if (@$bot2["id"]) $b = $bot2["id"];
if (@$bot3["id"]) $b = $bot3["id"];
if (empty($bot["id"])) $bot["id"]=$b;
if (empty($bot2["id"])) $bot2["id"]=$b;
if (empty($bot3["id"])) $bot3["id"]=$b;
if (@$bot['id']) {

$zz = rand(1,7);
switch($zz)
{
case 1: $travm = 10;break;
case 2: $travm = 30;break;
case 3: $travm = 30;break;
case 4: $travm = 50;break;
case 5: $travm = 80;break;
case 6: $travm = 80;break;
default: $travm = 100;break;
}

if (!$mine)
{
if (rand(1,100)<30)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"],$perstowho,"Нападение существа",$travm,300,1,$f_type);
elseif (rand(1,100)<10)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"],$perstowho,"Нападение существ",$travm,300,1,$f_type);
elseif (rand(1,100)<5)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"],$perstowho,"Нападение существ",$travm,300,1,$f_type);
elseif (rand(1,100)<3)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"]."|"."bot=".$bot2["id"],$perstowho,"Нападение существ",$travm,300,1,$f_type);
else
	begin_fight ("bot=".$bot["id"],$perstowho,"Нападение существа",$travm,300,1,$f_type);
}else begin_fight ("bot=".$bot["id"],$perstowho,"Нападение существа",$travm,300,1,$f_type);
}
}
?>