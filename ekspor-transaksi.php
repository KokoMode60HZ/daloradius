<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	$log = "visited page: export transaksi bulanan";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ekspor Transaksi Bulanan (Excel) - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/popup.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 520px; }
		.card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
		.card-header { padding:12px 16px; border-bottom:1px solid #e9ecef; display:flex; align-items:center; gap:10px; font-weight:700; color:#2b2f36; }
		.card-body { padding:16px; }
		.form-group { margin-bottom:14px; }
		.label { display:block; font-size:13px; color:#555; margin-bottom:6px; }
		.select { width:100%; padding:10px; border:1px solid #dfe3e8; border-radius:4px; }
		.btn { padding:10px 14px; border:none; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:14px; }
		.btn-primary { background:#1558b0; color:#fff; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="container">
			<div class="card">
				<div class="card-header"><i class="fas fa-file-excel"></i> Ekspor Transaksi Bulanan (Excel)</div>
				<div class="card-body">
					<div class="form-group">
						<label class="label">Tipe</label>
						<select id="tipe" class="select">
							<option value="">- Semua Transaksi -</option>
							<option value="income">Pemasukan</option>
							<option value="expense">Pengeluaran</option>
						</select>
					</div>
					<div class="form-group">
						<label class="label">Metode</label>
						<select id="metode" class="select">
							<option value="">- Semua Metode -</option>
							<option value="gopay">GoPay</option>
							<option value="dana">Dana</option>
							<option value="shopeepay">ShopeePay</option>
							<option value="bank_transfer">Bank Transfer</option>
							<option value="cash">Cash</option>
						</select>
					</div>
					<div class="form-group">
						<label class="label">Status Pembayaran</label>
						<select id="status" class="select">
							<option value="">- Semua Status Bayar -</option>
							<option value="pending">Pending</option>
							<option value="success">Success</option>
							<option value="failed">Failed</option>
							<option value="cancelled">Cancelled</option>
						</select>
					</div>
					<div class="form-group">
						<label class="label">Owner Data</label>
						<select id="owner" class="select">
							<option value="">- Semua Owner -</option>
							<option value="root">root</option>
							<option value="user">user</option>
							<option value="admin">admin</option>
							<option value="operator">operator</option>
						</select>
					</div>
					<div class="form-group">
						<label class="label">Pilih Tahun</label>
						<select id="tahun" class="select">
							<?php for($y = date('Y'); $y >= date('Y')-5; $y--) { echo '<option value="'.$y.'">'.$y.'</option>'; } ?>
						</select>
					</div>
					<div class="form-group">
						<label class="label">Pilih Bulan</label>
						<select id="bulan" class="select">
							<?php $bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember']; foreach($bulan as $k=>$v){ echo '<option value="'.$k.'">'.$v.'</option>'; } ?>
						</select>
					</div>
					<div class="form-group">
						<button class="btn btn-primary" onclick="exportTransaksi()"><i class="fas fa-file-export"></i> Ekspor Transaksi</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>

	<script>
		function exportTransaksi(){
			const payload = {
				tipe: document.getElementById('tipe').value,
				metode: document.getElementById('metode').value,
				status: document.getElementById('status').value,
				owner: document.getElementById('owner').value,
				tahun: document.getElementById('tahun').value,
				bulan: document.getElementById('bulan').value,
			};
			alert('Ekspor transaksi (simulasi) dengan filter:\n'+JSON.stringify(payload, null, 2));
		}
	</script>
</body>
</html>
