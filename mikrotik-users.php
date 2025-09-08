<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	include_once('library/MikroTikSimulator.php');
	
	$log = "visited page: mikrotik active users";
	include('include/config/logging.php');
	
	// Ambil data active users dari MikroTik
	$mikrotik = new MikroTikSimulator('192.168.1.1', 8728, 'admin', '');
	$mikrotik->connect();
	$activeUsers = $mikrotik->getActiveUsers();
	$mikrotik->disconnect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Active Users - MikroTik - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 1200px; margin: 0 auto; }
		.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
		.stat-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); padding: 20px; text-align: center; }
		.stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #fff; font-size: 24px; }
		.stat-value { font-size: 28px; font-weight: bold; color: #2b2f36; margin-bottom: 4px; }
		.stat-label { font-size: 14px; color: #666; }
		.data-table { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow: hidden; }
		.table-header { background: #f8f9fa; padding: 16px; border-bottom: 1px solid #dee2e6; font-weight: bold; color: #2b2f36; }
		.table-content { padding: 0; }
		table { width: 100%; border-collapse: collapse; }
		th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #dee2e6; }
		th { background: #f8f9fa; font-weight: 600; color: #495057; }
		.status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
		.status-active { background: #d4edda; color: #155724; }
		.service-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
		.service-pppoe { background: #cce5ff; color: #004085; }
		.service-hotspot { background: #fff3cd; color: #856404; }
		.service-vpn { background: #f8d7da; color: #721c24; }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="container">
			<h1 style="margin-bottom: 30px; color: #2b2f36;">
				<i class="fas fa-users"></i> Active Users - MikroTik Router
			</h1>

			<!-- Statistics -->
			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-icon" style="background: #007bff;">
						<i class="fas fa-users"></i>
					</div>
					<div class="stat-value"><?php echo count($activeUsers); ?></div>
					<div class="stat-label">Total Active Users</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon" style="background: #28a745;">
						<i class="fas fa-plug"></i>
					</div>
					<div class="stat-value"><?php echo count(array_filter($activeUsers, function($u) { return $u['service'] == 'pppoe'; })); ?></div>
					<div class="stat-label">PPPOE Users</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon" style="background: #ffc107;">
						<i class="fas fa-signal"></i>
					</div>
					<div class="stat-value"><?php echo count(array_filter($activeUsers, function($u) { return $u['service'] == 'hotspot'; })); ?></div>
					<div class="stat-label">Hotspot Users</div>
				</div>

				<div class="stat-card">
					<div class="stat-icon" style="background: #dc3545;">
						<i class="fas fa-random"></i>
					</div>
					<div class="stat-value"><?php echo count(array_filter($activeUsers, function($u) { return $u['service'] == 'vpn'; })); ?></div>
					<div class="stat-label">VPN Users</div>
				</div>
			</div>

			<!-- Active Users Table -->
			<div class="data-table">
				<div class="table-header">
					<i class="fas fa-list"></i> Active Users List
				</div>
				<div class="table-content">
					<table>
						<thead>
							<tr>
								<th>Username</th>
								<th>IP Address</th>
								<th>Service Type</th>
								<th>Uptime</th>
								<th>Download</th>
								<th>Upload</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($activeUsers) > 0): ?>
								<?php foreach($activeUsers as $user): ?>
								<tr>
									<td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
									<td><?php echo htmlspecialchars($user['address']); ?></td>
									<td>
										<span class="service-badge service-<?php echo $user['service']; ?>">
											<?php echo strtoupper($user['service']); ?>
										</span>
									</td>
									<td><?php echo htmlspecialchars($user['uptime']); ?></td>
									<td><?php echo htmlspecialchars($user['bytes-in']); ?></td>
									<td><?php echo htmlspecialchars($user['bytes-out']); ?></td>
									<td><span class="status-badge status-active">Active</span></td>
									<td>
										<button style="background:#007bff;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:0.8em;margin-right:4px;">
											<i class="fas fa-eye"></i> View
										</button>
										<button style="background:#dc3545;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:0.8em;">
											<i class="fas fa-ban"></i> Disconnect
										</button>
									</td>
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="8" style="text-align:center;padding:40px;color:#666;">
										<i class="fas fa-users" style="font-size:48px;margin-bottom:16px;display:block;"></i>
										No active users found
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Demo Notice -->
			<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 16px; border-radius: 8px; margin-top: 30px;">
				<h4 style="margin: 0 0 8px 0; color: #856404;">
					<i class="fas fa-info-circle"></i> Demo Mode
				</h4>
				<p style="margin: 0; color: #856404;">
					Data yang ditampilkan adalah simulasi untuk demo PKL. 
					Untuk produksi, ganti ke MikroTikAPI.php dan setup router fisik.
				</p>
			</div>
		</div>
	</div>

	<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
		<?php include 'page-footer.php'; ?>
	</div>
</body>
</html>
