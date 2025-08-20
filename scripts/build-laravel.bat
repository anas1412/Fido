@echo off
setlocal

rem Change directory to the project root (one level up from this script)
pushd "%~dp0..\"

echo "Optimizing composer dependencies for production..."
composer install --optimize-autoloader --no-dev

echo "Clearing Laravel caches..."
rem We only clear caches. We do not cache config/routes/views because the paths will be different on the user's machine.
php\php.exe artisan optimize:clear

echo "Laravel build script finished."

rem Return to the original directory
popd
endlocal
