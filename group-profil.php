<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: group profile";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Grup Profil - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .group-container { max-width: 1400px; margin: 0 auto; }
        .group-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .title { font-size:24px; font-weight:bold; color:#333; margin-bottom:16px; border-bottom:2px solid #009688; padding-bottom:12px; }
        .info { background:#f8f9fa; border:1px solid #e0e0e0; border-radius:8px; padding:16px; margin-bottom:16px; }
        .info b { color:#1976d2; }
        .table-controls { display:flex; justify-content:space-between; align-items:center; margin:8px 0 16px 0; }
        .entries-select { padding:6px 12px; border:1px solid #ddd; border-radius:4px; }
        .search-input { padding:6px 12px; border:1px solid #ddd; border-radius:4px; width:220px; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:#f8f9fa; color:#333; font-weight:600; text-align:left; padding:12px 8px; border-bottom:2px solid #e0e0e0; white-space:nowrap; }
        tbody td { padding:12px 8px; border-bottom:1px solid #eee; vertical-align:middle; }
        tbody tr:hover { background:#f9fbfd; }
        .checkbox-cell { width:40px; text-align:center; }
        .mono { font-family:monospace; font-size:13px; color:#666; }
        .owner { color:#666; font-style:italic; }
        .actions { display:flex; gap:6px; }
        .btn-edit{ background:#17a2b8; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
        .btn-del{ background:#fd7e14; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
        .pagination { display:flex; justify-content:space-between; align-items:center; padding-top:12px; }
        .page-btn{ padding:6px 10px; border:1px solid #ddd; background:#fff; border-radius:4px; }
        .page-btn.active{ background:#009688; color:#fff; border-color:#009688; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <h2 style="margin:0;color:#333;">Manajemen Grup Profil</h2>
        </div>

        <div class="group-container">
            <div class="group-section">
                <div class="title">Manajemen Grup</div>

                <div class="info">
                    <ul style="margin:0 0 0 18px; padding:0; color:#555; line-height:1.5;">
                        <li>Gunakan tool <b>EXPORT KE ROUTER</b> jika ingin menggunakan grup profil yang sama pada router lain yang berbeda</li>
                        <li>Jika ada perubahan pada grup profil, silahkan <b>EXPORT ULANG</b> ke router/nas yang terkait</li>
                    </ul>
                </div>

                <div class="table-controls">
                    <div>
                        Show
                        <select class="entries-select">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        entries
                    </div>
                    <div>
                        <span>Search:</span>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari grup..." onkeyup="filterRows()">
                    </div>
                </div>

                <table id="groupTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                            <th>Nama Grup</th>
                            <th>Tipe</th>
                            <th>Parent Pool</th>
                            <th>Modul</th>
                            <th>IP Lokal</th>
                            <th>IP Pertama</th>
                            <th>IP Terakhir</th>
                            <th>Router [ NAS ]</th>
                            <th>Owner Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>LJN-Berat</td>
                            <td>PPP</td>
                            <td>NONE</td>
                            <td>sql-ippool</td>
                            <td class="mono">10.11.4.10</td>
                            <td class="mono">10.11.4.2</td>
                            <td class="mono">10.11.5.254</td>
                            <td>CCR</td>
                            <td class="owner">root</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>LJN-Sedang</td>
                            <td>PPP</td>
                            <td>NONE</td>
                            <td>sql-ippool</td>
                            <td class="mono">10.10.5.5</td>
                            <td class="mono">10.10.4.2</td>
                            <td class="mono">10.10.5.254</td>
                            <td>CCR</td>
                            <td class="owner">root</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>LJN-Ringan</td>
                            <td>PPP</td>
                            <td>NONE</td>
                            <td>sql-ippool</td>
                            <td class="mono">10.9.5.10</td>
                            <td class="mono">10.9.4.2</td>
                            <td class="mono">10.9.6.254</td>
                            <td>4011</td>
                            <td class="owner">root</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <div>Showing 1 to 3 of 3 entries</div>
                    <div>
                        <button class="page-btn" disabled>Previous</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn" disabled>Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        function toggleAll(){
            const all = document.getElementById('selectAll');
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = all.checked);
        }
        function filterRows(){
            const term = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#groupTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        }
    </script>
</body>
</html>


