<?php
chdir(__DIR__ . '/app-service-laravel');

function runOrFail($command) {
    $command = $command . ' 2>&1';
    passthru($command, $code);
    if ($code !== 0) {
        http_response_code(500);
        exit("Command failed: $command\n");
    }
}

runOrFail('composer install --no-dev --optimize-autoloader 2>&1');
chdir(__DIR__ . '/customs-api');
runOrFail('composer install --no-dev --optimize-autoloader 2>&1');
