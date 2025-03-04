<?php
session_start();
require_once 'inc/functions.php'; // Для sanitizeInput и других функций
require_once 'db.php'; // Для getDatabaseConnection, если нужно

define('CSRF_TOKEN_LIFETIME_SECONDS', 3600);

$currentTime = time();

function generateCsrfToken($currentTime) {
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_time']) || $_SESSION['csrf_time'] < $currentTime - CSRF_TOKEN_LIFETIME_SECONDS) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_time'] = $currentTime;
    }
    return $_SESSION['csrf_token'];
}

$csrfToken = generateCsrfToken($currentTime);

// Обработка ошибок из $_GET['error']
$errorMessages = [
    'login' => 'Неверный логин или пароль.',
    'block' => 'Ваш аккаунт заблокирован.',
    'csrf' => 'Ошибка безопасности. Попробуйте снова.'
];
$errorMessage = isset($_GET['error']) && array_key_exists($_GET['error'], $errorMessages) ? $errorMessages[$_GET['error']] : '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aloneislands — бесплатная фэнтези MMORPG с элементами стратегии, квестами и магией. Выбери расу и создавай новый мир!">
    <meta name="keywords" content="онлайн игра, MMORPG, фэнтези, квесты, стратегия, магия, браузерная игра">
    <title>Aloneislands: Вселенная в твоих руках!</title>
    <link rel="preload" href="index/f.jpg" as="image">
    <link rel="preload" href="index/1024/logo2.png" as="image">
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script src="js/newmain.js" defer></script>
    <script src="js/cookie.js" defer></script>
</head>
<body>
    <img src="index/f.jpg" alt="Фоновое изображение игры" class="background">
    <div id="sound-layer"></div>
    <div class="overlay left-overlay"></div>
    <div class="overlay right-overlay"></div>

    <header class="content">
        <img src="index/1024/logo2.png" alt="Логотип Aloneislands" class="logo">
    </header>

    <main class="content">
        <section class="login-container">
            <form action="game.php" method="post" name="auth" id="auth-form">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <table>
                    <?php if ($errorMessage): ?>
                        <tr><td class="indexFont login-error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></td></tr>
                    <?php else: ?>
                        <tr><td class="indexFont login-error"></td></tr>
                    <?php endif; ?>
                    <tr><td class="indexFont">Логин<br><input class="loginBox" type="text" name="user" required></td></tr>
                    <tr><td class="indexFont">Пароль<br><input class="loginBox" type="password" name="pass" required></td></tr>
                </table>
                <button type="submit" class="submit-btn"><img src="index/1024/v.png" alt="Войти в игру"></button>
            </form>
        </section>
    </main>

    <footer>
        <nav>
            <a href="reg.php" class="boxed">Регистрация</a> |
            <a href="forum/" class="boxed">Форум</a> |
            <a href="remind.php" class="boxed">Забыли пароль?</a>
        </nav>
        <p>© Copyright 2006-2009, Alone Islands Ltd. Все права защищены.</p>
    </footer>

    <dialog id="modal">
        <div class="modal-content">
            <button id="close-modal">×</button>
            <iframe id="modal-iframe" frameborder="0"></iframe>
        </div>
    </dialog>

    <!-- Yandex.Metrika -->
    <script src="//mc.yandex.ru/resource/watch.js" async></script>
    <script>
        try { const yaCounter184038 = new Ya.Metrika(184038); } catch (e) {}
    </script>
    <noscript><div style="position:absolute;"><img src="//mc.yandex.ru/watch/184038" alt="Yandex.Metrika"></div></noscript>

    <!-- Автозаполнение логина из cookie -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginInput = document.querySelector('input[name="user"]');
            const savedLogin = readCookie('uid');
            if (savedLogin) loginInput.value = savedLogin;
        });
    </script>
</body>
</html>