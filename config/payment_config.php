<?php
/**
 * Konfigurasi Payment Gateway Simulasi
 * Sistem buatan sendiri untuk testing tanpa pihak ketiga
 */

// Mode aplikasi
define('PAYMENT_MODE', 'simulation');

// Database table names
define('TABLE_USER_BALANCE', 'user_balance');
define('TABLE_PAYMENT_TRANSACTIONS', 'payment_transactions');
define('TABLE_BANK_ACCOUNTS', 'bank_accounts');
define('TABLE_WHATSAPP_NOTIFICATIONS', 'whatsapp_notifications');

// Webhook settings (untuk simulasi)
define('WEBHOOK_URL', 'http://localhost/daloradius/webhook/payment-callback.php');
define('WEBHOOK_SECRET', 'simulation_secret_123');

// Payment methods yang didukung (simulasi)
define('PAYMENT_METHODS', [
    'gopay' => 'GoPay',
    'dana' => 'DANA',
    'shopeepay' => 'ShopeePay',
    'bank_transfer' => 'Transfer Bank',
    'cash' => 'Tunai'
]);

// Status transaksi
define('PAYMENT_STATUS', [
    'pending' => 'Menunggu Pembayaran',
    'success' => 'Pembayaran Berhasil',
    'failed' => 'Pembayaran Gagal',
    'cancelled' => 'Dibatalkan',
    'expired' => 'Kadaluarsa'
]);

// Logging
define('LOG_DIR', __DIR__ . '/../logs');
define('PAYMENT_LOG_FILE', LOG_DIR . '/payment_simulation.log');

// Helper functions
function logPayment($message) {
    if (!is_dir(LOG_DIR)) {
        mkdir(LOG_DIR, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents(PAYMENT_LOG_FILE, $logMessage, FILE_APPEND | LOCK_EX);
}

function generateTransactionId() {
    return 'TXN' . date('YmdHis') . rand(1000, 9999);
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function validateAmount($amount) {
    return is_numeric($amount) && $amount > 0 && $amount <= 1000000;
}

function getPaymentMethodName($method) {
    return PAYMENT_METHODS[$method] ?? 'Metode Tidak Dikenal';
}

function getStatusName($status) {
    return PAYMENT_STATUS[$status] ?? 'Status Tidak Dikenal';
}
?> 