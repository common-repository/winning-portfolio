<?php

Class WPF_Portfolio_Options 
{

	/*
	* The field prefix
	* that will be added to settings 
	* field slug
	*/
	private $pre;

	/*
	* The options name
	*/
	private $opname;

	/*
	* The sections array
	*/
	private $sections;

	/*
	* The submenu parameters
	*/
	private $option;

	public function __construct($name, $option) 
	{
		$this->opname = $name;
		$this->option = $option;
		$this->pre = $this->get_prefix($name);
		$this->sections = array();

		$this->hooks();
	}

	/**
	* Register hooks
	*
	* @since 1.0.0
	*
	*/
	public function hooks() 
	{
		add_action('admin_init', array( $this, 'settings_init' ) );
		add_action('admin_menu', array( $this, 'options_page' ) );
	}
	 
	/**
	 * custom option and settings
	 */
	public function settings_init()
	{
	    # Base
		$opname = $this->opname;
	    $pre = $this->pre;
	    $id = str_replace('_', '', $pre);

	    register_setting($id, $opname);

	    # Sections
	    $sections = $this->sections();

	    # Options / Setting fields
	    $options = $this->options();
	 
	 	# Add sections
	 	foreach( $sections as $section )
	 	{
	 		add_settings_section(
	 			$section['name'],
	 			$section['title'],
	 			array( $section['cb'], 'section' ),
	        	$id
	 		);
	 	} 


	    # Add settings fields
	    foreach( $options as $option ) 
	    { 
	    	add_settings_field(
	    		$option['slug'],
	    		$option['title'],
	        	array( $this, 'add_field' ),
	        	$id,
	        	$option['section'], 
	        	[
	        		'label_for'         => $option['slug'],
		            'class'             => 'wpf_row',
		            'wpf_custom_data'   => 'custom',
		            'type'				=> $option['type'],
		            'data'				=> $option['data'],
		            'desc'				=> isset( $option['desc'] ) ? $option['desc'] : ''
	        	]
	    	);
	    }
	 
	}

	/**
	 * Collect the options into array
	 * for the callback in settings
	 */
	public function options()
	{
		# options and data arrays
		$options = array();
		$data = $this->field_data();

		# Add Settings
		foreach( $data as $field ) 
		{ 
			$options[] = array(
				'slug'    => $field['name'],
				'section' => $field['section'],
				'title'   => $field['title'],
				'desc'    => isset( $field['desc'] ) ? $field['desc'] : '',
				'type'    => $field['type'],
				'data'    => isset( $field['data'] ) ? $field['data'] : array()
			); 
		}

		return $options;
	}

	/**
	 * Extract the sections from options
	 * and collect them into single array
	 */
	public function sections()
	{
	    $pre = $this->pre;
	    $sections = array();

		/**
		* Sections filter, that is 
		* used for hooking the option sections 
		*
		* @since 1.0.0
		*
		*/
		$sections = apply_filters( $pre.'options_sections', $sections );

		return $sections;
	}

	/**
	 * Collect the options into array
	 * for the callback in settings
	 */
	public function field_data()
	{
		# data
		$data = array();
		$pre = $this->pre;

		/**
		* Setings fields filter, that is 
		* used for hooking the plugin options 
		*
		* @since 1.0.0
		*
		*/
		$data = apply_filters( $pre.'options_data', $data ); 

		return $data;
	}

	/**
	 * Add settings field cb function
	 */
	public function add_field($args)
	{
		if( ! isset($args['type']) || empty($args['type']) ) $args['type'] = 'text';
		$field = "field_{$args['type']}";
		$html = $this->$field($args);

		echo $html;
	}

	/**
	 * Get prefix from the options
	 */
	public function get_prefix($name)
	{
		$end = strpos( $name, '_' );
		$pre = substr( $name, 0, $end+1 );

		return $pre;
	}
	 
	public function general_ops($args)
	{
	    ?>
	    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Modify the portfolios display.', 'wpf'); ?></p>
	    <?php
	}

	 
	/**
	 * Register Options subpage
	 */
	public function options_page()
	{
		$pre = $this->pre;
	    $id = str_replace('_', '', $pre);
	    $option = $this->option;

	    // add top level menu page
	    add_submenu_page(
	        $option['location'],
	        $option['title'],
	        $option['menu_text'],
	        'manage_options',
	        $id,
	        array( $this, 'options_page_html' )
	    );
	}
	 
	/**
	 * Options callback functions
	 */
	public function options_page_html()
	{
	    // check user capabilities
	    if (!current_user_can('manage_options')) {
	        return;
	    }

		$pre = $this->pre;
	    $id = str_replace('_', '', $pre);
	 
	    // add error/update messages
	 
	    // check if the user have submitted the settings
	    // wordpress will add the "settings-updated" $_GET parameter to the url
	    if (isset($_GET['settings-updated'])) {
	        // add settings saved message with the class of "updated"
	        add_settings_error($pre.'messages', $pre.'message', __('Settings Saved', 'wpf'), 'updated');
	    }
	 
	    // show error/update messages
	    settings_errors($pre.'messages');
	    ?>
	    <div class="wrap">
	        <h1><?= esc_html(get_admin_page_title()); ?></h1>
	        <form action="options.php" method="post">
	            <?php
	            // output security fields for the registered setting "wpf"
	            settings_fields($id);
	            // output setting sections and their fields
	            // (sections are registered for "wpf", each field is registered to a specific section)
	            do_settings_sections($id);
	            // output save settings button
	            submit_button('Save Settings');
	            ?>
	        </form>
	    </div>
	    <?php
	}

	### Settings Field Type ###

	/**
	 * Text field
	 */
	public function field_text($args)
	{
		$opname = $this->opname;
		$value = $this->get_value($opname, $args);
		$name = $this->name_attr($opname, $args);

		$html = '<input type="text" '.$name.' value="'.$value.'" />';

		if( isset($args['desc']) && '' != $args['desc'] ) $html .= '<p class="description">'.$args['desc'].'</p>';
		
		return $html;
	}

	/**
	 * Color field
	 */
	public function field_color($args)
	{
		$opname = $this->opname;
		$value = $this->get_value($opname, $args);
		$name = $this->name_attr($opname, $args);

		$html = '<input type="text" '.$name.' class="color" value="'.$value.'" />';

		if( isset($args['desc']) && '' != $args['desc'] ) $html .= '<p class="description">'.$args['desc'].'</p>';

		return $html;
	}

	/**
	 * select field
	 */
	public function field_select($args)
	{
		$opname = $this->opname;
		$data = $args['data'];
		$value = $this->get_value($opname, $args);

		$html = '<select id="'.esc_attr($args['label_for']).'"';
	    $html .= 'data-custom="'.esc_attr($args['wpf_custom_data']).'"';
	    $html .= $this->name_attr($opname, $args).'>';
	    foreach( $data['ops'] as $key => $val )
	    { 
	    	$html .= '<option value="'.$val['val'].'" '.('' != $value ? (selected($value, $val['val'], false)) : '').'>'.$val['option'].'</option>';
	    }
		$html .= '</select>';

		if( isset($args['desc']) && '' != $args['desc'] ) $html .= '<p class="description">'.$args['desc'].'</p>';

		return $html;
	}

	/**
	 * Get field value
	 */
	public function get_value($opname, $args)
	{
		$options = get_option($opname);
		$name = $args['label_for'];
		$default = isset( $args['data'] ) && isset($args['data']['default']) ? $args['data']['default'] : false;
		$value = '';

		if( isset( $options[$name] ) ) $value = $options[$name];
		if( $default && '' == $value ) $value = $default;

		if( $default && '' == $value ) $value = $default; 

		return $value;
	}

	/**
	 * Get field name
	 */
	public function name_attr($opname, $args)
	{
		$name = 'name="'.$opname.'['.esc_attr($args['label_for']).']"';

		return $name;
	}

	/**
	 * Create section callback function
	 */
	public function section_callback($section)
	{
		$name = $section['name'];
		$title = $section['title'];
		?>
	    <p id="<?= esc_attr($name); ?>"><?= esc_html( $title ); ?></p>
	    <?php

	}
	   
}