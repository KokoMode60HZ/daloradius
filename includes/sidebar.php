<?php
    $operator = isset($_SESSION['operator_user']) ? $_SESSION['operator_user'] : '';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/dashboard.css" type="text/css" />

<!-- Sidebar Navigation (header comes from includes/header.php) -->
<div class="sidebar-nav" style="position:fixed;top:48px;left:0;width:220px;height:calc(100vh - 48px);background:#1558b0;color:#fff;display:flex;flex-direction:column;z-index:100;">

    <nav style="flex:1;overflow-y:auto;overflow-x:hidden;">
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
            <a href="javascript:void(0);" onclick="showSemuaTagihanModal()" style="display:flex;align-items:center;padding:10px 36px;color:#fff;text-decoration:none;background:#1565c0;">
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

// Semua Tagihan Modal Functions (only for index.php)
function showSemuaTagihanModal() {
    // Only show modal if we're on index.php
    if (window.location.pathname.includes('index.php') || window.location.pathname === '/' || window.location.pathname === '/daloradius/') {
        document.getElementById('semuaTagihanModal').style.display = 'block';
    } else {
        // For other pages, navigate directly to tagihan-semua.php
        window.location.href = 'tagihan-semua.php';
    }
}

function hideSemuaTagihanModal() {
    document.getElementById('semuaTagihanModal').style.display = 'none';
}

function submitSemuaTagihanForm() {
    var tipeService = document.getElementById('sidebarTipeService').value;
    var ownerData = document.getElementById('sidebarOwnerData').value;
    
    // Build URL with parameters
    var url = 'tagihan-semua.php';
    var params = [];
    
    if (tipeService) {
        params.push('tipe_service=' + encodeURIComponent(tipeService));
    }
    if (ownerData) {
        params.push('owner_data=' + encodeURIComponent(ownerData));
    }
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    // Navigate to the page
    window.location.href = url;
}

</script>

<!-- Semua Tagihan Modal (only shows on index.php) -->
<div id="semuaTagihanModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; min-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">Semua Tagihan</h3>
            <button onclick="hideSemuaTagihanModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tipe Service</label>
            <select id="sidebarTipeService" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">- Semua Transaksi -</option>
                <option value="PRE">PRE</option>
                <option value="POST">POST</option>
            </select>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Owner Data</label>
            <select id="sidebarOwnerData" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">- Semua Owner -</option>
                <option value="root">root</option>
                <option value="user">user</option>
            </select>
        </div>
        
        <button onclick="submitSemuaTagihanForm()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%;">
            Lihat Laporan
        </button>
    </div>
</div>

<script>
// Close modal when clicking outside
document.addEventListener('click', function(event) {
    var modal = document.getElementById('semuaTagihanModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});
</script>

