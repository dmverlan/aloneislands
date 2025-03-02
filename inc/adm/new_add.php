<center class=timef> Приветствую вас, <b><?= $pers["user"]; ?></b>!<hr> Какую новость вы хотели бы добавить?</center>
<?
include("inc/balance.php");
/*
if (@$_GET["delete"])
{
	if (sql("DELETE FROM news WHERE date='".$_GET["delete"]."'")) 
	echo "<b class=brdr>Новость удачно удалена!</b>";
	else
	echo "<b class=brdr>Что-то не так.</b>";
}
if (@$_POST["edittext"])
{
	if (sql("UPDATE news SET text='".$_POST["edittext"]."',title='".$_POST["edittitle"]."' WHERE date='".$_GET["edit"]."'")) 
	echo "<b class=brdr>Новость удачно изменена!</b>";
	else
	echo "<b class=brdr>Что-то не так.</b>";
}

$news = sql("SELECT * FROM news");
echo '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
while ($new = mysql_fetch_array($news,MYSQL_ASSOC))
{
	echo "<tr>";
	echo "<td class=brdr width=200>".substr($new["title"],0,20)."</td>";
	echo "<td class=time width=100>".date("d.m.y H:i",$new["date"])."</td>";
	echo "<td class=timef width=50%>".substr($new["text"],0,40)."</td>";
	echo "<td><a class=timef href=main.php?edit=".$new["date"].">edit</a> | <a class=timef href=main.php?delete=".$new["date"].">delete</a></td>";
	echo "</tr>";
}
echo "</table>";


if (@$_GET["edit"] and empty($_POST))
{
	$new = sqla("SELECT * FROM news WHERE date='".$_GET["edit"]."'");
	echo "<form method=post action=main.php?edit=".$_GET["edit"].">
Заголовок новости: <input class=login name=edittitle size = 50 value='".$new["title"]."'><br>
Текст новости:<br>
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
<form method=post action=main.php>
Заголовок новости: <input class=login name=ntitle size = 50><br>
Текст новости:<br>
<textarea name=ntext class=inv_button cols=50 rows=5>
</textarea>
<input type=submit class=login value='Добавить'></form>
<?
	if (@$_POST["ntitle"])
	{
	if (	sql("INSERT INTO `news` ( `date` , `title` , `text` ) 
VALUES (
'".time()."', '".$_POST["ntitle"]."', '".$_POST["ntext"]."'
);")) echo "НОВОСТЬ УСПЕШНО ДОБАВЛЕНА!";
	}*/
?>
</p>