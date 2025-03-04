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

// Обработка формы
if (!empty($_POST)) {
    $err = 0;
    $att = '';

    // Валидация CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $csrfToken) {
        $att = "Ошибка безопасности. Попробуйте снова.";
        $err = 1;
    }

    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $spass = $_POST['spass'] ?? '';
    $fpass = filter_var($_POST['fpass'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    $cemail = isset($_POST['cemail']) && $_POST['cemail'] == 1;

    if (!$err && empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $att = "Введите корректный email.";
        $err = 1;
    }
    if (!$err && empty($spass)) {
        $att = "Введите второй пароль.";
        $err = 1;
    }

    if (!$err) {
        $stmt = $db->prepare("SELECT uid, user FROM users WHERE email = :email AND second_pass = :spass AND flash_pass = :fpass");
        $stmt->execute([':email' => $email, ':spass' => md5($spass), ':fpass' => (int)$fpass]);
        $pers = $stmt->fetch();

        if ($pers && !$cemail) {
            $newpass = substr(bin2hex(random_bytes(3)), 0, 6); // Случайный 6-символьный пароль
            $hashedPass = password_hash($newpass, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE uid = :uid");
            $stmt->execute([':pass' => $hashedPass, ':uid' => $pers['uid']]);
            $att = "<center class=\"return_win\"><br><br>ВАШ НОВЫЙ ПАРОЛЬ: $newpass<br><br></center>";
        } elseif ($pers && $cemail) {
            // Временно отключено, можно раскомментировать для отправки email
            /*
            $newpass = substr(bin2hex(random_bytes(3)), 0, 6);
            $hashedPass = password_hash($newpass, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET pass = :pass, second_pass = '', flash_pass = '' WHERE uid = :uid");
            $stmt->execute([':pass' => $hashedPass, ':uid' => $pers['uid']]);
            send_mail($email, "Здравствуйте! Вы запросили смену пароля.\n\nНикнэйм: {$pers['user']}\nПароль: $newpass\n\n<a href=\"http://aloneislands.ru\">AloneIslands.Ru</a>\nНе отвечайте на это письмо.", 'robot@aloneislands.ru');
            $att = "<center class=\"green\">Письмо успешно отправлено!</center>";
            */
            $att = "<center class=\"green\">Извините, сервис отправки email временно отключен.</center>";
        } else {
            $att = "<center class=\"hp\">Что-то не совпало, попробуйте ещё раз.</center>";
            $err = 1;
        }
    }

    echo $att;
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alone Islands - Восстановление пароля</title>
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <table border="1" width="100%" class="weapons_box" bordercolorlight="#E0E0E0" bordercolordark="#FFFFFF">
            <tr>
                <td class="user" align="center">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</td>
            </tr>
            <tr>
                <td align="center">
                    Пожалуйста, введите E-MAIL, на который был зарегистрирован ваш персонаж<br>
                    <input type="email" name="email" size="20" class="login" required>
                </td>
            </tr>
            <tr>
                <td align="center">
                    Пожалуйста, введите второй пароль от вашего персонажа<br>
                    <input type="text" name="spass" size="20" class="login" required>
                </td>
            </tr>
            <tr>
                <td align="center">
                    Пожалуйста, введите цифровой пароль, если он был установлен<br>
                    <input type="number" name="fpass" size="20" class="login" value="0">
                </td>
            </tr>
            <tr>
                <td align="center" class="but">
                    <input type="checkbox" name="cemail" value="1"> Выслать пароль на E-Mail (достаточно совпадения E-Mail)
                </td>
            </tr>
            <tr>
                <td align="center" class="but2">
                    <input type="submit" value="Ок" class="login">
                    <input type="reset" value="Сброс" class="login">
                </td>
            </tr>
        </table>
    </form>
    <hr>
    <p><i>Если второй пароль не был установлен, восстановление невозможно!</i></p>
</body>
</html>