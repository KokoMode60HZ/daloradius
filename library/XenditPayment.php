<?php
/**
 * Xendit Payment Gateway Handler
 * Class untuk handle semua operasi payment dengan Xendit
 */

require_once 'DB.php';
require_once '../config/payment_config.php';

class XenditPayment {
    private $db;
    private $apiKey;
    private $callbackToken;
    private $baseUrl;
    private $isProduction;
    
    public function __construct() {
        $this->db = new DB();
        $this->apiKey = XENDIT_API_KEY;
        $this->callbackToken = XENDIT_CALLBACK_TOKEN;
        $this->isProduction = (PAYMENT_MODE === 'production');
        
        // Set Xendit base URL
        if ($this->isProduction) {
            $this->baseUrl = 'https://api.xendit.co';
        } else {
            $this->baseUrl = 'https://api-staging.xendit.co';
        }
    }
    
    /**
     * Buat transaksi baru di Xendit
     */
    public function createTransaction($username, $amount, $description = '') {
        try {
            // Generate transaction ID
            $transactionId = generateTransactionId('XND');
            
            // Prepare transaction data for Xendit
            $transactionData = [
                'external_id' => $transactionId,
                'amount' => $amount,
                'description' => $description ?: 'Top Up Saldo - ' . $username,
                'success_redirect_url' => 'https://yourdomain.com/payment-success.php',
                'failure_redirect_url' => 'https://yourdomain.com/payment-failed.php',
                'payment_methods' => ['BCA', 'MANDIRI', 'BNI', 'BRI'],
                'should_send_email' => false,
                'customer' => [
                    'given_names' => $username,
                    'email' => $username . '@example.com'
                ]
            ];
            
            // Create transaction in database first
            $this->createLocalTransaction($username, $amount, 'xendit', 'pending', $transactionId);
            
            // Create invoice in Xendit
            $response = $this->makeXenditRequest('/v2/invoices', $transactionData);
            
            if ($response && isset($response['id'])) {
                // Update transaction with Xendit invoice ID
                $this->updateTransactionXenditId($transactionId, $response['id']);
                
                return [
                    'success' => true,
                    'invoice_id' => $response['id'],
                    'transaction_id' => $transactionId,
                    'redirect_url' => $response['invoice_url'],
                    'expiry_date' => $response['expiry_date']
                ];
            } else {
                throw new Exception('Gagal membuat invoice di Xendit');
            }
            
        } catch (Exception $e) {
            logPaymentError('Xendit createTransaction error: ' . $e->getMessage(), [
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
     * Handle callback dari Xendit
     */
    public function handleCallback($callbackData) {
        try {
            // Verify callback token
            if (!$this->verifyCallbackToken($callbackData)) {
                throw new Exception('Invalid callback token');
            }
            
            $externalId = $callbackData['external_id'];
            $status = $callbackData['status'];
            $invoiceId = $callbackData['id'];
            
            // Map Xendit status to our status
            $ourStatus = $this->mapXenditStatus($status);
            
            // Update transaction status
            $this->updateTransactionStatus($externalId, $ourStatus, $callbackData);
            
            // If payment success, update user balance
            if ($ourStatus === 'success') {
                $this->processSuccessfulPayment($externalId);
            }
            
            // Send WhatsApp notification
            $this->sendPaymentNotification($externalId, $ourStatus);
            
            return [
                'success' => true,
                'message' => 'Callback processed successfully'
            ];
            
        } catch (Exception $e) {
            logPaymentError('Xendit callback error: ' . $e->getMessage(), $callbackData);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify callback token dari Xendit
     */
    private function verifyCallbackToken($callbackData) {
        $token = $_SERVER['HTTP_X_XENDIT_SIGNATURE'] ?? '';
        $payload = file_get_contents('php://input');
        
        $expectedToken = hash_hmac('sha256', $payload, $this->callbackToken);
        
        return hash_equals($expectedToken, $token);
    }
    
    /**
     * Map status Xendit ke status kita
     */
    private function mapXenditStatus($status) {
        switch ($status) {
            case 'PAID':
                return 'success';
            case 'PENDING':
                return 'pending';
            case 'EXPIRED':
            case 'VOIDED':
                return 'failed';
            default:
                return 'pending';
        }
    }
    
    /**
     * Make HTTP request to Xendit API
     */
    private function makeXenditRequest($endpoint, $data) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':')
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 || $httpCode === 201) {
            return json_decode($response, true);
        } else {
            logPaymentError('Xendit API error', [
                'endpoint' => $endpoint,
                'http_code' => $httpCode,
                'response' => $response
            ]);
            return false;
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
        $stmt->bind_param("sdsss", $username, $amount, $method, $status, $transactionId, 'Xendit payment');
        
        return $stmt->execute();
    }
    
    /**
     * Update Xendit invoice ID di database
     */
    private function updateTransactionXenditId($transactionId, $xenditId) {
        $query = "UPDATE " . TABLE_PAYMENT_TRANSACTIONS . " 
                  SET notes = CONCAT(notes, ' | Xendit ID: ', ?) 
                  WHERE transaction_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $xenditId, $transactionId);
        
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
                return "Halo $username! Pembayaran Rp " . number_format($amount, 0, ',', '.') . " berhasil diproses via Xendit. Saldo Anda telah diperbarui.";
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
     * Get invoice status from Xendit
     */
    public function getInvoiceStatus($invoiceId) {
        try {
            $response = $this->makeXenditRequest('/v2/invoices/' . $invoiceId, null);
            return $response;
        } catch (Exception $e) {
            logPaymentError('Xendit getStatus error: ' . $e->getMessage(), ['invoice_id' => $invoiceId]);
            return false;
        }
    }
    
    /**
     * Expire invoice
     */
    public function expireInvoice($invoiceId) {
        try {
            $response = $this->makeXenditRequest('/invoices/' . $invoiceId . '/expire', []);
            return $response;
        } catch (Exception $e) {
            logPaymentError('Xendit expire error: ' . $e->getMessage(), ['invoice_id' => $invoiceId]);
            return false;
        }
    }
    
    /**
     * Get available payment methods
     */
    public function getPaymentMethods() {
        try {
            $response = $this->makeXenditRequest('/v2/invoices/payment_methods', []);
            return $response;
        } catch (Exception $e) {
            logPaymentError('Xendit getPaymentMethods error: ' . $e->getMessage());
            return false;
        }
    }
}
?> 