<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? getenv('TRUSTED_PROXIES') ?: null;
if ($trustedProxies) {
    if ($trustedProxies === '*') {
        Request::setTrustedProxies(['0.0.0.0/0', '::/0'], Request::HEADER_X_FORWARDED_ALL);
    } else {
        $proxies = array_values(array_filter(array_map('trim', explode(',', $trustedProxies))));
        if ($proxies) {
            Request::setTrustedProxies($proxies, Request::HEADER_X_FORWARDED_ALL);
        }
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/core/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/core/bootstrap/app.php')
    ->handleRequest(Request::capture());
