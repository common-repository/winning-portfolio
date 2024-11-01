<?php

Class WPF_Portfolio_Admin 
{

	/**
	* Main variable that will hold 
	* the meta name
	*
	* @since 1.0.0
	*/
	private $meta;


	public function __construct($name) {
		$this->meta = $name;
		$this->hooks();
	}

	/**
	* Register hooks
	*
	* @since 1.0.0
	*
	*/
	public function hooks() {
        add_filter( 'mce_buttons', array( $this, 'add_plugin' ) );
        add_filter( 'mce_external_plugins', array( $this, 'register_plugin' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
		add_action( 'admin_head', array( $this, 'admin_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets_hook' ) );
	}

	/**
	* Register shortcode plugin
	*
	* @since 1.0.0
	*
	*/
	public function register_plugin($plugins) {
		$plugins['wpf_shortcodes'] = PF_PORTF_ASSETS_PATH . 'js/shortcodes.js';

		return $plugins;
	}

	/**
	* Add shortcode plugin button
	*
	* @since 1.0.0
	*
	*/
	public function add_plugin($buttons) {
		$buttons[] = "wpf_shortcodes";

		return $buttons;
	}

	/**
	* Enqueue admin styles and scripts
	*
	* @since 1.0.0
	*
	*/
	public function assets_hook() {
		// Plugin styles
		$screen = get_current_screen();

	    if( 'wpf-portfolio' === $screen->post_type )
	    {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wpf-admin-css', PF_PORTF_ASSETS_PATH . 'css/admin.css' );
			wp_enqueue_script( 'wpf-admin', PF_PORTF_ASSETS_PATH . 'js/admin.js', array( 'wp-color-picker' ), false, true );
		}	
	}

	/**
	* Register meta box
	*
	* @since 1.0.0
	*
	*/
	public function register_meta() {
		add_meta_box( 'wportf-meta', 'Meta Content', array( $this, 'meta_content' ), 'wpf-portfolio', 'advanced', 'default' );
		add_meta_box( 'wportf-addons-meta', 'Addons', array( $this, 'promo_content' ), 'wpf-portfolio', 'advanced', 'default' );
	}

	/**
	* Meta box callback
	*
	* @since 1.0.0
	*
	*/
	public function meta_content($post) {
		$post_id = $post->ID;
		?>
		<table>
		   <?php
			/**
			* Meta box Form fields action, that is
			* used for hooking the options for
			* portfolio post type
			*
			* @since 1.0.0
			*
			*/
			 do_action( 'wpf_portfolio_meta', $post_id );
		   ?>
		</table>
		<?php
	}

	function promo_content($post) {
		?>
		<div class="wpf-banner-wrapper">
			<div class="wpf-addon">
				<img src="<?php echo PF_PORTF_ASSETS_PATH.'images/bundle.png' ?>" />
				<h3><?php esc_html_e('Bundle Addon','wpf'); ?></h3>
				<p style="color: green; font-style: italic; font-weight: 700; font-size: 13px;"><?php esc_html_e('Save 35% by purchasing the whole bundle','wpf'); ?></p>
				<p><?php esc_html_e('Winning Portfolio Bundle package contain all Winning Portfolio extensions: Winning Portfolio Additions and Winning Portfolio Masonry with all their features. If you want additional hover styles, filtering or just Masonry layout, this bundle contain all that, and it comes with a special price.', 'wpf'); ?></p>
				<a href="http://pressfore.com/item/winning-portfolio-bundle-addon/" target="_blank"><?php esc_html_e('Read More', 'wpf'); ?></a>
			</div>
			<div class="wpf-addon">
				<img src="<?php echo PF_PORTF_ASSETS_PATH.'images/masonry.png' ?>" />
				<h3><?php esc_html_e('Masonry Addon','wpf'); ?></h3>
				<p><?php esc_html_e('Add masonry portfolio with filters to your website','wpf'); ?>
				<p><?php esc_html_e('Winning Portfolio Masonry Addon will allow usage of popular Masonry layout. Beside awesome Masonry layout there are also options for each portfolio item for setting Masonry Image Size and Masonry Image Ratio.', 'wpf'); ?></p>
				<a href="http://pressfore.com/item/winning-portfolio-masonry-addon/" target="_blank"><?php esc_html_e('Read More', 'wpf'); ?></a>
			</div>
			<div class="wpf-addon">
				<img src="<?php echo PF_PORTF_ASSETS_PATH.'images/additions.png' ?>" />
				<h3><?php esc_html_e('Additions Addon','wpf'); ?></h3>
				<p><strong><?php esc_html_e('Add more options and customizations to your portfolio','wpf'); ?></strong></p>
				<p><?php esc_html_e('Winning Portfolio Additions Addon will extend default plugin functionality with query options and filtering which is of great essence, especially when you have many different portfolio categories which needs to be organized and filterable, and all that with nice hover effects.', 'wpf'); ?></p>
				<a href="http://pressfore.com/item/winning-portfolio-additions-addon/" target="_blank"><?php esc_html_e('Read More', 'wpf'); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	* Meta box save
	*
	* @since 1.0.0
	*
	*/
	public function save_meta($post_id) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        	return $post_id;

        if( isset( $_POST['post_type'] ) ) {
	        // Check permissions to edit pages and/or posts
		    if ( 'page' == $_POST['post_type'] ||  'post' == $_POST['post_type']) {
		        if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ))
		          return $post_id;
		    }

			$name = $this->meta;

		    $meta = isset( $_POST[$name] ) ? $this->sanitize($_POST[$name]) : false;

		    update_post_meta( $post_id, $name, $meta );
		 }
	}

	/**
	* Get portfolio meta content
	*
	* @param object $post
	* @return string $data
	* @since 1.0.0
	*
	*/
	public function get_meta($post_id, $meta)
	{
		$data = $this->get_val($post_id, $meta);

		return $data;
	}

	/**
	* Function to get post meta value
	*
	* @param mixed $val
	* @return mixed $data
	* @since 1.0.0
	*
	*/
	public function get_val($id, $val, $is_single = false)
	{
		$meta = $this->meta;
		$value = get_post_meta($id, $meta, $is_single);
		$data = '';

		// if recovered data is string
		if( $is_single ) 
			$data = '' != $value ? $value : false;
		else
			$data = ! empty( $value ) && isset($value[0]) && isset( $value[0][$val] ) ? $value[0][$val] : false;

		return $data;
	}

	/**
	* Function to get post meta value
	*
	* @param mixed $val
	* @return mixed $data
	* @since 1.0.0
	*
	*/
	public function sanitize($meta)
	{
		foreach( $meta as $key => $val ) 
		{
			$meta[$key] = sanitize_text_field( $val );
		}

		return $meta;
	}

	/**
	* Admin inline style
	*
	* @since 1.0.0
	*
	*/
	public function admin_style() {
		?>
			<style type="text/css">
				#wportf-meta input {min-width: 420px;}
			    .kc-components ul.kc-components-list li .cpicon.wpf-icon,
			    .kc-element-icon .wpf-icon.cpicon {
					background-image: url(<?php echo PF_PORTF_ASSETS_PATH . 'images/logo-color-60.png'; ?>);
					background-size: contain;
					background-position: center center;
				}
			</style>
		<?php
	}

}