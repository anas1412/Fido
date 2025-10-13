@echo off
ECHO This script will reset the application database.
ECHO It will delete the existing database file, create a new empty one,
ECHO and run migrations to set up the database schema and initial data.
ECHO.

SET "APP_DATA_PATH=%APPDATA%"
SET "DB_FILE_NAME=database.sqlite"

SET "FIDO_DB_PATH=%APP_DATA_PATH%\Fido\%DB_FILE_NAME%"
SET "FIDO_DEMO_DB_PATH=%APP_DATA_PATH%\Fido Demo\%DB_FILE_NAME%"

SET "DB_WAS_RESET=0"

ECHO Checking for standard database...
IF EXIST "%FIDO_DB_PATH%" (
    ECHO Found database at: %FIDO_DB_PATH%
    ECHO Deleting...
    del /F /Q "%FIDO_DB_PATH%"
    ECHO Recreating...
    fsutil file createnew "%FIDO_DB_PATH%" 0 > nul
    ECHO Standard database has been reset.
    SET "DB_WAS_RESET=1"
) ELSE (
    ECHO Standard database not found at %FIDO_DB_PATH%.
)

ECHO.
ECHO Checking for demo database...
IF EXIST "%FIDO_DEMO_DB_PATH%" (
    ECHO Found database at: %FIDO_DEMO_DB_PATH%
    ECHO Deleting...
    del /F /Q "%FIDO_DEMO_DB_PATH%"
    ECHO Recreating...
    fsutil file createnew "%FIDO_DEMO_DB_PATH%" 0 > nul
    ECHO Demo database has been reset.
    SET "DB_WAS_RESET=1"
) ELSE (
    ECHO Demo database not found at %FIDO_DEMO_DB_PATH%.
)

ECHO.

IF %DB_WAS_RESET% EQU 1 (
    ECHO ---
    ECHO Running database migrations and seeding initial data...
    ECHO This may take a moment.
    ECHO.
    php artisan migrate:fresh --seed
    ECHO.
    ECHO ---
    ECHO Database reset complete.
    ECHO You may now need to seed the admin or demo user.
    ECHO - php artisan seed:admin
    ECHO - php artisan seed:demo
) ELSE (
    ECHO No database was found to reset.
    ECHO.
    CHOICE /C YN /M "Do you want to create a new database in the standard Fido location?"
    IF ERRORLEVEL 2 GOTO :EOF

    ECHO.
    ECHO Creating a new database...
    IF NOT EXIST "%APP_DATA_PATH%\Fido" (
        ECHO Creating directory: %APP_DATA_PATH%\Fido
        mkdir "%APP_DATA_PATH%\Fido"
    )
    fsutil file createnew "%FIDO_DB_PATH%" 0 > nul
    ECHO.
    ECHO ---
    ECHO Running database migrations and seeding initial data...
    php artisan migrate:fresh --seed
    ECHO ---
    ECHO New database created and seeded successfully.
    ECHO You may now need to seed the admin or demo user.
    ECHO - php artisan seed:admin
    ECHO - php artisan seed:demo
)

ECHO.
pause
