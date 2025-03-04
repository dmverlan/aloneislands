<?php
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

require_once 'inc/functions.php';
require_once 'configs/config.php';
require_once 'db.php';

define('WEATHER_REDIRECT_DELAY', 15); // Секунды до редиректа

$db = getDatabaseConnection();
$currentTime = time();

// Проверяем и обновляем погоду (адаптация из game.php)
function checkWorldWeather($db, $currentTime) {
    static $world = null;
    if ($world === null) {
        $stmt = $db->query("SELECT weather, weatherchange FROM world LIMIT 1");
        $world = $stmt->fetch();
    }
    return $world;
}

function updateWorldWeather($db, $currentTime, $seasonId) {
    $world = checkWorldWeather($db, $currentTime);
    if ($world['weatherchange'] < $currentTime) {
        $stmt = $db->prepare("SELECT * FROM weather WHERE season = 5 OR season = :season ORDER BY RAND() LIMIT 1");
        $stmt->execute([':season' => $seasonId]);
        $newWeather = $stmt->fetch();

        $weatherChange = $currentTime + rand(1, 3) * $newWeather['time'];
        $stmt = $db->prepare("UPDATE world SET weather = :weather, weatherchange = :changeTime");
        $stmt->execute([':weather' => $newWeather['id'], ':changeTime' => $weatherChange]);

        say_to_chat('a', 'Произошла смена погоды.', 0, '', '*');
        $db->exec("UPDATE nature SET fish_population = fish_population + fish_population * 0.5 + " . rand(0, 100) . " WHERE fishing > 0 AND fish_population < 600");

        return ['weather' => $newWeather['id'], 'weatherchange' => $weatherChange];
    }
    return $world;
}

// Определяем сезон
function getSeasonData($currentTime) {
    $month = (int)date('m', $currentTime);
    $year = (int)date('Y', $currentTime);

    $seasons = [
        1 => ['name' => 'Зима', 'start' => [12, 1], 'end' => [3, 1]],
        2 => ['name' => 'Весна', 'start' => [3, 1], 'end' => [6, 1]],
        3 => ['name' => 'Лето', 'start' => [6, 1], 'end' => [9, 1]],
        4 => ['name' => 'Осень', 'start' => [9, 1], 'end' => [12, 1]]
    ];

    foreach ($seasons as $id => $season) {
        $startTime = mktime(0, 0, 0, $season['start'][0], $season['start'][1], $year + ($month >= 12 && $season['start'][0] == 12 ? 0 : ($month < $season['start'][0] ? -1 : 0)));
        $endTime = mktime(0, 0, 0, $season['end'][0], $season['end'][1], $year + ($month >= 12 && $season['end'][0] == 12 ? 1 : 0));
        if ($currentTime >= $startTime && $currentTime < $endTime) {
            return [
                'id' => $id,
                'name' => $season['name'],
                'changes' => tp($endTime - $currentTime)
            ];
        }
    }
    return ['id' => 1, 'name' => 'Зима', 'changes' => 'НЕИЗВЕСТНО']; // По умолчанию
}

// Генерация JavaScript для звука
function generatePlayScript($weatherId) {
    $hour = (int)date('H');
    $isNight = $hour > 21 || $hour < 7;
    $weatherMap = [
        1 => ['season' => 'Summer', 'type' => 'hot'],
        2 => ['season' => 'Summer', 'type' => 'rain'],
        3 => ['season' => 'Summer', 'type' => 'hrain'],
        4 => ['season' => 'Summer', 'type' => 'wind'],
        5 => ['season' => 'Summer', 'type' => 'storm'],
        6 => ['season' => 'Summer', 'type' => 'fog'],
        7 => ['season' => 'Summer', 'type' => 'gsnow'],
        8 => ['season' => 'Summer', 'type' => 'snow']
    ];
    if (isset($weatherMap[$weatherId])) {
        $params = $isNight ? "'{$weatherMap[$weatherId]['type']}', 1" : "'{$weatherMap[$weatherId]['type']}'";
        return "<script>top.Play{$weatherMap[$weatherId]['season']}($params);</script>";
    }
    return '';
}

// Основная логика
$seasonData = getSeasonData($currentTime);
$worldWeather = updateWorldWeather($db, $currentTime, $seasonData['id']);
$weatherData = $db->prepare("SELECT * FROM weather WHERE id = :id");
$weatherData->execute([':id' => $worldWeather['weather']]);
$weather = $weatherData->fetch();

$weatherChangeTime = tp($worldWeather['weatherchange'] - $currentTime);
$weatherId = ($hour > 21 || $hour < 7) ? $weather['id'] + 10 : $weather['id'];
$playScript = generatePlayScript($weather['id']);

require 'templates/weather_template.php';
?>