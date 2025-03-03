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

// Проверяем погоду в игровом мире
$stmt = $db->query("SELECT weather, weatherchange FROM world LIMIT 1");
$world = $stmt->fetch();
if ($world && $world['weatherchange'] < time()) {
    say_to_chat("#W", "#W", 0, '', '*'); // Уведомление в чат об изменении погоды
}

// Авторизация пользователя
$pers = null;
if (!empty($_POST['pass'])) {
    // Вход через форму с паролем (из index.php)
    $stmt = $db->prepare("SELECT * FROM users WHERE user = :user");
    $stmt->execute([':user' => $_POST['user']]);
    $pers = $stmt->fetch();

    if ($pers) {
        // Проверяем пароль
        if (password_verify($_POST['pass'], $pers['pass'])) {
            // Пароль уже в формате password_hash
        } elseif (md5($_POST['pass']) === $pers['pass']) {
            // Старый md5-хеш, обновляем на новый формат
            $newHash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE uid = :uid");
            $stmt->execute([':pass' => $newHash, ':uid' => $pers['uid']]);
            $pers['pass'] = $newHash; // Обновляем в текущей сессии
        } else {
            $pers = null; // Неверный пароль
        }
    }
} elseif (!empty($_POST['passnmd'])) {
    // Вход с хешированным паролем (для обратной совместимости)
    $stmt = $db->prepare("SELECT * FROM users WHERE user = :user");
    $stmt->execute([':user' => $_POST['user']]);
    $pers = $stmt->fetch();

    if ($pers && password_verify($_POST['passnmd'], $pers['pass'])) {
        // Пароль уже в новом формате (хотя passnmd обычно md5, оставим для совместимости)
    } elseif ($pers && $_POST['passnmd'] === $pers['pass']) {
        // Старый md5-хеш, обновляем
        $newHash = password_hash($_POST['passnmd'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE uid = :uid");
        $stmt->execute([':pass' => $newHash, ':uid' => $pers['uid']]);
        $pers['pass'] = $newHash;
    } else {
        $pers = null;
    }
} elseif (empty($_POST) && isset($_COOKIE['uid']) && isset($_COOKIE['hashcode'])) {
    // Вход через куки
    $stmt = $db->prepare("SELECT * FROM users WHERE uid = :uid");
    $stmt->execute([':uid' => (int)$_COOKIE['uid']]);
    $pers = $stmt->fetch();

    if ($pers && password_verify($_COOKIE['hashcode'], $pers['pass'])) {
        // Новый формат пароля
    } elseif ($pers && $_COOKIE['hashcode'] === $pers['pass']) {
        // Старый md5-хеш, обновляем
        $newHash = password_hash($_COOKIE['hashcode'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE uid = :uid");
        $stmt->execute([':pass' => $newHash, ':uid' => $pers['uid']]);
        $pers['pass'] = $newHash;
    } else {
        $pers = null;
    }
}

// Проверяем результат авторизации
$err = 1; // Ошибка по умолчанию
if ($pers && isset($pers['uid'])) {
    $err = 0; // Успех
}

// Проверяем второй пароль (дополнительная защита)
if ($pers && $pers['flash_pass']) {
    if (empty($_POST['spass'])) {
        include 'second_password.php'; // Запрашиваем второй пароль
        exit;
    } elseif ($pers['flash_pass'] !== $_POST['spass']) {
        $err = 1; // Неверный второй пароль
    }
}

// Проверяем блокировку аккаунта
if ($pers && !empty($pers['block'])) {
    $err = 2; // Пользователь заблокирован
}

// Обрабатываем ошибки авторизации
if ($err == 1) {
    $_GET['error'] = 'login'; // Неверные данные
    include 'index.php';
    exit;
} elseif ($err == 2) {
    $_GET['error'] = 'block'; // Аккаунт заблокирован
    include 'index.php';
    exit;
}

// Логируем попытку входа с одного устройства
if (!empty($_COOKIE['uid']) && $_COOKIE['uid'] != $pers['uid'] && $pers['uid']) {
    $stmt = $db->prepare("INSERT INTO one_comp_logins (uid1, uid2, time) VALUES (:uid1, :uid2, :time)");
    $stmt->execute([
        ':uid1' => (int)$_COOKIE['uid'],
        ':uid2' => (int)$pers['uid'],
        ':time' => time()
    ]);
}

// Устанавливаем куки для автоматического входа (30 дней)
$cookieLifetime = time() + 30 * 24 * 3600;
setcookie('uid', $pers['uid'], $cookieLifetime, '/');
setcookie('hashcode', $pers['pass'], $cookieLifetime, '/'); // Теперь это хеш password_hash
setcookie('nick', $pers['user'], $cookieLifetime, '/');
setcookie('options', $pers['options'], $cookieLifetime, '/');
setcookie('spass', $pers['flash_pass'], $cookieLifetime, '/');

// Обновляем данные пользователя
$lastVisit = date('d.m.Y H:i');
$chatLastId = (int)$db->query("SELECT MAX(id) FROM chat")->fetchColumn();
$stmt = $db->prepare("
    UPDATE users 
    SET lastip = :ip, 
        lastvisit = :visit, 
        lastvisits = :time, 
        lasto = :time, 
        online = 1, 
        chat_last_id = :chat 
    WHERE uid = :uid
");
$stmt->execute([
    ':ip' => show_ip(), // Функция из functions.php
    ':visit' => $lastVisit,
    ':time' => time(),
    ':chat' => $chatLastId,
    ':uid' => $pers['uid']
]);

// Логируем IP-адрес входа
$stmt = $db->prepare("INSERT INTO ips_in (uid, ip, date) VALUES (:uid, :ip, :time)");
$stmt->execute([
    ':uid' => $pers['uid'],
    ':ip' => show_ip(),
    ':time' => time()
]);

// Определяем день или ночь для отображения
$night = (date('H') > 6 && date('H') < 22) ? 0 : 1;

// Выводим HTML-страницу игры
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>AloneIslands[<?= htmlspecialchars($pers['user']) ?>]</title>
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" href="main.css">
    <script src="js/cookie.js" defer></script>
    <script src="js/jquery.js" defer></script>
    <script src="js/game.js?2" defer></script>
</head>
<body style="overflow: hidden;">
    <script>
        // Передаем серверные данные в JavaScript
        const today = new Date();
        var hours = <?= json_encode(date('H')) ?>;
        var minutes = <?= json_encode(date('i')) ?>;
        var seconds = <?= json_encode(date('s')) ?>;
        var ctip = <?= json_encode((int)$pers['ctip']) ?>;
        var SoundsOn = <?= json_encode($pers['sound'] == 1 ? 0 : 1) ?>;
        view_frames(<?= json_encode($night) ?>);
    </script>

    <?php
    // Приветствуем нового игрока
    if ($pers['lasto'] == 0) {
        say_to_chat('a', '<center class="return_win"><b>Приветствие!</b> Мы рады видеть вас на просторах нашего мира! <hr> Вы можете прочитать помощь на странице вашего персонажа.</center>', 1, $pers['user'], '*', 0);
        say_to_chat('a', "Родился малыш! <b>{$pers['user']}</b> мы приветствуем тебя и желаем длинного и увлекательного пути!", 0, '', '*', 0);

        if ($pers['referal_uid']) {
            $stmt = $db->prepare("SELECT uid, user FROM users WHERE uid = :uid");
            $stmt->execute([':uid' => $pers['referal_uid']]);
            $referrer = $stmt->fetch();
            if ($referrer) {
                $stmt = $db->prepare("UPDATE users SET money = money + 10, referal_counter = referal_counter + 1, refc = refc + 1, coins = coins + 1 WHERE uid = :uid");
                $stmt->execute([':uid' => $referrer['uid']]);
                say_to_chat('s', "Вы привели в игру персонажа <font class=user onclick=\"top.say_private('{$pers['user']}')\">{$pers['user']}</font>! Вам на счёт зачислено <b>10 LN и 1 Пергамент</b>", 1, $referrer['user'], '*', 0);
            }
        }

        // Выдаем стартовые предметы
        $stmt = $db->query("SELECT id FROM weapons WHERE tlevel = 0 AND where_buy = 0 LIMIT 1");
        if ($weapon = $stmt->fetch()) {
            insert_wp($weapon['id'], $pers['uid']);
        }
        $stmt = $db->query("SELECT id FROM weapons WHERE id = 14539");
        if ($weapon = $stmt->fetch()) {
            insert_wp($weapon['id'], $pers['uid']);
        }
    }

    // Оптимизация базы данных (только днем)
    if ($night == 0) {
        $stmt = $db->query("SELECT * FROM configs LIMIT 1");
        $configs = $stmt->fetch();

        if ($configs['last_dump'] < time()) {
            say_to_chat('a', "Внимание! Игра приостановит свою работу на малый срок. Оптимизация и сохранение параметров...", 0, 0, '*', 0);
            $stmt = $db->prepare("UPDATE configs SET last_dump = :time");
            $stmt->execute([':time' => time() + 86400]);
            $db->exec("OPTIMIZE TABLE users, wp, chars, mine, bots_cell, herbals_cell, bots, weapons, chat");
            $db->exec("TRUNCATE TABLE bots_battle, chat, salings");
            $db->exec("UPDATE users SET chat_last_id = 0");
        }
    }

    // Поздравление с днем рождения
    $dr = explode('.', $pers['dr']);
    $drMonth = (int)($dr[1] ?? 0);
    $drDay = (int)($dr[0] ?? 0);
    $year = (int)date('Y');
    $drCongratulate = $pers['DR_congratulate'] ?: mktime(0, 0, 0, $drMonth, $drDay, $year);
    if ($drCongratulate < time() && $pers['level'] > 0) {
        say_to_chat('s', "Администрация поздравляет <b>{$pers['user']}</b> с днём рождения! От лица всех игроков мы хотим вам пожелать ярких успехов, великих побед, море достатка и жизни без бед!", 0, '', '*', 0);
        say_to_chat('s', "Персонаж <b>{$pers['user']}</b> получает " . ($pers['level'] * 20) . " LN в честь дня рождения!", 0, '', '*', 0);
        $stmt = $db->prepare("UPDATE users SET money = money + :bonus WHERE uid = :uid");
        $stmt->execute([':bonus' => $pers['level'] * 20, ':uid' => $pers['uid']]);
        $drCongratulate = mktime(0, 0, 0, $drMonth, $drDay, $year + 1);
        $stmt = $db->prepare("UPDATE users SET DR_congratulate = :time WHERE uid = :uid");
        $stmt->execute([':time' => $drCongratulate, ':uid' => $pers['uid']]);
    }
    ?>
</body>
</html>