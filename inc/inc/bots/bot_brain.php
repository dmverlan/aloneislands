<?
 $perstemp = $pers;
 $pers = $persvs;
 $persvs = $perstemp;
 
 $bplaceT = $bplace;

 #################################################
$r = round(sqrt(sqr($pers["xf"]-$persvs["xf"])+sqr($pers["yf"]-$persvs["yf"]))); // - расстояние между ботом и нами.
//echo "   ".$r."   ";
$goed = 0;
if ($r>1) // Если мы стоим далеко - стоит подойти
 {
for ($i=0;$i<3 and $goed==0;$i++)	
{
$shagi = 1;
if ($shagi>$r) $shagi=$r; // - не даём лишака в шагах
$x=$pers["xf"];
$y=$pers["yf"];
$chk = sql("SELECT uid,xf,yf FROM users WHERE cfight=".$pers["cfight"]." and (xf>=".($x-1)." and xf<=".($x+1).") and (yf>=".($y-1)." and yf<=".($y+1).") and chp>0");
while ($c = mysql_fetch_array($chk,MYSQL_NUM)) if ($c[0]) $bplace["xy"].= $c[1].'_'.$c[2].'|';
$chk = sql("SELECT id,xf,yf FROM bots_battle WHERE cfight=".$pers["cfight"]." and (xf>=".($x-1)." and xf<=".($x+1).") and (yf>=".($y-1)." and yf<=".($y+1).") and chp>0");
while ($c = mysql_fetch_array($chk,MYSQL_NUM)) if ($c[0]) $bplace["xy"].= $c[1].'_'.$c[2].'|';
$lambda = 999;
$xfinish = $x; $yfinish=$y;
for ($xx=$x-1;$xx<=$x+1;$xx++)
for ($yy=$y-1;$yy<=$y+1;$yy++)
{
if ($xx<1)continue;
if ($yy<0)continue;
if ($xx>$fight["maxx"]) continue;
if ($yy>$fight["maxy"]) continue;
if (($lambd=sqr($xx-$persvs["xf"])+sqr($yy-$persvs["yf"]))<$lambda 
and !substr_count($bplace["xy"],"|".$xx."_".$yy."|"))
{
	$lambda = $lambd;
	$xfinish = $xx;
	$yfinish = $yy;
}
}
if ($x==$xfinish and $y==$yfinish) {$x = $persvs["xf"]-1; $y = $persvs["yf"];}
$x = $xfinish;
$y = $yfinish;
//$x = round($pers["xf"] + signum($persvs["xf"]-$pers["xf"])*$shagi+signum($i));
//$y = round($pers["yf"] + signum($persvs["yf"]-$pers["yf"])*$shagi+signum(floor($i/2)));
/*if ($x==0) $x=1;
$check_for_go = sqla("SELECT uid FROM users WHERE cfight=".$pers["cfight"]." and xf=".$x." and yf=".$y." and chp>0");
if (!$check_for_go[0] and !substr_count($bplace["xy"],"|".$x."_".$y."|"))
		{
		$check_for_go = sqla("SELECT id FROM bots_battle WHERE cfight=".$pers["cfight"]." and xf=".$x." and yf=".$y." and chp>0");
		if (!$check_for_go[0])
				{
				sql ("UPDATE bots_battle SET xf=".$x." , yf=".$y." WHERE id=".$pers["id"]);
				$goed = 1;
				}
		}
*/
sql ("UPDATE bots_battle SET xf=".$x." , yf=".$y." WHERE id=".$pers["id"]);
$goed = 1;
$pers["xf"] = $x;
$pers["yf"] = $y;
}
}
 ###################################################

else
{
 //var_dump($persvs);
 $od = $pers["level"];
 include ("chooser.php");
 if($persvs["uid"])set_vars("damage_get=chp",$persvs["uid"]);
 //echo $persvs["chp"]."ЛАЛАЛА";	
  $text .= newbot_udar ("ug",$botU);
  $text .= newbot_udar ("ut",$botU);
  $text .= newbot_udar ("uj",$botU);
  $text .= newbot_udar ("un",$botU);
 $text .= '%'; 
}
 
 
 $perstemp = $pers;
 $pers = $persvs;
 $persvs = $perstemp;
 
 $bplace = $bplaceT;
?>