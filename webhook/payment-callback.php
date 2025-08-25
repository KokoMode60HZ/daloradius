<?php
/**
 * Webhook Callback untuk Payment Gateway Simulasi
 * Sistem buatan sendiri untuk testing tanpa pihak ketiga
 */

require_once '../library/DB.php';
require_once '../config/payment_config.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Log webhook request
logPayment("Webhook callback received: " . json_encode($_POST));

try {
    // Validasi request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method tidak diizinkan");
    }
    
    // Ambil data dari POST
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        $data = $_POST; // Fallback ke POST data
    }
    
    if (empty($data)) {
        throw new Exception("Data tidak ditemukan");
    }
    
    // Validasi secret key (untuk simulasi)
    if (!isset($data['secret']) || $data['secret'] !== WEBHOOK_SECRET) {
        throw new Exception("Secret key tidak valid");
    }
    
    // Validasi data yang diperlukan
    if (empty($data['transaction_id']) || empty($data['status'])) {
        throw new Exception("Data transaksi tidak lengkap");
    }
    
    $transactionId = $data['transaction_id'];
    $status = $data['status'];
    $amount = $data['amount'] ?? 0;
    $paymentMethod = $data['payment_method'] ?? 'unknown';
    
    // Koneksi database
    $db = DB::connect("mysql://root:@localhost:3306/radius");
    if ($db->isError()) {
        throw new Exception("Database connection failed: " . $db->getMessage());
    }
    
    // Update status transaksi
    $updateQuery = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                    SET status = '{$db->escapeSimple($status)}', 
                        updated_at = NOW() 
                    WHERE transaction_id = '{$db->escapeSimple($transactionId)}'";
    
    $result = $db->query($updateQuery);
    if ($db->isError($result)) {
        throw new Exception("Gagal update status: " . $db->getMessage($result));
    }
    
    // Jika status success, update saldo user
    if ($status === 'success') {
        // Ambil data transaksi
        $getQuery = "SELECT username, amount FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                     WHERE transaction_id = '{$db->escapeSimple($transactionId)}'";
        
        $getResult = $db->query($getQuery);
        if ($db->isError($getResult)) {
            throw new Exception("Gagal ambil data transaksi: " . $db->getMessage($getResult));
        }
        
        if ($db->numRows($getResult) > 0) {
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
            if ($db->isError($balanceResult)) {
                throw new Exception("Gagal update saldo user: " . $db->getMessage($balanceResult));
            }
            
            logPayment("Saldo user {$username} bertambah Rp {$amount} setelah transaksi {$transactionId}");
        }
    }
    
    // Log sukses
    logPayment("Webhook callback berhasil: {$transactionId} -> {$status}");
    
    // Response sukses
    echo json_encode([
        'success' => true,
        'message' => 'Callback berhasil diproses',
        'transaction_id' => $transactionId,
        'status' => $status,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    // Log error
    logPayment("Webhook callback error: " . $e->getMessage());
    
    // Response error
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?> 