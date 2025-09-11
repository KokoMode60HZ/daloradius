<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar-new.php';

// Get parameters from URL
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$tipeUser = isset($_GET['tipe_user']) ? $_GET['tipe_user'] : 'SEMUA TIPE';
$tipeService = isset($_GET['tipe_service']) ? $_GET['tipe_service'] : '';
$ownerData = isset($_GET['owner_data']) ? $_GET['owner_data'] : '';
$metodePembayaran = isset($_GET['metode_pembayaran']) ? $_GET['metode_pembayaran'] : '';

// Format dates for display
$displayStartDate = $startDate ? date('M/d/Y', strtotime($startDate)) : '';
$displayEndDate = $endDate ? date('M/d/Y', strtotime($endDate)) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Periode [LUNAS] - LJN Management</title>
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
        
        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .report-info {
            color: #666;
            margin-bottom: 5px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .action-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-grid { background: #007bff; color: white; }
        .btn-print { background: #28a745; color: white; }
        .btn-download { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        
        .summary-content h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        
        .summary-content .amount {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        
        .profit-icon { background: #28a745; }
        .fee-icon { background: #ff9800; }
        .total-icon { background: #6c757d; }
        
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
        
        .service-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            color: white;
        }
        
        .service-post { background: #007bff; }
        .service-pre { background: #28a745; }
        
        .customer-link {
            color: #007bff;
            text-decoration: none;
        }
        
        .customer-link:hover {
            text-decoration: underline;
        }
        
        .info-icon {
            color: #007bff;
            margin-left: 5px;
            cursor: pointer;
        }
        
        .action-icons {
            display: flex;
            gap: 10px;
        }
        
        .action-icon {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .icon-save { background: #28a745; color: white; }
        .icon-delete { background: #ff9800; color: white; }
        
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
        
        .pagination button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="page-header">
            <div class="report-title">LAPORAN PERIODE [LUNAS]</div>
            <div class="report-info">Periode: <?php echo $displayStartDate; ?> - <?php echo $displayEndDate; ?></div>
            <div class="report-info">### Method: <?php echo $tipeUser; ?></div>
            
            <div class="action-buttons">
                <button class="action-btn btn-grid" title="Grid View">
                    <i class="fas fa-th"></i>
                </button>
                <button class="action-btn btn-print" title="Print Report">
                    <i class="fas fa-print"></i>
                </button>
                <button class="action-btn btn-download" title="Download Report">
                    <i class="fas fa-download"></i>
                </button>
                <button class="action-btn btn-delete" title="Delete Selected">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon profit-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="summary-content">
                    <h3>PROFIT (IDR)</h3>
                    <div class="amount">13.864.000</div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon fee-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="summary-content">
                    <h3>FEE SELLER (IDR)</h3>
                    <div class="amount">284</div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon total-icon">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="summary-content">
                    <h3>TOTAL + PPN (IDR)</h3>
                    <div class="amount">15.259.998</div>
                </div>
            </div>
        </div>
        
        <div class="data-table-container">
            <div class="table-controls">
                <div class="show-entries">
                    <span>Show</span>
                    <select id="entriesPerPage">
                        <option value="10" selected>10</option>
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
            
            <table class="data-table" id="incomeTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Id <i class="fas fa-sort"></i></th>
                        <th>Invoice <i class="fas fa-sort"></i></th>
                        <th>ID Pelanggan <i class="fas fa-sort"></i></th>
                        <th>Nama <i class="fas fa-sort"></i></th>
                        <th>Tipe Service <i class="fas fa-sort"></i></th>
                        <th>Paket Langganan <i class="fas fa-sort"></i></th>
                        <th>Harga [+PPN] <i class="fas fa-sort"></i></th>
                        <th>Fee Seller <i class="fas fa-sort"></i></th>
                        <th>Tanggal Aktif <i class="fas fa-sort"></i></th>
                        <th>Owner Data <i class="fas fa-sort"></i></th>
                        <th>Aksi <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6604</td>
                        <td>INV-2025-09-001</td>
                        <td><a href="#" class="customer-link">CUST-001</a></td>
                        <td>Puri <i class="fas fa-info-circle info-icon"></i></td>
                        <td><span class="service-badge service-post">POST PPPOE</span></td>
                        <td>PAKET-110rb</td>
                        <td>Rp. 110.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/01/2025 10:30</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6603</td>
                        <td>INV-2025-09-002</td>
                        <td><a href="#" class="customer-link">CUST-002</a></td>
                        <td>Olivia <i class="fas fa-info-circle info-icon"></i></td>
                        <td><span class="service-badge service-pre">PRE PPPOE</span></td>
                        <td>Paket-130rb</td>
                        <td>Rp. 129.999,52</td>
                        <td>Rp. 71</td>
                        <td>Sep/02/2025 14:15</td>
                        <td>root</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6602</td>
                        <td>INV-2025-09-003</td>
                        <td><a href="#" class="customer-link">CUST-003</a></td>
                        <td>Ichwan Kreweh</td>
                        <td><span class="service-badge service-post">POST PPPOE</span></td>
                        <td>PAKET-165rb</td>
                        <td>Rp. 165.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/03/2025 09:45</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6601</td>
                        <td>INV-2025-09-004</td>
                        <td><a href="#" class="customer-link">CUST-004</a></td>
                        <td>Ika Yutiningsih</td>
                        <td><span class="service-badge service-pre">PRE PPPOE</span></td>
                        <td>PAKET-110rb</td>
                        <td>Rp. 110.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/04/2025 16:20</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6600</td>
                        <td>INV-2025-09-005</td>
                        <td><a href="#" class="customer-link">CUST-005</a></td>
                        <td>Sutris/chepy</td>
                        <td><span class="service-badge service-post">POST PPPOE</span></td>
                        <td>PAKET-165rb</td>
                        <td>Rp. 165.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/05/2025 11:10</td>
                        <td>root</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6599</td>
                        <td>INV-2025-09-006</td>
                        <td><a href="#" class="customer-link">CUST-006</a></td>
                        <td>Imron</td>
                        <td><span class="service-badge service-pre">PRE PPPOE</span></td>
                        <td>PAKET-110rb</td>
                        <td>Rp. 110.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/06/2025 13:25</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6598</td>
                        <td>INV-2025-09-007</td>
                        <td><a href="#" class="customer-link">CUST-007</a></td>
                        <td>Sugeng kb</td>
                        <td><span class="service-badge service-post">POST PPPOE</span></td>
                        <td>Paket-130rb</td>
                        <td>Rp. 110.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/07/2025 08:40</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6597</td>
                        <td>INV-2025-09-008</td>
                        <td><a href="#" class="customer-link">CUST-008</a></td>
                        <td>Tan Sien Ind</td>
                        <td><span class="service-badge service-pre">PRE PPPOE</span></td>
                        <td>PAKET-165rb</td>
                        <td>Rp. 165.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/08/2025 15:55</td>
                        <td>root</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6596</td>
                        <td>INV-2025-09-009</td>
                        <td><a href="#" class="customer-link">CUST-009</a></td>
                        <td>Rista</td>
                        <td><span class="service-badge service-post">POST PPPOE</span></td>
                        <td>PAKET-110rb</td>
                        <td>Rp. 110.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/09/2025 12:30</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>6595</td>
                        <td>INV-2025-09-010</td>
                        <td><a href="#" class="customer-link">CUST-010</a></td>
                        <td>Sulastri Pasar</td>
                        <td><span class="service-badge service-pre">PRE PPPOE</span></td>
                        <td>PAKET-165rb</td>
                        <td>Rp. 165.000</td>
                        <td>Rp. 0</td>
                        <td>Sep/10/2025 17:15</td>
                        <td>user</td>
                        <td>
                            <div class="action-icons">
                                <button class="action-icon icon-save" title="Save"><i class="fas fa-save"></i></button>
                                <button class="action-icon icon-delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="table-footer">
                <div>Showing 1 to 10 of 126 entries</div>
                <div class="pagination">
                    <button disabled>Previous</button>
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>4</button>
                    <button>5</button>
                    <span>...</span>
                    <button>13</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select all checkbox functionality
            $('#selectAll').change(function() {
                $('tbody input[type="checkbox"]').prop('checked', this.checked);
            });
            
            // Search functionality
            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#incomeTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Entries per page functionality
            $('#entriesPerPage').change(function() {
                const entries = $(this).val();
                console.log('Showing', entries, 'entries per page');
                // Implement pagination logic here
            });
            
            // Action button functionality
            $('.action-btn').click(function() {
                const action = $(this).find('i').attr('class');
                console.log('Action clicked:', action);
                
                if (action.includes('print')) {
                    window.print();
                } else if (action.includes('download')) {
                    alert('Download functionality would be implemented here');
                } else if (action.includes('trash')) {
                    alert('Delete functionality would be implemented here');
                }
            });
            
            // Row action buttons
            $('.action-icon').click(function() {
                const action = $(this).find('i').attr('class');
                console.log('Row action clicked:', action);
                
                if (action.includes('save')) {
                    alert('Save functionality would be implemented here');
                } else if (action.includes('trash')) {
                    alert('Delete row functionality would be implemented here');
                }
            });
        });
    </script>
</body>
</html>
