<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	// include ("menu-home.php");

    include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/dashboard.css" type="text/css" />

<!-- Sidebar Navigation -->
<div class="sidebar-nav" style="position:fixed;top:48px;left:0;width:220px;height:calc(100vh - 48px);background:#1558b0;color:#fff;display:flex;flex-direction:column;z-index:100;">
    <!-- Top bar for logo, platform name, and future profile/icons -->
    <div id="topbar" style="position:fixed;top:0;left:0;width:100vw;height:48px;background:rgba(21,88,176,0.98);border-bottom:2px solid rgba(255,255,255,0.18);display:flex;align-items:center;gap:12px;padding:0 24px;z-index:101;">
        <img src="images/logo.png" alt="Logo" style="height:32px;">
        <span style="font-size:1.1em;font-weight:bold;color:#fff;">LJN Management</span>
        <!-- Future: profile & icons go here -->
    </div>
    <!-- Remove logo from here -->
    <!-- <div style="padding:24px 16px 12px 16px;display:flex;align-items:center;gap:12px;">
        <img src="images/logo.png" alt="Logo" style="height:40px;">
        <span style="font-size:1.2em;font-weight:bold;">LJN Platform</span>
    </div> -->
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
        <!-- Data Keuangan (expandable) -->
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

        <!-- Online Payment (expandable) -->
        <a href="javascript:void(0);" id="payment-toggle" style="display:flex;align-items:center;padding:12px 24px;color:#fff;text-decoration:none;cursor:pointer;">
            <img src="images/bayar-online.png" alt="Online Payment" style="height:22px;width:22px;margin-right:12px;">
            Online Payment
            <span id="payment-arrow" style="margin-left:auto;">
                <img src="images/arrow_left.png" alt="Show" style="height:16px;width:16px;">
            </span>
        </a>
        <div id="payment-submenu" style="display:none;flex-direction:column;">
            <a href="payment-index.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Payment Dashboard
            </a>
            <a href="checkout.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Checkout System
            </a>
            <a href="admin-bank-accounts.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Bank Accounts
            </a>
            <a href="admin/payment-dashboard.php" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
                <img src="images/circle_line.png" alt="" style="height:18px;width:18px;margin-right:10px;"> Admin Panel
            </a>
        </div>

        <!-- Tiket Laporan (expandable) -->
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

        <!-- Tool Sistem (expandable) -->
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

        <!-- Log Aplikasi (expandable) -->
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

<div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
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
          <span class="badge badge-success" style="background:#43a047;color:#fff;padding:4px 12px;border-radius:8px;margin-left:8px;">August 25, 2025</span>
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
<script>
document.getElementById('pengaturan-toggle').onclick = function() {
    var submenu = document.getElementById('pengaturan-submenu');
    var arrow = document.getElementById('pengaturan-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('sessionuser-toggle').onclick = function() {
    var submenu = document.getElementById('sessionuser-submenu');
    var arrow = document.getElementById('sessionuser-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('odp-toggle').onclick = function() {
    var submenu = document.getElementById('odp-submenu');
    var arrow = document.getElementById('odp-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('profil-toggle').onclick = function() {
    var submenu = document.getElementById('profil-submenu');
    var arrow = document.getElementById('profil-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('pelanggan-toggle').onclick = function() {
    var submenu = document.getElementById('pelanggan-submenu');
    var arrow = document.getElementById('pelanggan-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('voucher-toggle').onclick = function() {
    var submenu = document.getElementById('voucher-submenu');
    var arrow = document.getElementById('voucher-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('tagihan-toggle').onclick = function() {
    var submenu = document.getElementById('tagihan-submenu');
    var arrow = document.getElementById('tagihan-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('keuangan-toggle').onclick = function() {
    var submenu = document.getElementById('keuangan-submenu');
    var arrow = document.getElementById('keuangan-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('payment-toggle').onclick = function() {
    var submenu = document.getElementById('payment-submenu');
    var arrow = document.getElementById('payment-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('tiket-toggle').onclick = function() {
    var submenu = document.getElementById('tiket-submenu');
    var arrow = document.getElementById('tiket-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('tools-toggle').onclick = function() {
    var submenu = document.getElementById('tools-submenu');
    var arrow = document.getElementById('tools-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
document.getElementById('log-toggle').onclick = function() {
    var submenu = document.getElementById('log-submenu');
    var arrow = document.getElementById('log-arrow').querySelector('img');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
    } else {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
    }
};
</script>
</body>
</html>
