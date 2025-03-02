<?
include("inc/balance.php");

if ($priv["ewp"]==2)
{
if(@$_GET["addfullline"])
{
	AddAllWp();
}
if(@$_GET["addartfullline"])
{
	AddAllArtWp();
}
if(@$_GET["delartfullline"])
{
	sql("DELETE FROM weapons WHERE idn>=10000 and where_buy=1");
}
if (isset($_GET["delete"]))
{
	if (sql("DELETE FROM weapons WHERE id='".$_GET["delete"]."'")) echo "<center class=return_win>Удалено!</center>";
}
if (@$_GET["edit"]) 
{
	include("edit_w.php");
	die("<hr>");
}
if (@$_GET["new_wp"])
{
		$p["type"] = $_FILTER["lavkatype"];
		$type = "orujie";
		if ($p["type"]=="shle") $type = "shlem";
		if ($p["type"]=="naru") $type = "naruchi";
		if ($p["type"]=="perc") $type = "perchatki";
		if ($p["type"]=="kolc") $type = "kolco";
		if ($p["type"]=="kylo") $type = "ojerelie";
		if ($p["type"]=="sapo") $type = "sapogi";
		if ($p["type"]=="poya") $type = "poyas";
		if ($p["type"]=="bron") $type = "bronya";
	$mid = sqlr("SELECT MAX(idn) FROM weapons",0)+1;
	if ($mid<500) $mid+=500;
	sql("INSERT INTO weapons (`id`,`idn`,`type`,`stype`,`name`) VALUES
	('".$mid."',".$mid.",'".$type."','".$p["type"]."','Новая вещь ".$mid."')");
	echo "<center class=return_win>Добавлено.</center>";
}
if (@$_GET["copy"])
{
	$mid = sqlr("SELECT MAX(idn) FROM weapons",0)+1;
	if ($mid<500) $mid+=500;
	$part1 = "INSERT INTO weapons (";
	$part2 = ") VALUES (";
	$v = mysql_fetch_array(sql("SELECT * FROM weapons WHERE id='".$_GET["copy"]."'"),MYSQL_ASSOC);
	foreach($v as $key=>$a)
	{
		if($key=='idn') $a = $mid;
		if($key=='id') $a = $mid;
		$part1 .= "`".$key."`,";
		$part2 .= "'".$a."',";
	}
	$part1 = substr($part1,0,strlen($part1)-1);
	$part2 = substr($part2,0,strlen($part2)-1);
	sql($part1.$part2.");");
	echo "<center class=return_win>Скопировано.</center>";
}
if (@$_GET["give"])
{
	$uid = sqlr("SELECT uid FROM users WHERE user='".$_POST["nickfor"]."'");
	insert_wp($_GET["give"],$uid);
	echo "Удачно выдано";
}
}
?>
<script language=JavaScript src='js/adm_new.js' type="text/javascript"></script>
<script>
	function give(id)
{
	init_main_layer();
	ml.innerHTML += '<form action="main.php?give='+id+'" method=POST>КОМУ: <input class=login type=text value="" name=nickfor size=20><hr><input class=login type=submit value=[OK]></form>';
}
</script>
<a href="main.php?new_wp=1" class=bga>НОВАЯ ВЕЩЬ В ЭТОМ РАЗДЕЛЕ</a>
<table border=0 width=100% class=inv>
<tr><td align=center><script> show_imgs_sell('a=1'); </script><a href=main.php?a=1&set_type=fish class=bg>Рыба</a></td></tr><tr><td valign="top" align=center>
<form method="POST" action='main.php'><font class=time>
Сортировка: <select size="1" name="sort" class=items>
<option <? if ($_FILTER["lavkasort"]=='price') echo "selected"; ?> value="price">По цене</option>
<option <? if ($_FILTER["lavkasort"]=='tlevel') echo "selected"; ?> value="tlevel">По уровню</option>
<option <? if ($_FILTER["lavkasort"]=='where_buy') echo "selected"; ?> value="where_buy">Где купить</option>
</select>от<input type="text" name="minlevel" size="7" value="<? if ($_FILTER["lavkaminlevel"]<>"") echo $_FILTER["lavkaminlevel"];else echo "0";?>"  class=laar>до<input type="text" name="maxlevel" size="7" value=<? if ($_FILTER["lavkamaxlevel"]<>"") echo $_FILTER["lavkamaxlevel"];else echo $pers["level"];?>  class=laar>Уровня.&nbsp; 
Не дороже <input type="text" name="maxcena" size="7" value="<? if ($_FILTER["lavkamaxcena"]<>"") echo $_FILTER["lavkamaxcena"];else echo"1000";?>"  class=laar><input type="submit" value="Ок" class=loc></font>
<a href=main.php?view=l class=timef>В виде лавки</a>
<br>
<a href="javascript:if(confirm('Вы действительно хотите добавить полную линейку вещей в лавку?')) location = 'main.php?addfullline=1';" class=timef>Добавить полную линейку вещей</a> | 
<a href="javascript:if(confirm('Вы действительно хотите добавить полную линейку артов в дд?')) location = 'main.php?addartfullline=1';" class=timef>Добавить полную линейку артов</a> | 
<a href="javascript:if(confirm('Вы действительно хотите удалить полную линейку артов в дд?')) location = 'main.php?delartfullline=1';" class=timef>Удалить полную линейку артов</a> | 
<a href="javascript:if(confirm('Вы действительно хотите добавить полную линейку вещей в лавку?')) location = 'main.php?addfulllinethistype=1';" class=timef>Добавить полную линейку вещей этого типа</a> 
<?
	if ($_FILTER["lavkatype"]!='napad')
		$stype = "`stype`='".$_FILTER["lavkatype"]."'";
		else
		$stype = "`type` = 'napad' ";
	
	if ($_FILTER["lavkatype"]<>'all')
	$enures= sql ("SELECT * FROM `weapons` WHERE `tlevel`>='".$_FILTER["lavkaminlevel"]."' and `tlevel`<='".$_FILTER["lavkamaxlevel"]."' and `price`<='".$_FILTER["lavkamaxcena"]."' and ".$stype." ORDER BY `".$_FILTER["lavkasort"]."`,`where_buy` ASC");
	else
	$enures= sql ("SELECT * FROM `weapons` WHERE `tlevel`>='".$_FILTER["lavkaminlevel"]."' and `tlevel`<='".$_FILTER["lavkamaxlevel"]."' and `price`<='".$_FILTER["lavkamaxcena"]."' ORDER BY `".$_FILTER["lavkasort"]."`,`where_buy` ASC");
echo '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF class=LinedTable>';
while ($v=mysql_fetch_array ($enures,MYSQL_ASSOC)) 
{
	if($_GET["view"]!='l')
	{
	echo "<tr>";
	echo "<td width=10><img src=images/drop.gif onclick='if(confirm(\"УДАЛИТЬ???\")) location=\"main.php?delete=".$v["id"]."\"' style='cursor:pointer'></td>";
	echo "<td class=items>".$v["id"]."</td>";
	if ($priv["ewp"]==2)echo "<td><input type=button class=login onclick=\"give('".$v["id"]."')\" value=\"Выдать\"><input type=button class=login onclick='location=\"main.php?copy=".$v["id"]."\"' value=\"Раскопировать\"></td>";
	$rank_i = ($v["s1"]+$v["s2"]+$v["s3"]+$v["s4"]+$v["s5ya"]+$v["s6"]+$v["kb"])*0.3 + ($v["mf1"]+$v["mf2"]+$v["mf3"]+$v["mf4"]+$v["mf5"])*0.03 + ($v["hp"]+$v["ma"])*0.04+($v["udmin"]+$v["udmax"])*0.3;
	echo "<td class=user><a href=main.php?edit=".$v["id"]."><img src=images/weapons/".$v["image"].".gif height=40></a>".$v["name"]."<font class=timef>(".$rank_i.") [".$v["index"]."]</font></td>
	<td class=time>Кол-во в лавке:".$v["q_s"]."</td>";
	if ($v["where_buy"]==0) echo "<td class=green>купить в лавке</td>";
	elseif ($v["where_buy"]==1) echo "<td class=blue>купить в дд</td>";
	else echo "<td class=red><b>нигде</b></td>";
	echo "<td class=lvl>Уровень: ".$v["tlevel"]."</td>";
	echo "<td class=time>".$v["price"]." LN; ".$v["dprice"]." y.e.</td>";
	echo "<td class=time>".$v["slots"]."</td>";
	echo "</tr>";
	}
	else
	{
		echo "<tr><td align=left class=weapons_box>";
		if ($v["q_s"]<1) echo "<font class=hp><b> Нет в наличии</b></font> ";
if ($v["q_s"]>0 and $v["price"]<=$pers["money"]) echo "<input type=text class=login size=2 id='".$v["id"]."k' value=1> <input type=button class=inv_but onclick=\"w_buy('".$v["id"]."')\" value='Купить, Осталось: ".$v["q_s"]."'>";
		$vesh = $v;
		include ("inc/inc/weapon.php");
		echo "</td></tr>";
	}
}
echo "</table>";
?>