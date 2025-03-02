<?
mod_st_start("Удар бота",0);

$pers["botlibnew"] = $pers["botlib"];
$zakname = '';
if ($pers["pol"]=='female') $male='а'; else $male = '';
if ($male=='а') {
$podoshel = '//ла';
$otoshel = '\\ла';
$pitalsa = '**ась';
}else{
$podoshel = '//ёл';
$otoshel = '\\ёл';
$pitalsa = '**ся';
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

$kl=0;
$fall = '';$s='';$xod='';
/////////////////////////////////////////////////////////////////////////////////////////////////ШАГ
if ($persvs["chp"]>0) {
	if (strpos (" |".$fight["name"] , "|".$pers["botlib"]."|")>0) 
	 {
		$color="green";
		$vscolor="blue";
	 } 
	else 
	 {
		$color="blue";
		$vscolor="green";
	 }

$r = round(sqrt(sqr($pers["x"]-$persvs["xf"])+sqr($pers["y"]-$persvs["yf"])));
if ($r>1) 
 {
	
		$shagi = round (1+$pers["speed"]/10);
		if ($shagi>$r) $shagi=$r;
		$l=90;
		for ($i=$pers["x"]-3;$i<=$pers["x"]+3;$i++)
		for ($j=0;$j<=4;$j++)
		{
		if (!substr_count("|".$bplace["xy"]."|".$go_no_p,"|".$i."_".$j."|")
		and round(sqrt(sqr($persvs["xf"]-$i)+sqr($persvs["yf"]-$j)))<=$l 
		and round(sqrt(sqr($pers["x"]-$i)+sqr($pers["y"]-$j)))<=$shagi) 
		{$xtemp = $i;$ytemp = $j;$l=round(sqrt(sqr($persvs["xf"]-$i)+sqr($persvs["yf"]-$j)));}
		}
		if ($l<90) 
		{	
			$go_no_p = str_replace("|".$pers["x"]."_".$pers["y"]."|"
			,"|".$xtemp."_".$ytemp."|",$go_no_p);
			$pers["x"]=$xtemp;
			$pers["y"]=$ytemp;
		}
	$fall = $s.$fall;
	$botlib = $pers['botlib'];
	$p = explode ("=",$botlib);
	$p[4] = $pers["x"]."_".$pers["y"];
	$botlibnew = implode ("=",$p);
	$pers["botlib"]=$botlibnew;
	if (strpos(" ".$fight["name"],$botlib)>0) 
	 $fight["name"] = str_replace ($botlib."|",$botlibnew."|",$fight["name"]);
	if (strpos(" ".$fight["namevs"],$botlib)>0)
	 $fight["namevs"] = str_replace ($botlib."|",$botlibnew."|",$fight["namevs"]);
	sql ("UPDATE `fights` SET `turn`='".str_replace($botlib."|","",$fight["turn"]).$botlibnew."|"."' ,
	 `ltime`=".time()." ,
	`name`='".$fight["name"]."'  , `namevs`='".$fight["namevs"]."' WHERE `id`='".$fight["id"]."' ;");
	$fight["turn"]=str_replace($botlib."|","",$fight["turn"]).$botlibnew."|";
	$kl=1;
 }
}

 $text = '';
 $die='';
if ($r<=1 and $kl==0) {
 if ($persvs["chp"]>0) $text .= bot_udar ("ug");
 //if ($persvs["chp"]>0) $text .= bot_udar ("ut");
 if ($persvs["chp"]>0) $text .= bot_udar ("uj");
 if ($persvs["chp"]>0) $text .= bot_udar ("un");
 $fall .= $text;
 }
 
///////////////////////////////////////////////////////////////////////////////////////////////////Сохраняем всё в базе данных
if (!empty($_POST['attp']) and strpos(" ".$pers['zakl'],$_POST['attp'])>0 and $_POST["attp"]<>'forv') {
$kl=1;
$zakl = $_POST['attp'];
$user = $pers["user"];
$tpers = $pers;
$pers = $persvs;
$fiz=1;
include ('aura.php');
$persvs = $pers;
$pers = $tpers;
if ($error=='') $s=" <font class=".$color.">".$pers["user"]."</font> накладывает <font class=user>".$zak['name']."</font> на <font class=".$vscolor.">".$persvs['user']."</font>."; else $s=" Персонажу <font class=".$color.">".$pers["user"]."</font> не хватает маны чтобы наложить <font class=user>".$zak['name']."</font> на <font class=".$vscolor.">".$persvs['user']."</font>, или это заклинание уже действует.";
if ($error=='') sql ("UPDATE `users` SET `cma`='".$pers["cma"]."' WHERE `uid`='".$pers["uid"]."'; ");
$fall=$s.";".$fall;
}


if ($kl<>0) 
 {
	$fight["ltime"]=time();
	$persvs["user"] = $PVS_NICK;
	if ($persvs["bot"]==0)
	$p = sqla("SELECT aura FROM users WHERE user='".$persvs["user"]."'");
	if (substr_count($p["aura"],"invisible") or $persvs["user"]=='')
	{
		$persvs["user"]='невидимка';
		$persvs["level"]='??';
		$persvs["chp"]='??';
		$persvs["hp"]='??';
	}
		
	if ($fall) $fight["all"]=$die."<font class=time>".date("H:i")."</font> ".$fall." [<font class=time>".$persvs["user"]." [".$persvs["level"]."] HP: ".$persvs["chp"]."/".$persvs["hp"]."</font>];".$fight["all"];
	sql ("UPDATE `fights` 
	SET `all`='".addslashes($fight["all"])."' , `ltime`='".time()."' 
	WHERE `id`='".$fight["id"]."' ;");
 }

mod_st_fin();
?>