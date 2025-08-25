<?php
/**
 * Admin Payment Dashboard
 * Dashboard admin untuk monitoring payment dan analytics
 */

session_start();
require_once 'library/checklogin.php';
require_once 'library/opendb.php';
require_once 'config/payment_config.php';

// Check if user is admin
if (!isset($_SESSION['operator_user']) || $_SESSION['operator_user'] !== 'administrator') {
    header('Location: dologin.php');
    exit;
}

$message = '';
$error = '';

// Handle admin actions
if ($_POST && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'update_status':
            $transactionId = $_POST['transaction_id'];
            $newStatus = $_POST['new_status'];
            $notes = $_POST['notes'] ?? '';
            
            $query = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                      SET status = ?, notes = CONCAT(notes, ' | Admin update: ', ?) 
                      WHERE transaction_id = ?";
            
            $stmt = $db->prepare($query);
            $stmt->bind_param("sss", $newStatus, $notes, $transactionId);
            
            if ($stmt->execute()) {
                $message = "Status transaksi $transactionId berhasil diupdate ke $newStatus";
                
                // If status changed to success, update user balance
                if ($newStatus === 'success') {
                    $this->processSuccessfulPayment($transactionId);
                }
            } else {
                $error = "Gagal mengupdate status transaksi";
            }
            break;
            
        case 'send_whatsapp':
            $username = $_POST['username'];
            $messageType = $_POST['message_type'];
            $customMessage = $_POST['custom_message'] ?? '';
            
            if ($this->sendWhatsAppNotification($username, $messageType, $customMessage)) {
                $message = "Notifikasi WhatsApp berhasil dikirim ke $username";
            } else {
                $error = "Gagal mengirim notifikasi WhatsApp";
            }
            break;
    }
}

// Get payment statistics
$statsQuery = "SELECT 
    COUNT(*) as total_transactions,
    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_transactions,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_transactions,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_transactions,
    SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_revenue,
    AVG(CASE WHEN status = 'success' THEN amount ELSE NULL END) as avg_transaction
FROM " . TABLE_PAYMENT_TRANSACTIONS;

$statsResult = $db->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Get recent transactions
$recentQuery = "SELECT pt.*, ub.balance as user_balance 
                FROM " . TABLE_PAYMENT_TRANSACTIONS . " pt
                LEFT JOIN " . TABLE_USER_BALANCE . " ub ON pt.username = ub.username
                ORDER BY pt.payment_date DESC 
                LIMIT 20";

$recentResult = $db->query($recentQuery);

// Get payment method distribution
$methodQuery = "SELECT 
    payment_method,
    COUNT(*) as count,
    SUM(amount) as total_amount
FROM " . TABLE_PAYMENT_TRANSACTIONS 
WHERE status = 'success'
GROUP BY payment_method";

$methodResult = $db->query($methodQuery);

// Get daily revenue for last 7 days
$dailyQuery = "SELECT 
    DATE(payment_date) as date,
    SUM(amount) as daily_revenue,
    COUNT(*) as transaction_count
FROM " . TABLE_PAYMENT_TRANSACTIONS 
WHERE status = 'success' 
    AND payment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(payment_date)
ORDER BY date DESC";

$dailyResult = $db->query($dailyQuery);

// Get pending transactions that need attention
$pendingQuery = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                 WHERE status = 'pending' 
                 ORDER BY payment_date ASC";

$pendingResult = $db->query($pendingQuery);

// Get user balance overview
$balanceQuery = "SELECT 
    username,
    balance,
    last_updated
FROM " . TABLE_USER_BALANCE . " 
ORDER BY balance DESC 
LIMIT 10";

$balanceResult = $db->query($balanceQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Dashboard - daloRADIUS</title>
    <link rel="stylesheet" href="css/1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 0.9em;
        }
        .chart-container {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .transaction-table th,
        .transaction-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .transaction-table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-success { background: #28a745; color: #fff; }
        .status-failed { background: #dc3545; color: #fff; }
        .status-cancelled { background: #6c757d; color: #fff; }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: #000; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover { color: #000; }
        .form-group {
            margin: 15px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin: 20px 0;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid transparent;
            border-bottom: none;
            background: #f8f9fa;
        }
        .tab.active {
            background: white;
            border-color: #ddd;
            border-bottom: 1px solid white;
            margin-bottom: -1px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>📊 Admin Payment Dashboard</h1>
        
        <!-- Messages -->
        <?php if ($message): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0;">
                ✅ <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0;">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Overview -->
        <h2>📈 Statistik Pembayaran</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($stats['total_transactions']); ?></div>
                <div class="stat-label">Total Transaksi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($stats['successful_transactions']); ?></div>
                <div class="stat-label">Transaksi Berhasil</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($stats['pending_transactions']); ?></div>
                <div class="stat-label">Transaksi Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo formatCurrency($stats['total_revenue']); ?></div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo formatCurrency($stats['avg_transaction']); ?></div>
                <div class="stat-label">Rata-rata Transaksi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_transactions'] > 0 ? round(($stats['successful_transactions'] / $stats['total_transactions']) * 100, 1) : 0; ?>%</div>
                <div class="stat-label">Success Rate</div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('transactions')">📋 Transaksi</div>
            <div class="tab" onclick="showTab('analytics')">📊 Analytics</div>
            <div class="tab" onclick="showTab('pending')">⏳ Pending</div>
            <div class="tab" onclick="showTab('balances')">💰 Saldo User</div>
        </div>
        
        <!-- Transactions Tab -->
        <div id="transactions" class="tab-content active">
            <h3>📋 Semua Transaksi</h3>
            <?php if ($recentResult->num_rows > 0): ?>
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Username</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Saldo User</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $recentResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($transaction['payment_date'])); ?></td>
                                <td><?php echo formatCurrency($transaction['amount']); ?></td>
                                <td><?php echo ucfirst($transaction['payment_method']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                        <?php echo ucfirst($transaction['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatCurrency($transaction['user_balance'] ?? 0); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary" onclick="showStatusModal('<?php echo $transaction['transaction_id']; ?>', '<?php echo $transaction['status']; ?>')">
                                            Edit Status
                                        </button>
                                        <button class="btn btn-success" onclick="showWhatsAppModal('<?php echo $transaction['username']; ?>')">
                                            WhatsApp
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada transaksi.</p>
            <?php endif; ?>
        </div>
        
        <!-- Analytics Tab -->
        <div id="analytics" class="tab-content">
            <h3>📊 Analytics Pembayaran</h3>
            
            <!-- Payment Method Distribution -->
            <div class="chart-container">
                <h4>Distribusi Metode Pembayaran</h4>
                <canvas id="paymentMethodChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Daily Revenue Chart -->
            <div class="chart-container">
                <h4>Pendapatan Harian (7 Hari Terakhir)</h4>
                <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <!-- Pending Tab -->
        <div id="pending" class="tab-content">
            <h3>⏳ Transaksi Pending</h3>
            <?php if ($pendingResult->num_rows > 0): ?>
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Username</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pending = $pendingResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pending['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($pending['username']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pending['payment_date'])); ?></td>
                                <td><?php echo formatCurrency($pending['amount']); ?></td>
                                <td><?php echo ucfirst($pending['payment_method']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-success" onclick="updateStatus('<?php echo $pending['transaction_id']; ?>', 'success')">
                                            Mark Success
                                        </button>
                                        <button class="btn btn-danger" onclick="updateStatus('<?php echo $pending['transaction_id']; ?>', 'failed')">
                                            Mark Failed
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada transaksi pending.</p>
            <?php endif; ?>
        </div>
        
        <!-- Balances Tab -->
        <div id="balances" class="tab-content">
            <h3>💰 Saldo User</h3>
            <?php if ($balanceResult->num_rows > 0): ?>
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Saldo</th>
                            <th>Terakhir Update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($balance = $balanceResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($balance['username']); ?></td>
                                <td><?php echo formatCurrency($balance['balance']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($balance['last_updated'])); ?></td>
                                <td>
                                    <button class="btn btn-success" onclick="showWhatsAppModal('<?php echo $balance['username']; ?>')">
                                        WhatsApp
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada data saldo user.</p>
            <?php endif; ?>
        </div>
        
        <!-- Back to Dashboard -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="index.php" style="text-decoration: none; color: #007bff;">← Kembali ke Dashboard</a>
        </div>
    </div>
    
    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('statusModal')">&times;</span>
            <h3>Update Status Transaksi</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="transaction_id" id="modalTransactionId">
                
                <div class="form-group">
                    <label for="new_status">Status Baru:</label>
                    <select name="new_status" id="new_status" required>
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Catatan (Opsional):</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Alasan perubahan status..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
    
    <!-- WhatsApp Modal -->
    <div id="whatsappModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('whatsappModal')">&times;</span>
            <h3>Kirim Notifikasi WhatsApp</h3>
            <form method="POST">
                <input type="hidden" name="action" value="send_whatsapp">
                <input type="hidden" name="username" id="modalUsername">
                
                <div class="form-group">
                    <label for="message_type">Tipe Pesan:</label>
                    <select name="message_type" id="message_type" required>
                        <option value="payment_success">Payment Success</option>
                        <option value="payment_reminder">Payment Reminder</option>
                        <option value="low_balance">Low Balance</option>
                        <option value="invoice">Invoice</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="custom_message">Pesan Kustom (Opsional):</label>
                    <textarea name="custom_message" id="custom_message" rows="3" placeholder="Pesan kustom..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Kirim WhatsApp</button>
            </form>
        </div>
    </div>
    
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
        
        // Modal functions
        function showStatusModal(transactionId, currentStatus) {
            document.getElementById('modalTransactionId').value = transactionId;
            document.getElementById('new_status').value = currentStatus;
            document.getElementById('statusModal').style.display = 'block';
        }
        
        function showWhatsAppModal(username) {
            document.getElementById('modalUsername').value = username;
            document.getElementById('whatsappModal').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Quick status update
        function updateStatus(transactionId, status) {
            if (confirm(`Yakin ingin mengubah status transaksi ${transactionId} menjadi ${status}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="transaction_id" value="${transactionId}">
                    <input type="hidden" name="new_status" value="${status}">
                    <input type="hidden" name="notes" value="Quick update via admin dashboard">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
        
        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Payment Method Chart
            const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
            const methodData = <?php 
                $methodData = [];
                while ($method = $methodResult->fetch_assoc()) {
                    $methodData[] = [
                        'label' => ucfirst($method['payment_method']),
                        'data' => $method['count'],
                        'amount' => $method['total_amount']
                    ];
                }
                echo json_encode($methodData);
            ?>;
            
            new Chart(methodCtx, {
                type: 'doughnut',
                data: {
                    labels: methodData.map(item => item.label),
                    datasets: [{
                        data: methodData.map(item => item.data),
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Daily Revenue Chart
            const revenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            const revenueData = <?php 
                $revenueData = [];
                while ($daily = $dailyResult->fetch_assoc()) {
                    $revenueData[] = [
                        'date' => $daily['date'],
                        'revenue' => $daily['daily_revenue'],
                        'count' => $daily['transaction_count']
                    ];
                }
                echo json_encode($revenueData);
            ?>;
            
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => item.date),
                    datasets: [{
                        label: 'Pendapatan Harian (Rp)',
                        data: revenueData.map(item => item.revenue),
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html> 