document.title = 'Aloneislands - Вселенная в твоих руках!';

const elements = {
    errorEl: document.querySelector('.login-error'),
    logo: document.querySelector('.logo'),
    submitImg: document.querySelector('.submit-btn img'),
    leftOverlay: document.querySelector('.left-overlay'),
    rightOverlay: document.querySelector('.right-overlay'),
    boxedLinks: document.querySelectorAll('.boxed'),
    modal: document.getElementById('modal'),
    iframe: document.getElementById('modal-iframe'),
    closeModal: document.getElementById('close-modal'),
    soundLayer: document.getElementById('sound-layer')
};

let soundsOn = true;
let currentAudio = null;
const soundTracks = ['title1', 'title2', 'title3'];

function getScreenWidth() {
    const sw = window.innerWidth;
    const supportedWidths = [800, 1024, 1152, 1280];
    return sw < 800 ? 800 : sw > 1280 ? 1280 : supportedWidths.includes(sw) ? sw : 1024;
}

function initPage(error) {
    const errorMessages = {
        login: 'Неверный логин или пароль.',
        block: 'Персонаж заблокирован.',
        default: 'Войти в игру:'
    };
    elements.errorEl.textContent = errorMessages[error] || errorMessages.default;

    const sw = getScreenWidth();
    elements.logo.src = `index/${sw}/logo2.png`;
    elements.submitImg.src = `index/${sw}/v.png`;
    elements.leftOverlay.style.backgroundImage = `url('index/${sw}/m.png')`;
    elements.rightOverlay.style.backgroundImage = `url('index/${sw}/d.png')`;

    elements.boxedLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            elements.iframe.src = link.href;
            elements.modal.showModal();
        });
    });
}

function playSound() {
    if (!soundsOn || currentAudio || !window.Audio) return;
    const track = soundTracks[Math.floor(Math.random() * soundTracks.length)];
    currentAudio = new Audio(`sounds/${track}.mp3`);
    currentAudio.volume = 0.5;
    currentAudio.loop = true;
    currentAudio.play().catch(err => console.error('Ошибка воспроизведения звука:', err));
}

function pauseSound() {
    if (currentAudio) currentAudio.pause();
}

function resumeSound() {
    if (!currentAudio) playSound();
    else currentAudio.play().catch(err => console.error('Ошибка возобновления звука:', err));
}

function toggleSound() {
    soundsOn = !soundsOn;
    elements.soundLayer.innerHTML = soundsOn ?
        '<div class="sound-control" onclick="toggleSound()"><img src="images/icon_eq.gif" alt="Вкл"><i>Music ON</i></div>' :
        '<div class="sound-control" onclick="toggleSound()"><img src="images/paused.gif" alt="Выкл"><i>Music OFF</i></div>';
    soundsOn ? resumeSound() : pauseSound();
}

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    initPage(urlParams.get('error') || '');
    toggleSound();
    elements.closeModal.addEventListener('click', () => elements.modal.close());
});