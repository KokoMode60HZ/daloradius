<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: log aktivitas";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log Aktivitas - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 1600px; margin: 0 auto; }
		.card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		.card-header { display:flex; align-items:center; gap:8px; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #e9ecef; }
		.card-title { font-weight:700; color:#2b2f36; display:flex; align-items:center; gap:10px; }
		.card-title i { color:#1558b0; }
		.toolbar { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
		.btn { padding:8px 12px; border:none; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:14px; }
		.btn i { font-size:14px; }
		.btn-primary { background:#1558b0; color:#fff; }
		.btn-success { background:#28a745; color:#fff; }
		.btn-danger { background:#dc3545; color:#fff; }
		.btn-warning { background:#ffc107; color:#212529; }
		.btn-outline { background:#fff; color:#1558b0; border:1px solid #1558b0; }
		.table-wrap { padding:16px; }
		.table { width:100%; border-collapse:collapse; }
		.table th, .table td { padding:10px 12px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.table th { background:#f8f9fb; color:#333; font-weight:600; position:sticky; top:0; z-index:1; }
		.table tr:hover { background:#fafbfd; }
		.badge { padding:3px 8px; border-radius:999px; font-size:12px; font-weight:600; }
		.badge-role { background:#e7f1ff; color:#1558b0; }
		.controls { display:flex; align-items:center; gap:12px; padding:12px 16px; border-bottom:1px solid #eef1f4; }
		.controls .search { margin-left:auto; }
		.controls input[type="text"] { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; width:260px; }
		.controls select, .controls input[type="date"] { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.pagination { display:flex; justify-content:flex-end; gap:6px; padding:12px 16px; }
		.pagination .btn { padding:6px 10px; font-size:12px; }
		.small { font-size:12px; color:#6c757d; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="container">
			<div class="card">
				<div class="card-header">
					<div class="card-title"><i class="fas fa-list"></i> Log Aktivitas</div>
					<div class="toolbar">
						<button class="btn btn-success" onclick="exportData('excel')"><i class="fas fa-file-excel"></i> Excel</button>
						<button class="btn btn-outline" onclick="exportData('csv')"><i class="fas fa-file-csv"></i> CSV</button>
						<button class="btn btn-danger" onclick="clearLogs()"><i class="fas fa-trash"></i> Clear</button>
					</div>
				</div>

				<div class="controls">
					<div>
						<span class="small">Show</span>
						<select id="pageSize" onchange="renderTable(1)">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<span class="small">entries</span>
					</div>
					<div style="display:flex;align-items:center;gap:8px;">
						<input type="date" id="startDate" value="<?php echo date('Y-m-d', strtotime('-60 days')); ?>">
						<span class="small">s/d</span>
						<input type="date" id="endDate" value="<?php echo date('Y-m-d'); ?>">
						<button class="btn btn-primary" onclick="applyDateFilter()"><i class="fas fa-filter"></i> Filter</button>
					</div>
					<div class="search">
						<input type="text" id="searchInput" placeholder="Search... (aktivitas, identitas, user, IP)" oninput="renderTable(1)">
					</div>
				</div>

				<div class="table-wrap">
					<table class="table" id="activityTable">
						<thead>
							<tr>
								<th style="width:70px;">Id</th>
								<th style="width:180px;">Tanggal</th>
								<th>Aktivitas</th>
								<th style="width:160px;">Identitas Data</th>
								<th style="width:140px;">User ID</th>
								<th style="width:150px;">IP Address</th>
							</tr>
						</thead>
						<tbody id="tableBody"></tbody>
					</table>
					<div class="small" id="tableInfo"></div>
				</div>

				<div class="pagination" id="pagination"></div>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>

	<script>
		const data = [];
		// generate mock data 917 rows mirip screenshot
		(function seed(){
			const users = [
				{u:'user', role:'manager'},
				{u:'root', role:'administrator'}
			];
			const ips = ['182.4.134.180','125.166.13.177','103.242.106.157','182.4.133.184'];
			const acts = [
				'langganan diperpanjang',
				'login sistem',
				'ubah profil pelanggan',
				'buat invoice',
				'hapus voucher'
			];
			let id = 1111;
			for (let i=0;i<250;i++) {
				const d = new Date();
				d.setMinutes(d.getMinutes() - i*13);
				const usr = users[i%users.length];
				const row = {
					id: id--,
					date: d.toISOString().slice(0,19).replace('T',' '),
					activity: acts[i%acts.length] + ' — paket-110rb — ' + d.toLocaleString('en-GB',{month:'short'}) + '/' + d.getFullYear(),
					ident: String(Math.floor(100000000 + Math.random()*899999999)),
					user: usr.u,
					role: usr.role,
					ip: ips[i%ips.length]
				};
				data.push(row);
			}
		})();

		let currentPage = 1;
		function getFilteredData(){
			const q = (document.getElementById('searchInput').value||'').toLowerCase();
			const sd = document.getElementById('startDate').value;
			const ed = document.getElementById('endDate').value;
			return data.filter(r=>{
				const inSearch = !q || (r.activity+" "+r.ident+" "+r.user+" "+r.role+" "+r.ip).toLowerCase().includes(q);
				const inDate = (!sd || r.date.slice(0,10)>=sd) && (!ed || r.date.slice(0,10)<=ed);
				return inSearch && inDate;
			});
		}

		function renderTable(page){
			currentPage = page || 1;
			const pageSize = parseInt(document.getElementById('pageSize').value,10);
			const rows = getFilteredData();
			const start = (currentPage-1)*pageSize;
			const slice = rows.slice(start, start+pageSize);
			const body = document.getElementById('tableBody');
			body.innerHTML = slice.map(r=>`
				<tr>
					<td>${r.id}</td>
					<td>${r.date}</td>
					<td>${r.activity}</td>
					<td><a href="#" onclick="alert('ID ${r.ident}')">${r.ident}</a><div class="small">CUSTOMER PPP</div></td>
					<td>${r.user} <span class="badge badge-role">${r.role}</span></td>
					<td>${r.ip}</td>
				</tr>
			`).join('');
			document.getElementById('tableInfo').textContent = `Showing ${rows.length? start+1:0} to ${Math.min(start+pageSize, rows.length)} of ${rows.length} entries`;
			renderPagination(Math.ceil(rows.length/pageSize));
		}

		function renderPagination(total){
			const pag = document.getElementById('pagination');
			let html = '';
			html += `<button class="btn" onclick="gotoPage(${Math.max(1,currentPage-1)})">Previous</button>`;
			for(let i=1;i<=Math.min(total,7);i++){
				html += `<button class="btn ${i===currentPage?'btn-primary':''}" onclick="gotoPage(${i})">${i}</button>`;
			}
			html += `<button class="btn" onclick="gotoPage(${Math.min(total,currentPage+1)})">Next</button>`;
			pag.innerHTML = html;
		}
		function gotoPage(p){ renderTable(p); }
		function applyDateFilter(){ renderTable(1); }
		function exportData(fmt){ alert('Export '+fmt.toUpperCase()+' berhasil (simulasi).'); }
		function clearLogs(){ if(confirm('Hapus semua log (simulasi)?')){ alert('Log dihapus (simulasi).'); } }

		document.addEventListener('DOMContentLoaded', ()=> renderTable(1));
	</script>
</body>
</html>
