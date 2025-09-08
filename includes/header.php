<?php
	$operator = isset($_SESSION['operator_user']) ? $_SESSION['operator_user'] : '';
?>
<!-- Header -->
<div id="topbar" style="position:fixed;top:0;left:0;width:100vw;height:48px;background:rgba(21,88,176,0.98);border-bottom:2px solid rgba(255,255,255,0.18);display:flex;align-items:center;gap:16px;padding:0 20px;z-index:101;">
	<img src="images/logo.png" alt="Logo" style="height:32px;">
	<span style="font-size:1.2em;font-weight:bold;color:#fff;">LJN Management</span>
	<div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
		<button style="background:#28a745;color:#fff;border:none;border-radius:4px;padding:6px 12px;cursor:pointer;display:flex;align-items:center;gap:6px;">
			<i class="fas fa-percentage"></i>
			API MikroTik
		</button>
		<button style="background:#28a745;color:#fff;border:none;border-radius:4px;padding:6px;width:32px;height:32px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
			<i class="fas fa-search"></i>
		</button>
	</div>
</div>
