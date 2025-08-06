@echo off
setlocal enabledelayedexpansion

:: === CONFIG ===
set PROJECT_DIR=D:\Devs\Fido
set VALID_PHP_VERSION1=8.2
set VALID_PHP_VERSION2=8.3

:: === Go to project directory ===
echo Changing directory to the Laravel project...
cd /d "%PROJECT_DIR%" || (
    echo ❌ ERROR: Failed to change directory to %PROJECT_DIR%
    goto end
)

:: === Check PHP version ===
echo Checking PHP version...
for /f "delims=" %%i in ('php -r "echo PHP_VERSION;" 2^>nul') do set PHP_VERSION=%%i

if not defined PHP_VERSION (
    echo ❌ ERROR: PHP is not installed or not in PATH.
    goto end
)

set PHP_MAJOR_MINOR=!PHP_VERSION:~0,3!

if /i not "!PHP_MAJOR_MINOR!"=="%VALID_PHP_VERSION1%" if /i not "!PHP_MAJOR_MINOR!"=="%VALID_PHP_VERSION2%" (
    echo ❌ ERROR: Invalid PHP version detected: !PHP_VERSION!
    echo ➤ Required: PHP 8.2 or 8.3
    goto end
)

echo ✅ PHP version OK: !PHP_VERSION!

:: === Check Git ===
echo Checking if Git is installed...
git --version >nul 2>nul
if errorlevel 1 (
    echo ❌ ERROR: Git is not installed.
    start https://git-scm.com/download/win
    goto end
)

:: === Pull latest changes ===
echo Pulling latest changes from main branch...
git pull origin main || (
    echo ❌ ERROR: Git pull failed.
    goto end
)

:: === Check vendor folder ===
if not exist "vendor" (
    echo "vendor" folder not found. Running composer install...
    composer install || (
        echo ❌ ERROR: Composer install failed.
        goto end
    )
) else (
    echo ✅ "vendor" folder exists. Skipping composer install.
)

:: === Check for database.sqlite ===
if not exist "database\database.sqlite" (
    echo ❌ database.sqlite not found. Running migrations with seed...
    php artisan migrate --seed || (
        echo ❌ ERROR: Migrate --seed failed.
        goto end
    )
) else (
    echo ✅ database.sqlite found. Skipping migration.
)

:: === Start Laravel server ===
echo Starting Laravel server...
start /B php artisan serve || (
    echo ❌ ERROR: Failed to start Laravel server.
    goto end
)

:: === Open Browser ===
echo Opening browser at http://127.0.0.1:8000 ...
start "" "http://127.0.0.1:8000"

:: === Done ===
echo ------------------------------------------
echo ✅ Laravel server should now be running!
echo ➤ Visit: http://127.0.0.1:8000

:: === Display Admin Credentials ===
echo.
echo --- Admin Credentials ---
for /f "tokens=1,2 delims==" %%a in ('findstr "ADMIN_EMAIL" .env') do (
    if "%%a"=="ADMIN_EMAIL" (
        echo   Email: %%b
    )
)
for /f "tokens=1,2 delims==" %%a in ('findstr "ADMIN_PASSWORD" .env') do (
    if "%%a"=="ADMIN_PASSWORD" (
        echo   Password: %%b
    )
)
echo   Dashboard: http://127.0.0.1:8000/admin
echo ------------------------------------------

:end
echo.
echo Press any key to exit...
pause >nul
exit /b
