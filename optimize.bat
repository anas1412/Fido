@echo off
setlocal

:: === CONFIG ===
set "PROJECT_DIR=%~dp0"
set "PHP_DIR=%PROJECT_DIR%php"
set "PHP_EXECUTABLE=%PHP_DIR%\php.exe"
:: Assuming composer is in PATH or directly runnable from PROJECT_DIR
set "COMPOSER_EXECUTABLE=composer.exe"

:: === Go to project directory ===
cd /d "%PROJECT_DIR%" || (
    echo [ERROR] Failed to change directory to "%PROJECT_DIR%". Exiting.
    goto :end
)

:: === Check PHP Executable ===
if not exist "%PHP_EXECUTABLE%" (
    echo [ERROR] PHP executable not found at "%PHP_EXECUTABLE%".
    echo Please ensure PHP is correctly set up or run the main server.bat script first.
    goto :end
)

:: === Run Optimizations ===
echo.
echo Starting Laravel application optimization...
echo.

:: Clear route cache (good practice before re-optimizing)
echo Clearing route cache...
"%PHP_EXECUTABLE%" artisan route:clear
if %errorlevel% neq 0 (
    echo [ERROR] Failed to clear route cache.
    goto :end
)

:: Optimize Composer autoloader for production
echo Optimizing Composer autoloader...
"%COMPOSER_EXECUTABLE%" dump-autoload --optimize
if %errorlevel% neq 0 (
    echo [ERROR] Failed to optimize Composer autoloader.
    goto :end
)

:: Run Laravel's main optimization command (caches config, routes, views, events)
echo Running Laravel's main optimization command...
"%PHP_EXECUTABLE%" artisan optimize
if %errorlevel% neq 0 (
    echo [ERROR] Laravel optimization failed.
    goto :end
)

echo.
echo [SUCCESS] Application has been optimized for production.

:end
echo.
echo Press any key to exit...
pause >nul
exit /b