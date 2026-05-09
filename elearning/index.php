<?php

use Illuminate\Http\Request as IlluminateRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/core/vendor/autoload.php';

$trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? getenv('TRUSTED_PROXIES') ?: null;
if ($trustedProxies) {
    $headers = defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_ALL')
        ? SymfonyRequest::HEADER_X_FORWARDED_ALL
        : (SymfonyRequest::HEADER_X_FORWARDED_FOR
            | SymfonyRequest::HEADER_X_FORWARDED_HOST
            | SymfonyRequest::HEADER_X_FORWARDED_PORT
            | SymfonyRequest::HEADER_X_FORWARDED_PROTO
            | (defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_PREFIX') ? SymfonyRequest::HEADER_X_FORWARDED_PREFIX : 0));
    if ($trustedProxies === '*') {
        SymfonyRequest::setTrustedProxies(['0.0.0.0/0', '::/0'], $headers);
    } else {
        $proxies = array_values(array_filter(array_map('trim', explode(',', $trustedProxies))));
        if ($proxies) {
            SymfonyRequest::setTrustedProxies($proxies, $headers);
        }
    }
}

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/core/bootstrap/app.php')
    ->handleRequest(IlluminateRequest::capture());
