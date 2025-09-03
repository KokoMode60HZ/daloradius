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
    <style>
        /* Scoped style untuk halaman ini */
        #pgw-page { padding: 24px; }
        #pgw-page .title { margin:0; color:#333; font-size:22px; font-weight:700; }
        #pgw-page .subtitle { margin:6px 0 16px; color:#607d8b; }
        #pgw-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        #pgw-page .card-body { padding:18px; }
        #pgw-page ul { margin: 0 0 12px 18px; }
        #pgw-page .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap:12px; margin-top:8px; }
        #pgw-page .btn { display:inline-block; text-align:center; padding:10px 12px; background:#009688; color:#fff; border-radius:6px; text-decoration:none; }
        #pgw-page .btn.secondary { background:#3f51b5; }
        #pgw-page .btn.warn { background:#ff9800; }
        #pgw-page .btn.dark { background:#455a64; }
    </style>
</head>
<body>
    	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar-new.php'; ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;">
        <div id="pgw-page">
            <h2 class="title">Payment Gateway Settings</h2>
            <div class="subtitle">Configure payment system and gateway settings</div>

            <div class="card">
                <div class="card-body">
                    <h3 style="margin-top:0;margin-bottom:8px;">Payment System Configuration</h3>
                    <p style="margin-top:0;">This page provides access to payment gateway features:</p>
                    <ul>
                        <li><strong>Payment Dashboard:</strong> Monitor payment transactions</li>
                        <li><strong>Checkout System:</strong> Manage payment checkout</li>
                        <li><strong>Bank Accounts:</strong> Configure bank account details</li>
                        <li><strong>Transaction History:</strong> View payment history</li>
                    </ul>

                    <div class="grid">
                        <a href="payment-index.php" class="btn">Payment Dashboard</a>
                        <a href="checkout.php" class="btn secondary">Checkout System</a>
                        <a href="admin-bank-accounts.php" class="btn warn">Bank Accounts</a>
                        <a href="admin/payment-dashboard.php" class="btn dark">Admin Panel</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="footer" style="padding:16px 24px;">
            <?php include 'page-footer.php'; ?>
        </div>
    </div>
</body>
</html> 
