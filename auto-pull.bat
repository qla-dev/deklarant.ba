@echo off
REM ───────────────────────────────────────────────
REM  File: auto-git-pull.bat
REM  Purpose: Pull latest commits every 60 seconds.
REM ───────────────────────────────────────────────

:LOOP
    echo [%DATE% %TIME%] Running git pull...
    git pull
    cd app-service-laravel
    php artisan migrate
    cd ..
    echo.
    REM Wait 60 seconds before next pull
    timeout /t 60 /nobreak >nul
goto LOOP