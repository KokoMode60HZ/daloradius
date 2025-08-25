<?php
/**
 * Halaman Checkout Simulasi Payment Gateway
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

// Handle form submission
if ($_POST) {
    try {
        $amount = floatval($_POST['amount']);
        $paymentMethod = $_POST['payment_method'];
        $description = $_POST['description'] ?? '';
        
        if (!validateAmount($amount)) {
            throw new Exception("Jumlah pembayaran tidak valid");
        }
        
        if (!array_key_exists($paymentMethod, PAYMENT_METHODS)) {
            throw new Exception("Metode pembayaran tidak valid");
        }
        
        // Buat transaksi
        $db = DB::connect("mysql://root:@localhost:3306/radius");
        if ($db->isError()) {
            throw new Exception("Database connection failed: " . $db->getMessage());
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
        
        logPayment("Transaksi baru dibuat: {$transactionId} - {$username} - Rp {$amount} - {$paymentMethod}");
        
        $success = "Transaksi berhasil dibuat! ID: {$transactionId}";
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil saldo user
$currentBalance = 0;
try {
    $db = DB::connect("mysql://root:@localhost:3306/radius");
    if (!$db->isError()) {
        $balanceQuery = "SELECT balance FROM " . TABLE_USER_BALANCE . " WHERE username = '{$db->escapeSimple($username)}'";
        $balanceResult = $db->query($balanceQuery);
        if (!$db->isError($balanceResult) && $db->numRows($balanceResult) > 0) {
            $balanceRow = $db->fetchRow($balanceResult, DB_FETCHMODE_ASSOC);
            $currentBalance = $balanceRow['balance'];
        }
    }
} catch (Exception $e) {
    // Ignore balance error
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Payment Gateway Simulasi</title>
    <link rel="stylesheet" href="css/1.css">
    <style>
        .checkout-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .balance-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
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
        .btn-primary {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        .payment-method {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        .payment-method.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }
        .payment-method input[type="radio"] {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>💳 Checkout Payment Gateway Simulasi</h1>
        
        <!-- Info Saldo -->
        <div class="balance-info">
            <h3>💰 Saldo Anda Saat Ini</h3>
            <h2><?= formatRupiah($currentBalance) ?></h2>
        </div>
        
        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <!-- Form Checkout -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="amount">💰 Jumlah Top-up (Rupiah)</label>
                <input type="number" id="amount" name="amount" min="1000" max="1000000" step="1000" required 
                       placeholder="Contoh: 10000" value="<?= $_POST['amount'] ?? '' ?>">
                <small>Minimal Rp 1.000, maksimal Rp 1.000.000</small>
            </div>
            
            <div class="form-group">
                <label>💳 Pilih Metode Pembayaran</label>
                <div class="payment-methods">
                    <?php foreach (PAYMENT_METHODS as $key => $name): ?>
                        <div class="payment-method">
                            <input type="radio" id="<?= $key ?>" name="payment_method" value="<?= $key ?>" 
                                   <?= ($_POST['payment_method'] ?? '') === $key ? 'checked' : '' ?> required>
                            <label for="<?= $key ?>"><?= $name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">📝 Keterangan (Opsional)</label>
                <textarea id="description" name="description" rows="3" 
                          placeholder="Contoh: Top-up untuk internet bulanan"><?= $_POST['description'] ?? '' ?></textarea>
            </div>
            
            <button type="submit" class="btn-primary">🚀 Buat Transaksi</button>
        </form>
        
        <!-- Info Simulasi -->
        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 5px; border: 1px solid #ffeaa7;">
            <h4>ℹ️ Informasi Simulasi</h4>
            <p>Ini adalah sistem <strong>simulasi pembayaran</strong> buatan sendiri untuk testing.</p>
            <ul>
                <li>💰 Tidak ada uang real yang berpindah</li>
                <li>🔒 Transaksi dibuat dengan status "pending"</li>
                <li>✅ Admin dapat mengubah status menjadi "success"</li>
                <li>💳 Saldo akan bertambah setelah status "success"</li>
            </ul>
            <p><strong>Untuk testing real payment:</strong> Gunakan Midtrans, Xendit, atau daftar merchant resmi.</p>
        </div>
        
        <!-- Navigation -->
        <div style="margin-top: 20px; text-align: center;">
            <a href="payment-test.php" class="btn-primary" style="text-decoration: none; display: inline-block; margin: 5px;">
                📊 Lihat Transaksi
            </a>
            <a href="whatsapp-direct-test.php" class="btn-primary" style="text-decoration: none; display: inline-block; margin: 5px;">
                📱 Test WhatsApp
            </a>
            <a href="index.php" class="btn-primary" style="text-decoration: none; display: inline-block; margin: 5px;">
                🏠 Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <script>
        // Auto-select payment method on click
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                // Add selected class to clicked method
                this.classList.add('selected');
                // Check the radio button
                this.querySelector('input[type="radio"]').checked = true;
            });
        });
        
        // Auto-select method if radio is checked
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            if (radio.checked) {
                radio.closest('.payment-method').classList.add('selected');
            }
        });
    </script>
</body>
</html>
