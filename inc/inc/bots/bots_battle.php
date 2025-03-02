<?
$die = '';
$text = '';
echo "/*";
$persTEMP = $pers;
for($i=0;$i<3;$i++)
{
//Битва ботов между собой
$bot1 = sqla("SELECT * FROM bots_battle WHERE cfight='".$fight["id"]."' and fteam=1 and chp>0");
$bot2 = sqla("SELECT * FROM bots_battle WHERE cfight='".$fight["id"]."' and fteam=2 and chp>0");
$bot1["xf"]=$bot2["xf"]=$bot1["yf"]=$bot2["yf"]=0;
$pers = $bot2;
$persvs = $bot1;
if($pers and $persvs)
	include("bot_brain.php");
else
	break;
 if ($die.$text)
 {
	add_flog($die.$text,$pers["cfight"]);
	$die = '';
	$text = ''; 
 }
$bot1 = sqla("SELECT * FROM bots_battle WHERE cfight='".$fight["id"]."' and fteam=1 and chp>0");
$bot2 = sqla("SELECT * FROM bots_battle WHERE cfight='".$fight["id"]."' and fteam=2 and chp>0");
$bot1["xf"]=$bot2["xf"]=$bot1["yf"]=$bot2["yf"]=0;
$pers = $bot1;
$persvs = $bot2;
if($pers and $persvs)
	include("bot_brain.php");
else
	break;
 if ($die.$text)
 {
	add_flog($die.$text,$pers["cfight"]); 
 }
}
 echo "*/\n";
$pers = $persTEMP;
?>