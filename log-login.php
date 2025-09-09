<?php
    include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: log login";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log Login - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
	<style>
		.log-container { max-width: 1400px; margin: 0 auto; }
		.log-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
		.title { font-size:24px; font-weight:bold; color:#333; margin-bottom:24px; border-bottom:2px solid #009688; padding-bottom:12px; }
		.log-controls { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center; }
		.search-input { padding:8px 12px; border:1px solid #ddd; border-radius:4px; width:300px; }
		.btn { padding:8px 16px; border:none; border-radius:4px; cursor:pointer; font-size:14px; }
		.btn-primary { background:#007bff; color:#fff; }
		.btn-primary:hover { background:#0056b3; }
		.btn-secondary { background:#6c757d; color:#fff; }
		.btn-secondary:hover { background:#5a6268; }
		.btn-export { background:#17a2b8; color:#fff; }
		.btn-export:hover { background:#138496; }
		.btn-clear { background:#6c757d; color:#fff; }
		.btn-clear:hover { background:#5a6268; }
		.log-table { width:100%; border-collapse:collapse; margin-top:20px; }
		.log-table th, .log-table td { padding:12px 15px; text-align:left; border-bottom:1px solid #eee; }
		.log-table th { background:#f8f9fa; font-weight:600; color:#333; }
		.log-table tr:hover { background:#f5f5f5; }
		.status-success { color:#28a745; font-weight:600; }
		.status-failed { color:#dc3545; font-weight:600; }
		.status-pending { color:#ffc107; font-weight:600; }
		.log-summary { display:flex; gap:20px; margin-bottom:20px; flex-wrap:wrap; }
		.summary-card { background:#f8f9fa; border:1px solid #e9ecef; border-radius:6px; padding:16px; flex:1; min-width:200px; }
		.summary-card h4 { margin:0 0 8px 0; color:#495057; font-size:14px; }
		.summary-card .number { font-size:24px; font-weight:bold; color:#007bff; }
		.summary-card.success .number { color:#28a745; }
		.summary-card.failed .number { color:#dc3545; }
		.summary-card.pending .number { color:#ffc107; }
		.filter-tabs { display:flex; gap:8px; margin-bottom:20px; }
		.filter-tab { padding:8px 16px; border:1px solid #ddd; background:#fff; color:#333; cursor:pointer; border-radius:4px 4px 0 0; }
		.filter-tab.active { background:#007bff; color:#fff; border-color:#007bff; }
		.filter-tab:hover:not(.active) { background:#f8f9fa; }
		.date-range { display:flex; gap:12px; align-items:center; }
		.date-input { padding:8px 12px; border:1px solid #ddd; border-radius:4px; width:150px; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
			<h2 style="margin:0;color:#333;">Log Login</h2>
		</div>

		<div class="log-container">
			<div class="log-section">
				<div class="title">Log Login</div>

				<div class="log-summary">
					<div class="summary-card success">
						<h4>Login Berhasil</h4>
						<div class="number">1,247</div>
					</div>
					<div class="summary-card failed">
						<h4>Login Gagal</h4>
						<div class="number">89</div>
            </div>
					<div class="summary-card pending">
						<h4>Pending</h4>
						<div class="number">23</div>
                </div>
					<div class="summary-card">
						<h4>Total</h4>
						<div class="number">1,359</div>
                        </div>
                    </div>

				<div class="filter-tabs">
					<div class="filter-tab active" onclick="filterLogs('all')">Semua</div>
					<div class="filter-tab" onclick="filterLogs('success')">Berhasil</div>
					<div class="filter-tab" onclick="filterLogs('failed')">Gagal</div>
					<div class="filter-tab" onclick="filterLogs('pending')">Pending</div>
				</div>

				<div class="log-controls">
					<input type="text" id="searchInput" class="search-input" placeholder="Cari berdasarkan username, IP, atau status...">
					<button class="btn btn-primary" onclick="searchLogs()">
						<i class="fas fa-search"></i> Cari
					</button>
					<div class="date-range">
						<input type="date" id="startDate" class="date-input" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
						<span>s/d</span>
						<input type="date" id="endDate" class="date-input" value="<?php echo date('Y-m-d'); ?>">
					</div>
					<button class="btn btn-secondary" onclick="filterByDate()">
						<i class="fas fa-calendar"></i> Filter Tanggal
					</button>
					<button class="btn btn-export" onclick="exportLogs('excel')">
						<i class="fas fa-file-excel"></i> Export Excel
					</button>
					<button class="btn btn-export" onclick="exportLogs('csv')">
						<i class="fas fa-file-csv"></i> Export CSV
					</button>
					<button class="btn btn-clear" onclick="clearFilters()">
						<i class="fas fa-times"></i> Clear Filter
					</button>
				</div>

				<table class="log-table" id="logTable">
					<thead>
						<tr>
							<th>No</th>
							<th>Username</th>
							<th>IP Address</th>
							<th>Login Time</th>
							<th>Status</th>
							<th>Message</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="logTableBody">
						<tr>
							<td>1</td>
							<td>admin</td>
							<td>192.168.1.100</td>
							<td>2024-01-15 14:30:25</td>
							<td><span class="status-success">SUCCESS</span></td>
							<td>Login berhasil</td>
							<td>
								<button class="btn btn-secondary btn-sm" onclick="viewDetails(1)">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>user123</td>
							<td>192.168.1.101</td>
							<td>2024-01-15 14:28:15</td>
							<td><span class="status-failed">FAILED</span></td>
							<td>Password salah</td>
							<td>
								<button class="btn btn-secondary btn-sm" onclick="viewDetails(2)">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>3</td>
							<td>operator</td>
							<td>192.168.1.102</td>
							<td>2024-01-15 14:25:42</td>
							<td><span class="status-success">SUCCESS</span></td>
							<td>Login berhasil</td>
							<td>
								<button class="btn btn-secondary btn-sm" onclick="viewDetails(3)">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>4</td>
							<td>test_user</td>
							<td>192.168.1.103</td>
							<td>2024-01-15 14:22:18</td>
							<td><span class="status-pending">PENDING</span></td>
							<td>Menunggu verifikasi</td>
							<td>
								<button class="btn btn-secondary btn-sm" onclick="viewDetails(4)">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>
						<tr>
							<td>5</td>
							<td>guest</td>
							<td>192.168.1.104</td>
							<td>2024-01-15 14:20:05</td>
							<td><span class="status-failed">FAILED</span></td>
							<td>User tidak ditemukan</td>
							<td>
								<button class="btn btn-secondary btn-sm" onclick="viewDetails(5)">
									<i class="fas fa-eye"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="pagination" style="display:flex; justify-content:center; gap:8px; margin-top:20px;">
					<button class="btn btn-secondary" onclick="changePage('prev')">
						<i class="fas fa-chevron-left"></i> Previous
					</button>
					<button class="btn btn-secondary active">1</button>
					<button class="btn btn-secondary">2</button>
					<button class="btn btn-secondary">3</button>
					<button class="btn btn-secondary">4</button>
					<button class="btn btn-secondary">5</button>
					<button class="btn btn-secondary" onclick="changePage('next')">
						Next <i class="fas fa-chevron-right"></i>
					</button>
                </div>
            </div>
        </div>
    </div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>

	<script>
		let currentFilter = 'all';
		let currentPage = 1;

		function filterLogs(type) {
			currentFilter = type;
			document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
			event.target.classList.add('active');
			const rows = document.querySelectorAll('#logTableBody tr');
			rows.forEach(row => {
				const statusCell = row.querySelector('td:nth-child(5) span');
				if (statusCell) {
					const status = statusCell.textContent.toLowerCase();
					row.style.display = (type === 'all' || status === type) ? '' : 'none';
				}
			});
		}

		function searchLogs() {
			const searchTerm = document.getElementById('searchInput').value.toLowerCase();
			const rows = document.querySelectorAll('#logTableBody tr');
			rows.forEach(row => {
				const username = row.cells[1].textContent.toLowerCase();
				const ip = row.cells[2].textContent.toLowerCase();
				const status = row.cells[4].textContent.toLowerCase();
				row.style.display = (username.includes(searchTerm) || ip.includes(searchTerm) || status.includes(searchTerm)) ? '' : 'none';
			});
		}

		function filterByDate() {
			const startDate = document.getElementById('startDate').value;
			const endDate = document.getElementById('endDate').value;
			if (!startDate || !endDate) { alert('Pilih tanggal awal dan akhir!'); return; }
			alert(`Filter tanggal dari ${startDate} sampai ${endDate} berhasil diterapkan!`);
		}

		function exportLogs(format) { alert(`Export ${format.toUpperCase()} berhasil! File akan didownload.`); }

		function clearFilters() {
			document.getElementById('searchInput').value = '';
			document.getElementById('startDate').value = '<?php echo date('Y-m-d', strtotime('-7 days')); ?>';
			document.getElementById('endDate').value = '<?php echo date('Y-m-d'); ?>';
			document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
			document.querySelector('.filter-tab').classList.add('active');
			document.querySelectorAll('#logTableBody tr').forEach(row => row.style.display = '');
		}

		function viewDetails(id) { alert(`Melihat detail log ID: ${id}`); }
		function changePage(direction) { alert(`Halaman ${direction === 'prev' ? currentPage-1 : currentPage+1}`); }
	</script>
</body>
</html>
