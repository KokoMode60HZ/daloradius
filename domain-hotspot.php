<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: domain hotspot management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Domain Hotspot - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .domain-hotspot-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .domain-hotspot-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .domain-hotspot-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
            border-bottom: 2px solid #009688;
            padding-bottom: 12px;
        }
        .info-box {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .info-title {
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 8px;
        }
        .info-text {
            color: #1976d2;
            font-size: 14px;
            line-height: 1.4;
        }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }
        .entries-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .entries-select {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .search-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-input {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 200px;
        }
        .search-input:focus {
            outline: none;
            border-color: #009688;
        }
        .domain-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .domain-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }
        .domain-table th.sortable {
            cursor: pointer;
            position: relative;
        }
        .domain-table th.sortable:hover {
            background: #e9ecef;
        }
        .domain-table th.sortable::after {
            content: "↕";
            position: absolute;
            right: 8px;
            color: #999;
        }
        .domain-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            vertical-align: middle;
        }
        .domain-table tr:hover {
            background: #f8f9fa;
        }
        .checkbox-cell {
            width: 40px;
            text-align: center;
        }
        .checkbox-input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .domain-name {
            font-weight: 500;
            color: #333;
        }
        .access-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .access-all {
            background: #d4edda;
            color: #155724;
        }
        .access-specific {
            background: #fff3cd;
            color: #856404;
        }
        .voucher-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .voucher-available {
            background: #d1ecf1;
            color: #0c5460;
        }
        .voucher-unavailable {
            background: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 4px;
        }
        .btn-edit {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .btn-edit:hover {
            background: #138496;
        }
        .btn-delete {
            background: #fd7e14;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .btn-delete:hover {
            background: #e8690b;
        }
        .pagination-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e0e0e0;
        }
        .pagination-text {
            color: #666;
            font-size: 14px;
        }
        .pagination-controls {
            display: flex;
            gap: 4px;
        }
        .pagination-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .pagination-btn:hover {
            background: #f8f9fa;
        }
        .pagination-btn.active {
            background: #009688;
            color: white;
            border-color: #009688;
        }
        .pagination-btn:disabled {
            background: #f8f9fa;
            color: #999;
            cursor: not-allowed;
        }
        .add-domain-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .add-domain-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Domain Hotspot</h2>
        </div>
        
        <div class="domain-hotspot-container">
            <div class="domain-hotspot-section">
                <div class="domain-hotspot-title">Domain Hotspot</div>
                
                <!-- Info Box -->
                <div class="info-box">
                    <div class="info-title">INFO :</div>
                    <div class="info-text">
                        Anda harus menambahkan URL Hotspot anda disini jika Template Voucher anda menggunakan QRCode
                    </div>
                </div>
                
                <!-- Add Domain Button -->
                <button class="add-domain-btn" onclick="addDomain()">
                    <i class="fas fa-plus"></i>
                    Tambah Domain Hotspot
                </button>
                
                <!-- Table Controls -->
                <div class="table-controls">
                    <div class="entries-control">
                        <span>Show</span>
                        <select class="entries-select" id="entriesSelect" onchange="changeEntries()">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="search-control">
                        <span>Search:</span>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari domain..." onkeyup="searchDomains()">
                    </div>
                </div>
                
                <!-- Domain Table -->
                <table class="domain-table" id="domainTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th class="sortable" onclick="sortTable(1)">Domain Hotspot</th>
                            <th class="sortable" onclick="sortTable(2)">Bisa Diakses Oleh</th>
                            <th class="sortable" onclick="sortTable(3)">e-Voucher Form</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input domain-checkbox" value="sanjaya.wifi">
                            </td>
                            <td>
                                <span class="domain-name">sanjaya.wifi</span>
                            </td>
                            <td>
                                <span class="access-badge access-all">
                                    <i class="fas fa-users"></i>
                                    Semua User
                                </span>
                            </td>
                            <td>
                                <span class="voucher-status voucher-unavailable">
                                    <i class="fas fa-times"></i>
                                    Tidak tersedia
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editDomain('sanjaya.wifi')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteDomain('sanjaya.wifi')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination-info">
                    <div class="pagination-text">
                        Showing 1 to 1 of 1 entries
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-btn" disabled>Previous</button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn" disabled>Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        // Table functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.domain-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }
        
        function changeEntries() {
            const entriesSelect = document.getElementById('entriesSelect');
            console.log('Entries per page changed to:', entriesSelect.value);
            // Here you would typically reload the table with new pagination
        }
        
        function searchDomains() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#domainTable tbody tr');
            
            tableRows.forEach(row => {
                const domainName = row.cells[1].textContent.toLowerCase();
                const accessBy = row.cells[2].textContent.toLowerCase();
                
                if (domainName.includes(searchTerm) || accessBy.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function sortTable(columnIndex) {
            console.log('Sorting column:', columnIndex);
            // Here you would implement table sorting logic
        }
        
        // Domain actions
        function addDomain() {
            console.log('Adding new domain');
            alert('Fitur tambah domain akan diimplementasikan');
        }
        
        function editDomain(domainName) {
            console.log('Editing domain:', domainName);
            alert(`Edit domain: ${domainName}`);
        }
        
        function deleteDomain(domainName) {
            if (confirm(`Apakah Anda yakin ingin menghapus domain "${domainName}"?`)) {
                console.log('Deleting domain:', domainName);
                alert(`Domain "${domainName}" berhasil dihapus!`);
            }
        }
        
        // Add event listeners for individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.domain-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.domain-checkbox:checked');
                    const selectAll = document.getElementById('selectAll');
                    
                    // Update select all checkbox
                    selectAll.checked = checkedBoxes.length === checkboxes.length;
                    selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
                });
            });
        });
    </script>
</body>
</html>
