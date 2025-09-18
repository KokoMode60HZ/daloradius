<?php
    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include_once('library/config_read.php');
    $log = "visited page: active tickets";
    include('include/config/logging.php');
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar-new.php'; ?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px 48px;">
    <div class="dashboard-content" style="display:flex;gap:32px;">
        <div class="dashboard-main" style="flex:1;">
            <div class="dashboard-header" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h2 style="margin:0;">Manajemen Tiket</h2>
                    <div style="color:#666;">Kelola daftar tiket</div>
                </div>
                <div style="position:relative;">
                    <button id="btn-menu-tiket" style="display:inline-flex;align-items:center;gap:8px;background:#28a745;color:#fff;padding:10px 16px;border-radius:6px;border:none;cursor:pointer;">
                        <i class="fas fa-bars"></i> DAFTAR TIKET AKTIF <i class="fas fa-caret-down"></i>
                    </button>
                    <div id="menu-tiket" style="display:none;position:absolute;right:0;top:44px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;min-width:240px;box-shadow:0 4px 16px rgba(0,0,0,0.08);overflow:hidden;z-index:10;">
                        <a href="javascript:void(0);" id="open-buat-tiket" style="display:flex;gap:10px;align-items:center;padding:10px 12px;color:#333;text-decoration:none;">
                            <span style="width:16px;text-align:center;">✶</span> Kirim Tiket Ke Pelanggan
                        </a>
                        <a href="javascript:void(0);" id="open-tutup-terpilih" style="display:flex;gap:10px;align-items:center;padding:10px 12px;color:#333;text-decoration:none;border-top:1px solid #f1f1f1;">
                            <span style="width:16px;text-align:center;">✶</span> Tutup Yang Dipilih
                        </a>
                        <a href="javascript:void(0);" id="open-hapus-terpilih" style="display:flex;gap:10px;align-items:center;padding:10px 12px;color:#333;text-decoration:none;border-top:1px solid #f1f1f1;">
                            <span style="width:16px;text-align:center;">✶</span> Hapus Yang Dipilih
                        </a>
                    </div>
                </div>
            </div>

            <div style="margin:8px 0 12px 0;color:#444;">
                <i class="fas fa-exclamation-triangle" style="color:#f4b400;margin-right:6px;"></i>
                Tiket aktif akan otomatis ditutup setelah 7 hari sejak update terakhir
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
                                <th>Update Terakhir</th>
                                <th>Owner Data</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="11" style="text-align:center;padding:18px;">No data available in table</td>
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

<!-- Modals (same as semua-tiket) -->
<div id="modal-buat-tiket" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:8px;min-width:520px;padding:18px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h3 style="margin:0;"><i class="fas fa-pen"></i> Buat Tiket</h3>
            <button data-close="modal-buat-tiket" style="background:none;border:none;font-size:20px;color:#999;cursor:pointer;">&times;</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <div style="font-weight:bold;margin-bottom:6px;">! Pelanggan</div>
                <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    <option>- Pilih Pelanggan -</option>
                </select>
            </div>
            <div>
                <div style="font-weight:bold;margin-bottom:6px;">! Departement</div>
                <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    <option>- Pilih Departement -</option>
                </select>
            </div>
            <div>
                <div style="font-weight:bold;margin-bottom:6px;">! Prioritas</div>
                <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    <option>Normal</option>
                    <option>High</option>
                    <option>Low</option>
                </select>
            </div>
            <div>
                <div style="font-weight:bold;margin-bottom:6px;">! Judul</div>
                <input type="text" placeholder="Judul Pesan" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <div style="font-weight:bold;margin-bottom:6px;">! Tulis Pesan</div>
                <textarea placeholder="Write your message" style="width:100%;height:120px;padding:8px;border:1px solid #ddd;border-radius:4px;"></textarea>
            </div>
            <div>
                <button style="background:#007bff;color:#fff;border:none;padding:10px 16px;border-radius:4px;">Buat Tiket</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-tutup" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:8px;min-width:420px;padding:18px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h3 style="margin:0;"><i class="fas fa-times"></i> Tutup Tiket</h3>
            <button data-close="modal-tutup" style="background:none;border:none;font-size:20px;color:#999;cursor:pointer;">&times;</button>
        </div>
        <div style="color:#e53935;font-weight:bold;margin-bottom:12px;">Tiket yang dipilih akan ditutup, lanjutkan ?</div>
        <div>
            <button style="background:#1e88e5;color:#fff;border:none;padding:8px 12px;border-radius:4px;">Tutup Tiket</button>
            <a href="javascript:void(0);" data-close="modal-tutup" style="margin-left:8px;">Batal</a>
        </div>
    </div>
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
var btnMenu = document.getElementById('btn-menu-tiket');
if (btnMenu) {
    btnMenu.addEventListener('click', function() {
        var m = document.getElementById('menu-tiket');
        if (!m) return;
        m.style.display = (m.style.display === 'none' || m.style.display === '') ? 'block' : 'none';
    });
}
var openBuat = document.getElementById('open-buat-tiket');
if (openBuat) openBuat.addEventListener('click', function(){
    document.getElementById('modal-buat-tiket').style.display = 'block';
    document.getElementById('menu-tiket').style.display = 'none';
});
var openTutup = document.getElementById('open-tutup-terpilih');
if (openTutup) openTutup.addEventListener('click', function(){
    document.getElementById('modal-tutup').style.display = 'block';
    document.getElementById('menu-tiket').style.display = 'none';
});
var openHapus = document.getElementById('open-hapus-terpilih');
if (openHapus) openHapus.addEventListener('click', function(){
    document.getElementById('modal-hapus').style.display = 'block';
    document.getElementById('menu-tiket').style.display = 'none';
});
var closeButtons = document.querySelectorAll('[data-close]');
if (closeButtons) closeButtons.forEach(function(btn){
    btn.addEventListener('click', function(){
        var id = btn.getAttribute('data-close');
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
});
document.addEventListener('click', function(e){
    var menu = document.getElementById('menu-tiket');
    var btn = document.getElementById('btn-menu-tiket');
    if (!menu || !btn) return;
    if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.style.display = 'none';
    }
});
</script>
