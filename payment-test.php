<?php
/**
 * Halaman Test Payment Gateway Simulasi
 * Sistem buatan sendiri untuk testing tanpa pihak ketiga
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
$error = '';
$success = '';

// Handle manual payment confirmation
if ($_POST && isset($_POST['confirm_payment'])) {
    try {
        $transactionId = $_POST['transaction_id'];
        $newStatus = $_POST['new_status'];
        
        if (!array_key_exists($newStatus, PAYMENT_STATUS)) {
            throw new Exception("Status tidak valid");
        }
        
        // Update status transaksi
        $db = DB::connect("mysql://root:@localhost:3306/radius");
        if ($db->isError()) {
            throw new Exception("Database connection failed: " . $db->getMessage());
        }
        
        $updateQuery = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                        SET status = '{$db->escapeSimple($newStatus)}', 
                            updated_at = NOW() 
                        WHERE transaction_id = '{$db->escapeSimple($transactionId)}'";
        
        $result = $db->query($updateQuery);
        if ($db->isError($result)) {
            throw new Exception("Gagal update status: " . $db->getMessage($result));
        }
        
        // Jika status success, update saldo user
        if ($newStatus === 'success') {
            // Ambil data transaksi
            $getQuery = "SELECT username, amount FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                         WHERE transaction_id = '{$db->escapeSimple($transactionId)}'";
            
            $getResult = $db->query($getQuery);
            if (!$db->isError($getResult) && $db->numRows($getResult) > 0) {
                $transaction = $db->fetchRow($getResult, DB_FETCHMODE_ASSOC);
                $username = $transaction['username'];
                $amount = $transaction['amount'];
                
                // Cek apakah user sudah ada di tabel balance
                $checkQuery = "SELECT id FROM " . TABLE_USER_BALANCE . " WHERE username = '{$db->escapeSimple($username)}'";
                $checkResult = $db->query($checkQuery);
                
                if ($db->numRows($checkResult) > 0) {
                    // Update balance yang sudah ada
                    $balanceQuery = "UPDATE " . TABLE_USER_BALANCE . " 
                                    SET balance = balance + {$amount}, 
                                        last_updated = NOW() 
                                    WHERE username = '{$db->escapeSimple($username)}'";
                } else {
                    // Insert balance baru
                    $balanceQuery = "INSERT INTO " . TABLE_USER_BALANCE . " 
                                    (username, balance, last_updated) 
                                    VALUES ('{$db->escapeSimple($username)}', {$amount}, NOW())";
                }
                
                $balanceResult = $db->query($balanceQuery);
                if (!$db->isError($balanceResult)) {
                    logPayment("Saldo user {$username} bertambah Rp {$amount} setelah transaksi {$transactionId}");
                }
            }
        }
        
        $success = "Status transaksi {$transactionId} berhasil diupdate ke {$newStatus}";
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil data saldo dan transaksi
$currentBalance = 0;
$pendingTransactions = [];
$allTransactions = [];

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
        
        // Ambil transaksi pending
        $pendingQuery = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                         WHERE username = '{$db->escapeSimple($username)}' AND status = 'pending' 
                         ORDER BY created_at DESC";
        $pendingResult = $db->query($pendingQuery);
        if (!$db->isError($pendingResult)) {
            while ($row = $db->fetchRow($pendingResult, DB_FETCHMODE_ASSOC)) {
                $pendingTransactions[] = $row;
            }
        }
        
        // Ambil semua transaksi
        $allQuery = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                     WHERE username = '{$db->escapeSimple($username)}' 
                     ORDER BY created_at DESC LIMIT 10";
        $allResult = $db->query($allQuery);
        if (!$db->isError($allResult)) {
            while ($row = $db->fetchRow($allResult, DB_FETCHMODE_ASSOC)) {
                $allTransactions[] = $row;
            }
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Payment System - Simulasi</title>
    <link rel="stylesheet" href="css/1.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .balance-amount {
            font-size: 3em;
            font-weight: bold;
            margin: 10px 0;
        }
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .section h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .transaction-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .transaction-id {
            font-weight: bold;
            color: #007bff;
        }
        .transaction-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-success { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-cancelled { background: #e2e3e5; color: #383d41; }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
        }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn:hover { opacity: 0.8; }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .payment-method-badge {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .amount {
            font-weight: bold;
            color: #28a745;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3em;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Payment Gateway Simulasi</h1>
        
        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <!-- Balance Card -->
        <div class="balance-card">
            <h2>💰 Saldo Anda Saat Ini</h2>
            <div class="balance-amount"><?= formatRupiah($currentBalance) ?></div>
            <p>Saldo ini akan bertambah setelah pembayaran berhasil dikonfirmasi</p>
        </div>
        
        <!-- Pending Transactions -->
        <div class="section">
            <h3>⏳ Transaksi Pending</h3>
            <?php if (empty($pendingTransactions)): ?>
                <div class="empty-state">
                    <div>📭</div>
                    <h4>Tidak ada transaksi pending</h4>
                    <p>Buat transaksi baru di halaman checkout</p>
                    <a href="checkout.php" class="btn btn-success">💳 Buat Transaksi</a>
                </div>
            <?php else: ?>
                <?php foreach ($pendingTransactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <span class="transaction-id"><?= htmlspecialchars($transaction['transaction_id']) ?></span>
                            <span class="transaction-status status-pending"><?= getStatusName($transaction['status']) ?></span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Jumlah:</strong> <span class="amount"><?= formatRupiah($transaction['amount']) ?></span><br>
                            <strong>Metode:</strong> <span class="payment-method-badge"><?= getPaymentMethodName($transaction['payment_method']) ?></span><br>
                            <strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?><br>
                            <?php if ($transaction['description']): ?>
                                <strong>Keterangan:</strong> <?= htmlspecialchars($transaction['description']) ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                            <input type="hidden" name="confirm_payment" value="1">
                            
                            <button type="submit" name="new_status" value="success" class="btn btn-success">
                                ✅ Tandai Lunas
                            </button>
                            <button type="submit" name="new_status" value="failed" class="btn btn-danger">
                                ❌ Tandai Gagal
                            </button>
                            <button type="submit" name="new_status" value="cancelled" class="btn btn-warning">
                                🚫 Batalkan
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- All Transactions -->
        <div class="section">
            <h3>📊 Riwayat Transaksi</h3>
            <?php if (empty($allTransactions)): ?>
                <div class="empty-state">
                    <div>📭</div>
                    <h4>Belum ada transaksi</h4>
                    <p>Mulai dengan membuat transaksi pertama</p>
                </div>
            <?php else: ?>
                <?php foreach ($allTransactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <span class="transaction-id"><?= htmlspecialchars($transaction['transaction_id']) ?></span>
                            <span class="transaction-status status-<?= $transaction['status'] ?>">
                                <?= getStatusName($transaction['status']) ?>
                            </span>
                        </div>
                        <div>
                            <strong>Jumlah:</strong> <span class="amount"><?= formatRupiah($transaction['amount']) ?></span><br>
                            <strong>Metode:</strong> <span class="payment-method-badge"><?= getPaymentMethodName($transaction['payment_method']) ?></span><br>
                            <strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?><br>
                            <strong>Update:</strong> <?= date('d/m/Y H:i', strtotime($transaction['updated_at'])) ?><br>
                            <?php if ($transaction['description']): ?>
                                <strong>Keterangan:</strong> <?= htmlspecialchars($transaction['description']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="checkout.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                💳 Buat Transaksi Baru
            </a>
            <a href="whatsapp-direct-test.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                📱 Test WhatsApp
            </a>
            <a href="admin/payment-dashboard.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                📊 Dashboard Admin
            </a>
            <a href="index.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                🏠 Kembali ke Dashboard
            </a>
        </div>
        
        <!-- Info Simulasi -->
        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 10px; border: 1px solid #ffeaa7;">
            <h4>ℹ️ Cara Kerja Simulasi</h4>
            <ol>
                <li><strong>Buat Transaksi:</strong> Pilih jumlah dan metode pembayaran di checkout</li>
                <li><strong>Status Pending:</strong> Transaksi dibuat dengan status "pending"</li>
                <li><strong>Konfirmasi:</strong> Klik "Tandai Lunas" untuk mengubah status menjadi "success"</li>
                <li><strong>Saldo Bertambah:</strong> Setelah status "success", saldo user otomatis bertambah</li>
            </ol>
            <p><strong>Note:</strong> Ini adalah simulasi, tidak ada uang real yang berpindah. Untuk real payment, gunakan Midtrans/Xendit atau daftar merchant resmi.</p>
        </div>
    </div>
</body>
</html> 