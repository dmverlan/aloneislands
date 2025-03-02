<head>
<meta http-equiv="Content-Language" content="ru">
<LINK href=main.css rel=STYLESHEET type=text/css>
<title>Alone Islands [Подборка рецепта]</title>
</head>
<body topmargin="15" leftmargin="15" rightmargin="15" bottommargin="15" class=inv>
<?
include ("inc/functions.php");
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);
function bb($id)
{
	$a = sqla("SELECT image,name FROM herbals WHERE image=".$id."");
	return "<img src='images/weapons/herbals/".$a["image"].".gif' title='".$a["name"]."'>";
}
function aa($id)
{
	$a = sqla("SELECT image,name FROM herbals WHERE image=".$id."");
	return $a["name"];
}
$id = intval($_GET["id"]);
echo "Ингредиент: <b>".aa($id)."</b><br>";
	if ($id%30==0)
	{
		echo "Этот ингредиент может усилить ваше зелье!<br>".bb($id)."+".bb(50-$id%50)."= Двоекратное усиление<br>";
	}
	if (!$z["image"])
	{
		for ($j=round($id/2);$j<=round($id/2)+30;$j+=8)
		if (($id+$j)%30<14 and ($id+$j)%30>0) break;
$z = sqla("SELECT image,name,param FROM potions WHERE image=".(($id+$j)%30)." and image<14 LIMIT 0,1");
if ($z["image"])
{
	echo bb($id)."+".bb($j)." = <b>".$z["name"]."</b>";
}
}
?>
<hr>
Напоминание: Зелье тем сильней и дольше действует, чем больше ингридиентов в нём.<br>
Одно сваренное зелье прибавляет 1/(УМЕНИЕ АЛХИМИКА+1) умения алхимика.<br>
Существуют некоторые комбинации ингредиентов, способные повысить эффект зелья в несколько раз.<br>
Некоторые ингредиенты прибавляют 30 мин к действию зелья.<br>
<hr>
<b>Зелье мощи</b>: Боярышник,Калина,Карагана,Мускатный Орех<br>
<b>Зелье титановой кожи</b>: Астрагал,Лавровый Лист,Мускатный Орех,Корица<br>
<b>Зелье мастерства волшебного</b>: Белая поганка,Мухомор вонючий,Мухомор красный,Мухомор пантерный<br>
<b>Зелье великана</b>: Фенхель,Айва,Шпинат,Сатанинский гриб<br>
<b>Зелье горца</b>: Лук,Облепиха,Чеснок,Алоэ<br>
<b>Зелье невидимости</b>: Груздь чёрный,Чёрный перец,Тысячелистник,Рядовка серая<br>
<b>Зелье атлета</b>: Крапива,Орегано<br>
<b>Зелье восстановления</b>: Базелик,Инжир,Айва<br>
</body>