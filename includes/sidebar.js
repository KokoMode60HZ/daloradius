// Fungsi untuk toggle submenu dengan localStorage
function toggleSubmenu(menuId, arrowId) {
    var submenu = document.getElementById(menuId);
    var arrow = document.getElementById(arrowId).querySelector('img');
    var isOpen = submenu.style.display === 'flex';
    
    if (isOpen) {
        submenu.style.display = 'none';
        arrow.src = 'images/arrow_left.png';
        localStorage.setItem('sidebar_' + menuId, 'closed');
    } else {
        submenu.style.display = 'flex';
        arrow.src = 'images/arrow_down.png';
        localStorage.setItem('sidebar_' + menuId, 'open');
    }
}

// Event listeners untuk semua toggle
document.addEventListener('DOMContentLoaded', function() {
    // Setup event listeners
    var toggles = [
        {id: 'pengaturan-toggle', menu: 'pengaturan-submenu', arrow: 'pengaturan-arrow'},
        {id: 'sessionuser-toggle', menu: 'sessionuser-submenu', arrow: 'sessionuser-arrow'},
        {id: 'odp-toggle', menu: 'odp-submenu', arrow: 'odp-arrow'},
        {id: 'profil-toggle', menu: 'profil-submenu', arrow: 'profil-arrow'},
        {id: 'pelanggan-toggle', menu: 'pelanggan-submenu', arrow: 'pelanggan-arrow'},
        {id: 'voucher-toggle', menu: 'voucher-submenu', arrow: 'voucher-arrow'},
        {id: 'tagihan-toggle', menu: 'tagihan-submenu', arrow: 'tagihan-arrow'},
        {id: 'keuangan-toggle', menu: 'keuangan-submenu', arrow: 'keuangan-arrow'},
        {id: 'payment-toggle', menu: 'payment-submenu', arrow: 'payment-arrow'},
        {id: 'tiket-toggle', menu: 'tiket-submenu', arrow: 'tiket-arrow'},
        {id: 'tools-toggle', menu: 'tools-submenu', arrow: 'tools-arrow'},
        {id: 'log-toggle', menu: 'log-submenu', arrow: 'log-arrow'}
    ];
    
    toggles.forEach(function(toggle) {
        var element = document.getElementById(toggle.id);
        if (element) {
            element.onclick = function() { 
                toggleSubmenu(toggle.menu, toggle.arrow); 
            };
        }
    });
    
    // Restore state dari localStorage
    toggles.forEach(function(toggle) {
        var state = localStorage.getItem('sidebar_' + toggle.menu);
        if (state === 'open') {
            var submenu = document.getElementById(toggle.menu);
            var arrow = document.getElementById(toggle.arrow);
            if (submenu && arrow) {
                submenu.style.display = 'flex';
                arrow.querySelector('img').src = 'images/arrow_down.png';
            }
        }
    });
});
