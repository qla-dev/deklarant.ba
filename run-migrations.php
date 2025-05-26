<?php
chdir(__DIR__ . '/app-service-laravel');
passthru('php artisan migrate --force 2>&1', $code);
http_response_code($code === 0 ? 200 : 500);
