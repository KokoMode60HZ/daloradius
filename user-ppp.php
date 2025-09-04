<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: user ppp";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User PPP - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .stats-container { display:flex; gap:16px; margin-bottom:24px; }
        .stat-card { background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,.1); flex:1; display:flex; align-items:center; gap:12px; }
        .stat-icon { width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; color:#fff; }
        .stat-icon.blue { background:#007bff; }
        .stat-icon.green { background:#28a745; }
        .stat-icon.orange { background:#fd7e14; }
        .stat-icon.red { background:#dc3545; }
        .stat-content h3 { margin:0; font-size:24px; font-weight:bold; color:#333; }
        .stat-content p { margin:0; color:#666; font-size:14px; }
        .user-container { max-width: 1400px; margin: 0 auto; }
        .user-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .title { font-size:24px; font-weight:bold; color:#333; margin-bottom:16px; border-bottom:2px solid #009688; padding-bottom:12px; }
        .table-controls { display:flex; justify-content:space-between; align-items:center; margin:8px 0 16px 0; }
        .entries-select { padding:6px 12px; border:1px solid #ddd; border-radius:4px; }
        .search-input { padding:6px 12px; border:1px solid #ddd; border-radius:4px; width:220px; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:#f8f9fa; color:#333; font-weight:600; text-align:left; padding:12px 8px; border-bottom:2px solid #e0e0e0; white-space:nowrap; }
        tbody td { padding:12px 8px; border-bottom:1px solid #eee; vertical-align:middle; }
        tbody tr:hover { background:#f9fbfd; }
        .checkbox-cell { width:40px; text-align:center; }
        .customer-id { color:#007bff; text-decoration:none; }
        .customer-id:hover { text-decoration:underline; }
        .service-type { padding:4px 8px; border-radius:4px; font-size:12px; font-weight:bold; }
        .service-type.pre { background:#d4edda; color:#155724; }
        .service-type.post { background:#cce5ff; color:#004085; }
        .profile-name { font-weight:500; }
        .ip-address { font-family:monospace; font-size:13px; color:#666; }
        .renew-date { font-family:monospace; font-size:13px; color:#666; }
        .due-date { color:#007bff; text-decoration:none; font-family:monospace; font-size:13px; }
        .due-date:hover { text-decoration:underline; }
        .owner { color:#666; font-style:italic; }
        .renew-print { display:flex; gap:4px; }
        .btn-renew { background:#007bff; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px; }
        .btn-print { background:#28a745; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px; }
        .actions { display:flex; gap:6px; }
        .btn-edit{ background:#17a2b8; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
        .btn-view{ background:#6c757d; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; }
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
            <h2 style="margin:0;color:#333;">Manajemen Pelanggan PPP</h2>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <h3>1</h3>
                    <p>REGISTRASI BULAN INI</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>59</h3>
                    <p>RENEWAL BULAN INI</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>59</h3>
                    <p>PELANGGAN ISOLIR</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-content">
                    <h3>0</h3>
                    <p>AKUN DISABLE</p>
                </div>
            </div>
        </div>

        <div class="user-container">
            <div class="user-section">
                <div class="title">Manajemen Pelanggan</div>

                <button class="btn-add">
                    <i class="fas fa-plus"></i> Tambah User PPP
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
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari pelanggan..." onkeyup="filterRows()">
                    </div>
                </div>

                <table id="userTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Tipe Service</th>
                            <th>Paket Langganan</th>
                            <th>IP Address</th>
                            <th>Diperpanjang</th>
                            <th>Jatuh Tempo</th>
                            <th>Owner Data</th>
                            <th>Renew | Print</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">383nn</a></td>
                            <td><i class="fas fa-info-circle"></i> Muliadi Wonokoyo</td>
                            <td><span class="service-type pre">PRE PPPOE</span></td>
                            <td class="profile-name">PAKET-110rb</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-09-04 07:16:39</td>
                            <td><a href="#" class="due-date">2025-10-03 23:59:59</a></td>
                            <td class="owner">root</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">248128172</a></td>
                            <td><i class="fas fa-info-circle"></i> Fauzan</td>
                            <td><span class="service-type post">POST PPPOE</span></td>
                            <td class="profile-name">Paket-130rb</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-09-03 21:35:07</td>
                            <td><a href="#" class="due-date">2025-10-02 23:59:59</a></td>
                            <td class="owner">user</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">254473874924</a></td>
                            <td><i class="fas fa-info-circle"></i> Banana adi rt 7</td>
                            <td><span class="service-type pre">PRE PPPOE</span></td>
                            <td class="profile-name">PAKET-165rb</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-09-02 15:22:18</td>
                            <td><a href="#" class="due-date">2025-10-01 23:59:59</a></td>
                            <td class="owner">root</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-004</a></td>
                            <td><i class="fas fa-info-circle"></i> Ahmad Rizki</td>
                            <td><span class="service-type post">POST PPPOE</span></td>
                            <td class="profile-name">PAKET-220rb</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-09-01 10:45:30</td>
                            <td><a href="#" class="due-date">2025-09-30 23:59:59</a></td>
                            <td class="owner">user</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-005</a></td>
                            <td><i class="fas fa-info-circle"></i> Siti Nurhaliza</td>
                            <td><span class="service-type pre">PRE PPPOE</span></td>
                            <td class="profile-name">PAKET-80-MBPS</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-30 14:20:15</td>
                            <td><a href="#" class="due-date">2025-09-29 23:59:59</a></td>
                            <td class="owner">root</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-006</a></td>
                            <td><i class="fas fa-info-circle"></i> Budi Santoso</td>
                            <td><span class="service-type post">POST PPPOE</span></td>
                            <td class="profile-name">Family</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-28 09:15:42</td>
                            <td><a href="#" class="due-date">2025-09-27 23:59:59</a></td>
                            <td class="owner">user</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-007</a></td>
                            <td><i class="fas fa-info-circle"></i> Dewi Lestari</td>
                            <td><span class="service-type pre">PRE PPPOE</span></td>
                            <td class="profile-name">PAKET-FAMILY</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-25 16:30:20</td>
                            <td><a href="#" class="due-date">2025-09-24 23:59:59</a></td>
                            <td class="owner">root</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-008</a></td>
                            <td><i class="fas fa-info-circle"></i> Eko Prasetyo</td>
                            <td><span class="service-type post">POST PPPOE</span></td>
                            <td class="profile-name">Paket-Medium</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-22 11:45:33</td>
                            <td><a href="#" class="due-date">2025-09-21 23:59:59</a></td>
                            <td class="owner">user</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-009</a></td>
                            <td><i class="fas fa-info-circle"></i> Fitri Handayani</td>
                            <td><span class="service-type pre">PRE PPPOE</span></td>
                            <td class="profile-name">Paket-Gold</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-20 08:12:55</td>
                            <td><a href="#" class="due-date">2025-09-19 23:59:59</a></td>
                            <td class="owner">root</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox" class="row-check"></td>
                            <td><a href="#" class="customer-id">USR-010</a></td>
                            <td><i class="fas fa-info-circle"></i> Guntur Pratama</td>
                            <td><span class="service-type post">POST PPPOE</span></td>
                            <td class="profile-name">PAKET-165rb</td>
                            <td class="ip-address">Automatic</td>
                            <td class="renew-date">2025-08-18 13:25:40</td>
                            <td><a href="#" class="due-date">2025-09-17 23:59:59</a></td>
                            <td class="owner">user</td>
                            <td>
                                <div class="renew-print">
                                    <button class="btn-renew"><i class="fas fa-bolt"></i></button>
                                    <button class="btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-view"><i class="fas fa-file-alt"></i></button>
                                    <button class="btn-del"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <div>Showing 1 to 10 of 448 entries</div>
                    <div>
                        <button class="page-btn" disabled>Previous</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn">5</button>
                        <span>...</span>
                        <button class="page-btn">45</button>
                        <button class="page-btn">Next</button>
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
            document.querySelectorAll('#userTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
