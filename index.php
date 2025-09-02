<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: ";
    include('include/config/logging.php');
?>

<?php include 'includes/sidebar.php'; ?>

<div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
  <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
  <a href="status.php" style="display:inline-flex;align-items:center;gap:8px;background:#fb8c00;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-size:1.2em;font-weight:bold;box-shadow:0 2px 8px rgba(0,0,0,0.07);transition:background 0.2s;">
    <i class="fas fa-chart-line"></i> Status Hari Ini
  </a>
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
          <span class="badge badge-success" style="background:#43a047;color:#fff;padding:4px 12px;border-radius:8px;margin-left:8px;">
    <?php echo date('F d, Y'); ?>
</span>
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
          <span class="badge badge-success" style="background:#43a047;color:#fff;padding:4px 12px;border-radius:8px;margin-left:8px;">
    <?php echo date('F d, Y'); ?>
  </span>
        </div>
      </div>
    </aside>
  </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
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
