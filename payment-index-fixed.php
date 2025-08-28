<?php
/**
 * Payment System Index - daloRADIUS
 * Halaman utama untuk sistem payment gateway simulasi
 */

require_once 'library/DB.php';
require_once 'config/payment_config.php';

// Cek login menggunakan daloRADIUS session
if (!isset($_SESSION['operator_user'])) {
    header('Location: dologin.php');
    exit;
}

$username = $_SESSION['operator_user'] ?? 'administrator';

// Ambil statistik user
$currentBalance = 0;
$pendingTransactions = 0;
$totalTransactions = 0;

try {
    $db = DB::connect("mysql://root:@localhost:3306/radius");
    if ($db && !$db->isError()) {
        // Ambil saldo
        $balanceQuery = "SELECT balance FROM " . TABLE_USER_BALANCE . " WHERE username = '{$db->escapeSimple($username)}'";
        $balanceResult = $db->query($balanceQuery);
        if ($balanceResult && !$db->isError($balanceResult) && $db->numRows($balanceResult) > 0) {
            $balanceRow = $db->fetchRow($balanceResult, DB_FETCHMODE_ASSOC);
            $currentBalance = $balanceRow['balance'];
        }
        
        // Ambil jumlah transaksi pending
        $pendingQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                         WHERE username = '{$db->escapeSimple($username)}' AND status = 'pending'";
        $pendingResult = $db->query($pendingQuery);
        if ($pendingResult && !$db->isError($pendingResult)) {
            $pendingRow = $db->fetchRow($pendingResult, DB_FETCHMODE_ASSOC);
            $pendingTransactions = $pendingRow['total'];
        }
        
        // Ambil total transaksi
        $totalQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                       WHERE username = '{$db->escapeSimple($username)}'";
        $totalResult = $db->query($totalQuery);
        if ($totalResult && !$db->isError($totalResult)) {
            $totalRow = $db->fetchRow($totalResult, DB_FETCHMODE_ASSOC);
            $totalTransactions = $totalRow['total'];
        }
    }
} catch (Exception $e) {
    // Log error untuk debugging
    error_log("Payment Index Error: " . $e->getMessage());
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
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1em;
            color: #666;
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
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .feature-icon {
            font-size: 3em;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .feature-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-desc {
            color: #666;
            line-height: 1.6;
        }
        
        .info-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        
        .info-box h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .info-box p {
            color: #666;
            line-height: 1.6;
        }
        
        .back-button {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💳 Payment System</h1>
            <p>Sistem Payment Gateway Simulasi - daloRADIUS</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($currentBalance); ?></div>
                <div class="stat-label">Saldo Saat Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pendingTransactions; ?></div>
                <div class="stat-label">Transaksi Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalTransactions; ?></div>
                <div class="stat-label">Total Transaksi</div>
            </div>
        </div>
        
        <div class="features-grid">
            <a href="checkout.php" class="feature-card">
                <div class="feature-icon">🛒</div>
                <div class="feature-title">Checkout & Top-up</div>
                <div class="feature-desc">Buat transaksi baru untuk top-up saldo atau pembayaran layanan</div>
            </a>
            
            <a href="payment-test.php" class="feature-card">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Lihat Transaksi</div>
                <div class="feature-desc">Monitor status transaksi dan konfirmasi pembayaran</div>
            </a>
            
            <a href="admin/payment-dashboard.php" class="feature-card">
                <div class="feature-icon">🔧</div>
                <div class="feature-title">Admin Dashboard</div>
                <div class="feature-desc">Panel admin untuk monitoring dan manajemen sistem</div>
            </a>
            
            <a href="test-payment-system.php" class="feature-card">
                <div class="feature-icon">🧪</div>
                <div class="feature-title">Test System</div>
                <div class="feature-desc">Testing lengkap sistem payment gateway</div>
            </a>
            
            <a href="whatsapp-direct-test.php" class="feature-card">
                <div class="feature-icon">📱</div>
                <div class="feature-title">WhatsApp Integration</div>
                <div class="feature-desc">Test integrasi WhatsApp untuk notifikasi</div>
            </a>
            
            <a href="admin-bank-accounts.php" class="feature-card">
                <div class="feature-icon">🏦</div>
                <div class="feature-title">Bank Accounts</div>
                <div class="feature-desc">Kelola rekening bank untuk payment</div>
            </a>
        </div>
        
        <div class="info-box">
            <h3>ℹ️ Informasi Sistem</h3>
            <p>
                <strong>Mode:</strong> <?php echo PAYMENT_MODE; ?> | 
                <strong>Status:</strong> ✅ Active | 
                <strong>User:</strong> <?php echo htmlspecialchars($username); ?>
            </p>
            <p>
                Sistem ini adalah simulasi payment gateway untuk pembelajaran. 
                Tidak ada uang real yang berpindah. Semua transaksi bersifat demo.
            </p>
        </div>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-button">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
