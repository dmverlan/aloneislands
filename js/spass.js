// Функция инициализации интерфейса ввода второго пароля
function code(user, pass) {
    const container = document.querySelector('#second-password-input');
    if (!container) {
        console.error('Элемент #second-password-input не найден');
        return;
    }

    // Создаем форму
    const form = document.createElement('form');
    form.name = 'code';
    form.method = 'POST';
    form.action = 'game.php';
    form.addEventListener('submit', sbmt_img);

    // Поля формы
    const userInput = document.createElement('input');
    userInput.type = 'hidden';
    userInput.name = 'user';
    userInput.value = user;

    const passInput = document.createElement('input');
    passInput.type = 'hidden';
    passInput.name = 'pass'; // Изменено с passnmd на pass для совместимости с game.php
    passInput.value = pass;

    const spassInput = document.createElement('input');
    spassInput.type = 'text';
    spassInput.name = 'spass';
    spassInput.id = 'spass';
    spassInput.className = 'login';
    spassInput.value = '';
    spassInput.disabled = true; // Поле отключено до проверки

    // Кнопка отправки
    const submitButton = document.createElement('input');
    submitButton.type = 'button';
    submitButton.className = 'login';
    submitButton.value = 'OK';
    submitButton.style.cursor = 'pointer';
    submitButton.addEventListener('click', sbmt_img);

    // Логотип
    const logo = document.createElement('img');
    logo.src = 'images/logo.gif';
    logo.width = 180;
    logo.alt = 'Logo';

    // Собираем форму
    form.appendChild(logo);
    form.appendChild(document.createElement('br'));
    form.appendChild(userInput);
    form.appendChild(passInput);
    form.appendChild(spassInput);
    form.appendChild(document.createElement('br'));
    form.appendChild(submitButton);

    // Добавляем форму в контейнер
    container.appendChild(form);

    // Отображаем цифровую клавиатуру
    show_numbers();
}

// Функция проверки и отправки формы
function sbmt_img(event) {
    event?.preventDefault(); // Предотвращаем отправку, если вызвано не кнопкой
    const spassInput = document.getElementById('spass');
    const code = parseInt(spassInput.value, 10);

    // Проверка: код должен быть числом длиной от 4 до 6 символов
    if (!isNaN(code) && code >= 1000 && code <= 999999) {
        spassInput.disabled = false;
        document.forms['code'].submit();
    } else {
        alert('Введите корректный цифровой пароль (4-6 цифр)');
    }
}

// Функция отображения цифровой клавиатуры
function show_numbers() {
    const container = document.getElementById('second-password-input');
    if (!container) return;

    const hr = document.createElement('hr');
    container.appendChild(hr);

    // Кнопки с цифрами 0-9
    for (let i = 0; i < 10; i++) {
        const button = document.createElement('input');
        button.type = 'button';
        button.value = ` ${i} `;
        button.className = 'laar';
        button.style.cursor = 'pointer';
        button.addEventListener('click', () => plus_codeimg(i));
        button.addEventListener('dblclick', () => plus_codeimg(i));
        container.appendChild(button);
    }

    // Кнопка удаления последней цифры
    const backButton = document.createElement('input');
    backButton.type = 'button';
    backButton.value = ' << ';
    backButton.className = 'laar';
    backButton.style.cursor = 'pointer';
    backButton.addEventListener('click', () => plus_codeimg(-1));
    backButton.addEventListener('dblclick', () => plus_codeimg(-1));
    container.appendChild(backButton);

    // Кнопка очистки
    const clearButton = document.createElement('input');
    clearButton.type = 'button';
    clearButton.value = ' C ';
    clearButton.className = 'laar';
    clearButton.style.cursor = 'pointer';
    clearButton.addEventListener('click', () => plus_codeimg(-2));
    clearButton.addEventListener('dblclick', () => plus_codeimg(-2));
    container.appendChild(clearButton);
}

// Функция добавления или удаления цифр в поле ввода
function plus_codeimg(i) {
    const spassInput = document.getElementById('spass');
    let code = spassInput.value;

    if (i >= 0) {
        code += i; // Добавляем цифру
    } else if (i === -1) {
        code = code.slice(0, -1); // Удаляем последнюю цифру
    } else {
        code = ''; // Очищаем поле
    }

    spassInput.value = code;
}