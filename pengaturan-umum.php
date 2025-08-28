<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: general settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>General Settings - daloRADIUS</title>
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
                    <h2>General Settings</h2>
                    <h3>Configure general system settings</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>General Configuration</h3>
                        <p>This page provides access to general system configuration:</p>
                        <ul>
                            <li><strong>User Settings:</strong> Configure user preferences</li>
                            <li><strong>Interface Settings:</strong> Customize interface appearance</li>
                            <li><strong>System Settings:</strong> Configure system parameters</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="config-user.php" class="button">User Settings</a>
                            <a href="config-interface.php" class="button">Interface Settings</a>
                            <a href="config-main.php" class="button">System Settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
