<?
$zeroyed=0;
if (@$_GET["fz"] and $pers["level"]>=5) unset($_GET["fz"]);
if ($pers["action"]==-10) {$_GET["gopers"]="obnyl";}
if ($pers["action"]==-11) {$_GET["fz"]="obnyl";}
if (@$_GET["gopers"]=="obnyl" or @$_GET["fz"]) {include ('inc/inc/obnyl.php');unset($_GET["gopers"]);}

//завершаем бой
if ((@$_GET["eff"]==1 or $pers["gain_time"]>(tme()-1200)) and $pers["curstate"]==4) 
	$pers = end_battle($pers);


//Получение уровня
$level = sqla("SELECT * FROM `exp` WHERE `level`=".($pers["level"]+1));
$i = 0;
$free_stats = 0;
$free_f_skills = 0;
$free_p_skills = 0;
$free_m_skills = 0;
$levels = 0;
$money = 0;
$coins = 0;
while (($pers["exp"]+$pers["peace_exp"])>=$level["exp"] and $level["exp"]>0) {
$free_stats +=$level["stats"];
$free_f_skills+=$level["free_f_skills"];
$free_p_skills+=$level["free_p_skills"];
$free_m_skills+=$level["free_m_skills"];
$levels++;
if (!$zeroyed) $money+=$level["money"];
if (!$zeroyed) $coins+=$pers["level"]*2;
$level = sqla("SELECT * FROM `exp` WHERE `level`=".($level["level"]+1));
$i++;
}

if ($i>0)
{
$pers["level"]+=$levels;
$pers["free_stats"]+=$free_stats;
$pers["free_f_skills"]+=$free_f_skills;
$pers["free_p_skills"]+=$free_p_skills;
$pers["free_m_skills"]+=$free_m_skills;
$pers["money"]+=$money;
$pers["coins"]+=$coins;	
sql ("UPDATE `users` SET level=level+".$levels.", free_stats=free_stats+".$free_stats.",free_f_skills=free_f_skills + ".$free_f_skills.", free_m_skills=free_m_skills+".$free_m_skills.",money=money+".$money.",coins=coins+".$coins." WHERE `uid`='".$pers["uid"]."'");
}


if (!$zeroyed and $i>0)
{
if ($pers["invisible"]<tme())
	say_to_chat ("a","Персонаж <font class=user onclick=\"top.say_private(\'".$pers["user"]."\')\">".$pers["user"]."</font> достиг ".$pers["level"]." уровня! //035",0,'','*',0);
else
	say_to_chat ("a","Персонаж <i class=user>Невидимка</i> достиг ?? уровня! //035",0,'','*',0);
if($pers["instructor"])
{
	$pupil = sqla("SELECT * FROM users WHERE uid = ".$pers["instructor"]);
	say_to_chat ('^',"Ваш ученик <b>".$pers["user"]."</b>[".$pers["level"]."] достиг ".$pers["level"]." уровня!",1,$pupil["user"],'*',0);
}	
if($pers["level"]==10)
	set_vars("zeroing=zeroing+1",UID);
	
if ($pers["level"]==3 and $pers["referal_nick"])
{
	sql("UPDATE users SET referal_rcounter=referal_rcounter+1 WHERE uid=".$pers["referal_uid"]);
}
if($pers["level"]>=5 and $pers["instructor"])
{
	$pupil = sqla("SELECT * FROM users WHERE uid = ".$pers["instructor"]);
	say_to_chat ('^',"Вы закончили обучение <b>".$pers["user"]."</b>[".$pers["level"]."] и получаете 200 LN и 10 пергаментов, поздравляем!",1,$pupil["user"],'*',0);
	set_vars("money=money+200,coins=coins+10,good_pupils_count=good_pupils_count+1",$pupil["uid"]);
	if(($pupil["good_pupils_count"]+1)%5==0)
		set_vars("money=money+100",$pupil["uid"]);		
	say_to_chat ('^',"Вы закончили обучение, поздравляем!",1,$pers["user"],'*',0);
	sql("UPDATE users SET instructor=0 WHERE uid = ".$pers["uid"]);
}
if ($pers["level"]%5==0 and $pers["level"]<>0 and $pers["referal_nick"])
{
	sql("UPDATE users SET money=money+50 WHERE uid=".$pers["referal_uid"]."");
	say_to_chat ("s","Вы привели в игру персонажа <font class=user onclick=\"top.say_private(\'".$pers["user"]."\')\">".$pers["user"]."</font> и он достиг ".$pers["level"]." уровня! Вам на счёт зачислено 50 LN",1,$pers["referal_nick"],'*',0);
}
if ($pers["level"]==15 and $pers["referal_nick"])
{
	sql("UPDATE users SET money=money+200 WHERE uid=".$pers["referal_uid"]."");
	say_to_chat ("s","Вы привели в игру персонажа <font class=user onclick=\"top.say_private(\'".$pers["user"]."\')\">".$pers["user"]."</font> и он достиг 15 уровня! Вам на счёт зачислено 200 LN.",1,$pers["referal_nick"],'*',0);
}
}


//Получение Звания
if(@$_GET["eff"]==1)
{
$zvan = sqla("SELECT * FROM `zvanya` WHERE `id`='".($pers["zvan"]+1)."' ");
if ($zvan["cena"]<=($pers["victories"]-$pers["losses"]) and $zvan["cena"]<>0) {
$pers["zvan"]=$zvan["id"];
sql ("UPDATE `users` SET `zvan`=".$pers["zvan"]." WHERE `uid`=".$pers["uid"]." ;");
say_to_chat ("s","Вы получили новое звание: <b>".$zvan["name"]."</b>, поздравляем!",1,$pers["user"],'*',0);
}
}




if ($pers["curstate"]<>4 and $pers["cfight"]<5) 
 {
	if ($pers["level"]<6) {$pers["sm6"]+=60;$pers["sm7"]+=60;}
	sql ("UPDATE `users` SET ".hp_ma_up($pers["chp"],$pers["hp"],$pers["cma"],
	$pers["ma"],$pers["sm6"],$pers["sm7"],$lastom_old,$pers["tire"])." 
	WHERE `uid` =".$pers["uid"].";");
	if ($pers["level"]<6) {$pers["sm6"]-=60;$pers["sm7"]-=60;}
	$pers["chp"] = floor($hp);
	$pers["сma"] = floor($ma);
}
### Очень важно
	update_user(UID);

$p = $_POST;
if (@$p["hjkl"]) {
$summ = $p["nbs"] + $p["nss"];
for ($i=1;$i<15;$i++)$summ += $p["bs".$i];
for ($i=1;$i<8;$i++)$summ += $p["ss".$i];
$summ2 = $pers["free_f_skills"] + $pers["free_m_skills"];
for ($i=1;$i<15;$i++)$summ2 += $pers["sb".$i];
for ($i=1;$i<8;$i++)$summ2 += $pers["sm".$i];

if ($summ == $summ2 and $p["nbs"]>=0 and $p["nss"]>=0) {
$pers["hp"]+=($p["ss1"]-$pers["sm1"])*4;
$pers["ma"]+=($p["ss2"]-$pers["sm2"])*3;
$pers["sb1"] = $p["bs1"];
$pers["sb2"] = $p["bs2"];
$pers["sb3"] = $p["bs3"];
$pers["sb4"] = $p["bs4"];
$pers["sb5"] = $p["bs5"];
$pers["sb6"] = $p["bs6"];
$pers["sb7"] = $p["bs7"];
$pers["sb8"] = $p["bs8"];
$pers["sb9"] = $p["bs9"];
$pers["sb10"] = $p["bs10"];
$pers["sb11"] = $p["bs11"];
$pers["sb12"] = $p["bs12"];
$pers["sb13"] = $p["bs13"];
$pers["sb14"] = $p["bs14"];
$pers["sm1"] = $p["ss1"];
$pers["sm2"] = $p["ss2"];
$pers["sm3"] = $p["ss3"];
$pers["sm4"] = $p["ss4"];
$pers["sm5"] = $p["ss5"];
$pers["sm6"] = $p["ss6"];
$pers["sm7"] = $p["ss7"];
$pers["free_f_skills"] = $p["nbs"];
$pers["free_p_skills"] = $p["nms"];
$pers["free_m_skills"] = $p["nss"];
if(($pers["free_f_skills"]+$pers["free_p_skills"]+$pers["free_m_skills"])==0)
{
	$pers["chp"] = $pers["hp"];
	$pers["cma"] = $pers["ma"];
	echo "<script>location = 'main.php';</script>";
	sql("UPDATE p_auras SET esttime=0 
			WHERE uid=".$pers["uid"]." and special>2 and special<6 and esttime>".tme().";");
	say_to_chat('s',"<i><b>Вы полностью исцелены!</b></i>",1,$pers["user"],'*',0);		
}
set_vars(aq($pers),$pers["uid"]);
}
}

?>