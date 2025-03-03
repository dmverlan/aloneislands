<?php
// Защита от прямого доступа к файлу
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

// Включаем отображение ошибок для разработки (в продакшене заменить на логирование)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Подключаем зависимости
require_once 'inc/functions.php'; // Вспомогательные функции
require_once 'inc/sendmail.php';  // Функция отправки писем
require_once 'configs/config.php'; // Конфигурация базы данных

// Инициализируем сессию
session_start();

// Подключаемся к базе данных с использованием PDO
try {
    $db = new PDO(
        "mysql:host=$mysqlhost;dbname=$mysqlbase;charset=utf8",
        $mysqluser,
        $mysqlpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Выбрасывать исключения при ошибках
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Ассоциативные массивы по умолчанию
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Проверка существования пользователя для AJAX-запроса
if (isset($_GET['user_exists'])) {
    $user = $_GET['user_exists'];
    $stmt = $db->prepare("SELECT uid FROM users WHERE smuser = :smuser");
    $stmt->execute([':smuser' => strtolower($user)]);
    $response = $stmt->fetch();
    echo $response ? 'true' : 'false';
    exit;
}

// Генерация капчи
if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT); // Случайный 4-значный код
}

// Обработка формы регистрации
if (!empty($_POST)) {
    $err = 0;
    $att = ''; // Сообщение об ошибке или успехе

    // Валидация email
    $email = strtolower($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $att = "Введите корректный E-mail адрес.";
        $err = 1;
    }

    // Валидация логина
    $user = $_POST['user'] ?? '';
    if (empty($user) || strlen($user) < 3 || strlen($user) > 21 || $user === 'невидимка') {
        $att = "Введите корректный Логин (3-21 символ, не 'невидимка').";
        $err = 1;
    }
    if (!preg_match('/^[0-9a-zA-Z]+$/', $user) && !preg_match('/^[0-9а-яА-Я]+$/', $user)) {
        $att = "Логин не должен содержать спецсимволы, точку или смешивать русские и латинские буквы.";
        $err = 1;
    }

    // Проверка согласия с законами
    if (empty($_POST['zakon'])) {
        $att = "Вы не согласились с законами.";
        $err = 1;
    }

    // Валидация пароля
    $pass = $_POST['pass'] ?? '';
    if (empty($pass) || strlen($pass) < 6) {
        $att = "Введите корректный пароль (минимум 6 символов).";
        $err = 1;
    }
    if ($pass !== ($_POST['pass2'] ?? '')) {
        $att = "Пароли не совпадают.";
        $err = 1;
    }

    // Проверка ограничения регистрации с одного компьютера
    if (isset($_COOKIE['hh_reg'])) {
        $att = "Регистрация с одного компьютера возможна только раз в 6 часов!";
        $err = 1;
    }

    // Проверка капчи
    if (($_POST['check'] ?? '') !== ($_SESSION['captcha_code'] ?? '')) {
        $att = "Неверный код с картинки.";
        $err = 1;
    }

    // Проверка уникальности пользователя и email
    if ($err != 1) {
        $stmt = $db->prepare("SELECT user FROM users WHERE smuser = :smuser OR email = :email");
        $stmt->execute([':smuser' => strtolower($user), ':email' => $email]);
        if ($stmt->fetch()) {
            $att = "Такой персонаж или e-mail уже существует.";
            $err = 1;
        }
    }

    // Проверка реферала
    $exp = 0;
    $referrer = null;
    if (isset($_COOKIE['referalUID']) && $err != 1) {
        $stmt = $db->prepare("SELECT uid, user, lastip FROM users WHERE uid = :uid");
        $stmt->execute([':uid' => (int)$_COOKIE['referalUID']]);
        $referrer = $stmt->fetch();
        if (!$referrer || show_ip() === $referrer['lastip']) {
            $att = "У вас 'нехороший' IP (HideIP или совпадение с рефералом).";
            $err = 1;
        } else {
            $exp = 100; // Бонусный опыт за реферала
        }
    }

    // Регистрация нового пользователя
    if ($err != 1) {
        $ds = date('d.m.Y H:i'); // Дата регистрации
        $uidStmt = $db->query("SELECT MAX(uid) FROM users");
        $uid = ($uidStmt->fetchColumn() ?? 0) + 1;
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // Хешируем пароль

        // Вставка в таблицу chars
        $stmt = $db->prepare("INSERT INTO chars (uid) VALUES (:uid)");
        $stmt->execute([':uid' => $uid]);

        // Вставка в таблицу users
        $stmt = $db->prepare("
            INSERT INTO users (
                user, pass, city, country, name, dr, uid, level, email, ds, pol, location, smuser, 
                wears, zeroing, referal_nick, referal_uid, money, x, y, exp
            ) VALUES (
                :user, :pass, :city, :country, :name, :dr, :uid, 0, :email, :ds, :pol, 'arena', 
                LOWER(:smuser), 'none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none|none', 
                1, :referal_nick, :referal_uid, 1, -1, -3, :exp
            )
        ");
        $stmt->execute([
            ':user' => $user,
            ':pass' => $hashedPass,
            ':city' => $_POST['city'] ?? '',
            ':country' => $_POST['country'] ?? '',
            ':name' => $_POST['name'] ?? '',
            ':dr' => sprintf('%s.%s.%s', $_POST['dayd'] ?? '', $_POST['monthd'] ?? '', $_POST['yeard'] ?? ''),
            ':uid' => $uid,
            ':email' => $email,
            ':ds' => $ds,
            ':pol' => $_POST['pol'] ?? 'male',
            ':smuser' => $user,
            ':referal_nick' => $referrer['user'] ?? '',
            ':referal_uid' => $referrer['uid'] ?? null,
            ':exp' => $exp
        ]);

        // Успешная регистрация
        $att = ";top.Enter('$uid', '$hashedPass');"; // Передаем хеш для входа
        setcookie('hh_reg', 1, time() + 21600, '/'); // Ограничение на 6 часов
        unset($_SESSION['captcha_code']); // Сбрасываем капчу после успешной регистрации
        // Раскомментировать для отправки письма
        // send_mail($email, "Вы зарегистрировались в игре <b>AloneIslands</b>. <hr> <b>Никнэйм: <i>$user</i></b> <br> <b>Пароль: <i>$pass</i></b><hr><center><a href=http://aloneislands.ru><h2>AloneIslands.Ru</h2></a></center>", 'robot@aloneislands.ru');
    }

    // Вывод результата
    echo $err == 1 ? "<font color=\"red\">$att</font>" : $att;
    exit;
}

// Функция для генерации изображения капчи
function generateCaptchaImage($code) {
    $image = imagecreatetruecolor(100, 40);
    $bgColor = imagecolorallocate($image, 255, 255, 255); // Белый фон
    $textColor = imagecolorallocate($image, 0, 0, 0); // Черный текст
    imagefill($image, 0, 0, $bgColor);

    // Добавляем шум (случайные линии)
    for ($i = 0; $i < 5; $i++) {
        $lineColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
        imageline($image, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 40), $lineColor);
    }

    // Пишем код
    imagestring($image, 5, 30, 10, $code, $textColor);

    // Выводим изображение
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
    exit;
}

// Обработка запроса на изображение капчи
if (isset($_GET['captcha'])) {
    generateCaptchaImage($_SESSION['captcha_code']);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Alone Islands - Вселенная в твоих руках! - Регистрация</title>
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="css/Autocompleter.css" type="text/css" media="screen" />
    <script src="js/newmain.js"></script>
    <script src="js/mootools.js"></script>
    <script src="js/Observer.js"></script>
    <script src="js/Autocompleter.js"></script>
    <script src="js/countries.js"></script>
    <script src="js/cities.js"></script>
    <style>
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
    <div id="ajax_user_response" style="visibility: hidden; position: absolute;">false</div>
    <form method="post" id="form_reg" action="reg.php">
        <table border="0" width="100%">
            <tr>
                <td class="ma" width="40%">Логин</td>
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
                <td class="items"><p><span lang="en-us" class="hp">E-Mail</span></p></td>
                <td><input type="text" name="email" style="width: 100%;" class="login" onkeyup="check_email()" onchange="check_email()" id="inp_email"></td>
                <td><img src="images/bad.png" alt="Status" id="pic_email" onload="fixpng(this);"></td>
            </tr>
            <tr>
                <td class="items">Пол</td>
                <td>
                    <select size="1" name="pol" class="items" style="width: 100%">
                        <option selected value="male">Мужской</option>
                        <option value="female">Женский</option>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="items">Дата рождения</td>
                <td>
                    <select name="dayd" class="items">
                        <?php for ($i = 1; $i < 32; $i++) echo "<option value=\"$i\">$i</option>\n"; ?>
                    </select>
                    <select name="monthd" class="items">
                        <?php for ($i = 1; $i < 13; $i++) echo "<option value=\"$i\">$i</option>\n"; ?>
                    </select>
                    <select name="yeard" class="items">
                        <?php for ($i = 1959; $i < 2000; $i++) echo "<option value=\"$i\">$i</option>\n"; ?>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr><td class="items"></td><td><input name="name" style="width: 100%;" class="login" type="hidden"></td><td></td></tr>
            <tr><td class="items"></td><td><input name="country" style="width: 100%" class="login" id="inp_country" type="hidden"></td><td></td></tr>
            <tr><td class="items"></td><td><input name="city" style="width: 100%;" class="login" id="inp_city" type="hidden"></td><td></td></tr>
            <tr>
                <td class="items">Цифры на картинке</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="45px"><img border="0" src="reg.php?captcha=1" alt="Код" style="width: 100px;" id="captcha"></td>
                            <td>
                                <input type="text" name="check" size="8" class="login" maxlength="4" style="width: 100%;">
                                <a href="javascript:ch_cpth()" class="timef">обновить</a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="items">Я согласен с <a href="justice.htm" target="_blank">законами игры</a></td>
                <td><input type="checkbox" name="zakon" value="1"></td>
                <td></td>
            </tr>
        </table>
        <div align="center"><input type="submit" value="Готово" class="login" style="width: 80%"></div>
        <div id="log" style="visibility: hidden;"></div><br>
    </form>

    <script type="text/javascript">
    // Обновленные скрипты для валидации и AJAX
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

    var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    function check_email() {
        var res = emailRegex.test($('inp_email').value);
        setPictureStatus('pic_email', res);
        return res;
    }

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

        $('form_reg').addEvent('submit', function(e) {
            new Event(e).stop();
            var log = $('log').empty().setHTML('<center><img src="images/spinner.gif" alt="Подождите"></center>');
            log.style.visibility = 'visible';
            this.send({update: 'log', onComplete: function() {
                if ($('log').innerHTML.substr(0,1) == ';') {
                    eval($('log').innerHTML);
                    $('log').innerHTML = '<font color=green>Спасибо за регистрацию.</font>';
                }
            }});
        });
    });

    function ch_cpth() {
        document.getElementById('captcha').src = 'reg.php?captcha=1&' + new Date().getTime(); // Добавляем timestamp для обновления
    }
    </script>
</body>
</html>