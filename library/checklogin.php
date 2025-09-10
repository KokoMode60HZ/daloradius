<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 * Description:
 * 		verifies a user session, valid or invalid based on the random session_id generated on dologin.php
 *
 * Authors:	Liran Tal <liran@enginx.com>
 *
 *********************************************************************************************************
 */

// internal function to verify the custom session_id which we create upon logging in
function session_verify() {
	session_start();

	$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	$session_offset = intval(substr(session_id(), 0, 1));
	if (substr(md5($REMOTE_ADDR), 0, 10 + $session_offset) == 
		substr(session_id(), 1, 10 + $session_offset)) {
		$session_valid="yes";
	} else {
		$session_valid="no";
	}

	return $session_valid;
}


if (session_verify() == "yes") {
	if (!isset($_SESSION['daloradius_logged_in']) || $_SESSION['daloradius_logged_in'] != true) {
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Location: login.php');
		exit;
	}
} else {
	// Clear all session data and cookies
	session_unset();
	session_destroy();
	setcookie(session_name(), '', time() - 3600, '/');
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Location: login.php');
	exit;
}


?>
