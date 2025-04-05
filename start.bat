 @echo off
echo ===============================
echo Laravel Starter Script
echo ===============================

REM Sprawdź, czy jest composer
where composer >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo Composer nie jest zainstalowany lub nie dodany do PATH.
    pause
    exit /b
)

REM Instalacja zależności
echo Instalowanie zależności przez Composer...
composer install

REM Tworzenie .env jeśli nie istnieje
IF NOT EXIST ".env" (
    echo  Tworzenie pliku .env...
    copy .env.example .env
)

REM Generowanie klucza aplikacji
echo Generowanie klucza aplikacji...
php artisan key:generate

REM Uruchamianie migracji
echo Uruchamianie migracji...
php artisan migrate

REM Uruchamianie serwera
echo Start serwera aplikacji...
start http://127.0.0.1:8000
php artisan serve

pause
