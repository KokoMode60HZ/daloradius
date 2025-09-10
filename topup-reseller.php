<?php
include("library/checklogin.php");
include("library/opendb.php");
include("library/config_read.php");
include("library/closedb.php");
include("includes/header.php");
include("includes/sidebar.php");

// Get filter parameters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$reseller = isset($_GET['reseller']) ? $_GET['reseller'] : '';

// Format dates for display
$start_date_formatted = date('M d Y', strtotime($start_date));
$end_date_formatted = date('M d Y', strtotime($end_date));

// Mock data for demonstration
$active_amount = 0;
$pending_amount = 0;

// Empty topup data - will be populated from database later
$topup_data = [];

$total_entries = 0;
$current_page = 1;
$entries_per_page = 10;
?>

<div style="margin-left: 220px; margin-top: 48px; padding: 20px;">
    <!-- Header Section -->
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 24px; color: #333;">LAPORAN TOPUP</h1>
        <p style="margin: 5px 0; color: #666;">Periode: <?php echo $start_date_formatted; ?> - <?php echo $end_date_formatted; ?></p>
        
        <!-- Action Buttons -->
        <div style="margin: 15px 0;">
            <span style="font-weight: bold; margin-right: 10px;">ACTION:</span>
            <button onclick="showFilterModal()" style="background: #007bff; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-th"></i>
            </button>
            <button style="background: #28a745; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-print"></i>
            </button>
            <button style="background: #dc3545; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <!-- Summary Cards -->
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="background: #28a745; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">ACTIVE (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($active_amount, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <div style="background: #fd7e14; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">PENDING (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($pending_amount, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Display -->
    <?php if ($reseller): ?>
    <div style="background: #e9ecef; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        <strong>Filter Aktif:</strong>
        <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Reseller: <?php echo htmlspecialchars($reseller); ?></span>
        <a href="topup-reseller.php" style="color: #dc3545; text-decoration: none; margin-left: 10px;">[Hapus Filter]</a>
    </div>
    <?php endif; ?>

    <!-- Table Controls -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <label>Show </label>
            <select id="entriesPerPage" onchange="changeEntriesPerPage()" style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="10" <?php echo $entries_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $entries_per_page == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $entries_per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $entries_per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            <label> entries</label>
        </div>
        <div>
            <label>Search: </label>
            <input type="text" id="searchInput" placeholder="Search..." style="padding: 5px; border: 1px solid #ddd; border-radius: 4px; width: 200px;" onkeyup="filterTable()">
        </div>
    </div>

    <!-- Data Table -->
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <table id="topupTable" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Invoice <i class="fas fa-sort" onclick="sortTable(1)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Reseller <i class="fas fa-sort" onclick="sortTable(2)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Email <i class="fas fa-sort" onclick="sortTable(3)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Jumlah Topup <i class="fas fa-sort" onclick="sortTable(4)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Status Topup <i class="fas fa-sort" onclick="sortTable(5)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Saldo Awal <i class="fas fa-sort" onclick="sortTable(6)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Saldo Akhir <i class="fas fa-sort" onclick="sortTable(7)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tanggal Topup <i class="fas fa-sort" onclick="sortTable(8)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tanggal Bayar <i class="fas fa-sort" onclick="sortTable(9)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Channel <i class="fas fa-sort" onclick="sortTable(10)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Admin <i class="fas fa-sort" onclick="sortTable(11)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($topup_data)): ?>
                <tr>
                    <td colspan="13" style="padding: 40px; text-align: center; color: #6c757d; font-style: italic;">
                        No data available in table
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($topup_data as $row): ?>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['invoice']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['reseller']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['email']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">Rp. <?php echo number_format($row['jumlah_topup'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <?php 
                        $status_class = $row['status_topup'] == 'ACTIVE' ? 'background: #28a745; color: white;' : 'background: #fd7e14; color: white;';
                        ?>
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; <?php echo $status_class; ?>">
                            <?php echo $row['status_topup']; ?>
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">Rp. <?php echo number_format($row['saldo_awal'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">Rp. <?php echo number_format($row['saldo_akhir'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['tanggal_topup']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['tanggal_bayar']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['channel']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['admin']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <button style="background: #007bff; color: white; border: none; padding: 4px 8px; margin-right: 5px; border-radius: 4px; cursor: pointer;" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button style="background: #fd7e14; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
        <div style="color: #6c757d;">
            Showing 0 to 0 of 0 entries
        </div>
        <div>
            <button style="background: #6c757d; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;" disabled>Previous</button>
            <button style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;" disabled>Next</button>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; min-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">Filter Periode</h3>
            <button onclick="hideFilterModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Dari Tanggal</label>
            <input type="date" id="startDate" value="<?php echo $start_date; ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Sampai Tanggal</label>
            <input type="date" id="endDate" value="<?php echo $end_date; ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <button onclick="applyFilters()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%;">
            Terapkan Filter
        </button>
    </div>
</div>

<script>
function showFilterModal() {
    document.getElementById('filterModal').style.display = 'block';
}

function hideFilterModal() {
    document.getElementById('filterModal').style.display = 'none';
}

function applyFilters() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    
    // Build URL with parameters
    var url = 'topup-reseller.php';
    var params = [];
    
    if (startDate) {
        params.push('start_date=' + encodeURIComponent(startDate));
    }
    if (endDate) {
        params.push('end_date=' + encodeURIComponent(endDate));
    }
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    // Navigate to the page with new filters
    window.location.href = url;
}

function changeEntriesPerPage() {
    var entriesPerPage = document.getElementById('entriesPerPage').value;
    // Implement pagination logic here
    console.log('Changing entries per page to:', entriesPerPage);
}

function filterTable() {
    var input = document.getElementById('searchInput');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('topupTable');
    var tr = table.getElementsByTagName('tr');
    
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName('td');
        var found = false;
        
        for (var j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

function toggleAllCheckboxes() {
    var selectAll = document.getElementById('selectAll');
    var checkboxes = document.getElementsByClassName('row-checkbox');
    
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = selectAll.checked;
    }
}

function sortTable(columnIndex) {
    // Implement table sorting logic here
    console.log('Sorting by column:', columnIndex);
}

// Close modal when clicking outside
window.onclick = function(event) {
    var modal = document.getElementById('filterModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

</body>
</html>
