# tarik image php version 8.3
FROM php:8.3-cli-alpine

# lokasi project di dalam container dan setting port menjadi 6001
ENV \
    APP_DIR="/app" \
    APP_PORT="6001"

# copy semua project ke dalam container menggunakan flag variabel APP_DIR
COPY . $APP_DIR
RUN mv $APP_DIR/.env.example $APP_DIR/.env

# install library kebutuhan PHP (sepertinya ini untuk yang standar)
RUN apk add --update \
    curl \
    php \
    php-opcache \
    php-openssl \
    php-pdo \
    php-json \
    php-phar \
    php-dom \
    && rm -rf /var/cache/apk/*

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer

# buka aplikasi dan install composer
RUN cd $APP_DIR && composer self-update --1
# RUN cd $APP_DIR && php artisan key:generate

RUN apk update \
    && apk add --no-cache postgresql-dev postgresql-client \
    && docker-php-ext-install pgsql pdo_pgsql

# Definisikan untuk wokspace project akan ditaruh dimana
WORKDIR $APP_DIR
# menjalankan fitur artisan serve dengan port yang kita mau
CMD php artisan serve --host=0.0.0.0 --port=$APP_PORT

EXPOSE $APP_PORT