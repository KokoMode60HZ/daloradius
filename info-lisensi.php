<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: info lisensi";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Info Lisensi - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
	<style>
		#lic-page { padding:24px; }
		#lic-page .grid { display:grid; grid-template-columns: 1fr; gap:16px; }
		#lic-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); overflow:hidden; }
		#lic-page .card-head { padding:12px 16px; background:#f8f9fa; border-bottom:1px solid #e9ecef; font-weight:700; }
		#lic-page .card-body { padding:16px; }
		#lic-page .row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px dashed #eaeaea; }
		#lic-page .row:last-child { border-bottom:none; }
		#lic-page .label { color:#455a64; }
		#lic-page .value { color:#111; font-weight:600; }
		#lic-page .service { display:flex; align-items:center; gap:10px; margin:10px 0; }
		#lic-page .badge { padding:4px 10px; border-radius:16px; font-weight:700; font-size:12px; }
		#lic-page .ok { background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; }
		#lic-page .warn { background:#ffebee; color:#b71c1c; border:1px solid #ffcdd2; }
		#lic-page .bar { height:8px; border-radius:6px; background:#e0e0e0; position:relative; overflow:hidden; }
		#lic-page .bar > span { position:absolute; left:0; top:0; bottom:0; background:#42a5f5; border-radius:6px; }
		#lic-page .bar.red > span { background:#ef5350; }
		#lic-page .kpi { display:flex; align-items:center; gap:8px; }
	</style>
</head>
<body>
	<?php include 'includes/sidebar.php'; ?>

	<div class="dashboard-page" style="margin-left:240px;margin-top:56px;">
		<div id="lic-page" class="grid">
			<div class="card">
				<div class="card-head">Informasi Lisensi</div>
				<div class="card-body">
					<div class="row"><span class="label">Email Terdaftar</span><span class="value">thespringbed@gmail.com</span></div>
					<div class="row"><span class="label">Tanggal Aktivasi</span><span class="value">Jul/31/2021 08:25:22</span></div>
					<div class="row"><span class="label">Request ID</span><span class="value">LV4 | ••••••••••••••••••••••••••••••••••</span></div>
					<div class="row"><span class="label">Hardware ID</span><span class="value">XX-XX-XX-XX-XX-XX</span></div>
					<div class="row"><span class="label">Software Key</span><span class="value">@TOPSETTING | ••••••••••••••••••••••••••••••••••</span></div>
				</div>
			</div>

			<div class="card">
				<div class="card-head">Informasi Layanan</div>
				<div class="card-body">
					<div class="service kpi"><strong>CORE RADIUS</strong><span class="badge ok">Running</span><a href="#" class="badge" style="background:#e3f2fd;color:#1565c0;border:1px solid #bbdefb;">Restart</a></div>

					<div class="service">
						<span style="min-width:110px;display:inline-block;">MIKROTIK</span>
						<span style="color:#b71c1c;font-weight:700;">4 Router</span>
						<div class="bar red" style="flex:1; max-width:280px;"><span style="width:85%"></span></div>
						<span style="color:#b71c1c;">0 Quota</span>
					</div>

					<div class="service">
						<span style="min-width:110px;display:inline-block;">SESSION</span>
						<span style="color:#1565c0;font-weight:700;">400</span>
						<div class="bar" style="flex:1; max-width:280px;"><span style="width:40%"></span></div>
						<span>200 Quota</span>
					</div>

					<div class="service">
						<span style="min-width:110px;display:inline-block;">PELANGGAN</span>
						<span style="color:#2e7d32;font-weight:700;">568 Quota</span>
						<div class="bar" style="flex:1; max-width:280px;"><span style="width:56%"></span></div>
					</div>

					<div class="service">
						<span style="min-width:110px;display:inline-block;">VOUCHER</span>
						<span>65000 Quota</span>
						<div class="bar" style="flex:1; max-width:280px;"><span style="width:65%"></span></div>
					</div>

					<div class="service">
						<span style="min-width:110px;display:inline-block;">JATUH TEMPO</span>
						<span class="badge" style="background:#e8f5e9;color:#1b5e20;border:1px solid #c8e6c9;">September 02, 2025</span>
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


