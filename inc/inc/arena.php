<?
$yourzay=mysql_fetch_array (mysql_query ("SELECT * FROM `zayavki` WHERE `name`='".$_SESSION["user"]."|' and `vsname`='' ;"));
 if ($yourzay["name"]<>"" and $_GET["deletezay"]==1) {mysql_query ("DELETE FROM `zayavki` WHERE `name`='".$_SESSION["user"]."|' and `vsname`='' ;");
 mysql_query ("UPDATE `users` SET `cfight`='' WHERE `uid`='".$_SESSION["uid"]."';");}
 
$yourzay=mysql_fetch_array (mysql_query ("SELECT * FROM `zayavki` WHERE `name`='".$_SESSION["user"]."' or `vsname`='".$_SESSION["user"]."' ;"));

if ($_POST["type"]=="duel" and $pers["cfight"]<>1 and $pers["cfight"]<>2 and $pers["cfight"]<>-1){
$hjh=1;
if ($_POST["oruj"]==0 and $wears=="") $hjh=1; elseif ($_POST["oruj"]==0) $hjh=2;
if ($hjh==1) {mysql_query ("INSERT INTO `zayavki` (`name`,`type`,`travm`,`oruj`,`timeout`,`time`) VALUES ('".$pers["user"]."','duel','".$_POST["travm"]."','".$_POST["oruj"]."',".$_POST["timeout"].",".(time()).");");
mysql_query ("UPDATE `users` SET `cfight`=1 , `refr`=1 WHERE `uid`=".$_SESSION["uid"].";");}
}
if ($_POST["type"]=="group" and $pers["cfight"]<>1 and $pers["cfight"]<>2 and $pers["cfight"]<>-1){
$hjh=1;
foreach ($_POST as $p) if ($p=='') $hjh=3;
if ($_POST["oruj"]==0 and $wears=="") $hjh=1; elseif ($_POST["oruj"]==0) $hjh=2;
if ($_POST["maxlvl1"]<$pers["level"]) $_POST["maxlvl1"]=$pers["level"];
if ($hjh==1) {mysql_query ("INSERT INTO `zayavki` (`name`,`type`,`travm`,`oruj`,`timeout`,`time`,mpl1,mpl2,minlvl1,minlvl2,maxlvl1,maxlvl2) VALUES ('".$pers["user"]."|','group','".$_POST["travm"]."','".$_POST["oruj"]."','".$_POST["timeout"]."', '".(time()+$_POST["time"])."','".$_POST["mpl1"]."','".$_POST["mpl2"]."','".$_POST["minlvl1"]."','".$_POST["minlvl2"]."','".$_POST["maxlvl1"]."','".$_POST["maxlvl2"]."');");
mysql_query ("UPDATE `users` SET `cfight`=-2 WHERE `uid`=".$_SESSION["uid"].";");}
}
if ($pers["cfight"]==1 and $_GET["doarena"]=="otkaz") {
mysql_query ("INSERT INTO `chat` (`user`,`time2`,`message`,`private`,`towho`,`location`) VALUES ('Арена',".time().",'".$_SESSION["user"]." отказался от поединка с вами.',1,'".$yourzay["vsname"]."','".$pers["location"]."')");
mysql_query ("UPDATE `zayavki` SET `vsname`='' WHERE `name`='".$_SESSION["user"]."' ;");
mysql_query ("UPDATE `users` SET `cfight`='' WHERE `user`='".$yourzay["vsname"]."';");
}
if (empty($_GET["goarena"]) and $_SESSION["goarena"]=="") $_GET["goarena"]="duel";
if ($pers["cfight"]==2 and $_GET["doarena"]=="otozv") {
mysql_query ("INSERT INTO `chat` (`user`,`time2`,`message`,`private`,`towho`,`location`) VALUES ('Арена',".time().",'".$_SESSION["user"]." отказался от поединка с вами.',1,'".$yourzay["name"]."','".$pers["location"]."')");
mysql_query ("UPDATE `zayavki` SET `vsname`='' WHERE `vsname`='".$_SESSION["user"]."' ;");
mysql_query ("UPDATE `users` SET `cfight`='' WHERE `uid`='".$_SESSION["uid"]."';");
}
if ($pers["cfight"]<>1 and !empty($_POST["towhozay"]) and $_POST["towhozay"]<>$_SESSION["user"] and $_GET["type"]=="duel"){
mysql_query ("INSERT INTO `chat` (`user`,`time2`,`message`,`private`,`towho`,`location`) VALUES ('Арена',".time().",'".$_SESSION["user"]." принял вашу заявку на поединок.',1,'".$_POST["towhozay"]."','".$pers["location"]."')");
mysql_query ("UPDATE `zayavki` SET `vsname`='".$_SESSION["user"]."' WHERE `name`='".$_POST["towhozay"]."' ;");
mysql_query ("UPDATE `users` SET `cfight`=2 WHERE `uid`=".$_SESSION["uid"].";");
}
if ($pers["cfight"]<>1 and !empty($_POST["towhozay"]) and $_POST["towhozay"]<>$_SESSION["user"] and $_GET["type"]=="group"){
$post = explode ("_",$_POST["towhozay"]);
$zay = mysql_fetch_array (mysql_query ("SELECT * FROM `zayavki` WHERE `name`='".$post[1]."'"));
if ($post[0]==1) $q = "UPDATE `zayavki` SET `name`='".$zay["name"].$_SESSION["user"]."|' WHERE `name`='".$post[1]."' ;";
if ($post[0]==2) $q = "UPDATE `zayavki` SET `vsname`='".$zay["vsname"].$_SESSION["user"]."|' WHERE `name`='".$post[1]."' ;";
mysql_query ($q);
mysql_query ("UPDATE `users` SET `cfight`=-2 WHERE `uid`=".$_SESSION["uid"].";");
}
if ($pers["cfight"]==1 and $_GET["doarena"]=="delete") {
mysql_query ("DELETE FROM `zayavki` WHERE `name`='".$_SESSION["user"]."' ;");
mysql_query ("UPDATE `users` SET `cfight`='' WHERE `uid`='".$_SESSION["uid"]."';");
}
//начинаем бой
if ($_GET["doarena"]=="begin" and $yourzay["name"]<>"" and $yourzay["vsname"]<>"") {
begin_fight ($yourzay["name"],$yourzay["vsname"],"Дуэль на арене",$yourzay["travm"],$yourzay["timeout"],$yourzay["oruj"]);
}

?>