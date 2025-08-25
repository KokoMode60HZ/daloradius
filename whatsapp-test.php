<?php
/*
 * WhatsApp Testing Page untuk daloRADIUS
 * Halaman khusus untuk testing fitur WhatsApp
 */

include_once("library/checklogin.php");
include_once("library/opendb.php");
include_once("include/management/functions.php");
include_once("include/management/pages_common.php");

// Get user info
$username = $_SESSION['operator_user'];

// Handle WhatsApp send
if ($_POST['action'] == 'send_wa') {
    $phone = $_POST['phone'];
    $message_type = $_POST['message_type'];
    $custom_message = $_POST['custom_message'];
    
    // Generate message based on type
    switch ($message_type) {
        case 'payment_success':
            $message = "🎉 Payment Berhasil!\n\nHalo {$username}, payment Anda telah berhasil diproses.\n\nTerima kasih telah melakukan pembayaran.\n\nSalam,\nTim Support";
            break;
            
        case 'payment_reminder':
            $message = "⏰ Peringatan Pembayaran!\n\nHalo {$username}, ini adalah pengingat untuk pembayaran Anda.\n\nMohon segera lakukan pembayaran untuk menghindari gangguan layanan.\n\nSalam,\nTim Support";
            break;
            
        case 'low_balance':
            $message = "⚠️ Saldo Rendah!\n\nHalo {$username}, saldo Anda sudah rendah.\n\nMohon segera top up untuk melanjutkan layanan.\n\nSalam,\nTim Support";
            break;
            
        case 'invoice':
            $message = "📄 Invoice Pembayaran!\n\nHalo {$username}, berikut adalah invoice pembayaran Anda:\n\nTotal: Rp 50.000\nJatuh Tempo: " . date('d/m/Y') . "\n\nMohon segera lakukan pembayaran.\n\nSalam,\nTim Support";
            break;
            
        case 'custom':
            $message = $custom_message;
            break;
            
        default:
            $message = "Halo {$username}, ini adalah pesan dari daloRADIUS Payment System.";
    }
    
    if (sendWhatsAppNotification($username, $message_type, $message, $phone)) {
        $success_msg = "WhatsApp berhasil dikirim ke {$phone}!";
    } else {
        $error_msg = "Gagal kirim WhatsApp!";
    }
}

// Get recent WhatsApp notifications
$recent_notifications = [];
$sql = "SELECT * FROM whatsapp_notifications WHERE username = ? ORDER BY created_at DESC LIMIT 10";
$stmt = $dbSocket->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $recent_notifications[] = $row;
}

// WhatsApp notification function
function sendWhatsAppNotification($username, $type, $message, $phone) {
    global $dbSocket;
    
    // For testing, we'll simulate WhatsApp sending
    // In production, integrate with WhatsApp Business API
    
    // Insert notification record
    $sql = "INSERT INTO whatsapp_notifications (username, phone_number, message_type, message, status, sent_at) VALUES (?, ?, ?, ?, 'sent', NOW())";
    $stmt = $dbSocket->prepare($sql);
    $stmt->bind_param("ssss", $username, $phone, $type, $message);
    
    if ($stmt->execute()) {
        // Simulate WhatsApp sending (replace with real API call)
        // Here you would integrate with:
        // - WhatsApp Business API
        // - Twilio WhatsApp
        // - MessageBird
        // - Or other WhatsApp services
        
        return true;
    }
    
    return false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Testing - daloRADIUS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/1.css">
    <style>
        .whatsapp-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .whatsapp-header {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
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
        .btn-whatsapp {
            background: #25d366;
        }
        .btn-whatsapp:hover {
            background: #128c7e;
        }
        .notification-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #25d366;
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
        .message-preview {
            background: #e8f5e8;
            border: 1px solid #25d366;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
            font-family: monospace;
            white-space: pre-wrap;
        }
        .template-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }
        .template-btn {
            background: #6c757d;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
        }
        .template-btn:hover {
            background: #5a6268;
        }
        .template-btn.active {
            background: #25d366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 WhatsApp Testing System</h1>
        
        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <!-- WhatsApp Header -->
        <div class="whatsapp-header">
            <h2>💬 Test WhatsApp Integration</h2>
            <p>Kirim berbagai jenis notifikasi WhatsApp untuk testing</p>
        </div>
        
        <!-- WhatsApp Form -->
        <div class="whatsapp-card">
            <h3>📤 Kirim WhatsApp</h3>
            <form method="POST" id="waForm">
                <input type="hidden" name="action" value="send_wa">
                
                <div class="form-group">
                    <label>Nomor WhatsApp:</label>
                    <input type="text" name="phone" id="phone" value="08123456789" placeholder="08123456789" required>
                    <small>Format: 08123456789 (tanpa +62)</small>
                </div>
                
                <div class="form-group">
                    <label>Tipe Pesan:</label>
                    <select name="message_type" id="message_type" required>
                        <option value="payment_success">🎉 Payment Success</option>
                        <option value="payment_reminder">⏰ Payment Reminder</option>
                        <option value="low_balance">⚠️ Low Balance Warning</option>
                        <option value="invoice">📄 Invoice</option>
                        <option value="custom">✏️ Custom Message</option>
                    </select>
                </div>
                
                <div class="form-group" id="custom_message_group" style="display: none;">
                    <label>Pesan Custom:</label>
                    <textarea name="custom_message" id="custom_message" rows="4" placeholder="Tulis pesan custom Anda di sini...">Halo! Ini adalah pesan custom dari daloRADIUS Payment System.</textarea>
                </div>
                
                <!-- Template Buttons -->
                <div class="template-buttons">
                    <button type="button" class="template-btn" data-type="payment_success">Payment Success</button>
                    <button type="button" class="template-btn" data-type="payment_reminder">Payment Reminder</button>
                    <button type="button" class="template-btn" data-type="low_balance">Low Balance</button>
                    <button type="button" class="template-btn" data-type="invoice">Invoice</button>
                </div>
                
                <!-- Message Preview -->
                <div class="form-group">
                    <label>Preview Pesan:</label>
                    <div class="message-preview" id="message_preview">
                        Halo! Ini adalah preview pesan WhatsApp.
                    </div>
                </div>
                
                <button type="submit" class="btn btn-whatsapp">📤 Kirim WhatsApp</button>
            </form>
        </div>
        
        <!-- Recent Notifications -->
        <div class="whatsapp-card">
            <h3>📊 Riwayat Notifikasi WhatsApp</h3>
            <?php if (empty($recent_notifications)): ?>
                <p>Belum ada notifikasi WhatsApp.</p>
            <?php else: ?>
                <?php foreach ($recent_notifications as $notification): ?>
                    <div class="notification-item">
                        <div><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></div>
                        <div><strong>Nomor:</strong> <?php echo htmlspecialchars($notification['phone_number']); ?></div>
                        <div><strong>Tipe:</strong> <?php echo ucfirst(str_replace('_', ' ', $notification['message_type'])); ?></div>
                        <div><strong>Status:</strong> 
                            <span style="color: <?php echo $notification['status'] == 'sent' ? 'green' : 'orange'; ?>">
                                <?php echo ucfirst($notification['status']); ?>
                            </span>
                        </div>
                        <div><strong>Pesan:</strong></div>
                        <div style="background: #f1f1f1; padding: 10px; border-radius: 5px; margin-top: 5px; font-family: monospace; white-space: pre-wrap;"><?php echo htmlspecialchars($notification['message']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Navigation -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="index.php" class="btn">🏠 Kembali ke Dashboard</a>
            <a href="payment-test.php" class="btn">💰 Payment Test Page</a>
        </div>
    </div>
    
    <script>
        // Handle message type change
        document.getElementById('message_type').addEventListener('change', function() {
            const customGroup = document.getElementById('custom_message_group');
            if (this.value === 'custom') {
                customGroup.style.display = 'block';
            } else {
                customGroup.style.display = 'none';
                updateMessagePreview();
            }
        });
        
        // Handle template buttons
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                document.getElementById('message_type').value = type;
                document.getElementById('custom_message_group').style.display = 'none';
                
                // Update active button
                document.querySelectorAll('.template-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                updateMessagePreview();
            });
        });
        
        // Update message preview
        function updateMessagePreview() {
            const type = document.getElementById('message_type').value;
            const username = '<?php echo $username; ?>';
            let message = '';
            
            switch (type) {
                case 'payment_success':
                    message = `🎉 Payment Berhasil!

Halo ${username}, payment Anda telah berhasil diproses.

Terima kasih telah melakukan pembayaran.

Salam,
Tim Support`;
                    break;
                    
                case 'payment_reminder':
                    message = `⏰ Peringatan Pembayaran!

Halo ${username}, ini adalah pengingat untuk pembayaran Anda.

Mohon segera lakukan pembayaran untuk menghindari gangguan layanan.

Salam,
Tim Support`;
                    break;
                    
                case 'low_balance':
                    message = `⚠️ Saldo Rendah!

Halo ${username}, saldo Anda sudah rendah.

Mohon segera top up untuk melanjutkan layanan.

Salam,
Tim Support`;
                    break;
                    
                case 'invoice':
                    message = `📄 Invoice Pembayaran!

Halo ${username}, berikut adalah invoice pembayaran Anda:

Total: Rp 50.000
Jatuh Tempo: ${new Date().toLocaleDateString('id-ID')}

Mohon segera lakukan pembayaran.

Salam,
Tim Support`;
                    break;
                    
                default:
                    message = `Halo ${username}, ini adalah pesan dari daloRADIUS Payment System.`;
            }
            
            document.getElementById('message_preview').textContent = message;
        }
        
        // Handle custom message input
        document.getElementById('custom_message').addEventListener('input', function() {
            if (document.getElementById('message_type').value === 'custom') {
                document.getElementById('message_preview').textContent = this.value;
            }
        });
        
        // Initialize preview
        updateMessagePreview();
        
        // Phone number validation
        document.getElementById('phone').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html> 