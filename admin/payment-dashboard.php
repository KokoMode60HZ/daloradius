<?php
/**
 * Admin Dashboard untuk Payment Gateway Simulasi
 * Sistem buatan sendiri untuk testing tanpa pihak ketiga
 */

require_once '../library/DB.php';
require_once '../config/payment_config.php';

session_start();

// Cek login admin
if (!isset($_SESSION['daloradius_logged_in']) || $_SESSION['daloradius_logged_in'] !== true) {
    header('Location: ../dologin.php');
    exit;
}

$username = $_SESSION['daloradius_username'] ?? 'administrator';
$error = '';
$success = '';

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
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

// Ambil data statistik dan transaksi
$stats = [];
$recentTransactions = [];
$allTransactions = [];

try {
    $db = DB::connect("mysql://root:@localhost:3306/radius");
    if (!$db->isError()) {
        // Statistik
        $totalQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS;
        $totalResult = $db->query($totalQuery);
        $stats['total_transactions'] = $db->getOne($totalResult);
        
        $incomeQuery = "SELECT SUM(amount) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " WHERE status = 'success'";
        $incomeResult = $db->query($incomeQuery);
        $stats['total_income'] = $db->getOne($incomeResult) ?: 0;
        
        $pendingQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " WHERE status = 'pending'";
        $pendingResult = $db->query($pendingQuery);
        $stats['pending_transactions'] = $db->getOne($pendingResult);
        
        $successQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS . " WHERE status = 'success'";
        $successResult = $db->query($successQuery);
        $stats['success_transactions'] = $db->getOne($successResult);
        
        // Transaksi terbaru
        $recentQuery = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                        ORDER BY created_at DESC LIMIT 10";
        $recentResult = $db->query($recentQuery);
        if (!$db->isError($recentResult)) {
            while ($row = $db->fetchRow($recentResult, DB_FETCHMODE_ASSOC)) {
                $recentTransactions[] = $row;
            }
        }
        
        // Semua transaksi
        $allQuery = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                     ORDER BY created_at DESC LIMIT 50";
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
    <title>Admin Dashboard - Payment Gateway Simulasi</title>
    <link rel="stylesheet" href="../css/1.css">
    <style>
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 5px solid #007bff;
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 1.1em;
        }
        .section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .section h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .transaction-table th,
        .transaction-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .transaction-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .transaction-table tr:hover {
            background: #f8f9fa;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-success { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-cancelled { background: #e2e3e5; color: #383d41; }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
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
        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        .search-filter input,
        .search-filter select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .search-filter button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-filter button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Dashboard - Payment Gateway Simulasi</h1>
        <p>Selamat datang, <strong><?= htmlspecialchars($username) ?></strong>! Kelola semua transaksi pembayaran dari sini.</p>
        
        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-number"><?= number_format($stats['total_transactions'] ?? 0) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-number"><?= formatRupiah($stats['total_income'] ?? 0) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Transaksi Pending</div>
                <div class="stat-number"><?= number_format($stats['pending_transactions'] ?? 0) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Transaksi Berhasil</div>
                <div class="stat-number"><?= number_format($stats['success_transactions'] ?? 0) ?></div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="section">
            <h3>⏰ Transaksi Terbaru</h3>
            <?php if (empty($recentTransactions)): ?>
                <p style="text-align: center; color: #666; padding: 40px;">Belum ada transaksi</p>
            <?php else: ?>
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Username</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($transaction['transaction_id']) ?></strong></td>
                                <td><?= htmlspecialchars($transaction['username']) ?></td>
                                <td class="amount"><?= formatRupiah($transaction['amount']) ?></td>
                                <td><span class="payment-method-badge"><?= getPaymentMethodName($transaction['payment_method']) ?></span></td>
                                <td>
                                    <span class="status-badge status-<?= $transaction['status'] ?>">
                                        <?= getStatusName($transaction['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                <td>
                                    <?php if ($transaction['status'] === 'pending'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                                            <input type="hidden" name="update_status" value="1">
                                            
                                            <button type="submit" name="new_status" value="success" class="btn btn-success">
                                                ✅ Lunas
                                            </button>
                                            <button type="submit" name="new_status" value="failed" class="btn btn-danger">
                                                ❌ Gagal
                                            </button>
                                            <button type="submit" name="new_status" value="cancelled" class="btn btn-warning">
                                                🚫 Batal
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- All Transactions -->
        <div class="section">
            <h3>📊 Semua Transaksi</h3>
            
            <!-- Search & Filter -->
            <div class="search-filter">
                <input type="text" id="searchInput" placeholder="Cari ID transaksi atau username..." style="flex: 1;">
                <select id="statusFilter">
                    <option value="">Semua Status</option>
                    <?php foreach (PAYMENT_STATUS as $key => $name): ?>
                        <option value="<?= $key ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="filterTransactions()">🔍 Filter</button>
            </div>
            
            <?php if (empty($allTransactions)): ?>
                <p style="text-align: center; color: #666; padding: 40px;">Belum ada transaksi</p>
            <?php else: ?>
                <table class="transaction-table" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Username</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal Buat</th>
                            <th>Tanggal Update</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTransactions as $transaction): ?>
                            <tr data-status="<?= $transaction['status'] ?>" 
                                data-search="<?= strtolower($transaction['transaction_id'] . ' ' . $transaction['username']) ?>">
                                <td><strong><?= htmlspecialchars($transaction['transaction_id']) ?></strong></td>
                                <td><?= htmlspecialchars($transaction['username']) ?></td>
                                <td class="amount"><?= formatRupiah($transaction['amount']) ?></td>
                                <td><span class="payment-method-badge"><?= getPaymentMethodName($transaction['payment_method']) ?></span></td>
                                <td>
                                    <span class="status-badge status-<?= $transaction['status'] ?>">
                                        <?= getStatusName($transaction['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($transaction['updated_at'])) ?></td>
                                <td><?= htmlspecialchars($transaction['description'] ?: '-') ?></td>
                                <td>
                                    <?php if ($transaction['status'] === 'pending'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                                            <input type="hidden" name="update_status" value="1">
                                            
                                            <button type="submit" name="new_status" value="success" class="btn btn-success">
                                                ✅ Lunas
                                            </button>
                                            <button type="submit" name="new_status" value="failed" class="btn btn-danger">
                                                ❌ Gagal
                                            </button>
                                            <button type="submit" name="new_status" value="cancelled" class="btn btn-warning">
                                                🚫 Batal
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="../checkout.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                💳 Buat Transaksi Baru
            </a>
            <a href="../payment-test.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                🧪 Test Payment
            </a>
            <a href="../whatsapp-direct-test.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                📱 Test WhatsApp
            </a>
            <a href="../index.php" class="btn btn-success" style="text-decoration: none; display: inline-block; margin: 5px;">
                🏠 Kembali ke Dashboard
            </a>
        </div>
        
        <!-- Info Simulasi -->
        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 10px; border: 1px solid #ffeaa7;">
            <h4>ℹ️ Cara Kerja Simulasi</h4>
            <ol>
                <li><strong>User buat transaksi:</strong> Di halaman checkout, user pilih jumlah dan metode pembayaran</li>
                <li><strong>Status pending:</strong> Transaksi dibuat dengan status "pending"</li>
                <li><strong>Admin konfirmasi:</strong> Admin klik "Lunas" untuk mengubah status menjadi "success"</li>
                <li><strong>Saldo bertambah:</strong> Setelah status "success", saldo user otomatis bertambah</li>
            </ol>
            <p><strong>Note:</strong> Ini adalah simulasi, tidak ada uang real yang berpindah. Untuk real payment, gunakan Midtrans/Xendit atau daftar merchant resmi.</p>
        </div>
    </div>
    
    <script>
        function filterTransactions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#transactionsTable tbody tr');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                const status = row.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                
                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Auto-filter on search input
        document.getElementById('searchInput').addEventListener('input', filterTransactions);
        document.getElementById('statusFilter').addEventListener('change', filterTransactions);
    </script>
</body>
</html> 