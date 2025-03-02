<script type="text/javascript" src="js/newup.js?k"></script><script><?
$t=tme();
echo "var images='".$images."';";

##
if ($pers["gain_time"]>(tme()-1200)) unset($_GET["go"]);
 if ($t<$pers["waiter"] and !$pers["cfight"]) 
 {
	$pers["cfight"]=10;
	$pers["apps_id"] = 1;
 }
 if ($pers["cfight"]==0 and !$pers["apps_id"]){
 if (@$_GET["go"]=="pers") {
 sql ("UPDATE `users` SET `curstate` = 0 WHERE `uid`=".UID." ;");
 $pers["curstate"]=0;
 }else
 if (@$_GET["go"]=="inv") {
 sql ("UPDATE `users` SET `curstate` = 1 WHERE `uid`=".UID." ;");
 $pers["curstate"]=1;
 }else
 if (@$_GET["go"]=="back" and !$trvm) {
	if ($pers["help"]==2 and $pers["level"]==0)
	{
		set_vars("chp=hp,cma=ma",UID);
		$pers["chp"] = $pers["hp"];
		$pers["cma"] = $pers["ma"];
	}
  sql ("UPDATE `users` SET `curstate` = 2,help=3 WHERE `uid`=".UID." ;");
  $pers["curstate"]=2;
  $pers["help"] = 3;
 }else
 if (@$_GET["go"]=="addon") {
 sql ("UPDATE `users` SET `curstate` = 3 WHERE `uid`=".UID." ;");
 $pers["curstate"]=3;
 }else
 if (@$_GET["go"]=="self"  and !$trvm) {
 sql ("UPDATE `users` SET `curstate` = 5 WHERE `uid`=".UID." ;");
 $pers["curstate"]=5;
 }else
 if (@$_GET["go"]=="friends"  and !$trvm) {
 sql ("UPDATE `users` SET `curstate` = 6 WHERE `uid`=".UID." ;");
 $pers["curstate"]=6;
 }else
 if ($pers["priveleged"])
 {
 if (@$_GET["go"]=="map_edit" and $priv["emap"]) {
 sql ("UPDATE `users` SET `curstate` = 16 WHERE `uid`=".UID." ;");
 $pers["curstate"]=16;
 }else
 if (@$_GET["go"]=="add_new") {
 sql ("UPDATE `users` SET `curstate` = 17 WHERE `uid`=".UID." ;");
 $pers["curstate"]=17;
 }else
 if (@$_GET["go"]=="add_tip") {
 sql ("UPDATE `users` SET `curstate` = 18 WHERE `uid`=".UID." ;");
 $pers["curstate"]=18;
 }
 if (@$_GET["go"]=="administration") {
 sql ("UPDATE `users` SET `curstate` = 20 WHERE `uid`=".UID." ;");
 $pers["curstate"]=20;
 }else
 if (@$_GET["go"]=="media"  and $priv["emedia"]) {
 sql ("UPDATE `users` SET `curstate` = 21 WHERE `uid`=".UID." ;");
 $pers["curstate"]=21;
 }else
 if (@$_GET["go"]=="weapons"  and $priv["ewp"]) {
 sql ("UPDATE `users` SET `curstate` = 22 WHERE `uid`=".UID." ;");
 $pers["curstate"]=22;
 }
 if (@$_GET["go"]=="magic"  and $priv["emagic"]) {
 sql ("UPDATE `users` SET `curstate` = 23 WHERE `uid`=".UID." ;");
 $pers["curstate"]=23;
 }
 if (@$_GET["go"]=="bots"  and $priv["ebots"]) {
 sql ("UPDATE `users` SET `curstate` = 24 WHERE `uid`=".UID." ;");
 $pers["curstate"]=24;
 }
 if (@$_GET["go"]=="ministers"  and $priv["emain"]) {
 sql ("UPDATE `users` SET `curstate` = 25 WHERE `uid`=".UID." ;");
 $pers["curstate"]=25;
 }
 if (@$_GET["go"]=="users"  and $priv["eusers"]) {
 sql ("UPDATE `users` SET `curstate` = 26 WHERE `uid`=".UID." ;");
 $pers["curstate"]=26;
 }
 if (@$_GET["go"]=="quests"  and $priv["equests"]) {
 sql ("UPDATE `users` SET `curstate` = 27 WHERE `uid`=".UID." ;");
 $pers["curstate"]=27;
 }
 if (@$_GET["go"]=="questsR"  and $priv["equests"]) {
 sql ("UPDATE `users` SET `curstate` = 28 WHERE `uid`=".UID." ;");
 $pers["curstate"]=28;
 }
 if (@$_GET["go"]=="questsS"  and $priv["equests"]) {
 sql ("UPDATE `users` SET `curstate` = 29 WHERE `uid`=".UID." ;");
 $pers["curstate"]=29;
 }
  if (@$_GET["go"]=="questsQ"  and $priv["equests"]) {
 sql ("UPDATE `users` SET `curstate` = 30 WHERE `uid`=".UID." ;");
 $pers["curstate"]=30;
 } 
 if (@$_GET["go"]=="ava_req" and $pers["priveleged"]) {
 sql ("UPDATE `users` SET `curstate` = 31 WHERE `uid`=".UID." ;");
 $pers["curstate"]=31;
 }
 if (@$_GET["go"]=="clans" and $pers["priveleged"]) {
 sql ("UPDATE `users` SET `curstate` = 32 WHERE `uid`=".UID." ;");
 $pers["curstate"]=32;
 }
 }
 }
##
		
 #
if ($pers["location"]<>'out')
 $location = sqla("SELECT go_id,name FROM `locations` WHERE `id`='".$pers["location"]."'");
 #
 
if ($location["go_id"]) 
 $out = sqla("SELECT name FROM `locations` WHERE `id`='".$location["go_id"]."'");
 
## 
if($pers["prison"])
{
$prison = explode ('|',$pers["prison"]);
if ($prison[0]<tme() and $pers["curstate"]<>4 and $pers["prison"]) 
 set_vars("prison=''",$pers["uid"]);
if ($prison[0]>tme()) 
 {
  $out["name"]='';
  $location["go_id"]='';
 }
}
##
 
if (empty($out["name"]))
 $out["name"] = '';
  
 if (abs(10+($pers["sm3"]+$pers["s4"])*10) < $pers["weight_of_w"]) 
  $_OVERWEIGHT = 1;
 else
  $_OVERWEIGHT = 0;
  
echo "show_head('".$pers["curstate"]."','".$out["name"]."','".addslashes(build_go_string($location["go_id"],$lastom_new))."',".intval($pers["apps_id"]).",".($_TRVM+$_OVERWEIGHT).",".intval($pers["help"]).");";
?></script>