<?
	#### Считаем ОД на удар
	$_W = Weared_Weapons($pers["uid"]);
	$OD_UDAR = $_W["OD"];
	
	$fight = sqla("SELECT * FROM `fights` WHERE `id`='".$pers["cfight"]."'");
	if (!$fight["id"]) set_vars("cfight=0,curstate=0,refr=1",$pers["uid"]);
	if ($fight["bplace"])$bplace = sqla("SELECT * FROM battle_places WHERE id=".$fight["bplace"]);
	if ($fight['type']<>'f')include ("fights/ch_p_vs.php");
	$delta = floor(sqrt(sqr($pers["xf"]-$persvs["xf"])+sqr($pers["yf"]-$persvs["yf"]))); // Расстояние между игроками
	############@@@@@@@
	###### ПОВТОР
	if($_GET["repeat"] and $pers["turn_before"])
	{
		$arr = explode(";",$pers["turn_before"]);
		foreach($arr as $a)
		 if ($a<>'')
		  {
			$z = explode("=",$a);
			if($z[0]!="vs")
				$_POST[$z[0]]=$z[1];
		  }
		  $_POST["vs"] = $persvs["uid"] + $persvs["id"]; 
	}
	####################
	########@@@@@@@@@@@@@@@@@@@@
	include ("fights/constants.php");
	include ("fights/od_counter.php");
	////////////////////////////// Тип боя:
	if (($_GET["fstate"]==1 or $pers["fstate"]==0) and $pers["fstate"]<>1 and $pers["fstate"]=1) 
	set_vars("fstate=1",$pers["uid"]);
	if ($_GET["fstate"]==2 and $pers["fstate"]<>2 and $pers["fstate"]=2) 
	set_vars("fstate=2",$pers["uid"]);
	if ($_GET["fstate"]==3 and $pers["fstate"]<>3 and $pers["fstate"]=3) 
	set_vars("fstate=3",$pers["uid"]);
	if ($_GET["fstate"]==4 and $pers["fstate"]<>4 and $pers["fstate"]=4) 
	set_vars("fstate=4",$pers["uid"]);
	
	if ($r != $OD_UDAR+3)
	{
	if (($pers["sb1"]+5)<$r or !$persvs["chp"]) 
	 {
	  unset($_POST);
	 }
	}
	//////////////////////////////
	#####Лечимся за счёт маны
	if (@$_GET["up_health"] and 
$pers["hp"]<>$pers["chp"] and 
(($pers["hp"]-$pers["chp"])<=$pers["cma"]) and $pers["chp"] and $persvs)
{
		set_vars("cma=cma-hp+chp,chp=hp",$pers["uid"]);
		if ($pers["invisible"]<=tme())
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
		else 
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";
		add_flog($nvs." восстанавливает <font class=hp>".($pers["hp"] - $pers["chp"])." HP</font> за счёт маны.",$pers["cfight"]);
		$pers["cma"] = $pers["cma"] - $pers["hp"] + $pers["chp"];
		$pers["chp"] = $pers["hp"];
}

	if (@$_POST["attack"] and @$_POST["defence"])
	{
		$pers["fight_request"] = intval($_POST["attack"]).":".intval($_POST["defence"]).":".intval($zid).":".intval($_POST["magic_koef"]);
		set_vars("fight_request='".$pers["fight_request"]."'");
	}
	
if ($persvs["uid"])
	$can = sqla("SELECT * FROM turns_f WHERE uid2=".$pers["uid"]." and uid1=".$persvs["uid"]."");
	// ^ Если противник человек , то загружаем его действия в данный ход против нас. $go - показывает, можно ходить (1) или нельзя если противник не сходил против нас(0).
unset($go);
if (!$persvs["uid"] or $can["idf"]) $go = 1; else $go = 0;

#############################################Задаём запрос для повтора
if(@$_POST["vs"] and !$persvs["uid"])
{
	foreach ($_POST as $key => $v)
		$str.=$key."=".$v.";";
	set_vars("turn_before='".$str."'",$pers["uid"]);
}

if ($_STUN) 
{
	$_GET["gotox"] = $pers["xf"];
	$_GET["gotoy"] = $pers["yf"];
}
// Хождение:: Если противник не сходил против нас, и мы перемещаемся по карте то добавляем наши действия в базу:
if (@$_GET["gotox"] and $go == 0) 
sqla("INSERT INTO `turns_f` ( `idf` , `uid1` , `uid2` , `turn` ) 
VALUES (".$pers["cfight"].", ".$pers["uid"].", ".$persvs["uid"].", 'gotox=".intval($_GET["gotox"]).";gotoy=".intval($_GET["gotoy"]).";');");
########################################################################
// Удар:: Если соперник не сходил против нас, то добавляем наше действие в базу против него::
if (@$_POST["vs"] and $go==0)
{
	$str='';
	foreach ($_POST as $key => $v)
		$str.=$key."=".$v.";";
	set_vars("turn_before='".$str."'",$pers["uid"]);
	sqla("INSERT INTO `turns_f` ( `idf` , `uid1` , `uid2` , `turn` ) 
VALUES (".$pers["cfight"].", ".$pers["uid"].", ".$persvs["uid"].", '".$str."');");
	unset($persvs);
}elseif (@$_POST["vs"] and !$persvs["uid"])
{
	set_vars("bg=".intval($_POST["bg"]).",bt=".intval($_POST["bt"]).",bj=".intval($_POST["bj"]).",bn=".intval($_POST["bn"])."",$pers["uid"]);
	$pers["bg"] = $_POST["bg"];
	$pers["bt"] = $_POST["bt"];
	$pers["bj"] = $_POST["bj"];
	$pers["bn"] = $_POST["bn"];
}
#########################################################################
// Если противник сходил против нас, формируем массив значений его хода::
if ($go==1 and $persvs["uid"]) 
{
	$req = array();
	$arr = explode(";",$can["turn"]);
	foreach($arr as $a)
	 if ($a<>'')
	  {
		$z = explode("=",$a);
		$req[$z[0]]=$z[1];
	  }
}
if($go == 0)
{
	unset($_GET);
	unset($_POST);
}
#########################################################################
// Узнаём делаем ли мы какое-либо действие, $action = 1 - значит действие совершается.
if (isset($_POST["vs"])||isset($_GET["gotox"])) $action=1; else $action=0;
##############
// Блокирование ударов::

#############

#@##@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#
if ($action) # Основной блок действий
{
#>> Переход собственный
if (@$_GET["gotox"] and $go) 
 {
	$shagi=round(sqrt(sqr($pers["xf"]-$_GET["gotox"])+sqr($pers["yf"]-$_GET["gotoy"])));
	if ($shagi<=floor (1) and $_GET["gotox"]>0 and $_GET["gotox"]<$fight["maxx"] and $_GET["gotoy"]>=0 and $_GET["gotoy"]<$fight["maxy"] and !substr_count($bplace["xy"],"|".$_GET["gotox"]."_".$_GET["gotoy"]."|"))
	{
		$check_for_go = sqla("SELECT uid FROM users WHERE cfight=".$pers["cfight"]." and xf=".intval($_GET["gotox"])." and yf=".intval($_GET["gotoy"])." and chp>0");
		if (!$check_for_go[0])
		{
		$check_for_go = sqla("SELECT id FROM bots_battle WHERE cfight=".$pers["cfight"]." and xf=".intval($_GET["gotox"])." and yf=".intval($_GET["gotoy"])." and chp>0");
		if (!$check_for_go[0])
		{
			$pers["xf"] = intval($_GET["gotox"]);
			$pers["yf"] = intval($_GET["gotoy"]);
			set_vars("bg=0,bt=0,bj=0,bn=0,`xf`='".$pers["xf"]."' ,`yf`=".$pers["yf"]."",$pers["uid"]);
		}
		}
	}
 }

#####<<<<< ###
if (@$req["gotox"]) 
 {
	$shagi=round(sqrt(sqr($persvs["xf"]-$req["gotox"])+sqr($persvs["yf"]-$req["gotoy"])));
	if ($shagi<=floor (1) and $req["gotox"]>0 and $req["gotox"]<$fight["maxx"] and $req["gotoy"]>=0 and $req["gotoy"]<$fight["maxy"] and !substr_count($bplace["xy"],"|".$req["gotox"]."_".$req["gotoy"]."|"))
	{
		$check_for_go = sqla("SELECT uid FROM users WHERE cfight=".$pers["cfight"]." and xf=".intval($req["gotox"])." and yf=".intval($req["gotoy"])."");
		if (!$check_for_go[0])
		{
		$check_for_go = sqla("SELECT id FROM bots_battle WHERE cfight=".$pers["cfight"]." and xf=".intval($req["gotox"])." and yf=".intval($req["gotoy"])."");
		if (!$check_for_go[0])
		{
			$persvs["xf"] = intval($req["gotox"]);
			$persvs["yf"] = intval($req["gotoy"]);
			set_vars("bg=0,bt=0,bj=0,bn=0,`xf`='".$persvs["xf"]."' ,`yf`=".$persvs["yf"]."",$persvs["uid"]);
		}
		}
	}
 }
###>> Включаем бота, если надо::
 $text='';
 $die = '';
if ($fight["bplace"]==0) 
{
	$pers["xf"] = $persvs["xf"];
	$pers["yf"] = $persvs["yf"];
}
if (!$persvs["uid"] and $persvs["chp"]) include("bots/bot_brain.php");
########################################################
############ - Тесный контакт людей - ############################
$Checker = 0;
if ($req["vs"] and $persvs["uid"] and $r and $persvs["chp"] and $pers["chp"]) $Checker = 1;
if (!$persvs["uid"] and $persvs["chp"]) $Checker = 1;

 if ($Checker and $persvs["uid"])
 {
 	$radius = sqlr("SELECT MAX(radius) FROM wp WHERE uidp=".$persvs["uid"]." and weared=1 and type='orujie'",0);
	if ($radius<1)$radius=1;
if ($radius>=($delta) or $req["magic"] or !$fight["bplace"] or $req["aura_id"])
{
	if (!$fight["bplace"]) $delta=1;
##
	if ($persvs["invisible"]<=tme())
	$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>[".$persvs["level"]."]";
	else 
	$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]]."><i>невидимка</i></font>[??]";
##
 $persvs["damage_give"]=0;
 set_vars("damage_get=chp",$persvs["uid"]);
 set_vars("bg=".intval($_POST["bg"]).",bt=".intval($_POST["bt"]).",bj=".intval($_POST["bj"]).",bn=".intval($_POST["bn"])."",$pers["uid"]);
 $pers["bg"] = $_POST["bg"];
 $pers["bt"] = $_POST["bt"];
 $pers["bj"] = $_POST["bj"];
 $pers["bn"] = $_POST["bn"];
  $text .= human_udar ("ug",$persvs,$pers,$req,1,$delta);
  $text .= human_udar ("ut",$persvs,$pers,$req,1,$delta);
  $text .= human_udar ("uj",$persvs,$pers,$req,1,$delta);
  $text .= human_udar ("un",$persvs,$pers,$req,1,$delta);
 $text = substr($text,0,strlen($text)-1).'%';
 if (@$req["aura_id"])
 {
	$aid = intval($req["aura_id"]);
	$pers = $persvs;
	include ("battle_aura.php");
	$pers = catch_user(UID);
 }
  if ($text<>'') $text = $text."%";
}
 }
	## Наш удар::
 if ($Checker)
 {
	$radius = sqlr("SELECT MAX(radius) FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and type='orujie'",0);
	if ($radius<1)$radius=1;
if ($radius>=($delta) or $_POST["magic"] or !$fight["bplace"] or $_POST["aura_id"])
{
	if (!$fight["bplace"]) $delta=1;
##
	if ($pers["invisible"]<=tme())
	$nvs = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
	else 
	$nvs = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";
##
 $pers["damage_give"]=0;
 $pers["damage_get"]=$pers["chp"];
 set_vars("bg=".intval($req["bg"]).",bt=".intval($req["bt"]).",bj=".intval($req["bj"]).",bn=".intval($req["bn"])."",$persvs["uid"]);
 $persvs["bg"] = $req["bg"];
 $persvs["bt"] = $req["bt"];
 $persvs["bj"] = $req["bj"];
 $persvs["bn"] = $req["bn"];
  $text .= human_udar ("ug",$pers,$persvs,$_POST,0,$delta);
  $text .= human_udar ("ut",$pers,$persvs,$_POST,0,$delta);
  $text .= human_udar ("uj",$pers,$persvs,$_POST,0,$delta);
  $text .= human_udar ("un",$pers,$persvs,$_POST,0,$delta);
 $text = substr($text,0,strlen($text)-1).'%';
 set_vars("damage_get=".$pers["damage_get"]."",$pers["uid"]);
 if (@$_POST["aura_id"])
 {
	$aid = intval($_POST["aura_id"]);
	include ("battle_aura.php");
	$pers = catch_user(UID);
 }
}
 }
 if ($die.$text)
 {
	add_flog($die.$text,$pers["cfight"]); 
	echo "<script>top.ch_refresh();</script>";
 }
 
 #############@@@@@@@@@@@@@@@@@@######################
 
##############################################################
}
#@##@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#@#

		
// Действие выполнено с человеком:
if ($can["idf"] and $action) sql("DELETE FROM turns_f WHERE uid1=".$can["uid1"]." and uid2=".$can["uid2"]."");
if ($action and $can["idf"]) sqla("UPDATE users SET bg=0,bn=0,bj=0 WHERE uid=".intval($pers["uid"])." or uid=".intval($persvs["uid"])."");
//Итоговые преобразования:
if ($action) 
{
sql ("UPDATE `users` SET  f_turn='".(++$pers["f_turn"])."'   WHERE `uid`='".$pers["uid"]."';");
$fight["ltime"] = time();
sql ("UPDATE `fights` SET ltime=".$fight["ltime"]." WHERE `id`=".$pers["cfight"]."");
if ($persvs["uid"])
 sql ("UPDATE `users` SET `refr`=1 WHERE `uid`='".$persvs["uid"]."';");
}

if ($fight['type']<>'f' and $persvs["chp"]<1)include ("fights/ch_p_vs.php");
?>












