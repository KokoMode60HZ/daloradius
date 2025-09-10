<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Clear any output buffering
while (ob_get_level()) {
    ob_end_clean();
}

// Set headers to prevent caching and force browser to reload login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// JavaScript to clear browser history and redirect
echo "<script>
    window.history.forward();
    window.onunload = function() { null };
    window.location.replace('login.php');
</script>";

// Redirect as fallback if JavaScript is disabled
header("Location: login.php");
exit();
?>
