<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: setting api";
	include('include/config/logging.php');

	$configPath = __DIR__ . '/config/client_api.php';
	$API_KEY = '';
	$API_SECRET = '';
	if (file_exists($configPath)) {
		include_once($configPath);
		if (defined('CLIENT_API_KEY')) $API_KEY = CLIENT_API_KEY;
		if (defined('CLIENT_API_SECRET')) $API_SECRET = CLIENT_API_SECRET;
	}

	function randomToken($length = 32) {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$out = '';
		for ($i=0; $i<$length; $i++) { $out .= $alphabet[random_int(0, strlen($alphabet)-1)]; }
		return $out;
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		if ($action === 'generate') {
			$API_KEY = randomToken(32);
			$API_SECRET = randomToken(48);
		} else {
			$API_KEY = trim($_POST['api_key'] ?? '');
			$API_SECRET = trim($_POST['api_secret'] ?? '');
		}
		if (!is_dir(__DIR__ . '/config')) { @mkdir(__DIR__ . '/config', 0777, true); }
		file_put_contents($configPath, "<?php\n".
			"define('CLIENT_API_KEY', '".addslashes($API_KEY)."');\n".
			"define('CLIENT_API_SECRET', '".addslashes($API_SECRET)."');\n");
		$saved = true;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Setting API - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
	<style>
		#api-page { padding:24px; }
		#api-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		#api-page .card-body { padding:18px; }
		#api-page .title { margin:0; color:#333; font-size:22px; font-weight:700; }
		#api-page .info { margin:12px 0 18px; color:#e53935; font-weight:700; }
		#api-page .label { display:block; font-weight:700; margin:12px 0 6px; }
		#api-page .input { width:100%; max-width:680px; padding:10px; border:1px solid #d0d7de; border-radius:6px; background:#f5f7f9; }
		#api-page .row { margin-bottom:8px; }
		#api-page .btn { padding:10px 14px; border:none; border-radius:6px; background:#009688; color:#fff; cursor:pointer; }
		#api-page .btn.secondary { background:#3f51b5; }
		#api-page .muted { color:#546e7a; font-size:13px; margin-top:8px; }
	</style>
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar-new.php'; ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;">
		<div id="api-page">
			<h2 class="title">Konfigurasi API</h2>
			<div class="card">
				<div class="card-body">
					<div class="info">INFO : Kunci API dan Secret API ini hanya digunakan untuk autentikasi Portal Client (Clientarea)</div>
					<?php if (!empty($saved)) { ?>
						<div style="margin:0 0 12px; padding:10px 12px; background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; border-radius:6px;">Perubahan tersimpan.</div>
					<?php } ?>
					<form method="post">
						<div class="row">
							<label class="label">Kunci API</label>
							<input type="text" name="api_key" class="input" value="<?php echo htmlspecialchars($API_KEY); ?>" readonly>
						</div>
						<div class="row">
							<label class="label">Secret API</label>
							<input type="text" name="api_secret" class="input" value="<?php echo htmlspecialchars($API_SECRET); ?>" readonly>
						</div>
						<div class="row" style="display:flex; gap:12px; align-items:center; margin-top:12px;">
							<button class="btn" type="submit" name="action" value="generate">Generate</button>
							<button class="btn secondary" type="submit" name="action" value="save">Simpan Perubahan</button>
							<a href="#" onclick="history.back();return false;" class="muted">Atau Batal</a>
						</div>
						<div class="muted">Contoh pemakaian: kirim header <code>X-Api-Key</code> dan tanda tangan <code>X-Signature</code> = HMAC-SHA256(body, SECRET). Server memverifikasi sebelum memproses.</div>
					</form>
				</div>
			</div>
		</div>

		<div id="footer" style="padding:16px 24px;">
			<?php include 'page-footer.php'; ?>
		</div>
	</div>
</body>
</html>


