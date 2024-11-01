<?php

Class WPF_Portfolio {

	function __construct() {
		$this->hooks();
	}	

	/**
	* Register hooks
	*
	* @since 1.0.0
	*
	*/
	public function hooks() {
		add_action( 'init', array( $this, 'register_portfolio' ) );
		add_action( 'init', array( $this, 'register_category' ) );
		add_action( 'init', array( $this, 'image_sizes' ) );
	}

	/**
	* Register hooks
	*
	* @since 1.0.0
	*
	*/
	public function image_sizes() {
		add_image_size( 'wpf-rectangle', 670, 510, true );
	}

	/**
	* Register portfolio post type
	*
	* @since 1.0.0
	*
	*/
	public function register_portfolio() {
		$labels = array(
			'name'               => __( 'Portfolio',  'wpf' ),
			'singular_name'      => __( 'Portfolio', 'wpf' ),
			'menu_name'          => __( 'Portfolio', 'wpf' ),
			'add_new'            => __( 'Add New', 'Portfolio', 'wpf' ),
			'add_new_item'       => __( 'Add New Portfolio', 'wpf' ),
			'new_item'           => __( 'New Portfolio', 'wpf' ),
			'edit_item'          => __( 'Edit Portfolio', 'wpf' ),
			'view_item'          => __( 'View Portfolio', 'wpf' ),
			'all_items'          => __( 'All Portfolios', 'wpf' ),
			'search_items'       => __( 'Search Portfolios', 'wpf' ),
			'parent_item_colon'  => __( 'Parent Portfolio:', 'wpf' ),
			'not_found'          => __( 'No Portfolio found.', 'wpf' ),
			'not_found_in_trash' => __( 'No Portfolio found in Trash.', 'wpf' )
		);

		register_post_type( 'wpf-portfolio', array(
				'labels'			 => $labels,
				'description'        => __( 'Description.', 'wpf' ),
				'public'             => true,
				'publicly_queryable' => true,
				'rewrite'            => array( 'slug' => 'portfolio' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 10,
           		'menu_icon'     	 => PF_PORTF_ASSETS_PATH . 'images/logo-bw.png',
				'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
			)
		);
	}

	/**
	* Register portfolio post type
	*
	* @since 1.0.0
	*
	*/
	public function register_category() {

		$labels = array(
			'name'              => __( 'Categories', 'wpf' ),
			'singular_name'     => __( 'Category', 'wpf' ),
			'search_items'      => __( 'Search Categories', 'wpf' ),
			'all_items'         => __( 'All Categories', 'wpf' ),
			'parent_item'       => __( 'Parent Category', 'wpf' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpf' ),
			'edit_item'         => __( 'Edit Category', 'wpf' ),
			'update_item'       => __( 'Update Category', 'wpf' ),
			'add_new_item'      => __( 'Add New Category', 'wpf' ),
			'new_item_name'     => __( 'New Category Name', 'wpf' ),
			'menu_name'         => __( 'Category', 'wpf' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'rewrite'           => array( 'slug' => 'portfolio_category' ),
		);

		register_taxonomy( 'wportfolio_category', 'wpf-portfolio', $args );

	}
	
}