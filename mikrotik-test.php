<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	include_once('library/MikroTikSimulator.php');
	
	$log = "visited page: mikrotik test";
	include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Test Koneksi MikroTik - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.test-container { max-width: 800px; margin: 0 auto; }
		.test-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); margin-bottom: 20px; }
		.test-header { padding: 16px; border-bottom: 1px solid #e9ecef; font-weight: bold; color: #2b2f36; }
		.test-body { padding: 16px; }
		.form-group { margin-bottom: 16px; }
		.label { display: block; font-size: 14px; color: #555; margin-bottom: 6px; }
		.input { width: 100%; padding: 10px; border: 1px solid #dfe3e8; border-radius: 4px; }
		.btn { padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
		.btn-primary { background: #1558b0; color: #fff; }
		.btn-success { background: #28a745; color: #fff; }
		.btn-danger { background: #dc3545; color: #fff; }
		.result-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 12px; margin-top: 12px; }
		.success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
		.error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="test-container">
			<div class="test-card">
				<div class="test-header">
					<i class="fas fa-network-wired"></i> Test Koneksi MikroTik Router
				</div>
				<div class="test-body">
					<form method="POST">
						<div class="form-group">
							<label class="label">IP Address Router</label>
							<input type="text" name="router_ip" class="input" value="<?php echo isset($_POST['router_ip']) ? htmlspecialchars($_POST['router_ip']) : '192.168.1.1'; ?>" placeholder="192.168.1.1">
						</div>
						<div class="form-group">
							<label class="label">Port API</label>
							<input type="number" name="router_port" class="input" value="<?php echo isset($_POST['router_port']) ? htmlspecialchars($_POST['router_port']) : '8728'; ?>" placeholder="8728">
						</div>
						<div class="form-group">
							<label class="label">Username</label>
							<input type="text" name="router_user" class="input" value="<?php echo isset($_POST['router_user']) ? htmlspecialchars($_POST['router_user']) : 'admin'; ?>" placeholder="admin">
						</div>
						<div class="form-group">
							<label class="label">Password</label>
							<input type="password" name="router_pass" class="input" value="<?php echo isset($_POST['router_pass']) ? htmlspecialchars($_POST['router_pass']) : ''; ?>" placeholder="password">
						</div>
						<button type="submit" name="test_connection" class="btn btn-primary">
							<i class="fas fa-plug"></i> Test Koneksi
						</button>
					</form>

					<?php
					if (isset($_POST['test_connection'])) {
						$router_ip = $_POST['router_ip'];
						$router_port = $_POST['router_port'];
						$router_user = $_POST['router_user'];
						$router_pass = $_POST['router_pass'];

						echo '<div class="result-box">';
						echo '<h4>Hasil Test Koneksi:</h4>';

						try {
							$mikrotik = new MikroTikSimulator($router_ip, $router_port, $router_user, $router_pass);
							$result = $mikrotik->testConnection();

							if ($result['status'] == 'success') {
								echo '<div class="success">';
								echo '<i class="fas fa-check-circle"></i> <strong>Koneksi Berhasil!</strong><br>';
								echo 'Router: ' . $router_ip . ':' . $router_port . '<br>';
								echo 'User: ' . $router_user . '<br>';
								echo '<pre>' . print_r($result['data'], true) . '</pre>';
								echo '</div>';
							} else {
								echo '<div class="error">';
								echo '<i class="fas fa-times-circle"></i> <strong>Koneksi Gagal!</strong><br>';
								echo 'Error: ' . $result['message'];
								echo '</div>';
							}
						} catch (Exception $e) {
							echo '<div class="error">';
							echo '<i class="fas fa-exclamation-triangle"></i> <strong>Error!</strong><br>';
							echo $e->getMessage();
							echo '</div>';
						}

						echo '</div>';
					}
					?>
				</div>
			</div>

			<div class="test-card">
				<div class="test-header">
					<i class="fas fa-info-circle"></i> Informasi Setup
				</div>
				<div class="test-body">
					<h4>Langkah-langkah Setup:</h4>
					<ol>
						<li><strong>Download MikroTik CHR:</strong> https://mikrotik.com/download</li>
						<li><strong>Install VirtualBox:</strong> https://www.virtualbox.org/</li>
						<li><strong>Setup CHR di VirtualBox</strong> dengan network Bridged</li>
						<li><strong>Konfigurasi Router:</strong>
							<ul>
								<li>Set IP: <code>/ip address add address=192.168.1.1/24 interface=ether1</code></li>
								<li>Enable API: <code>/ip service enable api</code></li>
								<li>Buat user: <code>/user add name=api-user password=api123 group=full</code></li>
							</ul>
						</li>
						<li><strong>Test koneksi</strong> menggunakan form di atas</li>
					</ol>
					
					<h4>Simulasi Settings (Demo Mode):</h4>
					<ul>
						<li><strong>IP:</strong> 192.168.1.1 (simulasi)</li>
						<li><strong>Port:</strong> 8728 (simulasi)</li>
						<li><strong>User:</strong> admin (password kosong) atau api-user (password: api123)</li>
						<li><strong>Mode:</strong> Simulator - data mock untuk demo</li>
					</ul>
					
					<div style="background:#fff3cd;border:1px solid #ffeaa7;padding:12px;border-radius:4px;margin-top:12px;">
						<strong>📝 Catatan:</strong> Ini adalah mode simulasi untuk demo PKL. 
						Data yang ditampilkan adalah mock data. Untuk produksi, 
						ganti ke MikroTikAPI.php dan setup router fisik.
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>
</body>
</html>
