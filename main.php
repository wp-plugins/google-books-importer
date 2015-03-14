<?php

define('REFTRACK_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('REFTRACK_PATH', __DIR__);

// default options
$reftrackoptions_default = array(
	'cookie_length' => '30',
	'tracking_key' => 'ref',
	'cookiesign' => substr(md5(uniqid()), 0, 15),
);

$reftrackoptions = get_option('reftrack_options');
if($reftrackoptions == false) { // first time run
	update_option('reftrack_options', $reftrackoptions_default);
}

require_once('model/affiliates.php');
require_once('model/visits.php');

$affiliatesdb = new reftrack_affiliates();
$visitsdb = new reftrack_visits();

// load admin options
require_once('admin/includes/functions.php');
require_once('admin/index.php');
