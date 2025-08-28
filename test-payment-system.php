<?php
/**
 * Test Payment System - Simulasi Payment Gateway
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

// Handle test actions
if ($_POST && isset($_POST['test_action'])) {
    try {
        $db = DB::connect("mysql://root:@localhost:3306/radius");
        if ($db->isError()) {
            throw new Exception("Database connection failed: " . $db->getMessage());
        }
        
        switch ($_POST['test_action']) {
            case 'create_test_transaction':
                $amount = floatval($_POST['amount']);
                $paymentMethod = $_POST['payment_method'];
                $description = $_POST['description'] ?? 'Test transaction';
                
                if (!validateAmount($amount)) {
                    throw new Exception("Jumlah pembayaran tidak valid");
                }
                
                if (!array_key_exists($paymentMethod, PAYMENT_METHODS)) {
                    throw new Exception("Metode pembayaran tidak valid");
                }
                
                $transactionId = generateTransactionId();
                $currentTime = date('Y-m-d H:i:s');
                
                // Insert ke tabel transaksi
                $query = "INSERT INTO " . TABLE_PAYMENT_TRANSACTIONS . " 
                          (transaction_id, username, amount, payment_method, description, status, created_at, updated_at) 
                          VALUES ('{$db->escapeSimple($transactionId)}', 
                                  '{$db->escapeSimple($username)}', 
                                  {$db->escapeSimple($amount)}, 
                                  '{$db->escapeSimple($paymentMethod)}', 
                                  '{$db->escapeSimple($description)}', 
                                  'pending', 
                                  '{$currentTime}', 
                                  '{$currentTime}')";
                
                $result = $db->query($query);
                if ($db->isError($result)) {
                    throw new Exception("Gagal membuat transaksi: " . $db->getMessage($result));
                }
                
                logPayment("Test transaksi dibuat: {$transactionId} - {$username} - Rp {$amount} - {$paymentMethod}");
                $success = "✅ Test transaksi berhasil dibuat! ID: {$transactionId}";
                break;
                
            case 'test_webhook':
                $transactionId = $_POST['transaction_id'];
                $status = $_POST['webhook_status'];
                
                if (!array_key_exists($status, PAYMENT_STATUS)) {
                    throw new Exception("Status tidak valid");
                }
                
                // Simulasi webhook callback
                $webhookData = [
                    'transaction_id' => $transactionId,
                    'status' => $status,
                    'secret' => WEBHOOK_SECRET
                ];
                
                // Kirim POST request ke webhook
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, WEBHOOK_URL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 200) {
                    $success = "✅ Webhook test berhasil! Status: {$status}";
                    logPayment("Webhook test berhasil: {$transactionId} -> {$status}");
                } else {
                    throw new Exception("Webhook test gagal. HTTP Code: {$httpCode}, Response: {$response}");
                }
                break;
                
            case 'test_balance_update':
                $transactionId = $_POST['transaction_id'];
                
                // Update status transaksi ke success
                $updateQuery = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                               SET status = 'success', updated_at = NOW() 
                               WHERE transaction_id = '{$db->escapeSimple($transactionId)}'";
                
                $result = $db->query($updateQuery);
                if ($db->isError($result)) {
                    throw new Exception("Gagal update status: " . $db->getMessage($result));
                }
                
                // Update saldo user
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
                        logPayment("Test balance update: {$username} + Rp {$amount}");
                        $success = "✅ Test balance update berhasil! User {$username} + Rp {$amount}";
                    }
                }
                break;
        }
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil data untuk testing
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
        .test-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .test-section h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        .btn:hover { opacity: 0.8; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .test-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
        }
        .test-card h4 {
            color: #495057;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Payment System - Simulasi</h1>
        <p>Halaman testing lengkap untuk sistem payment gateway simulasi</p>
        
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
        
        <!-- Test Sections -->
        <div class="test-grid">
            <!-- Test Create Transaction -->
            <div class="test-card">
                <h4>💳 Test Buat Transaksi</h4>
                <form method="POST">
                    <input type="hidden" name="test_action" value="create_test_transaction">
                    
                    <div class="form-group">
                        <label>Jumlah (Rp):</label>
                        <input type="number" name="amount" value="10000" min="1000" max="1000000" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Metode Pembayaran:</label>
                        <select name="payment_method" required>
                            <?php foreach (PAYMENT_METHODS as $key => $name): ?>
                                <option value="<?= $key ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Keterangan:</label>
                        <textarea name="description" rows="2" placeholder="Test transaction description">Test transaction untuk testing sistem</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">🚀 Buat Test Transaksi</button>
                </form>
            </div>
            
            <!-- Test Webhook -->
            <div class="test-card">
                <h4>🔗 Test Webhook Callback</h4>
                <?php if (!empty($pendingTransactions)): ?>
                    <form method="POST">
                        <input type="hidden" name="test_action" value="test_webhook">
                        
                        <div class="form-group">
                            <label>Pilih Transaksi:</label>
                            <select name="transaction_id" required>
                                <?php foreach ($pendingTransactions as $transaction): ?>
                                    <option value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                                        <?= htmlspecialchars($transaction['transaction_id']) ?> - 
                                        Rp <?= number_format($transaction['amount']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Status Baru:</label>
                            <select name="webhook_status" required>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">🔗 Test Webhook</button>
                    </form>
                <?php else: ?>
                    <p style="color: #666;">Tidak ada transaksi pending untuk testing webhook</p>
                    <p><small>Buat transaksi terlebih dahulu di section sebelah</small></p>
                <?php endif; ?>
            </div>
            
            <!-- Test Balance Update -->
            <div class="test-card">
                <h4>💰 Test Update Balance</h4>
                <?php if (!empty($pendingTransactions)): ?>
                    <form method="POST">
                        <input type="hidden" name="test_action" value="test_balance_update">
                        
                        <div class="form-group">
                            <label>Pilih Transaksi:</label>
                            <select name="transaction_id" required>
                                <?php foreach ($pendingTransactions as $transaction): ?>
                                    <option value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                                        <?= htmlspecialchars($transaction['transaction_id']) ?> - 
                                        Rp <?= number_format($transaction['amount']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success">💰 Test Update Balance</button>
                    </form>
                <?php else: ?>
                    <p style="color: #666;">Tidak ada transaksi pending untuk testing balance</p>
                    <p><small>Buat transaksi terlebih dahulu di section sebelah</small></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Pending Transactions -->
        <div class="test-section">
            <h3>⏳ Transaksi Pending untuk Testing</h3>
            <?php if (empty($pendingTransactions)): ?>
                <p style="text-align: center; color: #666; padding: 40px;">Tidak ada transaksi pending</p>
            <?php else: ?>
                <?php foreach ($pendingTransactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <span class="transaction-id"><?= htmlspecialchars($transaction['transaction_id']) ?></span>
                            <span class="transaction-status status-pending"><?= getStatusName($transaction['status']) ?></span>
                        </div>
                        <div>
                            <strong>Jumlah:</strong> <span class="amount"><?= formatRupiah($transaction['amount']) ?></span><br>
                            <strong>Metode:</strong> <span class="payment-method-badge"><?= getPaymentMethodName($transaction['payment_method']) ?></span><br>
                            <strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?><br>
                            <?php if ($transaction['description']): ?>
                                <strong>Keterangan:</strong> <?= htmlspecialchars($transaction['description']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- All Transactions -->
        <div class="test-section">
            <h3>📊 Riwayat Semua Transaksi</h3>
            <?php if (empty($allTransactions)): ?>
                <p style="text-align: center; color: #666; padding: 40px;">Belum ada transaksi</p>
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
                            <strong>Tanggal Buat:</strong> <?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?><br>
                            <strong>Tanggal Update:</strong> <?= date('d/m/Y H:i', strtotime($transaction['updated_at'])) ?><br>
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
            <a href="payment-test.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                🧪 Test Payment
            </a>
            <a href="admin/payment-dashboard.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                📊 Dashboard Admin
            </a>
            <a href="whatsapp-direct-test.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                📱 Test WhatsApp
            </a>
            <a href="index.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                🏠 Kembali ke Dashboard
            </a>
        </div>
        
        <!-- Info Testing -->
        <div style="margin-top: 30px; padding: 20px; background: #e3f2fd; border-radius: 10px; border: 1px solid #bbdefb;">
            <h4>🧪 Panduan Testing</h4>
            <ol>
                <li><strong>Buat Test Transaksi:</strong> Buat transaksi dengan jumlah dan metode pembayaran yang diinginkan</li>
                <li><strong>Test Webhook:</strong> Simulasi callback dari payment gateway ke sistem</li>
                <li><strong>Test Balance Update:</strong> Langsung update status transaksi dan saldo user</li>
                <li><strong>Monitor Hasil:</strong> Lihat perubahan status dan saldo di halaman ini</li>
            </ol>
            <p><strong>Note:</strong> Semua testing ini menggunakan sistem simulasi. Tidak ada uang real yang berpindah.</p>
        </div>
    </div>
</body>
</html> 