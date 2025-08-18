<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	// include ("menu-home.php");

    include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Sidebar Navigation -->
<div class="sidebar-nav" style="position:fixed;top:0;left:0;width:220px;height:100vh;background:#1976d2;color:#fff;display:flex;flex-direction:column;z-index:100;">
    <div style="padding:24px 16px 12px 16px;display:flex;align-items:center;gap:12px;">
        <img src="images/daloradius_logo.jpg" alt="Logo" style="height:48px;">
        <span style="font-size:1.2em;font-weight:bold;">daloRADIUS</span>
    </div>
    <nav style="flex:1;">
        <a href="index.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Home</a>
        <a href="mng-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Management</a>
        <a href="rep-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Reports</a>
        <a href="acct-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Accounting</a>
        <a href="bill-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Billing</a>
        <a href="gis-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">GIS</a>
        <a href="graph-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Graphs</a>
        <a href="config-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Config</a>
        <a href="help-main.php" style="display:block;padding:12px 24px;color:#fff;text-decoration:none;">Help</a>
    </nav>
    <div style="padding:16px 16px 24px 16px;border-top:1px solid #1565c0;">
        <span style="font-size:1em;">Welcome, <b><?php echo htmlspecialchars($operator); ?></b>.</span><br>
        <span style="font-size:1em;">Location: <b>default.</b></span>
        <a href="logout.php" title="Logout" style="float:right;color:#fff;font-size:1.2em;margin-top:-24px;">
            <i class="fas fa-times"></i>
        </a>
    </div>
</div>

<div class="dashboard-page" style="margin-left:240px;padding:24px;">
  <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <span class="header-icon" style="font-size:2em;color:#009688;"><i class="fas fa-th-large"></i></span>
    <span class="header-title" style="font-size:1.5em;font-weight:bold;">Status, Hari ini</span>
  </div>
  <div class="dashboard-content" style="display:flex;gap:32px;">
    <div class="dashboard-main" style="flex:1;">
      <div class="dashboard-tabs" style="margin-bottom:18px;">
        <button class="tab active" style="padding:8px 24px;border-radius:8px;background:#009688;color:#fff;border:none;" onclick="window.location.href='index.php'">Ringkasan</button>
        <button class="tab" style="padding:8px 24px;border-radius:8px;background:#eee;color:#333;border:none;" onclick="window.location.href='aktivitas.php'">Aktivitas</button>
        <button class="tab" style="padding:8px 24px;border-radius:8px;background:#eee;color:#333;border:none;" onclick="window.location.href='trafik.php'">Trafik</button>
      </div>
      <div class="dashboard-cards-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:32px;">
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-signal" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">HOTSPOT USER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-plug" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">PPPOE USER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-random" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">VPN USER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-id-card" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">TOTAL VOUCHER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-print" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">VC CREATED TODAY</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-sign-in-alt" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">VC LOGIN TODAY</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-calendar-times" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">EXP VOUCHER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-user-times" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">EXP CUSTOMER</div>
          <div class="card-value" style="font-size:1.3em;">0</div>
        </div>
        <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
          <i class="fas fa-calendar-alt" style="font-size:2em;color:#009688;"></i>
          <div class="card-label" style="margin-top:8px;font-weight:bold;">Tanggal</div>
          <div class="card-value" style="font-size:1.1em;">
            <?php echo date('d F Y'); ?>
            <br>
            <span class="card-time" id="live-time" style="font-size:0.9em;"></span>
          </div>
        </div>
      </div>
      <div class="dashboard-table-section" style="margin-bottom:32px;">
        <div class="table-tabs" style="margin-bottom:12px;">
          <button class="tab active" style="padding:8px 24px;border-radius:8px;background:#009688;color:#fff;border:none;">Invoice Harian</button>
          <button class="tab" style="padding:8px 24px;border-radius:8px;background:#eee;color:#333;border:none;">Invoice</button>
          <button class="tab" style="padding:8px 24px;border-radius:8px;background:#eee;color:#333;border:none;">Router</button>
        </div>
        <div class="table-responsive">
          <table class="styled-table" style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#009688;color:#fff;">
                <th>Id</th>
                <th>Invoice</th>
                <th>ID Pelanggan</th>
                <th>Nama</th>
                <th>Tipe Service</th>
                <th>Server</th>
                <th>Paket Langganan</th>
                <th>Jumlah</th>
                <th>Periode Bayar</th>
                <th>Owner Data</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="11" style="text-align:center;">No data available in the table</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <aside class="dashboard-panel-kanan" style="width:320px;min-width:260px;">
      <div class="panel-title" style="font-weight:bold;font-size:1.2em;margin-bottom:18px;"><i class="fas fa-info-circle"></i> Informasi Layanan</div>
      <div class="panel-status">
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-database"></i> CORE RADIUS
          <span class="badge badge-success" style="background:#43a047;color:#fff;padding:4px 12px;border-radius:8px;margin-left:8px;">Running</span>
          <span class="badge badge-light" style="background:#eee;color:#333;padding:4px 12px;border-radius:8px;margin-left:8px;">Restart</span>
        </div>
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-server"></i> MIKROTIK
          <span class="panel-bar bar-red" style="display:inline-block;width:40px;height:8px;background:#e53935;border-radius:4px;margin-left:8px;"></span>
          <span class="panel-label" style="margin-left:8px;">4 Router <span class="text-danger" style="color:#e53935;">0 Quota</span></span>
        </div>
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-globe"></i> SESSION
          <span class="panel-bar bar-blue" style="display:inline-block;width:40px;height:8px;background:#1e88e5;border-radius:4px;margin-left:8px;"></span>
          <span class="panel-label" style="margin-left:8px;">400 <i class="fas fa-chart-bar"></i> 200 Quota</span>
        </div>
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-users"></i> PELANGGAN
          <span class="panel-bar bar-green" style="display:inline-block;width:40px;height:8px;background:#43a047;border-radius:4px;margin-left:8px;"></span>
          <span class="panel-label" style="margin-left:8px;">568 Quota</span>
        </div>
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-id-card"></i> VOUCHER
          <span class="panel-label" style="margin-left:8px;">65000 Quota</span>
        </div>
        <div class="panel-status-row" style="margin-bottom:12px;">
          <i class="fas fa-calendar-alt"></i> JATUH TEMPO
          <span class="badge badge-success" style="background:#43a047;color:#fff;padding:4px 12px;border-radius:8px;margin-left:8px;">August 01, 2025</span>
        </div>
      </div>
    </aside>
  </div>
</div>

<div id="footer">
    <?php include 'page-footer.php'; ?>
</div>
<script>
function updateTime() {
    const now = new Date();
    // Format jam:menit:detik
    const timeStr = now.toLocaleTimeString('id-ID', { hour12: false });
    document.getElementById('live-time').textContent = timeStr;
}
// Update setiap detik
setInterval(updateTime, 1000);
// Jalankan sekali saat halaman dimuat
updateTime();
</script>
</body>
</html>
