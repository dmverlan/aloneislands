<?
define("FISHING_TIME",75);

define("HERBAL_CHANGE",18200);
define("HERBAL_GROW",8600);
define("HERBAL_COUNT",5);
define("HLOOK_TIME",40);

define("TREE_CHANGE",20000);
define("TREE_GROW",4600);
define("TREE_COUNT",2);
define("TLOOK_TIME",60);

define("GAIN_COST",50);
?>
<script type="text/javascript" src="js/naturen.js?1"></script>
<div id=mainouter>
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="background-image: url('images/DS/main_bg.png')">
<tr>
<td align=center width=640 id=map valign=top>
</td>
<td align=center valign=top id=d2 style="height:100%; background-image: url('images/DS/main_green_column_right.png'); background-position: left top; background-repeat: no-repeat;">
</td>
</tr>
</table>
<div style="background-image: url('images/DS/main_topline.jpg'); height:17px; width:100%;"></div>
</div>

<div id=outer style="display:none;"><?
$t = tme();
$x = $pers["x"];
$y = $pers["y"];
$cell = sqla("SELECT * FROM nature WHERE x=".$x." and y=".$y."");

########## Телепорт
if($_POST["teleport"] and $cell["teleport"])
{
	list($xtp,$ytp) = explode("_",$_POST["teleport"]);
	$TP = sqlr("SELECT COUNT(*) FROM nature WHERE x=".intval($xtp)." and y=".intval($ytp));
	if($TP and $pers["money"]>=$cell["teleport"])
	{
		set_vars("x=".intval($xtp)." , y=".intval($ytp)." , money = money-".$cell["teleport"],UID);
//		say_to_chat ("#sound","windsand","1",$pers["user"],"*");
		echo "<b class=green>Удачная телепортация!</b><script>top.Sound('misc6',0.6,0);</script>";
	}
}
##########

if ($cell["belong"]==$pers["uid"] or !$cell["winnable"])
{
include("inc/get_herbal.php");
if (@$_GET["wood"] and $cell["wood"]) include("inc/wood.php");


$www = 1;
if ($t<$pers["waiter"]) $www = 0;

$wwid = WEATHER;
if (date("H")>21 or date("H")<7) $wwid+=10;

if (@$_WOOD_RESPONSE) echo $_WOOD_RESPONSE;
include('cariers/fishing.php');
include('cariers/herbalism.php');

include ("inc/bot_cell.php");
}

include('gain/wcell.php');

include ("quest/main.php");
?></div>

<script>
<?
	if($tmp_wt=mtrunc($pers["waiter"]-tme())) 
		echo "waiterSPEC(".$tmp_wt.");";
?>
var aX = <? echo $pers["x"];?>;
var aY = <? echo $pers["y"];?>;
view_nature();
</script>
