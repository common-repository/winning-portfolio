<?php
Class WPF_Portfolio_List 
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
		$this->slug = 'general_ops';

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
	    <p id="<?= esc_attr($section ); ?>"><?= esc_html__('Customize the portfolios list display.', 'wpf'); ?></p>
	    <?php
	}

	/**
	* Section options
	*
	* @since 1.0.0
	*
	*/
	public function options($options)
	{
		$section = $this->slug;

		# Layouts
		$layouts = $hover = array();

		/**
		* Custom hook for adding layouts
		*
		* @since 1.0.0
		*/
		$layouts = apply_filters( 'wpf_layouts', $layouts );

		/**
		 * Custom hook for adding hovers
		 *
		 * @since 1.1
		 */
		$hover = apply_filters( 'wpf_hover', $hover );

		# Options Array #

		# Layout
		$options[] = array(
				'section' => $section,  
				'name'    => 'layout',
				'title'   => esc_html__('Portfolio List Layout', 'wpf'),
				'desc'    => esc_html__('Choose the layout that will be applied to portfolio list.', 'wpf'),
				'type'    => 'select',
				'data'    => array(
					'ops'     => $layouts,
					'default' => 'no-space',
				)
		);

		# Layout
		$options[] = array(
				'section' => $section,
				'name'    => 'hover',
				'title'   => esc_html__('Portfolio Hover Style', 'wpf'),
				'desc'    => esc_html__('Choose Hover Style', 'wpf'),
				'type'    => 'select',
				'data'    => array(
					'ops' => $hover,
				)
		);

		# Excerpt Length
		$options[] = array(
				'section' => $section,  
				'name'    => 'excerpt_length',
				'title'   => esc_html__('Excerpt Length', 'wpf'),
				'desc'    => esc_html__('Set the max number of characters allowed in excerpt field', 'wpf'),
				'type'    => 'text',
				'data'    => array( 'default' => 70 ),
		);

		# Excerpt Background
		$options[] = array(
				'section' => $section,  
				'name'    => 'desc_bg',
				'title'   => esc_html__('Excerpt Background Color', 'wpf'),
				'desc'    => esc_html__('Choose the background color for excerpt field, which is shown on hover in portfolio list', 'wpf'),
				'type'    => 'color'
		);

		# Excerpt Color
		$options[] = array(
				'section' => $section,  
				'name'    => 'desc_color',
				'title'   => esc_html__('Excerpt Text Color', 'wpf'),
				'desc'    => esc_html__('Choose the text color for excerpt field, which is shown on hover in portfolio list', 'wpf'),
				'type'    => 'color'
		);

		# Excerpt Title Color
		$options[] = array(
				'section' => $section,  
				'name'    => 'title_color',
				'title'   => esc_html__('Excerpt Title Color', 'wpf'),
				'desc'    => esc_html__('Choose the title text color in excerpt field, which is shown on hover in portfolio list', 'wpf'),
				'type'    => 'color'
		);

		# Excerpt Padding
		$options[] = array(
				'section' => $section,  
				'name'    => 'exerpt_padding',
				'title'   => esc_html__('Description Padding', 'wpf'),
				'desc'    => esc_html__('Set the padding of the exerpt box that will be shown on hover. Add value with the unit - px, em or %. Example - 5px 10px', 'wpf'),
				'type'    => 'text'
		);

		return apply_filters( 'wpf_options_list_section', $options, $section );
	}
}
