<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: payment gateway settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Gateway Settings - daloRADIUS</title>
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
                    <h2>Payment Gateway Settings</h2>
                    <h3>Configure payment system and gateway settings</h3>
                </div>
                <div id="content">
                    <div class="contentbox">
                        <h3>Payment System Configuration</h3>
                        <p>This page provides access to payment gateway features:</p>
                        <ul>
                            <li><strong>Payment Dashboard:</strong> Monitor payment transactions</li>
                            <li><strong>Checkout System:</strong> Manage payment checkout</li>
                            <li><strong>Bank Accounts:</strong> Configure bank account details</li>
                            <li><strong>Transaction History:</strong> View payment history</li>
                        </ul>
                        
                        <div class="button-container">
                            <a href="payment-index.php" class="button">Payment Dashboard</a>
                            <a href="checkout.php" class="button">Checkout System</a>
                            <a href="admin-bank-accounts.php" class="button">Bank Accounts</a>
                            <a href="admin/payment-dashboard.php" class="button">Admin Panel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("page-footer.php"); ?>
</body>
</html>
