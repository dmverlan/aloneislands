<?
error_reporting(0);
$a1 = md5(microtime());
$a2 = time();
include ("inc/functions.php");
include ("inc/sendmail.php");
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $res);

// Проверка существования пользователя для ajax
if (isset($_GET["user_exists"])) {
	$_GET["user_exists"] =iconv("utf-8", "windows-1251", $_GET["user_exists"]);
	$response = sqla("SELECT uid FROM `users` WHERE `smuser`=\"".strtolower($_GET["user_exists"])."\"");
	if ($response === false) print "false"; else print "true";
	exit;
}
$att = '';

if (!empty($_POST)) {
	$_POST["user"] =iconv("utf-8", "windows-1251", $_POST["user"]);
	$_POST["name"] =iconv("utf-8", "windows-1251", $_POST["name"]);
	$_POST["city"] =iconv("utf-8", "windows-1251", $_POST["city"]);
	$_POST["country"] =iconv("utf-8", "windows-1251", $_POST["country"]);
	//print_r(iconv("utf-8", "windows-1251", $_POST["pass"]));
	$_POST["pass"] =iconv("utf-8", "windows-1251", $_POST["pass"]);
	$_POST["pass2"] =iconv("utf-8", "windows-1251", $_POST["pass2"]);
	//print_r($_POST["pass"]);
	$err=0;

	$email = $_POST ["email"];
	if ($email == "" || (!eregi("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$", $email))) {
		$att = "Введите корректный E-mail адрес.";
		$err=1;
	}

	if ($_POST["user"] == "" || strlen($_POST["user"]) < 3 || strlen($_POST["user"]) > 21 || $_POST["user"]=='невидимка') {
		$att = "Введите корректный Логин.";
		$err=1;
	}
	if (!(eregi("^[0-9a-zA-Z]+$", $_POST["user"]) || eregi("^[0-9а-яА-Я]+$", $_POST["user"]))) {
		$att = "Введите корректный логин (нельзя использовать специальные символы, точку, одновременно русские и латинские буквы).";
		$err=1;
	}

	if ($_POST["zakon"] == "") {
		$att = "Вы не согласились с законами.";
		$err=1;
	}

	if ($_POST["pass"] == "" || strlen($_POST["pass"])<6) {
		$att = "Введите корректный пароль (минимум 6 символов).";
		$err=1;
	}

	if ($_POST["pass"] != $_POST["pass2"]) {
		$att = "Пароли не совпадают.";
		$err=1;
	}

	if (@$_COOKIE["hh_reg"]) {
		$att = "Регистрация с одного компьютера только раз в 6 часов!";
		$err=1;
	}

	if ($_POST["check"]<>uncrypt2($_POST["asd1"],$_POST["asd2"])) {$att = "Неверный код."; $err=1;}
	if ($err<>1) {
		$row = sqla ("SELECT * FROM `users` WHERE `smuser`='".(strtolower($_POST['user']))."' or `email`='".(strtolower($_POST['email']))."'");
		if ($row ["user"] != "") {
			$att = "Такой персонаж или e-mail уже существует.";
			$err=1;
		}
		$exp = 0;
		if (@$_COOKIE["referalUID"] && $err != 1) {
			$p = sqla("SELECT uid,user,lastip FROM users WHERE uid=".intval($_COOKIE["referalUID"])."");
			if (!show_ip() or show_ip()==$p["lastip"]) {
				$att =
				"У вас \"нехороший\" IP. (Либо HideIP, либо ваш IP совпадает с персонажем, который привёл вас в игру)";
				$err=1;
			}
			else
			{
				$exp = 100;
			}
		}
		if ($err != 1) {
			$ds=date("d.m.Y H:i");
			$uid = sqla("SELECT MAX(uid) FROM `users`");
			$uid = $uid[0]+1;
			sql ("INSERT INTO `chars` (`uid`) VALUES (".$uid."); ");
			$res = sql ("INSERT INTO `users` ( `user` , `pass` , `city` , `country` , `name` , `dr` , `uid` , `level` , `email` ,`ds` , `pol`,`location`,`smuser`,wears,`zeroing`,`referal_nick`,`referal_uid`,`money`,x,y,`exp`)
			VALUES ('".$_POST['user']."', '".(md5($_POST['pass']))."', '".$_POST['city']."', '".$_POST['country']."', '".$_POST['name']."', '".$_POST['dayd'].".".$_POST['monthd'].".".$_POST['yeard']."', '".$uid."', '0', '".(strtolower($_POST['email']))."' , '".$ds."'  ,'".$_POST["pol"]."','arena',LOWER('".$_POST['user']."'),'none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|',1,'".$p["user"]."','".$p["uid"]."',1,-1,-3,".$exp."); ");
			if (!mysql_error()) {
				$att = ";top.Enter('".$uid."','".md5($_POST['pass'])."');";
				setcookie("hh_reg",1,tme()+21600);
				//send_mail($_POST['email'], 'Вы зарегистрировались в игре <b>AloneIslands</b>. <hr> <b>Никнэйм: <i>'.$_POST['user'].'</i></b> <br> <b>Пароль: <i>'.$_POST['pass'].'</i></b><hr><center><a href=http://aloneislands.ru><h2>AloneIslands.Ru</h2></a><br>не нужно отвечать на это письмо</center>', 'robot@aloneislands.ru');
			} else $att = "<font class=hp>Ошибка в SQL запросе.</font> ";
		}
	}
	if ($res != 1) $att = "<font color=\"red\">".$att."</font>";
	print $att;
	exit;
}

function uncrypt2($value,$key)
{
	$a=0;
	for($i=0;$i<strlen($value);$i++)
		$a += (ord($value[$i])<<(($i+23)>>1)<<1)^($key^9+$i);
	$a %= 10000;
	$a = abs($a);
	if ($a<1000) $a+=2343;
	return $a;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<LINK href="main.css" rel="stylesheet" type="text/css">
	<link rel='shortcut icon' href='images/icon.ico'>
	<title>Alone Islands - Вселенная в твоих руках! - Регистрация</title>
	<meta http-equiv=content-type content='text/html; charset=windows-1251'>
	<script src="js/newmain.js"></script>
	<link rel="stylesheet" href="css/Autocompleter.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="js/mootools.js"></script>
	<script type="text/javascript" src="js/Observer.js"></script>
	<script type="text/javascript" src="js/Autocompleter.js"></script>
	<script type="text/javascript" src="js/countries.js"></script>
	<script type="text/javascript" src="js/cities.js"></script>
	<style type="text/css">
	#log {
		float: center;
		padding: 0.5em;
		margin-left: 10px;
		border: 1px solid #d6d6d6;
		border-left-color: #e4e4e4;
		border-top-color: #e4e4e4;
		margin-top: 10px;
	}
	</style>
</head>
<body>
<!-- Тут был ужоснах... Потихоньку исправляю... (snizovtsev)-->
<script type="text/javascript">
function setPictureStatus(pic, s) {
	var picture = $(pic);
	if (!s) {
		picture.src = 'images/bad.png';
		picture.alt = "Bad";
		fixpng(picture);
	} else {
		picture.src = 'images/ok.png';
		picture.alt = "OK";
		fixpng(picture);
	}
}

function check_user() {
	var inp_user = $('inp_user');
	if (inp_user.value.length < 3 || inp_user.value.length > 21) {
		setPictureStatus('pic_user', false);
		return;
	}
	new Ajax("/reg.php", {
		data: Object.toQueryString({user_exists: inp_user.value}),
			  method: 'get',
			  update: 'ajax_user_response',
			  onComplete: function() {
				  setPictureStatus('pic_user', $('ajax_user_response').innerHTML == 'false');
				  eval($('ajax_user_response').innerHTML);
			  }
	}).request();
}

function check_pass() {
	var res = $('inp_pass').value.length >= 6;
	setPictureStatus('pic_pass', res);
	return res;
}

function check_pass2() {
	var res = ($('inp_pass').value.length >= 6) && ($('inp_pass').value == $('inp_pass2').value);
	setPictureStatus('pic_pass2', res);
	return res;
}

var emailRegex = new RegExp(decode64('KD86W2EtejAtOSEjJCUmJyorLz0/Xl9ge3x9fi1dKyg/OlwuW2EtejAtOSEjJCUmJyorLz0/Xl9ge3x9fi1dKykqfCIoPzpbXHgwMS1ceDA4XHgwYlx4MGNceDBlLVx4MWZceDIxXHgyMy1ceDViXHg1ZC1ceDdmXXxcXFtceDAxLVx4MDlceDBiXHgwY1x4MGUtXHg3Zl0pKiIpQCg/Oig/OlthLXowLTldKD86W2EtejAtOS1dKlthLXowLTldKT9cLikrW2EtejAtOV0oPzpbYS16MC05LV0qW2EtejAtOV0pP3xcWyg/Oig/OjI1WzAtNV18MlswLTRdWzAtOV18WzAxXT9bMC05XVswLTldPylcLil7M30oPzoyNVswLTVdfDJbMC00XVswLTldfFswMV0/WzAtOV1bMC05XT98W2EtejAtOS1dKlthLXowLTldOig/OltceDAxLVx4MDhceDBiXHgwY1x4MGUtXHgxZlx4MjEtXHg1YVx4NTMtXHg3Zl18XFxbXHgwMS1ceDA5XHgwYlx4MGNceDBlLVx4N2ZdKSspXF0p'));

function check_email() {
	var res = emailRegex.test($('inp_email').value)
	setPictureStatus('pic_email', res);
	return res;
}
</script>
<div id="ajax_user_response" style="visibility: hidden; position: absolute;">false</div>
<form method="post" id="form_reg" action="reg.php">
<table border="0" width="100%">
<tr>
	<td class="ma" width="40%"> Логин</td>
	<td><input type="text" name="user" style="width: 100%" class="login" id="inp_user" onchange="check_user()"></td>
	<td width="25px"><img src="images/bad.png" id="pic_user" alt="Status" onload="fixpng(this);"></td>
</tr>
<tr>
	<td class="items">Пароль</td>
	<td><input type="password" name="pass" style="width: 100%;" class="login" onkeyup="check_pass()" onchange="check_pass()" id="inp_pass"></td>
	<td><img src="images/bad.png" alt="Status" id="pic_pass" onload="fixpng(this);"></td>
</tr>
<tr>
	<td class="items">Пароль ещё раз</td>
	<td><input type="password" name="pass2" style="width: 100%" class="login" onkeyup="check_pass2()" onchange="check_pass2()" id="inp_pass2"></td>
	<td><img src="images/bad.png" alt="Status" id="pic_pass2" onload="fixpng(this);"></td>
</tr>
<tr>
	<td class="items"> <p><span lang="en-us" class="hp">E-Mail</span></p></td>
	<td><input type="text" name="email" style="width: 100%;" class="login" onkeyup="check_email()" onchange="check_email()" id="inp_email"></td>
	<td><img src="images/bad.png" alt="Status" id="pic_email" onload="fixpng(this);"></td>
</tr>
<tr>
	<td class="items">Пол</td>
	<td>
	<select size="1" name="pol" class="items" style="width: 100%">
	<option selected value="male">Мужской</option>
	<option value="female">Женский</option> </select>
	</td>
	<td></td>
</tr>
<tr>
	<td class="items">Дата рождения</td>
	<td>
	<select name="dayd" class="items">
	<?		for ($i=1;$i<32;$i++) echo  "<option value=".$i.">".$i."</option>\n"; ?>
	</select>
	<select name="monthd" class="items">
	<?		for ($i=1;$i<13;$i++) echo  "<option value=".$i.">".$i."</option>\n"; ?>
	</select>
	<select name="yeard" class="items">
	<?		for ($i=1959;$i<2000;$i++) echo  "<option value=".$i.">".$i."</option>\n"; ?>
	</select>
	</td>
	<td></td>
</tr>
<tr>
	<td class="items"></td>
	<td><input name="name" style="width: 100%;" class="login" type=hidden></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="items"></td>
	<td><input name="country" style="width: 100%" class="login" id="inp_country" type=hidden></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="items"></td>
	<td><input name="city" style="width: 100%;" class="login" id="inp_city" type=hidden></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="items">Цифры на картинке</td>
	<td>
	<table width="100%"><tr>
		<td width="45px"><img border="0" src="http://aloneislands.ru/check.php?a1=<?=$a1?>&a2=<?=$a2?>" alt="Код" style="width: 100px;" id=captcha></td>
		<td>
			<input type="text" name="check" size="8" class="login" maxlength="4" style="width: 100%;">
			<input type="hidden" name="asd1" size="8" class="login" value="<?=$a1;?>">
			<input type="hidden" name="asd2" size="8" class="login" value="<?=$a2;?>">
			<a href="javascript:ch_cpth()" class=timef>обновить</a>
		</td>
	</tr></table>
	</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="items">Я согласен с <a href="justice.htm" target="_blank"> законами игры</a></td>
	<td><input type="checkbox" name="zakon" value=1></td>
	<td>&nbsp;</td>
</tr>
</table>
<div align="center"><input type="submit" value="Готово" class="login" style="width:80%"></div>
<div id="log" style="visibility: hidden;"></div><BR>
</form>

<script type="text/javascript">
window.addEvent('domready', function(){
	var inp_country = $('inp_country');
	var completer1 = new Autocompleter.Local(inp_country, countries, {
		'delay': 100,
		'filterTokens': function() {
			var regex = new RegExp('^' + this.queryValue.escapeRegExp(), 'i');
			return this.tokens.filter(function(token){
				return regex.test(token);
			});
		},
		'injectChoice': function(choice) {
			var el = new Element('li').setHTML(this.markQueryValue(choice));
			el.inputValue = choice;
			this.addChoiceEvents(el).injectInside(this.choices);
		}
	});

	var inp_city = $('inp_city');
	var completer2 = new Autocompleter.Local(inp_city, cities, {
		'delay': 100,
		'filterTokens': function() {
			var regex = new RegExp('^' + this.queryValue.escapeRegExp(), 'i');
			return this.tokens.filter(function(token){
				return regex.test(token);
			});
		},
		'injectChoice': function(choice) {
			var el = new Element('li').setHTML(this.markQueryValue(choice));
			el.inputValue = choice;
			this.addChoiceEvents(el).injectInside(this.choices);
		}
	});

	// Перехватываем submit формы для ajax запроса
	$('form_reg').addEvent('submit', function(e) {
		// Отсанавливаем другие event'ы, чтобы запретить сабмит без ajax
		new Event(e).stop();

		// Показываем индикатор загрузки
		var log = $('log').empty().setHTML('<center><img src="images/spinner.gif" alt="Подождите"></center>');
		log.style.visibility = 'visible';

		// Посылаем запрос ajax-ом
		this.send({update: 'log' , onComplete: function() {if($('log').innerHTML.substr(0,1)==';'){eval($('log').innerHTML);$('log').innerHTML = '<font color=green>Спасибо за регистрацию.</font>';}}});
	});
});
function ch_cpth()
{
	document.getElementById('captcha').src += '&a=1';
}
</script>
</body>
</html>
