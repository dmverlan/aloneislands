<script>
var MAINID;
function w_buy (id) {
	MAINID = 'id'+id;
	top.frames['updater'].location = 'services/_wpbuy.php?buy='+id+'&kolvo='+document.getElementById(id+'k').value+'&'+Math.random();
	document.getElementById(MAINID).innerHTML = '<img src=images/spinner.gif>';
}
</script>
<center>
<center style='width:760px;'>
<?
echo "<table style='width:100%;' cellspacing=0 cellspacing=0 class=but>
	<tr>
		<td style='width: 25%' align=center><div><a href=# ".build_go_string('pr_shop',$lastom_new)." class=But>Второй этаж</a> Магазин подарков</div></td>
		<td style='width: 25%' align=center><div><a href=# ".build_go_string('warehouse',$lastom_new)." class=But>Третий этаж</a> Аукцион</div></td>
		<td style='width: 25%' align=center><div><a href=# ".build_go_string('alchemy',$lastom_new)." class=But>Подвал</a> Лавка алхимика</div></td>
	</tr>
</table>
";

//Покупаем  вещь
if (isset($_GET["buy"]) and isset($_GET["kolvo"]) and $_GET["kolvo"]>0 and $_GET["kolvo"]<100) {
$v = sqla("SELECT price,q_s,where_buy,name,id,max_durability FROM `weapons` WHERE `id`='".$_GET["buy"]."' ;");
$kolvo = intval($_GET["kolvo"]);
if ($kolvo>$v['q_s']) $kolvo = $v['q_s'];
if ($v["where_buy"]==0 and $v["q_s"]>0) {
if ($pers["money"]<($v["price"]*$kolvo)) 
echo "<b><font class=hp>Не хватает денег.</b></font>"; 
else 
{
for ($i=1;$i<=$kolvo;$i++) insert_wp($v["id"],$pers["uid"],$v["max_durability"],0,$pers["user"]);
$pers["money"]-= $v["price"]*$kolvo;
sql ("UPDATE `users` SET `money`=money-".$v["price"]*$kolvo." WHERE `uid`='".$pers["uid"]."' ;");
sql ("UPDATE `weapons` SET `q_s`=q_s - ".$kolvo." WHERE `id`='".$v["id"]."' ;");
echo "<br><font class=hp>Вы удачно купили \"".$v["name"]."\" за ".$v["price"]." LN.(".$kolvo." шт.)</font>";
}}}
//Лавка

if($_FILTER["lavkatype"]=='potion' and @$_GET["put_lot2"])
{
	if(@$_GET["put_lot"])
	{
		$id = intval($_GET["put_lot"]);
		$price = mtrunc(intval($_POST["price"]));
		$RES = sqla("SELECT * FROM `wp` WHERE uidp=".UID." and stype='potion' and weared=0 and in_bank=0 and id=".$id);
		if($price>$RES["price"]*10)
			$price=$RES["price"]*10;
		if($RES)
			sql("UPDATE wp SET tempprice=".$price.",in_bank=2,user='".$pers["user"]."' WHERE id=".$id); 
	}
	$RES = sql("SELECT * FROM `wp` WHERE uidp=".UID." and stype='potion' and weared=0 and in_bank=0");
	echo "<table border=0 width=760 cellspacing=0 cellpadding=0 class=but>";
	while ($v=mysql_fetch_array ($RES)) {
	echo "<tr><td align=left class=weapons_box>";
	echo "<form method=post action='main.php?put_lot2=1&put_lot=".$v["id"]."'>Цена: <input type=text class=login size=3 name=price value=".$v["price"]."> <b>LN</b> <input type=submit class=inv_but value='Выставить на продажу'></form>";
	$vesh = $v;
	include ("inc/inc/weapon.php");
	echo "</td></tr>";
	}
	echo "</table>";
}
if($_FILTER["lavkatype"]=='potion' and @$_GET["buy_potion"])
{
	$id = intval($_GET["buy_potion"]);
	$RES = sqla("SELECT * FROM `wp` WHERE in_bank=2 and id=".$id);
	if($RES)
	{
		if($RES["tempprice"]>$pers["money"])
		{
			echo "<center class=but><b class=hp>Не хватает денег на покупку</b></center>";
		}else
		{
			set_vars("money=money-".intval($RES["tempprice"]),UID);
			$pers["money"] -= $RES["tempprice"];
			sql("UPDATE wp SET uidp=".UID.", user='".$pers["user"]."', in_bank=0, tempprice=0 WHERE id=".$id);
			say_to_chat("s","У вас купили <b>".$RES["name"]."</b> за <b>".$RES["tempprice"]." LN</b>. Покупку совершил <b>".$pers["user"]."</b>. Комиссия: <b>".round($RES["tempprice"]*0.1,2)." LN</b>",1,$RES["user"],"*");
			set_vars("money=money+".round($RES["tempprice"]*0.9,2),$RES["uidp"]);
			transfer_log(1,$RES["uidp"],$pers["user"],$RES["tempprice"],$RES["price"],$RES["name"],"Через лавку");
			transfer_log(4,$pers["uid"],$RES["user"],$RES["price"],$RES["tempprice"],$RES["name"],"Через лавку");
		}
	}
}

$ti=time();
echo '<table border="0" width=760 cellspacing="0" cellpadding="0" class=but>
	<tr>
		<td align="center">'; 

		if ($_FILTER["lavkasort"]<>'tlevel' and $_FILTER["lavkasort"]<>'price') $_FILTER["lavkasort"]='price';
?>
<table border=0 width=98% cellspacing=0 cellspadding=0 style="height:16px;"><tr><td style="background-image: url('images/DS/graybg_left.png'); background-position:bottom left; height:16px; width:12px;"></td><td style="background-image: url('images/DS/graybg.png');" align=center nowrap>
<form method="POST" action='main.php'><font class=Llvl>
Сортировка: <select size="1" name="sort" class=items>
<option <? if ($_FILTER["lavkasort"]=='price') echo "selected"; ?> value="price">По цене</option>
<option <? if ($_FILTER["lavkasort"]=='tlevel') echo "selected"; ?> value="tlevel">По уровню</option>
</select> | <b class=about>Уровень от<input type="text" name="minlevel" size="7" value="<? if ($_FILTER["lavkaminlevel"]<>"") echo $_FILTER["lavkaminlevel"];else echo "0";?>"  class=but2>до<input type="text" name="maxlevel" size="7" value=<? if ($_FILTER["lavkamaxlevel"]<>"") echo $_FILTER["lavkamaxlevel"];else echo $pers["level"];?>  class=but2></b> 
 | Не дороже <input type="text" name="maxcena" size="7" value="<? if ($_FILTER["lavkamaxcena"]<>"") echo $_FILTER["lavkamaxcena"];else echo"100000";?>"  class=but2><b>LN</b> | <input type="submit" value="Ок" class=but style='width:80px;height:16px;cursor:pointer;'></font>
</td><td style="background-image: url('images/DS/graybg_right.png'); background-position:bottom right; height:16px; width:12px;"></td></tr></table>
<script> show_imgs_sell(); </script>
</form><? echo "<center class=lUser>У вас с собой <b>".round($pers["money"],2)." LN</b></center>"; ?><hr></td></tr><tr><td valign="top">
<table border=2 width=98% cellspacing=2 cellpadding=2 bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>
<?
$lavka = 1;
if ($_FILTER["lavkasort"]!='tlevel') $_FILTER["lavkasort"]='price';

if ($_FILTER["lavkatype"]!='napad')
	$stype = "`stype`='".$_FILTER["lavkatype"]."'";
	else
	$stype = "`type` = 'napad' ";
	
$enures= sql ("SELECT * FROM `weapons` WHERE `tlevel`>='".$_FILTER["lavkaminlevel"]."' and `tlevel`<='".$_FILTER["lavkamaxlevel"]."' and `price`<='".$_FILTER["lavkamaxcena"]."' and ".$stype." and `where_buy`='0' ORDER BY `".$_FILTER["lavkasort"]."` ASC");
echo "<form action=main.php onsubmit='return false;' name=lavka1>";
while ($v=mysql_fetch_array ($enures)) {
if($v["material"]=='')
{
	$r = rand(1,2);
	if($v["tlevel"]<3)
		$r = rand(1,2);
	elseif($v["tlevel"]<10)
		$r = rand(2,4);
	else
		$r = rand(3,5);
	$resources = sql("SELECT * FROM resources ORDER BY RAND() LIMIT 0,".$r);
	while($_r = mysql_fetch_array($resources))
	{
		$v["material_show"] .= $_r["name"].", ";
		$v["material"] .= $_r["image"]."|";
	}
	$v["material"] = substr($v["material"],0,strlen($v["material"])-1);
	$v["material_show"] = substr($v["material_show"],0,strlen($v["material_show"])-2);
	sql("UPDATE weapons SET material='".$v["material"]."',material_show='".$v["material_show"]."' WHERE id='".$v["id"]."'");
}
echo "<tr><td align=left class=weapons_box>";
if ($v["q_s"]<1) echo "<font class=hp><b> Нет в наличии</b></font> ";
if ($v["q_s"]>0 and $v["price"]<=$pers["money"]) echo "<input type=text class=login size=2 id='".$v["id"]."k' value=1> <input type=button class=inv_but onclick=\"w_buy('".$v["id"]."')\" value='Купить, Осталось: ".$v["q_s"]."'> <div class=inv_but id=id".$v["id"]."></div>";
$vesh = $v;
include ("inc/inc/weapon.php");
echo "</td></tr>";
}

if($_FILTER["lavkatype"]=='potion')
{
	$lavka = 0;
	echo "<center><a href='main.php?put_lot2=1' class=wbut>Выставить</a></center>";
	$RES = sql("SELECT * FROM `wp` WHERE in_bank=2");
	echo "<table border=0 width=760 cellspacing=0 cellpadding=0 class=but>";
	while ($v=mysql_fetch_array ($RES)) {
	echo "<tr><td align=left class=weapons_box>";
	echo "Цена: <b>".$v["tempprice"]." LN</b> | Выставил: <b>".$v["user"]."</b>";
	if($v["tempprice"]<=$pers["money"])echo " | <input type=button class=inv_but onclick=\"location = 'main.php?buy_potion=".$v["id"]."'\" value='Купить'>";
	$vesh = $v;
	include ("inc/inc/weapon.php");
	echo "</td></tr>";
	}
	echo "</table>";
}
?></form></table></td></tr></table>
</center>
</center>


<script>
function success(name,price,kolvo)
{
	document.getElementById(MAINID).innerHTML = 'Вы удачно купили <b>"'+name+'"</b> за <b>'+price+'LN</b> в количестве '+kolvo+' шт.';
}
</script>