@echo off
echo Starting Laravel Queue Worker...
cd /d %~dp0
php artisan queue:work --tries=3 --timeout=60