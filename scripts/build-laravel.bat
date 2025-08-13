@echo off
setlocal

set "DB_DATABASE_PATH=%APPDATA%\fido\storage\database.sqlite"
set "DB_STORAGE_DIR=%APPDATA%\fido\storage"

rem Ensure the directory exists
if not exist "%DB_STORAGE_DIR%" (
    mkdir "%DB_STORAGE_DIR%"
)

rem Ensure the database file exists
if not exist "%DB_DATABASE_PATH%" (
    type nul > "%DB_DATABASE_PATH%"
)

rem Set DB_DATABASE for the current session and run Laravel commands
set "DB_DATABASE=%DB_DATABASE_PATH%"
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

endlocal