<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: kelola data odp pop";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data ODP | POP - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .odp-pop-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .odp-pop-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .odp-pop-title {
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
        .odp-pop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .odp-pop-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }
        .odp-pop-table th.sortable {
            cursor: pointer;
            position: relative;
        }
        .odp-pop-table th.sortable:hover {
            background: #e9ecef;
        }
        .odp-pop-table th.sortable::after {
            content: "↕";
            position: absolute;
            right: 8px;
            color: #999;
        }
        .odp-pop-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            vertical-align: middle;
        }
        .odp-pop-table tr:hover {
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
        .odp-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #333;
        }
        .odp-code {
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .odp-arrow {
            color: #1976d2;
            font-size: 12px;
        }
        .area-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            background: #f8f9fa;
            color: #495057;
        }
        .coordinates {
            font-family: monospace;
            font-size: 13px;
            color: #666;
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
        .add-odp-btn {
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
        .add-odp-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Kelola Data ODP | POP</h2>
        </div>
        
        <div class="odp-pop-container">
            <div class="odp-pop-section">
                <div class="odp-pop-title">Kelola Data ODP | POP</div>
                
                <!-- Add ODP Button -->
                <button class="add-odp-btn" onclick="addODP()">
                    <i class="fas fa-plus"></i>
                    Tambah Data ODP | POP
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
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari ODP/POP..." onkeyup="searchODP()">
                    </div>
                </div>
                
                <!-- ODP POP Table -->
                <table class="odp-pop-table" id="odpPopTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th class="sortable" onclick="sortTable(1)">Nama atau Kode ODP | POP</th>
                            <th class="sortable" onclick="sortTable(2)">Area ODP | POP</th>
                            <th class="sortable" onclick="sortTable(3)">Latitude</th>
                            <th class="sortable" onclick="sortTable(4)">Longitude</th>
                            <th class="sortable" onclick="sortTable(5)">Owner Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="1">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(20)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>1 SUMBULWETAN - NURSALAM</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    SUMBULWETAN
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8632727</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6297795</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('1')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('1')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="2">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(21)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>2 KAMPUNGAN - AHMAD</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    KAMPUNGAN
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8641234</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6301234</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('2')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('2')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="3">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(22)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>3 PRODO - SITI</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    PRODO
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8652345</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6312345</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('3')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('3')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="4">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(23)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>4 KARANGAN - BUDI</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    KARANGAN
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8663456</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6323456</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('4')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('4')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="5">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(24)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>5 SUMBER - RINA</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    SUMBER
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8674567</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6334567</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('5')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('5')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="6">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(25)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>6 TAMAN - DEDI</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    TAMAN
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8685678</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6345678</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('6')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('6')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="7">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(26)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>7 KEBON - EKO</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    KEBON
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8696789</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6356789</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('7')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('7')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="8">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(27)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>8 SIDO - FANI</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    SIDO
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8707890</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6367890</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('8')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('8')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="9">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(28)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>9 GUNUNG - GITA</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    GUNUNG
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8718901</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6378901</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('9')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('9')">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input odp-checkbox" value="10">
                            </td>
                            <td>
                                <div class="odp-name">
                                    <span class="odp-code">(29)</span>
                                    <span class="odp-arrow">→</span>
                                    <span>10 LOR - HADI</span>
                                </div>
                            </td>
                            <td>
                                <span class="area-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    LOR
                                </span>
                            </td>
                            <td>
                                <span class="coordinates">-7.8729012</span>
                            </td>
                            <td>
                                <span class="coordinates">112.6389012</span>
                            </td>
                            <td>
                                <span class="owner-data">root</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editODP('10')">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                    <button class="btn-delete" onclick="deleteODP('10')">
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
                        Showing 1 to 10 of 32 entries
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-btn" disabled>Previous</button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn">4</button>
                        <button class="pagination-btn">Next</button>
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
            const checkboxes = document.querySelectorAll('.odp-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }
        
        function changeEntries() {
            const entriesSelect = document.getElementById('entriesSelect');
            console.log('Entries per page changed to:', entriesSelect.value);
            // Here you would typically reload the table with new pagination
        }
        
        function searchODP() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#odpPopTable tbody tr');
            
            tableRows.forEach(row => {
                const odpName = row.cells[1].textContent.toLowerCase();
                const area = row.cells[2].textContent.toLowerCase();
                
                if (odpName.includes(searchTerm) || area.includes(searchTerm)) {
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
        
        // ODP actions
        function addODP() {
            console.log('Adding new ODP/POP');
            alert('Fitur tambah ODP/POP akan diimplementasikan');
        }
        
        function editODP(odpId) {
            console.log('Editing ODP/POP:', odpId);
            alert(`Edit ODP/POP ID: ${odpId}`);
        }
        
        function deleteODP(odpId) {
            if (confirm(`Apakah Anda yakin ingin menghapus ODP/POP ID "${odpId}"?`)) {
                console.log('Deleting ODP/POP:', odpId);
                alert(`ODP/POP ID "${odpId}" berhasil dihapus!`);
            }
        }
        
        // Add event listeners for individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.odp-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.odp-checkbox:checked');
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
