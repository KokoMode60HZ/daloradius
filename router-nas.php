<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: router nas";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Router [NAS] - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

	<style>
		/* SCOPED STYLE – hanya untuk halaman ini */
		#router-nas * { box-sizing: border-box; }
		#router-nas { padding: 24px; }

		#router-nas .page-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 16px;
		}
		#router-nas .page-title {
			margin: 0;
			color: #333;
			font-weight: 700;
			font-size: 22px;
		}
		#router-nas .btn-primary {
			background: #3498db;
			color: #fff;
			border: none;
			border-radius: 6px;
			padding: 10px 14px;
			cursor: pointer;
		}
		#router-nas .btn-primary:hover { background: #2980b9; }

		#router-nas .info {
			background: #e8f4fd;
			border: 1px solid #bee5eb;
			border-radius: 6px;
			padding: 12px 14px;
			margin-bottom: 16px;
		}
		#router-nas .info-title { font-weight: 700; color: #0c5460; margin-bottom: 6px; }
		#router-nas .info ul { margin: 0; padding-left: 18px; color: #0c5460; }

		#router-nas .card {
			background: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0,0,0,.08);
			overflow: hidden;
		}
		#router-nas .card-head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 14px;
			border-bottom: 1px solid #e9ecef;
			gap: 12px;
			flex-wrap: wrap;
		}
		#router-nas .inline { display: flex; align-items: center; gap: 8px; }
		#router-nas .select, #router-nas .input {
			padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px;
		}

		#router-nas table { width: 100%; border-collapse: collapse; }
		#router-nas thead th {
			background: #f8f9fa; color: #495057; font-weight: 600;
			text-align: left; padding: 12px 10px; border-bottom: 2px solid #dee2e6; white-space: nowrap;
		}
		#router-nas tbody td {
			padding: 12px 10px; border-bottom: 1px solid #eee; vertical-align: middle;
		}
		#router-nas tbody tr:hover { background: #f9fbfd; }

		#router-nas .badge {
			padding: 5px 10px; border-radius: 16px; font-size: 12px; font-weight: 600; display: inline-block; min-width: 72px; text-align: center;
		}
		#router-nas .online { background: #d4edda; color: #155724; }
		#router-nas .timeout { background: #f8d7da; color: #721c24; }

		#router-nas .btn { background: #007bff; color: #fff; border: none; border-radius: 6px; padding: 6px 10px; cursor: pointer; font-size: 12px; }
		#router-nas .btn-edit { background: #17a2b8; }
		#router-nas .btn-delete { background: #fd7e14; }
		#router-nas .actions { display: flex; gap: 6px; }

		#router-nas .card-foot {
			display: flex; justify-content: space-between; align-items: center;
			padding: 12px 14px; border-top: 1px solid #e9ecef; background: #f8f9fa; flex-wrap: wrap; gap: 10px;
		}
		#router-nas .pagination { display: flex; gap: 6px; }
		#router-nas .page-btn { padding: 8px 12px; border: 1px solid #dee2e6; background: #fff; color: #495057; border-radius: 6px; cursor: pointer; }
		#router-nas .page-btn.active { background: #007bff; border-color: #007bff; color: #fff; }
		#router-nas .page-btn:disabled { opacity: .5; cursor: not-allowed; }

		@media (max-width: 768px) {
			#router-nas .card-head { flex-direction: column; align-items: stretch; }
			#router-nas .input { width: 100%; }
		}
	</style>
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>

	<!-- konten utama, sejajar dengan halaman lain -->
	<div class="dashboard-page" style="margin-left:240px;margin-top:56px;">
		<div id="router-nas">
			<div class="page-header">
				<h2 class="page-title">Router [NAS]</h2>
				<button class="btn-primary" type="button" id="btnAddNas">+ TAMBAH ROUTER [NAS]</button>
			</div>

			<div class="info">
				<div class="info-title">INFO :</div>
				<ul>
					<li>Sistem akan mengecek status ping ke router setiap 5 menit</li>
					<li>Tabel Router akan direfresh otomatis setiap 1 menit</li>
				</ul>
			</div>

			<div class="card">
				<div class="card-head">
					<div class="inline">
						<span>Show</span>
						<select class="select" id="showEntries">
							<option>10</option><option>25</option><option>50</option><option>100</option>
						</select>
						<span>entries</span>
					</div>
					<div class="inline">
						<span>Search:</span>
						<input type="text" class="input" id="searchInput" placeholder="Cari router...">
					</div>
				</div>

				<table>
					<thead>
						<tr>
							<th>API</th>
							<th>Status Ping</th>
							<th>Nama Router</th>
							<th>IP Address</th>
							<th>Zona Waktu</th>
							<th>Deskripsi</th>
							<th>User Online</th>
							<th>Cek Status Terakhir</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody id="routerTableBody">
						<tr>
							<td><button class="btn">Go</button></td>
							<td><span class="badge online">✔ online</span></td>
							<td>4011</td>
							<td>172.23.123.95</td>
							<td>+07:00 Asia/Jakarta</td>
							<td>Ro. Sumbul 4011</td>
							<td>active 372</td>
							<td>2025-09-02 08:55:02</td>
							<td class="actions">
								<button class="btn-edit">Edit</button>
								<button class="btn-delete">Hapus</button>
							</td>
						</tr>
						<tr>
							<td><button class="btn">Go</button></td>
							<td><span class="badge online">✔ online</span></td>
							<td>CCR</td>
							<td>172.23.123.94</td>
							<td>+07:00 Asia/Jakarta</td>
							<td>Ro. Cloud Core Router</td>
							<td>-</td>
							<td>2025-09-02 08:55:02</td>
							<td class="actions">
								<button class="btn-edit">Edit</button>
								<button class="btn-delete">Hapus</button>
							</td>
						</tr>
						<tr>
							<td><button class="btn">Go</button></td>
							<td><span class="badge timeout">✗ timeout</span></td>
							<td>GX4</td>
							<td>172.23.122.204</td>
							<td>+07:00 Asia/Jakarta</td>
							<td>DISTRIBUSI GX4</td>
							<td>-</td>
							<td>2025-09-02 08:55:03</td>
							<td class="actions">
								<button class="btn-edit">Edit</button>
								<button class="btn-delete">Hapus</button>
							</td>
						</tr>
						<tr>
							<td><button class="btn">Go</button></td>
							<td><span class="badge timeout">✗ timeout</span></td>
							<td>GR3</td>
							<td>172.23.123.177</td>
							<td>+07:00 Asia/Jakarta</td>
							<td>Dist NON VLAN</td>
							<td>-</td>
							<td>2025-09-02 08:55:04</td>
							<td class="actions">
								<button class="btn-edit">Edit</button>
								<button class="btn-delete">Hapus</button>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="card-foot">
					<div id="tableInfo">Showing 1 to 4 of 4 entries</div>
					<div class="pagination">
						<button class="page-btn" disabled>Previous</button>
						<button class="page-btn active">1</button>
						<button class="page-btn" disabled>Next</button>
					</div>
				</div>
			</div>
		</div>

		<div id="footer" style="padding:16px 24px;">
			<?php include 'page-footer.php'; ?>
		</div>
	</div>

	<script>
		// Search sederhana (client-side)
		document.getElementById('searchInput').addEventListener('input', function () {
			const term = this.value.toLowerCase();
			document.querySelectorAll('#routerTableBody tr').forEach(function (row) {
				row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
			});
		});

		// Placeholder tombol
		document.getElementById('btnAddNas').addEventListener('click', function () {
			alert('Fitur tambah router akan dihubungkan ke DB nanti.');
		});
		document.querySelectorAll('.btn-edit').forEach(function (btn) {
			btn.addEventListener('click', function () { alert('Edit router (WIP).'); });
		});
		document.querySelectorAll('.btn-delete').forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (confirm('Hapus router ini?')) alert('Hapus router (WIP).');
			});
		});

		// Mock auto-refresh dan ping-check
		setInterval(function(){ console.log('Refresh tabel router...'); }, 60000);
		setInterval(function(){ console.log('Cek status ping...'); }, 300000);
	</script>
</body>
</html>