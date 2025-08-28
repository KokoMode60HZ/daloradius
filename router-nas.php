<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: router nas management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Router [NAS] Management - daloRADIUS</title>
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
                    <h2>Router [NAS] Management</h2>
                    <h3>Manage Network Access Server configurations</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>NAS Configuration</h3>
                        <p>This page provides access to NAS management features:</p>
                        <ul>
                            <li><strong>NAS List:</strong> View all configured NAS devices</li>
                            <li><strong>Add NAS:</strong> Configure new NAS device</li>
                            <li><strong>Edit NAS:</strong> Modify existing NAS settings</li>
                            <li><strong>Remove NAS:</strong> Delete NAS configurations</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="mng-rad-nas-list.php" class="button">View All NAS</a>
                            <a href="mng-rad-nas-new.php" class="button">Add New NAS</a>
                            <a href="mng-rad-nas-edit.php" class="button">Edit NAS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
