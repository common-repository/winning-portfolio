<?php

Class WPF_Portfolio_Hooks 
{

	/**
	* Main variable that will hold 
	* the meta name
	*
	* @since 1.0.0
	*/
	private $meta;

	/**
	* Admin object
	*
	* Main admin class that holds
	* some re-usable methods that can
	* be applied in hooks
	*
	* @since 1.0.0
	*/
	public $admin;

	/**
	* Admin object
	*
	* Main public class that holds
	* some re-usable methods that can
	* be applied in hooks
	*
	* @since 1.0.0
	*/
	public $public;


	public function __construct($name) 
	{
		$this->meta = $name;
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
        add_action( 'wpf_portfolio_meta', array( $this, 'meta_ops' ), 10, 1 );
        add_filter( 'wpf_options_sections', array( $this, 'sections' ), 1, 1 );
        add_filter( 'wpf_content_output', array( $this, 'content' ), 1, 2 );
        add_filter( 'wpf_css_output', array( $this, 'css' ), 1, 1 );
        add_filter( 'wpf_layouts', array( $this, 'list_layouts' ), 1, 1 );
		add_filter( 'wpf_hover', array( $this, 'list_hovers' ), 1, 1 );
        add_filter( 'wpf_external_link', array( $this, 'external_link' ), 1, 2 );
        add_filter( 'wpf_list_class', array( $this, 'list_classes' ), 1, 1 );
        add_filter( 'wpf_excerpt_length', array( $this, 'excerpt_length' ), 1, 1 );
	}

	/**
	* Meta Content
	*
	* @since 1.0.0
	*
	*/
	public function meta_ops($post_id) 
	{	
		$html = '';

		// add fields
		$html .= $this->meta_field($post_id, esc_html__('External Link', 'wpf'), 'portf_link');
		$html .= $this->meta_field($post_id, esc_html__('Date Finished', 'wpf'), 'portf_date'); 

		echo $html;
	}

	/**
	* Meta fields output
	*
	* @since 1.0.0
	*
	*/
	public function meta_field($post_id, $op, $name)
	{
		$base = $this->meta;
		$id = $base;
		$admin = $this->admin;
		$name_attr = $base.'['.$name.']';
		$val = $admin->get_meta($post_id, $name);

		$html = "
			<tr>
				<td>$op</td>
				<td><input type=\"text\" id=\"$id\" name=\"$name_attr\" value=\"$val\" /></td>
			</tr>
		";

		return $html;
	}

	/**
	* Main plugin options
	*
	* @since 1.0.0
	*
	*/
	public function sections($sections)
	{
		# General Section
	    $sections[] = array( 
	    	'name'   => 'general_ops',
	    	'title'  => esc_html__('Portfolio List Options', 'wpf'),
	    	'cb'     => new WPF_Portfolio_List
	    );

	    # Single Portfolio Section
	    $sections[] = array( 
	    	'name'   => 'single_ops',
	    	'title'  => esc_html__('Single Portfolio Options', 'wpf'),
	    	'cb'     => new WPF_Portfolio_Single
	    );

	    return $sections;
	}

	/**
	* Content filter that will apply
	* meta position option, putting it
	* above, or bellow content
	*
	* @since 1.0.0
	*
	*/
	public function content($content, $meta)
	{
		$opname = 'wpf_options';
		$options = get_option($opname);

		# Retrieve meta content position
		$position = isset( $options['meta_pos'] ) ? $options['meta_pos'] : 'above';

		if( 'above' === $position ) $content = $meta.$content;
		else $content = $content.$meta;

		return $content;
	}

	/**
	* Content filter that will apply
	* meta position option, putting it
	* above, or bellow conent
	*
	* @since 1.0.0
	*
	*/
	public function css($css)
	{
		$opname = 'wpf_options';
		$options = get_option($opname);
		$css = array();

		# Excerpt background
		$bg = isset( $options['desc_bg'] ) ? $options['desc_bg'] : 'rgba(0, 0, 0, 0.7)';
		
		# Excerpt Color
		$cl = isset( $options['desc_color'] ) ? $options['desc_color'] : '';
		
		# Excerpt Title Color
		$title = isset( $options['title_color'] ) ? $options['title_color'] : '';

		# Excerpt Box padding
		$box_padd = isset( $options['exerpt_padding'] ) ? $options['exerpt_padding'] : '';
		
		# css output
		if ( '' !== $bg ) {
			$css[] = '.wpf_portfolios .wpf-portfolio-item figcaption.wpf-item-description {background-color: '.esc_attr($bg).'}';
		}

		if ( '' !== $cl ) {
			$css[] = '.wpf_portfolios .wpf-portfolio-item figcaption.wpf-item-description p {color: '.esc_attr($cl).'}';
		}

		if ( '' !== $title ) {
			$css[] = '.wpf_portfolios .wpf-portfolio-item figcaption.wpf-item-description a {color: '.esc_attr($title).'}';
		}

		if ( '' !== $box_padd ) {
			$css[] = '.wpf_portfolios .wpf-portfolio-item .wpf-item-description {padding: '.esc_attr($box_padd).'}';
		}

		$css = apply_filters( 'wpf_inline_css_output', $css, $options );

		return implode(' ', $css );
	}

	/**
	* Content filter that will apply
	* layouts for portfoio list
	*
	* @since 1.0.0
	*
	*/
	public function list_layouts($layouts)
	{

		# Without Space
		$layouts[] = array(
				'val'    => 'no-space',
				'option' => esc_html__('Without Space', 'wpf'),
		);

		# With Space
		$layouts[] = array(
				'val'    => 'with-space',
				'option' => esc_html__('With Small Space', 'wpf'),
		);

		return $layouts;
	}

	/**
	 * Content filter that will apply
	 * hover for portfoio list
	 *
	 * @since 1.0.0
	 *
	 */
	public function list_hovers($hovers)
	{

		# Hover 1
		$hovers[] = array(
				'val'    => 'standard',
				'option' => esc_html__('Standard', 'wpf'),
		);

		# Hover 2
		$hovers[] = array(
				'val'    => 'slide-up',
				'option' => esc_html__('Slide Up', 'wpf'),
		);

		# Hover 3
		$hovers[] = array(
				'val'    => 'white-overlay',
				'option' => esc_html__('White Overlay', 'wpf'),
		);

		return $hovers;
	}

	/**
	* External link filter
	* check for external link option
	* and force the post url if "yes" is selected
	*
	* @since 1.0.0
	*
	*/
	public function external_link($url, $id)
	{
		$opname = 'wpf_options';
		$options = get_option($opname);

		# Get option
		$link_single = isset( $options['link_single'] ) ? $options['link_single'] : 'no';

		if( 'yes' === $link_single ) $url = get_the_permalink($id);

		return $url;
	}

	/**
	* Add additional classes
	*
	* @since 1.0.0
	*
	*/
	public function list_classes($class)
	{
		$opname = 'wpf_options';
		$options = get_option($opname);

		# Get option
		$layout = isset( $options['layout'] ) ? $options['layout'] : 'no-space';
		$hover  = isset( $options['hover'] ) ? $options['hover'] : 'standard';

		$class[] = esc_attr( $layout );
		$class[] = esc_attr( $hover );

		return apply_filters( 'wpf_list_classes', $class );

	}

	/**
	* Excerpt Length
	*
	* @since 1.0.0
	*
	*/
	public function excerpt_length()
	{
		$opname = 'wpf_options';
		$options = get_option($opname);

		# Get option
		$length = isset( $options['excerpt_length'] ) ? $options['excerpt_length'] : 100;

		return $length;

	}

}