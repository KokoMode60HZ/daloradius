<?php
	include_once('library/config_read.php');
	include_once('library/checklogin.php');
	include_once('library/MikroTikSimulator.php');
	
	$log = "visited page: mikrotik dashboard";
	include('include/config/logging.php');
	
	// Simulasi koneksi ke MikroTik
	$mikrotik = new MikroTikSimulator('192.168.1.1', 8728, 'admin', '');
	$mikrotik->connect();
	
	// Ambil data dari simulator
	$systemInfo = $mikrotik->getSystemResource()[0];
	$activeUsers = $mikrotik->getActiveUsers();
	$interfaces = $mikrotik->getInterfaces();
	$bandwidth = $mikrotik->getBandwidthMonitoring();
	$profiles = $mikrotik->getPPPProfiles();
	
	$mikrotik->disconnect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>MikroTik Router Dashboard - daloRADIUS</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.dashboard-container { max-width: 1200px; margin: 0 auto; }
		.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
		.stat-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); padding: 20px; }
		.stat-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
		.stat-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; }
		.stat-title { font-size: 14px; color: #666; margin: 0; }
		.stat-value { font-size: 24px; font-weight: bold; color: #2b2f36; margin: 4px 0 0 0; }
		.data-table { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow: hidden; }
		.table-header { background: #f8f9fa; padding: 16px; border-bottom: 1px solid #dee2e6; font-weight: bold; color: #2b2f36; }
		.table-content { padding: 0; }
		table { width: 100%; border-collapse: collapse; }
		th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #dee2e6; }
		th { background: #f8f9fa; font-weight: 600; color: #495057; }
		.status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
		.status-active { background: #d4edda; color: #155724; }
		.status-inactive { background: #f8d7da; color: #721c24; }
		.bandwidth-bar { background: #e9ecef; height: 8px; border-radius: 4px; overflow: hidden; }
		.bandwidth-fill { height: 100%; background: linear-gradient(90deg, #28a745, #20c997); }
	</style>
</head>
<body>
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/sidebar-new.php'); ?>

	<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
		<div class="dashboard-container">
			<h1 style="margin-bottom: 30px; color: #2b2f36;">
				<i class="fas fa-network-wired"></i> MikroTik Router Dashboard
			</h1>

			<!-- System Stats -->
			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-header">
						<div class="stat-icon" style="background: #007bff;">
							<i class="fas fa-server"></i>
						</div>
						<div>
							<h3 class="stat-title">Router Model</h3>
							<p class="stat-value"><?php echo $systemInfo['board-name']; ?></p>
						</div>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-header">
						<div class="stat-icon" style="background: #28a745;">
							<i class="fas fa-clock"></i>
						</div>
						<div>
							<h3 class="stat-title">Uptime</h3>
							<p class="stat-value"><?php echo $systemInfo['uptime']; ?></p>
						</div>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-header">
						<div class="stat-icon" style="background: #ffc107;">
							<i class="fas fa-memory"></i>
						</div>
						<div>
							<h3 class="stat-title">Free Memory</h3>
							<p class="stat-value"><?php echo $systemInfo['free-memory']; ?></p>
						</div>
					</div>
				</div>

				<div class="stat-card">
					<div class="stat-header">
						<div class="stat-icon" style="background: #dc3545;">
							<i class="fas fa-users"></i>
						</div>
						<div>
							<h3 class="stat-title">Active Users</h3>
							<p class="stat-value"><?php echo count($activeUsers); ?></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Active Users Table -->
			<div class="data-table" style="margin-bottom: 30px;">
				<div class="table-header">
					<i class="fas fa-users"></i> Active Users
				</div>
				<div class="table-content">
					<table>
						<thead>
							<tr>
								<th>Username</th>
								<th>IP Address</th>
								<th>Service</th>
								<th>Uptime</th>
								<th>Download</th>
								<th>Upload</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($activeUsers as $user): ?>
							<tr>
								<td><?php echo htmlspecialchars($user['name']); ?></td>
								<td><?php echo htmlspecialchars($user['address']); ?></td>
								<td><?php echo htmlspecialchars($user['service']); ?></td>
								<td><?php echo htmlspecialchars($user['uptime']); ?></td>
								<td><?php echo htmlspecialchars($user['bytes-in']); ?></td>
								<td><?php echo htmlspecialchars($user['bytes-out']); ?></td>
								<td><span class="status-badge status-active">Active</span></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Interface & Bandwidth -->
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
				<!-- Interfaces -->
				<div class="data-table">
					<div class="table-header">
						<i class="fas fa-ethernet"></i> Network Interfaces
					</div>
					<div class="table-content">
						<table>
							<thead>
								<tr>
									<th>Interface</th>
									<th>Type</th>
									<th>Status</th>
									<th>MTU</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($interfaces as $interface): ?>
								<tr>
									<td><?php echo htmlspecialchars($interface['name']); ?></td>
									<td><?php echo htmlspecialchars($interface['type']); ?></td>
									<td>
										<span class="status-badge <?php echo $interface['running'] == 'true' ? 'status-active' : 'status-inactive'; ?>">
											<?php echo $interface['running'] == 'true' ? 'Running' : 'Down'; ?>
										</span>
									</td>
									<td><?php echo htmlspecialchars($interface['mtu']); ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Bandwidth Monitoring -->
				<div class="data-table">
					<div class="table-header">
						<i class="fas fa-tachometer-alt"></i> Bandwidth Monitoring
					</div>
					<div class="table-content">
						<table>
							<thead>
								<tr>
									<th>Interface</th>
									<th>Download</th>
									<th>Upload</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($bandwidth as $bw): ?>
								<tr>
									<td><?php echo htmlspecialchars($bw['interface']); ?></td>
									<td>
										<div style="display: flex; align-items: center; gap: 8px;">
											<span><?php echo htmlspecialchars($bw['rx-bits-per-second']); ?></span>
											<div class="bandwidth-bar" style="width: 60px;">
												<div class="bandwidth-fill" style="width: 75%;"></div>
											</div>
										</div>
									</td>
									<td>
										<div style="display: flex; align-items: center; gap: 8px;">
											<span><?php echo htmlspecialchars($bw['tx-bits-per-second']); ?></span>
											<div class="bandwidth-bar" style="width: 60px;">
												<div class="bandwidth-fill" style="width: 60%;"></div>
											</div>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- PPP Profiles -->
			<div class="data-table">
				<div class="table-header">
					<i class="fas fa-cogs"></i> PPP Profiles
				</div>
				<div class="table-content">
					<table>
						<thead>
							<tr>
								<th>Profile Name</th>
								<th>Local Address</th>
								<th>Remote Address Pool</th>
								<th>Rate Limit</th>
								<th>Only One</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($profiles as $profile): ?>
							<tr>
								<td><?php echo htmlspecialchars($profile['name']); ?></td>
								<td><?php echo htmlspecialchars($profile['local-address']); ?></td>
								<td><?php echo htmlspecialchars($profile['remote-address']); ?></td>
								<td><?php echo htmlspecialchars($profile['rate-limit']); ?></td>
								<td>
									<span class="status-badge <?php echo $profile['only-one'] == 'yes' ? 'status-active' : 'status-inactive'; ?>">
										<?php echo $profile['only-one'] == 'yes' ? 'Yes' : 'No'; ?>
									</span>
								</td>
							</tr>
							<?php endforeach; ?>
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
