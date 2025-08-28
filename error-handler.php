<?php
/**
 * Error Handler untuk daloRADIUS Payment System
 * Menangani error dan warning dengan lebih baik
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/php_errors.log');

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorType = '';
    
    switch ($errno) {
        case E_ERROR:
            $errorType = 'FATAL ERROR';
            break;
        case E_WARNING:
            $errorType = 'WARNING';
            break;
        case E_PARSE:
            $errorType = 'PARSE ERROR';
            break;
        case E_NOTICE:
            $errorType = 'NOTICE';
            break;
        case E_STRICT:
            $errorType = 'STRICT';
            break;
        case E_DEPRECATED:
            $errorType = 'DEPRECATED';
            break;
        default:
            $errorType = 'UNKNOWN';
    }
    
    $errorMessage = "[{$errorType}] {$errstr} in {$errfile} on line {$errline}";
    
    // Log error
    error_log($errorMessage);
    
    // Display error in development mode
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 10px; border-radius: 4px;'>";
        echo "<strong>Error:</strong> {$errstr}<br>";
        echo "<strong>File:</strong> {$errfile}<br>";
        echo "<strong>Line:</strong> {$errline}<br>";
        echo "</div>";
    }
    
    return true;
}

// Set custom error handler
set_error_handler('customErrorHandler');

// Exception handler
function customExceptionHandler($exception) {
    $errorMessage = "Uncaught Exception: " . $exception->getMessage() . 
                   " in " . $exception->getFile() . 
                   " on line " . $exception->getLine();
    
    error_log($errorMessage);
    
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 10px; border-radius: 4px;'>";
        echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
        echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
        echo "<strong>Stack Trace:</strong><br><pre>" . $exception->getTraceAsString() . "</pre>";
        echo "</div>";
    }
}

set_exception_handler('customExceptionHandler');

// Fatal error handler
function fatalErrorHandler() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        $errorMessage = "FATAL ERROR: " . $error['message'] . 
                       " in " . $error['file'] . 
                       " on line " . $error['line'];
        
        error_log($errorMessage);
        
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
            echo "<div style='background: #dc3545; border: 1px solid #c82333; color: white; padding: 15px; margin: 10px; border-radius: 4px;'>";
            echo "<strong>FATAL ERROR:</strong> " . $error['message'] . "<br>";
            echo "<strong>File:</strong> " . $error['file'] . "<br>";
            echo "<strong>Line:</strong> " . $error['line'] . "<br>";
            echo "</div>";
        }
    }
}

register_shutdown_function('fatalErrorHandler');

// Development mode (set to false in production)
define('DEVELOPMENT_MODE', true);

// Function to safely include files
function safeInclude($file) {
    if (file_exists($file)) {
        try {
            include_once $file;
            return true;
        } catch (Exception $e) {
            error_log("Error including file {$file}: " . $e->getMessage());
            return false;
        }
    } else {
        error_log("File not found: {$file}");
        return false;
    }
}

// Function to safely require files
function safeRequire($file) {
    if (file_exists($file)) {
        try {
            require_once $file;
            return true;
        } catch (Exception $e) {
            error_log("Error requiring file {$file}: " . $e->getMessage());
            return false;
        }
    } else {
        error_log("File not found: {$file}");
        return false;
    }
}

// Function to check if database connection is working
function checkDatabaseConnection() {
    try {
        if (safeRequire('library/DB.php')) {
            $db = DB::connect("mysql://root:@localhost:3306/radius");
            if ($db && !$db->isError()) {
                return true;
            }
        }
        return false;
    } catch (Exception $e) {
        error_log("Database connection check failed: " . $e->getMessage());
        return false;
    }
}

// Function to display system status
function displaySystemStatus() {
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; margin: 10px; border-radius: 4px;'>";
    echo "<strong>System Status:</strong><br>";
    echo "PHP Version: " . phpversion() . "<br>";
    echo "Database Connection: " . (checkDatabaseConnection() ? "✅ OK" : "❌ Failed") . "<br>";
    echo "Error Reporting: " . (error_reporting() ? "✅ Enabled" : "❌ Disabled") . "<br>";
    echo "Display Errors: " . (ini_get('display_errors') ? "✅ Enabled" : "❌ Disabled") . "<br>";
    echo "</div>";
}

// Auto-include this file in all pages
if (!defined('ERROR_HANDLER_INCLUDED')) {
    define('ERROR_HANDLER_INCLUDED', true);
}
?>
