<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: log wa blast";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log WA Blast - daloRADIUS</title>
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
		.notice { padding:10px 16px; font-size:13px; color:#333; display:flex; align-items:center; gap:10px; }
		.notice .badge-failed { background:#dc3545; color:#fff; padding:4px 8px; border-radius:4px; font-size:11px; }
		.filters { display:flex; gap:16px; align-items:center; padding:0 16px 12px; }
		.filters label { font-size:13px; color:#555; }
		.input-date { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.btn { padding:8px 12px; border:none; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:14px; }
		.btn i { font-size:14px; }
		.btn-primary { background:#1558b0; color:#fff; }
		.btn-success { background:#28a745; color:#fff; }
		.btn-outline { background:#fff; color:#1558b0; border:1px solid #1558b0; }
		.table-wrap { padding:0 16px 16px; }
		.table { width:100%; border-collapse:collapse; }
		.table th, .table td { padding:10px 12px; border-bottom:1px solid #f0f0f0; text-align:left; }
		.table th { background:#f8f9fb; color:#333; font-weight:600; position:sticky; top:0; z-index:1; }
		.table tr:hover { background:#fafbfd; }
		.badge { padding:3px 8px; border-radius:999px; font-size:11px; font-weight:700; }
		.badge-success { background:#e7f9ee; color:#138a47; border:1px solid #b6e7cc; }
		.badge-failed { background:#fde8e8; color:#c53030; border:1px solid #f5b5b5; }
		.small { font-size:12px; color:#6c757d; }
		.controls { display:flex; align-items:center; gap:12px; padding:12px 16px; }
		.controls .search { margin-left:auto; }
		.controls input[type="text"], .controls select { padding:8px 10px; border:1px solid #dfe3e8; border-radius:4px; }
		.pagination { display:flex; justify-content:flex-end; gap:6px; padding:12px 16px; }
		.pagination .btn { padding:6px 10px; font-size:12px; }
		.btn-resend { background:#20c997; color:#fff; padding:6px 10px; font-size:12px; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="container">
			<div class="card">
				<div class="card-header">
					<div class="card-title"><i class="fas fa-comments"></i> Log WA Blast</div>
					<div class="toolbar">
						<button class="btn btn-outline" onclick="exportData()"><i class="fas fa-file-export"></i> Export</button>
					</div>
				</div>
				<div class="notice">
					<strong>INFO:</strong> *** Fitur KIRIM ULANG ini hanya untuk pesan <span class="badge-failed">FAILED</span>
				</div>

				<div class="filters">
					<div>
						<label>Kirim : Dari Tanggal</label><br>
						<input type="date" id="fromDate" class="input-date" value="<?php echo date('Y-m-d'); ?>">
					</div>
					<div>
						<label>Sampai Tanggal</label><br>
						<input type="date" id="toDate" class="input-date" value="<?php echo date('Y-m-d'); ?>">
					</div>
					<div style="margin-top:18px;">
						<button class="btn btn-primary" onclick="applyDateFilter()"><i class="fas fa-paper-plane"></i> Kirim Ulang</button>
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
								<th style="width:160px;">Tanggal</th>
								<th style="width:340px;">Pesan</th>
								<th style="width:160px;">No. Penerima</th>
								<th style="width:180px;">Nama</th>
								<th style="width:160px;">Tipe Notifikasi</th>
								<th style="width:130px;">WA Gateway</th>
								<th style="width:120px;">Status</th>
								<th style="width:160px;">Owner Data</th>
								<th style="width:110px;">Aksi</th>
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
			const names = ['Rahma Tumapel','Elok Wonokoyo','Sahrul','Nur Indriasari','Kancil Bontoro','Wantono','Sumberwangi Opet','Airin','Nikmah','Rudi jrgkrikil'];
			const notif = ['Receipt | automatic','Invoice | automatic','Audit | automatic'];
			let id = 2733;
			for (let i=0;i<2278;i++) {
				const d = new Date(); d.setMinutes(d.getMinutes() - i*3);
				const isFail = i%3===0; // sebagian gagal
				data.push({
					id: id--,
					date: d.toISOString().slice(0,19).replace('T',' '),
					msg: '*Lintas Jaringan Nusantara Kantor Layanan* — invoice '+(100000+i),
					phone: (i%7===0?'62829654086080':'000000000000'),
					name: names[i%names.length],
					type: notif[i%notif.length],
					gateway: 'WABLAS-V2',
					status: isFail ? 'FAILED' : 'SUCCESS',
					owner: i%2===0 ? 'user | module' : 'root | scheduler'
				});
			}
		})();

		let currentPage = 1;
		function inRange(dateStr){
			const from = document.getElementById('fromDate').value;
			const to = document.getElementById('toDate').value;
			const d = dateStr.slice(0,10);
			return (!from || d>=from) && (!to || d<=to);
		}
		function getFilteredData(){
			const q = (document.getElementById('searchInput').value||'').toLowerCase();
			return data.filter(r=> inRange(r.date) && (!q || (r.msg+" "+r.phone+" "+r.name+" "+r.type+" "+r.gateway+" "+r.status+" "+r.owner).toLowerCase().includes(q)) );
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
					<td title="${r.msg}">${r.msg.length>48?r.msg.slice(0,48)+'...':r.msg}</td>
					<td>${r.phone}</td>
					<td>${r.name}</td>
					<td>${r.type}</td>
					<td>${r.gateway}</td>
					<td>${r.status==='SUCCESS'?'<span class="badge badge-success">✓ SUCCESS</span>':'<span class="badge badge-failed">✖ FAILED</span>'}</td>
					<td>${r.owner}</td>
					<td>${r.status==='FAILED'?'<button class="btn-resend" onclick="resend('+r.id+')"><i class="fas fa-redo"></i> Resend</button>':''}</td>
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
		function exportData(){ alert('Export log WA Blast (simulasi).'); }
		function applyDateFilter(){ renderTable(1); }
		function resend(id){ alert('Resend id '+id+' (simulasi). Hanya untuk status FAILED.'); }
		document.addEventListener('DOMContentLoaded', ()=> renderTable(1));
	</script>
</body>
</html>
