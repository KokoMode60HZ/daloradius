<?php
/*
 * Admin Bank Accounts Management
 * Halaman untuk mengelola rekening bank
 */

include_once("library/checklogin.php");
include_once("library/opendb.php");
include_once("include/management/functions.php");
include_once("include/management/pages_common.php");

// Check if user is admin
if (!isset($_SESSION['operator_user']) || $_SESSION['operator_user'] !== 'administrator') {
    header("Location: index.php");
    exit;
}

// Handle form submissions
if ($_POST['action'] == 'add_bank') {
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $account_holder = trim($_POST['account_holder']);
    
    if (!empty($bank_name) && !empty($account_number) && !empty($account_holder)) {
        $sql = "INSERT INTO bank_accounts (bank_name, account_number, account_holder) VALUES (?, ?, ?)";
        $stmt = $dbSocket->prepare($sql);
        $stmt->bind_param("sss", $bank_name, $account_number, $account_holder);
        
        if ($stmt->execute()) {
            $success_msg = "Rekening bank berhasil ditambahkan!";
        } else {
            $error_msg = "Gagal menambahkan rekening bank!";
        }
    } else {
        $error_msg = "Semua field harus diisi!";
    }
}

if ($_POST['action'] == 'edit_bank') {
    $id = intval($_POST['bank_id']);
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $account_holder = trim($_POST['account_holder']);
    
    if ($id > 0 && !empty($bank_name) && !empty($account_number) && !empty($account_holder)) {
        $sql = "UPDATE bank_accounts SET bank_name = ?, account_number = ?, account_holder = ? WHERE id = ?";
        $stmt = $dbSocket->prepare($sql);
        $stmt->bind_param("sssi", $bank_name, $account_number, $account_holder, $id);
        
        if ($stmt->execute()) {
            $success_msg = "Rekening bank berhasil diupdate!";
        } else {
            $error_msg = "Gagal mengupdate rekening bank!";
        }
    } else {
        $error_msg = "Data tidak valid!";
    }
}

if ($_POST['action'] == 'delete_bank') {
    $id = intval($_POST['bank_id']);
    
    if ($id > 0) {
        $sql = "DELETE FROM bank_accounts WHERE id = ?";
        $stmt = $dbSocket->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success_msg = "Rekening bank berhasil dihapus!";
        } else {
            $error_msg = "Gagal menghapus rekening bank!";
        }
    }
}

if ($_POST['action'] == 'toggle_status') {
    $id = intval($_POST['bank_id']);
    $status = $_POST['status'] == '1' ? 0 : 1;
    
    if ($id > 0) {
        $sql = "UPDATE bank_accounts SET is_active = ? WHERE id = ?";
        $stmt = $dbSocket->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        
        if ($stmt->execute()) {
            $success_msg = "Status rekening bank berhasil diubah!";
        } else {
            $error_msg = "Gagal mengubah status rekening bank!";
        }
    }
}

// Get all bank accounts
$bank_accounts = [];
$sql = "SELECT * FROM bank_accounts ORDER BY created_at DESC";
$result = $dbSocket->query($sql);
while ($row = $result->fetch_assoc()) {
    $bank_accounts[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Bank Accounts - daloRADIUS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/1.css">
    <style>
        .admin-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .form-group {
            margin: 15px 0;
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
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #1e7e34;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }
        .bank-item {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .bank-item.active {
            border-left: 4px solid #28a745;
        }
        .bank-item.inactive {
            border-left: 4px solid #dc3545;
            opacity: 0.7;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
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
            margin: 10% auto;
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
        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Admin Bank Accounts Management</h1>
        
        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <!-- Admin Header -->
        <div class="admin-header">
            <h2>🔐 Admin Panel - Bank Accounts</h2>
            <p>Kelola rekening bank untuk payment system</p>
        </div>
        
        <!-- Add New Bank Account -->
        <div class="admin-card">
            <h3>➕ Tambah Rekening Bank Baru</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_bank">
                
                <div class="form-group">
                    <label>Nama Bank:</label>
                    <input type="text" name="bank_name" placeholder="Contoh: Bank BCA" required>
                </div>
                
                <div class="form-group">
                    <label>Nomor Rekening:</label>
                    <input type="text" name="account_number" placeholder="Contoh: 1234567890" required>
                </div>
                
                <div class="form-group">
                    <label>Atas Nama:</label>
                    <input type="text" name="account_holder" placeholder="Contoh: Nama Lengkap" required>
                </div>
                
                <button type="submit" class="btn btn-success">➕ Tambah Rekening</button>
            </form>
        </div>
        
        <!-- Bank Accounts List -->
        <div class="admin-card">
            <h3>📋 Daftar Rekening Bank</h3>
            <?php if (empty($bank_accounts)): ?>
                <p>Belum ada rekening bank yang ditambahkan.</p>
            <?php else: ?>
                <?php foreach ($bank_accounts as $bank): ?>
                    <div class="bank-item <?php echo $bank['is_active'] ? 'active' : 'inactive'; ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4><?php echo htmlspecialchars($bank['bank_name']); ?></h4>
                            <span class="status-badge <?php echo $bank['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $bank['is_active'] ? 'AKTIF' : 'NONAKTIF'; ?>
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <p><strong>No. Rekening:</strong> <?php echo htmlspecialchars($bank['account_number']); ?></p>
                            <p><strong>Atas Nama:</strong> <?php echo htmlspecialchars($bank['account_holder']); ?></p>
                            <p><strong>Dibuat:</strong> <?php echo date('d/m/Y H:i', strtotime($bank['created_at'])); ?></p>
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="btn btn-warning btn-sm" onclick="editBank(<?php echo $bank['id']; ?>, '<?php echo htmlspecialchars($bank['bank_name']); ?>', '<?php echo htmlspecialchars($bank['account_number']); ?>', '<?php echo htmlspecialchars($bank['account_holder']); ?>')">
                                ✏️ Edit
                            </button>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="bank_id" value="<?php echo $bank['id']; ?>">
                                <input type="hidden" name="status" value="<?php echo $bank['is_active']; ?>">
                                <button type="submit" class="btn <?php echo $bank['is_active'] ? 'btn-warning' : 'btn-success'; ?> btn-sm">
                                    <?php echo $bank['is_active'] ? '🚫 Nonaktifkan' : '✅ Aktifkan'; ?>
                                </button>
                            </form>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus rekening ini?');">
                                <input type="hidden" name="action" value="delete_bank">
                                <input type="hidden" name="bank_id" value="<?php echo $bank['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="index.php" class="btn">🏠 Kembali ke Dashboard</a>
            <a href="payment-test.php" class="btn">💰 Payment Test Page</a>
            <a href="whatsapp-test.php" class="btn">📱 WhatsApp Test Page</a>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>✏️ Edit Rekening Bank</h3>
            
            <form method="POST">
                <input type="hidden" name="action" value="edit_bank">
                <input type="hidden" name="bank_id" id="edit_bank_id">
                
                <div class="form-group">
                    <label>Nama Bank:</label>
                    <input type="text" name="bank_name" id="edit_bank_name" required>
                </div>
                
                <div class="form-group">
                    <label>Nomor Rekening:</label>
                    <input type="text" name="account_number" id="edit_account_number" required>
                </div>
                
                <div class="form-group">
                    <label>Atas Nama:</label>
                    <input type="text" name="account_holder" id="edit_account_holder" required>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-success">💾 Update Rekening</button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">❌ Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Edit bank function
        function editBank(id, bankName, accountNumber, accountHolder) {
            document.getElementById('edit_bank_id').value = id;
            document.getElementById('edit_bank_name').value = bankName;
            document.getElementById('edit_account_number').value = accountNumber;
            document.getElementById('edit_account_holder').value = accountHolder;
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Close modal function
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        // Auto-refresh after successful operations
        <?php if (isset($success_msg)): ?>
        setTimeout(function() {
            location.reload();
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html> 