FROM php:7.4-apache

# Установка необходимых расширений PHP
RUN docker-php-ext-install pdo_mysql gd

# Установка зависимостей для отправки email (опционально)
RUN apt-get update && apt-get install -y \
    msmtp \
    && rm -rf /var/lib/apt/lists/*

# Настройка Apache
RUN a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Настройка msmtp (опционально, для sendmail.php)
COPY msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc && chown www-data:www-data /etc/msmtprc

EXPOSE 80