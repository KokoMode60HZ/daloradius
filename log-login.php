<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: login logs";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Logs - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
</head>
<body>
    <div id="wrapper">
        <div id="innerwrapper">
            <?php include("menu-header.php"); ?>
            <div id="sidebar">
                <?php include("sidebar.php"); ?>
            </div>
            <div id="contentnorightbar">
                <div id="header">
                    <h2>Login Logs</h2>
                    <h3>View system login activity logs</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>Login Activity Monitoring</h3>
                        <p>This page provides access to login monitoring features:</p>
                        <ul>
                            <li><strong>Login History:</strong> View all login attempts</li>
                            <li><strong>Failed Logins:</strong> Monitor failed login attempts</li>
                            <li><strong>User Sessions:</strong> Track active user sessions</li>
                            <li><strong>Security Alerts:</strong> Monitor security events</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="rep-logs-daloradius.php" class="button">System Logs</a>
                            <a href="rep-logs-radius.php" class="button">RADIUS Logs</a>
                            <a href="rep-online.php" class="button">Online Users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
