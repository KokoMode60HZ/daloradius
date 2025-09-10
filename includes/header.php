<?php
$operator = isset($_SESSION['operator_user']) ? $_SESSION['operator_user'] : '';
?>
<div id="topbar" style="position:fixed;top:0;left:0;right:0;height:48px;background:rgba(21,88,176,0.98);border-bottom:2px solid rgba(255,255,255,0.18);display:flex;justify-content:space-between;align-items:center;z-index:101;">
    <div style="display:flex;align-items:center;padding-left:24px;">
        <img src="images/logo.png" alt="Logo" style="height:32px;">
        <span style="font-size:1.2em;font-weight:bold;color:#fff;margin-left:16px;">LJN Management</span>
    </div>
    <div style="display:flex;align-items:center;gap:12px;padding-right:24px;">
        <button style="background:#28a745;color:#fff;border:none;border-radius:4px;padding:6px 12px;cursor:pointer;display:flex;align-items:center;gap:6px;">
            <i class="fas fa-percentage"></i>
            API MikroTik
        </button>
        <button style="background:#28a745;color:#fff;border:none;border-radius:4px;padding:6px;width:32px;height:32px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-search"></i>
        </button>
        <div class="profile-dropdown" style="position:relative;">
            <button onclick="toggleProfileMenu()" style="background:none;border:none;display:flex;align-items:center;gap:8px;color:#fff;cursor:pointer;padding:4px;">
                <img src="images/default-avatar.png" alt="Profile" style="width:32px;height:32px;border-radius:50%;">
                <span><?php echo $operator; ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div id="profileDropdown" style="display:none;position:absolute;top:100%;right:0;background:#fff;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);min-width:200px;margin-top:8px;">
                <div style="padding:16px;text-align:center;border-bottom:1px solid #eee;">
                    <img src="images/default-avatar.png" alt="Profile" style="width:64px;height:64px;border-radius:50%;margin-bottom:8px;">
                    <div style="font-weight:bold;"><?php echo $operator; ?></div>
                    <div style="color:#666;font-size:0.9em;">Administrator</div>
                </div>
                <a href="profile.php" style="display:block;padding:12px 16px;color:#333;text-decoration:none;transition:background 0.2s;">
                    <i class="fas fa-user" style="margin-right:8px;"></i> Profile
                </a>
                <a href="logout.php" style="display:block;padding:12px 16px;color:#333;text-decoration:none;transition:background 0.2s;">
                    <i class="fas fa-sign-out-alt" style="margin-right:8px;"></i> Sign out
                </a>
            </div>
        </div>
    </div>
    <script>
    function toggleProfileMenu() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.profile-dropdown')) {
            document.getElementById('profileDropdown').style.display = 'none';
        }
    });
    </script>
</div>
