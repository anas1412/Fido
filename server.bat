@echo off
setlocal enabledelayedexpansion

:: === CONFIG ===
set "PROJECT_DIR=%~dp0"
set "PHP_DIR=%PROJECT_DIR%php"
set "COMPOSER_PHAR=%PROJECT_DIR%composer.phar"
set "PHP_EXECUTABLE=php"
set "COMPOSER_CMD=composer"

:: === Go to project directory ===
echo Changing directory to the Laravel project...
cd /d "%PROJECT_DIR%" || (
    echo [ERROR] Failed to change directory to %PROJECT_DIR%
    goto end
)

:: === Set up Environment ===
echo Setting up environment...

:: Check for local PHP
if exist "%PHP_DIR%\php.exe" goto :php_setup_complete

:: --- PHP Download and Setup Logic ---
echo -^> Local PHP not found. Attempting to download and install it automatically.
echo    This may take a few moments depending on your internet connection.

:download_php
echo    Downloading PHP (approx. 30MB)...
curl -L "https://windows.php.net/downloads/releases/php-8.3.24-Win32-vs16-x64.zip" -o "php.zip"
if errorlevel 1 (
    echo [ERROR] Failed to download PHP. Please check your internet connection.
    goto end
)

:extract_php
echo    Creating PHP directory...
mkdir "%PHP_DIR%" >nul

echo    Extracting PHP files...
tar -xf php.zip -C "%PHP_DIR%"
if errorlevel 1 (
    echo [ERROR] Failed to extract PHP. Please ensure you have permissions to create files.
    del php.zip
    goto end
)

:configure_php
echo    Configuring PHP...
del "%PHP_DIR%\php.ini" >nul 2>nul
copy "%PROJECT_DIR%php.ini" "%PHP_DIR%\php.ini" >nul
if errorlevel 1 (
    echo [ERROR] Failed to copy custom php.ini. Make sure php.ini exists in the project root.
    goto end
)

:cleanup_php_zip
echo    Cleaning up installation files...
del php.zip

echo [OK] PHP has been set up successfully.
echo.

:php_setup_complete
set "PATH=%PHP_DIR%;%PATH%"
set "PHP_EXECUTABLE=%PHP_DIR%\php.exe"

:: Check PHP version
echo Checking PHP version...
for /f "delims=" %%i in ('%PHP_EXECUTABLE% -r "echo PHP_VERSION;" 2^>nul') do set "PHP_VERSION=%%i"

if not defined PHP_VERSION (
    echo [ERROR] PHP is not working correctly.
    goto end
)
echo [OK] PHP version OK: !PHP_VERSION!

:: Check for Composer
if exist "%COMPOSER_PHAR%" goto :composer_ready

:download_composer
echo -^> Local composer.phar not found. Attempting to download it automatically...
%PHP_EXECUTABLE% -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
if not exist "composer-setup.php" (
    echo [ERROR] Failed to download composer-setup.php.
    goto end
)

:verify_composer
echo Verifying installer...
%PHP_EXECUTABLE% -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
if %errorlevel% neq 0 (
    echo [ERROR] Composer installer verification failed. The hash may be outdated.
    goto end
)

:install_composer
echo Installing Composer...
%PHP_EXECUTABLE% composer-setup.php --quiet
%PHP_EXECUTABLE% -r "unlink('composer-setup.php');"
if not exist "%COMPOSER_PHAR%" (
    echo [ERROR] Composer installation failed.
    goto end
)
echo [OK] Composer downloaded successfully.

:composer_ready
set "COMPOSER_CMD=%PHP_EXECUTABLE% %COMPOSER_PHAR%"

:: === Project Setup ===
:pull_changes
echo Pulling latest changes from main branch...
git pull origin main || (
    echo [ERROR] Git pull failed.
    goto end
)

:install_dependencies
if exist "vendor" goto :dependencies_installed
echo "vendor" folder not found. Running composer install...
%COMPOSER_CMD% install --no-dev --optimize-autoloader || (
    echo [ERROR] Composer install failed.
    goto end
)
:dependencies_installed

:setup_env_file
if exist ".env" goto :env_file_exists
echo .env file not found. Copying from .env.example...
copy .env.example .env >nul
:env_file_exists

:run_migrations
if exist "database\database.sqlite" goto :database_exists
echo database.sqlite not found. Running migrations with seed...
%PHP_EXECUTABLE% artisan migrate --seed || (
    echo [ERROR] Migrate --seed failed.
    goto end
)
:database_exists

:: === Start Laravel server ===
echo Starting Laravel server...
start /B %PHP_EXECUTABLE% artisan serve || (
    echo [ERROR] Failed to start Laravel server.
    goto end
)

:: === Open Browser ===
echo Opening browser at http://127.0.0.1:8000 ...
start "" "http://127.0.0.1:8000"

:: === Done ===
echo ------------------------------------------
echo [OK] Laravel server should now be running!
echo -^> Visit: http://127.0.0.1:8000

:: === Display Admin Credentials ===
echo.
echo --- Admin Credentials ---
for /f "tokens=1,* delims==" %%a in ('findstr /B /C:"ADMIN_EMAIL=" .env') do (
    echo   Email: %%b
)
for /f "tokens=1,* delims==" %%a in ('findstr /B /C:"ADMIN_PASSWORD=" .env') do (
    echo   Password: %%b
)
echo   Dashboard: http://127.0.0.1:8000/admin
echo ------------------------------------------

:end
echo.
echo Press any key to exit...
pause >nul
exit /b
