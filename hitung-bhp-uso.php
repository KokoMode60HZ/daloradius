<?php
    include 'includes/header.php';
    include 'includes/sidebar-new.php';
?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
    <div style="background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.07);max-width:900px;margin:auto;">
        <h2 style="font-weight:bold;font-size:1.4em;margin-bottom:8px;">Laporan BHP | USO | KSO</h2>
        <div style="font-size:0.95em;color:#666;margin-bottom:18px;">
            <em>Generated at : --------- -, ---- --, --:--</em>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:18px;">
            <button style="background:#4caf50;color:#fff;border:none;border-radius:4px;padding:8px 18px;cursor:pointer;font-weight:bold;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-calculator"></i> Calculator
            </button>
            <button style="background:#2196f3;color:#fff;border:none;border-radius:4px;padding:8px 18px;cursor:pointer;font-weight:bold;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <form>
            <div style="margin-bottom:18px;">
                <label for="owner" style="font-weight:bold;">Owner Data</label>
                <select id="owner" name="owner" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                    <option>- Semua Owner -</option>
                </select>
            </div>
            <div style="display:flex;gap:18px;margin-bottom:18px;">
                <div style="flex:1;">
                    <label for="bhp" style="font-weight:bold;color:#43a047;">! BHP ( Unit % )</label>
                    <input type="number" step="0.01" id="bhp" name="bhp" value="0.5" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                </div>
                <div style="flex:1;">
                    <label for="uso" style="font-weight:bold;color:#43a047;">! USO ( Unit % )</label>
                    <input type="number" step="0.01" id="uso" name="uso" value="1.25" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                </div>
            </div>
            <div style="display:flex;gap:18px;margin-bottom:18px;">
                <div style="flex:1;">
                    <label for="kso" style="font-weight:bold;color:#43a047;">! KSO ( Unit % )</label>
                    <input type="number" step="0.01" id="kso" name="kso" value="3" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                </div>
                <div style="flex:1;">
                    <label for="ppn" style="font-weight:bold;color:#43a047;">! PPN ( Unit % )</label>
                    <input type="number" step="0.01" id="ppn" name="ppn" value="11" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <label style="font-weight:bold;color:#43a047;">! Periode Perhitungan</label>
                <div style="margin-top:6px;">
                    <input type="radio" id="bulan" name="periode" value="bulan" checked>
                    <label for="bulan" style="margin-right:24px;">BULAN</label>
                    <input type="radio" id="tahun" name="periode" value="tahun">
                    <label for="tahun">TAHUN</label>
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <label for="periode-pilih" style="font-weight:bold;color:#43a047;" id="periode-label">! Pilih Bulan</label>
                <select id="periode-pilih" name="periode-pilih" style="width:100%;padding:10px;border-radius:4px;border:1px solid #ddd;margin-top:6px;">
                    <option>Pilih Bulan...</option>
                    <option>Januari</option>
                    <option>Februari</option>
                    <option>Maret</option>
                    <option>April</option>
                    <option>Mei</option>
                    <option>Juni</option>
                    <option>Juli</option>
                    <option>Agustus</option>
                    <option>September</option>
                    <option>Oktober</option>
                    <option>November</option>
                    <option>Desember</option>
                </select>
            </div>
            <button type="submit" style="background:#43a047;color:#fff;border:none;border-radius:4px;padding:10px 24px;cursor:pointer;font-weight:bold;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-table"></i> Tampilkan Data
            </button>
        </form>
    </div>
</div>

<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
    <?php include 'page-footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bulanRadio = document.getElementById('bulan');
    const tahunRadio = document.getElementById('tahun');
    const periodeSelect = document.getElementById('periode-pilih');
    const periodeLabel = document.getElementById('periode-label');

    function setBulanOptions() {
        periodeLabel.textContent = '! Pilih Bulan';
        periodeSelect.innerHTML = `
            <option>Pilih Bulan...</option>
            <option>Januari</option>
            <option>Februari</option>
            <option>Maret</option>
            <option>April</option>
            <option>Mei</option>
            <option>Juni</option>
            <option>Juli</option>
            <option>Agustus</option>
            <option>September</option>
            <option>Oktober</option>
            <option>November</option>
            <option>Desember</option>
        `;
    }

    function setTahunOptions() {
        periodeLabel.textContent = '! Pilih Tahun';
        periodeSelect.innerHTML = `
            <option>Pilih Tahun...</option>
            <option>2022</option>
            <option>2023</option>
            <option>2024</option>
            <option>2025</option>
        `;
    }

    bulanRadio.addEventListener('change', function() {
        if (bulanRadio.checked) setBulanOptions();
    });

    tahunRadio.addEventListener('change', function() {
        if (tahunRadio.checked) setTahunOptions();
    });

    // Set initial options
    setBulanOptions();
});
</script>