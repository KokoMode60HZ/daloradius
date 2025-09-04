<?php
    include_once('library/config_read.php');
    include_once('library/checklogin.php');
    $log = "visited page: peta lokasi odp pop";
    include('include/config/logging.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Peta Lokasi ODP | POP - daloRADIUS</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="library/javascript/pages_common.js"></script>
    <script type="text/javascript" src="library/javascript/ajax.js"></script>
    <script type="text/javascript" src="library/javascript/dynamic.js"></script>
    <script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
    
    <!-- Google Maps API - Development Mode -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap"></script>
    
    <style>
        .map-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .map-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .map-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
            border-bottom: 2px solid #009688;
            padding-bottom: 12px;
        }
        .search-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .search-input:focus {
            outline: none;
            border-color: #009688;
            box-shadow: 0 0 0 2px rgba(0,150,136,0.2);
        }
        .search-btn {
            background: #009688;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-btn:hover {
            background: #00796b;
        }
        .map-wrapper {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        #map {
            width: 100%;
            height: 100%;
        }
        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .map-control-btn {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .map-control-btn:hover {
            background: #f8f9fa;
        }
        .legend {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: white;
            border-radius: 6px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .legend-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            font-size: 12px;
        }
        .legend-marker {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }
        .marker-odp {
            background: #e53935;
        }
        .marker-pop {
            background: #1976d2;
        }
        .info-window {
            max-width: 250px;
        }
        .info-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .info-detail {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        .info-coordinates {
            font-family: monospace;
            font-size: 11px;
            color: #999;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar-new.php'; ?>

    <div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
        <div class="dashboard-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <h2 style="margin:0;color:#333;">Peta Lokasi ODP | POP</h2>
        </div>
        
        <div class="map-container">
            <div class="map-section">
                <div class="map-title">Peta Lokasi ODP | POP</div>
                
                <!-- Search Form -->
                <div class="search-form">
                    <input type="text" class="search-input" id="searchInput" placeholder="Nama atau Kode atau Area ODP | POP" onkeypress="handleSearch(event)">
                    <button class="search-btn" onclick="searchLocation()">
                        <i class="fas fa-search"></i>
                        Cari
                    </button>
                </div>
                
                <!-- Map Container -->
                <div class="map-wrapper">
                    <div id="map"></div>
                    
                    <!-- Map Controls -->
                    <div class="map-controls">
                        <button class="map-control-btn" onclick="centerMap()">
                            <i class="fas fa-crosshairs"></i>
                            Center
                        </button>
                        <button class="map-control-btn" onclick="toggleMarkers()">
                            <i class="fas fa-map-marker-alt"></i>
                            Toggle Markers
                        </button>
                        <button class="map-control-btn" onclick="fitBounds()">
                            <i class="fas fa-expand-arrows-alt"></i>
                            Fit All
                        </button>
                    </div>
                    
                    <!-- Legend -->
                    <div class="legend">
                        <div class="legend-title">Legenda</div>
                        <div class="legend-item">
                            <div class="legend-marker marker-odp"></div>
                            <span>ODP</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-marker marker-pop"></div>
                            <span>POP</span>
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
        let markerCluster;
        let showMarkers = true;

        // Sample ODP/POP data
        const odpPopData = [
            {
                id: 1,
                name: "1 SUMBULWETAN - NURSALAM",
                code: "(20)",
                area: "SUMBULWETAN",
                type: "ODP",
                lat: -7.8632727,
                lng: 112.6297795,
                owner: "root"
            },
            {
                id: 2,
                name: "2 KAMPUNGAN - AHMAD",
                code: "(21)",
                area: "KAMPUNGAN",
                type: "ODP",
                lat: -7.8641234,
                lng: 112.6301234,
                owner: "root"
            },
            {
                id: 3,
                name: "3 PRODO - SITI",
                code: "(22)",
                area: "PRODO",
                type: "POP",
                lat: -7.8652345,
                lng: 112.6312345,
                owner: "root"
            },
            {
                id: 4,
                name: "4 KARANGAN - BUDI",
                code: "(23)",
                area: "KARANGAN",
                type: "ODP",
                lat: -7.8663456,
                lng: 112.6323456,
                owner: "root"
            },
            {
                id: 5,
                name: "5 SUMBER - RINA",
                code: "(24)",
                area: "SUMBER",
                type: "ODP",
                lat: -7.8674567,
                lng: 112.6334567,
                owner: "root"
            },
            {
                id: 6,
                name: "6 TAMAN - DEDI",
                code: "(25)",
                area: "TAMAN",
                type: "POP",
                lat: -7.8685678,
                lng: 112.6345678,
                owner: "root"
            },
            {
                id: 7,
                name: "7 KEBON - EKO",
                code: "(26)",
                area: "KEBON",
                type: "ODP",
                lat: -7.8696789,
                lng: 112.6356789,
                owner: "root"
            },
            {
                id: 8,
                name: "8 SIDO - FANI",
                code: "(27)",
                area: "SIDO",
                type: "ODP",
                lat: -7.8707890,
                lng: 112.6367890,
                owner: "root"
            },
            {
                id: 9,
                name: "9 GUNUNG - GITA",
                code: "(28)",
                area: "GUNUNG",
                type: "POP",
                lat: -7.8718901,
                lng: 112.6378901,
                owner: "root"
            },
            {
                id: 10,
                name: "10 LOR - HADI",
                code: "(29)",
                area: "LOR",
                type: "ODP",
                lat: -7.8729012,
                lng: 112.6389012,
                owner: "root"
            }
        ];

        function initMap() {
            try {
                // Initialize map centered on Malang area
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: { lat: -7.8667, lng: 112.6333 }, // Malang coordinates
                    mapTypeId: 'roadmap',
                    styles: [
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });

                // Add markers for each ODP/POP
                addMarkers();
            } catch (error) {
                console.error('Google Maps initialization failed:', error);
                showMapError();
            }
        }

        function showMapError() {
            const mapElement = document.getElementById('map');
            mapElement.innerHTML = `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #666;">
                    <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 16px; color: #ccc;"></i>
                    <h3 style="margin: 0 0 8px 0; color: #333;">Google Maps tidak dapat dimuat</h3>
                    <p style="margin: 0 0 16px 0; text-align: center; max-width: 300px;">
                        Untuk menggunakan fitur peta, Anda perlu mengonfigurasi Google Maps API Key.
                    </p>
                    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 400px;">
                        <h4 style="margin: 0 0 12px 0; color: #333;">Data ODP/POP:</h4>
                        <div style="max-height: 300px; overflow-y: auto;">
                            ${odpPopData.map(location => `
                                <div style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    <strong>${location.name}</strong><br>
                                    <small>${location.area} - ${location.type}</small><br>
                                    <small style="color: #999;">${location.lat}, ${location.lng}</small>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        }

        function addMarkers() {
            markers = [];
            
            odpPopData.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    title: location.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: location.type === 'ODP' ? '#e53935' : '#1976d2',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });

                // Create info window content
                const infoContent = `
                    <div class="info-window">
                        <div class="info-title">${location.name}</div>
                        <div class="info-detail"><strong>Kode:</strong> ${location.code}</div>
                        <div class="info-detail"><strong>Area:</strong> ${location.area}</div>
                        <div class="info-detail"><strong>Tipe:</strong> ${location.type}</div>
                        <div class="info-detail"><strong>Owner:</strong> ${location.owner}</div>
                        <div class="info-coordinates">
                            Lat: ${location.lat}<br>
                            Lng: ${location.lng}
                        </div>
                    </div>
                `;

                const infoWindow = new google.maps.InfoWindow({
                    content: infoContent
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });

            // Create marker clusterer if available
            if (typeof MarkerClusterer !== 'undefined') {
                markerCluster = new MarkerClusterer(map, markers, {
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });
            }
        }

        function searchLocation() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            if (!searchTerm) {
                alert('Masukkan nama, kode, atau area ODP/POP untuk mencari');
                return;
            }

            const foundLocation = odpPopData.find(location => 
                location.name.toLowerCase().includes(searchTerm) ||
                location.code.toLowerCase().includes(searchTerm) ||
                location.area.toLowerCase().includes(searchTerm)
            );

            if (foundLocation) {
                map.setCenter({ lat: foundLocation.lat, lng: foundLocation.lng });
                map.setZoom(15);
                
                // Open info window for found location
                const marker = markers.find(m => m.getTitle() === foundLocation.name);
                if (marker) {
                    google.maps.event.trigger(marker, 'click');
                }
            } else {
                alert('Lokasi tidak ditemukan');
            }
        }

        function handleSearch(event) {
            if (event.key === 'Enter') {
                searchLocation();
            }
        }

        function centerMap() {
            map.setCenter({ lat: -7.8667, lng: 112.6333 });
            map.setZoom(12);
        }

        function toggleMarkers() {
            showMarkers = !showMarkers;
            markers.forEach(marker => {
                marker.setVisible(showMarkers);
            });
        }

        function fitBounds() {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            map.fitBounds(bounds);
        }

        // Fallback if Google Maps API fails
        window.initMap = initMap;
        
        // Timeout fallback if Google Maps doesn't load within 5 seconds
        setTimeout(() => {
            if (!map) {
                console.log('Google Maps failed to load, showing fallback');
                showMapError();
            }
        }, 5000);
    </script>
</body>
</html>
