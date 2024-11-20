@echo off
:: Set the path to your Laravel project directory
set PROJECT_DIR=D:\Devs\Fido

:: Change directory to the Laravel project
echo Changing directory to the Laravel project...
cd /d "%PROJECT_DIR%"

:: Check if artisan exists
if not exist artisan (
    echo ERROR: artisan file not found. Please check the Laravel setup.
    exit /b 1
)

:: Output information about the environment
echo Laravel project found! Preparing to start the server...

:: Run php artisan serve in the background
echo Starting "php artisan serve" in the background...
start /B php artisan serve --verbose

:: Optional: Success message
echo Laravel server is now running in the background! Access it at http://127.0.0.1:8000

:: Open the browser to the Laravel app
echo Opening the Laravel app in the default browser...
start "" "http://127.0.0.1:8000"

:: Keep the terminal window open
pause
