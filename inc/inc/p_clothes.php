<?
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

$or1type = $or2type = "";

$ws1=0;
$ws2=0;
$ws3=0;
$ws4=0;
$ws5=0;
$ws6=0;

if ($pers["uid"])
$res = sql("SELECT * FROM `wp` WHERE uidp=".intval($pers["uid"])." and weared=1"); 
else
$res = sql("SELECT * FROM `wp` WHERE uidp=".intval(-1*$pers["bid"])." and weared=1");
$j=0;
while ($v=mysql_fetch_array($res)) {

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
		if ($_WT)
		{
		if ($v["price"]) $dscr .= '<b>'.$v["price"]." LN</b>@";
		if ($v["dprice"]) $dscr .= '<b>'.$v["dprice"]." Бр.</b>@";
		if ($v["dprice"]>100) $dscr .= "<font class=green>АРТЕФАКТ</font></i>@";
		}
		if ($v["kb"]) $dscr .= 'Класс брони: <B>'.plus_param($v["kb"])."</B>@";
		if ($v["hp"]) $dscr .= 'Жизнь: <B>'.plus_param($v["hp"])."</B>@";
		if ($v["ma"]) $dscr .= 'Мана: <B>'.plus_param($v["ma"])."</B>@";
		if ($v["udmax"]+$v["udmin"]) $dscr .= 'Удар: <B>'.$v["udmin"]."-".$v["udmax"]."</B>@";
		if ($v["slots"]) $dscr .= 'Слотов: <B>'.$v["slots"]."</B>@";
		if ($v["radius"]) $dscr .= 'Радиус поражения: <B>'.$v["radius"]."</B>@";
	if ($v["type"]=="shlem" and $sh["image"]=$v["image"]) $sh["id"]=$dscr;
	if ($v["type"]=="ojerelie" and $oj["image"]=$v["image"]) $oj["id"]=$dscr;
	if ($v["type"]=="poyas" and $po["image"]=$v["image"]) $po["id"]=$dscr;
	if ($v["type"]=="sapogi" and $sa["image"]=$v["image"]) $sa["id"]=$dscr;
	if ($v["type"]=="naruchi" and $na["image"]=$v["image"]) $na["id"]=$dscr;
	if ($v["type"]=="perchatki" and $pe["image"]=$v["image"]) $pe["id"]=$dscr;
	if ($v["type"]=="bronya" and $br["image"]=$v["image"]) $br["id"]=$dscr;
	if ($v["type"]=="orujie" and $or1["id"]=="0" and $or1["image"]=$v["image"]){$or1["id"]=$dscr;$or1type=$v["stype"];}
	if ($v["type"]=="orujie" and ($or1["id"]<>$dscr)
	and $or2["image"]=$v["image"])	{$or2["id"]=$dscr;$or2type=$v["stype"];}
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
?>