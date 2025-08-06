@echo off
setlocal

:: === CONFIG ===
set "PROJECT_DIR=%~dp0"
set "PHP_DIR=%PROJECT_DIR%php"
set "PHP_EXECUTABLE=%PHP_DIR%\php.exe"

:: === Go to project directory ===
cd /d "%PROJECT_DIR%" || (
    echo [ERROR] Failed to change directory to %PROJECT_DIR%
    goto end
)

:: === Run Optimizations ===
echo Running Laravel production optimizations...

if not exist "%PHP_EXECUTABLE%" (
    echo [ERROR] PHP executable not found at %PHP_EXECUTABLE%
    echo Please run the main server.bat script first to download it.
    goto end
)

%PHP_EXECUTABLE% artisan route:clear
%PHP_EXECUTABLE% artisan optimize
if %errorlevel% neq 0 (
    echo [ERROR] Optimization failed.
    goto end
)

echo.
echo [OK] Application has been optimized for production.

:end
echo.
echo Press any key to exit...
pause >nul
exit /b
