<?
$text = '';
$t = time();
$napad = 0;
$options = explode ("|",$pers["options"]);
if (is_array($vesh)) $v = $vesh;
if ($v['id']<>'') {
$text .= "show_w (";

if (!empty($v["name"])) {
$counter++;
if ($lavka == 1) $v["durability"] = $v["max_durability"];
$text .= "'".$v["name"]."','".$sht."','".$v["image"]."','".$v["durability"]."','".$v["max_durability"]."','";
if ($v['dprice']==0){
if ($lavka<>1 or $pers['money']>=$v['price']) 
	$text .= "<font class=user><img src=images/money.gif> <b>".$v["price"]."LN</font></b><font class=items><br>"; 
else 
	$text .= "<font class=hp><img src=images/money.gif> <b>".$v["price"]."LN</font></b><font class=items><br>";

}
else
$text .= "<img src=images/signs/diler.gif> <b>".$v["dprice"]." Бр. </b><font class=items><br>";
$text .= "',".$v['price'].",'".$v["dprice"]."','";
$rank_i = ($v["s1"]+$v["s2"]+$v["s3"]+$v["s4"]+$v["s5ya"]+$v["s6"]+$v["kb"])*0.3 + ($v["mf1"]+$v["mf2"]+$v["mf3"]+$v["mf4"]+$v["mf5"])*0.03 + ($v["hp"]+$v["ma"])*0.04+($v["udmin"]+$v["udmax"])*0.3;
if ($v["stype"]=="shit")
$v["describe"] .= "<br>Защита от магии +50%";
if ($v["type"]=="napad")
{
	if ($v["stype"]=="napadt")
		$v["describe"] .= "<div class=but>Свиток тактического нападения</div>";
	else
		$v["describe"] .= "<div class=but>Свиток классического нападения</div>";
	if ($v["p_type"]==15)
		$v["describe"] .= "<div class=hp>ЗАКРЫТЫЙ БОЙ</div>";
}
if ($v["stype"]=="resources")
$v["describe"] .= "Полезный ресурс";
if ($v["timeout"])
{
	if($v["describe"])
		$v["describe"].='<br><span class=timef>Пропадёт через '.tp($v["timeout"]-$t)."</span>";
	else
		$v["describe"].='<span class=timef>Пропадёт через '.tp($v["timeout"]-$t)."</span>";
}
if ($v["type"]=="rune"){$v["describe"].='<br>Чтобы вставить руну в предмет, нужно чтобы этот предмет был надет на вас, и ничего больше.';if ($v["udmax"])$v["udmin"]=1;}
if ($v["upgrated"]){$v["describe"].='<br><b class=green>УЛУЧШЕНА</b>';}

$attrs = '<table style="border-width:0px; font-size:10px;width:100%" cellspacing=0>';
$v_count = 0;
$R = all_params();
foreach($R as $r)
	if ($v[$r] and $r<>"udmin" and $r<>"udmax")
	{
		$v_count++;
		if (substr($r,0,2)=='mf') $prc = '%'; else $prc = '';
		if ($r == 'kb') $prc = '<b class=green>КБ</b>';
		if ($r == 'hp') $prc = '<b class=hp>HP</b>';
		if ($r == 'ma') $prc = '<b class=ma>MP</b>';
		$attrs.= '<tr><td width=140 nowrap>'.name_of_skill($r).': </td><td><b>'.plus_param($v[$r]).' '.$prc.'</b></td></tr>';
	}
if ($v["udmin"] or $v["udmax"])
{
	$v_count++;
	$attrs.= '<tr><td>Удар: </td><td><b>'.$v["udmin"].'-'.$v["udmax"].'</b></td></tr>';
}
if($v["material_show"])
	$attrs.= '<tr><td>Материал: </td><td>'.$v["material_show"].'</td></tr>';
else
	$attrs.= '<tr><td>Материал: </td><td><i>неизвестно</i></td></tr>';
if ($rank_i>0)
	$attrs.= '<tr><td>Ранк: </td><td><i>'.$rank_i.'</i></td></tr>';
$attrs .= '</table>';
if (!$v_count) $attrs = '';

if ($v["where_buy"]<>'0') $text .= "1',"; else $text .= "0',";
$text .= "'".$attrs."','".$v["describe"]."','".$v["present"]."','".$v["clan_sign"]."','".$v["clan_name"]."',".intval($v["slots"]).",".intval($v["radius"]).",".intval($v["arrows"]).",".intval($v["arrows_max"]).",'".$v["arrow_name"]."'
,'";

$_ATTR = '';
if ($v["type"]=="zakl") 
{
	$bl=sqla("SELECT esttime,params FROM `auras` WHERE `id`='".$v["index"]."'");
	$_ATTR = '<table border=0 cellspacing=0 cellspadding=0 width=100%>';
		$params = explode("@",$bl["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			if (substr($p[0],0,2)=='mf') $perc = '%'; else $perc = '';
			if ($p[1][strlen($p[1])-1]=='%') $perc .= '<i>[%]</i>';
			if ($p[1])
			$_ATTR .= "<tr><td width=60% class=items>".name_of_skill($p[0]).": </td><td class=items><b>".plus_param(intval($p[1])).$perc."</b></td></tr>";
		}
	$_ATTR .= '</table>';
	$text .= tp($bl['esttime']).$_ATTR."','";
}else $text .= "0','";
$z=1;
$text .= $v["weight"]."','".$v["index"]."','";

if ($pers["level"]<$v["tlevel"]) {$p="hp";$z=0;} else $p="green";
$text .= "<font class=mfb>Масса: <b>".$v["weight"]."</b></font><center class=but><b>Уровень</b>:<font class=".$p."> ".$v["tlevel"]."</font></center>";

$attrs = '<table style="border-width:0px; font-size:10px;width:100%" cellspacing=0>';
$v_count = 0;
$R = all_params();
foreach($R as $r)
{
	if ($v['t'.$r])
	{
		$v_count++;
		if (substr($r,0,2)=='mf') $prc = '%'; else $prc = '';
		if ($r == 'kb') $prc = '<b class=green>КБ</b>';
		if ($r == 'hp') $prc = '<b class=hp>HP</b>';
		if ($r == 'ma') $prc = '<b class=ma>MP</b>';
		if ($v['t'.$r]>$pers[$r]) {$cls = 'hp';$z=0;} else $cls = 'green';
		$attrs.= '<tr><td>'.name_of_skill($r).': </td><td class='.$cls.'><b>'.$v['t'.$r].' '.$prc.'</b></td></tr>';
	}
}
$attrs .= '</table>';
if (!$v_count) $attrs = '';
$text .= $attrs;

if($v["clan_sign"])$text .= "Клан:<img src=images/signs/".$v["clan_sign"].".gif title=\'".$v["clan_name"]."\'><br>";

$text .= "'";
if ($v["type"]=="zakl") $napad=2;
if ($v["type"]=="napad") $napad=1;
}
$text .= ");";
}
?>