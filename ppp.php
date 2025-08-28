<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: ppp management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>PPP Management - daloRADIUS</title>
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
                    <h2>PPP Management</h2>
                    <h3>Manage PPP connections and configurations</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>PPP Overview</h3>
                        <p>This page provides access to PPP management features:</p>
                        <ul>
                            <li><strong>PPP Users:</strong> Manage PPP user accounts</li>
                            <li><strong>PPP Configuration:</strong> Configure PPP settings</li>
                            <li><strong>PPP Monitoring:</strong> Monitor active connections</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="mng-list-all.php" class="button">View All Users</a>
                            <a href="mng-new.php" class="button">Add New User</a>
                            <a href="acct-all.php" class="button">User Accounting</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
