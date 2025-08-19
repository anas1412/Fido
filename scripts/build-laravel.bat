@echo off
setlocal

rem set "DB_DATABASE_PATH_EXPANDED=%APPDATA%\fido\storage\database.sqlite"
set "DB_STORAGE_DIR=%APPDATA%\fido\storage"

rem Ensure the directory exists
if not exist "%DB_STORAGE_DIR%" (
    mkdir "%DB_STORAGE_DIR%"
)

rem Ensure the database file exists
if not exist "%DB_DATABASE_PATH_EXPANDED%" (
    type nul > "%DB_DATABASE_PATH_EXPANDED%"
)

rem Set DB_DATABASE for the current session and run Laravel commands
rem This is crucial for php artisan commands run directly by this script
rem set "DB_DATABASE=%DB_DATABASE_PATH_EXPANDED%"
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

endlocal