<?php 
	isset($_REQUEST['error']) ? $error = $_REQUEST['error'] : $error = "";
	
	// clean up error code to avoid XSS
	$error = strip_tags(htmlspecialchars($error));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<title>LJN Login</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
</head>
<body onLoad="document.login.operator_user.focus()">
<?php include_once ("lang/main.php"); ?>

<div id="wrapper">
    <div id="innerwrapper">
        <div id="logo">
            <img src="images/logo.png" alt="LJN Logo" />
        </div>
        <div id="sidebar">
            <h2>Login Required</h2>
            <h3 style="color: #ff9800;">Login Please</h3>
            <?php if ($error) { ?>
                <div id="error-message">
                    <?php echo $error; ?>
                    <?php echo t('messages','loginerror'); ?>
                </div>
            <?php } ?>
            <form name="login" action="dologin.php" method="post">
                <label for="operator_user">Username</label>
                <input name="operator_user" id="operator_user" value="administrator" type="text" tabindex=1 />

                <label for="operator_pass">Password</label>
                <input name="operator_pass" id="operator_pass" value="" type="password" tabindex=2 />

                <label for="location">Location</label>
                <select name="location" id="location" tabindex=3 class="generic">
                    <?php
                        if (isset($configValues['CONFIG_LOCATIONS']) && is_array($configValues['CONFIG_LOCATIONS']) && count($configValues['CONFIG_LOCATIONS']) > 0) {
                            foreach ($configValues['CONFIG_LOCATIONS'] as $locations=>$val)
                                echo "<option value='$locations'>$locations</option>";
                        } else {
                            echo "<option value='default'>Default</option>";	
                        }
                    ?>
                </select>
                <input class="sidebutton" type="submit" value="Login" tabindex=4 />
            </form>
        </div>

        <div id="goals-section">
            <h2 style="text-align:center; margin-top:48px;">Tujuan LJN</h2>
            <p style="text-align:center; font-size:1.2em; margin-bottom:32px;">
                Membangun ekosistem jaringan digital yang menghubungkan seluruh Nusantara dengan teknologi terdepan.
            </p>
            <div class="goals-grid">
                <div class="goal-box" style="background:#43a047;">
                    <img src="images/nasional.png" alt="Konektivitas Nasional" />
                    <div class="goal-title">Konektivitas Nasional</div>
                </div>
                <div class="goal-box" style="background:#1e88e5;">
                    <img src="images/teknologi.png" alt="Inovasi Teknologi" />
                    <div class="goal-title">Inovasi Teknologi</div>
                </div>
                <div class="goal-box" style="background:#fb8c00;">
                    <img src="images/masyarakat.png" alt="Pemberdayaan Masyarakat" />
                    <div class="goal-title">Pemberdayaan Masyarakat</div>
                </div>
                <div class="goal-box" style="background:#757575;">
                    <img src="images/kamanan.png" alt="Keamanan Jaringan" />
                    <div class="goal-title">Keamanan Jaringan</div>
                </div>
                <div class="goal-box" style="background:#8e24aa;">
                    <img src="images/pertumbuhan.png" alt="Pertumbuhan Ekonomi" />
                    <div class="goal-title">Pertumbuhan Ekonomi</div>
                </div>
                <div class="goal-box" style="background:#00897b;">
                    <img src="images/lingkungan.png" alt="Teknologi Ramah Lingkungan" />
                    <div class="goal-title">Teknologi Ramah Lingkungan</div>
                </div>
            </div>
        </div>
        <div id="footer" style="margin-top:32px;">
            <?php include 'page-footer.php'; ?>
        </div>
    </div>
</div>
</body>
</html>
