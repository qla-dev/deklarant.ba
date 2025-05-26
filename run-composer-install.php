<?php
chdir(__DIR__ . '/app-service-laravel');
passthru('composer install --no-dev --optimize-autoloader 2>&1', $code);
http_response_code($code === 0 ? 200 : 500);
