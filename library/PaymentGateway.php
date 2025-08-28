<?php
/**
 * Payment Gateway Simulasi
 * Sistem buatan sendiri untuk testing tanpa pihak ketiga
 */

require_once 'DB.php';
require_once '../config/payment_config.php';

class PaymentGateway {
    private $db;
    
    public function __construct() {
        $this->db = DB::connect("mysql://root:@localhost:3306/radius");
        if ($this->db->isError()) {
            throw new Exception("Database connection failed: " . $this->db->getMessage());
        }
    }
    
    /**
     * Buat transaksi pembayaran baru (simulasi)
     */
    public function createPayment($username, $amount, $paymentMethod, $description = '') {
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
                  VALUES ('{$this->db->escapeSimple($transactionId)}', 
                          '{$this->db->escapeSimple($username)}', 
                          {$this->db->escapeSimple($amount)}, 
                          '{$this->db->escapeSimple($paymentMethod)}', 
                          '{$this->db->escapeSimple($description)}', 
                          'pending', 
                          '{$currentTime}', 
                          '{$currentTime}')";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal membuat transaksi: " . $this->db->getMessage($result));
        }
        
        logPayment("Transaksi baru dibuat: {$transactionId} - {$username} - Rp {$amount} - {$paymentMethod}");
        
        return [
            'transaction_id' => $transactionId,
            'username' => $username,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'created_at' => $currentTime
        ];
    }
    
    /**
     * Update status transaksi
     */
    public function updateTransactionStatus($transactionId, $newStatus, $adminUsername = 'system') {
        if (!array_key_exists($newStatus, PAYMENT_STATUS)) {
            throw new Exception("Status tidak valid");
        }
        
        $currentTime = date('Y-m-d H:i:s');
        
        // Update status transaksi
        $query = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                  SET status = '{$this->db->escapeSimple($newStatus)}', 
                      updated_at = '{$currentTime}' 
                  WHERE transaction_id = '{$this->db->escapeSimple($transactionId)}'";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal update status: " . $this->db->getMessage($result));
        }
        
        // Jika status success, update saldo user
        if ($newStatus === 'success') {
            $this->updateUserBalance($transactionId);
        }
        
        logPayment("Status transaksi {$transactionId} diupdate ke {$newStatus} oleh {$adminUsername}");
        
        return true;
    }
    
    /**
     * Update saldo user setelah pembayaran berhasil
     */
    private function updateUserBalance($transactionId) {
        // Ambil data transaksi
        $query = "SELECT username, amount FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  WHERE transaction_id = '{$this->db->escapeSimple($transactionId)}'";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal ambil data transaksi: " . $this->db->getMessage($result));
        }
        
        $transaction = $this->db->fetchRow($result, DB_FETCHMODE_ASSOC);
        if (!$transaction) {
            throw new Exception("Transaksi tidak ditemukan");
        }
        
        $username = $transaction['username'];
        $amount = $transaction['amount'];
        
        // Cek apakah user sudah ada di tabel balance
        $checkQuery = "SELECT id FROM " . TABLE_USER_BALANCE . " WHERE username = '{$this->db->escapeSimple($username)}'";
        $checkResult = $this->db->query($checkQuery);
        
        if ($this->db->numRows($checkResult) > 0) {
            // Update balance yang sudah ada
            $updateQuery = "UPDATE " . TABLE_USER_BALANCE . " 
                           SET balance = balance + {$amount}, 
                               last_updated = NOW() 
                           WHERE username = '{$this->db->escapeSimple($username)}'";
        } else {
            // Insert balance baru
            $updateQuery = "INSERT INTO " . TABLE_USER_BALANCE . " 
                           (username, balance, last_updated) 
                           VALUES ('{$this->db->escapeSimple($username)}', {$amount}, NOW())";
        }
        
        $updateResult = $this->db->query($updateQuery);
        if ($this->db->isError($updateResult)) {
            throw new Exception("Gagal update saldo user: " . $this->db->getMessage($updateResult));
        }
        
        logPayment("Saldo user {$username} bertambah Rp {$amount} setelah transaksi {$transactionId}");
    }
    
    /**
     * Ambil saldo user
     */
    public function getUserBalance($username) {
        $query = "SELECT balance FROM " . TABLE_USER_BALANCE . " 
                  WHERE username = '{$this->db->escapeSimple($username)}'";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal ambil saldo: " . $this->db->getMessage($result));
        }
        
        if ($this->db->numRows($result) > 0) {
            $row = $this->db->fetchRow($result, DB_FETCHMODE_ASSOC);
            return $row['balance'];
        }
        
        return 0.00;
    }
    
    /**
     * Ambil semua transaksi user
     */
    public function getUserTransactions($username, $limit = 10) {
        $query = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  WHERE username = '{$this->db->escapeSimple($username)}' 
                  ORDER BY created_at DESC 
                  LIMIT {$limit}";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal ambil transaksi: " . $this->db->getMessage($result));
        }
        
        $transactions = [];
        while ($row = $this->db->fetchRow($result, DB_FETCHMODE_ASSOC)) {
            $transactions[] = $row;
        }
        
        return $transactions;
    }
    
    /**
     * Ambil semua transaksi (admin)
     */
    public function getAllTransactions($limit = 50) {
        $query = "SELECT * FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  ORDER BY created_at DESC 
                  LIMIT {$limit}";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal ambil semua transaksi: " . $this->db->getMessage($result));
        }
        
        $transactions = [];
        while ($row = $this->db->fetchRow($result, DB_FETCHMODE_ASSOC)) {
            $transactions[] = $row;
        }
        
        return $transactions;
    }
    
    /**
     * Ambil statistik pembayaran
     */
    public function getPaymentStats() {
        $stats = [];
        
        // Total transaksi
        $totalQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS;
        $totalResult = $this->db->query($totalQuery);
        $stats['total_transactions'] = $this->db->getOne($totalResult);
        
        // Total pendapatan
        $incomeQuery = "SELECT SUM(amount) as total FROM " . TABLE_PAYMENT_TRANSACTIONS WHERE status = 'success'";
        $incomeResult = $this->db->query($incomeQuery);
        $stats['total_income'] = $this->db->getOne($incomeResult) ?: 0;
        
        // Transaksi pending
        $pendingQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS WHERE status = 'pending'";
        $pendingResult = $this->db->query($pendingQuery);
        $stats['pending_transactions'] = $this->db->getOne($pendingResult);
        
        // Transaksi success
        $successQuery = "SELECT COUNT(*) as total FROM " . TABLE_PAYMENT_TRANSACTIONS WHERE status = 'success'";
        $successResult = $this->db->query($successQuery);
        $stats['success_transactions'] = $this->db->getOne($successResult);
        
        return $stats;
    }
    
    /**
     * Hapus transaksi (admin only)
     */
    public function deleteTransaction($transactionId) {
        $query = "DELETE FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  WHERE transaction_id = '{$this->db->escapeSimple($transactionId)}'";
        
        $result = $this->db->query($query);
        if ($this->db->isError($result)) {
            throw new Exception("Gagal hapus transaksi: " . $this->db->getMessage($result));
        }
        
        logPayment("Transaksi {$transactionId} dihapus");
        return true;
    }
    
    /**
     * Generate QR Code untuk simulasi (fake QR)
     */
    public function generateFakeQR($transactionId, $amount) {
        // Ini hanya simulasi, tidak ada QR real
        $qrData = [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'timestamp' => time(),
            'fake_qr' => true
        ];
        
        return base64_encode(json_encode($qrData));
    }
    
    /**
     * Simulasi callback pembayaran (untuk testing)
     */
    public function simulatePaymentCallback($transactionId, $status) {
        return $this->updateTransactionStatus($transactionId, $status, 'simulation');
    }
}
?> 