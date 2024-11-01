<?php
/*
*
* Plugin Name: Winning Portfolio
* Author: Pressfore
* Author URI: http://pressfore.com
* Plugin URI: http://pressfore.com
* Description: Very Flexibile and simple to use Portfolios plugin, compatible with King Composer and Visual Composer
* Text Domain: wpf
* License: GPL
* License URL: 
* Version: 1.1
*
*/

if( !defined( 'ABSPATH' ) ) exit;

if( !defined('PF_PORTF_PATH') ) 
	define('PF_PORTF_PATH', plugin_dir_path(__FILE__));

if( !defined('PF_PORTF_ASSETS_PATH') ) 
	define('PF_PORTF_ASSETS_PATH', plugin_dir_url(__FILE__).'assets/');

if(!function_exists('is_plugin_active')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! defined('WPF_VERSION') ){
	define( 'WPF_VERSION', '1.1' );
}

if( !defined('PF_STORE_URL') ) {
	define('PF_STORE_URL', 'http://pressfore.com');
}

# initialzie Portfolios
require_once PF_PORTF_PATH.'core/init.php';
require_once PF_PORTF_PATH.'pages/init.php';

if( is_admin() ) {
	$pages = new WPF_Pages;
}



