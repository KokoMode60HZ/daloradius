<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar-new.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Called Station - LJN Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .main-content {
            margin-left: 220px;
            margin-top: 48px;
            padding: 20px;
            min-height: calc(100vh - 48px);
        }
        
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .server-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .server-dropdown-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 500;
        }
        
        .server-dropdown-btn:hover {
            background: #218838;
        }
        
        .server-dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 6px;
            overflow: hidden;
            top: 100%;
            left: 0;
            margin-top: 5px;
        }
        
        .server-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        
        .server-dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        
        .info-section {
            background: #e8f4fd;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .info-section h4 {
            margin: 0 0 10px 0;
            color: #0066cc;
            font-size: 16px;
        }
        
        .info-section p {
            margin: 0;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .data-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-controls {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .show-entries {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }
        
        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table-footer {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e0e0e0;
        }
        
        .pagination {
            display: flex;
            gap: 5px;
        }
        
        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .pagination button:hover:not(:disabled) {
            background: #f8f9fa;
        }
        
        .pagination button:disabled {
            background: #f8f9fa;
            color: #999;
            cursor: not-allowed;
        }
        
        /* Modal Styles */
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
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .close {
            color: #999;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
        }
        
        .close:hover {
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group label.required::after {
            content: " *";
            color: red;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .command-instructions {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .command-instructions strong {
            color: #333;
            font-weight: bold;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: right;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .delete-modal .modal-content {
            max-width: 400px;
        }
        
        .delete-modal .modal-body {
            text-align: center;
            padding: 30px 20px;
        }
        
        .delete-modal .modal-body h4 {
            color: #dc3545;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .delete-modal .modal-footer {
            text-align: center;
            padding: 20px;
        }
        
        .delete-modal .btn-danger {
            margin-right: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="page-header">
            <div class="server-dropdown">
                <button class="server-dropdown-btn" onclick="toggleServerDropdown()">
                    <i class="fas fa-bars"></i>
                    HOTSPOT & PPPOE SERVER
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="server-dropdown-content" id="serverDropdown">
                    <a href="#" onclick="showAddModal()">
                        <i class="fas fa-plus"></i> Tambah Called Station
                    </a>
                    <a href="#" onclick="showDeleteModal()">
                        <i class="fas fa-trash"></i> Hapus yang dipilih
                    </a>
                </div>
            </div>
            
            <div class="info-section">
                <h4>INFO</h4>
                <p>Tambahkan Nama Server Hotspot atau Nama Server PPPoE disini<br>
                Sehingga voucher atau pelanggan bisa ditentukan hanya bisa login melalui server hotspot atau server pppoe yang ditentukan</p>
            </div>
        </div>
        
        <div class="data-table-container">
            <div class="table-controls">
                <div class="show-entries">
                    <span>Show</span>
                    <select id="entriesPerPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-box">
                    <span>Search:</span>
                    <input type="text" id="searchInput" placeholder="Search...">
                </div>
            </div>
            
            <table class="data-table" id="calledStationTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Called Station <i class="fas fa-sort"></i></th>
                        <th>Router [NAS] <i class="fas fa-sort"></i></th>
                        <th>Tipe <i class="fas fa-sort"></i></th>
                        <th>Bisa Diakses Oleh <i class="fas fa-sort"></i></th>
                        <th>Aksi <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="no-data">No data available in table</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="table-footer">
                <div>Showing 0 to 0 of 0 entries</div>
                <div class="pagination">
                    <button disabled>Previous</button>
                    <button disabled>Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Called Station Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Called Station</h3>
                <button class="close" onclick="closeAddModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="form-group">
                        <label for="serverName" class="required">Nama Server | Service</label>
                        <input type="text" id="serverName" name="serverName" value="Hs-Server1" required>
                        <div class="command-instructions">
                            <strong>CARA MELIHAT NAMA SERVER HOTSPOT ATAU PPPOE DI MIKROTIK COPY PASTE YANG PERINTAH DIBAWAH KE MIKROTIK (TEXT TEBAL)</strong><br><br>
                            <strong>HOTSPOT (NAME) :</strong> /ip hotspot print brief<br>
                            <strong>PPPOE (SERVICE NAME) :</strong> /interface pppoe-server server print brief
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="serverType">Tipe</label>
                        <select id="serverType" name="serverType">
                            <option value="HOTSPOT">HOTSPOT</option>
                            <option value="PPPOE">PPPOE</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="routerNas" class="required">Router</label>
                        <select id="routerNas" name="routerNas" required>
                            <option value="">- Pilih Router (NAS) -</option>
                            <option value="router1">Router 1</option>
                            <option value="router2">Router 2</option>
                            <option value="router3">Router 3</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="accessBy">Bisa Diakses Oleh</label>
                        <select id="accessBy" name="accessBy">
                            <option value="all">Semua User</option>
                            <option value="specific">User Tertentu</option>
                            <option value="group">Group Tertentu</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="addCalledStation()">Tambah Called Station</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-trash"></i> Hapus Item</h3>
                <button class="close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <h4>Hapus data-data yang dipilih ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
                <span style="margin-left: 10px;">Or <a href="#" onclick="closeDeleteModal()" style="color: #007bff; text-decoration: underline;">Batal</a></span>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        // Server Dropdown Toggle
        function toggleServerDropdown() {
            const dropdown = document.getElementById('serverDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            const dropdown = document.getElementById('serverDropdown');
            if (!event.target.matches('.server-dropdown-btn') && !event.target.closest('.server-dropdown-content')) {
                dropdown.style.display = 'none';
            }
        }

        // Add Modal Functions
        function showAddModal() {
            document.getElementById('addModal').style.display = 'block';
            document.getElementById('serverDropdown').style.display = 'none';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function addCalledStation() {
            const form = document.getElementById('addForm');
            const formData = new FormData(form);
            
            // Here you would typically send the data to a PHP script
            console.log('Adding called station:', Object.fromEntries(formData));
            
            // For demo purposes, just close the modal
            alert('Called Station berhasil ditambahkan!');
            closeAddModal();
        }

        // Delete Modal Functions
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('serverDropdown').style.display = 'none';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function confirmDelete() {
            // Here you would typically send delete request to PHP script
            console.log('Deleting selected items');
            
            // For demo purposes, just close the modal
            alert('Data berhasil dihapus!');
            closeDeleteModal();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === addModal) {
                closeAddModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Table functionality
        $(document).ready(function() {
            // Initialize DataTable if needed
            // $('#calledStationTable').DataTable();
            
            // Select all checkbox functionality
            $('#selectAll').change(function() {
                $('tbody input[type="checkbox"]').prop('checked', this.checked);
            });
            
            // Search functionality
            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#calledStationTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Entries per page functionality
            $('#entriesPerPage').change(function() {
                const entries = $(this).val();
                console.log('Showing', entries, 'entries per page');
                // Implement pagination logic here
            });
        });
    </script>
</body>
</html>
