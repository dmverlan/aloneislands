<?
$lts = date("H:i");

$zakname = '';
$fight= sqla("SELECT * FROM `fights` WHERE `id`='".$pers["cfight"]."' ");
if ($pers["pol"]=='female') $male='а';
if ($male=='а') {
$podoshel = '//ла';
$otoshel = '\\ла';
$pitalsa = '**ась';
$ypersa = 'уперлась';
}else{
$podoshel = '//ёл';
$otoshel = '\\ёл';
$pitalsa = '**ся';
$ypersa = 'уперся';
}
if ($persvs["pol"]=='female')
 {
	$pogib = '#ла';
	$malevs='а';
	$yvvs = '*:ась';
 }
else
 {
	$pogib = '#';
	$malevs='';
	$yvvs = '*:ся';
 }
$magic=0;
 if ($_POST["p"])
 {
	if ($_POST["ug"])$point="ug";
	$_POST[$point] = 'magic';
	$_POST[$point."p"] = $_POST["p"];
	if ($_POST["uj"])$point="uj";
	$_POST[$point] = 'magic';
	$_POST[$point."p"] = $_POST["p"];
	if ($_POST["un"])$point="un";
	$_POST[$point] = 'magic';
	$_POST[$point."p"] = $_POST["p"];
	$magic=1;
 }
 if ($_POST["ap"])
 {
	//if ($_POST["enemy"]==0)
	$_POST["attp"] = $_POST["ap"];
 }
$kl=0;
$fall = '';$xod='';
/////////////////////////////////////////////////////////////////////////////////////////////////
include ("fights/od_counter.php");
if ($r>round ($pers["sm5"]/3 + 3 + $pers["od_b"])) echo "Превышение очков действия. Если вы всё делаете правильно, но ошибка повторяется советуем нажать F5 или очистить кеш браузера.";
if ($r<=round ($pers["sm5"]/3 + 3 + $pers["od_b"])) 
 {
	if (substr_count ("|".$fight["name"] , "|".USER_NICK."|")) 
	 {
		$color="green";
		$vscolor="blue";
	 } 
	else 
	 {
		$color="blue";
		$vscolor="green";
	 }
#################################################################################	 
$temp1=explode ("|",$fight["name"]);
$temp2=explode ("|",$fight["namevs"]);
if ($fight["bplace"])$bplace = sqla("SELECT * FROM battle_places WHERE id=".$fight["bplace"]);
$go_no_p = '|';
foreach ($temp1 as $tmp)
 { 
	if (strpos(" |".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[8];
		$tmp["cma"]=$tmp["ma"];
	}
	  else
		$tmp = sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma FROM `users` 
		WHERE `user`='".$tmp."'");
	if (floor($tmp["chp"])>0)
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
 }
 
foreach ($temp2 as $tmp)
 { 
	if (strpos(" ".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp,ma FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[1];
		$tmp["cma"]=$tmp["ma"];
	 }
	  else
		$tmp=sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma FROM `users` WHERE `user`='".$tmp."'");
	if (floor($tmp["chp"])>0)
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
 }
#################################################################################	 

if (!$persvs["bot"])$can = sqla("SELECT * FROM turns_f WHERE uid2='".$pers["uid"]."' and uid1=".$persvs["uid"]."");

if ($can["idf"]) $go=1; else $go=0;
if ($persvs["bot"]) $go=1;
if ($_GET["gotox"] and $go==0) sqla("INSERT INTO `turns_f` ( `idf` , `uid1` , `uid2` , `turn` ) 
VALUES (".$pers["cfight"].", ".$pers["uid"].", ".$persvs["uid"].", 'gotox=".$_GET["gotox"].";gotoy=".$_GET["gotoy"].";');");
if ($_POST["vs"] and $go==0)
{
	$str='';
	foreach ($_POST as $key => $v) $str.=$key."=".$v.";";
	sqla("INSERT INTO `turns_f` ( `idf` , `uid1` , `uid2` , `turn` ) 
VALUES (".$pers["cfight"].", ".$pers["uid"].", ".$persvs["uid"].", '".$str."');");
}

if ($go==1) 
{
	$arr = explode(";",$can["turn"]);
	foreach($arr as $a)
	{
	if ($a<>'')
	{
	$z = explode("=",$a);
	$req[$z[0]]=$z[1];
	}
	}
}

(isset($_POST["vs"])||$_GET["gotox"])?$action=1 : 0;

$pers["bg"] = $_POST["bg"];
$pers["bj"] = $_POST["bj"];
$pers["bn"] = $_POST["bn"];
$persvs["bg"] = $req["bg"];
$persvs["bj"] = $req["bj"];
$persvs["bn"] = $req["bn"];

if ($_GET["gotox"] and $go) 
 {
	$kl=1;
	$k=1;
	$xod = 1;
	$shagi=round(sqrt(sqr($pers["xf"]-$_GET["gotox"])+sqr($pers["yf"]-$_GET["gotoy"])));
	$sha = round (1+$pers["sm4"]/30);
	if ($shagi<=$sha and $_GET["gotox"]>0 and $_GET["gotox"]<15 and $_GET["gotoy"]>=0 and $_GET["gotoy"]<5 and !substr_count($bplace["xy"],"|".$_GET["gotox"]."_".$_GET["gotoy"]."|"))
	{
		if ($shagi == 1) $shagi = $shagi." шаг";
		if ($shagi > 1 and $shagi<5) $shagi = $shagi." шага";
		if ($shagi > 4 or $shagi==0) $shagi = $shagi." шагов";
		//$s="<font class=".$color."> ".$pers["user"]."$ ".$otoshel." на ".$shagi.""; 
	$fall=$s.$fall;
	$go_no_p = str_replace("|".$pers["xf"]."_".$pers["yf"]."|"
	,"|".intval($_GET["gotox"])."_".intval($_GET["gotoy"])."|",$go_no_p);
	$pers["xf"] = intval($_GET["gotox"]);
	$pers["yf"] = intval($_GET["gotoy"]);
	 sql ("UPDATE `users` 
	 SET `xf`='".$pers["xf"]."' ,`yf`=".$pers["yf"].", `bg`=0 , `bj`=0 , `bn`=0 WHERE `uid`='".$pers["uid"]."' ;");
	}
 }
 
if ($req["gotox"]) 
 {
	$kl=1;
	$k=1;
	$xod = 1;
	$shagi=round(sqrt(sqr($persvs["xf"]-$req["gotox"])+sqr($persvs["yf"]-$req["gotoy"])));
	$sha = round (1+$persvs["sm4"]/30);
	if ($shagi<=$sha and $req["gotox"]>0 and $req["gotox"]<15 and $req["gotoy"]>=0 and $req["gotoy"]<5 and !substr_count($bplace["xy"],"|".$req["gotox"]."_".$req["gotoy"]."|"))
	{
		if ($shagi == 1) $shagi = $shagi." шаг";
		if ($shagi > 1 and $shagi<5) $shagi = $shagi." шага";
		if ($shagi > 4 or $shagi==0) $shagi = $shagi." шагов";
		//$s="<font class=".$color."> ".$pers["user"]."$ ".$otoshel." на ".$shagi.""; 
	$fall=$s.$fall;
	$go_no_p = str_replace("|".$persvs["xf"]."_".$persvs["yf"]."|"
	,"|".intval($req["gotox"])."_".intval($req["gotoy"])."|",$go_no_p);
	$persvs["xf"] = intval($req["gotox"]);
	$persvs["yf"] = intval($req["gotoy"]);
	 sql ("UPDATE `users` 
	 SET `xf`='".$persvs["xf"]."' ,`yf`=".$persvs["yf"].", `bg`=0 , `bj`=0 , `bn`=0 WHERE `uid`='".$can["uid1"]."' ;");
	}
 }
 
 if ($persvs["bot"] and $action)
 {
 $kl=0;
 $text='';
 $die = '';
  $perstemp = $pers;
 $pers = $persvs;
 $persvs = $perstemp;
	$r = round(sqrt(sqr($pers["xf"]-$persvs["xf"])+sqr($pers["yf"]-$persvs["yf"])));
if ($r>1) 
 {
	
		$shagi = round (1+$pers["speed"]/10);
		if ($shagi>$r) $shagi=$r;
		$l=90;
		for ($i=$pers["xf"]-3;$i<=$pers["xf"]+3;$i++)
		for ($j=0;$j<=4;$j++)
		{
		if (!substr_count("|".$bplace["xy"]."|".$go_no_p,"|".$i."_".$j."|")
		and round(sqrt(sqr($persvs["xf"]-$i)+sqr($persvs["yf"]-$j)))<=$l 
		and round(sqrt(sqr($pers["xf"]-$i)+sqr($pers["yf"]-$j)))<=$shagi) 
		{$xtemp = $i;$ytemp = $j;$l=round(sqrt(sqr($persvs["xf"]-$i)+sqr($persvs["yf"]-$j)));}
		}
		if ($l<90) 
		{	
			$go_no_p = str_replace("|".$pers["xf"]."_".$pers["yf"]."|"
			,"|".$xtemp."_".$ytemp."|",$go_no_p);
			$pers["xf"]=$xtemp;
			$pers["yf"]=$ytemp;
		}
	$fall = $s.$fall;
	$botlib = $pers['botlib'];
	$p = explode ("=",$botlib);
	$p[4] = $pers["xf"]."_".$pers["yf"];
	$botlibnew = implode ("=",$p);
	$pers["botlib"]=$botlibnew;
	if (strpos(" ".$fight["name"],$botlib)>0) 
	 $fight["name"] = str_replace ($botlib."|",$botlibnew."|",$fight["name"]);
	if (strpos(" ".$fight["namevs"],$botlib)>0)
	 $fight["namevs"] = str_replace ($botlib."|",$botlibnew."|",$fight["namevs"]);
	sql ("UPDATE `fights` SET 	 `ltime`=".time()." ,
	`name`='".$fight["name"]."'  , `namevs`='".$fight["namevs"]."' WHERE `id`='".$fight["id"]."' ;");
	$kl=1;
 }

if ($r<=1 and $kl==0) {
$od = $pers["level"]+20;
include ("bots/chooser.php");
$ptemp = $_POST;
$_POST = $botU;
$tempcolor = $color;
$color = $vscolor;
$vscolor=$tempcolor;
 if ($persvs["chp"]>0) $text .= bot_udar ("ug");
 if ($persvs["chp"]>0) $text .= bot_udar ("uj");
 if ($persvs["chp"]>0) $text .= bot_udar ("un");
$tempcolor = $color;
$color = $vscolor;
$vscolor=$tempcolor;
$_POST = $ptemp;
if (substr_count($persvs["aura"],"invisible"))
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[Невидимка[??] ??/??]$<br>";}
else
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[".$persvs["user"]."[".$persvs["level"]."] ".$persvs["chp"]."/".$persvs["hp"]."]$<br>";}

 }
 $fall = $die.$fall.";";
 $perstemp = $pers;
 $pers = $persvs;
 $persvs = $perstemp;
 }
 
#################################################################################	 
 $text = '';
 $die='';
 if (@$_POST and $go)
 {
	$radius = sqla("SELECT MAX(radius) FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and type='orujie'");
	$radius = $radius[0];
	if ($radius<2)$radius=2;
	if ($pers["fstate"]==2 and ARROWS>0 or $pers["fstate"]<>2)
if ($radius>=(sqrt(sqr($pers["xf"]-$persvs["xf"])+sqr($pers["yf"]-$persvs["yf"]))) or $magic)
{
 $pers["damage_give"]=0;
 $pers["damage_get"]=$pers["chp"];
 $PVS_NICK = $persvs["user"];
 $USER_NICK = $pers["user"];
 if ($persvs["chp"]>0) $text .= human_udar ("ug");
 if ($persvs["chp"]>0) $text .= human_udar ("uj");
 if ($persvs["chp"]>0) $text .= human_udar ("un");
 set_vars("damage_give=".$pers["damage_give"].",damage_get=".$pers["damage_get"],$pers["uid"]);
}
 }
if (substr_count($persvs["aura"],"invisible"))
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[Невидимка[??] ??/??]$<br>";}
else
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[".$persvs["user"]."[".$persvs["level"]."] ".$persvs["chp"]."/".$persvs["hp"]."]$<br>";}
 $_POST = $req;
 $fall = $die.$fall.";";
 $die = '';
 $text='';
 $perstemp = $pers;
 $pers = $persvs;
 $persvs = $perstemp;
 $tempcolor = $color;
$color = $vscolor;
$vscolor=$tempcolor;
  if (@$_POST)
 {
	$radius = sqla("SELECT MAX(radius) FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and type='orujie'");
	$radius = $radius[0];
	if ($radius<2)$radius=2;
	if ($pers["fstate"]==2 and ARROWS>0 or $pers["fstate"]<>2)
if ($radius>=(sqrt(sqr($pers["xf"]-$persvs["xf"])+sqr($pers["yf"]-$persvs["yf"]))) or $magic)
{
 $pers["damage_give"]=0;
 $pers["damage_get"]=$pers["chp"];
 $PVS_NICK = $persvs["user"];
 $USER_NICK = $pers["user"];
 if ($persvs["chp"]>0) $text .= human_udar ("ug");
 if ($persvs["chp"]>0) $text .= human_udar ("uj");
 if ($persvs["chp"]>0) $text .= human_udar ("un");
 set_vars("damage_give=".$pers["damage_give"].",damage_get=".$pers["damage_get"],$pers["uid"]);
}
 }
if (substr_count($persvs["aura"],"invisible"))
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[Невидимка[??] ??/??]$<br>";}
else
{if ($text) $fall .= "<font class=time>".date("H:i")."</font> ".$text."<font class=time>[".$persvs["user"]."[".$persvs["level"]."] ".$persvs["chp"]."/".$persvs["hp"]."]$<br>";}

$fall = $fall.";";
$fall = $die.$fall.";";
 $persvs = $pers;
 $pers = $perstemp;
 $tempcolor = $color;
$color = $vscolor;
$vscolor=$tempcolor;

#################################################################################	 

///////////////////////////////////////////////////////////////////////////////////////////////////Сохраняем всё в базе данных
if (!empty($_POST['attp']) and strpos(" ".$pers['zakl'],$_POST['attp'])>0) {
$z = do_magic($_POST['attp'],$pers,$persvs);
 if (substr_count($pers["aura"],"invisible"))$pers["user"] = '<i>невидимка</i>';
 if (substr_count($persvs["aura"],"invisible"))$persvs["user"] = '<i>невидимка</i>';
if (@$z["name"] and count($z)>2) 
 {
 if (empty($persvs["botlib"]))
 $s=" <font class=".$color.">".$pers["user"]."</font> накладывает «<font class=user>".$z['name']."</font>» на <font class=user>".$persvs['user']."</font>"; 
 else 
 $s=" <font class=".$color.">".$pers["user"]."</font> ".$pitalsa." наложить «<font class=user>".$z['name']."</font>» на <font class=user>".$persvs['user']."</font> ,но магические ауры не действуют на существ"; 
 }else $s=" Персонажу <font class=".$color.">".$pers["user"]."</font> не хватает маны чтобы наложить «<font class=user>".$z[0]."</font>» на <font class=user>".$persvs['user']."</font>, или это заклинание уже действует";
$fall=$s.$fall;
$kl = 1;
}
#################################################################################	 
$zakname = '';
if (!empty($_POST['bggp']) and strpos(" ".$pers['zakl'],$_POST['bggp'])>0) {
$persto = catch_user($_SESSION["uid"]);
$z = do_magic($_POST['bggp'],$persto,$persto);
if (@$z["name"] and count($z)>2) 
 {
	$s=" <font class=".$color.">".$pers["user"]."</font> накладывает на себя «<font class=user>".$z['name']."</font>»"; 
 }else $s=" Персонажу <font class=".$color.">".$pers["user"]."</font> не хватает маны чтобы наложить на себя «<font class=user>".$z[0]."</font>», или это заклинание уже действует";
$fall=$s.$fall;
$kl = 1;
}
#################################################################################	 
if ($kl<>0) {
$fight["ltime"]=time();
if ($fall) $fight["all"]=$fall.$fight["all"];
sql ("UPDATE `fights` 
SET `turn`='".$fight["turn"]."' , `all`='".addslashes($fight["all"])."' , `ltime`='".time()."' 
WHERE `id`='".$fight["id"]."' ;");

sql ("UPDATE `users` SET  f_turn='".(++$pers["f_turn"])."'   WHERE `uid`='".$pers["uid"]."';");
sql ("UPDATE `users` SET `refr`=1 WHERE `uid`='".$persvs["uid"]."';");
}
#################################################################################	 
}

if ($can["idf"] and $action) sql("DELETE FROM turns_f WHERE uid1=".$can["uid1"]." and uid2=".$can["uid2"]."");

#################################################################################	 
$temp1=explode ("|",$fight["name"]);
$temp2=explode ("|",$fight["namevs"]);
if ($fight["bplace"])$bplace = sqla("SELECT * FROM battle_places WHERE id=".$fight["bplace"]);
$go_no_p = '|';
foreach ($temp1 as $tmp)
 { 
	if (strpos(" |".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[8];
		$tmp["cma"]=$tmp["ma"];
	}
	  else
		$tmp = sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma FROM `users` 
		WHERE `user`='".$tmp."'");
	if (floor($tmp["chp"])>0)
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
 }
 
foreach ($temp2 as $tmp)
 { 
	if (strpos(" ".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp,ma FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[1];
		$tmp["cma"]=$tmp["ma"];
	 }
	  else
		$tmp=sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma FROM `users` WHERE `user`='".$tmp."'");
	if (floor($tmp["chp"])>0)
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
 }
#################################################################################	 
?>