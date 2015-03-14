<?php

// admin menus
function include_admin_menu_main() { 
	require_once('admin_users.php'); 
}

function include_admin_menu_visits() { 
	require_once('admin_visits.php');
}

function include_admin_menu_settings() {
	require_once('admin_settings.php');
}

function reftrack_admin_menu() { 
	add_menu_page('Referral Tracking', 'Reftrack', 'activate_plugins', 'reftrack-users', 'include_admin_menu_main', 'dashicons-chart-bar', '84.9');
	add_submenu_page('reftrack-users', 'Visits ', 'Visits', 'activate_plugins', 'reftrack-visits', 'include_admin_menu_visits'); 
	add_submenu_page('reftrack-users', 'Settings ', 'Settings', 'activate_plugins', 'reftrack-settings', 'include_admin_menu_settings'); 
}
add_action('admin_menu', 'reftrack_admin_menu');

// resources
function reftrack_admin_resources() { 
	
	wp_enqueue_style('reftrackstyle', REFTRACK_URL.'/admin/css/style.css');
	wp_enqueue_script('reftrackjs', REFTRACK_URL.'/admin/js/main.js');
	
	wp_enqueue_style('reftrackfonts', REFTRACK_URL.'/admin/fonts/css/font-awesome.min.css');
		
}
add_action('admin_init', 'reftrack_admin_resources');

require_once('includes/reftrack-users-list-table.php');
require_once('includes/reftrack-visits-list-table.php');
require_once('reftrack-notifications.php');
require_once('reftrack-adminoptions.php');
