<?php
include("library/checklogin.php");
include("library/opendb.php");
include("library/config_read.php");
include("library/closedb.php");
include("includes/header.php");
include("includes/sidebar.php");

// Get filter parameters
$periode = isset($_GET['periode']) ? $_GET['periode'] : date('M/d/Y');
$method = isset($_GET['method']) ? $_GET['method'] : 'ALL USER-TYPE';
$tipe_user = isset($_GET['tipe_user']) ? $_GET['tipe_user'] : '';
$tipe_service = isset($_GET['tipe_service']) ? $_GET['tipe_service'] : '';
$owner_data = isset($_GET['owner_data']) ? $_GET['owner_data'] : '';
$metode_pembayaran = isset($_GET['metode_pembayaran']) ? $_GET['metode_pembayaran'] : '';

// Mock data for demonstration
$profit = 500000;
$fee_seller = 0;
$total_ppn = 550000;

// Mock billing data
$billing_data = [
    ['id' => 6592, 'invoice' => 'INV-992514070176', 'customer_id' => '2251210506', 'nama' => 'Imron', 'tipe_service' => 'POST PPPOE', 'paket' => 'PAKET-110rb', 'harga' => 110000, 'fee_seller' => 0, 'tanggal_aktif' => '2025-09-10 06:45:46', 'owner' => 'user'],
    ['id' => 6591, 'invoice' => 'INV-992514070175', 'customer_id' => '2251210505', 'nama' => 'Siti', 'tipe_service' => 'PRE PPPOE', 'paket' => 'PAKET-110rb', 'harga' => 110000, 'fee_seller' => 0, 'tanggal_aktif' => '2025-09-10 06:30:15', 'owner' => 'root'],
    ['id' => 6590, 'invoice' => 'INV-992514070174', 'customer_id' => '2251210504', 'nama' => 'Budi', 'tipe_service' => 'POST PPPOE', 'paket' => 'PAKET-110rb', 'harga' => 110000, 'fee_seller' => 0, 'tanggal_aktif' => '2025-09-10 06:15:30', 'owner' => 'user'],
    ['id' => 6589, 'invoice' => 'INV-992514070173', 'customer_id' => '2251210503', 'nama' => 'Rina', 'tipe_service' => 'PRE PPPOE', 'paket' => 'PAKET-110rb', 'harga' => 110000, 'fee_seller' => 0, 'tanggal_aktif' => '2025-09-10 06:00:45', 'owner' => 'root'],
    ['id' => 6588, 'invoice' => 'INV-992514070172', 'customer_id' => '2251210502', 'nama' => 'Ahmad', 'tipe_service' => 'POST PPPOE', 'paket' => 'PAKET-110rb', 'harga' => 110000, 'fee_seller' => 0, 'tanggal_aktif' => '2025-09-10 05:45:20', 'owner' => 'user'],
];

$total_entries = 5;
$current_page = 1;
$entries_per_page = 10;
?>

<div style="margin-left: 220px; margin-top: 48px; padding: 20px;">
    <!-- Header Section -->
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 24px; color: #333;">LAPORAN HARIAN [LUNAS]</h1>
        <p style="margin: 5px 0; color: #666;">Periode: <?php echo $periode; ?></p>
        <p style="margin: 5px 0; color: #666;">### Method: <?php echo $method; ?></p>
        
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
                    <div style="font-size: 14px; opacity: 0.9;">PROFIT (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($profit, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <div style="background: #fd7e14; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">FEE SELLER (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($fee_seller, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <div style="background: #6c757d; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">TOTAL + PPN (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($total_ppn, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Display -->
    <?php if ($tipe_user || $tipe_service || $owner_data || $metode_pembayaran): ?>
    <div style="background: #e9ecef; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        <strong>Filter Aktif:</strong>
        <?php if ($tipe_user): ?>
            <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Tipe User: <?php echo htmlspecialchars($tipe_user); ?></span>
        <?php endif; ?>
        <?php if ($tipe_service): ?>
            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Tipe Service: <?php echo htmlspecialchars($tipe_service); ?></span>
        <?php endif; ?>
        <?php if ($owner_data): ?>
            <span style="background: #fd7e14; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Owner: <?php echo htmlspecialchars($owner_data); ?></span>
        <?php endif; ?>
        <?php if ($metode_pembayaran): ?>
            <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Metode: <?php echo htmlspecialchars($metode_pembayaran); ?></span>
        <?php endif; ?>
        <a href="income-harian.php" style="color: #dc3545; text-decoration: none; margin-left: 10px;">[Hapus Filter]</a>
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
        <table id="billingTable" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Id <i class="fas fa-sort" onclick="sortTable(1)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Invoice <i class="fas fa-sort" onclick="sortTable(2)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        ID Pelanggan <i class="fas fa-sort" onclick="sortTable(3)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Nama <i class="fas fa-sort" onclick="sortTable(4)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tipe Service <i class="fas fa-sort" onclick="sortTable(5)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Paket Langganan <i class="fas fa-sort" onclick="sortTable(6)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Harga [+PPN] <i class="fas fa-sort" onclick="sortTable(7)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Fee Seller <i class="fas fa-sort" onclick="sortTable(8)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tanggal Aktif <i class="fas fa-sort" onclick="sortTable(9)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Owner Data <i class="fas fa-sort" onclick="sortTable(10)"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billing_data as $row): ?>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['id']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['invoice']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <a href="#" style="color: #007bff; text-decoration: none;"><?php echo $row['customer_id']; ?></a>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <i class="fas fa-info-circle" style="color: #6c757d; margin-right: 5px;"></i>
                        <?php echo $row['nama']; ?>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <?php 
                        $service_class = strpos($row['tipe_service'], 'PRE') !== false ? 'background: #28a745; color: white;' : 'background: #007bff; color: white;';
                        ?>
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; <?php echo $service_class; ?>">
                            <?php echo $row['tipe_service']; ?>
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['paket']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">Rp. <?php echo number_format($row['fee_seller'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['tanggal_aktif']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><?php echo $row['owner']; ?></td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <button style="background: #28a745; color: white; border: none; padding: 4px 8px; margin-right: 5px; border-radius: 4px; cursor: pointer;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button style="background: #fd7e14; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
        <div style="color: #6c757d;">
            Showing 1 to <?php echo count($billing_data); ?> of <?php echo $total_entries; ?> entries
        </div>
        <div>
            <button style="background: #6c757d; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;" disabled>Previous</button>
            <button style="background: #007bff; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;">1</button>
            <button style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Next</button>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; min-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">Filter Laporan</h3>
            <button onclick="hideFilterModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Periode</label>
            <input type="date" id="periode" value="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Method</label>
            <select id="method" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="ALL USER-TYPE" <?php echo $method == 'ALL USER-TYPE' ? 'selected' : ''; ?>>ALL USER-TYPE</option>
                <option value="HOTSPOT ONLY" <?php echo $method == 'HOTSPOT ONLY' ? 'selected' : ''; ?>>HOTSPOT ONLY</option>
                <option value="PPPOE ONLY" <?php echo $method == 'PPPOE ONLY' ? 'selected' : ''; ?>>PPPOE ONLY</option>
            </select>
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
    var periode = document.getElementById('periode').value;
    var method = document.getElementById('method').value;
    
    // Build URL with parameters
    var url = 'topup-reseller.php';
    var params = [];
    
    if (periode) {
        params.push('periode=' + encodeURIComponent(periode));
    }
    if (method) {
        params.push('method=' + encodeURIComponent(method));
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
    var table = document.getElementById('billingTable');
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
