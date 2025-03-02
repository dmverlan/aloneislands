<a class=bga href=main.php?go=administration>Назад</a>
<center class=inv>Управление кабинетом министров</center>
<?
if (@$_GET["delete"] and $_GET["delete"]<>$pers["uid"])
{
	sql("UPDATE users SET priveleged=0 WHERE uid=".intval($_GET["delete"]));
	sql("DELETE FROM priveleges WHERE uid=".intval($_GET["delete"]));
}
if (@$_GET["edit"])
{
	$p = sqla("SELECT * FROM priveleges WHERE uid=".intval($_GET["edit"])."");
	echo "<form action=main.php?sedit=".$p["uid"]." method=post>";
	echo "<ul>";
	foreach ($p as $key=>$value)
	{
		if (is_string($key) and $key<>'uid')
		{
			echo "<li>".$key." : <input class=laar type=text value=".$value." name='".$key."'></li>";
		}
	}
	echo "</ul>[0 - не доступно. 1 - просмотр. 2 - изменение]";
	echo "<input class=login type=submit value='Сохранить'>";
	echo "</form>";
}
if (@$_GET["sedit"] and @$_POST)
{
	$q = '';
	foreach($_POST as $key=>$value)
	{
		$key = str_replace (" ","",$key);
		$value = str_replace("'","",$value);
		$q .= "`".$key."`='".$value."',";
	}
	$q = substr($q,0,strlen($q)-1);
	sql("UPDATE priveleges SET ".$q." WHERE uid=".intval($_GET["sedit"]));
}
if (@$_POST["go_in"])
{
	$p = sqlr("SELECT uid FROM users WHERE user='".$_POST["go_in"]."'",0);
	if ($p)
	{
	sql("INSERT INTO `priveleges` ( `uid` , `emap` , `ewp` , `emagic` , `ebots` , `eusers` , `emain` , `emedia` , `status` ) 
VALUES ('".$p."', '0', '0', '0', '0', '0', '0', '0', 'Министр');");
	sql("UPDATE users SET priveleged=1 WHERE uid=".$p."");
	}
}

echo "Состав:<br>";
$m = sql("SELECT uid,user,level,sign,state FROM users WHERE priveleged=1");
echo '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
echo "<tr><td>НИК</td><td>LvL</td><td>Клан</td><td>Карта</td><td>Вещи</td><td>Магия</td><td>Боты</td><td>Население</td><td>Министры</td><td>Медиа</td><td>Квесты</td><td>Должность</td></tr>";
while($p = mysql_fetch_array($m,MYSQL_ASSOC))
{
	echo "<tr>";
	$prv = sqla("SELECT * FROM priveleges WHERE uid=".$p["uid"]);
	echo "<td>";
	if ($p["uid"]<>$pers["uid"])echo "<a href='javascript:if(confirm(\"Вы действительно хотите исключить ".$p["user"]." из кабинетa министров?\")) location=\"main.php?delete=".$p["uid"]."\";'><img src=images/drop.gif></a><a href=main.php?edit=".$p["uid"]." class=user>".$p["user"]."</a>";
	else echo "<font class=user>".$p["user"]."</font>";
	echo "</td>";
	echo "<td class=lvl>".$p["level"]."</td>";
	echo "<td class=red><img src=images/signs/".$p["sign"].".gif>".$p["state"]."</td>";
	foreach ($prv as $key=>$value)
	{
		if (is_string($key))
		{
		if ($value and $key<>'status' and $key<>'uid') echo "<td class=green>ДА[".$value."]</td>";
		elseif ($key<>'status' and $key<>'uid') echo "<td class=hp>НЕТ</td>";
		}
	}
	echo "<td class=login>".$prv["status"]."</td>";
	echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo'<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1">
<tr>
		<td bgcolor="#F0F0F0" class="td"><form method="POST" action=main.php>
<p align="right">
<input name=go_in size=100 class=laar style="float: left"> 
<input type="submit" value="Принять" class=inv_but></p>
		</form></td>
	</tr></table>';
?>







