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

runOrFail('php artisan migrate --force');
runOrFail('php artisan config:clear');
runOrFail('php artisan cache:clear');
runOrFail('php artisan view:clear');
runOrFail('php artisan route:clear');
runOrFail('php artisan config:cache');
runOrFail('php artisan route:cache');
runOrFail('php artisan view:cache');

chdir(__DIR__ . '/customs-api');

runOrFail("ssh deklarant-ai.ba '~/deploy-deklarant.sh'");

http_response_code(200);
