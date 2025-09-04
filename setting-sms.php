<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: ";
    include('include/config/logging.php');
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar-new.php'; ?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
  <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <h1 style="margin:0;font-size:1.8em;font-weight:bold;color:#333;">Setting Notifikasi SMS</h1>
  </div>
  
  <div class="setting-content" style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
    
    <!-- Provider SMS API section -->
    <div class="setting-section" style="margin-bottom:32px;">
      <label class="setting-label" style="display:block;font-weight:bold;color:#333;margin-bottom:12px;font-size:1.1em;">
        Provider SMS API
      </label>
      <select class="sms-provider-select" style="width:100%;padding:16px;border:1px solid #e0e0e0;border-radius:8px;background:#fff;font-size:1.1em;color:#333;cursor:pointer;">
        <option value="" disabled selected>- Pilih Provider SMS [DISABLED] -</option>
        <option value="medansms">MedanSMS (Metakreasi) -- https://metakreasi.id</option>
        <option value="onewaysms">OneWay-SMS -- https://onewaysms.com</option>
        <option value="isms">iSMS API -- https://www.isms.com.my</option>
        <option value="nexmosms">NexmoSMS (Vonage) -- https://www.vonage.id</option>
      </select>
    </div>
    
    <!-- Action buttons -->
    <div class="setting-actions" style="display:flex;align-items:center;gap:16px;margin-top:40px;">
      <button class="btn-save" style="background:#009688;color:#fff;border:none;padding:14px 28px;border-radius:8px;font-size:1.1em;font-weight:bold;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);transition:background 0.2s;">
        Simpan Perubahan
      </button>
      <span class="or-text" style="color:#666;font-size:1em;">atau</span>
      <a href="index.php" class="btn-back" style="color:#009688;text-decoration:none;font-size:1em;font-weight:500;">Kembali</a>
    </div>
    
  </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
    <?php include 'page-footer.php'; ?>
</div>

<script>
// Add functionality for the SMS provider selection
document.querySelector('.sms-provider-select').addEventListener('change', function() {
    const selectedValue = this.value;
    if (selectedValue) {
        // Handle provider selection
        console.log('SMS Provider selected:', selectedValue);
    }
});

// Add functionality for the save button
document.querySelector('.btn-save').addEventListener('click', function() {
    const smsProvider = document.querySelector('.sms-provider-select').value;
    if (smsProvider) {
        // Here you would typically send the data to the server
        alert('Pengaturan SMS berhasil disimpan!');
    } else {
        alert('Silakan pilih provider SMS terlebih dahulu.');
    }
});
</script>
</body>
</html>
