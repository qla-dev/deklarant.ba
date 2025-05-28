<?php
chdir(__DIR__ . '/app-service-laravel');

function runOrFail($command) {
    passthru($command, $code);
    if ($code !== 0) {
        http_response_code(500);
        exit("Command failed: $command\n");
    }
}

runOrFail('php artisan migrate --force 2>&1');
runOrFail('php artisan config:clear 2>&1');
runOrFail('php artisan cache:clear 2>&1');
runOrFail('php artisan view:clear 2>&1');
runOrFail('php artisan route:clear 2>&1');

http_response_code(200);
