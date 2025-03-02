<center class=timef> Приветствую вас, <b><?= $pers["user"]; ?></b>!<hr> Какую подсказку вы хотели бы добавить?</center>
<?
if (@$_POST["ntitle"])
	{
		$idn = sqlr("SELECT MAX(id) FROM tips",0);
	if (	sql("INSERT INTO `tips` ( `id` , `title` , `text` ,`type`) 
VALUES (".($idn+1).",'".$_POST["ntitle"]."', '".$_POST["ntext"]."'
,2);")) echo "ПОДСКАЗКА УСПЕШНО ДОБАВЛЕНА!";
}

if (@$_GET["delete"])
{
	if (sql("DELETE FROM tips WHERE id='".$_GET["delete"]."' and type=2") and sql("UPDATE tips SET id=id-1 WHERE id>'".$_GET["delete"]."'")) 
	echo "<b class=brdr>Подсказка удачно удалена!</b>";
	else
	echo "<b class=brdr>Что-то не так.</b>";
}
if (@$_POST["edittext"])
{
	if (sql("UPDATE tips SET text='".$_POST["edittext"]."',title='".$_POST["edittitle"]."' WHERE id='".$_GET["edit"]."' and type=2")) 
	echo "<b class=brdr>Подсказка удачно изменена!</b>";
	else
	echo "<b class=brdr>Что-то не так.</b>";
}

$news = sql("SELECT * FROM tips WHERE type=2 ORDER BY `id` ASC");
echo '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
while ($new = mysql_fetch_array($news,MYSQL_ASSOC))
{
	echo "<tr>";
	echo "<td class=brdr width=200>".substr($new["title"],0,20)."</td>";
	echo "<td class=time width=100>".$new["id"]."</td>";
	echo "<td class=timef width=50%>".substr($new["text"],0,40)."</td>";
	echo "<td><a class=timef href=main.php?clan=tips&action=addon&gopers=clan&edit=".$new["id"].">edit</a> | <a class=timef href=main.php?clan=tips&action=addon&gopers=clan&delete=".$new["id"].">delete</a></td>";
	echo "</tr>";
}
echo "</table>";


if (@$_GET["edit"] and empty($_POST))
{
	$new = sqla("SELECT * FROM tips WHERE id='".$_GET["edit"]."' and type=2");
	echo "<form method=post action=main.php?clan=tips&action=addon&gopers=clan&edit=".$_GET["edit"].">
Заголовок подсказки: <input class=login name=edittitle size = 50 value='".$new["title"]."'><br>
Текст:<br>
<textarea name=edittext class=inv_button cols=50 rows=5>
".$new["text"]."
</textarea>
<input type=submit class=login value='Edit'></form>";
}
?>

<br>
<hr>
<p class=loc>
ДОБАВИТЬ:
<form method=post action=main.php?clan=tips&action=addon&gopers=clan>
Заголовок: <input class=login name=ntitle size = 50><br>
Текст:<br>
<textarea name=ntext class=inv_button cols=50 rows=5>
</textarea>
<input type=submit class=login value='Добавить'></form>
</p>