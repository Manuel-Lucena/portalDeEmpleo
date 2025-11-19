FROM php:8.2-apache


# Instalar utilidades y extensiones

RUN apt-get update && apt-get install -y \
        default-mysql-client \
        libonig-dev \
        libzip-dev \
        unzip \
        locales \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && docker-php-source delete


# Configurar UTF-8 

RUN sed -i 's/# es_ES.UTF-8 UTF-8/es_ES.UTF-8 UTF-8/' /etc/locale.gen && \
    locale-gen es_ES.UTF-8

ENV LANG=es_ES.UTF-8
ENV LANGUAGE=es_ES:es
ENV LC_ALL=es_ES.UTF-8


# Instalar Composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer


# Directorio de trabajo

WORKDIR /var/www/html


# Copiar el script SQL

COPY database.sql /docker-entrypoint-initdb.d/


# Instalar dependencias de PHP (Composer)

COPY composer.json composer.lock /var/www/html/
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 80
