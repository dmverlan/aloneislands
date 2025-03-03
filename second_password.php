<?php
// Защита от прямого доступа к файлу
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

// Включаем отображение ошибок для разработки (в продакшене заменить на логирование)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Проверяем, что данные пришли из формы
if (!isset($_POST['user']) || !isset($_POST['pass'])) {
    die('Ошибка: отсутствуют необходимые данные для ввода второго пароля.');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alone Islands - Ввод цифрового пароля</title>
    <link rel="stylesheet" href="main.css">
    <script src="js/spass.js" defer></script>
</head>
<body>
    <center class="return_win">
        <p>Для входа в игру вам требуется ввести ваш цифровой пароль ниже.<br>Ввод осуществляется только мышкой.</p>
        <div id="second-password-input"></div> <!-- Предполагаемое место для интерфейса из spass.js -->
        <script>
            // Передаем данные в JavaScript с защитой от XSS
            const user = <?= json_encode($_POST['user']) ?>;
            const passHash = <?= json_encode($_POST['pass']) ?>;
            code(user, passHash); // Вызываем функцию из spass.js
        </script>
    </center>
</body>
</html>