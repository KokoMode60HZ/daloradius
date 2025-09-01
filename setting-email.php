<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: ";
    include('include/config/logging.php');
?>

<?php include 'includes/sidebar.php'; ?>

<div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
  <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <h1 style="margin:0;font-size:1.8em;font-weight:bold;color:#333;">Setting Notifikasi Email</h1>
  </div>
  
  <div class="setting-content" style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
    
    <!-- Port yang didukung section -->
    <div class="setting-section" style="margin-bottom:32px;">
      <label class="setting-label" style="display:block;font-weight:bold;color:#333;margin-bottom:12px;font-size:1.1em;">
        Port yang didukung
      </label>
      <div class="port-display" style="background:#f5f5f5;border:1px solid #e0e0e0;border-radius:8px;padding:16px;color:#666;font-family:monospace;font-size:1.1em;">
        465 | 587
      </div>
    </div>
    
    <!-- Server SMTP section -->
    <div class="setting-section" style="margin-bottom:32px;">
      <label class="setting-label" style="display:block;font-weight:bold;color:#333;margin-bottom:12px;font-size:1.1em;">
        Server SMTP
      </label>
      <select class="smtp-select" style="width:100%;padding:16px;border:1px solid #e0e0e0;border-radius:8px;background:#fff;font-size:1.1em;color:#333;cursor:pointer;">
        <option value="" disabled selected>- Pilih Server SMTP [DISABLED] -</option>
        <option value="gmail">Gmail -- https://mail.google.com</option>
        <option value="custom">Server SMTP Kostum</option>
      </select>
    </div>
    
    <!-- Action buttons -->
    <div class="setting-actions" style="display:flex;align-items:center;gap:16px;margin-top:40px;">
      <button class="btn-save" style="background:#1976d2;color:#fff;border:none;padding:14px 28px;border-radius:8px;font-size:1.1em;font-weight:bold;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);transition:background 0.2s;">
        Simpan Perubahan
      </button>
      <span class="or-text" style="color:#666;font-size:1em;">atau</span>
      <a href="index.php" class="btn-back" style="color:#1976d2;text-decoration:none;font-size:1em;font-weight:500;">Kembali</a>
    </div>
    
  </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
    <?php include 'page-footer.php'; ?>
</div>

<script>
// Add functionality for the SMTP server selection
document.querySelector('.smtp-select').addEventListener('change', function() {
    const selectedValue = this.value;
    if (selectedValue === 'gmail') {
        // Handle Gmail selection
        console.log('Gmail SMTP selected');
    } else if (selectedValue === 'custom') {
        // Handle custom SMTP selection
        console.log('Custom SMTP selected');
    }
});

// Add functionality for the save button
document.querySelector('.btn-save').addEventListener('click', function() {
    const smtpServer = document.querySelector('.smtp-select').value;
    if (smtpServer) {
        // Here you would typically send the data to the server
        alert('Pengaturan berhasil disimpan!');
    } else {
        alert('Silakan pilih server SMTP terlebih dahulu.');
    }
});
</script>
</body>
</html>
