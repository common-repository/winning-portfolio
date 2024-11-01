<?php
/*
 Custom dashboard that will be opened on install, or update
*/

class WPF_Pages {
 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
 
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() { 

		add_action('admin_menu', array( $this,'wpf_register_menu') );
 
	} // end constructor
 
	
	function wpf_register_menu() {
		add_submenu_page( 'edit.php?post_type=wpf-portfolio', 'About', 'About', 'read', 'wpf-about', array( $this,'about') );
		add_submenu_page( 'edit.php?post_type=wpf-portfolio', 'Addons', 'Addons', 'read', 'wpf-addons', array( $this,'addons') );
	}
	
	function about() {
		include_once( 'dashboard.php'  );
	}

	function addons() {
		include_once( 'addons.php'  );
	}
}