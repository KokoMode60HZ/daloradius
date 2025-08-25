<?php
/**
 * Midtrans Payment Gateway Handler
 * Class untuk handle semua operasi payment dengan Midtrans
 */

require_once 'DB.php';
require_once '../config/payment_config.php';

class MidtransPayment {
    private $db;
    private $serverKey;
    private $clientKey;
    private $merchantId;
    private $isProduction;
    
    public function __construct() {
        $this->db = new DB();
        $this->serverKey = MIDTRANS_SERVER_KEY;
        $this->clientKey = MIDTRANS_CLIENT_KEY;
        $this->merchantId = MIDTRANS_MERCHANT_ID;
        $this->isProduction = (PAYMENT_MODE === 'production');
        
        // Set Midtrans environment
        if ($this->isProduction) {
            \Midtrans\Config::$serverKey = $this->serverKey;
            \Midtrans\Config::$isProduction = true;
        } else {
            \Midtrans\Config::$serverKey = $this->serverKey;
            \Midtrans\Config::$isProduction = false;
        }
        
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
    
    /**
     * Buat transaksi baru di Midtrans
     */
    public function createTransaction($username, $amount, $description = '') {
        try {
            // Generate transaction ID
            $transactionId = generateTransactionId('MID');
            
            // Prepare transaction data
            $transactionDetails = [
                'order_id' => $transactionId,
                'gross_amount' => $amount,
                'payment_type' => 'bank_transfer',
                'bank_transfer' => [
                    'bank' => 'bca' // Default bank, bisa diubah
                ],
                'item_details' => [
                    [
                        'id' => 'TOPUP',
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => 'Top Up Saldo'
                    ]
                ],
                'customer_details' => [
                    'first_name' => $username,
                    'email' => $username . '@example.com'
                ],
                'custom_field1' => $username,
                'custom_field2' => 'TOPUP'
            ];
            
            // Create transaction in database first
            $this->createLocalTransaction($username, $amount, 'midtrans', 'pending', $transactionId);
            
            // Get Snap Token from Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($transactionDetails);
            
            if ($snapToken) {
                // Update transaction with snap token
                $this->updateTransactionSnapToken($transactionId, $snapToken);
                
                return [
                    'success' => true,
                    'snap_token' => $snapToken,
                    'transaction_id' => $transactionId,
                    'redirect_url' => 'https://app.midtrans.com/snap/v2/vtweb/' . $snapToken
                ];
            } else {
                throw new Exception('Gagal mendapatkan Snap Token dari Midtrans');
            }
            
        } catch (Exception $e) {
            logPaymentError('Midtrans createTransaction error: ' . $e->getMessage(), [
                'username' => $username,
                'amount' => $amount
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Handle callback dari Midtrans
     */
    public function handleCallback($callbackData) {
        try {
            // Verify signature key
            if (!$this->verifySignature($callbackData)) {
                throw new Exception('Invalid signature key');
            }
            
            $orderId = $callbackData['order_id'];
            $status = $callbackData['transaction_status'];
            $fraudStatus = $callbackData['fraud_status'];
            
            // Map Midtrans status to our status
            $ourStatus = $this->mapMidtransStatus($status, $fraudStatus);
            
            // Update transaction status
            $this->updateTransactionStatus($orderId, $ourStatus, $callbackData);
            
            // If payment success, update user balance
            if ($ourStatus === 'success') {
                $this->processSuccessfulPayment($orderId);
            }
            
            // Send WhatsApp notification
            $this->sendPaymentNotification($orderId, $ourStatus);
            
            return [
                'success' => true,
                'message' => 'Callback processed successfully'
            ];
            
        } catch (Exception $e) {
            logPaymentError('Midtrans callback error: ' . $e->getMessage(), $callbackData);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify signature key dari Midtrans
     */
    private function verifySignature($callbackData) {
        $orderId = $callbackData['order_id'];
        $statusCode = $callbackData['status_code'];
        $grossAmount = $callbackData['gross_amount'];
        $serverKey = $this->serverKey;
        
        $signature = $orderId . $statusCode . $grossAmount . $serverKey;
        $expectedSignature = hash('sha512', $signature);
        
        return $expectedSignature === $callbackData['signature_key'];
    }
    
    /**
     * Map status Midtrans ke status kita
     */
    private function mapMidtransStatus($status, $fraudStatus) {
        switch ($status) {
            case 'capture':
            case 'settlement':
                return 'success';
            case 'pending':
                return 'pending';
            case 'deny':
            case 'expire':
            case 'cancel':
                return 'failed';
            default:
                return 'pending';
        }
    }
    
    /**
     * Buat transaksi lokal di database
     */
    private function createLocalTransaction($username, $amount, $method, $status, $transactionId) {
        $query = "INSERT INTO " . TABLE_PAYMENT_TRANSACTIONS . " 
                  (username, amount, payment_method, status, transaction_id, notes) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdsss", $username, $amount, $method, $status, $transactionId, 'Midtrans payment');
        
        return $stmt->execute();
    }
    
    /**
     * Update snap token di database
     */
    private function updateTransactionSnapToken($transactionId, $snapToken) {
        $query = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                  SET notes = CONCAT(notes, ' | Snap Token: ', ?) 
                  WHERE transaction_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $snapToken, $transactionId);
        
        return $stmt->execute();
    }
    
    /**
     * Update status transaksi
     */
    private function updateTransactionStatus($transactionId, $status, $callbackData) {
        $query = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                  SET status = ?, notes = CONCAT(notes, ' | Callback: ', ?) 
                  WHERE transaction_id = ?";
        
        $callbackInfo = json_encode($callbackData);
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $status, $callbackInfo, $transactionId);
        
        return $stmt->execute();
    }
    
    /**
     * Proses pembayaran yang berhasil
     */
    private function processSuccessfulPayment($transactionId) {
        // Get transaction details
        $query = "SELECT username, amount FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  WHERE transaction_id = ? AND status = 'success'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $transactionId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            $amount = $row['amount'];
            
            // Update user balance
            $this->updateUserBalance($username, $amount);
        }
    }
    
    /**
     * Update saldo user
     */
    private function updateUserBalance($username, $amount) {
        $query = "INSERT INTO " . TABLE_USER_BALANCE . " (username, balance) 
                  VALUES (?, ?) 
                  ON DUPLICATE KEY UPDATE balance = balance + ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdd", $username, $amount, $amount);
        
        return $stmt->execute();
    }
    
    /**
     * Kirim notifikasi WhatsApp
     */
    private function sendPaymentNotification($transactionId, $status) {
        // Get transaction details
        $query = "SELECT username, amount FROM " . TABLE_PAYMENT_TRANSACTIONS . " 
                  WHERE transaction_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $transactionId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            $amount = $row['amount'];
            
            $messageType = ($status === 'success') ? 'payment_success' : 'payment_reminder';
            $message = $this->generateWhatsAppMessage($messageType, $username, $amount, $status);
            
            // Insert to WhatsApp notifications table
            $this->insertWhatsAppNotification($username, $messageType, $message);
        }
    }
    
    /**
     * Generate pesan WhatsApp
     */
    private function generateWhatsAppMessage($type, $username, $amount, $status) {
        switch ($type) {
            case 'payment_success':
                return "Halo $username! Pembayaran Rp " . number_format($amount, 0, ',', '.') . " berhasil diproses. Saldo Anda telah diperbarui.";
            case 'payment_reminder':
                return "Halo $username! Status pembayaran Anda: $status. Silakan cek dashboard untuk informasi lebih lanjut.";
            default:
                return "Halo $username! Ada update status pembayaran Anda.";
        }
    }
    
    /**
     * Insert notifikasi WhatsApp ke database
     */
    private function insertWhatsAppNotification($username, $type, $message) {
        $query = "INSERT INTO " . TABLE_WHATSAPP_NOTIFICATIONS . " 
                  (username, message_type, message, status) 
                  VALUES (?, ?, ?, 'pending')";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $type, $message);
        
        return $stmt->execute();
    }
    
    /**
     * Get transaction status
     */
    public function getTransactionStatus($transactionId) {
        try {
            $status = \Midtrans\Transaction::status($transactionId);
            return $status;
        } catch (Exception $e) {
            logPaymentError('Midtrans getStatus error: ' . $e->getMessage(), ['transaction_id' => $transactionId]);
            return false;
        }
    }
    
    /**
     * Cancel transaction
     */
    public function cancelTransaction($transactionId) {
        try {
            $cancel = \Midtrans\Transaction::cancel($transactionId);
            return $cancel;
        } catch (Exception $e) {
            logPaymentError('Midtrans cancel error: ' . $e->getMessage(), ['transaction_id' => $transactionId]);
            return false;
        }
    }
}
?> 