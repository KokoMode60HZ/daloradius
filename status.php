<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/dashboard.css" type="text/css" />

<!-- Sidebar Navigation (copied from index.php) -->
<div class="sidebar-nav" style="position:fixed;top:48px;left:0;width:220px;height:calc(100vh - 48px);background:#1558b0;color:#fff;display:flex;flex-direction:column;z-index:100;">
    <div id="topbar" style="position:fixed;top:0;left:0;width:100vw;height:48px;background:rgba(21,88,176,0.98);border-bottom:2px solid rgba(255,255,255,0.18);display:flex;align-items:center;gap:12px;padding:0 24px;z-index:101;">
        <img src="images/logo.png" alt="Logo" style="height:32px;">
        <span style="font-size:1.1em;font-weight:bold;color:#fff;">LJN Management</span>
    </div>
    <nav style="flex:1;">
        <a href="javascript:void(0);" id="sessionuser-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/session_user.png" alt="Session User" style="height:22px;width:22px;margin-right:12px;">
            Session User
            <span id="sessionuser-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="sessionuser-submenu" style="display:none;flex-direction:column;">
            <a href="hotspot.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Hotspot
            </a>
            <a href="ppp.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> PPP
            </a>
        </div>
        <a href="index.php" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;">
            <img src="images/board.png" alt="Dashboard" style="height:22px;width:22px;margin-right:12px;"> Dashboard
        </a>
        <a href="javascript:void(0);" id="pengaturan-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/pengaturan.png" alt="Pengaturan" style="height:22px;width:22px;margin-right:12px;">
            Pengaturan
            <span id="pengaturan-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="pengaturan-submenu" style="display:none;flex-direction:column;">
            <a href="pengaturan-umum.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Pengaturan Umum
            </a>
            <a href="lokalisasi.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Lokalisasi
            </a>
            <a href="manajemen-user.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Manajemen User
            </a>
            <a href="logo-invoice.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Logo Invoice
            </a>
            <a href="whatsapp-api.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Whatsapp API
            </a>
            <a href="setting-email.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Setting Email
            </a>
            <a href="setting-sms.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Setting SMS
            </a>
            <a href="google-map-api.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Google Map API
            </a>
            <a href="payment-gateway.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Payment Gateway
            </a>
            <a href="template-voucher.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Template Voucher
            </a>
            <a href="domain-hotspot.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Domain Hotspot
            </a>
            <a href="called-station.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Called Station
            </a>
            <a href="setting-api.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Setting API
            </a>
            <a href="info-lisensi.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Info Lisensi
            </a>
        </div>
        <a href="router-nas.php" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;">
            <img src="images/router-nas.png" alt="Router NAS" style="height:22px;width:22px;margin-right:12px;"> Router [NAS]
        </a>
        <a href="javascript:void(0);" id="odp-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/odp-pop.png" alt="Data ODP | POP" style="height:22px;width:22px;margin-right:12px;">
            Data ODP | POP
            <span id="odp-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="odp-submenu" style="display:none;flex-direction:column;">
            <a href="kelola-odp-pop.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Kelola ODP | POP
            </a>
            <a href="lihat-peta.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Lihat Peta
            </a>
        </div>
        <a href="javascript:void(0);" id="profil-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/profil.png" alt="Profil Paket" style="height:22px;width:22px;margin-right:12px;">
            Profil Paket
            <span id="profil-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="profil-submenu" style="display:none;flex-direction:column;">
            <a href="bandwidth.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Bandwidth
            </a>
            <a href="group-profil.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Group Profil
            </a>
            <a href="profil-hotspot.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Profil Hotspot
            </a>
            <a href="profil-ppp.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Profil PPP
            </a>
        </div>
        <a href="javascript:void(0);" id="pelanggan-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/pelanggan.png" alt="List Pelanggan" style="height:22px;width:22px;margin-right:12px;">
            List Pelanggan
            <span id="pelanggan-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="pelanggan-submenu" style="display:none;flex-direction:column;">
            <a href="user-hotspot.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> User Hotspot
            </a>
            <a href="user-ppp.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> User PPP
            </a>
            <a href="peta-pelanggan.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Peta Pelanggan
            </a>
        </div>
        <a href="javascript:void(0);" id="voucher-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/cards.png" alt="Kartu Voucher" style="height:22px;width:22px;margin-right:12px;">
            Kartu Voucher
            <span id="voucher-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="voucher-submenu" style="display:none;flex-direction:column;">
            <a href="voucher-hotspot.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Voucher Hotspot
            </a>
            <a href="voucher-ppp.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Voucher PPP
            </a>
            <a href="data-evoucher.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Data e-Voucher
            </a>
        </div>
        <a href="javascript:void(0);" id="tagihan-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/tagihan.png" alt="Data Tagihan" style="height:22px;width:22px;margin-right:12px;">
            Data Tagihan
            <span id="tagihan-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="tagihan-submenu" style="display:none;flex-direction:column;">
            <a href="tagihan-semua.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Semua Tagihan
            </a>
            <a href="tagihan-periode.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Tagihan Periode
            </a>
        </div>
        <a href="javascript:void(0);" id="keuangan-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/data-uang.png" alt="Data Keuangan" style="height:22px;width:22px;margin-right:12px;">
            Data Keuangan
            <span id="keuangan-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="keuangan-submenu" style="display:none;flex-direction:column;">
            <a href="topup-reseller.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Topup Reseller
            </a>
            <a href="income-harian.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Income Harian
            </a>
            <a href="income-periode.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Income Periode
            </a>
            <a href="pengeluaran.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Pengeluaran
            </a>
            <a href="laba-rugi.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Laba Rugi
            </a>
            <a href="hitung-bhp-uso.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Hitung BHP | USO
            </a>
        </div>
        <a href="javascript:void(0);" id="payment-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/bayar-online.png" alt="Online Payment" style="height:22px;width:22px;margin-right:12px;">
            Online Payment
            <span id="payment-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="payment-submenu" style="display:none;flex-direction:column;">
            <a href="midtrans.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> MIDTRANS
            </a>
        </div>
        <a href="javascript:void(0);" id="tiket-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/tiket.png" alt="Tiket Laporan" style="height:22px;width:22px;margin-right:12px;">
            Tiket Laporan
            <span id="tiket-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="tiket-submenu" style="display:none;flex-direction:column;">
            <a href="semua-tiket.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Semua Tiket
            </a>
            <a href="tiket-aktif.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Tiket Aktif
            </a>
            <a href="tiket-ditutup.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Tiket Ditutup
            </a>
        </div>
        <a href="javascript:void(0);" id="tools-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/sistem.png" alt="Tool Sistem" style="height:22px;width:22px;margin-right:12px;">
            Tool Sistem
            <span id="tools-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="tools-submenu" style="display:none;flex-direction:column;">
            <a href="cek-pemakaian.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Cek Pemakaian
            </a>
            <a href="import-user.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Import User
            </a>
            <a href="ekspor-user.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Ekspor User
            </a>
            <a href="ekspor-transaksi.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Ekspor Transaksi
            </a>
            <hr style="border:0;border-top:1px solid #e0e0e0;margin:8px 0;">
            <a href="backup-restore-db.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Backup Restore DB
            </a>
            <a href="reset-laporan.php" style="display:flex;align-items:center;padding:10px 36px;color:#e53935;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Reset Laporan
            </a>
            <a href="reset-database.php" style="display:flex;align-items:center;padding:10px 36px;color:#e53935;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Reset Database
            </a>
        </div>
        <a href="javascript:void(0);" id="log-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/log-apk.png" alt="Log Aplikasi" style="height:22px;width:22px;margin-right:12px;">
            Log Aplikasi
            <span id="log-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="log-submenu" style="display:none;flex-direction:column;">
            <a href="log-login.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Log Login
            </a>
            <a href="log-aktivitas.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Log Aktivitas
            </a>
            <a href="log-bg-process.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Log BG Process
            </a>
            <a href="log-auth-radius.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Log Auth Radius
            </a>
            <hr style="border:0;border-top:1px solid #e0e0e0;margin:8px 0;">
            <a href="log-wa-blast.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Log WA Blast
            </a>
        </div>
        <a href="list-neighbor.php" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;">
            <img src="images/list-neighbor.png" alt="List Neighbor" style="height:22px;width:22px;margin-right:12px;"> List Neighbor
        </a>
    </nav>
    <div style="padding:16px 16px 24px 16px;border-top:1px solid #1565c0;">
        <span style="font-size:1em;">Welcome, <b><?php echo htmlspecialchars($operator); ?></b>.</span><br>
        <span style="font-size:1em;">Location: <b>default.</b></span>
        <a href="logout.php" title="Logout" style="float:right;color:#fff;font-size:1.2em;margin-top:-24px;">
            <i class="fas fa-times"></i>
        </a>
    </div>
</div>

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