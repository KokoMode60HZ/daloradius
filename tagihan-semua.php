<?php
include("library/checklogin.php");
include("library/opendb.php");
include("library/config_read.php");
include("library/closedb.php");
include("includes/header.php");
include("includes/sidebar.php");

// Get filter parameters
$tipe_service = isset($_GET['tipe_service']) ? $_GET['tipe_service'] : '';
$owner_data = isset($_GET['owner_data']) ? $_GET['owner_data'] : '';

// Calculate summary data (mock data for now)
$tagihan_pokok = 0;
$fee_seller = 0;
$total_ppn = 0;
?>

<div style="margin-left: 220px; margin-top: 48px; padding: 20px;">
    <!-- Header Section -->
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 24px; color: #333;">SEMUA TAGIHAN</h1>
        
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
                <i class="fas fa-file-alt"></i>
            </button>
            <button style="background: #dc3545; color: white; border: none; padding: 8px 12px; margin-right: 5px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <!-- Summary Cards -->
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="background: #007bff; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">TAGIHAN POKOK (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($tagihan_pokok, 0, ',', '.'); ?></div>
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
            
            <div style="background: #dc3545; color: white; padding: 15px; border-radius: 8px; flex: 1; display: flex; align-items: center;">
                <div style="margin-right: 15px; font-size: 24px;">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">TOTAL + PPN (IDR)</div>
                    <div style="font-size: 20px; font-weight: bold;"><?php echo number_format($total_ppn, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Display -->
    <?php if ($tipe_service || $owner_data): ?>
    <div style="background: #e9ecef; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        <strong>Filter Aktif:</strong>
        <?php if ($tipe_service): ?>
            <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Tipe Service: <?php echo htmlspecialchars($tipe_service); ?></span>
        <?php endif; ?>
        <?php if ($owner_data): ?>
            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 12px; margin-right: 5px;">Owner: <?php echo htmlspecialchars($owner_data); ?></span>
        <?php endif; ?>
        <a href="tagihan-semua.php" style="color: #dc3545; text-decoration: none; margin-left: 10px;">[Hapus Filter]</a>
    </div>
    <?php endif; ?>

    <!-- Table Controls -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <label>Show </label>
            <select style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label> entries</label>
        </div>
        <div>
            <label>Search: </label>
            <input type="text" placeholder="Search..." style="padding: 5px; border: 1px solid #ddd; border-radius: 4px; width: 200px;">
        </div>
    </div>

    <!-- Data Table -->
    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        <input type="checkbox">
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Id <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Invoice <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        ID Pelanggan <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Nama <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tipe Service <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Paket Langganan <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Tagihan [+PPN] <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Jatuh Tempo <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Owner Data <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Renew | Print <i class="fas fa-sort"></i>
                    </th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                        Aksi <i class="fas fa-sort"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="11" style="padding: 40px; text-align: center; color: #6c757d; font-style: italic;">
                        No data available in the table
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
        <div style="color: #6c757d;">
            Showing 0 to 0 of 0 entries
        </div>
        <div>
            <!-- No pagination buttons needed when there's no data -->
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; min-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">Semua Tagihan</h3>
            <button onclick="hideFilterModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        <div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tipe Service</label>
                <select id="tipeService" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">- Semua Transaksi -</option>
                    <option value="PRE" <?php echo $tipe_service == 'PRE' ? 'selected' : ''; ?>>PRE</option>
                    <option value="POST" <?php echo $tipe_service == 'POST' ? 'selected' : ''; ?>>POST</option>
                </select>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Owner Data</label>
                <select id="ownerData" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">- Semua Owner -</option>
                    <option value="root" <?php echo $owner_data == 'root' ? 'selected' : ''; ?>>root</option>
                    <option value="user" <?php echo $owner_data == 'user' ? 'selected' : ''; ?>>user</option>
                </select>
            </div>
            
            <button type="button" onclick="applyFiltersToCurrentPage()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%;">
                Lihat Laporan
            </button>
        </div>
    </div>
</div>

<script>
function showFilterModal() {
    document.getElementById('filterModal').style.display = 'block';
}

function hideFilterModal() {
    document.getElementById('filterModal').style.display = 'none';
}

// Function to apply filters to current page
function applyFiltersToCurrentPage() {
    var tipeService = document.getElementById('tipeService').value;
    var ownerData = document.getElementById('ownerData').value;
    
    // Build URL with parameters
    var url = window.location.pathname;
    var params = [];
    
    if (tipeService) {
        params.push('tipe_service=' + encodeURIComponent(tipeService));
    }
    if (ownerData) {
        params.push('owner_data=' + encodeURIComponent(ownerData));
    }
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    } else {
        // If no filters, remove all parameters
        url = window.location.pathname;
    }
    
    // Reload the current page with new filters
    window.location.href = url;
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