# Tarik image PHP version 8.3
FROM php:8.3-cli-alpine

# Lokasi project di dalam container dan setting port menjadi 6001
ENV APP_DIR="/app"
ENV APP_PORT="6001"

# Buat direktori aplikasi di dalam container
RUN mkdir -p $APP_DIR

# Copy semua project ke dalam container menggunakan flag variabel APP_DIR
COPY . $APP_DIR
RUN mv $APP_DIR/.env.example $APP_DIR/.env

# Install library kebutuhan PHP dan alat-alat lain yang dibutuhkan
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

RUN apk update \
    && apk add --no-cache postgresql-dev postgresql-client \
    && docker-php-ext-install pgsql pdo_pgsql

# Instalasi ekstensi GD
RUN apk add --update --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer

# Buka aplikasi dan install dependencies menggunakan Composer
WORKDIR $APP_DIR

# Copy file artisan dari root directory ke dalam direktori aplikasi di dalam container
COPY artisan .

# Install Composer dependencies
RUN composer install --no-scripts --no-autoloader

# Generate the optimized autoloader
RUN composer dump-autoload --optimize

# Generate application key
RUN php artisan key:generate

# Menjalankan fitur artisan serve dengan port yang ditentukan
CMD php artisan serve --host=0.0.0.0 --port=$APP_PORT

# Expose port yang digunakan
EXPOSE $APP_PORT