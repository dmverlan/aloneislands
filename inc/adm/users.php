<center class=loc>Приветствие.<br>Введите ник персонажа для редактирования:<form action=main.php method=post><input type=text name=nick class=laar><input type=submit value=ОК class=login></form><br><a href=main.php?zeroingall=1 class=timef>Выдать всем обнуления</a><br><a href=main.php?szeroingall=1 class=timef>Выдать всем обнуления умения</a><br><a href=main.php?zeroall=1 class=timef>Принудительно обнулить всех</a><br><a href=main.php?fullzeroall=1 class=timef>Принудительно полностью обнулить всех</a>
<a href=main.php?myself_edit=1 class=but>Редактировать себя</a><hr>
<a href=main.php?weather=1 class=timef>Погода:Ясно</a>
<a href=main.php?weather=2 class=timef>Погода:Дождь</a>
<a href=main.php?weather=3 class=timef>Погода:Ливень</a>
<a href=main.php?weather=4 class=timef>Погода:Ветер</a>
<a href=main.php?weather=5 class=timef>Погода:Шторм</a>
<a href=main.php?weather=6 class=timef>Погода:Туман</a>
<a href=main.php?weather=7 class=timef>Погода:Град</a>
<a href=main.php?weather=8 class=timef>Погода:Снег</a>
<form action=main.php?zero=1 method=post>Обнулить:<input type=text name=zeronick class=laar><input type=submit value=ОК class=login></form><br>
<form action=main.php?fullzero=1 method=post>Полностью Обнулить:<input type=text name=fullzeronick class=laar><input type=submit value=ОК class=login></form>
</center>
<?
	if (!$priv["eusers"]) exit;
	if (@$_GET["weather"])
	{
		if(sql("UPDATE world SET weather=".intval($_GET["weather"]).",weatherchange=0")) echo "Погода установлена";
	}
	if (@$_GET["zeroingall"])
	{
		if(sql("UPDATE users SET zeroing=zeroing+1")) echo "Обнуления успешно выданы";
	}
	if (@$_GET["szeroingall"])
	{
		if(sql("UPDATE users SET skill_zeroing=skill_zeroing+1")) echo "Обнуления успешно выданы";
	}
	if (@$_GET["zeroall"])
	{
		if(sql("UPDATE users SET action=-10")) echo "Все успешно обнулены.";
	}
	if (@$_GET["fullzeroall"])
	{
		if(sql("UPDATE users SET action=-11")) echo "Все успешно полностью обнулены.";
	}
	if (@$_GET["zero"])
	{
		if(sql("UPDATE users SET action=-10 WHERE user='".$_POST["zeronick"]."'")) echo "Персонаж успешно обнулен.";
	}
	if (@$_GET["fullzero"])
	{
		if(sql("UPDATE users SET action=-11 WHERE user='".$_POST["fullzeronick"]."'")) echo "Персонаж успешно полностью обнулен.";
	}
	if (@$_POST["nick"] or @$_GET["myself_edit"])
	{
		if($_GET["myself_edit"])
			$_POST["nick"] = $pers["user"];
		$p = sqla("SELECT * FROM users WHERE user='".$_POST["nick"]."'");
		echo "<p class=inv>";
		echo "<form action=main.php?edit=".$p["uid"]." method=post><input class=login type=submit value='Сохранить'>";
		echo "<ul class=inv>";
		foreach ($p as $key=>$value)
		{
			if (is_string($key) and $key<>'uid'and $key<>'pass'and $key<>'second_pass'and $key<>'flash_pass'and $key<>'priveleged')
			{
				if($key==name_of_skill($key) or !name_of_skill($key))
					echo "<li>".$key." : <input class=laar type=text value='".$value."' name='".$key."'></li>";
				else
					echo "<li><b>".name_of_skill($key)."</b> : <input class=laar type=text value='".$value."' name='".$key."'></li>";
			}
		}
		echo "</ul>";
		echo "<input class=login type=submit value='Сохранить'></form>";
		echo "</p>";
	}
	if (@$_GET["edit"] and $priv["eusers"]>1)
	{
			$q = '';
			foreach($_POST as $key=>$value)
			{
				if ($key<>'uid'and $key<>'pass'and $key<>'second_pass'and $key<>'flash_pass'and $key<>'priveleged')
				{
				$key = str_replace (" ","",$key);
				$value = str_replace("'","",$value);
				$q .= "`".$key."`='".$value."',";
				}
			}
			$q = substr($q,0,strlen($q)-1);
			if (sql("UPDATE users SET ".$q." WHERE uid=".intval($_GET["edit"]).""))
			echo $_POST["user"]." успешно изменён!";
	}
?>