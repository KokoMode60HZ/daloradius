<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: profil bandwidth";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Bandwidth - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .bandwidth-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .bandwidth-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .bandwidth-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
            border-bottom: 2px solid #009688;
            padding-bottom: 12px;
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
        .bandwidth-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .bandwidth-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }
        .bandwidth-table th.sortable {
            cursor: pointer;
            position: relative;
        }
        .bandwidth-table th.sortable:hover {
            background: #e9ecef;
        }
        .bandwidth-table th.sortable::after {
            content: "↕";
            position: absolute;
            right: 8px;
            color: #999;
        }
        .bandwidth-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            vertical-align: middle;
        }
        .bandwidth-table tr:hover {
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
        .bandwidth-name {
            font-weight: 500;
            color: #333;
        }
        .speed-value {
            font-family: monospace;
            font-size: 13px;
            color: #666;
        }
        .speed-unit {
            color: #999;
            font-size: 12px;
        }
        .owner-data {
            color: #666;
            font-style: italic;
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
        .add-bandwidth-btn {
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
        .add-bandwidth-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Profil Bandwidth</h2>
        </div>
        
        <div class="bandwidth-container">
            <div class="bandwidth-section">
                <div class="bandwidth-title">Profil Bandwidth</div>
                
                <!-- Add Bandwidth Button -->
                <button class="add-bandwidth-btn" onclick="addBandwidth()">
                    <i class="fas fa-plus"></i>
                    Tambah Profil Bandwidth
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
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari bandwidth..." onkeyup="searchBandwidth()">
                    </div>
                </div>
                
                <!-- Bandwidth Table -->
                <table class="bandwidth-table" id="bandwidthTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th class="sortable" onclick="sortTable(1)">Nama Bandwidth</th>
                            <th class="sortable" onclick="sortTable(2)">Upload (Min | Max)</th>
                            <th class="sortable" onclick="sortTable(3)">Download (Min | Max)</th>
                            <th class="sortable" onclick="sortTable(4)">Owner Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="1">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw-100Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">80250 <span class="speed-unit">Kbps</span> | 100280 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">80250 <span class="speed-unit">Kbps</span> | 100768 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('1')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('1')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="2">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw - 50Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">40125 <span class="speed-unit">Kbps</span> | 50140 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">40125 <span class="speed-unit">Kbps</span> | 50384 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('2')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('2')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="3">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw4-UpTo30Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">24075 <span class="speed-unit">Kbps</span> | 30084 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">24075 <span class="speed-unit">Kbps</span> | 30230 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('3')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('3')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="4">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw5-20Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">16050 <span class="speed-unit">Kbps</span> | 20056 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">16050 <span class="speed-unit">Kbps</span> | 20154 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('4')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('4')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="5">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw1-10Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">8025 <span class="speed-unit">Kbps</span> | 10028 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">8025 <span class="speed-unit">Kbps</span> | 10077 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('5')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('5')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="6">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw-Family</span>
                            </td>
                            <td>
                                <span class="speed-value">4012 <span class="speed-unit">Kbps</span> | 5014 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">4012 <span class="speed-unit">Kbps</span> | 5038 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('6')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('6')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="7">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw3-20Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">16050 <span class="speed-unit">Kbps</span> | 20056 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">16050 <span class="speed-unit">Kbps</span> | 20154 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('7')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('7')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input bandwidth-checkbox" value="8">
                            </td>
                            <td>
                                <span class="bandwidth-name">Bw2-15Mbps</span>
                            </td>
                            <td>
                                <span class="speed-value">12037 <span class="speed-unit">Kbps</span> | 15042 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="speed-value">12037 <span class="speed-unit">Kbps</span> | 15115 <span class="speed-unit">Kbps</span></span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editBandwidth('8')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteBandwidth('8')">
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
                        Showing 1 to 8 of 8 entries
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
            const checkboxes = document.querySelectorAll('.bandwidth-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }
        
        function changeEntries() {
            const entriesSelect = document.getElementById('entriesSelect');
            console.log('Entries per page changed to:', entriesSelect.value);
            // Here you would typically reload the table with new pagination
        }
        
        function searchBandwidth() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#bandwidthTable tbody tr');
            
            tableRows.forEach(row => {
                const bandwidthName = row.cells[1].textContent.toLowerCase();
                
                if (bandwidthName.includes(searchTerm)) {
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
        
        // Bandwidth actions
        function addBandwidth() {
            console.log('Adding new bandwidth profile');
            alert('Fitur tambah profil bandwidth akan diimplementasikan');
        }
        
        function editBandwidth(bandwidthId) {
            console.log('Editing bandwidth:', bandwidthId);
            alert(`Edit profil bandwidth ID: ${bandwidthId}`);
        }
        
        function deleteBandwidth(bandwidthId) {
            if (confirm(`Apakah Anda yakin ingin menghapus profil bandwidth ID "${bandwidthId}"?`)) {
                console.log('Deleting bandwidth:', bandwidthId);
                alert(`Profil bandwidth ID "${bandwidthId}" berhasil dihapus!`);
            }
        }
        
        // Add event listeners for individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.bandwidth-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.bandwidth-checkbox:checked');
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
