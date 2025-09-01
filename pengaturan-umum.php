<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: general settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan Umum - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .settings-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .settings-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .settings-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
            border-bottom: 2px solid #009688;
            padding-bottom: 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-label.required::before {
            content: "! ";
            color: #e53935;
        }
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: #009688;
            box-shadow: 0 0 0 2px rgba(0,150,136,0.2);
        }
        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
            box-sizing: border-box;
        }
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            box-sizing: border-box;
        }
        .bank-accounts {
            margin-top: 16px;
        }
        .bank-account {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .bank-account-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
            font-size: 16px;
        }
        .bank-account-row {
            display: flex;
            gap: 16px;
            margin-bottom: 8px;
        }
        .bank-account-row:last-child {
            margin-bottom: 0;
        }
        .bank-account-label {
            font-weight: bold;
            color: #666;
            min-width: 120px;
            font-size: 14px;
        }
        .bank-account-value {
            color: #333;
            font-size: 14px;
        }
        .info-text {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 4px;
            padding: 12px;
            margin-top: 12px;
            font-size: 13px;
            color: #1976d2;
            line-height: 1.4;
        }
        .info-text .highlight {
            color: #009688;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .btn-save {
            background: #009688;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-save:hover {
            background: #00796b;
        }
        .btn-back {
            background: none;
            color: #009688;
            padding: 12px 24px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }
        .btn-back:hover {
            color: #00796b;
        }
        .package-extension-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .package-extension-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Pengaturan Umum</h2>
        </div>
        
        <div class="settings-container">
            <!-- General Settings Section -->
            <div class="settings-section">
                <div class="settings-title">Pengaturan Umum</div>
                
                <!-- Company Information -->
                <div class="form-group">
                    <label class="form-label required">Perusahaan</label>
                    <input type="text" class="form-input" value="Lintas Jaringan Nusantara Kantor Layanan Singosari">
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Alamat</label>
                    <textarea class="form-textarea">Dusun Sumbul RT 2 RW 8 Desa Klampok Singosari</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Nomor HP</label>
                    <input type="text" class="form-input" value="081234517346">
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Deskripsi Invoice</label>
                    <input type="text" class="form-input" value="High Speed Internet Package Service">
                </div>
                
                <!-- Bank Accounts -->
                <div class="form-group">
                    <label class="form-label">Rekening Bank</label>
                    <select class="form-select">
                        <option>2 Rekening Bank</option>
                        <option>1 Rekening Bank</option>
                        <option>3 Rekening Bank</option>
                    </select>
                    
                    <div class="bank-accounts">
                        <div class="bank-account">
                            <div class="bank-account-title">Bank 1</div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">Bank BRI</span>
                            </div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">Atas Nama:</span>
                                <span class="bank-account-value">ISMANTO</span>
                            </div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">No. Rekening:</span>
                                <span class="bank-account-value">751401002939534</span>
                            </div>
                        </div>
                        
                        <div class="bank-account">
                            <div class="bank-account-title">Bank 2</div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">BCA</span>
                            </div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">Atas Nama:</span>
                                <span class="bank-account-value">ISMANTO</span>
                            </div>
                            <div class="bank-account-row">
                                <span class="bank-account-label">No. Rekening:</span>
                                <span class="bank-account-value">3681620395</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Automatic Invoice -->
                <div class="form-group">
                    <label class="form-label">Invoice Otomatis</label>
                    <select class="form-select">
                        <option>5 HARI SEBELUM JATUH TEMPO</option>
                        <option>1 HARI SEBELUM JATUH TEMPO</option>
                        <option>3 HARI SEBELUM JATUH TEMPO</option>
                        <option>7 HARI SEBELUM JATUH TEMPO</option>
                    </select>
                    
                    <div class="info-text">
                        INVOICE BELUM BAYAR PADA DATA PELANGGAN (<span class="highlight">HOTSPOT</span> / <span class="highlight">PPP</span>) HARUS SUDAH TERBAYARKAN SEBELUM PERIODE YANG DIPILIH
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-save" onclick="saveSettings()">Simpan Perubahan</button>
                    <button class="btn-back" onclick="goBack()">Atau Kembali</button>
                </div>
            </div>
            
            <!-- Package Extension Section -->
            <div class="package-extension-section">
                <div class="package-extension-title">Disable Perpanjangan Paket Sampai</div>
                
                <div class="form-group">
                    <select class="form-select">
                        <option>BISA RENEWAL/PERPANJANGAN KAPAN SAJA</option>
                        <option>1 HARI SEBELUM JATUH TEMPO</option>
                        <option>3 HARI SEBELUM JATUH TEMPO</option>
                        <option>5 HARI SEBELUM JATUH TEMPO</option>
                        <option>7 HARI SEBELUM JATUH TEMPO</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-save" onclick="savePackageSettings()">Simpan Perubahan</button>
                    <button class="btn-back" onclick="goBack()">Atau Kembali</button>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        function saveSettings() {
            // Collect form data
            const formData = {
                company: document.querySelector('input[value*="Lintas Jaringan"]').value,
                address: document.querySelector('.form-textarea').value,
                phone: document.querySelector('input[value*="081234517346"]').value,
                invoiceDescription: document.querySelector('input[value*="High Speed Internet"]').value,
                bankAccounts: document.querySelector('select').value,
                automaticInvoice: document.querySelectorAll('select')[1].value
            };
            
            // Show success message
            alert('Pengaturan berhasil disimpan!');
            console.log('Settings saved:', formData);
        }
        
        function savePackageSettings() {
            const packageExtension = document.querySelectorAll('select')[2].value;
            
            // Show success message
            alert('Pengaturan perpanjangan paket berhasil disimpan!');
            console.log('Package extension setting saved:', packageExtension);
        }
        
        function goBack() {
            // Navigate back to previous page
            window.history.back();
        }
        
        // Add event listeners for form inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Add change event listeners to all form inputs
            const inputs = document.querySelectorAll('.form-input, .form-textarea, .form-select');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    console.log('Form field changed:', this.name || this.className, this.value);
                });
            });
        });
    </script>
</body>
</html>
