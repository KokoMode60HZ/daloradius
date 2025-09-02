<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: template voucher";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Template Voucher - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

	<style>
		/* Scoped styles untuk halaman ini */
		#tpl-page { padding: 24px; }
		#tpl-page .head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
		#tpl-page .title { margin:0; color:#333; font-size:18px; font-weight:700; display:flex; align-items:center; gap:8px; }
		#tpl-page .btn { border:none; border-radius:6px; padding:8px 12px; cursor:pointer; color:#fff; background:#009688; }
		#tpl-page .btn i { margin-right:6px; }
		#tpl-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); overflow:hidden; }
		#tpl-page .card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 14px; border-bottom:1px solid #e9ecef; flex-wrap:wrap; }
		#tpl-page .inline { display:flex; align-items:center; gap:8px; }
		#tpl-page .select, #tpl-page .input { padding:6px 10px; border:1px solid #d0d7de; border-radius:6px; }
		#tpl-page table { width:100%; border-collapse:collapse; }
		#tpl-page thead th { background:#f8f9fa; color:#495057; font-weight:600; padding:12px 10px; border-bottom:2px solid #dee2e6; text-align:left; white-space:nowrap; }
		#tpl-page tbody td { padding:12px 10px; border-bottom:1px solid #eee; vertical-align:middle; }
		#tpl-page tbody tr:hover { background:#f9fbfd; }
		#tpl-page .badge { background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; padding:4px 10px; border-radius:16px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:6px; }
		#tpl-page .actions { display:flex; gap:6px; }
		#tpl-page .icon-btn { border:1px solid #d0d7de; background:#fff; color:#455a64; border-radius:6px; padding:6px 8px; cursor:pointer; }
		#tpl-page .icon-btn.blue { color:#1565c0; border-color:#bbdefb; }
		#tpl-page .icon-btn.green { color:#2e7d32; border-color:#c8e6c9; }
		#tpl-page .table-foot { display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8f9fa; border-top:1px solid #e9ecef; flex-wrap:wrap; gap:10px; }
		#tpl-page .pagination { display:flex; gap:6px; }
		#tpl-page .page-btn { padding:8px 12px; border:1px solid #dee2e6; background:#fff; color:#495057; border-radius:6px; cursor:pointer; }
		#tpl-page .page-btn.active { background:#007bff; border-color:#007bff; color:#fff; }
		@media (max-width: 768px) {
			#tpl-page .card-head { flex-direction:column; align-items:stretch; }
			#tpl-page .input { width:100%; }
		}
	</style>
</head>
<body>
	<?php include 'includes/sidebar.php'; ?>

	<div class="dashboard-page" style="margin-left:240px;margin-top:56px;">
		<div id="tpl-page">
			<div class="head">
				<h3 class="title"><span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border:1px solid #d0d7de;border-radius:4px;">≡</span> TEMPLATE VOUCHER</h3>
				<button class="btn" id="btnMenu">TEMPLATE VOUCHER ▾</button>
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
						<input type="text" class="input" id="searchInput" placeholder="Cari template...">
					</div>
				</div>

				<table>
					<thead>
						<tr>
							<th style="width:36px;"><input type="checkbox" id="selectAll" /></th>
							<th>Nama Template</th>
							<th>Bisa Diakses Oleh</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody id="tplBody">
						<?php
							// Mock data sementara; nanti ganti query DB jika diperlukan
							$rows = [
								['4_POS_58-20220418_QRCode-2', 'Semua User', 'Enable'],
								['4_POS_58-20220418_QRCode-1', 'Semua User', 'Enable'],
								['4_POS_58-20220418_NoQRCode', 'Semua User', 'Enable'],
								['3. 20190709-Logo-QRCode-SM', 'Semua User', 'Enable'],
								['3. 20190415-Logo-QRCode', 'Semua User', 'Enable'],
								['20201129-Minimal-Design', 'Semua User', 'Enable'],
								['20201129-Logo-NoQRCode-2', 'Semua User', 'Enable'],
								['20201129-Logo-NoQRCode-1', 'Semua User', 'Enable'],
								['2. sample-template', 'Semua User', 'Enable'],
								['1. Default Template', 'Semua User', 'Enable'],
							];
							foreach ($rows as $r) {
								echo '<tr>';
								echo '<td><input type="checkbox" class="row-check" /></td>';
								echo '<td>'.htmlspecialchars($r[0]).'</td>';
								echo '<td>'.htmlspecialchars($r[1]).'</td>';
								echo '<td><span class="badge">✔ Enable</span></td>';
								echo '<td class="actions">'
									.'<button class="icon-btn" title="Preview">👁</button>'
									.'<button class="icon-btn blue" title="Edit">✎</button>'
									.'<button class="icon-btn green" title="Enable/Disable">⚙</button>'
								.'</td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>

				<div class="table-foot">
					<div id="tblInfo">Showing 1 to 10 of 10 entries</div>
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
		// Pencarian sederhana client-side
		document.getElementById('searchInput').addEventListener('input', function(){
			const term = this.value.toLowerCase();
			document.querySelectorAll('#tplBody tr').forEach(function(row){
				row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
			});
		});

		// Select-all: centang header untuk semua baris
		const selectAll = document.getElementById('selectAll');
		function updateHeaderState(){
			const rows = Array.from(document.querySelectorAll('.row-check'));
			const checked = rows.filter(cb => cb.checked).length;
			if (checked === 0) { selectAll.checked = false; selectAll.indeterminate = false; }
			else if (checked === rows.length) { selectAll.checked = true; selectAll.indeterminate = false; }
			else { selectAll.checked = false; selectAll.indeterminate = true; }
		}
		if (selectAll) {
			selectAll.addEventListener('change', function(){
				document.querySelectorAll('.row-check').forEach(cb => { cb.checked = selectAll.checked; });
				selectAll.indeterminate = false;
			});
			document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateHeaderState));
		}
	</script>
</body>
</html>


