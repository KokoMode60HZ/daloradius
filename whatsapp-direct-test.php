<?php
/**
 * WhatsApp Direct Test - Langsung buka WhatsApp Web
 */

require_once 'library/checklogin.php';
require_once 'library/DB.php';

// Check login
if (!isset($_SESSION['operator_user'])) {
    header('Location: dologin.php');
    exit;
}

$username = $_SESSION['operator_user'];
$db = DB::connect("mysql://root:@localhost:3306/radius");
$message = '';
$error = '';

// Handle WhatsApp send
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'send_whatsapp') {
    $phone = $_POST['phone'];
    $messageType = $_POST['message_type'];
    $customMessage = $_POST['custom_message'] ?? '';
    
    // Format phone number (hapus +62, ganti dengan 62)
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (substr($phone, 0, 1) === '0') {
        $phone = '62' . substr($phone, 1);
    }
    
    // Generate WhatsApp message based on type
    $whatsappMessage = '';
    switch ($messageType) {
        case 'payment_success':
            $whatsappMessage = "🎉 *Pembayaran Berhasil!*\n\nHalo {$username}, pembayaran Anda telah berhasil diproses.\n\nTerima kasih telah menggunakan layanan kami!";
            break;
        case 'payment_reminder':
            $whatsappMessage = "⏰ *Pengingatan Pembayaran*\n\nHalo {$username}, ini adalah pengingatan untuk pembayaran tagihan Anda.\n\nSilakan lakukan pembayaran segera untuk menghindari keterlambatan.";
            break;
        case 'low_balance':
            $whatsappMessage = "💰 *Saldo Rendah*\n\nHalo {$username}, saldo Anda sudah rendah.\n\nSilakan top up saldo untuk melanjutkan layanan.";
            break;
        case 'invoice':
            $whatsappMessage = "📄 *Invoice Baru*\n\nHalo {$username}, invoice baru telah dibuat untuk Anda.\n\nSilakan lakukan pembayaran sesuai dengan invoice yang diberikan.";
            break;
        case 'custom':
            $whatsappMessage = $customMessage;
            break;
        default:
            $whatsappMessage = "Halo {$username}, ini adalah pesan dari sistem daloRADIUS.";
    }
    
    // Store in database for record
    $sql = "INSERT INTO whatsapp_notifications (username, message_type, message, status, phone_number) 
            VALUES ('" . $db->escapeSimple($username) . "', '" . $db->escapeSimple($messageType) . "', '" . $db->escapeSimple($whatsappMessage) . "', 'sent', '" . $db->escapeSimple($phone) . "')";
    $db->query($sql);
    
    // Redirect to WhatsApp Web
    $encodedMessage = urlencode($whatsappMessage);
    $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";
    
    header("Location: {$whatsappUrl}");
    exit;
}

// Get user balance
$currentBalance = 0;
$sql = "SELECT balance FROM user_balance WHERE username = '" . $db->escapeSimple($username) . "'";
$result = $db->query($sql);
if ($result) {
    $row = $db->fetchRow($result, DB_FETCHMODE_ASSOC);
    $currentBalance = $row ? $row['balance'] : 0;
}

// Get recent WhatsApp notifications
$recentNotifications = [];
$sql = "SELECT * FROM whatsapp_notifications WHERE username = '" . $db->escapeSimple($username) . "' ORDER BY created_at DESC LIMIT 10";
$result = $db->query($sql);
if ($result) {
    while ($row = $db->fetchRow($result, DB_FETCHMODE_ASSOC)) {
        $recentNotifications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Direct Test - daloRADIUS</title>
    <link rel="stylesheet" href="css/1.css">
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: 20px 0; }
        .btn { background: #25d366; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin: 10px 5px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #128c7e; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { height: 100px; resize: vertical; }
        .notification-item { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #25d366; }
        .phone-preview { background: #e9ecef; padding: 15px; border-radius: 8px; margin: 15px 0; font-family: monospace; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-sent { background: #28a745; color: white; }
        .status-pending { background: #ffc107; color: #000; }
        .whatsapp-info { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 WhatsApp Direct Test</h1>
        <p>Kirim pesan WhatsApp langsung ke pengguna lain</p>
        
        <!-- WhatsApp Info -->
        <div class="whatsapp-info">
            <h3>ℹ️ Cara Kerja</h3>
            <p>1. Masukkan nomor telepon pengguna (format: 08123456789)</p>
            <p>2. Pilih tipe pesan atau tulis pesan custom</p>
            <p>3. Klik tombol "📤 Kirim WhatsApp"</p>
            <p>4. Otomatis buka WhatsApp Web dengan pesan yang sudah disiapkan</p>
        </div>
        
        <!-- Current Status -->
        <div class="card">
            <h3>📊 Status Saat Ini</h3>
            <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
            <p><strong>Current Balance:</strong> Rp <?= number_format($currentBalance, 2) ?></p>
        </div>
        
        <!-- WhatsApp Form -->
        <div class="card">
            <h3>📱 Kirim WhatsApp</h3>
            <form method="POST">
                <input type="hidden" name="action" value="send_whatsapp">
                
                <div class="form-group">
                    <label>📞 Nomor Telepon:</label>
                    <input type="tel" name="phone" value="08123456789" placeholder="08123456789" required>
                    <small>Format: 08123456789 (tanpa +62)</small>
                </div>
                
                <div class="form-group">
                    <label>💬 Tipe Pesan:</label>
                    <select name="message_type" id="message_type" onchange="toggleCustomMessage()">
                        <option value="payment_success">✅ Payment Success</option>
                        <option value="payment_reminder">⏰ Payment Reminder</option>
                        <option value="low_balance">💰 Low Balance</option>
                        <option value="invoice">📄 New Invoice</option>
                        <option value="custom">✏️ Custom Message</option>
                    </select>
                </div>
                
                <div class="form-group" id="custom_message_group" style="display: none;">
                    <label>✏️ Pesan Custom:</label>
                    <textarea name="custom_message" placeholder="Tulis pesan custom Anda di sini...">Halo! Ini adalah pesan custom dari sistem daloRADIUS.</textarea>
                </div>
                
                <div class="form-group">
                    <label>📱 Preview Pesan:</label>
                    <div class="phone-preview" id="message_preview">
                        Halo <?= htmlspecialchars($username) ?>, pembayaran Anda telah berhasil diproses.
                    </div>
                </div>
                
                <button type="submit" class="btn">📤 Kirim WhatsApp</button>
            </form>
        </div>
        
        <!-- Quick WhatsApp Buttons -->
        <div class="card">
            <h3>⚡ Quick WhatsApp</h3>
            <p>Kirim pesan cepat dengan template yang sudah disiapkan:</p>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="send_whatsapp">
                <input type="hidden" name="phone" value="08123456789">
                <input type="hidden" name="message_type" value="payment_success">
                <button type="submit" class="btn">🎉 Payment Success</button>
            </form>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="send_whatsapp">
                <input type="hidden" name="phone" value="08123456789">
                <input type="hidden" name="message_type" value="payment_reminder">
                <button type="submit" class="btn">⏰ Payment Reminder</button>
            </form>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="send_whatsapp">
                <input type="hidden" name="phone" value="08123456789">
                <input type="hidden" name="message_type" value="low_balance">
                <button type="submit" class="btn">💰 Low Balance</button>
            </form>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="send_whatsapp">
                <input type="hidden" name="phone" value="08123456789">
                <input type="hidden" name="message_type" value="invoice">
                <button type="submit" class="btn">📄 New Invoice</button>
            </form>
        </div>
        
        <!-- Recent Notifications -->
        <div class="card">
            <h3>📋 Riwayat Notifikasi WhatsApp</h3>
            <?php if (empty($recentNotifications)): ?>
                <p>Belum ada notifikasi WhatsApp yang dikirim.</p>
            <?php else: ?>
                <?php foreach ($recentNotifications as $notif): ?>
                    <div class="notification-item">
                        <p><strong>Tipe:</strong> <?= htmlspecialchars($notif['message_type']) ?></p>
                        <p><strong>Pesan:</strong> <?= htmlspecialchars($notif['message']) ?></p>
                        <p><strong>Status:</strong> 
                            <span class="status-badge status-<?= $notif['status'] ?>">
                                <?= ucfirst($notif['status']) ?>
                            </span>
                        </p>
                        <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($notif['created_at'] ?? 'now')) ?></p>
                        <?php if (!empty($notif['phone_number'])): ?>
                            <p><strong>Nomor:</strong> <?= htmlspecialchars($notif['phone_number']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div class="card">
            <h3>🔗 Navigasi</h3>
            <a href="payment-test.php" class="btn btn-secondary">💳 Payment Test</a>
            <a href="test-payment-system.php" class="btn btn-secondary">🧪 System Test</a>
            <a href="admin/payment-dashboard.php" class="btn btn-secondary">🔧 Admin Dashboard</a>
            <a href="index.php" class="btn btn-secondary">🏠 Dashboard</a>
        </div>
    </div>
    
    <script>
        function toggleCustomMessage() {
            const messageType = document.getElementById('message_type').value;
            const customGroup = document.getElementById('custom_message_group');
            const messagePreview = document.getElementById('message_preview');
            
            if (messageType === 'custom') {
                customGroup.style.display = 'block';
                // Update preview when custom message changes
                document.querySelector('textarea[name="custom_message"]').addEventListener('input', updatePreview);
            } else {
                customGroup.style.display = 'none';
                updatePreview();
            }
        }
        
        function updatePreview() {
            const messageType = document.getElementById('message_type').value;
            const messagePreview = document.getElementById('message_preview');
            const customMessage = document.querySelector('textarea[name="custom_message"]')?.value || '';
            
            let preview = '';
            switch (messageType) {
                case 'payment_success':
                    preview = '🎉 *Pembayaran Berhasil!*\n\nHalo <?= htmlspecialchars($username) ?>, pembayaran Anda telah berhasil diproses.\n\nTerima kasih telah menggunakan layanan kami!';
                    break;
                case 'payment_reminder':
                    preview = '⏰ *Pengingatan Pembayaran*\n\nHalo <?= htmlspecialchars($username) ?>, ini adalah pengingatan untuk pembayaran tagihan Anda.\n\nSilakan lakukan pembayaran segera untuk menghindari keterlambatan.';
                    break;
                case 'low_balance':
                    preview = '💰 *Saldo Rendah*\n\nHalo <?= htmlspecialchars($username) ?>, saldo Anda sudah rendah.\n\nSilakan top up saldo untuk melanjutkan layanan.';
                    break;
                case 'invoice':
                    preview = '📄 *Invoice Baru*\n\nHalo <?= htmlspecialchars($username) ?>, invoice baru telah dibuat untuk Anda.\n\nSilakan lakukan pembayaran sesuai dengan invoice yang diberikan.';
                    break;
                case 'custom':
                    preview = customMessage || 'Halo <?= htmlspecialchars($username) ?>, ini adalah pesan custom dari sistem daloRADIUS.';
                    break;
                default:
                    preview = 'Halo <?= htmlspecialchars($username) ?>, ini adalah pesan dari sistem daloRADIUS.';
            }
            
            messagePreview.textContent = preview;
        }
        
        // Initialize preview
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>
</body>
</html>

