<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: all tickets";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Tickets - daloRADIUS</title>
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
                    <h2>All Tickets</h2>
                    <h3>View and manage all system tickets</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>Ticket Management</h3>
                        <p>This page provides access to ticket management features:</p>
                        <ul>
                            <li><strong>Active Tickets:</strong> View currently active tickets</li>
                            <li><strong>Closed Tickets:</strong> View resolved tickets</li>
                            <li><strong>Ticket Reports:</strong> Generate ticket reports</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="tiket-aktif.php" class="button">Active Tickets</a>
                            <a href="tiket-ditutup.php" class="button">Closed Tickets</a>
                            <a href="rep-main.php" class="button">Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
