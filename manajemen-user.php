<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: user management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .user-management-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .user-management-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .user-management-title {
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
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .data-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }
        .data-table th.sortable {
            cursor: pointer;
            position: relative;
        }
        .data-table th.sortable:hover {
            background: #e9ecef;
        }
        .data-table th.sortable::after {
            content: "↕";
            position: absolute;
            right: 8px;
            color: #999;
        }
        .data-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            vertical-align: middle;
        }
        .data-table tr:hover {
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
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .access-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #009688;
            font-size: 12px;
        }
        .access-badge i {
            font-size: 10px;
        }
        .action-buttons {
            display: flex;
            gap: 4px;
        }
        .btn-view {
            background: #6c757d;
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
        .btn-view:hover {
            background: #5a6268;
        }
        .btn-edit {
            background: #007bff;
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
            background: #0056b3;
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
        .bulk-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }
        .btn-bulk {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-bulk-delete {
            background: #dc3545;
            color: white;
        }
        .btn-bulk-delete:hover {
            background: #c82333;
        }
        .btn-bulk-edit {
            background: #28a745;
            color: white;
        }
                 .btn-bulk-edit:hover {
             background: #218838;
         }
         
         /* Modal Styles */
         .modal {
             position: fixed;
             z-index: 1000;
             left: 0;
             top: 0;
             width: 100%;
             height: 100%;
             background-color: rgba(0,0,0,0.5);
             display: flex;
             align-items: center;
             justify-content: center;
         }
         
         .modal-content {
             background-color: white;
             border-radius: 8px;
             box-shadow: 0 4px 20px rgba(0,0,0,0.3);
             animation: modalFadeIn 0.3s ease-out;
         }
         
         @keyframes modalFadeIn {
             from {
                 opacity: 0;
                 transform: scale(0.9) translateY(-20px);
             }
             to {
                 opacity: 1;
                 transform: scale(1) translateY(0);
             }
         }
         
         .modal-header {
             display: flex;
             justify-content: space-between;
             align-items: center;
             padding: 16px 20px;
             border-bottom: 1px solid #e0e0e0;
         }
         
         .modal-close {
             background: none;
             border: none;
             font-size: 24px;
             cursor: pointer;
             color: #999;
             padding: 0;
             width: 24px;
             height: 24px;
             display: flex;
             align-items: center;
             justify-content: center;
         }
         
         .modal-close:hover {
             color: #333;
         }
         
         .modal-body {
             padding: 20px;
         }
         
         .modal-footer {
             padding: 16px 20px;
             border-top: 1px solid #e0e0e0;
         }
         
         /* Report Summary Styles */
         .report-summary {
             display: flex;
             flex-direction: column;
             gap: 12px;
         }
         
         .report-item {
             display: flex;
             align-items: center;
             gap: 12px;
             padding: 8px 0;
             border-bottom: 1px solid #f0f0f0;
         }
         
         .report-item:last-child {
             border-bottom: none;
         }
         
         .report-item i {
             width: 16px;
             text-align: center;
         }
         
         .report-label {
             flex: 1;
             font-weight: 500;
             color: #333;
         }
         
         .report-value {
             font-weight: bold;
             color: #009688;
             min-width: 100px;
             text-align: right;
         }
     </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Manajemen User</h2>
        </div>
        
        <div class="user-management-container">
            <div class="user-management-section">
                <div class="user-management-title">Manajemen User</div>
                
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <button class="btn-bulk btn-bulk-edit" onclick="bulkEdit()">
                        <i class="fas fa-edit"></i> Edit Selected
                    </button>
                    <button class="btn-bulk btn-bulk-delete" onclick="bulkDelete()">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
                
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
                        <input type="text" class="search-input" id="searchInput" placeholder="Search users..." onkeyup="searchUsers()">
                    </div>
                </div>
                
                <!-- User Data Table -->
                <table class="data-table" id="userTable">
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <input type="checkbox" class="checkbox-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th class="sortable" onclick="sortTable(1)">Username</th>
                            <th class="sortable" onclick="sortTable(2)">Status Akun</th>
                            <th class="sortable" onclick="sortTable(3)">Posisi User</th>
                            <th class="sortable" onclick="sortTable(4)">Izin Akses</th>
                            <th class="sortable" onclick="sortTable(5)">Nomor HP</th>
                            <th class="sortable" onclick="sortTable(6)">Balance</th>
                            <th>Summary</th>
                            <th class="sortable" onclick="sortTable(8)">Terakhir Login</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="11" style="text-align: center; padding: 40px 20px; color: #666;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                    <i class="fas fa-users" style="font-size: 48px; color: #ddd;"></i>
                                    <div>
                                        <h3 style="margin: 0; color: #999; font-weight: normal;">No Users Found</h3>
                                        <p style="margin: 8px 0 0 0; color: #bbb; font-size: 14px;">No user data available yet. Users will appear here once added to the system.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination-info">
                    <div class="pagination-text">
                        Showing 1 to 5 of 5 entries
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-trash" style="color: #dc3545;"></i>
                    <span style="font-weight: bold;">Hapus Item</span>
                </div>
                <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color: #dc3545; font-weight: bold; margin: 0;">Anda yakin ingin menghapus data ini ?</p>
            </div>
            <div class="modal-footer" style="display: flex; align-items: center; gap: 12px;">
                <button class="btn-delete" onclick="confirmDelete()">Hapus</button>
                <span style="color: #666;">Or</span>
                <a href="#" onclick="hideDeleteModal()" style="color: #007bff; text-decoration: none;">Batal</a>
            </div>
        </div>
    </div>

    <!-- Report Summary Modal -->
    <div id="reportModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chart-bar" style="color: #009688;"></i>
                    <span style="font-weight: bold;">Ringkasan Laporan - [ <span id="reportUsername"></span> ]</span>
                </div>
                <button class="modal-close" onclick="hideReportModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="report-summary">
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Income Harian:</span>
                        <span class="report-value">Rp. 900.000</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Income Bulan Ini:</span>
                        <span class="report-value">Rp. 900.000</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Fee Hari ini:</span>
                        <span class="report-value">Rp. 71</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Fee Bulan Ini:</span>
                        <span class="report-value">Rp. 71</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Stok Voucher:</span>
                        <span class="report-value">0 pcs</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Voucher Expired:</span>
                        <span class="report-value">0 pcs</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-plus" style="color: #007bff;"></i>
                        <span class="report-label">Voucher Bulan ini:</span>
                        <span class="report-value">0 pcs</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Jumlah Pelanggan - HOTSPOT:</span>
                        <span class="report-value">0 user</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Jumlah Pelanggan - PPP:</span>
                        <span class="report-value">245 user</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Pelanggan Isolir - HOTSPOT:</span>
                        <span class="report-value">0 user</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-check" style="color: #28a745;"></i>
                        <span class="report-label">Pelanggan Isolir - PPP:</span>
                        <span class="report-value">25 user</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-plus" style="color: #007bff;"></i>
                        <span class="report-label">Pelanggan Bulan ini - HOTSPOT:</span>
                        <span class="report-value">0 user</span>
                    </div>
                    <div class="report-item">
                        <i class="fas fa-plus" style="color: #007bff;"></i>
                        <span class="report-label">Pelanggan Bulan ini - PPP:</span>
                        <span class="report-value">0 user</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        // Table functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            bulkActions.style.display = selectAll.checked ? 'flex' : 'none';
        }
        
        function changeEntries() {
            const entriesSelect = document.getElementById('entriesSelect');
            console.log('Entries per page changed to:', entriesSelect.value);
            // Here you would typically reload the table with new pagination
        }
        
        function searchUsers() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTable tbody tr');
            
            tableRows.forEach(row => {
                const username = row.cells[1].textContent.toLowerCase();
                const status = row.cells[2].textContent.toLowerCase();
                const position = row.cells[3].textContent.toLowerCase();
                
                if (username.includes(searchTerm) || status.includes(searchTerm) || position.includes(searchTerm)) {
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
        
        // User actions
        function viewDetails(username) {
            console.log('Viewing details for user:', username);
            document.getElementById('reportUsername').textContent = username;
            document.getElementById('reportModal').style.display = 'flex';
        }
        
        function editUser(username) {
            console.log('Editing user:', username);
            alert(`Editing user: ${username}`);
        }
        
        function deleteUser(username) {
            window.currentDeleteUser = username;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        
        // Modal functions
        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            window.currentDeleteUser = null;
        }
        
        function hideReportModal() {
            document.getElementById('reportModal').style.display = 'none';
        }
        
        function confirmDelete() {
            const username = window.currentDeleteUser;
            if (username) {
                console.log('Deleting user:', username);
                alert(`User ${username} deleted successfully!`);
                hideDeleteModal();
            }
        }
        
        function bulkEdit() {
            const selectedUsers = getSelectedUsers();
            if (selectedUsers.length === 0) {
                alert('Please select users to edit');
                return;
            }
            console.log('Bulk editing users:', selectedUsers);
            alert(`Bulk editing ${selectedUsers.length} users`);
        }
        
        function bulkDelete() {
            const selectedUsers = getSelectedUsers();
            if (selectedUsers.length === 0) {
                alert('Please select users to delete');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedUsers.length} users?`)) {
                console.log('Bulk deleting users:', selectedUsers);
                alert(`${selectedUsers.length} users deleted successfully!`);
            }
        }
        
        function getSelectedUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            return Array.from(checkboxes).map(checkbox => checkbox.value);
        }
        
        // Add event listeners for individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                    const selectAll = document.getElementById('selectAll');
                    
                    // Update select all checkbox
                    selectAll.checked = checkedBoxes.length === checkboxes.length;
                    selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
                    
                    // Show/hide bulk actions
                    bulkActions.style.display = checkedBoxes.length > 0 ? 'flex' : 'none';
                });
            });
            
            // Close modals when clicking outside
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                        if (modal.id === 'deleteModal') {
                            window.currentDeleteUser = null;
                        }
                    }
                });
            });
            
            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const deleteModal = document.getElementById('deleteModal');
                    const reportModal = document.getElementById('reportModal');
                    
                    if (deleteModal.style.display === 'flex') {
                        hideDeleteModal();
                    }
                    if (reportModal.style.display === 'flex') {
                        hideReportModal();
                    }
                }
            });
        });
    </script>
</body>
</html>
