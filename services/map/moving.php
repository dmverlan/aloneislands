<?
	if (intval($sphp)==0)$sphp=5000;
	if (intval($spma)==0)$spma=5000;
	
$cells_around = sql("SELECT x,y,winnable,buildable,belong,name FROM nature WHERE x>=".($x-3)." and x<=".($x+3)." and 
y>=".($y-2)." and y<=".($y+2)." and passable=1");

$maked_str = '';
function Minus($a)
{
	if($a<0) $a = "M".abs($a);
	return $a;
}
while ($cc = mysql_fetch_array($cells_around))
{
	if ($cc["winnable"] and $cc["belong"]==$pers["uid"]) 
		$ztmp=1*4; 
	else 
		$ztmp=0;
	//$maked_str .= ($cc["winnable"]+$cc["buildable"]*2+$ztmp).'<'.$cc["x"].'_'.$cc["y"].'>';
	$maked_str .= "var NAME".Minus($cc["x"])."_".Minus($cc["y"])."=\"".$cc["name"]."\";";
	$maked_str .= "var X".Minus($cc["x"])."_".Minus($cc["y"])."=".($cc["winnable"]+$cc["buildable"]*2+$ztmp).';';
}

/*
$t=time();
if(@$_GET["go_nature"]=="up")$go_n ='север';
if(@$_GET["go_nature"]=="down")$go_n ='юг';
if(@$_GET["go_nature"]=="left")$go_n ='запад';
if(@$_GET["go_nature"]=="right")$go_n ='восток';
if(@$_GET["go_nature"]=="lup")$go_n ='северо-запад';
if(@$_GET["go_nature"]=="ldown")$go_n ='юго-запад';
if(@$_GET["go_nature"]=="rup")$go_n ='северо-восток';
if(@$_GET["go_nature"]=="rdown")$go_n ='юго-восток';
if (@$_GET["go_nature"])$p="Переход на <i>".$go_n."</i>."; else $p="";
$y = $pers["y"];
$x = $pers["x"];*/

/*
$buildings_around = sql("SELECT x,y,type FROM buildings WHERE x>=".($x-3)." and x<=".($x+3)." and 
y>=".($y-2)." and y<=".($y+2)."");
$bd_str = '';
while ($cc = mysql_fetch_array($buildings_around))
{
	//$bd_str .= '<'.$cc["x"].'_'.$cc["y"].':'.$cc["type"].'>';
	$bd_str .= "var B".Minus($cc["x"])."_".Minus($cc["y"])."=".$cc["type"].';';
}
*/
?>