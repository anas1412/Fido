@echo OFF

echo Wiping the database and preparing for demo data...
php artisan migrate:fresh

echo Seeding database with demo user and data only...
php artisan db:seed --class=DemoUserSeeder

echo.
echo Demo database reset complete.

pause