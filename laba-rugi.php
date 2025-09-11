<?php
    include 'includes/header.php';
    include 'includes/sidebar-new.php';
?>

<div class="dashboard-page" style="margin-left:220px;margin-top:48px;padding:24px;">
    <div style="background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
        <h2 style="font-weight:bold;font-size:2em;margin-bottom:18px;">LABA RUGI</h2>
        <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;margin-bottom:24px;">
            <div style="display:flex;gap:8px;">
                <button style="background:#43a047;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-search"></i>
                </button>
                <button style="background:#009688;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-calendar-alt"></i>
                </button>
                <button style="background:#2196f3;color:#fff;border:none;border-radius:6px;padding:10px 14px;cursor:pointer;font-size:1.2em;">
                    <i class="fas fa-print"></i>
                </button>
            </div>
            <div style="flex:1;display:flex;gap:24px;">
                <div style="background:#e53935;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-calendar-alt" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">PERIODE (2025)</div>
                        <div style="font-size:1.2em;font-weight:bold;">Jan 01 - Sep 11</div>
                    </div>
                </div>
                <div style="background:#03a9f4;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-shopping-cart" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">GROSS INCOME (IDR)</div>
                        <div style="font-size:1.2em;font-weight:bold;">361.079.826</div>
                    </div>
                </div>
                <div style="background:#ff9800;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-file-alt" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">MONEY OUT (IDR)</div>
                        <div style="font-size:1.2em;font-weight:bold;">32.990.981</div>
                    </div>
                </div>
                <div style="background:#43a047;color:#fff;border-radius:8px;padding:16px 24px;display:flex;align-items:center;gap:16px;min-width:220px;">
                    <i class="fas fa-dollar-sign" style="font-size:2em;"></i>
                    <div>
                        <div style="font-size:0.95em;">PROFIT (IDR)</div>
                        <div style="font-size:1.2em;font-weight:bold;">328.088.846</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Graph Section -->
        <div style="margin-bottom:32px;">
            <div style="font-weight:bold;color:#888;margin-bottom:8px;">MONEY IN (PROFIT) STATISTIC 2025</div>
            <canvas id="profitChart" height="80"></canvas>
        </div>
        <div style="margin-bottom:32px;">
            <div style="font-weight:bold;color:#888;margin-bottom:8px;">MONEY OUT STATISTIC 2025</div>
            <canvas id="moneyOutChart" height="60"></canvas>
        </div>
        <!-- Table Section -->
        <div style="margin-bottom:8px;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">Periode</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">Transaksi</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">Gross Income</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">Fee Seller</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">PPN</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">Pengeluaran</th>
                        <th style="padding:10px;border-bottom:2px solid #90caf9;">NET PROFIT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background:#fff;">
                        <td style="padding:10px;">January 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">312 TRX</span></td>
                        <td style="padding:10px;">Rp. 38.283.798</td>
                        <td style="padding:10px;">Rp. 355</td>
                        <td style="padding:10px;">Rp. 3.473.443</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 34.810.000</td>
                    </tr>
                    <tr style="background:#f7f7f7;">
                        <td style="padding:10px;">February 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">291 TRX</span></td>
                        <td style="padding:10px;">Rp. 36.074.996</td>
                        <td style="padding:10px;">Rp. 568</td>
                        <td style="padding:10px;">Rp. 3.296.428</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 32.778.000</td>
                    </tr>
                    <tr style="background:#fff;">
                        <td style="padding:10px;">March 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">347 TRX</span></td>
                        <td style="padding:10px;">Rp. 44.574.993</td>
                        <td style="padding:10px;">Rp. 1.065</td>
                        <td style="padding:10px;">Rp. 4.083.928</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 40.490.000</td>
                    </tr>
                    <tr style="background:#f7f7f7;">
                        <td style="padding:10px;">April 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">332 TRX</span></td>
                        <td style="padding:10px;">Rp. 40.589.995</td>
                        <td style="padding:10px;">Rp. 781</td>
                        <td style="padding:10px;">Rp. 3.713.214</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 36.876.000</td>
                    </tr>
                    <tr style="background:#fff;">
                        <td style="padding:10px;">May 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">354 TRX</span></td>
                        <td style="padding:10px;">Rp. 43.161.065</td>
                        <td style="padding:10px;">Rp. 923</td>
                        <td style="padding:10px;">Rp. 3.942.377</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 39.217.765</td>
                    </tr>
                    <tr style="background:#f7f7f7;">
                        <td style="padding:10px;">June 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">361 TRX</span></td>
                        <td style="padding:10px;">Rp. 46.479.995</td>
                        <td style="padding:10px;">Rp. 30.710</td>
                        <td style="padding:10px;">Rp. 4.215.592</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 42.233.694</td>
                    </tr>
                    <tr style="background:#fff;">
                        <td style="padding:10px;">July 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">370 TRX</span></td>
                        <td style="padding:10px;">Rp. 47.759.994</td>
                        <td style="padding:10px;">Rp. 60.852</td>
                        <td style="padding:10px;">Rp. 4.303.449</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 43.395.694</td>
                    </tr>
                    <tr style="background:#f7f7f7;">
                        <td style="padding:10px;">August 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">377 TRX</span></td>
                        <td style="padding:10px;">Rp. 48.784.993</td>
                        <td style="padding:10px;">Rp. 91.065</td>
                        <td style="padding:10px;">Rp. 4.370.234</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 44.323.694</td>
                    </tr>
                    <tr style="background:#fff;">
                        <td style="padding:10px;">September 2025</td>
                        <td style="padding:10px;"><span style="background:#03a9f4;color:#fff;padding:4px 12px;border-radius:8px;">127 TRX</span></td>
                        <td style="padding:10px;">Rp. 15.369.998</td>
                        <td style="padding:10px;">Rp. 284</td>
                        <td style="padding:10px;">Rp. 1.405.714</td>
                        <td style="padding:10px;">Rp. 0</td>
                        <td style="padding:10px;">Rp. 13.964.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="footer" style="position:fixed;bottom:12px;left:220px;width:calc(100% - 220px);text-align:center;">
    <?php include 'page-footer.php'; ?>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
drawCharts();
function drawCharts() {
    // Profit Chart
    var ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
                {
                    label: 'TRX COUNT',
                    data: [312,291,347,332,354,361,370,377,127,0,0,0],
                    borderColor: '#ffe082',
                    backgroundColor: 'rgba(255,224,130,0.2)',
                    fill: false,
                    tension: 0.2
                },
                {
                    label: 'INCOME',
                    data: [38283798,36074996,44574993,40589995,43161065,46479995,47759994,48784993,15369998,0,0,0],
                    borderColor: '#03a9f4',
                    backgroundColor: 'rgba(3,169,244,0.1)',
                    fill: false,
                    tension: 0.2
                },
                {
                    label: 'PROFIT',
                    data: [34810000,32778000,40490000,36876000,39217765,42233694,43395694,44323694,13964000,0,0,0],
                    borderColor: '#43a047',
                    backgroundColor: 'rgba(67,160,71,0.1)',
                    fill: true,
                    tension: 0.2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Money Out Chart
    var ctx2 = document.getElementById('moneyOutChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
                {
                    label: 'FEE SELLER',
                    data: [355,568,1065,781,923,30710,60852,91065,284,0,0,0],
                    backgroundColor: '#b3e5fc'
                },
                {
                    label: 'PPN',
                    data: [3473443,3296428,4083928,3713214,3942377,4215592,4303449,4370234,1405714,0,0,0],
                    backgroundColor: '#aed581'
                },
                {
                    label: 'PAYOUT',
                    data: [0,0,0,0,0,0,0,0,0,0,0,0],
                    backgroundColor: '#ffe082'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
</script>