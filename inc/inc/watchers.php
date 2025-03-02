<script>var nick='<?= $pers["user"];?>';</script><script type="text/javascript" src="js/watchers.js"></script><script><?
$rank = " ".$_SESSION["rank"];
$m = (strpos($rank,"<molch>")) ? 1 : 0;
$p = (strpos($rank,"<prison>")) ? 1 : 0;
$b = (strpos($rank,"<block>")) ? 1 : 0;
$w = (strpos($rank,"<w_pom>")) ? 1 : 0;
$i = (strpos($rank,"<b_info>")) ? 1 : 0;
$d = (strpos($rank,"<diler>")) ? 1 : 0;
$lt = date("d.m.y H:i");
if ($_GET["do_w"]=='mpb') echo "show_mpb($m,$p,$b,$w,$i,$d);";

if (@$_POST["molch"] and $m==1 and $_POST["molch"]<>'sn') {
if ($_POST["molch"]==5) $timemolch = '5 минут';
if ($_POST["molch"]==10) $timemolch = '10 минут';
if ($_POST["molch"]==15) $timemolch = '15 минут';
if ($_POST["molch"]==30) $timemolch = '30 минут';
if ($_POST["molch"]==60) $timemolch = '1 час';
if ($_POST["molch"]==360) $timemolch = '6 часов';
if ($_POST["molch"]==1440) $timemolch = 'сутки';
echo 'show_message("Персонаж <b>'.$pers["user"].'</b> замолчал на '.$timemolch.' (<b>'.$_SESSION["user"].'</b>)");';
say_to_chat('w','Персонаж <b>'.$pers["user"].'</b> замолчал на '.$timemolch." (<b>".$_SESSION["user"]."</b>)",0,'','*',0); 
mysql_query ("UPDATE `users` SET aura='".$pers["aura"]."molch"."|".($_POST["molch"]*60+time())."|molch.gif|Заклинание молчания|@"."' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET molch='".$chars["molch"].$lt."|".$timemolch."|".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}

if (@$_GET["bug"]) {
echo 'show_message("BUG OFF ::: % <b>'.$pers["user"].'</b>");';
mysql_query ("UPDATE `users` SET cfight=0 , curstate=0 WHERE `uid`=".$pers["uid"]."");
}

if (@$_GET["logclear"]) {
echo 'show_message("Log clear ::  <b>'.$pers["user"].'</b>");';
mysql_query ("UPDATE `chars` SET transfers='',sales='',ips='' WHERE `uid`=".$pers["uid"]."");
}

if (@$_POST["molch"] and $m==1 and $_POST["molch"]=='sn') {
echo 'show_message("C персонажа <b>'.$pers["user"].'</b> снято заклинание молчания (<b>'.$_SESSION["user"].'</b>)");';
say_to_chat('<font class=att>Внимание</font>','C персонажа <b>'.$pers["user"].'</b> снято заклинание молчания (<b>'.$_SESSION["user"].'</b>)',0,'','*',0); 
$aura = explode("@",$pers["aura"]);
foreach($aura as $a) 
	if (substr_count($a,'molch')>0) $pers["aura"]=str_replace($a."@","",$pers["aura"]);
mysql_query ("UPDATE `users` SET aura='".$pers["aura"]."' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET molch='".$chars["molch"].$lt."|".'0'."|".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}

if (@$_POST["prison"] and $p==1 and $_POST["prisontime"]<>'vip') {
$_POST["prisontime"]=time()+intval($_POST["prisontime"])*86400;
echo 'show_message("Персонаж <b>'.$pers["user"].'</b> попал в тюремное заточение (<b>'.$_SESSION["user"].'</b>)");';
say_to_chat('<font class=att>Внимание</font>','Персонаж <b>'.$pers["user"].'</b> попал в тюремное заточение (<b>'.$_SESSION["user"].'</b>)',0,'','*',0); 
mysql_query ("UPDATE `users` SET location='prison',prison='".$_POST["prisontime"]."|".htmlspecialchars($_POST["prison"])."' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET blocks='".$chars["blocks"].$lt."|".$_POST["prisontime"]."|(".htmlspecialchars($_POST["prison"]).")|".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}

if ($p==1 and $_POST["prisontime"]=='vip') {
echo 'show_message("Персонаж <b>'.$pers["user"].'</b> выпущен из тюрьмы (<b>'.$_SESSION["user"].'</b>)");';
say_to_chat('<font class=att>Внимание</font>','Персонаж <b>'.$pers["user"].'</b> выпущен из тюрьмы (<b>'.$_SESSION["user"].'</b>)',0,'','*',0); 
mysql_query ("UPDATE `users` SET prison='' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET blocks='".$chars["blocks"].$lt."|".'0'."||".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}


if (@$_POST["block"] and $b==1 and $_POST["blockt"]<>'2') {
echo 'show_message("На персонажа <b>'.$pers["user"].'</b> наложено заклинание смерти, спи спокойно! (<b>'.$_SESSION["user"].'</b>)");';
say_to_chat('<font class=att>Внимание</font>','На персонажа <b>'.$pers["user"].'</b> наложено заклинание смерти, спи спокойно! (<b>'.$_SESSION["user"].'</b>)',0,'','*',0); 
mysql_query ("UPDATE `users` SET block='".htmlspecialchars($_POST["block"])."' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET blocks='".$chars["blocks"].$lt."|".'1'."|(".htmlspecialchars($_POST["block"]).")|".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}

if ($b==1 and $_POST["blockt"]=='2') {
echo 'show_message("<b>'.$_SESSION["user"].'</b> оживляет <b>'.$pers["user"].'</b> после смерти!");';
say_to_chat('<font class=att>Внимание</font>','<b>'.$_SESSION["user"].'</b> оживляет <b>'.$pers["user"].'</b> после смерти!',0,'','*',0); 
mysql_query ("UPDATE `users` SET block='' WHERE `uid`=".$pers["uid"]."");
mysql_query("UPDATE chars SET blocks='".$chars["blocks"].$lt."|".'2'."||".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
}

if (@$_POST["blocki"] and $i==1 and $_POST["blockit"]<>'2') {
echo 'show_message("На персонажа <b>'.$pers["user"].'</b> наложено заклинание блокирование информации(<b>'.$_SESSION["user"].'</b>)");';
mysql_query ("UPDATE `chars` SET about='Заблокировано. Причина: ".htmlspecialchars($_POST["blocki"])."' WHERE `uid`=".$pers["uid"]."");
}

if (@$_POST["pometka"] and $w==1) {
mysql_query("UPDATE chars SET zametki='".$chars["zametki"].$lt."|".htmlspecialchars($_POST["pometka"])."|".$_SESSION["user"]."@' WHERE uid='".$pers["uid"]."'");
echo 'show_message("Вы оставили пометку \''.htmlspecialchars($_POST["pometka"]).'\'");';
}

if ($_GET["do_w"]=='w_z') echo "zametki('".addslashes($chars["zametki"])."');";
if ($_GET["do_w"]=='rmpb') echo "rmpb('".addslashes($chars["molch"])."','".addslashes($chars["blocks"])."');";
if ($_GET["do_w"]=='ip') echo "ip('".addslashes($chars["ips"])."');";
if ($_GET["do_w"]=='pass') echo "pass('".addslashes($chars["pass"])."');";
if ($_GET["do_w"]=='sells') echo "sells('".addslashes($chars["sales"])."','".addslashes($chars["transfers"])."');";

if (@$_POST["d_num"] and $d==1)
{
	$dk = abs(intval($_POST["d_num"]));
	echo 'show_message("Вы точно хотите продать <b>'.$dk.' y.e.</b> для '.$pers["user"].'?<form method=post name=confirm><input type=hidden name=v_num value='.$dk.'><br><input type=button value=Да class=laar name=yes onclick=form_yes()></form><form method=post><input type=submit value=Нет class=laar></form>");';
}
if (@$_POST["v_num"] and $d==1)
{
	$dk = abs(intval($_POST["v_num"]));
	mysql_query("UPDATE configs SET dollars=dollars+".$dk);
	mysql_query("UPDATE configs SET dialers=CONCAT('<u>".$_SESSION["user"]."</u> >> <i>".$pers["user"]."</i> <b>".$dk." y.e.</b><br>',dialers)");
	mysql_query("UPDATE users SET dmoney=dmoney+".$dk." WHERE uid=".$pers["uid"]."");
	echo 'show_message("Вы продали <b>'.$dk.' y.e.</b> для '.$pers["user"].'");';
	if ($pers["referal_nick"])
	say_to_chat ("s","Вы привели в игру персонажа <font class=user onclick=\"top.say_private(\'".$pers["user"]."\')\">".$pers["user"]."</font> и он купил ".$dk."y.e. у дилера. Вам на счёт зачислено ".round($dk*0.03)." y.e.",1,$pers["referal_nick"],'*',0);
	say_to_chat('s','Дилер <b>'.$_SESSION["user"].'</b> продал вам <b>'.$dk.' y.e.</b>',1,$pers["user"],'',0); 
	sql("UPDATE users SET dmoney=dmoney+".round($dk*0.03)." WHERE uid=".$pers["referal_uid"]);
}
?>
function form_yes()
{
	document.confirm.submit();
	document.confirm.yes.disabled = true;
}
</script>