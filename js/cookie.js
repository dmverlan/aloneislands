// Создаёт cookie с заданным именем, значением и сроком действия в днях
function createCookie(name, value, days) {
    const date = new Date();
    let expires = '';
    if (days) {
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Вычисление даты истечения
        expires = `; expires=${date.toUTCString()}`; // Формат UTC
    }
    // Установка cookie с экранированием и дополнительными атрибутами безопасности
    document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires}; path=/; SameSite=Lax; Secure`;
}

// Читает значение cookie по имени
function readCookie(name) {
    const nameEQ = `${encodeURIComponent(name)}=`; // Префикс для поиска
    const cookies = document.cookie.split(';'); // Разделение всех cookie
    for (let cookie of cookies) {
        cookie = cookie.trim(); // Удаление лишних пробелов
        if (cookie.startsWith(nameEQ)) return decodeURIComponent(cookie.substring(nameEQ.length));
    }
    return null; // Возвращает null, если cookie не найдено
}

// Удаляет cookie, устанавливая отрицательный срок действия
function eraseCookie(name) {
    createCookie(name, '', -1); // Срок действия -1 день для удаления
}