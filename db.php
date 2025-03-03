<?php
require_once 'configs/config.php';

function getDatabaseConnection() {
    static $db = null;
    if ($db === null) {
        try {
            $dsn = "mysql:host=$mysqlhost;dbname=$mysqlbase;charset=utf8";
            $db = new PDO($dsn, $mysqluser, $mysqlpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log("Ошибка подключения к базе данных: " . $e->getMessage());
            die("Ошибка сервера. Попробуйте позже.");
        }
    }
    return $db;
}