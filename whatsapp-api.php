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
    <style>
        /* Scoped style hanya untuk halaman ini */
        #wa-page { padding: 24px; }
        #wa-page .title { margin: 0; color: #333; font-size: 22px; font-weight: 700; }
        #wa-page .subtitle { margin: 6px 0 16px; color: #607d8b; }
        #wa-page .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        #wa-page .card-body { padding: 18px; }
        #wa-page ul { margin: 0 0 12px 18px; }
        #wa-page .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 8px; }
        #wa-page .btn { display: inline-block; text-align: center; padding: 10px 12px; background: #009688; color: #fff; border-radius: 6px; text-decoration: none; }
        #wa-page .btn.secondary { background: #3f51b5; }
        #wa-page .btn.warn { background: #ff9800; }
    </style>
</head>
<body>
    	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar-new.php'; ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;">
        <div id="wa-page">
            <h2 class="title">WhatsApp API Settings</h2>
            <div class="subtitle">Configure WhatsApp integration and notifications</div>

            <div class="card">
                <div class="card-body">
                    <h3 style="margin-top:0;margin-bottom:8px;">WhatsApp Configuration</h3>
                    <p style="margin-top:0;">This page provides access to WhatsApp integration features:</p>
                    <ul>
                        <li><strong>WhatsApp Templates:</strong> Manage notification templates</li>
                        <li><strong>Direct WhatsApp:</strong> Send direct WhatsApp messages</li>
                        <li><strong>API Configuration:</strong> Configure WhatsApp API settings</li>
                        <li><strong>Notification Logs:</strong> View sent notifications</li>
                    </ul>

                    <div class="grid">
                        <a href="whatsapp-direct-test.php" class="btn">WhatsApp Direct</a>
                        <a href="whatsapp-test.php" class="btn secondary">Test Notifications</a>
                        <a href="admin-bank-accounts.php" class="btn warn">Bank Accounts</a>
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
