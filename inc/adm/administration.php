<center width=100% class=but><b>Возможности министра</b>
<?
$_max = sqla("SELECT max_online,time_max_online FROM `configs` LIMIT 0,1");
$max = $_max["max_online"];
$tmax = $_max["time_max_online"];

$online = "Макс. Онлайн: <b>".$max."</b> | <span class=gray>".date("d.m.Y H:i",$tmax)."</span>";

$abb = '';
$links = '';
if ($priv["emap"]) 
{
	$abb .=  "Вы <b>можете</b> просматривать карту.<br>";
	if ($priv["emap"]==2)
	$abb .=  "Вы <b>можете</b> изменять карту.<br>";
	$links .= "<li><a class=bg href=main.php?go=map_edit>Переход в режим редактора карты</a></li>";
}else
{
	$abb .=  "Вы <b>не можете</b> просматривать карту.<br>";
}
if ($priv["ewp"]) 
{
	$abb .= "Вы <b>можете</b> просматривать вещи.<br>";
	$abb .= "Вы <b>можете</b> просматривать кланы.<br>";
	if ($priv["ewp"]==2)
	{
		$abb .= "Вы <b>можете</b> изменять вещи.<br>";
		$abb .= "Вы <b>можете</b> изменять кланы.<br>";
	}
	$links .= "<li><a class=bg href=main.php?go=weapons>Переход в режим редактора вещей</a></li>";
	$links .= "<li><a class=bg href=main.php?go=clans>Переход в режим редактора кланов</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> просматривать вещи.<br>";
}
if ($priv["emedia"]) 
{
	$abb .= "Вы <b>можете</b> использовать медиа-функции.<br>";
	$links .= "<li><a class=bg href=main.php?go=media>Медиа-функции</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> использовать медиа-функции.<br>";
}
if ($priv["emain"]) 
{
	$abb .= "Вы <b>можете</b> управлять кабинетом министров.<br>";
	$links .= "<li><a class=bg href=main.php?go=ministers>Кабинет министров</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> управлять кабинетом министров.<br>";
}
if ($priv["eusers"]) 
{
	$abb .= "Вы <b>можете</b> управлять населением мира.<br>";
	$links .= "<li><a class=bg href=main.php?go=users>Население</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> управлять населением мира.<br>";
}
if ($priv["emagic"]) 
{
	$abb .= "Вы <b>можете</b> управлять магией в мире.<br>";
	$links .= "<li><a class=bg href=main.php?go=magic>Магия</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> управлять магией мира.<br>";
}
if ($priv["equests"]) 
{
	$abb .= "Вы <b>можете</b> управлять квестами.<br>";
	$links .= "<li><a class=bg href=main.php?go=quests>Квесты</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> управлять квестами.<br>";
}
if ($priv["ebots"]) 
{
	$abb .= "Вы <b>можете</b> управлять существами мира.<br>";
	$links .= "<li><a class=bg href=main.php?go=bots>Боты</a></li>";
}else
{
	$abb .= "Вы <b>не можете</b> управлять существами мира.<br>";
}

$req = sqlr("SELECT COUNT(*) FROM avatar_request");
$links .= "<li><a class=bg href=main.php?go=ava_req>Одобрить образ[<b class=user>".$req."</b>]</a></li>";

$links .='<li><a class=bg href=main.php?go=add_tip>Подсказки</a></li>';
$links .='<li><a class=bg href=main.php?go=add_new>Новости</a></li>';
echo "<br>Должность: Создатель мира назначил вас на должность <b>".$priv["status"]."</b><br>";
//echo "[Каждый министр привелегирован на: Добаление и редактирование новостей, Создание и редактирование подсказок в мире от лица создателя.]</center>";
echo "<center class=but>".$online."<div style='width:50%'><ul class=but>".$links."</ul></div><p>".$abb."</p></center>";


if($_POST)
{
	$pass = rand(1000,9999);
	if(sql("UPDATE users SET pass=MD5('".$pass."') WHERE user='".$_POST["user"]."'"))
	echo $_POST["user"]." ::  Пароль успешно изменён на ".$pass;
}
echo "<div class=but2><form action=main.php method=post>Смена пароля:<br> Логин <input class=login type=text name=user> <input type=submit value=OK></form></div>";
?>
