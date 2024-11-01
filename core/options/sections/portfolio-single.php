<?php
Class WPF_Portfolio_Single 
{
	/**
	* The section slug
	*
	* @since 1.0.0
	*
	*/
	private $slug;

	public function __construct()
	{
		$this->slug = 'single_ops';
		
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
        add_filter( 'wpf_options_data', array( $this, 'options' ), 1, 1 );
	}

	/**
	* The section markup
	*
	* @since 1.0.0
	*
	*/
	public function section()
	{
		$section = $this->slug;
		?>
	    <p id="<?= esc_attr($section); ?>"><?= esc_html__('Customize the portfolio single page display.', 'wpf'); ?></p>
	    <?php
	}

	/**
	* Main plugin options
	*
	* @since 1.0.0
	*
	*/
	public function options($options)
	{
		$section = $this->slug;

		# Meta Position
		$options[] = array(
				'section' => $section,  
				'name'    => 'meta_pos',
				'title'   => esc_html__('Custom Meta Position', 'wpf'),
				'desc'    => esc_html__('Choose to show custom fields (like link, date created, etc.) before the main content on the single portfolio page, or after the main content.', 'wpf'),
				'type'    => 'select',
				'data'    => array(
					'ops' => array(
						0 => array(
							'val'    => 'above',
							'option' => esc_html__('Above Content', 'wpf'),
						),
						1 => array(
							'val'    => 'bellow',
							'option' => esc_html__('Bellow Content', 'wpf'),
						)
					),
					'default' => 'above',
				)
		);
		
		# Link display
		$options[] = array(
				'section' => $section,  
				'name'    => 'link_single',
				'title'   => esc_html__('External Link in Single Portfolio', 'wpf'),
				'desc'    => esc_html__('Choose to show external link field on the single portfolio page, instead of pointing to the url directly from list. This way when you click on portfolio in list, it will open single portfolio page, and with other custom data, like date finished, external link will be show.', 'wpf'),
				'type'    => 'select',
				'data'    => array(
					'ops' => array(
						0 => array(
							'val'    => 'yes',
							'option' => esc_html__('Yes', 'wpf'),
						),
						1 => array(
							'val'    => 'no',
							'option' => esc_html__('No', 'wpf'),
						)
					),
					'default' => 'no',
				)
		);

		return $options;
	}
}
