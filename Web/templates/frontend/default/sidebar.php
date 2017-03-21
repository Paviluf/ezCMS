<?php

/**
 * Sidebar
 */

$html = '';

if(file_exists(stream_resolve_include_path("custom/sidebar.php"))) {
	include "custom/sidebar.php";
}
else {
	// Generate Summary
	if(isset($summary) && !empty($summary)) {
		$html .= \Library\Functions::buildSumary($summary, $id_PK, $name);			
	}
}