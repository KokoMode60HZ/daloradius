<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: log bg process";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log BG Process - daloRADIUS</title>
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
		.btn-danger { background:#dc3545; color:#fff; }
		.btn-outline { background:#fff; color:#1558b0; border:1px solid #1558b0; }
		.note { padding:8px 16px; color:#6c757d; font-size:12px; }
		.controls { display:flex; align-items:center; gap:12px; padding:12px 16px; }
		.controls .search { margin-left:auto; }
		.controls input[type="text"], .controls select { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.table-wrap { padding:0 16px 16px; }
		.table { width:100%; border-collapse:collapse; }
		.table th, .table td { padding:10px 12px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.table th { background:#f8f9fb; color:#333; font-weight:600; position:sticky; top:0; z-index:1; }
		.table tr:hover { background:#fafbfd; }
		.badge { padding:3px 8px; border-radius:999px; font-size:12px; font-weight:600; }
		.badge-role { background:#e7f1ff; color:#1558b0; }
		.pagination { display:flex; justify-content:flex-end; gap:6px; padding:12px 16px; }
		.pagination .btn { padding:6px 10px; font-size:12px; }
		.small { font-size:12px; color:#6c757d; padding:0 16px 12px; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="container">
			<div class="card">
				<div class="card-header">
					<div class="card-title"><i class="fas fa-cogs"></i> Log BG Process</div>
					<div class="toolbar">
						<button class="btn btn-danger" onclick="clearLogs()"><i class="fas fa-trash"></i></button>
					</div>
				</div>
				<div class="note">*) only display logs record last 2 months</div>

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
					<div class="search">
						<input type="text" id="searchInput" placeholder="Search..." oninput="renderTable(1)">
					</div>
				</div>

				<div class="table-wrap">
					<table class="table">
						<thead>
							<tr>
								<th style="width:70px;">Id</th>
								<th style="width:180px;">Tanggal</th>
								<th>Background Task</th>
								<th style="width:180px;">Source Task</th>
								<th style="width:180px;">Identifier</th>
								<th style="width:160px;">Task Owner</th>
								<th style="width:150px;">IP Address</th>
							</tr>
						</thead>
						<tbody id="tableBody"></tbody>
					</table>
				</div>
				<div class="small" id="tableInfo"></div>
				<div class="pagination" id="pagination"></div>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>

	<script>
		const data = [];
		(function seed(){
			const bgTasks = [
				'( PPP ) creating customer invoice',
				'mixradius version updated (subversion only)',
				'removing radius authentication log',
				'removing resolved tickets that older than 1 months',
				'removing wa-blast log that older than 2 months'
			];
			const srcTasks = ['Automatic Invoice','Automatic Update','Automatic Remove'];
			const owners = [
				{owner:'System', role:'scheduler'},
				{owner:'System', role:'cron'}
			];
			const ips = ['-','125.166.13.177','103.242.106.157'];
			let id = 1656;
			for (let i=0;i<1188;i++) {
				const d = new Date();
				d.setMinutes(d.getMinutes() - i*13);
				const ident = Math.floor(200000000 + Math.random()*800000000).toString();
				const o = owners[i%owners.length];
				data.push({
					id: id--,
					date: d.toISOString().slice(0,19).replace('T',' '),
					bg: bgTasks[i%bgTasks.length],
					src: srcTasks[i%srcTasks.length],
					ident: i%5===0 ? '-' : ident,
					owner: o.owner,
					role: o.role,
					ip: ips[i%ips.length]
				});
			}
		})();

		let currentPage = 1;
		function getFilteredData(){
			const q = (document.getElementById('searchInput').value||'').toLowerCase();
			return data.filter(r=>!q || (r.bg+" "+r.src+" "+r.ident+" "+r.owner+" "+r.role+" "+r.ip).toLowerCase().includes(q));
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
					<td>${r.bg}</td>
					<td>${r.src}</td>
					<td>${r.ident}</td>
					<td>${r.owner} <span class="badge badge-role">${r.role}</span></td>
					<td>${r.ip}</td>
				</tr>
			`).join('');
			document.getElementById('tableInfo').textContent = `Showing ${rows.length? start+1:0} to ${Math.min(start+pageSize, rows.length)} of ${rows.length} entries`;
			renderPagination(Math.ceil(rows.length/pageSize));
		}
		function renderPagination(total){
			const pag = document.getElementById('pagination');
			let html = '';
			html += `<button class=\"btn\" onclick=\"gotoPage(${Math.max(1,currentPage-1)})\">Previous</button>`;
			for(let i=1;i<=Math.min(total,7);i++){
				html += `<button class=\"btn ${i===currentPage?'btn-outline':''}\" onclick=\"gotoPage(${i})\">${i}</button>`;
			}
			html += `<button class=\"btn\" onclick=\"gotoPage(${Math.min(total,currentPage+1)})\">Next</button>`;
			pag.innerHTML = html;
		}
		function gotoPage(p){ renderTable(p); }
		function clearLogs(){ if(confirm('Hapus semua log BG (simulasi)?')) alert('BG logs cleared (simulasi).'); }
		document.addEventListener('DOMContentLoaded', ()=> renderTable(1));
	</script>
</body>
</html>
