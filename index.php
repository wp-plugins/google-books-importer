<?php
/*
Plugin Name: Google Books Importer
Plugin URI: https://firescripts.net
Description: Bulk import of books from Google Books
Version: 1.0
Author: Zarko
Author URI: https://firescripts.net
License: GPLv3
*/

add_action('admin_menu', 'gbi_register_submenu_page');
add_action('admin_init', 'gbi_register_resources');
add_action('admin_init', 'gbi_default_setup');

function gbi_register_submenu_page() {
	add_submenu_page( 'options-general.php', 'Import Google Books', 'Google Books Import', 'manage_options', 'google-books-importer', 'gbi_admin_import_page'); 
}

function gbi_admin_import_page() {
	require_once(dirname(__FILE__).'/admin.php');
}

// include resources
function gbi_register_resources() { 
	if(isset($_GET['page']) && $_GET['page'] == 'google-books-importer') { 
		wp_enqueue_style('gbi-style', plugins_url().'/google-books-importer/assets/style.css');
		wp_enqueue_script('gbi-script', plugins_url().'/google-books-importer/assets/scripts.js', array('jquery'));
	}
}

function gbi_default_setup() { 
	
	if(get_option('gbi_fields'))
		return;

	// default setup
	$mapped_fields = array( 
		'post_title' => 'title',
		'post_content' => 'description',
		'post_excerpt' => 'subtitle',
		'post_status' => 'publish',
		'comment_status' => 'open',
		'post_types' => 'post',
		'category' => '0',
	);

	update_option('gbi_fields', $mapped_fields);
	update_option('gbi_apikey', '');

}
