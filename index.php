<?php
/**
 * Plugin Name: Referral Tracking
 * Plugin URI: https://firescripts.net
 * Description: This simple referrial tracking plugin will allow you to create affiliate link which you can give to anyone you want and later track actions of referred visitor throughout your website
 * Version: 1.1
 * Author: Zarko
 * Author URI: https://firescripts.net
 * Text Domain: reftrack
 */

require_once('main.php');
require_once('class-reftrack.php');
$reftrack = new reftrack;
register_activation_hook( __FILE__, array($reftrack, 'install'));

// starting up! 
$reftrack->request_init();
