<title>Бесплатно скачать музыку</title>
<LINK href=main.css rel=STYLESHEET type=text/css>
<SCRIPT src="jquery.js"></SCRIPT>
<SCRIPT src="sm2.js"></SCRIPT>
<SCRIPT src="aimusic.js"></SCRIPT>
<center>
<a href="http://aloneislands.ru"><img src="http://aloneislands.ru/images/b1.jpg" title='Лучшая игра'></a>
<h1>Поиск музыки по базе <b href='http://vkontakte.ru' class=blue style="cursor:pointer;">В Контакте</b> с возможностью скачивая!</h1>
<center class=but style='width:600px;'>
<?
if (isset($_GET["q"])) $_POST["q"] = $_GET["q"];

if ($_POST["norepeat"]==1) $checked = 'CHECKED'; else $checked = '';
echo "
<form method=post action=index.php>
<i>Искать:</i><br>
<input name=q value='".$_POST["q"]."' class=login2 style='width:500px;COLOR:#000000;'><br>
<input type=checkbox value=1 name=norepeat id=norepeat ".$checked."><label for=norepeat>Без повторений</label><br>
<input type=submit value='Искать!' class=combofight style='width:500px;cursor:pointer;'>
</form>
";
?>
</center>
<?
	//phpinfo();
	if (isset($_POST["q"]))
	{
		include "exp.php";
	}
?>
</center>
<br><br>
<center><script>co1();co2();</script></center>
<i class=timef>Если песня не скачивается, попробуйте другой браузер.</i>