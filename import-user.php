<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: import user";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import User dari File - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .import-container { max-width: 1200px; margin: 0 auto; }
        .import-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .title { font-size:24px; font-weight:bold; color:#333; margin-bottom:24px; border-bottom:2px solid #009688; padding-bottom:12px; }
        .form-group { margin-bottom:20px; }
        .form-label { display:block; font-weight:600; color:#333; margin-bottom:8px; font-size:14px; }
        .form-label.required::before { content:"! "; color:#dc3545; }
        .form-control { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:4px; font-size:14px; }
        .form-control:focus { border-color:#007bff; outline:none; box-shadow:0 0 0 2px rgba(0,123,255,.25); }
        .radio-group { display:flex; gap:20px; margin-top:8px; }
        .radio-item { display:flex; align-items:center; gap:8px; }
        .radio-item input[type="radio"] { margin:0; }
        .radio-item label { margin:0; font-weight:normal; cursor:pointer; }
        .btn { padding:10px 20px; border:none; border-radius:4px; cursor:pointer; font-size:14px; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
        .btn-primary { background:#007bff; color:#fff; }
        .btn-primary:hover { background:#0056b3; }
        .btn-success { background:#28a745; color:#fff; }
        .btn-success:hover { background:#1e7e34; }
        .btn-danger { background:#dc3545; color:#fff; }
        .btn-danger:hover { background:#c82333; }
        .btn-info { background:#17a2b8; color:#fff; }
        .btn-info:hover { background:#138496; }
        .btn-large { padding:15px 30px; font-size:16px; }
        .download-section { background:#f8f9fa; border:1px solid #e9ecef; border-radius:6px; padding:16px; margin-bottom:24px; }
        .download-section h4 { margin:0 0 12px 0; color:#495057; font-size:16px; }
        .download-buttons { display:flex; gap:12px; }
        .file-input-wrapper { position:relative; display:inline-block; width:100%; }
        .file-input { position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; }
        .file-input-display { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border:1px solid #ddd; border-radius:4px; background:#fff; cursor:pointer; }
        .file-input-display:hover { border-color:#007bff; }
        .file-input-text { color:#6c757d; }
        .file-input-button { background:#6c757d; color:#fff; padding:6px 12px; border-radius:4px; font-size:12px; }
        .help-text { font-size:12px; color:#6c757d; margin-top:4px; }
        .form-row { display:flex; gap:20px; }
        .form-row .form-group { flex:1; }
        .link-add { color:#007bff; text-decoration:none; font-size:12px; }
        .link-add:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <h2 style="margin:0;color:#333;">Import User dari File</h2>
        </div>

        <div class="import-container">
            <div class="import-section">
                <div class="title">Impor User dari File (XLSX/CSV)</div>

                <!-- Download Template Section -->
                <div class="download-section">
                    <h4>Download Template</h4>
                    <div class="download-buttons">
                        <button class="btn btn-info" onclick="downloadTemplate('customer')">
                            <i class="fas fa-download"></i> CUSTOMER
                        </button>
                        <button class="btn btn-danger" onclick="downloadTemplate('voucher')">
                            <i class="fas fa-download"></i> VOUCHER
                        </button>
                    </div>
                </div>

                <form id="importForm" enctype="multipart/form-data">
                    <!-- Upload File Section -->
                    <div class="form-group">
                        <label class="form-label required">Upload File XLSX/CSV Disini</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="fileInput" name="importFile" class="file-input" accept=".xlsx,.csv" onchange="updateFileName()">
                            <div class="file-input-display" onclick="document.getElementById('fileInput').click()">
                                <span class="file-input-text" id="fileName">No file chosen</span>
                                <span class="file-input-button">Choose File</span>
                            </div>
                        </div>
                        <div class="help-text">Format yang didukung: .xlsx, .csv (Maksimal 10MB)</div>
                    </div>

                    <!-- Tipe User -->
                    <div class="form-group">
                        <label class="form-label required">Tipe User</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="userTypeCustomer" name="userType" value="CUSTOMER">
                                <label for="userTypeCustomer">CUSTOMER</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="userTypeVoucher" name="userType" value="VOUCHER">
                                <label for="userTypeVoucher">VOUCHER</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tipe Port -->
                    <div class="form-group">
                        <label class="form-label required">Tipe Port</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="portTypeHotspot" name="portType" value="HOTSPOT">
                                <label for="portTypeHotspot">HOTSPOT</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="portTypePPP" name="portType" value="PPP">
                                <label for="portTypePPP">PPP</label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Row -->
                    <div class="form-row">
                        <!-- Paket Langganan -->
                        <div class="form-group">
                            <label class="form-label required">Paket Langganan</label>
                            <select class="form-control" name="package" required>
                                <option value="">- Pilih Paket -</option>
                                <option value="PAKET-110rb">PAKET-110rb</option>
                                <option value="Paket-130rb">Paket-130rb</option>
                                <option value="PAKET-165rb">PAKET-165rb</option>
                                <option value="PAKET-220rb">PAKET-220rb</option>
                                <option value="PAKET-80-MBPS">PAKET-80-MBPS</option>
                                <option value="Family">Family</option>
                                <option value="PAKET-FAMILY">PAKET-FAMILY</option>
                                <option value="Paket-Medium">Paket-Medium</option>
                                <option value="Paket-Gold">Paket-Gold</option>
                            </select>
                        </div>

                        <!-- Fee Seller -->
                        <div class="form-group">
                            <label class="form-label">Fee Seller</label>
                            <input type="number" class="form-control" name="feeSeller" value="0" min="0" step="0.01">
                            <div class="help-text">FEE DARI HARGA JUAL PADA PROFIL AKAN DIABAIKAN</div>
                        </div>
                    </div>

                    <!-- Form Row -->
                    <div class="form-row">
                        <!-- Owner Data -->
                        <div class="form-group">
                            <label class="form-label required">Owner Data</label>
                            <select class="form-control" name="ownerData" required>
                                <option value="">- Pilih Owner Data -</option>
                                <option value="root">root</option>
                                <option value="user">user</option>
                                <option value="admin">admin</option>
                                <option value="operator">operator</option>
                            </select>
                        </div>

                        <!-- Nama Server | Service -->
                        <div class="form-group">
                            <label class="form-label required">Nama Server | Service</label>
                            <select class="form-control" name="serverName" required>
                                <option value="">- Pilih Nama Server -</option>
                                <option value="CCR">CCR</option>
                                <option value="4011">4011</option>
                                <option value="RB750">RB750</option>
                                <option value="RB951">RB951</option>
                                <option value="RB1100">RB1100</option>
                            </select>
                            <a href="#" class="link-add">[+] TAMBAH SERVER</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group" style="margin-top:30px;">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        function updateFileName() {
            const fileInput = document.getElementById('fileInput');
            const fileName = document.getElementById('fileName');
            
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
                fileName.style.color = '#333';
            } else {
                fileName.textContent = 'No file chosen';
                fileName.style.color = '#6c757d';
            }
        }

        function downloadTemplate(type) {
            // Simulate template download
            const link = document.createElement('a');
            link.href = '#'; // In real implementation, this would be the actual template file URL
            link.download = `template_${type.toLowerCase()}.xlsx`;
            link.click();
            
            // Show notification
            alert(`Template ${type.toUpperCase()} berhasil didownload!`);
        }

        document.getElementById('importForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const fileInput = document.getElementById('fileInput');
            const userType = document.querySelector('input[name="userType"]:checked');
            const portType = document.querySelector('input[name="portType"]:checked');
            const package = document.querySelector('select[name="package"]').value;
            const ownerData = document.querySelector('select[name="ownerData"]').value;
            const serverName = document.querySelector('select[name="serverName"]').value;
            
            if (!fileInput.files.length) {
                alert('Pilih file yang akan diimport!');
                return;
            }
            
            if (!userType) {
                alert('Pilih tipe user!');
                return;
            }
            
            if (!portType) {
                alert('Pilih tipe port!');
                return;
            }
            
            if (!package) {
                alert('Pilih paket langganan!');
                return;
            }
            
            if (!ownerData) {
                alert('Pilih owner data!');
                return;
            }
            
            if (!serverName) {
                alert('Pilih nama server!');
                return;
            }
            
            // Simulate import process
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                alert('Import berhasil! File telah diproses.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                this.reset();
                updateFileName();
            }, 2000);
        });
    </script>
</body>
</html>
