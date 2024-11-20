@echo off
:: Set the path to your Laravel project directory
set PROJECT_DIR=D:\Devs\Fido

:: Change directory to the Laravel project
echo Changing directory to the Laravel project...
cd /d "%PROJECT_DIR%" || (echo ERROR: Failed to change directory. Exiting... & exit /b 1)

:: Check if Git is installed
echo Checking if Git is installed...
git --version >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Git is not installed.
    set /p INSTALL_GIT="Do you want to install Git? (Yes/No): "
    if /i "%INSTALL_GIT%"=="Yes" (
        echo Opening Git download page...
        start https://git-scm.com/download/win
    ) else (
        echo Please install Git manually and run the script again.
        exit /b 1
    )
)

:: Pull the latest changes from the main branch
echo Pulling the latest changes from the main branch...
git pull origin main || (echo ERROR: Git pull failed. Exiting... & exit /b 1)




:: Output information about the environment
echo Laravel project found! Preparing to start the server...

:: Run php artisan serve in the background
echo Starting "php artisan serve" in the background...
start /B php artisan serve --verbose || (echo ERROR: Failed to start Laravel server. Exiting... & exit /b 1)

:: Optional: Success message
echo Laravel server is now running in the background! Access it at http://127.0.0.1:8000

:: Open the browser to the Laravel app
echo Opening the Laravel app in the default browser...
start "" "http://127.0.0.1:8000" || (echo ERROR: Failed to open browser. Exiting... & exit /b 1)

:: Keep the terminal window open
pause
