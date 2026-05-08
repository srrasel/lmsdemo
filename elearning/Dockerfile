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

CMD ["bash", "-lc", "set -euo pipefail\ncd /var/www/html/core\n\nif [ ! -f .env ]; then cp .env.example .env; fi\nln -sfn /var/www/html/assets /var/www/html/core/assets\n\nif [ -n \"${APP_KEY:-}\" ]; then\n  if grep -q \"^APP_KEY=\" .env; then\n    sed -i \"s/^APP_KEY=.*/APP_KEY=${APP_KEY}/\" .env\n  else\n    printf \"\\nAPP_KEY=%s\\n\" \"${APP_KEY}\" >> .env\n  fi\nfi\n\nexport DB_HOST=\"${DB_HOST:-db}\"\nexport DB_PORT=\"${DB_PORT:-3306}\"\nexport DB_DATABASE=\"${DB_DATABASE:-elearning}\"\nexport DB_USERNAME=\"${DB_USERNAME:-root}\"\nexport DB_PASSWORD=\"${DB_PASSWORD:-root}\"\n\nuntil php -r \"new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));\" >/dev/null 2>&1; do echo waiting for mysql; sleep 2; done\n\ncomposer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts\ncomposer dump-autoload --optimize\nphp artisan package:discover --ansi\n\nif ! grep -q \"^APP_KEY=base64\" .env; then php artisan key:generate --force; fi\n\nphp artisan config:cache || true\nphp artisan view:cache || true\n\nphp artisan migrate --force\n\ncat > /tmp/router.php <<'PHP'\n<?php\n$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';\n$file = '/var/www/html' . $path;\nif ($path !== '/' && is_file($file)) {\n    return false;\n}\nrequire '/var/www/html/index.php';\nPHP\n\nphp -S 0.0.0.0:8000 -t /var/www/html /tmp/router.php\"]
