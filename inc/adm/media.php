<?
if (@$_POST["name"] and $_FILES["sign"]["type"]=="image/gif")
{
	$idl = sqla("SELECT MAX(id) FROM clans");
	$idl = intval($idl[0]);
	$idl++;
	sql("INSERT INTO `clans` ( `id` , `name` , `glav` , `sign` , `sait` ) 
VALUES (
'".$idl."', '".addslashes($_POST["name"])."', '".addslashes($_POST["glav"])."', 'c".$idl."', '".addslashes($_POST["site"])."');");
	sql("UPDATE users SET sign='c".$idl."',rank='<glav>',state='Глава клана' WHERE user='".addslashes($_POST["glav"])."'");
	print "<pre>";
if (move_uploaded_file($_FILES['sign']['tmp_name'], "images/signs/c".$idl.".gif")) {
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    print_r($_FILES);
} else {
    print "Possible file upload attack!  Here's some debugging info:\n";
    print "Possible file upload attack!  Дополнительная отладочная информация:\n";
    print_r($_FILES);
}
	print "</pre>";
	echo "Клан удачно зарегистрирован.";
}

if (@$_POST["nick"] and $_FILES["obr"]["type"]=="image/gif")
{
	$obr = sqla("SELECT MAX(obr) FROM users");
	$obr = $obr[0]+1;
	$pers = sqla("SELECT pol FROM users WHERE user='".addslashes($_POST["nick"])."'");
	if (move_uploaded_file($_FILES['obr']['tmp_name'], "images/persons/".$pers["pol"]."_".$obr.".gif")) {
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    print_r($_FILES);
} else {
    print "Possible file upload attack!  Here's some debugging info:\n";
    print "Possible file upload attack!  Дополнительная отладочная информация:\n";
    print_r($_FILES);
}
	sql("UPDATE users SET obr='".$obr."' WHERE user='".addslashes($_POST["nick"])."'");
	echo "Образ успешно установлен.";
}elseif (@$_POST["nick"]) echo "Неверный тип образа. Принимается только GIF!!!";

if (@$_POST["nick2"] and $_FILES["pict"]["type"]=="image/gif")
{
	if (move_uploaded_file($_FILES['pict']['tmp_name'], "images/titles/".($t=time()).".gif")) {
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    print_r($_FILES);
} else {
    print "Possible file upload attack!  Here's some debugging info:\n";
    print "Possible file upload attack!  Дополнительная отладочная информация:\n";
    print_r($_FILES);
}
	sql("UPDATE users SET image_in_info='images/titles/".$t.".gif' WHERE user='".addslashes($_POST["nick2"])."'");
	echo "Картинка успешно установлена.";
}elseif (@$_POST["nick2"]) echo "Неверный тип. Принимается только GIF!!!";


if (@$_POST["wpname"] and $_FILES["wp"]["type"]=="image/gif")
{
	if (move_uploaded_file($_FILES['wp']['tmp_name'], "images/weapons/".($_POST["wpname"]).".gif")) {
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    print_r($_FILES);
} else {
    print "Possible file upload attack!  Here's some debugging info:\n";
    print "Possible file upload attack!  Дополнительная отладочная информация:\n";
    print_r($_FILES);
}
	echo "Картинка успешно закачана.";
}

if (@$_POST["nick3"])
{
	$pers = sqlr("SELECT uid FROM users WHERE user='".addslashes($_POST["nick3"])."'",0);
	$a = sqlr("SELECT COUNT(id) FROM wp WHERE uidp=".$pers." and weared=1 and 
	`type`='".addslashes($_POST["type"])."'");
	sql("UPDATE wp SET image='".addslashes($_POST["wpname2"])."' WHERE uidp=".$pers." and weared=1 and 
	`type`='".addslashes($_POST["type"])."'");
	echo "Заменено ".$a." вещи.";
}
?>
<div class=loc>
<font class=inv>Принимается только GIF!!!</font>
<hr>
Clan-registering::
<form enctype="multipart/form-data" method=post>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="18%"><span lang="ru">Название</span></td>
		<td width="82%"><input class="login" name="name"></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Значёк</span></td>
		<td width="82%"><input class="login" name="sign" type=file></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Сайт (Без </span>http://<span lang="ru">)</span></td>
		<td width="82%"><input class="login" name="site" size="36"></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Точный НИК главы клана</span></td>
		<td width="82%"><input class="login" name="glav"></td>
	</tr>
	<tr>
		<td colspan="2" class="laar" align="center">
		<input class="laar" type="submit" value="Окай"></td>
	</tr>
</table>
</form><hr>

Avatar-change :: <br>
<form enctype="multipart/form-data" method=post>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="18%"><span lang="ru">Образ</span></td>
		<td width="82%"><input class="login" name="obr" type=file></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Точный НИК</span></td>
		<td width="82%"><input class="login" name="nick"></td>
	</tr>
	<tr>
		<td colspan="2" class="laar" align="center">
		<input class="laar" type="submit" value="Окай"></td>
	</tr>
</table>
</form>
<hr>

Pictrue-in-info set :: <br>
<form enctype="multipart/form-data" method=post>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="18%"><span lang="ru">Рисунок</span></td>
		<td width="82%"><input class="login" name="pict" type=file></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Точный НИК</span></td>
		<td width="82%"><input class="login" name="nick2"></td>
	</tr>
	<tr>
		<td colspan="2" class="laar" align="center">
		<input class="laar" type="submit" value="Окай"></td>
	</tr>
</table>
</form>


New image in weapons :: <br>
<form enctype="multipart/form-data" method=post>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="18%"><span lang="ru">Рисунок</span></td>
		<td width="82%"><input class="login" name="wp" type=file></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Название (images/weapons/название.gif)</span></td>
		<td width="82%"><input class="login" name="wpname"></td>
	</tr>
	<tr>
		<td colspan="2" class="laar" align="center">
		<input class="laar" type="submit" value="Окай"></td>
	</tr>
</table>
</form>

Заменить картинку оружия :: <br>
<form enctype="multipart/form-data" method=post>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="18%"><span lang="ru">Точный НИК</span></td>
		<td width="82%"><input class="login" name="nick3"></td>
	</tr>
	<tr>
		<td width="18%"><span lang="ru">Название (images/weapons/название.gif)</span></td>
		<td width="82%"><input class="login" name="wpname2"></td>
	</tr>
	<tr>
		<td width="18%">Тип заменяемой вещи (Надетой)</td>
		<td width="82%"><select size="1" name="type" class="login"><option selected value="shlem">Шлем</option><option value="ojerelie">Кулон</option><option value="orujie">Оружие</option><option value="poyas">Пояс</option><option value="zelie">Зелье/камень</option><option value="sapogi">Сапоги</option><option value="naruchi">Наручи</option><option value="perchatki">Перчатки</option><option value="kolco">Кольцо</option><option value="bronya">Броня</option></select></td>
	</tr>
	<tr>
		<td colspan="2" class="laar" align="center">
		<input class="laar" type="submit" value="Окай"></td>
	</tr>
</table>
</form>
</div>