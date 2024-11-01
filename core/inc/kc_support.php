<?php

if ( is_plugin_active( 'kingcomposer/kingcomposer.php' ) ){
    add_action('init', 'wpf_portfolio_box_params', 99 );
}


function wpf_portfolio_box_params()
{
    global $kc;
    $kc->add_map(
        array(
            'wpf-portfolio' => array(
                'name' => 'Portfolio',
                'description' => __('Add portfolio list', 'wpf'),
                'icon' => 'wpf-icon',
                //'is_container' => true,
                'category' => 'content',
                'css_box'    => true,
                'params' => array(

                 'General' => apply_filters( 'wpf_kc_params', array(
	                 array(
		                 'type'			=> 'dropdown',
		                 'label'	    => __( 'Hover Style', 'wpf' ),
		                 'name'			=> 'hover',
		                 'admin_label'	=> true,
		                 'options'		=> array(
				                 'standard'      => 'Standard',
				                 'slide-up'      => 'Slide Up',
				                 'white-overlay' => 'White Overlay'
		                 ),
	                 ),

	                array(
	                    'type'			=> 'text',
	                    'label'			=> __( 'Number Of Portfolios', 'wpf' ),
	                    'name'			=> 'show',
	                    'description'	=> __( 'Limit Number of displayed items. To show all leave it empty or enter -1.', 'wpf' ),
	                    'admin_label'	=> true,
	                ),

	                array(
	                    'type'			=> 'text',
	                    'label'			=> __( 'Category', 'wpf' ),
	                    'name'			=> 'category',
	                    'description'	=> __( 'Show items only from selected category, or comma separated list of categories', 'wpf' ),
	                    'admin_label'	=> true,
	                ),

	                array(
	                    'type'			=> 'dropdown',
	                    'label'			=> __( 'Order', 'wpf' ),
	                    'name'			=> 'order',
	                    'description'	=> __( 'Show items from selected category', 'wpf' ),
	                    'admin_label'	=> true,
	                    'options'		=> array(
		                  'title'  => 'Title',
		                  'date'   => 'Date',
		                  'author' => 'Author',
		                  'random' => 'Random',
		                ),

	                ),

	                array(
	                    'type'			=> 'dropdown',
	                    'label'			=> __( 'Organize', 'wpf' ),
	                    'name'			=> 'organize',
	                    'description'	=> __( 'Organize results', 'wpf' ),
	                    'admin_label'	=> true,
	                    'options'		=> array(
		                  'ASC'  => 'Ascending',
		                  'DESC' => 'Descending',
		                ),

	                ),

	                array(
	                    'type'			=> 'dropdown',
	                    'label'			=> __( 'Columns', 'wpf' ),
	                    'name'			=> 'columns',
	                    'description'	=> __( 'Select Number of Columns in which portfolios will be listed. Columns will take equal widths, so if you select 4 columns, each portfolio will have width of 25%, etc.', 'wpf' ),
	                    'admin_label'	=> true,
	                    'options'		=> array(
		                  '1' => '1',
		                  '2' => '2',
		                  '3' => '3',
		                  '4' => '4',
		                  '6' => '6',
		                ),

	                ),
                ) ),
                
                'Typography' => array(
                    array(
                        'name' => 'title_color',
                        'label' => 'Title Color',
                        'type' => 'color_picker',
                        'admin_label' => true,
                        'value' => '#4A4A4A',

                    ), 
                      array(
                        'name' => 'descr_color',
                        'label' => 'Description Color',
                        'type' => 'color_picker',
                        'admin_label' => true,
                        'value' => '#4A4A4A',
                        
                    ), 
                      array(
                        'name' => 'title_f_size',
                        'label' => 'Title font size',
                        'type' => 'number_slider',
                        'options' => array(
                            'min' => 0,
                            'max' => 40,
                            'unit' => 'px',
                            'show_input' => true
                        ),
                        'value' => '20',
                        'description' => 'Chose Title Font Size here, Default is 14px'
                    ), 
                                        array(
                        'name' => 'descr_f_size',
                        'label' => 'Description font size',
                        'type' => 'number_slider',
                        'options' => array(
                            'min' => 0,
                            'max' => 40,
                            'unit' => 'px',
                            'show_input' => true
                        ),
                        'value' => '14',
                        'description' => 'Chose Description Font Size here, Default is 14px'
                    ), 
                ),
                      
                )
            )
        )
    );
}