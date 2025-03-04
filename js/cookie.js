/**
 * Управление cookies с кэшированием для повышения производительности
 */
const CookieManager = (() => {
    let cookieCache = null;

    /**
     * Парсит текущие cookies в объект
     * @returns {Object} Кэшированный объект cookies
     */
    function parseCookies() {
        if (cookieCache) return cookieCache;
        cookieCache = {};
        document.cookie.split(';').forEach(cookie => {
            const [name, value] = cookie.trim().split('=');
            if (name && value !== undefined) {
                cookieCache[decodeURIComponent(name)] = decodeURIComponent(value);
            }
        });
        return cookieCache;
    }

    /**
     * Создаёт cookie с заданными параметрами
     * @param {string} name Имя cookie
     * @param {string} value Значение cookie
     * @param {number} [days] Срок действия в днях (по умолчанию бесконечно)
     * @param {Object} [options] Дополнительные параметры
     * @param {string} [options.path] Путь (по умолчанию '/')
     * @param {boolean} [options.secure] Использовать Secure (по умолчанию true при HTTPS)
     * @param {string} [options.sameSite] SameSite (по умолчанию 'Lax')
     * @param {boolean} [options.httpOnly] HttpOnly (по умолчанию false)
     */
    function createCookie(name, value, days, options = {}) {
        if (!name || typeof name !== 'string' || value === undefined) {
            console.warn('Некорректные параметры для cookie:', { name, value });
            return;
        }

        const date = new Date();
        let expires = '';
        if (days !== undefined) {
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = `; expires=${date.toUTCString()}`;
        }

        const defaults = {
            path: '/',
            secure: window.location.protocol === 'https:',
            sameSite: 'Lax',
            httpOnly: false
        };
        const opts = { ...defaults, ...options };

        const cookieString = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires}` +
            `; path=${opts.path}` +
            (opts.secure ? '; Secure' : '') +
            `; SameSite=${opts.sameSite}` +
            (opts.httpOnly ? '; HttpOnly' : '');

        document.cookie = cookieString;
        cookieCache = null; // Сбрасываем кэш после изменения
    }

    /**
     * Читает значение cookie по имени
     * @param {string} name Имя cookie
     * @returns {string|null} Значение cookie или null, если не найдено
     */
    function readCookie(name) {
        if (!name || typeof name !== 'string') return null;
        const cookies = parseCookies();
        return cookies[encodeURIComponent(name)] || null;
    }

    /**
     * Удаляет cookie по имени
     * @param {string} name Имя cookie
     * @param {Object} [options] Параметры удаления (path, domain и т.д.)
     */
    function eraseCookie(name, options = {}) {
        if (!name || typeof name !== 'string') return;
        createCookie(name, '', -1, options);
    }

    return { createCookie, readCookie, eraseCookie };
})();

// Экспорт для обратной совместимости с существующим кодом
const { createCookie, readCookie, eraseCookie } = CookieManager;