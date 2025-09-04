<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: peta pelanggan";
    include('include/config/logging.php');

    // Mock data for customer locations
    $customer_locations = [
        ['id' => '383nn', 'name' => 'Muliadi Wonokoyo', 'lat' => -7.9460, 'lng' => 112.6150, 'type' => 'PPP'],
        ['id' => '248128172', 'name' => 'Fauzan', 'lat' => -7.9300, 'lng' => 112.6300, 'type' => 'HOTSPOT'],
        ['id' => '254473874924', 'name' => 'Banana adi rt 7', 'lat' => -7.9550, 'lng' => 112.6000, 'type' => 'PPP'],
        ['id' => 'USR-004', 'name' => 'Ahmad Rizki', 'lat' => -7.9600, 'lng' => 112.6500, 'type' => 'HOTSPOT'],
        ['id' => 'USR-005', 'name' => 'Siti Nurhaliza', 'lat' => -7.9700, 'lng' => 112.6600, 'type' => 'PPP'],
        ['id' => 'USR-006', 'name' => 'Budi Santoso', 'lat' => -7.9800, 'lng' => 112.6700, 'type' => 'HOTSPOT'],
        ['id' => 'USR-007', 'name' => 'Dewi Lestari', 'lat' => -7.9900, 'lng' => 112.6800, 'type' => 'PPP'],
        ['id' => 'USR-008', 'name' => 'Eko Prasetyo', 'lat' => -8.0000, 'lng' => 112.6900, 'type' => 'HOTSPOT'],
        ['id' => 'USR-009', 'name' => 'Fitri Handayani', 'lat' => -8.0100, 'lng' => 112.7000, 'type' => 'PPP'],
        ['id' => 'USR-010', 'name' => 'Guntur Pratama', 'lat' => -8.0200, 'lng' => 112.7100, 'type' => 'HOTSPOT'],
    ];

    // Default center for the map (Malang, Indonesia)
    $default_lat = -7.9839;
    $default_lng = 112.6213;
    $default_zoom = 12;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Peta Pelanggan - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    <style>
        .map-container { max-width: 1400px; margin: 0 auto; }
        .map-section { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .title { font-size:24px; font-weight:bold; color:#333; margin-bottom:16px; border-bottom:2px solid #009688; padding-bottom:12px; }
        .map-controls { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center; }
        .search-input { padding:8px 12px; border:1px solid #ddd; border-radius:4px; width:300px; }
        .btn { padding:8px 16px; border:none; border-radius:4px; cursor:pointer; font-size:14px; }
        .btn-primary { background:#007bff; color:#fff; }
        .btn-primary:hover { background:#0056b3; }
        .btn-secondary { background:#6c757d; color:#fff; }
        .btn-secondary:hover { background:#5a6268; }
        .filter-btn { background:#f0f0f0; color:#333; border:1px solid #ccc; }
        .filter-btn.active { background:#007bff; color:#fff; border-color:#007bff; }
        .filter-btn:hover:not(.active) { background:#e2e6ea; }
        .map-area { width:100%; height:600px; background:#e0e0e0; border:1px solid #ccc; border-radius:8px; overflow:hidden; position:relative; }
        #map { width:100%; height:100%; }
        .map-fallback { padding:20px; text-align:center; color:#555; display:none; }
        .map-fallback h3 { color:#d9534f; }
        .customer-list-fallback { margin-top:20px; border:1px solid #eee; border-radius:8px; overflow:hidden; }
        .customer-list-fallback table { width:100%; border-collapse:collapse; }
        .customer-list-fallback th, .customer-list-fallback td { padding:12px 15px; text-align:left; border-bottom:1px solid #eee; }
        .customer-list-fallback th { background:#f8f8f8; font-weight:600; color:#333; }
        .customer-list-fallback tr:hover { background:#f5f5f5; }
        .customer-list-fallback .no-data { text-align:center; padding:20px; color:#777; }
        .btn-add { background:#28a745; color:#fff; border:none; padding:8px 16px; border-radius:4px; cursor:pointer; margin-bottom:16px; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <h2 style="margin:0;color:#333;">Peta Pelanggan</h2>
            <button class="btn-add">
                <i class="fas fa-plus"></i> TAMBAH PENANDA
            </button>
        </div>

        <div class="map-container">
            <div class="map-section">
                <div class="title">Peta Pelanggan</div>

                <div class="map-controls">
                    <input type="text" id="customerSearch" class="search-input" placeholder="Customer ID, Username or Fullname, ODP Name or ODP Area">
                    <button class="btn btn-primary" onclick="searchCustomers()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button class="btn filter-btn active" id="filterAll" onclick="filterMap('all')">Semua</button>
                    <button class="btn filter-btn" id="filterPPP" onclick="filterMap('PPP')">PPP</button>
                    <button class="btn filter-btn" id="filterHotspot" onclick="filterMap('HOTSPOT')">HOTSPOT</button>
                    <button class="btn btn-secondary" onclick="centerMap()">
                        <i class="fas fa-crosshairs"></i> Pusat Peta
                    </button>
                    <button class="btn btn-secondary" onclick="toggleMarkers()">
                        <i class="fas fa-eye"></i> Tampilkan/Sembunyikan Penanda
                    </button>
                    <button class="btn btn-secondary" onclick="fitAllMarkers()">
                        <i class="fas fa-expand"></i> Sesuaikan Semua Penanda
                    </button>
                </div>

                <div class="map-area">
                    <div id="map"></div>
                    <div id="map-fallback" class="map-fallback">
                        <h3><i class="fas fa-exclamation-triangle"></i> Google Maps tidak dapat dimuat.</h3>
                        <p>Pastikan kunci API Google Maps Anda benar dan penagihan diaktifkan untuk proyek Anda.</p>
                        <p>Berikut adalah daftar lokasi pelanggan:</p>
                        <div class="customer-list-fallback">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID Pelanggan</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                    </tr>
                                </thead>
                                <tbody id="fallbackCustomerList">
                                    <?php if (!empty($customer_locations)): ?>
                                        <?php foreach ($customer_locations as $customer): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                                <td><?php echo htmlspecialchars($customer['type']); ?></td>
                                                <td><?php echo htmlspecialchars($customer['lat']); ?></td>
                                                <td><?php echo htmlspecialchars($customer['lng']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="no-data">Tidak ada data pelanggan untuk ditampilkan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
        <?php include 'page-footer.php'; ?>
    </div>

    <script>
        let map;
        let markers = [];
        let infoWindow;
        const customerData = <?php echo json_encode($customer_locations); ?>;
        let currentFilter = 'all';

        function initMap() {
            // Check if Google Maps API is available
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                console.error("Google Maps API not loaded. Displaying fallback.");
                document.getElementById('map').style.display = 'none';
                document.getElementById('map-fallback').style.display = 'block';
                return;
            }

            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: <?php echo $default_lat; ?>, lng: <?php echo $default_lng; ?> },
                zoom: <?php echo $default_zoom; ?>,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            infoWindow = new google.maps.InfoWindow();
            addMarkers(customerData);
            filterMap(currentFilter);
        }

        function addMarkers(locations) {
            markers.forEach(marker => marker.setMap(null));
            markers = [];

            locations.forEach(customer => {
                const marker = new google.maps.Marker({
                    position: { lat: customer.lat, lng: customer.lng },
                    map: map,
                    title: customer.name,
                    customerType: customer.type,
                    customerId: customer.id,
                    customerName: customer.name,
                });

                marker.addListener('click', () => {
                    infoWindow.setContent(`
                        <div>
                            <strong>${customer.name}</strong><br>
                            ID: ${customer.id}<br>
                            Tipe: ${customer.type}<br>
                            Lat: ${customer.lat}, Lng: ${customer.lng}
                        </div>
                    `);
                    infoWindow.open(map, marker);
                });
                markers.push(marker);
            });
        }

        function filterMap(type) {
            currentFilter = type;
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('filter' + type.charAt(0).toUpperCase() + type.slice(1)).classList.add('active');

            markers.forEach(marker => {
                if (type === 'all' || marker.customerType === type) {
                    marker.setMap(map);
                } else {
                    marker.setMap(null);
                }
            });
            fitAllMarkers();
        }

        function searchCustomers() {
            const searchTerm = document.getElementById('customerSearch').value.toLowerCase();
            markers.forEach(marker => {
                const customerId = marker.customerId.toLowerCase();
                const customerName = marker.customerName.toLowerCase();
                const customerType = marker.customerType.toLowerCase();

                if (customerId.includes(searchTerm) || customerName.includes(searchTerm) || customerType.includes(searchTerm)) {
                    marker.setMap(map);
                } else {
                    marker.setMap(null);
                }
            });
            fitAllMarkers();
        }

        function centerMap() {
            map.setCenter({ lat: <?php echo $default_lat; ?>, lng: <?php echo $default_lng; ?> });
            map.setZoom(<?php echo $default_zoom; ?>);
        }

        function toggleMarkers() {
            const visible = markers.length > 0 && markers[0].getMap() !== null;
            markers.forEach(marker => {
                marker.setMap(visible ? null : map);
            });
        }

        function fitAllMarkers() {
            const bounds = new google.maps.LatLngBounds();
            let hasVisibleMarkers = false;
            markers.forEach(marker => {
                if (marker.getMap() !== null) {
                    bounds.extend(marker.getPosition());
                    hasVisibleMarkers = true;
                }
            });

            if (hasVisibleMarkers) {
                map.fitBounds(bounds);
                if (markers.filter(m => m.getMap() !== null).length === 1) {
                    map.setZoom(15);
                }
            } else {
                centerMap();
            }
        }

        // Load Google Maps API script
        function loadGoogleMapsScript() {
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?callback=initMap';
            script.async = true;
            script.defer = true;
            script.onerror = () => {
                console.error("Failed to load Google Maps API script.");
                document.getElementById('map').style.display = 'none';
                document.getElementById('map-fallback').style.display = 'block';
            };
            document.head.appendChild(script);
        }

        // Initialize when page loads
        window.onload = loadGoogleMapsScript;
    </script>
</body>
</html>
