# 🚀 PANDUAN SETUP SISTEM PAYMENT GATEWAY - UNTUK GURU

## 📋 **OVERVIEW PROJECT**
Sistem Payment Gateway Simulasi yang terintegrasi dengan daloRADIUS untuk pembelajaran dan testing payment system.

**Fitur Utama:**
- 💳 Payment Gateway Simulasi (tanpa uang real)
- 📱 WhatsApp Integration
- 🔧 Admin Dashboard
- 💰 User Balance Management
- 📊 Transaction Monitoring

---

## 🛠️ **PREREQUISITES (Yang Harus Diinstall Dulu)**

### **1. XAMPP (Wajib)**
- Download dari: https://www.apachefriends.org/
- Versi: PHP 8.0+ (recommended)
- Include: Apache, MySQL, PHP

### **2. Browser Modern**
- Chrome, Firefox, Edge (versi terbaru)
- Enable JavaScript

---

## 📥 **STEP 1: DOWNLOAD & EXTRACT**

### **1. Download Project**
- Download file ZIP: `daloradius-payment-system.zip`
- Extract ke folder: `C:\xampp\htdocs\daloradius\`

### **2. Struktur Folder**
```
C:\xampp\htdocs\daloradius\
├── 📁 library/          (Core system)
├── 📁 config/           (Payment config)
├── 📁 admin/            (Admin dashboard)
├── 📁 webhook/          (Webhook handler)
├── 📄 checkout.php      (Halaman checkout)
├── 📄 payment-test.php  (Test payment)
├── 📄 payment-index.php (Dashboard payment)
└── 📄 index.php         (Dashboard utama)
```

---

## 🗄️ **STEP 2: SETUP DATABASE**

### **1. Start XAMPP**
- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**
- Pastikan status **Running** (hijau)

### **2. Buka phpMyAdmin**
- Buka browser: `http://localhost/phpmyadmin`
- Login dengan username: `root` (password kosong)

### **3. Buat Database**
```sql
-- Buat database baru
CREATE DATABASE radius;

-- Pilih database radius
USE radius;
```

### **4. Import Database daloRADIUS**
- File: `contrib/db/mysql-daloradius.sql`
- Import via phpMyAdmin → Import → Choose File → Go

### **5. Import Database Payment System**
- File: `database_payment_setup.sql`
- Import via phpMyAdmin → Import → Choose File → Go

---

## ⚙️ **STEP 3: KONFIGURASI**

### **1. Cek File Config**
- Buka: `config/payment_config.php`
- Pastikan database settings benar:
```php
define('PAYMENT_MODE', 'simulation');
define('WEBHOOK_SECRET', 'simulation_secret_123');
```

### **2. Cek Database Connection**
- File: `library/DB.php`
- Pastikan DSN string benar:
```php
DB::connect("mysql://root:@localhost:3306/radius")
```

---

## 🚀 **STEP 4: TESTING SISTEM**

### **1. Akses Dashboard**
- Buka: `http://localhost/daloradius/`
- Login dengan:
  - Username: `administrator`
  - Password: `radius`

### **2. Akses Payment System**
- Buka: `http://localhost/daloradius/payment-index.php`
- Atau dari menu utama

### **3. Test Fitur Payment**
- Buat transaksi baru di checkout
- Test konfirmasi pembayaran
- Cek update saldo user

---

## 📱 **FITUR YANG TERSEDIA**

### **💳 Payment Gateway**
- `checkout.php` - Buat transaksi baru
- `payment-test.php` - Monitor transaksi
- `test-payment-system.php` - Testing lengkap

### **🔧 Admin Panel**
- `admin/payment-dashboard.php` - Dashboard admin
- `admin-bank-accounts.php` - Kelola rekening bank

### **📱 WhatsApp Integration**
- `whatsapp-direct-test.php` - Test WhatsApp
- Template notifikasi otomatis

### **📊 Monitoring**
- Real-time transaction status
- User balance tracking
- Payment statistics

---

## 🎯 **CARA DEMO KE GURU**

### **1. Login & Dashboard**
- Tunjukkan login system
- Tunjukkan dashboard utama

### **2. Payment Flow**
- Buat transaksi baru (misal: Rp 10.000)
- Tunjukkan status "pending"
- Konfirmasi pembayaran → status "success"
- Tunjukkan saldo bertambah

### **3. Admin Features**
- Tunjukkan admin dashboard
- Tunjukkan statistik transaksi
- Tunjukkan monitoring semua user

### **4. WhatsApp Integration**
- Tunjukkan template notifikasi
- Tunjukkan direct WhatsApp link
- Tunjukkan database tracking

---

## 🔧 **TROUBLESHOOTING**

### **Error: Database Connection Failed**
```
Solution: 
1. Pastikan MySQL running di XAMPP
2. Cek username/password database
3. Cek nama database 'radius'
```

### **Error: Table Not Found**
```
Solution:
1. Import database daloRADIUS dulu
2. Import database payment system
3. Cek struktur tabel di phpMyAdmin
```

### **Error: Permission Denied**
```
Solution:
1. Pastikan folder htdocs bisa diakses
2. Cek file permissions
3. Restart XAMPP
```

### **Halaman Blank/Error 500**
```
Solution:
1. Cek error log di XAMPP
2. Pastikan PHP version compatible
3. Enable error reporting
```

---

## 📞 **SUPPORT & KONTAK**

### **Jika Ada Masalah:**
1. Cek error message dengan teliti
2. Pastikan semua step sudah benar
3. Restart XAMPP jika perlu
4. Cek file log untuk detail error

### **File Log:**
- Apache: `C:\xampp\apache\logs\error.log`
- PHP: `C:\xampp\php\logs\php_error_log`

---

## 🎉 **SELAMAT! SISTEM SUDAH SIAP**

**Status: ✅ Ready for Demo**

**Fitur yang Bisa Didemo:**
- ✅ Login & Authentication
- ✅ Payment Gateway Simulasi
- ✅ Admin Dashboard
- ✅ WhatsApp Integration
- ✅ User Balance Management
- ✅ Transaction Monitoring

**Note:** Ini adalah sistem simulasi untuk pembelajaran. Tidak ada uang real yang berpindah.

---

**Happy Demo! 🚀**

*Dibuat dengan ❤️ untuk project PKL*
