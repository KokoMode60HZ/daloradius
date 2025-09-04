<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: profil ppp";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil PPP - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .ppp-container { max-width: 1400px; margin: 0 auto; }
        .ppp-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .title { font-size:24px; font-weight:bold; color:#333; margin-bottom:16px; border-bottom:2px solid #009688; padding-bottom:12px; }
        .table-controls { display:flex; justify-content:space-between; align-items:center; margin:8px 0 16px 0; }
        .entries-select { padding:6px 12px; border:1px solid #ddd; border-radius:4px; }
        .search-input { padding:6px 12px; border:1px solid #ddd; border-radius:4px; width:220px; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:#f8f9fa; color:#333; font-weight:600; text-align:left; padding:12px 8px; border-bottom:2px solid #e0e0e0; white-space:nowrap; }
        tbody td { padding:12px 8px; border-bottom:1px solid #eee; vertical-align:middle; }
        tbody tr:hover { background:#f9fbfd; }
        .checkbox-cell { width:40px; text-align:center; }
        .profile-name { display:flex; align-items:center; gap:8px; }
        .profile-icon { color:#dc3545; }
        .price { font-family:monospace; font-size:13px; color:#28a745; font-weight:bold; }
        .owner { color:#666; font-style:italic; }
        .actions { display:flex; gap:6px; }
        .btn-edit{ background:#17a2b8; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
        .btn-del{ background:#fd7e14; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
        .pagination { display:flex; justify-content:space-between; align-items:center; padding-top:12px; }
        .page-btn{ padding:6px 10px; border:1px solid #ddd; background:#fff; border-radius:4px; }
        .page-btn.active{ background:#009688; color:#fff; border-color:#009688; }
        .btn-add { background:#28a745; color:#fff; border:none; padding:8px 16px; border-radius:4px; cursor:pointer; margin-bottom:16px; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <h2 style="margin:0;color:#333;">Manajemen Paket PPP</h2>
        </div>

        <div class="ppp-container">
            <div class="ppp-section">
                <div class="title">Manajemen Paket</div>

                <button class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Profil PPP
                </button>

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
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari profil..." onkeyup="filterRows()">
                    </div>
                </div>

                <table id="pppTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                            <th>Nama Profil</th>
                            <th>Grup Profil</th>
                            <th>Bandwidth</th>
                            <th>Harga Modal</th>
                            <th>Harga Jual</th>
                            <th>Masa Aktif</th>
                            <th>Shared Users</th>
                            <th>Owner Data</th>
                            <th>VCR | Customer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Gold
                                </div>
                            </td>
                            <td>LJN-Berat</td>
                            <td>Bw-100Mbps</td>
                            <td class="price">Rp. 330.000,00</td>
                            <td class="price">Rp. 360.000,00</td>
                            <td>1 Bulan</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 5</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Silver
                                </div>
                            </td>
                            <td>LJN-Sedang</td>
                            <td>Bw-50Mbps</td>
                            <td class="price">Rp. 193.693,69</td>
                            <td class="price">Rp. 193.693,69</td>
                            <td>1 Bulan</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 12</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Bronze
                                </div>
                            </td>
                            <td>LJN-Ringan</td>
                            <td>Bw-20Mbps</td>
                            <td class="price">Rp. 0,00</td>
                            <td class="price">Rp. 0,00</td>
                            <td>Unlimited</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 20</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Basic
                                </div>
                            </td>
                            <td>LJN-Ringan</td>
                            <td>Bw-10Mbps</td>
                            <td class="price">Rp. 116.000,00</td>
                            <td class="price">Rp. 116.071,00</td>
                            <td>1 Bulan</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 35</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Corporate
                                </div>
                            </td>
                            <td>LJN-Berat</td>
                            <td>Bw-200Mbps</td>
                            <td class="price">Rp. 2.000.000,00</td>
                            <td class="price">Rp. 2.000.000,00</td>
                            <td>1 Bulan</td>
                            <td>Multiple Device</td>
                            <td class="owner">root</td>
                            <td>0 | 3</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Gaming
                                </div>
                            </td>
                            <td>LJN-Sedang</td>
                            <td>Bw-75Mbps</td>
                            <td class="price">Rp. 200.000,00</td>
                            <td class="price">Rp. 200.000,00</td>
                            <td>1 Bulan</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 8</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Student
                                </div>
                            </td>
                            <td>LJN-Ringan</td>
                            <td>Bw-15Mbps</td>
                            <td class="price">Rp. 150.000,00</td>
                            <td class="price">Rp. 150.000,00</td>
                            <td>1 Bulan</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 50</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td>
                                <div class="profile-name">
                                    <i class="fas fa-eye-slash profile-icon"></i>
                                    PPP-Premium
                                </div>
                            </td>
                            <td>LJN-Berat</td>
                            <td>Bw-300Mbps</td>
                            <td class="price">Rp. 100.000,00</td>
                            <td class="price">Rp. 100.000,00</td>
                            <td>1 Menit</td>
                            <td>Single Device</td>
                            <td class="owner">root</td>
                            <td>0 | 1</td>
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
                    <div>Showing 1 to 8 of 8 entries</div>
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
            document.querySelectorAll('#pppTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
