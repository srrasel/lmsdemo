FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
EXPOSE 8000

CMD ["bash", "-lc", "if [ ! -f .env ]; then cp .env.example .env; fi && composer install --no-interaction --prefer-dist --optimize-autoloader && npm install && php artisan key:generate --force && until php -r 'try { new PDO(\"mysql:host=db;port=3306;dbname=elearning\", \"root\", \"root\"); } catch (Throwable $e) { sleep(2); exit(1);}'; do echo waiting for mysql; sleep 2; done && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
