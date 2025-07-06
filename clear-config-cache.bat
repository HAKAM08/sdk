@echo off
echo Clearing Laravel Configuration Cache...
cd /d %~dp0
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo Done!
pause