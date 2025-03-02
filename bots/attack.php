<?
$lb = 120;
//$all_time = $get_time = time() + microtime(); 
if($SPECIAL_pers) 
	$pers = $SPECIAL_pers;
else
	$pers = sqla("SELECT * FROM users WHERE online=1 and location='out' and cfight=0 and apps_id=0 and curstate<5 and lb_attack<".(tme()-2*$lb)." LIMIT 0,1;");
//$get_time = time() + microtime() - $get_time;
$mine = 0;

if ($pers)
{
$lb_attack = tme()+30-rand(0,$lb*2);
set_vars("lb_attack=".$lb_attack,$pers["uid"]);

	$timeout = 300;
	$x = $pers["x"];
	$y = $pers["y"];

if ($pers["gain_time"]>(tme()-1200))
{
	$lb_attack = tme()-rand($lb*1.5,$lb*2);
	$cell = sqla("SELECT bot,blvlmin,blvlmax,type FROM `nature` WHERE x>=".($x-1)." and x<=".($x+1)." and 
y>=".($y-1)." and y<=".($y+1)." ORDER BY RAND() LIMIT 0,1");
	$timeout = 50;
	list($a1,$a2) = sqla ("SELECT idmin,idmax FROM nature_bots WHERE x>=".($x-1)." and x<=".($x+1)." and 
y>=".($y-1)." and y<=".($y+1)." ORDER BY RAND()");
	$BTS = rand(intval($a1),intval($a2));
}else
{
list($a1,$a2) = sqla ("SELECT idmin,idmax FROM nature_bots WHERE x=".($x)." and y=".($y)." ORDER BY RAND()");
$BTS = rand(intval($a1),intval($a2));
}

if($BTS==0)
{
$cell = sqla("SELECT bot,blvlmin,blvlmax,type FROM `nature` WHERE `x`='".$pers["x"]."' and `y`='".$pers["y"]."'");
$user = sqla("SELECT user FROM bots WHERE id='".$cell["bot"]."'");
$bot_id2 = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id3 = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id = sqla ("SELECT id FROM bots WHERE user='".$user["user"]."' and level=".rand($cell["blvlmin"],$cell["blvlmax"])."");
$bot_id2 = $bot_id2["id"];
$bot_id3 = $bot_id3["id"];
$bot_id = $bot_id["id"];
}
else
{
$bot_id = $BTS;
list($a1,$a2) = sqla ("SELECT idmin,idmax FROM nature_bots WHERE x=".($x)." and y=".($y)." ORDER BY RAND()");
$bot_id2 = rand($a1,$a2);
list($a1,$a2) = sqla ("SELECT idmin,idmax FROM nature_bots WHERE x=".($x)." and y=".($y)." ORDER BY RAND()");
$bot_id3 = rand($a1,$a2);
}



$f_type = 0;
if ($cell["type"]==0) $f_type = 1;
if ($cell["type"]==1) $f_type = 1;
if ($cell["type"]==2) $f_type = 4;
if ($cell["type"]==6) $f_type = 5;
if ($cell["type"]==8) $f_type = 2;
if ($cell["type"]==3) $f_type = 3;
//$f_type = 0;
$perstowho = $pers["user"];
$bots=sql("SELECT id,rank_i FROM bots WHERE id='".$bot_id."' or id='".$bot_id2."' or id='".$bot_id3."' and rank_i>".($pers["rank_i"]/10)." LIMIT 0,3");
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

if ($bot['id']) {
	
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

if($SPECIAL_count==3)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"],$perstowho,"Нападение существ",$travm,$timeout,1,$f_type);
if($SPECIAL_count==6)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"],$perstowho,"Нападение существ",$travm,$timeout,1,$f_type);
else
if (!$mine)
{
if (rand(1,100)<50)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"],$perstowho,"Нападение существа",$travm,300,1,$f_type);
elseif (rand(1,100)<20)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"],$perstowho,"Нападение существ",$travm,$timeout,1,$f_type);
elseif (rand(1,100)<10)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"],$perstowho,"Нападение существ",$travm,$timeout,1,$f_type);
elseif (rand(1,100)<5)
	begin_fight ("bot=".$bot["id"]."|"."bot=".$bot2["id"]."|"."bot=".$bot3["id"]."|"."bot=".$bot["id"]."|"."bot=".$bot2["id"],$perstowho,"Нападение существ",$travm,$timeout,1,$f_type);
else
	begin_fight ("bot=".$bot["id"],$perstowho,"Нападение существа",$travm,$timeout,1,$f_type);
}
}

//$all_time = time() + microtime() - $all_time;
//say_to_chat ("a",'<b>'.$pers["user"].'</b> Время выборки: '.$get_time.' | Время работы всего скрипта: '.$all_time.'',1,'sL','*');
}
?>