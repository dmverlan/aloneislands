<?php
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

require_once 'inc/functions.php';
require_once 'inc/sendmail.php';
require_once 'configs/config.php';
require_once 'db.php';

session_start();
$db = getDatabaseConnection();
$currentTime = time();

// CSRF-токен
define('CSRF_TOKEN_LIFETIME_SECONDS', 3600);
function generateCsrfToken($currentTime) {
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || $_SESSION['csrf_time'] < $currentTime - CSRF_TOKEN_LIFETIME_SECONDS) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_time'] = $currentTime;
    }
    return $_SESSION['csrf_token'];
}
$csrfToken = generateCsrfToken($currentTime);

// Проверка существования пользователя (AJAX)
if (isset($_GET['user_exists'])) {
    $user = filter_var($_GET['user_exists'], FILTER_SANITIZE_STRING);
    $stmt = $db->prepare("SELECT uid FROM users WHERE smuser = :smuser");
    $stmt->execute([':smuser' => strtolower($user)]);
    echo $stmt->fetch() ? 'true' : 'false';
    exit;
}

// Генерация капчи
if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
}

// Обработка формы
if (!empty($_POST)) {
    $err = 0;
    $att = '';

    // Валидация CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $csrfToken) {
        $att = "Ошибка безопасности. Попробуйте снова.";
        $err = 1;
    }

    $email = strtolower($_POST['email'] ?? '');
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';
    $zakon = $_POST['zakon'] ?? '';
    $check = $_POST['check'] ?? '';

    if (!$err && (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email))) {
        $att = "Введите корректный E-mail адрес.";
        $err = 1;
    }
    if (!$err && (empty($user) || strlen($user) < 3 || strlen($user) > 21 || strtolower($user) === 'невидимка' || !preg_match('/^[0-9a-zA-Zа-яА-Я]+$/', $user))) {
        $att = "Введите корректный Логин (3-21 символ, без спецсимволов и 'невидимка').";
        $err = 1;
    }
    if (!$err && empty($zakon)) {
        $att = "Вы не согласились с законами.";
        $err = 1;
    }
    if (!$err && (empty($pass) || strlen($pass) < 6 || $pass !== $pass2)) {
        $att = empty($pass) || strlen($pass) < 6 ? "Введите корректный пароль (минимум 6 символов)." : "Пароли не совпадают.";
        $err = 1;
    }
    if (!$err && $check !== $_SESSION['captcha_code']) {
        $att = "Неверный код с картинки.";
        $err = 1;
    }
    if (!$err && isset($_COOKIE['hh_reg'])) {
        $att = "Регистрация с одного компьютера возможна только раз в 6 часов!";
        $err = 1;
    }

    if (!$err) {
        $stmt = $db->prepare("SELECT user FROM users WHERE smuser = :smuser OR email = :email");
        $stmt->execute([':smuser' => strtolower($user), ':email' => $email]);
        if ($stmt->fetch()) {
            $att = "Такой персонаж или e-mail уже существует.";
            $err = 1;
        }
    }

    $exp = 0;
    $referrer = null;
    if (!$err && isset($_COOKIE['referalUID'])) {
        $stmt = $db->prepare("SELECT uid, user, lastip FROM users WHERE uid = :uid");
        $stmt->execute([':uid' => (int)$_COOKIE['referalUID']]);
        $referrer = $stmt->fetch();
        if (!$referrer || show_ip() === $referrer['lastip']) {
            $att = "У вас 'нехороший' IP (HideIP или совпадение с рефералом).";
            $err = 1;
        } else {
            $exp = 100;
        }
    }

    if (!$err) {
        $ds = date('d.m.Y H:i', $currentTime);
        $uidStmt = $db->query("SELECT MAX(uid) FROM users");
        $uid = ($uidStmt->fetchColumn() ?? 0) + 1;
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO chars (uid) VALUES (:uid)");
            $stmt->execute([':uid' => $uid]);

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
                ':city' => filter_var($_POST['city'] ?? '', FILTER_SANITIZE_STRING),
                ':country' => filter_var($_POST['country'] ?? '', FILTER_SANITIZE_STRING),
                ':name' => filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING),
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

            $db->commit();
            $att = ";top.Enter('$uid', '$hashedPass');";
            setcookie('hh_reg', 1, $currentTime + 21600, '/', '', false, true); // Добавлен HttpOnly
            unset($_SESSION['captcha_code']);
        } catch (Exception $e) {
            $db->rollBack();
            $att = "Ошибка регистрации: " . $e->getMessage();
            $err = 1;
        }
    }

    echo $err == 1 ? "<font color=\"red\">$att</font>" : $att;
    exit;
}

// Генерация капчи (вынесено в отдельный маршрут)
if (isset($_GET['captcha'])) {
    $image = imagecreatetruecolor(100, 40);
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    imagefill($image, 0, 0, $bgColor);
    for ($i = 0; $i < 5; $i++) {
        $lineColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
        imageline($image, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 40), $lineColor);
    }
    imagestring($image, 5, 30, 10, $_SESSION['captcha_code'], $textColor);
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alone Islands - Регистрация</title>
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="css/Autocompleter.css">
    <script src="js/newmain.js"></script>
    <script src="js/mootools.js"></script>
    <script src="js/Observer.js"></script>
    <script src="js/Autocompleter.js"></script>
    <script src="js/countries.js"></script>
    <script src="js/cities.js"></script>
    <style>
        #log {
            padding: 0.5em;
            margin: 10px auto;
            border: 1px solid #d6d6d6;
            border-left-color: #e4e4e4;
            border-top-color: #e4e4e4;
            text-align: center;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div id="ajax_user_response" style="display: none;">false</div>
    <form method="post" id="form_reg" action="reg.php">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <table border="0" width="100%">
            <tr>
                <td class="ma" width="40%">Логин</td>
                <td><input type="text" name="user" style="width: 100%" class="login" id="inp_user" onchange="check_user()" required></td>
                <td width="25px"><img src="images/bad.png" id="pic_user" alt="Статус" onload="fixpng(this);"></td>
            </tr>
            <tr>
                <td class="items">Пароль</td>
                <td><input type="password" name="pass" style="width: 100%;" class="login" onkeyup="check_pass()" onchange="check_pass()" id="inp_pass" required></td>
                <td><img src="images/bad.png" alt="Статус" id="pic_pass" onload="fixpng(this);"></td>
            </tr>
            <tr>
                <td class="items">Пароль ещё раз</td>
                <td><input type="password" name="pass2" style="width: 100%" class="login" onkeyup="check_pass2()" onchange="check_pass2()" id="inp_pass2" required></td>
                <td><img src="images/bad.png" alt="Статус" id="pic_pass2" onload="fixpng(this);"></td>
            </tr>
            <tr>
                <td class="items"><span lang="en-us" class="hp">E-Mail</span></td>
                <td><input type="email" name="email" style="width: 100%;" class="login" onkeyup="check_email()" onchange="check_email()" id="inp_email" required></td>
                <td><img src="images/bad.png" alt="Статус" id="pic_email" onload="fixpng(this);"></td>
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
                        <?php for ($i = 1; $i < 32; $i++) echo "<option value=\"$i\">$i</option>"; ?>
                    </select>
                    <select name="monthd" class="items">
                        <?php for ($i = 1; $i < 13; $i++) echo "<option value=\"$i\">$i</option>"; ?>
                    </select>
                    <select name="yeard" class="items">
                        <?php for ($i = 1959; $i < 2000; $i++) echo "<option value=\"$i\">$i</option>"; ?>
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
                                <input type="text" name="check" size="8" class="login" maxlength="4" style="width: 100%;" required>
                                <a href="javascript:ch_cpth()" class="timef">обновить</a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="items">Я согласен с <a href="justice.htm" target="_blank">законами игры</a></td>
                <td><input type="checkbox" name="zakon" value="1" required></td>
                <td></td>
            </tr>
        </table>
        <div align="center"><input type="submit" value="Готово" class="login" style="width: 80%"></div>
        <div id="log" style="display: none;"></div><br>
    </form>

    <script type="text/javascript">
    function setPictureStatus(pic, s) {
        const picture = document.getElementById(pic);
        picture.src = s ? 'images/ok.png' : 'images/bad.png';
        picture.alt = s ? 'OK' : 'Bad';
        fixpng(picture);
    }

    function check_user() {
        const inp_user = document.getElementById('inp_user');
        if (inp_user.value.length < 3 || inp_user.value.length > 21) {
            setPictureStatus('pic_user', false);
            return;
        }
        fetch(`/reg.php?user_exists=${encodeURIComponent(inp_user.value)}`)
            .then(response => response.text())
            .then(data => {
                setPictureStatus('pic_user', data === 'false');
                document.getElementById('ajax_user_response').innerHTML = data;
            })
            .catch(err => console.error('Ошибка проверки пользователя:', err));
    }

    function check_pass() {
        const res = document.getElementById('inp_pass').value.length >= 6;
        setPictureStatus('pic_pass', res);
        return res;
    }

    function check_pass2() {
        const inpPass = document.getElementById('inp_pass').value;
        const inpPass2 = document.getElementById('inp_pass2').value;
        const res = inpPass.length >= 6 && inpPass === inpPass2;
        setPictureStatus('pic_pass2', res);
        return res;
    }

    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    function check_email() {
        const res = emailRegex.test(document.getElementById('inp_email').value);
        setPictureStatus('pic_email', res);
        return res;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_reg');
        const log = document.getElementById('log');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            log.innerHTML = '<center><img src="images/spinner.gif" alt="Подождите"></center>';
            log.style.display = 'block';
            fetch('reg.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.text())
            .then(data => {
                log.innerHTML = data.startsWith(';') ? '<font color="green">Спасибо за регистрацию.</font>' : data;
                if (data.startsWith(';')) eval(data);
            })
            .catch(err => {
                log.innerHTML = '<font color="red">Ошибка: ' + err.message + '</font>';
            });
        });

        // Автозаполнение логина из cookie
        const loginInput = document.querySelector('input[name="user"]');
        const savedLogin = readCookie('uid');
        if (savedLogin) loginInput.value = savedLogin;
    });

    function ch_cpth() {
        document.getElementById('captcha').src = 'reg.php?captcha=1&' + Date.now();
    }
    </script>
</body>
</html>