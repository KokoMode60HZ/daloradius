# 🚀 Payment Gateway Simulasi - daloRADIUS

Sistem payment gateway buatan sendiri untuk testing dan pembelajaran, terintegrasi dengan daloRADIUS untuk manajemen saldo user.

## 📋 Fitur Utama

### 💳 Payment Gateway Simulasi
- **Sistem buatan sendiri** - Tidak memerlukan pihak ketiga (Midtrans, Xendit, dll)
- **Metode pembayaran** - GoPay, DANA, ShopeePay, Transfer Bank, Tunai
- **Status transaksi** - Pending, Success, Failed, Cancelled, Expired
- **Simulasi lengkap** - Dari pembuatan transaksi hingga update saldo

### 🔄 Alur Kerja
1. **User buka checkout.php** → Pilih metode pembayaran
2. **Sistem generate transaksi** → Status "pending"
3. **Admin/User konfirmasi** → Klik "Tandai Lunas"
4. **Status berubah ke success** → Saldo user otomatis bertambah

### 📱 WhatsApp Integration
- **Direct messaging** - Link langsung ke WhatsApp Web
- **Template notifikasi** - Payment success, reminder, low balance, invoice
- **Database tracking** - Semua notifikasi tersimpan

### 🎯 Keunggulan
- ✅ **Tidak ada biaya** - Sistem buatan sendiri
- ✅ **Testing mudah** - Bisa test tanpa uang real
- ✅ **Integrasi lengkap** - Dengan daloRADIUS dan database
- ✅ **Admin dashboard** - Monitoring dan manajemen transaksi
- ✅ **Logging system** - Tracking semua aktivitas

## 🛠️ Instalasi

### 1. Prerequisites
- XAMPP (Apache + MySQL + PHP)
- daloRADIUS sudah terinstall dan berjalan
- Database `radius` sudah dibuat

### 2. Setup Database
```bash
# Import struktur database payment
mysql -u root -p radius < database_payment_setup.sql
```

### 3. Konfigurasi
```php
// config/payment_config.php
define('PAYMENT_MODE', 'simulation');
define('WEBHOOK_SECRET', 'simulation_secret_123');
```

### 4. File yang Diperlukan
- `config/payment_config.php` - Konfigurasi sistem
- `library/PaymentGateway.php` - Class utama payment
- `webhook/payment-callback.php` - Endpoint webhook
- `checkout.php` - Halaman checkout user
- `payment-test.php` - Testing payment system
- `admin/payment-dashboard.php` - Dashboard admin

## 🚀 Cara Penggunaan

### 1. User Checkout
```
1. Buka checkout.php
2. Pilih jumlah top-up (min Rp 1.000, max Rp 1.000.000)
3. Pilih metode pembayaran
4. Klik "Buat Transaksi"
5. Transaksi dibuat dengan status "pending"
```

### 2. Konfirmasi Pembayaran
```
1. Buka payment-test.php atau admin dashboard
2. Lihat transaksi pending
3. Klik "Tandai Lunas" untuk status success
4. Saldo user otomatis bertambah
```

### 3. Testing Webhook
```
1. Buat transaksi pending
2. Gunakan test webhook di test-payment-system.php
3. Pilih status yang diinginkan
4. Sistem akan update status dan saldo
```

## 🗄️ Struktur Database

### Tabel `user_balance`
```sql
CREATE TABLE user_balance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (username)
);
```

### Tabel `payment_transactions`
```sql
CREATE TABLE payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(64) UNIQUE NOT NULL,
    username VARCHAR(64) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('gopay','dana','shopeepay','bank_transfer','cash') NOT NULL,
    description TEXT,
    status ENUM('pending','success','failed','cancelled','expired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel `bank_accounts`
```sql
CREATE TABLE bank_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    account_holder VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel `whatsapp_notifications`
```sql
CREATE TABLE whatsapp_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL,
    message_type ENUM('payment_success','payment_reminder','low_balance','invoice','custom') NOT NULL,
    message TEXT NOT NULL,
    phone_number VARCHAR(20),
    status ENUM('pending','sent','failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔧 Konfigurasi

### Payment Methods
```php
define('PAYMENT_METHODS', [
    'gopay' => 'GoPay',
    'dana' => 'DANA',
    'shopeepay' => 'ShopeePay',
    'bank_transfer' => 'Transfer Bank',
    'cash' => 'Tunai'
]);
```

### Status Transaksi
```php
define('PAYMENT_STATUS', [
    'pending' => 'Menunggu Pembayaran',
    'success' => 'Pembayaran Berhasil',
    'failed' => 'Pembayaran Gagal',
    'cancelled' => 'Dibatalkan',
    'expired' => 'Kadaluarsa'
]);
```

### Webhook Settings
```php
define('WEBHOOK_URL', 'http://localhost/daloradius/webhook/payment-callback.php');
define('WEBHOOK_SECRET', 'simulation_secret_123');
```

## 📱 WhatsApp Integration

### Direct Messaging
```php
// Format nomor telepon ke 62...
$phone = '62' . substr($phone, 1);

// Buat URL WhatsApp
$whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
```

### Template Notifikasi
- **Payment Success**: "Pembayaran berhasil! Saldo Anda bertambah Rp X"
- **Payment Reminder**: "Jangan lupa bayar tagihan Rp X"
- **Low Balance**: "Saldo Anda rendah. Silakan top-up"
- **Invoice**: "Invoice baru tersedia. Total: Rp X"

## 🧪 Testing

### 1. Test Payment Flow
```
1. checkout.php → Buat transaksi baru
2. payment-test.php → Lihat dan konfirmasi transaksi
3. test-payment-system.php → Testing lengkap sistem
```

### 2. Test Webhook
```
1. Buat transaksi pending
2. Gunakan test webhook dengan status berbeda
3. Monitor perubahan di database
```

### 3. Test Balance Update
```
1. Buat transaksi pending
2. Konfirmasi pembayaran
3. Cek saldo user bertambah
```

## 🔒 Keamanan

### Validasi Input
- **Amount validation** - Min Rp 1.000, max Rp 1.000.000
- **Payment method validation** - Hanya metode yang diizinkan
- **Status validation** - Hanya status yang valid

### Database Security
- **SQL injection protection** - Menggunakan `escapeSimple()`
- **Input sanitization** - Validasi semua input user
- **Session management** - Cek login untuk setiap aksi

### Webhook Security
- **Secret key validation** - Validasi secret untuk webhook
- **Method validation** - Hanya POST request yang diterima
- **Data validation** - Validasi data yang diterima

## 📊 Monitoring

### Admin Dashboard
- **Statistik transaksi** - Total, pendapatan, pending, success
- **Transaksi terbaru** - Monitoring real-time
- **Semua transaksi** - Search dan filter
- **Status management** - Update status transaksi

### Logging System
- **Payment logs** - Semua aktivitas payment tersimpan
- **Error tracking** - Log error untuk debugging
- **Audit trail** - Tracking perubahan status

## 🚨 Troubleshooting

### Common Issues

#### 1. Database Connection Error
```
Error: Database connection failed
Solution: Cek konfigurasi database di library/DB.php
```

#### 2. Table Not Found
```
Error: Table 'user_balance' doesn't exist
Solution: Import database_payment_setup.sql
```

#### 3. Permission Denied
```
Error: Access denied for user
Solution: Cek username dan password database
```

#### 4. Webhook Not Working
```
Error: Webhook callback failed
Solution: Cek URL webhook dan secret key
```

### Debug Mode
```php
// Aktifkan debug di config
define('DEBUG_MODE', true);

// Cek log file
tail -f logs/payment_simulation.log
```

## 🔄 Upgrade ke Real Payment

### Dari Simulasi ke Real
1. **Daftar merchant** - GoPay, DANA, ShopeePay
2. **Dapatkan API key** - Server key, client key
3. **Update konfigurasi** - Ganti ke real credentials
4. **Test integration** - Pastikan webhook berfungsi

### Real Payment Requirements
- **Business registration** - SIUP, NPWP
- **Bank account** - Rekening perusahaan
- **API documentation** - Baca dokumentasi resmi
- **Security compliance** - PCI DSS untuk kartu kredit

## 📚 Referensi

### Dokumentasi
- [daloRADIUS Documentation](https://github.com/lirantal/daloradius)
- [FreeRADIUS Documentation](https://freeradius.org/documentation/)
- [PHP MySQLi Documentation](https://www.php.net/manual/en/book.mysqli.php)

### Payment Gateway APIs
- [Midtrans Documentation](https://docs.midtrans.com/)
- [Xendit Documentation](https://docs.xendit.co/)
- [DOKU Documentation](https://docs.doku.com/)

### Security Best Practices
- [OWASP SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [Webhook Security](https://webhooks.fyi/security)

## 🤝 Kontribusi

### Cara Kontribusi
1. Fork repository
2. Buat feature branch
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

### Coding Standards
- **PHP PSR-4** - Autoloading standard
- **MySQL naming** - snake_case untuk database
- **HTML semantic** - Gunakan tag yang sesuai
- **CSS BEM** - Block Element Modifier

## 📄 License

Project ini menggunakan license yang sama dengan daloRADIUS.

## 🆘 Support

### Getting Help
- **Documentation** - Baca README ini dengan teliti
- **Issues** - Buat issue di GitHub repository
- **Community** - Bergabung dengan komunitas daloRADIUS

### Contact
- **Developer** - [Your Name]
- **Email** - [your.email@example.com]
- **GitHub** - [your-github-username]

---

**Note**: Sistem ini adalah simulasi untuk testing dan pembelajaran. Untuk production dengan uang real, gunakan payment gateway resmi atau daftar merchant.

**Happy Coding! 🚀** 