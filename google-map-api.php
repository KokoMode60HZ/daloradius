<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: google map api";
	include('include/config/logging.php');

	// Config path for storing Google Maps API key
	$configPath = __DIR__ . '/config/google_map.php';
	$apiKey = '';

	// Load existing key if config file exists
	if (file_exists($configPath)) {
		include_once($configPath);
		if (defined('GOOGLE_MAPS_API_KEY')) {
			$apiKey = GOOGLE_MAPS_API_KEY;
		}
	}

	// Handle save (POST)
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$key = isset($_POST['gmapsKey']) ? trim($_POST['gmapsKey']) : '';
		if ($key !== '') {
			// Save simple PHP config file
			$contents = "<?php\n".
				"define('GOOGLE_MAPS_API_KEY', '" . addslashes($key) . "');\n";
			if (!is_dir(__DIR__ . '/config')) {
				@mkdir(__DIR__ . '/config', 0777, true);
			}
			file_put_contents($configPath, $contents);
			$apiKey = $key;
			$saveSuccess = true;
		} else {
			$saveError = 'API Key tidak boleh kosong';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Google Map API - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

	<style>
		/* Scoped styles untuk halaman ini */
		#gmaps-page { padding: 24px; }
		#gmaps-page .title { margin:0; color:#333; font-size:22px; font-weight:700; }
		#gmaps-page .subtitle { margin:6px 0 16px; color:#607d8b; }
		#gmaps-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		#gmaps-page .card-body { padding:18px; }
		#gmaps-page .form-group { margin-bottom:14px; }
		#gmaps-page label { display:block; font-weight:600; margin-bottom:6px; color:#333; }
		#gmaps-page .input { width:100%; max-width:560px; padding:10px; border:1px solid #d0d7de; border-radius:6px; }
		#gmaps-page .btn { padding:10px 14px; border:none; border-radius:6px; background:#009688; color:#fff; cursor:pointer; }
		#gmaps-page .btn.secondary { background:#3f51b5; }
		#gmaps-page .help { margin-top:10px; font-size:13px; color:#546e7a; }
		#gmaps-page .mapbox { margin-top:16px; height:360px; background:#e3f2fd; border:1px solid #bbdefb; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1976d2; }
	</style>
</head>
<body>
	<?php include 'includes/sidebar.php'; ?>

	<div class="dashboard-page" style="margin-left:240px;margin-top:56px;">
		<div id="gmaps-page">
			<h2 class="title">Google Map API</h2>
			<div class="subtitle">Konfigurasi API Key dan pratinjau peta</div>

			<div class="card">
				<div class="card-body">
					<?php if (!empty($saveSuccess)) { ?>
						<div style="margin:0 0 12px; padding:10px 12px; background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; border-radius:6px;">API Key berhasil disimpan.</div>
					<?php } ?>
					<?php if (!empty($saveError)) { ?>
						<div style="margin:0 0 12px; padding:10px 12px; background:#ffebee; color:#b71c1c; border:1px solid #ffcdd2; border-radius:6px;"><?php echo htmlspecialchars($saveError); ?></div>
					<?php } ?>

					<form method="post">
						<div class="form-group">
							<label for="gmapsKey">Google Maps API Key</label>
							<input type="text" id="gmapsKey" name="gmapsKey" class="input" placeholder="Masukkan API Key Anda" value="<?php echo htmlspecialchars($apiKey); ?>">
							<div class="help">Petunjuk: buat API Key di Google Cloud Console, aktifkan <strong>Maps JavaScript API</strong>, dan batasi penggunaan sesuai domain.</div>
						</div>
						<div class="form-group">
							<button class="btn" type="submit">Simpan</button>
							<button class="btn secondary" type="button" id="btnPreview">Preview Peta</button>
						</div>
					</form>

					<div id="map" class="mapbox">Pratinjau peta akan tampil di sini</div>
				</div>
			</div>
		</div>

		<div id="footer" style="padding:16px 24px;">
			<?php include 'page-footer.php'; ?>
		</div>
	</div>

	<script>
		function loadMapsJsWithKey(key) {
			if (!key) return;
			if (document.getElementById('gmaps-sdk')) return; // avoid double load
			window.initMap = function() {
				const center = { lat: -6.200000, lng: 106.816666 }; // Jakarta
				const map = new google.maps.Map(document.getElementById('map'), { zoom: 11, center });
				new google.maps.Marker({ position: center, map });
			};
			const s = document.createElement('script');
			s.id = 'gmaps-sdk';
			s.async = true; s.defer = true;
			s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(key) + '&callback=initMap';
			document.body.appendChild(s);
		}

		document.getElementById('btnPreview').addEventListener('click', function(){
			const k = document.getElementById('gmapsKey').value.trim();
			if (!k) { alert('Masukkan API Key terlebih dahulu.'); return; }
			loadMapsJsWithKey(k);
		});

		// Auto-load when API key already saved in config
		(function(){
			var saved = <?php echo json_encode($apiKey); ?>;
			if (saved) { loadMapsJsWithKey(saved); }
		})();
	</script>
</body>
</html>


