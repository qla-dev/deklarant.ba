<?php
chdir(__DIR__ . '/app-service-laravel');
passthru('php artisan migrate --force 2>&1', $code);
passthru('php artisan config:clear 2>&1', $code);
passthru('php artisan cache:clear 2>&1', $code);
passthru('php artisan view:clear 2>&1', $code);
passthru('php artisan route:clear 2>&1', $code);
http_response_code($code === 0 ? 200 : 500);
