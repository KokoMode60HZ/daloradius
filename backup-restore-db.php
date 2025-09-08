<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: backup restore db";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Backup - Restore Database - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 1200px; margin: 0 auto; }
		.card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		.header { padding:16px; border-bottom:1px solid #e9ecef; display:flex; align-items:center; justify-content:space-between; }
		.title { font-weight:700; color:#2b2f36; display:flex; align-items:center; gap:10px; }
		.body { padding:16px; }
		.kv { font-size:13px; color:#333; }
		.kv .muted { color:#6c757d; }
		.actions { display:flex; gap:8px; align-items:center; margin-top:12px; }
		input[type="file"] { padding:6px; border:1px solid #dfe3e8; border-radius:4px; background:#fff; }
		.btn { padding:8px 12px; border:none; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:14px; }
		.btn i { font-size:14px; }
		.btn-success { background:#28a745; color:#fff; }
		.btn-primary { background:#1558b0; color:#fff; }
		.note { font-size:12px; color:#6c757d; margin-top:6px; }
		.table-wrap { margin-top:16px; }
		.controls { display:flex; gap:12px; align-items:center; margin-bottom:8px; }
		.controls .search { margin-left:auto; }
		.controls input, .controls select { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.table { width:100%; border-collapse:collapse; }
		.table th, .table td { padding:10px 12px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.table th { background:#f8f9fb; color:#333; font-weight:600; }
		.pagination { display:flex; justify-content:flex-end; gap:6px; padding-top:8px; }
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
				<div class="header">
					<div class="title"><i class="fas fa-database"></i> Backup - Restore Database</div>
				</div>
				<div class="body">
					<div class="kv">
						Ukuran Database: <strong>30,64 MB</strong> <span class="muted">[ MAX UPLOAD SIZE : 32 MB ]</span><br>
						<span class="muted">Supported extension : .sql and .sql.gz (recommended)</span>
					</div>
					<div class="actions">
						<input type="file" id="restoreFile" accept=".sql,.gz" />
						<button class="btn btn-success" onclick="doRestore()"><i class="fas fa-rotate-left"></i> Restore DB</button>
						<button class="btn btn-primary" onclick="doBackup()"><i class="fas fa-download"></i> Backup DB</button>
					</div>
					<div class="note">Operasi ini disimulasikan untuk demo. Implementasi nyata perlu akses shell/DB dump.</div>

					<div class="table-wrap">
						<div class="controls">
							<div>
								<span class="small">Show</span>
								<select id="pageSize" onchange="renderTable(1)">
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
								</select>
								<span class="small">entries</span>
							</div>
							<div class="search">
								<input type="text" id="searchInput" placeholder="Search..." oninput="renderTable(1)">
							</div>
						</div>

						<table class="table">
							<thead>
								<tr>
									<th>Nama Tabel</th>
									<th style="width:120px;">Rows</th>
									<th style="width:140px;">Size</th>
								</tr>
							</thead>
							<tbody id="tableBody"></tbody>
						</table>
						<div class="small" id="tableInfo"></div>
						<div class="pagination" id="pagination"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>

	<script>
		const tables = [
			{name:'nas', rows:4, size:'48 KB'},
			{name:'radacct', rows:15570, size:'15,456 KB'},
			{name:'radcheck', rows:855, size:'160 KB'},
			{name:'radgroupcheck', rows:19, size:'32 KB'},
			{name:'radgroupreply', rows:27, size:'32 KB'},
			{name:'radippool', rows:1783, size:'352 KB'},
			{name:'radpostauth', rows:299, size:'64 KB'},
			{name:'radreply', rows:896, size:'144 KB'},
			{name:'radusergroup', rows:448, size:'80 KB'},
			{name:'tbl_activation', rows:1, size:'16 KB'},
			{name:'userinfo', rows:123, size:'120 KB'},
			{name:'operators', rows:7, size:'24 KB'}
		];

		let currentPage = 1;
		function getFiltered(){
			const q = (document.getElementById('searchInput').value||'').toLowerCase();
			return tables.filter(t=>!q || t.name.toLowerCase().includes(q));
		}
		function renderTable(page){
			currentPage = page || 1;
			const pageSize = parseInt(document.getElementById('pageSize').value,10);
			const rows = getFiltered();
			const start = (currentPage-1)*pageSize;
			const slice = rows.slice(start, start+pageSize);
			const body = document.getElementById('tableBody');
			body.innerHTML = slice.map(r=>`<tr><td>${r.name}</td><td>${r.rows}</td><td>${r.size}</td></tr>`).join('');
			document.getElementById('tableInfo').textContent = `Showing ${rows.length? start+1:0} to ${Math.min(start+pageSize, rows.length)} of ${rows.length} entries`;
			renderPagination(Math.ceil(rows.length/pageSize));
		}
		function renderPagination(total){
			const pag = document.getElementById('pagination');
			let html = '';
			html += `<button class=\"btn\" onclick=\"gotoPage(${Math.max(1,currentPage-1)})\">Previous</button>`;
			for(let i=1;i<=Math.min(total,7);i++){
				html += `<button class=\"btn ${i===currentPage?'btn-primary':''}\" onclick=\"gotoPage(${i})\">${i}</button>`;
			}
			html += `<button class=\"btn\" onclick=\"gotoPage(${Math.min(total,currentPage+1)})\">Next</button>`;
			pag.innerHTML = html;
		}
		function gotoPage(p){ renderTable(p); }

		function doBackup(){
			alert('Backup database berhasil dibuat (simulasi).');
		}
		function doRestore(){
			const f = document.getElementById('restoreFile');
			if(!f.files.length){ alert('Pilih file backup (.sql/.sql.gz) terlebih dahulu.'); return; }
			alert('Restore database dari '+f.files[0].name+' (simulasi).');
		}

		document.addEventListener('DOMContentLoaded', ()=> renderTable(1));
	</script>
</body>
</html>
