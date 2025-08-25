<?php
/**
 * Payment System Index - daloRADIUS
 * Halaman utama untuk sistem payment gateway simulasi
 */

require_once 'library/DB.php';
require_once 'config/payment_config.php';

session_start();

// Cek login
if (!isset($_SESSION['daloradius_logged_in']) || $_SESSION['daloradius_logged_in'] !== true) {
    header('Location: dologin.php');
    exit;
}

$username = $_SESSION['daloradius_username'] ?? 'administrator';

// Ambil statistik user
$currentBalance = 0;
$pendingTransactions = 0;
$totalTransactions = 0;

try {
    $db = DB::connect("mysql://root:@localhost:3306/radius");
    if (!$db->isError()) {
        // Ambil saldo
        $balanceQuery = "SELECT balance FROM " . TABLE_USER_BALANCE . " WHERE username = '{$db->escapeSimple($username)}'";
        $balanceResult = $db->query($balanceQuery);
        if (!$db->isError($balanceResult) && $db->numRows($balanceResult) > 0) {
            $balanceRow = $db->fetchRow($balanceResult, DB_FETCHMODE_ASSOC);
            $currentBalance = $balanceRow['balance'];
        }
        
        // Ambil jumlah transaksi pending
        $pendingQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                         WHERE username = '{$db->escapeSimple($username)}' AND status = 'pending'";
        $pendingResult = $db->query($pendingQuery);
        if (!$db->isError($pendingResult)) {
            $pendingRow = $db->fetchRow($pendingResult, DB_FETCHMODE_ASSOC);
            $pendingTransactions = $pendingRow['total'];
        }
        
        // Ambil total transaksi
        $totalQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                       WHERE username = '{$db->escapeSimple($username)}'";
        $totalResult = $db->query($totalQuery);
        if (!$db->isError($totalResult)) {
            $totalRow = $db->fetchRow($totalResult, DB_FETCHMODE_ASSOC);
            $totalTransactions = $totalRow['total'];
        }
    }
} catch (Exception $e) {
    // Ignore errors for now
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment System - daloRADIUS</title>
    <link rel="stylesheet" href="css/1.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1em;
            font-weight: 500;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            border-left: 5px solid #667eea;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .feature-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.4em;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .feature-card .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .feature-card .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        .info-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
        }
        
        .info-section h3 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .info-section ul {
            color: #666;
            line-height: 1.8;
        }
        
        .info-section li {
            margin-bottom: 10px;
        }
        
        .navigation {
            text-align: center;
            margin-top: 40px;
        }
        
        .nav-btn {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .simulation-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
        }
        
        .simulation-notice h4 {
            color: #856404;
            margin-bottom: 15px;
        }
        
        .simulation-notice p {
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Payment System</h1>
            <p>Sistem Payment Gateway Simulasi untuk daloRADIUS</p>
            <p>Selamat datang, <strong><?= htmlspecialchars($username) ?></strong>!</p>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= formatRupiah($currentBalance) ?></div>
                <div class="stat-label">Saldo Saat Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $pendingTransactions ?></div>
                <div class="stat-label">Transaksi Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalTransactions ?></div>
                <div class="stat-label">Total Transaksi</div>
            </div>
        </div>
        
        <!-- Features -->
        <div class="features-grid">
            <div class="feature-card">
                <h3>💳 Checkout & Top-up</h3>
                <p>Buat transaksi baru untuk top-up saldo. Pilih metode pembayaran dan jumlah yang diinginkan.</p>
                <a href="checkout.php" class="btn">🚀 Mulai Checkout</a>
            </div>
            
            <div class="feature-card">
                <h3>📊 Monitor Transaksi</h3>
                <p>Lihat status transaksi, konfirmasi pembayaran, dan update saldo secara real-time.</p>
                <a href="payment-test.php" class="btn">📊 Lihat Transaksi</a>
            </div>
            
            <div class="feature-card">
                <h3>🔧 Admin Dashboard</h3>
                <p>Kelola semua transaksi, update status, dan monitor statistik sistem payment.</p>
                <a href="admin/payment-dashboard.php" class="btn">🔧 Dashboard Admin</a>
            </div>
            
            <div class="feature-card">
                <h3>🧪 Testing System</h3>
                <p>Testing lengkap sistem payment, webhook, dan update balance untuk development.</p>
                <a href="test-payment-system.php" class="btn">🧪 Test System</a>
            </div>
            
            <div class="feature-card">
                <h3>📱 WhatsApp Integration</h3>
                <p>Kirim notifikasi langsung ke WhatsApp untuk payment success, reminder, dan invoice.</p>
                <a href="whatsapp-direct-test.php" class="btn">📱 Test WhatsApp</a>
            </div>
            
            <div class="feature-card">
                <h3>🏦 Kelola Rekening</h3>
                <p>Tambah, edit, dan hapus rekening bank untuk metode transfer bank.</p>
                <a href="admin-bank-accounts.php" class="btn">🏦 Kelola Bank</a>
            </div>
        </div>
        
        <!-- Simulation Notice -->
        <div class="simulation-notice">
            <h4>ℹ️ Informasi Penting - Sistem Simulasi</h4>
            <p><strong>Ini adalah sistem payment gateway simulasi buatan sendiri untuk testing dan pembelajaran.</strong></p>
            <p>✅ <strong>Yang Bisa:</strong> Test alur pembayaran, update saldo, notifikasi WhatsApp</p>
            <p>❌ <strong>Yang Tidak Bisa:</strong> Transaksi uang real, integrasi dengan bank/ewallet</p>
            <p>💡 <strong>Untuk Real Payment:</strong> Gunakan Midtrans, Xendit, atau daftar merchant resmi</p>
        </div>
        
        <!-- How It Works -->
        <div class="info-section">
            <h3>🔄 Cara Kerja Sistem</h3>
            <ol>
                <li><strong>User Checkout:</strong> Pilih jumlah dan metode pembayaran di halaman checkout</li>
                <li><strong>Transaksi Pending:</strong> Sistem membuat transaksi dengan status "pending"</li>
                <li><strong>Konfirmasi Admin:</strong> Admin atau user klik "Tandai Lunas" untuk mengubah status</li>
                <li><strong>Update Saldo:</strong> Setelah status "success", saldo user otomatis bertambah</li>
                <li><strong>Notifikasi:</strong> Sistem dapat mengirim notifikasi WhatsApp (opsional)</li>
            </ol>
        </div>
        
        <!-- Payment Methods -->
        <div class="info-section">
            <h3>💳 Metode Pembayaran yang Didukung</h3>
            <ul>
                <?php foreach (PAYMENT_METHODS as $key => $name): ?>
                    <li><strong><?= $name ?>:</strong> Simulasi pembayaran via <?= $name ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Navigation -->
        <div class="navigation">
            <a href="index.php" class="nav-btn">🏠 Dashboard Utama</a>
            <a href="checkout.php" class="nav-btn">💳 Buat Transaksi</a>
            <a href="payment-test.php" class="nav-btn">📊 Lihat Transaksi</a>
            <a href="admin/payment-dashboard.php" class="nav-btn">🔧 Admin Panel</a>
            <a href="whatsapp-direct-test.php" class="nav-btn">📱 Test WhatsApp</a>
        </div>
    </div>
</body>
</html>
