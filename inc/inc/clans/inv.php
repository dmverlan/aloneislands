<?
$pers["sign"] = $clan["sign"];

$url = '';
if(!$delete_button1)
{ 
$delete_button1 = '';
$delete_button2 = '';
$url = 'action=addon&gopers=clan&clan=w&';
}


if (@$_GET["get_item"] and $pers["clan_tr"])
{
	$v = sqla("SELECT * FROM wp WHERE id=".intval($_GET["get_item"])." and clan_sign='".$clan["sign"]."'");
	if (@$v["id"] and $v["weared"]==0)
	{
		sql("UPDATE wp SET uidp=".$pers["uid"].",user='".$pers["user"]."' WHERE id=".intval($_GET["get_item"])." and clan_sign='".$clan["sign"]."'");
	}
}
?>

<center>
<table border=0 width=600 class=but>
<tr><td align=center><script> show_imgs_sell('<?=$url;?>inv=<?=$clan["sign"];?>'); </script></td></tr><tr><td valign="top">
<?
	if ($_FILTER["lavkatype"]!='napad')
		$stype = "`stype`='".$_FILTER["lavkatype"]."'";
		else
		$stype = "`type` = 'napad' ";

	if ($_FILTER["lavkatype"]<>'all')
	$enures= sql ("SELECT * FROM `wp` WHERE ".$stype." and clan_sign='".$pers["sign"]."'");
	else
	$enures= sql ("SELECT * FROM `wp` WHERE clan_sign='".$pers["sign"]."'");
$check = 0;
while ($v=mysql_fetch_array ($enures)) {
if($v["max_durability"] and !$v["durability"]) continue;
	echo "<div class=but2>";
$check++;
if ($v["weared"]==0 and $pers["clan_tr"]) echo "<a href=info.php?".$v["user"]." class=user target=_blank>".$v["user"]."</a> <input type=button class=but onclick=\"location='main.php?action=addon&gopers=clan&clan=w&get_item=".$v["id"]."'\" value='Взять'>";
elseif($pers["clan_tr"])
{
	echo "<font class=hp>Вещь надета на персонаже </font><a href=info.php?".$v["user"]." class=user target=_blank>".$v["user"]."</a>";
}
if($delete_button1)
	echo $delete_button1.$v["id"].$delete_button2;
echo "</div>";
$vesh = $v;
include ("inc/inc/weapon.php");

}
if ($clan["treasury"]<$check)
{
	$tr = sqlr("SELECT COUNT(*) FROM wp WHERE clan_sign='".$pers["sign"]."'",0);
	sql("UPDATE clans SET treasury=".$tr." WHERE sign='".$pers["sign"]."'");
}
?>
</table>
</center>