@echo off
setlocal enabledelayedexpansion

:: === CONFIG ===
set "PROJECT_DIR=%~dp0"
set "PHP_DIR=%PROJECT_DIR%php"
set "COMPOSER_PHAR=%PROJECT_DIR%composer.phar"
set "PHP_EXECUTABLE=%PHP_DIR%\php.exe"
set "COMPOSER_CMD=%PHP_EXECUTABLE% %COMPOSER_PHAR%"

:: === Go to project directory ===
echo Changing directory to the Laravel project...
cd /d "%PROJECT_DIR%" || (
    echo [ERROR] Failed to change directory to %PROJECT_DIR%
    goto end
)

:: === Set up PHP and Composer paths (assuming they exist from server.bat first run) ===
if not exist "%PHP_DIR%\php.exe" (
    echo [ERROR] PHP not found. Please run server.bat first to install required components.
    goto end
)
set "PATH=%PHP_DIR%;%PATH%"

if not exist "%COMPOSER_PHAR%" (
    echo [ERROR] Composer not found. Please run server.bat first to install required components.
    goto end
)

:: === Update Application ===
echo --- Starting Application Update ---

:pull_changes
echo [1/3] Pulling latest changes from the repository...
git pull origin main
if errorlevel 1 (
    echo [ERROR] Failed to pull changes from git. Please check your internet connection and git configuration.
    goto end
)
echo [OK] Code updated successfully.
echo.

:update_dependencies
echo [2/3] Updating dependencies with Composer...
%COMPOSER_CMD% install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo [ERROR] Composer install failed.
    goto end
)
echo [OK] Dependencies are up to date.
echo.

:run_migrations
echo [3/3] Applying database updates...
%PHP_EXECUTABLE% artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Database migration failed.
    goto end
)
echo [OK] Database is up to date.
echo.

echo --- Update Complete ---

:end
echo.
echo Press any key to exit...
pause >nul
exit /b
