<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: list neighbor";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>List Neighbor - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

	<style>
		/* Scoped styles untuk halaman ini saja */
		#neighbor-page { padding: 24px; }
		#neighbor-page .header-row { display:flex; align-items:center; gap:12px; margin-bottom:16px; }
		#neighbor-page .title { margin:0; color:#333; font-size:22px; font-weight:700; }
		#neighbor-page .refresh { color:#1e7e34; font-weight:700; margin-left:8px; cursor:pointer; }

		#neighbor-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		#neighbor-page .card-body { padding:16px; }

		#neighbor-page .form-row { max-width:420px; }
		#neighbor-page .label { display:block; margin-bottom:8px; color:#333; font-weight:600; }
		#neighbor-page .select { width:100%; padding:10px; border:1px solid #d0d7de; border-radius:6px; }

		#neighbor-page .result { margin-top:18px; padding:12px; background:#f8f9fa; border:1px solid #e9ecef; border-radius:6px; color:#495057; }
	</style>
</head>
<body>
	<?php include 'includes/sidebar.php'; ?>

	<div class="dashboard-page" style="margin-left:240px;margin-top:56px;">
		<div id="neighbor-page">
			<div class="header-row">
				<h2 class="title">List Neighbor</h2>
				<span class="refresh" id="btnRefresh">- REFRESH</span>
			</div>

			<div class="card">
				<div class="card-body">
					<div class="form-row">
						<label class="label" for="routerSelect">Pilih Router</label>
						<select class="select" id="routerSelect">
							<option value="" selected>- Pilih Router -</option>
							<option>4011</option>
							<option>CCR</option>
							<option>GX4</option>
							<option>GR3</option>
						</select>
					</div>

					<div class="result" id="resultBox" style="display:none;"></div>
				</div>
			</div>
		</div>

		<div id="footer" style="padding:16px 24px;">
			<?php include 'page-footer.php'; ?>
		</div>
	</div>

	<script>
		const selectEl = document.getElementById('routerSelect');
		const box = document.getElementById('resultBox');
		selectEl.addEventListener('change', function(){
			if (!this.value) { box.style.display='none'; return; }
			box.style.display='block';
			box.textContent = 'Neighbor untuk router "' + this.value + '" akan ditampilkan di sini (simulasi).';
		});

		document.getElementById('btnRefresh').addEventListener('click', function(){
			// Placeholder refresh action
			if (selectEl.value) {
				box.style.display='block';
				box.textContent = 'Data neighbor untuk "' + selectEl.value + '" diperbarui.';
			} else {
				alert('Pilih router terlebih dahulu.');
			}
		});
	</script>
</body>
</html>


