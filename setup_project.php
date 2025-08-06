<?php
/*
 * Setup Script untuk daloRADIUS
 * Jalankan script ini setelah clone project
 */

echo "=== Setup daloRADIUS Project ===\n\n";

// Check requirements
echo "1. Checking requirements...\n";
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    echo "✓ PHP version: " . PHP_VERSION . "\n";
} else {
    die("✗ PHP version must be 7.0 or higher\n");
}

if (extension_loaded('mysqli')) {
    echo "✓ mysqli extension: OK\n";
} else {
    echo "✗ mysqli extension: NOT FOUND\n";
    echo "  Please enable mysqli in php.ini\n";
}

if (extension_loaded('pdo_mysql')) {
    echo "✓ PDO MySQL extension: OK\n";
} else {
    echo "✗ PDO MySQL extension: NOT FOUND\n";
}

echo "\n2. Database setup...\n";
echo "Please run these commands manually:\n";
echo "1. Start MySQL service\n";
echo "2. Create database: CREATE DATABASE radius;\n";
echo "3. Import data: mysql -u root -p radius < contrib/db/mysql-daloradius.sql\n";

echo "\n3. Configuration check...\n";
$configFile = 'library/daloradius.conf.php';
if (file_exists($configFile)) {
    echo "✓ Configuration file exists\n";
    echo "  Please check database settings in: $configFile\n";
} else {
    echo "✗ Configuration file not found\n";
}

echo "\n4. Web server check...\n";
if (isset($_SERVER['HTTP_HOST'])) {
    echo "✓ Web server: " . $_SERVER['HTTP_HOST'] . "\n";
} else {
    echo "✗ Web server not detected\n";
    echo "  Please run via web server (Apache/Nginx)\n";
}

echo "\n=== Setup Complete ===\n";
echo "Next steps:\n";
echo "1. Configure database in library/daloradius.conf.php\n";
echo "2. Start Apache & MySQL\n";
echo "3. Access: http://localhost/daloradius\n";
echo "4. Login: administrator / radius\n";
?> 