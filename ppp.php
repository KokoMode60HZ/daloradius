<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: ppp management";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>PPP Management - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .session-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        .session-tab {
            padding: 12px 24px;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            font-weight: bold;
            color: #666;
            border-radius: 8px 8px 0 0;
            margin-right: 4px;
        }
        .session-tab.active {
            background: #009688;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .action-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        .action-btn.blue { background: #1e88e5; }
        .action-btn.green { background: #43a047; }
        .action-btn.red { background: #e53935; }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .entries-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-control input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .session-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .session-table th {
            background: #009688;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        .session-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
        }
        .session-table tr:hover {
            background: #f9f9f9;
        }
        .username-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .username-icon {
            color: #009688;
            font-size: 14px;
        }
        .action-btn-small {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: #e53935;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .pagination-info {
            color: #666;
        }
        .pagination-controls {
            display: flex;
            gap: 8px;
        }
        .pagination-btn {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .pagination-btn.active {
            background: #009688;
            color: white;
            border-color: #009688;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .btn-delete {
            background: #009688;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-cancel {
            background: #f5f5f5;
            color: #666;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .tab-content {
            display: block;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="dashboard-page" style="margin-left:240px;margin-top:56px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Session Management</h2>
        </div>
        
        <div class="dashboard-content">
            <!-- Session Tabs -->
            <div class="session-tabs">
                <button class="session-tab active" onclick="switchTab('active')">Session Aktif</button>
                <button class="session-tab" onclick="switchTab('offline')">Pelanggan Offline</button>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="action-btn blue" title="Group Actions">
                    <i class="fas fa-users"></i>
                </button>
                <button class="action-btn green" title="Export Data">
                    <i class="fas fa-file-excel"></i>
                </button>
                <button class="action-btn red" title="Delete Selected" onclick="showDeleteModal()">
                    <i class="fas fa-ban"></i>
                </button>
            </div>

            <!-- Table Controls -->
            <div class="table-controls">
                <div class="entries-control">
                    <span>Show</span>
                    <select style="padding: 4px 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-control">
                    <span>Search:</span>
                    <input type="text" placeholder="Search sessions...">
                </div>
            </div>

            <!-- Session Table (Active Sessions) -->
            <div id="active-sessions" class="tab-content">
                <table class="session-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                            <th>Username</th>
                            <th>Router [NAS]</th>
                            <th>Calling Station</th>
                            <th>IP Address</th>
                            <th>Waktu Mulai</th>
                            <th>Owner Data</th>
                            <th>Upload</th>
                            <th>Download</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- No data available yet -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing 0 to 0 of 0 entries
                    </div>
                    <div class="pagination-controls">
                        <!-- No pagination controls needed when no data -->
                    </div>
                </div>
            </div>

            <!-- Offline Customers Table -->
            <div id="offline-customers" class="tab-content" style="display:none;">
                <table class="session-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAllOffline" onchange="toggleSelectAllOffline()"></th>
                            <th>Status Session</th>
                            <th>Status Akun</th>
                            <th>ID Pelanggan</th>
                            <th>Username</th>
                            <th>Tipe Service</th>
                            <th>Paket Langganan</th>
                            <th>Jatuh Tempo</th>
                            <th>Owner Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- No offline customer data available yet -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing 0 to 0 of 0 entries
                    </div>
                    <div class="pagination-controls">
                        <!-- No pagination controls needed when no data -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-trash"></i>
                    Hapus Item
                </div>
                <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
            </div>
            <div style="margin-bottom: 20px;">
                Hapus data-data yang dipilih ?
            </div>
            <div class="modal-actions">
                <button class="btn-delete" onclick="deleteSelected()">Hapus</button>
                <button class="btn-cancel" onclick="hideDeleteModal()">Atau Batal</button>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:240px;width:calc(100% - 240px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        function switchTab(tab) {
            // Remove active class from all tabs
            document.querySelectorAll('.session-tab').forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            event.target.classList.add('active');
            
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show the appropriate tab content
            if (tab === 'active') {
                document.getElementById('active-sessions').style.display = 'block';
            } else if (tab === 'offline') {
                document.getElementById('offline-customers').style.display = 'block';
            }
            
            console.log('Switched to tab:', tab);
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('#active-sessions .row-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function toggleSelectAllOffline() {
            const selectAllOffline = document.getElementById('selectAllOffline');
            const checkboxes = document.querySelectorAll('#offline-customers .row-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllOffline.checked;
            });
        }

        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function deleteSelected() {
            const selectedRows = document.querySelectorAll('.row-checkbox:checked');
            if (selectedRows.length > 0) {
                alert('Deleting ' + selectedRows.length + ' selected sessions');
                hideDeleteModal();
            } else {
                alert('Please select at least one session to delete');
            }
        }

        function disconnectSession(username) {
            if (confirm('Disconnect session for ' + username + '?')) {
                alert('Session disconnected for ' + username);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                hideDeleteModal();
            }
        }
    </script>
</body>
</html>
