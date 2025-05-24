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

REM Sprawdź, czy jest docker
where docker >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo Docker nie jest zainstalowany lub nie dodany do PATH.
    pause
    exit /b
)

REM Uruchomienie kontenera PostgreSQL, jeśli nie działa
echo Sprawdzanie, czy kontener PostgreSQL już działa...
docker ps --filter "name=laravel-postgres" --format "{{.Names}}" | findstr "laravel-postgres" >nul
IF %ERRORLEVEL% NEQ 0 (
    echo Uruchamianie kontenera PostgreSQL...
    docker rm -f laravel-postgres
    docker run --name laravel-postgres ^
    -e POSTGRES_USER=laravel ^
    -e POSTGRES_PASSWORD=secret ^
    -e POSTGRES_DB=laravel ^
    -p 5432:5432 ^
    -d postgres:12
) ELSE (
    echo Kontener PostgreSQL już działa.
)

REM Instalacja zależności
echo Instalowanie zależności przez Composer...
call composer install

REM Tworzenie .env jeśli nie istnieje
IF NOT EXIST ".env" (
    echo Tworzenie pliku .env...
    copy .env.example .env
)

REM Generowanie klucza aplikacji
echo Generowanie klucza aplikacji...
call php artisan key:generate

REM Uruchamianie migracji
echo Uruchamianie migracji...
call php artisan migrate
call php artisan db:seed
call php artisan storage:link

set SOURCE=public\images\placeholder.jpg
set DEST=public\storage\seedImage.jpg

if exist "%SOURCE%" (
    copy /Y "%SOURCE%" "%DEST%"
    echo Plik został skopiowany jako %DEST%
) else (
    echo Nie znaleziono pliku źródłowego: %SOURCE%
)

REM Uruchamianie serwera
echo Start serwera aplikacji...
start http://127.0.0.1:8000

call npm install
start cmd /k "npm run dev"
start cmd /k "php artisan serve"

pause
