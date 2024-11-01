<?php

# Include plugin files
require_once 'setup.php';
require_once 'license.php';
require_once 'parts/admin.php';
require_once 'options/options.loader.php';
require_once 'parts/public.php';
require_once 'parts/main-hooks.php';
require_once 'inc/kc_support.php';
require_once 'inc/vc_support.php';

# Meta Data
$name = '_wpf_meta';

# Option Framework
$opname = 'wpf_options';
$options = array(
	'location'   => 'edit.php?post_type=wpf-portfolio',
    'title'  	 => esc_html__( 'Winning Portfolio Options', 'wpf' ),
    'menu_text'  => 'Options',
);

# Initialize parts
$portfolio = new WPF_Portfolio();
$admin = new WPF_Portfolio_Admin($name);
$public = new WPF_Portfolio_Public($name);
$hooks = new WPF_Portfolio_Hooks($name);

# Apply public and admin instance to hooks
$hooks->admin = $admin;
$hooks->public = $public;

# Instantiate options framework
$options = new WPF_Options_Loader($opname, $options);