@echo off
echo ========================================
echo Setup RADIUS Admin Panel
echo ========================================
echo.

echo 1. Checking XAMPP installation...
if exist "C:\xampp\apache\bin\httpd.exe" (
    echo ✓ XAMPP found
) else (
    echo ✗ XAMPP not found in C:\xampp
    echo Please install XAMPP first
    pause
    exit
)

echo.
echo 2. Starting XAMPP services...
echo Starting Apache...
start "" "C:\xampp\xampp-control.exe"

echo.
echo 3. Please do the following manually:
echo    - Start Apache in XAMPP Control Panel
echo    - Start MySQL in XAMPP Control Panel
echo    - Wait until both show green status
echo.

echo 4. Database setup commands:
echo    Open Command Prompt and run:
echo    cd C:\xampp\mysql\bin
echo    mysql -u root -e "CREATE DATABASE radius;"
echo    mysql -u root radius ^< C:\xampp\htdocs\radius-admin-panel\contrib\db\mysql-daloradius.sql
echo.

echo 5. Access the web:
echo    http://localhost/radius-admin-panel
echo    Login: administrator / radius
echo.

pause 