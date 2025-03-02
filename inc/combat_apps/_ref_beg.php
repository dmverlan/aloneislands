<?
if ($yapp["atime"]<=time() and $yapp["type"]==1)
{
	$pers["apps_id"] = 0;
	sql("DELETE FROM app_for_fight WHERE id=".$yapp["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0 WHERE apps_id=".$yapp["id"]."");
	$yapp["uid"] = 0;
	$yapp["type"]= 0;
}
if ($yapp["uid"]<>$pers["uid"] and $yapp["type"]==1 and @$_GET["refusem"])
{
	$pers["apps_id"] = 0;
	sql("UPDATE app_for_fight SET pl2=0,atime=".(time()+300)." WHERE id=".$yapp["id"]."");
	set_vars("apps_id=0,fteam=0",UID);
	$yapp["uid"] = 0;
	$yapp["type"]= 0;
}
if ($yapp["uid"]==$pers["uid"] and $yapp["type"]==1 and @$_GET["refuse"])
{
	$pers["apps_id"] = 0;
	sql("UPDATE app_for_fight SET pl2=0,atime=".(time()+300)." WHERE id=".$yapp["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$yapp["id"]." and fteam=2");
}
if ($yapp["uid"]==$pers["uid"] and $yapp["type"]==1 and @$_GET["get_back"])
{
	$pers["apps_id"] = 0;
	sql("DELETE FROM app_for_fight WHERE id=".$yapp["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$yapp["id"]."");
	$yapp["uid"] = 0;
	$yapp["type"]= 0;
}
if ($yapp["uid"]==$pers["uid"] and $yapp["type"]==1 and $yapp["pl2"]==1 and @$_GET["begin"])
{
	sql("DELETE FROM app_for_fight WHERE id=".$yapp["id"]."");
	$persvs = sqla("SELECT user FROM users WHERE apps_id=".$yapp["id"]." and fteam=2");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$yapp["id"]."");
	echo "da('Бой начался!');location='main.php';";
	if ($persvs)
	begin_fight ($pers["user"],$persvs["user"],"Дуэль на арене [".$yapp["comment"]."]",$yapp["travm"]
	,$yapp["timeout"],$yapp["oruj"],$yapp["bplace"],1);
}
if ($yapp["uid"]==$pers["uid"] and $yapp["type"]==2 and @$_GET["get_back"])
{
	$pers["apps_id"] = 0;
	sql("DELETE FROM app_for_fight WHERE id=".$yapp["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$yapp["id"]."");
	$yapp["uid"] = 0;
	$yapp["type"]= 0;
}
if ($yapp["uid"]==$pers["uid"] and $yapp["type"]==3 and @$_GET["get_back"])
{
	$pers["apps_id"] = 0;
	sql("DELETE FROM app_for_fight WHERE id=".$yapp["id"]."");
	sql("UPDATE users SET apps_id=0,fteam=0,refr=1 WHERE apps_id=".$yapp["id"]."");
	$yapp["uid"] = 0;
	$yapp["type"]= 0;
}
?>