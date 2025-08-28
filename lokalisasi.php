<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: localization settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Localization Settings - daloRADIUS</title>
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
                    <h2>Localization Settings</h2>
                    <h3>Configure language and regional settings</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>Language & Regional Configuration</h3>
                        <p>This page provides access to localization settings:</p>
                        <ul>
                            <li><strong>Language Settings:</strong> Configure system language</li>
                            <li><strong>Regional Settings:</strong> Set timezone and date format</li>
                            <li><strong>Currency Settings:</strong> Configure currency display</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="config-lang.php" class="button">Language Settings</a>
                            <a href="config-interface.php" class="button">Interface Settings</a>
                            <a href="config-main.php" class="button">General Settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
