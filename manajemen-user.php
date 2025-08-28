<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: user management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management - daloRADIUS</title>
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
                    <h2>User Management</h2>
                    <h3>Manage system users and operators</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>User & Operator Management</h3>
                        <p>This page provides access to user management features:</p>
                        <ul>
                            <li><strong>Operator List:</strong> View all system operators</li>
                            <li><strong>Add Operator:</strong> Create new operator accounts</li>
                            <li><strong>Edit Operator:</strong> Modify operator settings</li>
                            <li><strong>Remove Operator:</strong> Delete operator accounts</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="config-operators-list.php" class="button">View Operators</a>
                            <a href="config-operators-new.php" class="button">Add Operator</a>
                            <a href="config-operators-edit.php" class="button">Edit Operator</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
