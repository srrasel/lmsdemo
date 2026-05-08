FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    bash \
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

RUN cat > /usr/local/bin/start-backend.sh <<'SH'
#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html/core

if [ ! -f .env ]; then
  cp .env.example .env
fi

ln -sfn /var/www/html/assets /var/www/html/core/assets

if [ -n "${APP_KEY:-}" ]; then
  if grep -q "^APP_KEY=" .env; then
    sed -i "s/^APP_KEY=.*/APP_KEY=${APP_KEY}/" .env
  else
    printf "\nAPP_KEY=%s\n" "${APP_KEY}" >> .env
  fi
fi

export DB_HOST="${DB_HOST:-db}"
export DB_PORT="${DB_PORT:-3306}"
export DB_DATABASE="${DB_DATABASE:-elearning}"
export DB_USERNAME="${DB_USERNAME:-root}"
export DB_PASSWORD="${DB_PASSWORD:-root}"

until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1; do
  echo waiting for mysql
  sleep 2
done

composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts
composer dump-autoload --optimize
php artisan package:discover --ansi

if ! grep -q "^APP_KEY=base64" .env; then
  php artisan key:generate --force
fi

php artisan config:cache || true
php artisan view:cache || true

php artisan migrate --force

cat > /tmp/router.php <<'PHP'
<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$file = '/var/www/html' . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}
require '/var/www/html/index.php';
PHP

php -S 0.0.0.0:8000 -t /var/www/html /tmp/router.php
SH

RUN sed -i 's/\r$//' /usr/local/bin/start-backend.sh

RUN chmod +x /usr/local/bin/start-backend.sh

CMD ["/usr/local/bin/start-backend.sh"]
