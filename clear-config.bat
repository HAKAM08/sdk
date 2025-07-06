@echo off
echo Clearing Laravel configuration cache...

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo Configuration cache cleared successfully!
echo.
echo You can now test the email functionality with the following URLs:
echo - http://localhost:8000/debug/email-test-ui (Web interface for testing emails)
echo - http://localhost:8000/debug/email-test (Simple email test)
echo.
echo Or run the following Artisan command:
echo php artisan email:test your-email@example.com
echo.

pause