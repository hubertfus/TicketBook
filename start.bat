@echo off
echo ===============================
echo Laravel Starter Script
echo ===============================

REM Check if composer is installed
where.exe composer >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo Composer is not installed or not added to PATH.
    pause
    exit /b
)

REM Check if postgres is installed and in PATH
where.exe postgres >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo PostgreSQL is not installed or not added to PATH.
    pause
    exit /b
)

REM Get full path to postgres.exe
for /f "delims=" %%i in ('where.exe postgres') do (
    set "PG_EXE=%%i"
    set "PG_PATH=%%~dpi"
)

echo Found postgres.exe at: %PG_EXE%
echo PostgreSQL bin directory: %PG_PATH%

REM Check if PostgreSQL is running
echo Checking if PostgreSQL server is running...
tasklist.exe /FI "IMAGENAME eq postgres.exe" | findstr /I "postgres.exe" >nul
IF %ERRORLEVEL% NEQ 0 (
    echo PostgreSQL is not running.

    REM Check if pg_ctl.exe exists in the bin directory
    if exist "%PG_PATH%pg_ctl.exe" (
        REM NOTE: PROVIDE THE CORRECT DATA DIRECTORY PATH:
        "%PG_PATH%pg_ctl.exe" start -D "C:\Program Files\PostgreSQL\17\data" -l logfile
    ) else (
        echo pg_ctl.exe not found in %PG_PATH%. Please check your PostgreSQL installation.
        pause
        exit /b
    )

    timeout /t 5
) else (
    echo PostgreSQL is already running.
)

REM Install PHP dependencies using Composer
echo Installing PHP dependencies via Composer...
call composer install

REM Create .env if it does not exist
IF NOT EXIST ".env" (
    echo Creating .env file...
    copy .env.example .env
)

REM Generate application key
echo Generating Laravel application key...
call php artisan key:generate

REM Run database migrations
echo Running database migrations...
call php artisan migrate
call php artisan db:seed
call php artisan storage:link

call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
call php artisan optimize

set SOURCE=public\images\placeholder.jpg
set DEST=public\storage\seedImage.jpg

if exist "%SOURCE%" (
    copy /Y "%SOURCE%" "%DEST%"
    echo File copied as %DEST%
) else (
    echo Source file not found: %SOURCE%
)

REM Start application servers
echo Starting application server...
start http://127.0.0.1:8000

call npm install
start cmd /k "npm run dev"
start cmd /k "php artisan serve"

pause
