<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: hotspot management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hotspot Management - daloRADIUS</title>
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
                    <h2>Hotspot Management</h2>
                    <h3>Manage hotspot configurations and users</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>Hotspot Overview</h3>
                        <p>This page provides access to hotspot management features:</p>
                        <ul>
                            <li><strong>Hotspot Users:</strong> Manage hotspot user accounts</li>
                            <li><strong>Hotspot Configuration:</strong> Configure hotspot settings</li>
                            <li><strong>Hotspot Monitoring:</strong> Monitor active sessions</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="mng-hs-list.php" class="button">View All Hotspots</a>
                            <a href="mng-hs-new.php" class="button">Add New Hotspot</a>
                            <a href="acct-hotspot.php" class="button">Hotspot Accounting</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
