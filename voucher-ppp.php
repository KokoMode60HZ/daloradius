<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: voucher ppp management";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manajemen Voucher PPP - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<script type="text/javascript" src="library/javascript/pages_common.js"></script>
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/dynamic.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>

	<style>
		/* Scoped styles untuk halaman ini */
		#voucher-page { padding: 24px; }
		#voucher-page .head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
		#voucher-page .title { margin:0; color:#333; font-size:18px; font-weight:700; display:flex; align-items:center; gap:8px; }
		#voucher-page .btn { border:none; border-radius:6px; padding:8px 12px; cursor:pointer; color:#fff; background:#28a745; }
		#voucher-page .btn i { margin-right:6px; }
		#voucher-page .btn-whatsapp { background:#25d366; }
		#voucher-page .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); overflow:hidden; }
		#voucher-page .card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 14px; border-bottom:1px solid #e9ecef; flex-wrap:wrap; }
		#voucher-page .inline { display:flex; align-items:center; gap:8px; }
		#voucher-page .select, #voucher-page .input { padding:6px 10px; border:1px solid #d0d7de; border-radius:6px; }
		#voucher-page table { width:100%; border-collapse:collapse; }
		#voucher-page thead th { background:#f8f9fa; color:#495057; font-weight:600; padding:12px 10px; border-bottom:2px solid #dee2e6; text-align:left; white-space:nowrap; position:relative; }
		#voucher-page thead th .sort-arrows { position:absolute; right:8px; top:50%; transform:translateY(-50%); display:flex; flex-direction:column; gap:2px; }
		#voucher-page thead th .sort-arrows span { font-size:10px; color:#6c757d; cursor:pointer; }
		#voucher-page thead th .sort-arrows span:hover { color:#495057; }
		#voucher-page tbody td { padding:12px 10px; border-bottom:1px solid #eee; vertical-align:middle; }
		#voucher-page tbody tr:hover { background:#f9fbfd; }
		#voucher-page .badge { background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; padding:4px 10px; border-radius:16px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:6px; }
		#voucher-page .badge.expired { background:#ffebee; color:#c62828; border-color:#ffcdd2; }
		#voucher-page .badge.used { background:#e3f2fd; color:#1565c0; border-color:#bbdefb; }
		#voucher-page .actions { display:flex; gap:6px; }
		#voucher-page .icon-btn { border:1px solid #d0d7de; background:#fff; color:#455a64; border-radius:6px; padding:6px 8px; cursor:pointer; }
		#voucher-page .icon-btn.blue { color:#1565c0; border-color:#bbdefb; }
		#voucher-page .icon-btn.green { color:#2e7d32; border-color:#c8e6c9; }
		#voucher-page .icon-btn.red { color:#d32f2f; border-color:#ffcdd2; }
		#voucher-page .table-foot { display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8f9fa; border-top:1px solid #e9ecef; flex-wrap:wrap; gap:10px; }
		#voucher-page .pagination { display:flex; gap:6px; }
		#voucher-page .page-btn { padding:8px 12px; border:1px solid #dee2e6; background:#fff; color:#495057; border-radius:6px; cursor:pointer; }
		#voucher-page .page-btn.active { background:#007bff; border-color:#007bff; color:#fff; }
		#voucher-page .page-btn:disabled { opacity:0.5; cursor:not-allowed; }
		#voucher-page .instructions { background:#e3f2fd; border:1px solid #bbdefb; border-radius:8px; padding:16px; margin-bottom:20px; }
		#voucher-page .instructions ul { margin:0; padding-left:20px; }
		#voucher-page .instructions li { margin-bottom:8px; color:#1565c0; }
		#voucher-page .dropdown-menu { position:absolute; top:100%; left:0; background:#fff; border:1px solid #d0d7de; border-radius:6px; box-shadow:0 4px 12px rgba(0,0,0,0.15); z-index:1000; min-width:200px; display:none; }
		#voucher-page .dropdown-menu.show { display:block; }
		#voucher-page .dropdown-item { display:flex; align-items:center; padding:10px 16px; color:#495057; text-decoration:none; border-bottom:1px solid #f1f3f4; }
		#voucher-page .dropdown-item:hover { background:#f8f9fa; color:#1565c0; }
		#voucher-page .dropdown-item:last-child { border-bottom:none; }
		#voucher-page .dropdown-item i { margin-right:8px; width:16px; }
		#voucher-page .new-badge { background:#28a745; color:#fff; font-size:10px; padding:2px 6px; border-radius:10px; margin-left:8px; }
		#voucher-page .category-header { background:#ffebee; color:#c62828; font-weight:bold; padding:8px 16px; font-size:12px; }
		@media (max-width: 768px) {
			#voucher-page .card-head { flex-direction:column; align-items:stretch; }
			#voucher-page .input { width:100%; }
		}
	</style>
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;">
		<div id="voucher-page">
			<div class="head">
				<h3 class="title">
					<span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border:1px solid #d0d7de;border-radius:4px;">≡</span> 
					MANAJEMEN VOUCHER
					<span style="margin-left:auto; position:relative;">
						<button class="btn" id="btnMenu" style="position:relative;">
							<i class="fas fa-bars"></i> MANAJEMEN VOUCHER <i class="fas fa-chevron-down"></i>
						</button>
						<div class="dropdown-menu" id="voucherMenu">
							<a href="#" class="dropdown-item">
								<i class="fas fa-plus"></i> Tambah Voucher
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-search"></i> Cari Data Voucher
								<span class="new-badge">new</span>
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-print"></i> Cetak Voucher
								<span class="new-badge">new</span>
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-trash"></i> Hapus Voucher Expired
							</a>
							<div class="category-header">Aksi Checkbox (Massal)</div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-print"></i> Cetak Yang Dipilih
								<span class="new-badge">new</span>
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-user-edit"></i> Ubah Owner Data
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-link"></i> Set Bind Onlogin
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-file-csv"></i> Ekspor CSV
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-check-circle"></i> Aktifkan Voucher
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-times-circle"></i> Nonaktifkan Voucher
							</a>
							<a href="#" class="dropdown-item">
								<i class="fas fa-trash-alt"></i> Hapus Voucher
							</a>
						</div>
					</span>
				</h3>
			</div>

			<div class="instructions">
				<ul>
					<li>Voucher yang sudah digenerate disarankan untuk langsung dicetak</li>
					<li>Klik tombol ini: <button class="btn btn-whatsapp" style="margin:0 8px;"><i class="fab fa-whatsapp"></i> Kirim via Whatsapp</button> jika ingin mengirim voucher ke nomor whatsapp</li>
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
						<input type="text" class="input" id="searchInput" placeholder="Cari voucher...">
					</div>
				</div>

				<table>
					<thead>
						<tr>
							<th style="width:36px;"><input type="checkbox" id="selectAll" /></th>
							<th>Id
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Username
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Password
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Nama Profil
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Harga Jual
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Nama Server | Service
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Tanggal Dibuat
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Jatuh Tempo
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Owner Data
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Status
								<div class="sort-arrows">
									<span>▲</span>
									<span>▼</span>
								</div>
							</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody id="voucherBody">
						<tr>
							<td colspan="12" style="text-align:center; padding:40px; color:#6c757d;">
								No data available in table
							</td>
						</tr>
					</tbody>
				</table>

				<div class="table-foot">
					<div id="tblInfo">Showing 0 to 0 of 0 entries</div>
					<div class="pagination">
						<button class="page-btn" disabled>Previous</button>
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
		// Dropdown menu toggle
		document.getElementById('btnMenu').addEventListener('click', function(e) {
			e.stopPropagation();
			const menu = document.getElementById('voucherMenu');
			menu.classList.toggle('show');
		});

		// Close dropdown when clicking outside
		document.addEventListener('click', function() {
			document.getElementById('voucherMenu').classList.remove('show');
		});

		// Pencarian sederhana client-side
		document.getElementById('searchInput').addEventListener('input', function(){
			const term = this.value.toLowerCase();
			const rows = document.querySelectorAll('#voucherBody tr');
			let visibleCount = 0;
			
			rows.forEach(function(row){
				if (row.textContent.toLowerCase().includes(term)) {
					row.style.display = '';
					visibleCount++;
				} else {
					row.style.display = 'none';
				}
			});
			
			// Update table info
			document.getElementById('tblInfo').textContent = `Showing 0 to ${visibleCount} of ${visibleCount} entries`;
		});

		// Select-all: centang header untuk semua baris
		const selectAll = document.getElementById('selectAll');
		function updateHeaderState(){
			const rows = Array.from(document.querySelectorAll('.row-check'));
			const checked = rows.filter(cb => cb.checked).length;
			if (checked === 0) { 
				selectAll.checked = false; 
				selectAll.indeterminate = false; 
			}
			else if (checked === rows.length) { 
				selectAll.checked = true; 
				selectAll.indeterminate = false; 
			}
			else { 
				selectAll.checked = false; 
				selectAll.indeterminate = true; 
			}
		}
		
		if (selectAll) {
			selectAll.addEventListener('change', function(){
				document.querySelectorAll('.row-check').forEach(cb => { 
					cb.checked = selectAll.checked; 
				});
				selectAll.indeterminate = false;
			});
			document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateHeaderState));
		}

		// Table sorting functionality
		document.querySelectorAll('.sort-arrows span').forEach(function(arrow) {
			arrow.addEventListener('click', function() {
				// Remove active class from all arrows
				document.querySelectorAll('.sort-arrows span').forEach(a => a.style.color = '#6c757d');
				// Highlight clicked arrow
				this.style.color = '#495057';
			});
		});

		// Show entries change
		document.getElementById('showEntries').addEventListener('change', function() {
			const entries = this.value;
			document.getElementById('tblInfo').textContent = `Showing 0 to 0 of 0 entries`;
		});

		// WhatsApp button functionality
		document.querySelector('.btn-whatsapp').addEventListener('click', function() {
			alert('Fitur WhatsApp akan segera tersedia!');
		});

		// Dropdown menu item clicks
		document.querySelectorAll('.dropdown-item').forEach(function(item) {
			item.addEventListener('click', function(e) {
				e.preventDefault();
				const text = this.textContent.trim();
				alert(`Fitur "${text}" akan segera tersedia!`);
			});
		});
	</script>
</body>
</html>
