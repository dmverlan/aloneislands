<?
if (isset($_GET["get_herbal"]) and $pers["action"]==1)
{
	set_vars("action=0",$pers["uid"]);
	$herbal_grow = HERBAL_GROW;
	if (WEATHER==2) $herbal_grow/=2;
	if (WEATHER==3) $herbal_grow*=2;
	if (WEATHER==1 and date("m")>5 and date("m")<9) $herbal_grow*=3;
	if (WEATHER==6) $herbal_grow/=3;
	$w = sqla("SELECT id FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and p_type=2 and durability>0");
	$res = sqla("SELECT time,image,name FROM herbals_cell WHERE x_y='".$cell["x"]."_".$cell["y"]."' and image=".intval($_GET["get_herbal"])." and time<".(time()-$herbal_grow)."");
	if ($res["image"] and $w["id"] and md5($pers["city"].$res["name"])==$_GET["code"])
	{
		echo "<center class=but><font class=hp>Удачно срезано \"<img src=images/weapons/herbals/".$res["image"].".gif title='".$res["name"]."' height=20>".$res["name"]."\"<br></font><b>Долговечность серпа понизилась на 1.<br>Мирный опыт +1</b></center>";
		sql("UPDATE wp SET durability=durability-1 WHERE id=".$w["id"]."");
		set_vars("peace_exp=peace_exp+1",$pers["uid"]);
		$lastid = sqla("SELECT MAX(id) FROM wp");
		$lastid = 1+$lastid[0];
		sql("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` ,`p_type`, `timeout`) 
VALUES (
'".$lastid."', '".$pers["uid"]."', '0','','1', '0', 'herbals/".intval($_GET["get_herbal"])."', '', 'herbal', 'herbal', '".$res["name"]."', '', '1', '0', '1', '1','200',".(time()+1200000).");");
		sql("UPDATE herbals_cell SET time=".time()." WHERE x_y='".$cell["x"]."_".$cell["y"]."' and image=".intval($_GET["get_herbal"])." and time<".(time()-$herbal_grow)." LIMIT 1;");
	}elseif (empty($w["id"])) echo "<div class=return_win align=center>Вас кто-то опередил...</div>";
}
?>