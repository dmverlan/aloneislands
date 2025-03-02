<head>
<meta http-equiv="Content-Language" content="en-us">
<LINK href=main.css rel=STYLESHEET type=text/css>
<title>Восстановление персонажа</title>
<meta http-equiv=content-type content='text/html; charset=utf-8'>
</head>


<?
error_reporting(0);
include ('inc/functions.php');
include ('inc/sendmail.php');
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);

if (isset($_POST["email"]))
{
	$email = addslashes(str_replace("'","",$_POST["email"]));
	$spass = md5($_POST["spass"]);
	$fpass = intval($_POST["fpass"]);
	$pers = sqla("SELECT uid FROM users WHERE email='".$email."' and second_pass='".$spass."' and flash_pass='".$fpass."'");
	if ($pers["uid"] and empty($_POST["cemail"]))
	{
	$newpass = substr(md5(time()),0,6);
	set_vars("pass='".md5($newpass)."'",$pers["uid"]);
	echo "<center class=return_win><br><br>ВАШ НОВЫЙ ПАРОЛЬ: ".$newpass."<br><br></center>";
	}elseif(empty($_POST["cemail"]))
	{
	unset($_POST);
	echo "<center class=hp>Что-то не совпало, попробуйте ещё раз.</center>";
	}
	else
	{
		$pers = sqla("SELECT uid,user FROM users WHERE email='".$email."'");
		if ($pers)
		{
		//$newpass = substr(md5(time()),0,6);
		//set_vars("pass='".md5($newpass)."',second_pass='',flash_pass=''",$pers["uid"]);
		//send_mail($email, 'Здраствуйте! Вы запросили смену пароля. <hr> <b>Никнэйм: <i>'.$pers["user"].'</i></b> <br> <b>Пароль: <i>'.$newpass.'</i></b><hr><center><a href=http://aloneislands.ru><h2>AloneIslands.Ru</h2></a><br>не нужно отвечать на это письмо</center>', 'robot@aloneislands.ru', 0, 0);
		//echo "<center class=green>Письмо удачно отправлено!</center>";
		echo "<center class=green>Извините сервис не работает...</center>";
		}
		else
			echo "<center class=hp>Извините сервис не работает...</center>";
		
		unset($_POST);
	}
}
if (empty($_POST["email"]))
	echo '
	<form method="POST">
	<table border="1" width="100%" id="table1" cellspacing="0" cellpadding="0" class="weapons_box" bordercolorlight="#E0E0E0" bordercolordark="#FFFFFF">
	<tr>
		<td class="user" align="center">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</td>
	</tr>
	<tr>
		<td align="center">
			Пожалуйста введите E-MAIL на который был зарегистрирован ваш персонаж <br>
			<input type="text" name="email" size="20" class="login">
		</td>
	</tr>
	<tr>
		<td align="center">
			Пожалуйста введите второй пароль от вашего персонажа<br>
			<input type="text" name="spass" size="20" class="login">
		</td>
	</tr>
	<tr>
		<td align="center">
			Пожалуйста введите цифровой пароль если он был установлен<br>
			<input type="text" name="fpass" size="20" class="login">
		</td>
	</tr>
	
	<tr>
		<td align="center" class=but>
			<input type="checkbox" name="cemail" value=1> Выслать пароль на E-Mail. (Достаточно совпадение E-Mail)
		</td>
	</tr>

	<tr>
		<td align="center" class=but2>
			<input type="submit" value="Ок" class="login">
			<input type="reset" value="Сброс" class="login">
		</td>
	</tr>

</table></form>
<hr>
<i> Если второй пароль не был установлен, то восстановление не возможно!</i>';

	
	
?>