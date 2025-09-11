<?php
    include 'includes/header.php';
    include 'includes/sidebar-new.php';
?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
    <div style="background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
        <h2 style="font-weight:bold;font-size:2em;margin-bottom:18px;">PENGELUARAN</h2>
        <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;margin-bottom:24px;">
            <div style="display:flex;gap:8px;">
                <button style="background:#2196f3;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-plus"></i>
                </button>
                <button style="background:#43a047;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-search"></i>
                </button>
                <button style="background:#009688;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-calendar-alt"></i>
                </button>
                <button style="background:#ffc107;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-print"></i>
                </button>
                <button style="background:#e53935;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div style="flex:1;display:flex;gap:24px;">
                <div style="background:#43a047;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-calendar-alt" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">PERIODE (2025)</div>
                        <div style="font-size:1.2em;font-weight:bold;">Sep 01 - Sep 11</div>
                    </div>
                </div>
                <div style="background:#fbc02d;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-file-alt" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">TOTAL (IDR)</div>
                        <div style="font-size:1.2em;font-weight:bold;">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom:12px;">
            <label for="show-entries" style="margin-right:8px;">Show</label>
            <select id="show-entries" style="padding:4px 8px;border-radius:4px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span style="margin-left:8px;">entries</span>
            <span style="float:right;">
                <label for="search" style="margin-right:8px;">Search:</label>
                <input id="search" type="text" style="padding:4px 8px;border-radius:4px;border:1px solid #ccc;">
            </span>
        </div>
        <table style="width:100%;border-collapse:collapse;margin-bottom:8px;">
            <thead>
                <tr style="background:#e3f2fd;">
                    <th style="padding:10px;border-bottom:2px solid #90caf9;"><input type="checkbox"></th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Id</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Tanggal</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Tipe</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Deskripsi</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Jumlah</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Dibayar Oleh</th>
                    <th style="padding:10px;border-bottom:2px solid #90caf9;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" style="text-align:center;padding:24px;color:#888;">No data available in table</td>
                </tr>
            </tbody>
        </table>
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span>Showing 0 to 0 of 0 entries</span>
            <div>
                <button style="background:#eee;border:none;border-radius:4px;padding:6px 12px;margin-right:4px;cursor:pointer;">Previous</button>
                <button style="background:#eee;border:none;border-radius:4px;padding:6px 12px;cursor:pointer;">Next</button>
            </div>
        </div>
    </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
    <?php include 'page-footer.php'; ?>