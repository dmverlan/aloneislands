<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Указание кодировки и метаданных для SEO -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aloneislands — бесплатная фэнтези MMORPG с элементами стратегии, квестами и магией. Выбери расу и создавай новый мир!">
    <meta name="keywords" content="онлайн игра, MMORPG, фэнтези, квесты, стратегия, магия, браузерная игра">
    <title>Aloneislands: Вселенная в твоих руках!</title>

    <!-- Подключение favicon -->
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <!-- Подключение объединённых стилей -->
    <link rel="stylesheet" href="styles.css">
    <!-- Подключение скриптов с отложенной загрузкой -->
    <script src="js/newmain.js" defer></script>
    <script src="js/cookie.js" defer></script>
</head>
<body>
    <!-- Фоновое изображение страницы -->
    <img src="index/f.jpg" alt="Background" class="background">
    <!-- Контейнер для управления звуком -->
    <div id="sound-layer"></div>
    <!-- Наложения для декоративных элементов -->
    <div class="overlay left-overlay"></div>
    <div class="overlay right-overlay"></div>
    
    <!-- Основной контент страницы -->
    <main class="content">
        <!-- Логотип игры -->
        <img src="index/1024/logo2.png" alt="Logo" class="logo">
        <!-- Контейнер для формы входа -->
        <div class="login-container">
            <form action="game.php" method="post" name="auth" id="auth-form">
                <table>
                    <!-- Сообщение об ошибке или подсказка -->
                    <tr><td class="indexFont login-error"></td></tr>
                    <!-- Поле ввода логина -->
                    <tr><td class="indexFont">Логин<br><input class="loginBox" type="text" name="user"></td></tr>
                    <!-- Поле ввода пароля -->
                    <tr><td class="indexFont">Пароль<br><input class="loginBox" type="password" name="pass"></td></tr>
                </table>
                <!-- Кнопка отправки формы -->
                <button type="submit" class="submit-btn"><img src="index/1024/v.png" alt="Войти"></button>
            </form>
        </div>
    </main>
    
    <!-- Нижняя часть страницы с навигацией и копирайтом -->
    <footer>
        <nav>
            <a href="reg.php" class="boxed">Регистрация</a> |
            <a href="forum/" class="boxed">Форум</a> |
            <a href="remind.php" class="boxed">Забыли пароль?</a>
        </nav>
        <p>© Copyright 2006-2009, Alone Islands Ltd. Все права защищены.</p>
    </footer>

    <!-- Модальное окно для iframe -->
    <dialog id="modal">
        <div class="modal-content">
            <!-- Кнопка закрытия модального окна -->
            <button id="close-modal">×</button>
            <!-- Фрейм для отображения содержимого -->
            <iframe id="modal-iframe" frameborder="0"></iframe>
        </div>
    </dialog>

    <!-- Yandex.Metrika для аналитики -->
    <script src="//mc.yandex.ru/resource/watch.js" async></script>
    <script>
        try { const yaCounter184038 = new Ya.Metrika(184038); } catch (e) {}
    </script>
    <noscript><div style="position:absolute;"><img src="//mc.yandex.ru/watch/184038" alt=""></div></noscript>

    <!-- Счётчики посещений -->
    <img src="http://counter.yadro.ru/hit?t26.5;r" alt="LiveInternet" style="display:none;">
    <img src="http://dc.c6.b2.a1.top.list.ru/counter?id=1207359;t=109" alt="Mail.ru" style="display:none;">
    <img src="http://yandeg.ru/count/cnt.php?id=107052" alt="Yandeg" style="display:none;">
</body>
</html>