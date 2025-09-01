<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: localization settings";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lokalisasi - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .localization-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .localization-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .localization-title {
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
        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
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
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            box-sizing: border-box;
            position: relative;
        }
        .form-select:focus {
            outline: none;
            border-color: #009688;
            box-shadow: 0 0 0 2px rgba(0,150,136,0.2);
        }
        .currency-hint {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 8px;
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        .currency-hint strong {
            color: #009688;
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
        .dropdown-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Lokalisasi</h2>
        </div>
        
        <div class="localization-container">
            <div class="localization-section">
                <div class="localization-title">Lokalisasi</div>
                
                <!-- Time Zone -->
                <div class="form-group">
                    <label class="form-label">Zona Waktu</label>
                    <div style="position: relative;">
                        <select class="form-select" id="timezone">
                            <option value="+07:00 Asia/Jakarta" selected>+07:00 Asia/Jakarta</option>
                            <option value="+08:00 Asia/Singapore">+08:00 Asia/Singapore</option>
                            <option value="+08:00 Asia/Kuala_Lumpur">+08:00 Asia/Kuala_Lumpur</option>
                            <option value="+07:00 Asia/Bangkok">+07:00 Asia/Bangkok</option>
                            <option value="+09:00 Asia/Tokyo">+09:00 Asia/Tokyo</option>
                            <option value="+00:00 UTC">+00:00 UTC</option>
                            <option value="-05:00 America/New_York">-05:00 America/New_York</option>
                            <option value="+01:00 Europe/London">+01:00 Europe/London</option>
                        </select>
                        <span class="dropdown-arrow">▼</span>
                    </div>
                </div>
                
                <!-- Date Format -->
                <div class="form-group">
                    <label class="form-label">Format Tanggal</label>
                    <div style="position: relative;">
                        <select class="form-select" id="dateFormat">
                            <option value="01/09/2025" selected>01/09/2025</option>
                            <option value="2025-09-01">2025-09-01</option>
                            <option value="01-09-2025">01-09-2025</option>
                            <option value="01.09.2025">01.09.2025</option>
                            <option value="September 01, 2025">September 01, 2025</option>
                            <option value="01 Sep 2025">01 Sep 2025</option>
                        </select>
                        <span class="dropdown-arrow">▼</span>
                    </div>
                </div>
                
                <!-- Language -->
                <div class="form-group">
                    <label class="form-label">Bahasa</label>
                    <div style="position: relative;">
                        <select class="form-select" id="language">
                            <option value="Indonesia" selected>Indonesia</option>
                            <option value="English">English</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Japan">Japan</option>
                            <option value="China">China</option>
                        </select>
                        <span class="dropdown-arrow">▼</span>
                    </div>
                </div>
                
                <!-- Decimal and Thousands Separators -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Pemisah Desimal</label>
                        <input type="text" class="form-input" id="decimalSeparator" value="," maxlength="1" placeholder=",">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Pemisah Ribuan</label>
                        <input type="text" class="form-input" id="thousandsSeparator" value="." maxlength="1" placeholder=".">
                    </div>
                </div>
                
                <!-- Currency -->
                <div class="form-group">
                    <label class="form-label required">Mata Uang [Jangan gunakan Karakter Spesial]</label>
                    <input type="text" class="form-input" id="currency" value="Rp." placeholder="Rp.">
                    <div class="currency-hint">
                        <strong>Accepted formats:</strong> [USD | EUR | IDR | GBP | CAD | AUD | Rp.]
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-save" onclick="saveLocalization()">Simpan Perubahan</button>
                    <button class="btn-back" onclick="goBack()">Atau Kembali</button>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        function saveLocalization() {
            // Collect form data
            const formData = {
                timezone: document.getElementById('timezone').value,
                dateFormat: document.getElementById('dateFormat').value,
                language: document.getElementById('language').value,
                decimalSeparator: document.getElementById('decimalSeparator').value,
                thousandsSeparator: document.getElementById('thousandsSeparator').value,
                currency: document.getElementById('currency').value
            };
            
            // Validate required fields
            if (!formData.decimalSeparator || !formData.thousandsSeparator || !formData.currency) {
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return;
            }
            
            // Validate currency format (no special characters)
            const currencyRegex = /^[A-Za-z0-9\s\.]+$/;
            if (!currencyRegex.test(formData.currency)) {
                alert('Mata uang tidak boleh mengandung karakter spesial!');
                return;
            }
            
            // Show success message
            alert('Pengaturan lokalisasi berhasil disimpan!');
            console.log('Localization settings saved:', formData);
        }
        
        function goBack() {
            // Navigate back to previous page
            window.history.back();
        }
        
        // Add event listeners for form inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Add change event listeners to all form inputs
            const inputs = document.querySelectorAll('.form-input, .form-select');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    console.log('Form field changed:', this.id, this.value);
                });
            });
            
            // Add input validation for separators
            const decimalSeparator = document.getElementById('decimalSeparator');
            const thousandsSeparator = document.getElementById('thousandsSeparator');
            
            decimalSeparator.addEventListener('input', function() {
                if (this.value.length > 1) {
                    this.value = this.value.slice(0, 1);
                }
            });
            
            thousandsSeparator.addEventListener('input', function() {
                if (this.value.length > 1) {
                    this.value = this.value.slice(0, 1);
                }
            });
            
            // Prevent same separators
            decimalSeparator.addEventListener('change', function() {
                if (this.value === thousandsSeparator.value) {
                    alert('Pemisah desimal dan ribuan tidak boleh sama!');
                    this.focus();
                }
            });
            
            thousandsSeparator.addEventListener('change', function() {
                if (this.value === decimalSeparator.value) {
                    alert('Pemisah desimal dan ribuan tidak boleh sama!');
                    this.focus();
                }
            });
        });
    </script>
</body>
</html>
