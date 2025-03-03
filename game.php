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

$_POST = sanitizeInput($_POST);
$_GET = sanitizeInput($_GET);
$_COOKIE = sanitizeInput($_COOKIE);

$db = getDatabaseConnection();

$currentTime = time();
$night = ($currentTime >= mktime(6, 0, 0) && $currentTime < mktime(22, 0, 0)) ? 0 : 1;

function checkWorldWeather($db, $currentTime) {
    static $world = null;
    if ($world === null) {
        $stmt = $db->query("SELECT weather, weatherchange FROM world LIMIT 1");
        $world = $stmt->fetch();
    }
    if ($world && $world['weatherchange'] < $currentTime) {
        say_to_chat("#W", "#W", 0, '', '*');
    }
    return $world;
}

function authenticateUser($db) {
    global $pers;
    $credentials = [
        ['field' => 'pass', 'source' => $_POST],
        ['field' => 'passnmd', 'source' => $_POST],
        ['field' => 'hashcode', 'source' => $_COOKIE, 'condition' => empty($_POST) && isset($_COOKIE['uid'])]
    ];
    foreach ($credentials as $cred) {
        if (empty($cred['source'][$cred['field']]) || (isset($cred['condition']) && !$cred['condition'])) continue;
        $uid = isset($cred['source']['uid']) ? (int)$cred['source']['uid'] : null;
        $user = $cred['source']['user'] ?? null;
        $pass = $cred['source'][$cred['field']];
        $stmt = $db->prepare("SELECT * FROM users WHERE " . ($uid ? "uid = :uid" : "user = :user"));
        $stmt->execute($uid ? [':uid' => $uid] : [':user' => $user]);
        $pers = $stmt->fetch();
        if ($pers && (password_verify($pass, $pers['pass']) || updateOldPassword($db, $pers, $pass))) return true;
    }
    return false;
}

function updateOldPassword($db, &$pers, $password) {
    if ($pers['pass'] === md5($password)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET pass = :pass WHERE uid = :uid");
        $stmt->execute([':pass' => $newHash, ':uid' => $pers['uid']]);
        $pers['pass'] = $newHash;
        return true;
    }
    return false;
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

$pers = null;
$err = 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyCsrfToken($_POST['csrf'] ?? '')) {
    $err = 3;
} elseif (authenticateUser($db)) {
    $err = 0;
}

if ($pers && $pers['flash_pass']) {
    if (empty($_POST['spass'])) {
        include 'second_password.php';
        exit;
    } elseif ($pers['flash_pass'] !== $_POST['spass']) {
        $err = 1;
    }
}

if ($pers && !empty($pers['block'])) $err = 2;

$errorMap = [1 => 'login', 2 => 'block', 3 => 'csrf'];
if ($err !== 0) {
    $_GET['error'] = $errorMap[$err];
    include 'index.php';
    exit;
}

define('UID', $pers['uid']);
define('PASS', $pers['pass']);
define('USER', $pers['user']);
define('SPASS', $pers['flash_pass']);

function updateUserSession($db, $pers, $currentTime) {
    try {
        $cookieLifetime = $currentTime + 30 * 24 * 3600;
        foreach (['uid' => $pers['uid'], 'hashcode' => $pers['pass'], 'nick' => $pers['user'], 'options' => $pers['options'], 'spass' => $pers['flash_pass']] as $name => $value) {
            setcookie($name, $value, $cookieLifetime, '/');
        }
        $lastVisit = date('d.m.Y H:i', $currentTime);
        $ip = show_ip();
        $chatLastId = (int)$db->query("SELECT MAX(id) FROM chat")->fetchColumn();
        $stmt = $db->prepare("UPDATE users SET lastip = :ip, lastvisit = :visit, lastvisits = :time, lasto = :time, online = 1, chat_last_id = :chat WHERE uid = :uid; INSERT INTO ips_in (uid, ip, date) VALUES (:uid, :ip, :time)");
        $stmt->execute([':ip' => $ip, ':visit' => $lastVisit, ':time' => $currentTime, ':chat' => $chatLastId, ':uid' => $pers['uid']]);
        if (!empty($_COOKIE['uid']) && $_COOKIE['uid'] != $pers['uid']) {
            $stmt = $db->prepare("INSERT INTO one_comp_logins (uid1, uid2, time) VALUES (:uid1, :uid2, :time)");
            $stmt->execute([':uid1' => (int)$_COOKIE['uid'], ':uid2' => $pers['uid'], ':time' => $currentTime]);
        }
    } catch (Exception $e) {
        error_log("Ошибка в updateUserSession: " . $e->getMessage());
    }
}

function welcomeNewPlayer($db, $pers, $currentTime) {
    if ($pers['lasto'] != 0) return;
    $db->beginTransaction();
    try {
        say_to_chat('a', '<center class="return_win"><b>Приветствие!</b> Мы рады видеть вас на просторах нашего мира! <hr> Вы можете прочитать помощь на странице вашего персонажа.</center>', 1, $pers['user'], '*', 0);
        say_to_chat('a', "Родился малыш! <b>{$pers['user']}</b> мы приветствуем тебя и желаем длинного и увлекательного пути!", 0, '', '*', 0);
        if ($pers['referal_uid']) {
            $stmt = $db->prepare("SELECT uid, user FROM users WHERE uid = :uid");
            $stmt->execute([':uid' => $pers['referal_uid']]);
            if ($referrer = $stmt->fetch()) {
                $stmt = $db->prepare("UPDATE users SET money = money + 10, referal_counter = referal_counter + 1, refc = refc + 1, coins = coins + 1 WHERE uid = :uid");
                $stmt->execute([':uid' => $referrer['uid']]);
                say_to_chat('s', "Вы привели в игру персонажа <font class=\"user\" onclick=\"top.say_private('{$pers['user']}')\">{$pers['user']}</font>! Вам на счёт зачислено <b>10 LN и 1 Пергамент</b>", 1, $referrer['user'], '*', 0);
            }
        }
        $stmt = $db->query("SELECT id FROM weapons WHERE tlevel = 0 AND where_buy = 0 LIMIT 1");
        if ($weapon = $stmt->fetch()) insert_wp($weapon['id'], $pers['uid']);
        $stmt = $db->query("SELECT id FROM weapons WHERE id = 14539");
        if ($weapon = $stmt->fetch()) insert_wp($weapon['id'], $pers['uid']);
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Ошибка в welcomeNewPlayer: " . $e->getMessage());
    }
}

function optimizeDatabase($db, $night, $currentTime) {
    if ($night != 0) return;
    static $configs = null;
    if ($configs === null) {
        $stmt = $db->query("SELECT * FROM configs LIMIT 1");
        $configs = $stmt->fetch();
    }
    if ($configs['last_dump'] < $currentTime) {
        say_to_chat('a', "Внимание! Игра приостановит свою работу на малый срок. Оптимизация и сохранение параметров...", 0, 0, '*', 0);
        $stmt = $db->prepare("UPDATE configs SET last_dump = :time");
        $stmt->execute([':time' => $currentTime + 86400]);
        $db->exec("OPTIMIZE TABLE users, wp, chars, mine, bots_cell, herbals_cell, bots, weapons, chat");
        $db->exec("TRUNCATE TABLE bots_battle, chat, salings");
        $db->exec("UPDATE users SET chat_last_id = 0");
        $configs['last_dump'] = $currentTime + 86400;
    }
}

function celebrateBirthday($db, $pers, $currentTime) {
    $dr = explode('.', $pers['dr'] ?? '');
    $drMonth = (int)($dr[1] ?? 0);
    $drDay = (int)($dr[0] ?? 0);
    $year = (int)date('Y', $currentTime);
    $drCongratulate = $pers['DR_congratulate'] ?: mktime(0, 0, 0, $drMonth, $drDay, $year);
    if ($drCongratulate >= $currentTime || $pers['level'] <= 0) return;

    $bonus = $pers['level'] * 20;
    $message = "Администрация поздравляет <b>{$pers['user']}</b> с днём рождения! От лица всех игроков мы хотим вам пожелать ярких успехов, великих побед, море достатка и жизни без бед!<br>Персонаж <b>{$pers['user']}</b> получает $bonus LN в честь дня рождения!";
    say_to_chat('s', $message, 0, '', '*', 0);

    $stmt = $db->prepare("UPDATE users SET money = money + :bonus, DR_congratulate = :time WHERE uid = :uid");
    $stmt->execute([':bonus' => $bonus, ':time' => mktime(0, 0, 0, $drMonth, $drDay, $year + 1), ':uid' => $pers['uid']]);
}

$world = checkWorldWeather($db, $currentTime);
updateUserSession($db, $pers, $currentTime);
welcomeNewPlayer($db, $pers, $currentTime);
optimizeDatabase($db, $night, $currentTime);
celebrateBirthday($db, $pers, $currentTime);

$csrfToken = generateCsrfToken();

register_shutdown_function(function() use ($db) {
    global $chatBuffer;
    if (!empty($chatBuffer)) {
        $values = implode(',', array_fill(0, count($chatBuffer), '(?, ?, ?, ?, ?, ?, ?, ?)'));
        $flatParams = [];
        foreach ($chatBuffer as $msg) $flatParams = array_merge($flatParams, array_values($msg));
        $stmt = $db->prepare("INSERT INTO chat (user, time2, message, private, towho, location, time, color) VALUES $values");
        $stmt->execute($flatParams);
        $chatBuffer = [];
    }
});

require 'templates/game_template.php';
?>