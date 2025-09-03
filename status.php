<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content (Status page, NO Status Hari Ini button) -->
<div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
    <div class="dashboard-content" style="display:flex;flex-direction:column;gap:32px;max-width:600px;">
        <!-- Status Cards Vertically -->
        <div class="dashboard-card" style="background:#43a047;border-radius:18px;padding:32px;text-align:left;box-shadow:0 2px 12px rgba(0,0,0,0.10);font-size:1.3em;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:2em;font-weight:bold;color:#fff;">Rp. 0</div>
                <div style="color:#fff;">Income Hari Ini (IDR)</div>
                <a href="#" style="color:#fff;text-decoration:underline;font-size:1em;margin-top:12px;display:inline-block;"><i class="fas fa-arrow-right"></i> Lihat Detil</a>
            </div>
            <img src="images/bayar-online.png" alt="" style="height:64px;opacity:0.2;">
        </div>
        <div class="dashboard-card" style="background:#fbc02d;border-radius:18px;padding:32px;text-align:left;box-shadow:0 2px 12px rgba(0,0,0,0.10);font-size:1.3em;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:2em;font-weight:bold;color:#fff;">0 Invoice</div>
                <div style="color:#fff;">Data Tagihan</div>
                <a href="#" style="color:#fff;text-decoration:underline;font-size:1em;margin-top:12px;display:inline-block;"><i class="fas fa-arrow-right"></i> Lihat Detil</a>
            </div>
            <img src="images/tagihan.png" alt="" style="height:64px;opacity:0.2;">
        </div>
        <div class="dashboard-card" style="background:#0097a7;border-radius:18px;padding:32px;text-align:left;box-shadow:0 2px 12px rgba(0,0,0,0.10);font-size:1.3em;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:2em;font-weight:bold;color:#fff;">0 Users</div>
                <div style="color:#fff;">PPP Online</div>
                <a href="#" style="color:#fff;text-decoration:underline;font-size:1em;margin-top:12px;display:inline-block;"><i class="fas fa-arrow-right"></i> Lihat Detil</a>
            </div>
            <img src="images/profil.png" alt="" style="height:64px;opacity:0.2;">
        </div>
        <div class="dashboard-card" style="background:#e53935;border-radius:18px;padding:32px;text-align:left;box-shadow:0 2px 12px rgba(0,0,0,0.10);font-size:1.3em;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:2em;font-weight:bold;color:#fff;">0 Users</div>
                <div style="color:#fff;">HOTSPOT Online</div>
                <a href="#" style="color:#fff;text-decoration:underline;font-size:1em;margin-top:12px;display:inline-block;"><i class="fas fa-arrow-right"></i> Lihat Detil</a>
            </div>
            <img src="images/hotspot.png" alt="" style="height:64px;opacity:0.2;">
        </div>
        <!-- System Info Cards Vertically -->
        <div style="display:flex;flex-direction:column;gap:12px;margin-top:24px;">
            <div style="display:flex;align-items:center;">
                <span style="background:#bdbdbd;color:#333;padding:8px 18px;border-radius:8px 0 0 8px;font-weight:bold;">&#128336; UPTIME</span>
                <span style="background:#eee;color:#333;padding:8px 18px;border-radius:0 8px 8px 0;font-weight:bold;">503 DAY 11 HOUR 7 MIN</span>
            </div>
            <div style="display:flex;align-items:center;">
                <span style="background:#bdbdbd;color:#333;padding:8px 18px;border-radius:8px 0 0 8px;font-weight:bold;"><i class="fas fa-memory"></i> RAM</span>
                <span style="background:#eee;color:#333;padding:8px 18px;border-radius:0 8px 8px 0;font-weight:bold;">32668 MB TOTAL</span>
            </div>
            <div style="display:flex;align-items:center;">
                <span style="background:#bdbdbd;color:#333;padding:8px 18px;border-radius:8px 0 0 8px;font-weight:bold;"><i class="fas fa-memory"></i> FREE RAM</span>
                <span style="background:#eee;color:#333;padding:8px 18px;border-radius:0 8px 8px 0;font-weight:bold;">3867 MB FREE</span>
            </div>
            <div style="display:flex;align-items:center;">
                <span style="background:#bdbdbd;color:#333;padding:8px 18px;border-radius:8px 0 0 8px;font-weight:bold;"><i class="fas fa-hdd"></i> HDD/SSD</span>
                <span style="background:#eee;color:#333;padding:8px 18px;border-radius:0 8px 8px 0;font-weight:bold;">106.6 GB FREE</span>
            </div>
        </div>
    </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
    <?php include 'page-footer.php'; ?>
</div>

