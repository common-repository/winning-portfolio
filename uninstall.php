<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://pressfore.com
 * @since      1.0.0
 *
 * @package    Winning Portfolio
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$ops = 'wpf_options';

delete_option( $op );

