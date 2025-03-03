<?php
// Защита от прямого доступа к файлу
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

// Подключаем PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php'; // Если используется Composer
// Или вручную:
// require_once 'path/to/PHPMailer/src/PHPMailer.php';
// require_once 'path/to/PHPMailer/src/SMTP.php';
// require_once 'path/to/PHPMailer/src/Exception.php';

/**
 * Отправляет email с использованием PHPMailer
 * @param string $to Адрес получателя
 * @param string $body Тело письма (HTML)
 * @param string $from Адрес отправителя
 * @param string $subject Тема письма (по умолчанию "Заполнена форма на сайте")
 * @return bool Успешность отправки
 */
function send_mail($to, $body, $from, $subject = 'Заполнена форма на сайте') {
    // Валидация входных данных
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        error_log("Некорректный адрес получателя: $to");
        return false;
    }
    if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
        error_log("Некорректный адрес отправителя: $from");
        return false;
    }
    if (empty($body)) {
        error_log("Пустое тело письма");
        return false;
    }

    // Создаем экземпляр PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Настройки сервера
        $mail->isSMTP(); // Используем SMTP (можно заменить на mail() через isMail())
        $mail->Host       = 'smtp.example.com'; // Укажи свой SMTP-сервер (например, smtp.gmail.com)
        $mail->SMTPAuth   = true;               // Включаем аутентификацию
        $mail->Username   = 'your_email@example.com'; // SMTP логин
        $mail->Password   = 'your_password';    // SMTP пароль
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS шифрование
        $mail->Port       = 587;                // Порт SMTP (587 для TLS, 465 для SSL)

        // Настройки отправителя и получателя
        $mail->setFrom($from, strtoupper($_SERVER['SERVER_NAME']));
        $mail->addAddress($to);
        $mail->addReplyTo($from);

        // Кодировка и содержимое
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true); // Письмо в формате HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Отправляем письмо
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Логируем ошибку
        error_log("Ошибка отправки письма: {$mail->ErrorInfo}");
        return false;
    }
}