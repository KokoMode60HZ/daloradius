<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: closed tickets";
    include('include/config/logging.php');
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar-new.php'; ?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px 48px;">
    <div class="dashboard-content" style="display:flex;gap:32px;">
        <div class="dashboard-main" style="flex:1;">
            <div class="dashboard-header" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h2 style="margin:0;">Tiket Ditutup</h2>
                    <div style="color:#666;">Daftar tiket yang sudah ditutup</div>
                </div>
                <div>
                    <button id="btn-hapus-terpilih" style="display:inline-flex;align-items:center;gap:8px;background:#e53935;color:#fff;padding:8px 14px;border-radius:6px;border:none;cursor:pointer;">
                        <i class="fas fa-trash"></i> Hapus Yang Dipilih
                    </button>
                </div>
            </div>

            <div class="dashboard-card" style="background:#fff;border-radius:12px;padding:0;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
                <div style="padding:12px 16px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        Show 
                        <select style="padding:4px 6px;border:1px solid #ddd;border-radius:4px;">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        entries
                    </div>
                    <div>
                        Search: <input type="text" style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="styled-table" style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#e0f2f1;color:#333;">
                                <th style="width:30px;"><input type="checkbox"></th>
                                <th>Id</th>
                                <th>No. Tiket</th>
                                <th>ID Pelanggan</th>
                                <th>Email</th>
                                <th>Departement</th>
                                <th>Priority & Judul</th>
                                <th>Status</th>
                                <th>Ditutup Oleh</th>
                                <th>Update Terakhir</th>
                                <th>Owner Data</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" style="text-align:center;padding:18px;">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding:12px 16px;border-top:1px solid #eee;display:flex;justify-content:space-between;color:#666;">
                    <div>Showing 0 to 0 of 0 entries</div>
                    <div>Previous &nbsp; Next</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
    <?php include 'page-footer.php'; ?>
    
</div>

<div id="modal-hapus" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:8px;min-width:420px;padding:18px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h3 style="margin:0;"><i class="fas fa-trash"></i> Hapus Item</h3>
            <button data-close="modal-hapus" style="background:none;border:none;font-size:20px;color:#999;cursor:pointer;">&times;</button>
        </div>
        <div style="color:#e53935;font-weight:bold;margin-bottom:12px;">Hapus data-data yang dipilih ?</div>
        <div>
            <button style="background:#1e88e5;color:#fff;border:none;padding:8px 12px;border-radius:4px;">Hapus</button>
            <a href="javascript:void(0);" data-close="modal-hapus" style="margin-left:8px;">Batal</a>
        </div>
    </div>
</div>

<script>
var hapusBtn = document.getElementById('btn-hapus-terpilih');
if (hapusBtn) hapusBtn.addEventListener('click', function(){
    var m = document.getElementById('modal-hapus');
    if (m) m.style.display = 'block';
});
var closeButtons = document.querySelectorAll('[data-close]');
if (closeButtons) closeButtons.forEach(function(btn){
    btn.addEventListener('click', function(){
        var id = btn.getAttribute('data-close');
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
});
</script>
