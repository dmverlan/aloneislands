// Установка заголовка страницы
document.title = 'Aloneislands - Вселенная в твоих руках!';

// Глобальные переменные для управления звуком
let soundsOn = true; // Флаг включения звука
let currentAudio = null; // Текущий аудиообъект

// Определяет ширину экрана и возвращает ближайшее поддерживаемое значение
function getScreenWidth() {
    const sw = window.innerWidth;
    return sw < 800 ? 800 : sw > 1280 ? 1280 : [800, 1024, 1152, 1280].includes(sw) ? sw : 1024;
}

// Инициализирует страницу с учётом ошибки авторизации
function index(terror) {
    // Отображение сообщения об ошибке
    const errorEl = document.querySelector('.login-error');
    errorEl.textContent = terror === 'login' ? 'Неверный логин или пароль.' :
                         terror === 'block' ? 'Персонаж заблокирован.' : 'Войти в игру:';

    // Установка изображений в зависимости от ширины экрана
    const sw = getScreenWidth();
    document.querySelector('.logo').src = `index/${sw}/logo2.png`;
    document.querySelector('.submit-btn img').src = `index/${sw}/v.png`;
    document.querySelector('.left-overlay').style.backgroundImage = `url('index/${sw}/m.png')`;
    document.querySelector('.right-overlay').style.backgroundImage = `url('index/${sw}/d.png')`;

    // Обработка кликов по ссылкам для открытия модального окна
    document.querySelectorAll('.boxed').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById('modal');
            const iframe = document.getElementById('modal-iframe');
            iframe.src = link.href;
            modal.showModal();
        });
    });
}

// Воспроизводит случайный звуковой трек
function playSound(track) {
    if (!soundsOn || currentAudio) return;
    currentAudio = new Audio(`sounds/${track}.mp3`);
    currentAudio.volume = 0.5; // Громкость 50%
    currentAudio.loop = true; // Зацикливание
    currentAudio.play();
}

// Приостанавливает воспроизведение звука
function pauseSound() {
    if (currentAudio) currentAudio.pause();
}

// Возобновляет воспроизведение звука
function resumeSound() {
    if (!currentAudio) playSound(`title${Math.floor(Math.random() * 3) + 1}`);
    else currentAudio.play();
}

// Переключает состояние звука (вкл/выкл)
function toggleSound() {
    const soundLayer = document.getElementById('sound-layer');
    soundsOn = !soundsOn;
    soundLayer.innerHTML = soundsOn ?
        '<div class="sound-control" onclick="toggleSound()"><img src="images/icon_eq.gif"><i>Music ON</i></div>' :
        '<div class="sound-control" onclick="toggleSound()"><img src="images/paused.gif"><i>Music OFF</i></div>';
    soundsOn ? resumeSound() : pauseSound();
}

// Инициализация страницы после загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
    // Обработка ошибки из URL
    index(new URLSearchParams(window.location.search).get('error') || '');
    // Запуск управления звуком
    toggleSound();
    // Закрытие модального окна
    document.getElementById('close-modal').addEventListener('click', () => {
        document.getElementById('modal').close();
    });
});

// Сохраняет логин и пароль в cookies и перенаправляет на game.php
function enter(login, pass) {
    createCookie("uid", login, 7); // 7 дней по умолчанию
    createCookie("hashcode", pass, 7);
    window.location = 'game.php';
}