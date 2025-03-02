<div class=fightlong>
<?
/*
if (substr_count($pers["rank"],"<molch>") or $pers["diler"]=='1' or $pers["priveleged"]);else
{
	echo "Шахта находится в режиме тестирования.";
	exit;
}*/

if ($pers["waiter"]>tme()) 
	echo "<center><div id=waiter class=items align=center></div><script>waiter(".($pers["waiter"]-tme()).");</script></center>";
	
define("LMINE1",50);
define("LMINE2",90);
define("LMINE3",130);
define("LMINE5",300);

if (@$_GET["buy"] and isset($_GET["kolvo"]) and $_GET["kolvo"]>0 and $_GET["kolvo"]<100) 
{
$buy = addslashes($_GET["buy"]);
$v = sqla ("SELECT price,name,id,max_durability FROM `weapons` WHERE `id`='".$buy."'");
$kolvo = intval($_GET["kolvo"]);

if ($pers["money"]<($v["price"]*$kolvo)) 
	echo "<center class=but><font class=hp>Не хватает денег.</font></center>"; 
else 
{
	for ($i=1;$i<=$kolvo;$i++) insert_wp($v["id"],$pers["uid"],$v["max_durability"],0);
	$v["q_s"]-= $kolvo;
	$pers["money"]-= $v["price"]*$kolvo;
	sql ("UPDATE `users` SET `money`='".$pers["money"]."' , `inv`='".$pers["inv"]."' WHERE `uid`='".$pers["uid"]."' ;");
	echo "<center class=but><font class=green>Вы удачно купили \"".$v["name"]."\" за ".$v["price"]." LN.(".$kolvo." шт.)</font></center>";
}
}

if (@$_GET["lbuy"]<6 and $_GET["lbuy"]>0)
{
$cat = intval($_GET["lbuy"]);
if ($cat==1 and $pers["money"]>LMINE1)
{
	$a["image"] = rand(82,83);
	$a["params"] = '';
	$a["esttime"] = 3600;
	$a["name"] = 'Лицензия шахтёра';
	$a["special"] = 14;
	light_aura_on($a,$pers["uid"]);
	set_vars("money=money-".LMINE1,UID);
	$pers["money"]-=LMINE1;
}
if ($cat==2 and $pers["money"]>LMINE2)
{
	$a["image"] = rand(82,83);
	$a["params"] = '';
	$a["esttime"] = 3600*2;
	$a["name"] = 'Лицензия шахтёра';
	$a["special"] = 14;
	light_aura_on($a,$pers["uid"]);
	set_vars("money=money-".LMINE2,UID);
	$pers["money"]-=LMINE2;
}
if ($cat==3 and $pers["money"]>LMINE3)
{
	$a["image"] = rand(82,83);
	$a["params"] = '';
	$a["esttime"] = 3600*3;
	$a["name"] = 'Лицензия шахтёра';
	$a["special"] = 14;
	light_aura_on($a,$pers["uid"]);
	set_vars("money=money-".LMINE3,UID);
	$pers["money"]-=LMINE3;
}
if ($cat==5 and $pers["money"]>LMINE5)
{
	$a["image"] = rand(82,83);
	$a["params"] = '';
	$a["esttime"] = 3600*5;
	$a["name"] = 'Лицензия шахтёра';
	$a["special"] = 14;
	light_aura_on($a,$pers["uid"]);
	set_vars("money=money-".LMINE5,UID);
	$pers["money"]-=LMINE5;
}
$_MINE = 3600*$cat;
}


if ($_MINE and !$_UMINE) 
	echo "<center class=but><input type=button class=login onclick=\"location='main.php?gomine=1'\" value='Спуститься в шахту' style='width:80%'><br>Осталось ".tp($_MINE).".</center>";
if ($_UMINE) 
	echo "<center class=but>Отдышка: Осталось ".tp($_UMINE).".</center>";
	
	
echo "<center class=timef>У вас с собой <img src=images/money.gif><b>".round($pers["money"],2)." LN</b></center>"; 

if (!$_UMINE)
echo "<table border=0 cellspacing=0 cellspadding=0 class=but2><tr>
<td width=25% class=but align=center><input type=button class=login onclick=\"location='main.php?lbuy=1'\" value='Купить'> лицензию добычи на <b>час</b>. ".LMINE1." LN</td>
<td width=25% class=but align=center><input type=button class=login onclick=\"location='main.php?lbuy=2'\" value='Купить'> лицензию добычи на <b>2 часа</b> ".LMINE2." LN</td>
<td width=25% class=but align=center><input type=button class=login onclick=\"location='main.php?lbuy=3'\" value='Купить'> лицензию добычи на <b>3 часа</b> ".LMINE3." LN</td>
<td width=25% class=but align=center><input type=button class=login onclick=\"location='main.php?lbuy=5'\" value='Купить'> лицензию добычи на <b>5 часов</b> ".LMINE5." LN</td>
</tr></table>";

$lavka = 1;
$enures= sql ("SELECT * FROM `weapons` WHERE p_type=5 or p_type=13 ORDER BY `price` ASC");
echo "<form action=main.php onsubmit='return false;' name=lavka1>";
while ($v=mysql_fetch_array ($enures)) 
{
	if ($v["price"]>$pers["money"]) echo "<font class=hp>Не хватает денег</font> ";
	if ($v["price"]<=$pers["money"]) echo "<input type=text class=laar size=1 id='".$v["id"]."k' value=1> <input type=button class=submit onclick=\"w_buy('".$v["id"]."')\" value='Купить'>";
	$vesh = $v;
	echo "<center><div class=but style='width:80%'>";
	include ("inc/inc/weapon.php");
	echo "</div></center>";
}
echo "</form>";
?>
</div>
<script>
function w_buy (id) {
location = 'main.php?buy='+id+'&kolvo='+document.getElementById(id+'k').value;
}
</script>
