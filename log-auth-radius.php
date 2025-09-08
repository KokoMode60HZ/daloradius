<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: log auth radius";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log Autentikasi Radius - daloRADIUS</title>
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
		.controls { display:flex; align-items:center; gap:12px; padding:12px 16px; }
		.controls .search { margin-left:auto; }
		.controls input[type="text"], .controls select { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.table-wrap { padding:0 16px 16px; }
		.table { width:100%; border-collapse:collapse; }
		.table th, .table td { padding:10px 12px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.table th { background:#f8f9fb; color:#333; font-weight:600; position:sticky; top:0; z-index:1; }
		.table tr:hover { background:#fafbfd; }
		.radius-ok { color:#16a34a; font-weight:600; }
		.radius-lost { color:#f97316; font-weight:600; }
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
					<div class="card-title"><i class="fas fa-shield-alt"></i> Log Autentikasi Radius</div>
					<div class="toolbar">
						<button class="btn btn-danger" onclick="clearLogs()"><i class="fas fa-trash"></i></button>
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
					<div class="search">
						<input type="text" id="searchInput" placeholder="Search..." oninput="renderTable(1)">
					</div>
				</div>

				<div class="table-wrap">
					<table class="table">
						<thead>
							<tr>
								<th style="width:70px;">Id</th>
								<th style="width:220px;">Username</th>
								<th style="width:110px;">Router [ NAS ]</th>
								<th style="width:200px;">Called Station</th>
								<th style="width:200px;">Calling Station</th>
								<th>Radius Response</th>
								<th style="width:190px;">Tanggal Autentikasi</th>
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
			const users = ['nurhayati@sumbul','agus@wonokoyo','amir@wonokoyo','matali@sumbul'];
			const nas = ['4011','CCR','RB750'];
			const called = ['service3','vlan-1000-wonokoyo'];
			const response = [
				(u)=>`user ${u} logged in`,
				(u)=>`user ${u} logged out : Lost-Carrier`
			];
			let id = 296;
			for (let i=0;i<296;i++) {
				const d = new Date();
				d.setMinutes(d.getMinutes() - i*11);
				const u = users[i%users.length];
				const mac = () => Array.from({length:6},()=>('0'+Math.floor(Math.random()*256).toString(16)).slice(-2)).join(':').toUpperCase();
				data.push({
					id: id--,
					user: u,
					nas: nas[i%nas.length],
					called: called[i%called.length],
					calling: mac(),
					resp: response[i%response.length](u),
					date: d.toISOString().slice(0,19).replace('T',' ')
				});
			}
		})();

		let currentPage = 1;
		function getFilteredData(){
			const q = (document.getElementById('searchInput').value||'').toLowerCase();
			return data.filter(r=>!q || (r.user+" "+r.nas+" "+r.called+" "+r.calling+" "+r.resp).toLowerCase().includes(q));
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
					<td>${r.user}</td>
					<td>${r.nas}</td>
					<td>${r.called}</td>
					<td>${r.calling}</td>
					<td>${r.resp.includes('logged in')?'<span class="radius-ok">'+r.resp+'</span>':'<span class="radius-lost">'+r.resp+'</span>'}</td>
					<td>${r.date}</td>
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
				html += `<button class=\"btn ${i===currentPage?'btn-danger':''}\" onclick=\"gotoPage(${i})\">${i}</button>`;
			}
			html += `<button class=\"btn\" onclick=\"gotoPage(${Math.min(total,currentPage+1)})\">Next</button>`;
			pag.innerHTML = html;
		}
		function gotoPage(p){ renderTable(p); }
		function clearLogs(){ if(confirm('Hapus log autentikasi (simulasi)?')) alert('Authentication logs cleared (simulasi).'); }
		document.addEventListener('DOMContentLoaded', ()=> renderTable(1));
	</script>
</body>
</html>
