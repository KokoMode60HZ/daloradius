<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: whatsapp api settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp API Settings - daloRADIUS</title>
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
                    <h2>WhatsApp API Settings</h2>
                    <h3>Configure WhatsApp integration and notifications</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>WhatsApp Configuration</h3>
                        <p>This page provides access to WhatsApp integration features:</p>
                        <ul>
                            <li><strong>WhatsApp Templates:</strong> Manage notification templates</li>
                            <li><strong>Direct WhatsApp:</strong> Send direct WhatsApp messages</li>
                            <li><strong>API Configuration:</strong> Configure WhatsApp API settings</li>
                            <li><strong>Notification Logs:</strong> View sent notifications</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="whatsapp-direct-test.php" class="button">WhatsApp Direct</a>
                            <a href="whatsapp-test.php" class="button">Test Notifications</a>
                            <a href="admin-bank-accounts.php" class="button">Bank Accounts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
