<?
if (@$_GET["use"] and $pers["cfight"]>10 and $pers["chp"])
{
	$v = sqla("SELECT `id`,`index`,durability FROM `wp` WHERE `id`=".intval($_GET["use"])."");
	$index = $v["index"];
	if ($v["durability"]>0)
	{
	if (substr_count($index,"hp$"))
	{
		$hp_value = intval(str_replace("hp$","",$index));
		if ($hp_value>abs($pers["hp"]-$pers["chp"]))$hp_value=abs($pers["hp"]-$pers["chp"]);
		if ($hp_value>0)
		{
		set_vars("chp=chp+".$hp_value,$pers["uid"]);
		$pers["chp"]+=$hp_value;
		if ($pers["invisible"]<tme())
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
		else 
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";
		add_flog($nvs." восстанавливает <font class=hp>".$hp_value." HP</font>.",$pers["cfight"]); 
		}
	}
	if (substr_count($index,"ma$"))
	{
		$ma_value = intval(str_replace("ma$","",$index));
		if ($ma_value>abs($pers["ma"]-$pers["cma"]))$ma_value=abs($pers["ma"]-$pers["cma"]);
		if ($ma_value>0)
		{
		set_vars("cma=cma+".$ma_value,$pers["uid"]);
		$pers["cma"]+=$ma_value;
		if ($pers["invisible"]<tme())
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
		else 
		$nvs = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";
		add_flog($nvs." восстанавливает <font class=ma>".$ma_value." MA</font>.",$pers["cfight"]); 
		}
	}
	sql("UPDATE wp SET durability=durability-1 WHERE id=".intval($_GET["use"])." and uidp='".$pers["uid"]."'");
	}
}


if (@$_POST["do"]=="wear")
{
	$chars = sqla("SELECT complects FROM chars WHERE uid='".$pers["uid"]."'");
	$cc = explode("@",$chars["complects"]);
	$cc = $cc[$_POST["c"]];
	remove_all_weapons ();
	$ids = explode(":",$cc);
	$ids = explode("|",$ids[1]);
	foreach($ids as $id)
		if ($id)dress_weapon($id,1);
	unset($chars);
}

// Одеваем вещь >>>>>>>>>>>>>>>>>>>>>>>>
if (!empty($_GET["wear"]) and $_GET["wear"]<>'none' and !$pers["cfight"] and !$pers["apss_id"] ) 
dress_weapon (intval($_GET["wear"]));

// Снимаем всё>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.
//////////////////////////////////
if (@$_GET["snall"]=="all" and !$pers["cfight"]) 
remove_all_weapons ();

// Снимаем что-то одно >>>>>>>>>>>>
///////////////////////////////
if (!empty($_GET["sn"]) and !$pers["cfight"]) 
remove_weapon ($_GET["sn"],0);
 


//Проверка  требований одежды

$wears = array();
for ($i=0;$i<18;$i++)
 {
	$m = array();
	$m["image"]='slots/pob'.($i+1);
	$m["id"]="0";
	$wears[$i]=$m;
 }
 
$sh = $wears[0];
$na = $wears[8];
$oj = $wears[1];
$pe = $wears[9];
$or1 = $wears[2];
$or2 = $wears[10];
$po = $wears[3];
$z1 = $wears[4];
$z2 = $wears[5];
$z3 = $wears[6];
$sa = $wears[7];
$ko1 = $wears[11];
$ko2 = $wears[12];
$br = $wears[13];
$kam1 = $wears[14];
$kam2 = $wears[15];
$kam3 = $wears[16];
$kam4 = $wears[17];
unset($wears);
unset($m);

$or1type = $or2type = "";

$ws1=0;
$ws2=0;
$ws3=0;
$ws4=0;
$ws5=0;
$ws6=0;


$res = sql("SELECT * FROM `wp` WHERE uidp='".$pers["uid"]."' and weared=1"); 
$j=0;
$tt = time();
$weared_count=0;
$UD_ART = 1;
while ($v=mysql_fetch_array($res,MYSQL_ASSOC)) 
{
$z=1;

if (($v["durability"]<1 and $v["max_durability"]>0) or ($v["timeout"]>0 and $v["timeout"]<$tt)) 
{
	remove_weapon ($v["id"],$v);
	$z=0;
}
if ($z and $pers["curstate"]<>4)
	foreach ($v as $key => $value) 
	{
		if ($key[0]=='t' and $key<>'timeout') 
		 if ($value>0 and $pers[substr($key,1,strlen($key)-1)]<$value)
		 {
		  $z =0;
		  remove_weapon ($v["id"],$v);
		  break;
		 }
	}

if ($z)
	{
		$ws1 += $v["s1"];
		$ws2 += $v["s2"];
		$ws3 += $v["s3"];
		$ws4 += $v["s4"];
		$ws5 += $v["s5"];
		$ws6 += $v["s6"];
		$dscr = $v["id"].'|';
		if ($v["name"]) $dscr .= '<b><i>'.str_replace(' ','&nbsp;',str_replace('"','*',$v["name"]))."</b></i>@";
		if ($v["tlevel"]) $dscr .= '<b class=dark>Уровень: '.$v["tlevel"]."</b>@";
		if ($v["clan_sign"]) $dscr .= 'Клан: <img src=images/signs/'.$v["clan_sign"].'.gif><b>'.$v["clan_name"].'</b>@';
		if ($v["price"]) $dscr .= '<b>'.$v["price"]." LN</b>@";
		if ($v["dprice"]) $dscr .= '<b>'.$v["dprice"]." Бр.</b>@";
		if ($v["dprice"]>100) $dscr .= "<font class=green>АРТЕФАКТ</font></i>@";
		if ($v["kb"]) $dscr .= 'Класс брони: <B>'.plus_param($v["kb"])."</B>@";
		if ($v["hp"]) $dscr .= 'Жизнь: <B>'.plus_param($v["hp"])."</B>@";
		if ($v["ma"]) $dscr .= 'Мана: <B>'.plus_param($v["ma"])."</B>@";
		if ($v["udmax"]+$v["udmin"]) $dscr .= 'Удар: <B>'.$v["udmin"]."-".$v["udmax"]."</B>@";
		if ($v["slots"]) $dscr .= 'Слотов: <B>'.$v["slots"]."</B>@";
		if ($v["radius"]) $dscr .= 'Радиус поражения: <B>'.$v["radius"]."</B>@";
		$dscr .= 'Долговечность:&nbsp;<B>'.$v["durability"]."/".$v["max_durability"]."</B>@";
	if ($v["type"]=="shlem" and $sh["image"]=$v["image"]) $sh["id"]=$dscr;
	if ($v["type"]=="ojerelie" and $oj["image"]=$v["image"]) $oj["id"]=$dscr;
	if ($v["type"]=="poyas" and $po["image"]=$v["image"]) $po["id"]=$dscr;
	if ($v["type"]=="sapogi" and $sa["image"]=$v["image"]) $sa["id"]=$dscr;
	if ($v["type"]=="naruchi" and $na["image"]=$v["image"]) $na["id"]=$dscr;
	if ($v["type"]=="perchatki" and $pe["image"]=$v["image"]) $pe["id"]=$dscr;
	if ($v["type"]=="bronya" and $br["image"]=$v["image"]) $br["id"]=$dscr;
	if ($v["type"]=="orujie" and $or1["id"]=="0" and $or1["image"]=$v["image"]){$or1["id"]=$dscr;$or1type=$v["stype"];}
	if ($v["type"]=="orujie" and $or2["id"]<>"0") remove_weapon($v["id"],$v);
	if ($v["stype"]=='book')
	{
		define("BOOK_ID",$v["id"]);
		define("BOOK_SLOTS",$v["slots"]);
		define("BOOK_INDEX",$v["index"]);
	}
	if ($v["type"]=="orujie" and ($or1["id"]<>$dscr)
	and $or2["image"]=$v["image"]){$or2["id"]=$dscr;$or2type=$v["stype"];}
	if ($v["type"]=="kolco" and $ko1["id"]=="0" and $ko1["image"]=$v["image"] and $_ko1=true)
	$ko1["id"]=$dscr;
	if ($v["type"]=="kolco" and ($ko1["id"]<>$dscr)
	and $ko2["image"]=$v["image"])	$ko2["id"]=$dscr;
	if ($v["type"]=="kam")
	{	
		for ($i=$j;$i<$j+1;$i++)
		if($i==0){$kam1["id"]=$dscr;$kam1["image"]=$v["image"];}
		elseif($i==1){$kam2["id"]=$dscr;$kam2["image"]=$v["image"];}
		elseif($i==2){$kam3["id"]=$dscr;$kam3["image"]=$v["image"];}
		elseif($i==3){$kam4["id"]=$dscr;$kam4["image"]=$v["image"];}
		$j++;
	}
	$weared_count++;
	if ($weared_count==1) {$weared_name=$v["name"];$weared_id=$v["id"];$weared_slots=$v["slots"];$weared_wp=$v;}
	//if ($v["dprice"]>100) $UD_ART += $v["dprice"]/5000;
	}
}

if ($or1type=='noji' or $or1type=='shit') 
{
	$tmp = $or1;
	$or1 = $or2;
	$or2 = $tmp;	
}

$ws1 = plus_param($ws1);
$ws2 = plus_param($ws2);
$ws3 = plus_param($ws3);
$ws4 = plus_param($ws4);
$ws5 = plus_param($ws5);
$ws6 = plus_param($ws6);

if ($UD_ART<>$pers["is_art"]) set_vars("is_art=".$UD_ART."",UID);
define ("UD_ART",$UD_ART);
unset($v);
mysql_free_result($res);
unset($res);

if ($t%10==0)
{
$all_weight = intval(sqlr("SELECT SUM(weight) as ww FROM `wp` WHERE uidp=".$pers["uid"]."  and in_bank=0"));
if ($all_weight<>$pers["weight_of_w"])
sql ("UPDATE `users` SET `weight_of_w`=".($all_weight)." WHERE `uid`='".$pers["uid"]."'");
}

// Вставляем руну>>>>>>>>>>>>>>>>>>>>>
///////////////////////////////
if (@$_GET["rune_join"])
{
	if ($weared_slots)
	{
	$rune = sqla("SELECT * FROM wp WHERE id=".intval($_GET["rune_join"])."");
	if ($pers["sp5"]>$rune["tsp5"])
	{
		remove_weapon ($weared_id,$weared_wp);
		$sk = explode("_",$rune["id_in_w"]);
		sql("UPDATE wp SET `".$sk[2]."`=`".$sk[2]."`+".$sk[1].",slots=slots-1,price=price+".sqrt($rune["price"]).",
		`name`='".$weared_name."[Р]' WHERE id=".$weared_id."");
		if ($sk[2]=="udmax")sql("UPDATE wp SET `udmin`=`udmin`+1 WHERE id=".$weared_id."");
		sql("DELETE FROM wp WHERE id=".intval($_GET["rune_join"])."");
		$_RETURN .= "Удачно вставлена \"".$rune["name"]."\" в \"".$weared_name."\"";
		dress_weapon($weared_id,1);
	}else
	$_RETURN .= "<font class=hp>Не хватает умения \"Кузнец\".</hp>";
	}else $_RETURN .= "Закончились слоты для рун.";
	unset($rune);
}


//rank_i
$rank_i = round(($pers["s1"]+$pers["s2"]+$pers["s3"]+$pers["s4"]+$pers["s5"]+$pers["s6"]+$pers["kb"])*0.3 + ($pers["mf1"]+$pers["mf2"]+$pers["mf3"]+$pers["mf4"])*0.03 + ($pers["hp"]+$pers["ma"])*0.04+($pers["udmin"]+$pers["udmax"])*0.3,2);

if ($rank_i<>round($pers["rank_i"],2))
{
	$pers["rank_i"]=$rank_i;
	set_vars("rank_i=".$pers["rank_i"]."",$pers["uid"]);
}
//
?>