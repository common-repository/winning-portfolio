<?php

Class WPF_Portfolio_Public 
{
	/**
	* Main variable that will hold 
	* the meta name
	*
	* @since 1.0.0
	*/
	private $meta;

	/**
	* Main constructor function
	*
	* @since 1.0.0
	*/
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
		add_shortcode( 'wpf-portfolio', array( $this, 'portfolio_list' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets_hook' ) );
		add_action( 'wp_head', array( $this, 'css_output' ) );
		add_filter( 'the_content', array( $this, 'content_filter' ) );
	}

	/**
	* Enqueue main styles and scripts
	*
	* @since 1.0.0
	*
	*/
	public function assets_hook() {
		// Plugin styles
		wp_enqueue_style('wpf-portfolio-css', PF_PORTF_ASSETS_PATH.'css/public.css');
	}

	/**
	* Portfolio List Shortcode
	*
	* @since 1.0.0
	*
	*/
	public function portfolio_list($atts) {
		# extract attributes
		extract( shortcode_atts( array(
			'show' => -1,
			'category' => '',
			'order'    => '',
			'organize' => '',
			'columns'  => '4'
		), $atts ) );

		if( '' === $show || 'all' == $show ) $show = -1;

		$args = array(
			'post_type'      => 'wpf-portfolio',
			'posts_per_page' => $show,
			'post_staus'     => 'publish'
		); 

		if( '' !== $category ) {
			$args['tax_query'] = array( array(
					'taxonomy'  => 'wportfolio_category',
					'field'     => 'name',
					'terms'     => $category
			) );
		}

		if( 'random' === $order ) {
			$order = 'rand';
		}
		if( '' !== $order ) {
			$args['orderby'] = $order;
		}
		if( '' !== $organize ) {
			$args['order'] = $organize;
		}

		/**
		 * Custom hook for query args
		 *
		 * @since 1.1
		 */
		$args = apply_filters( 'wpf_shortcode_list_args', $args, $atts );

		$html = '';

		if( $columns > 6 ) {
			$columns = 6;
		}

		$columns = 'wpf-columns-' . $columns;
		$class = array( 'wpf_portfolios', 'clearfix', esc_attr( $columns ) );

		/**
		* Custom hook adding/removing classes
		*
		* @since 1.0.0
		*/
		$class = apply_filters( 'wpf_list_class', $class, $atts );

		# Implode classes for later use
		$class = implode(' ', $class);

		$portfolio = new WP_Query( $args );

		# filter menu
		$html .= apply_filters( 'wpf_before_list_markup', '' );
		# /filter menu end

		# portfolio section starts
		$html .= '<div class="' . esc_attr( $class ) . '">';

		/**
		 * Custom hook for adding content
		 * before the portfolio list markup
		 *
		 * @since 1.0.0
		 */
		$html .= apply_filters( 'wpf_after_list_opening', '' );

		if( $portfolio->have_posts() ) {
			while( $portfolio->have_posts() ) {
				$opname  = 'wpf_options';
				$options = get_option($opname);
				$hover   = isset( $options['hover'] ) ? $options['hover'] : 'standard';

				# advance to the next post
				$portfolio->the_post();
				$id = get_the_ID();
				$url = '' != get_post_meta($id, 'portf_link', true) 
					    ? get_post_meta($id, 'portf_link', true)
					    : get_the_permalink($id);

				/**
				* Custom hook for setting image size
				*
				* @since 1.0.0
				*/
				$img = apply_filters( 'wpf_image_size', 'wpf-rectangle' );

				/**
				* Custom hook for modifying url
				* 
				* Currently used to apply external link
				* option, if "yes" is selected force the 
				* default post link.
				*
				* @since 1.0.0
				*/
				$url = apply_filters( 'wpf_external_link', $url, $id );

				/**
				 * Custom hook for additional wrapper
				 *
				 * this will be used fo conditional to
				 * add additional div wrapper inside description
				 *
				 * @since 1.1
				 */
				$additional_wrap = apply_filters( 'wpf_additional_hover_wrap', false, $hover );

				if( 'white-overlay' === $hover ) {
					$additional_wrap = true;
				}

				// Item classes.
				$item_class = apply_filters( 'wpf_list_item_class', array( 'wpf-portfolio-item' ), $id );
				$item_class = join( ' ', $item_class );

				// Item data.
				$item_data  = apply_filters( 'wpf_list_item_data', array(), $id );
				$item_data = join( ' ', $item_data );

				# Porfolio output
				$html .= '<div class="' . esc_attr( $item_class ) . '" ' . esc_attr( $item_data ) . '>';

				/**
				* Custom hook for adding content
				* before the portfolio item markup
				*
				* @since 1.0.0
				*/
				$html .= apply_filters( 'wpf_pre_list_output', '' );

				$image = '<div class="wpf-portfolio-img">';
				$image .= '<a class="wpf-portfolio-link" href="' . $url . '"></a>';
				$image .= get_the_post_thumbnail( $id, $img );
				$image .= '</div><!-- .wpf-portfolio-img -->';

				$html .= '<figure class="wpf-portfolio-inner">';
				$html .= apply_filters( 'wpf_portfolio_list_image_markup', $image, $id, $img, $url );
				$html .= '<figcaption class="wpf-item-description">';
				$html .= '<div class="wpf-description-wrap">';
				if ( $additional_wrap ) {
					$html .= '<div class="wpf-inner-wrap">';
				}
				$html .= '<h5><a class="wpf-portfolio-link" href="' . $url . '">';
				$html .= get_the_title( $id );
				$html .= '</a></h5>';
				$html .= '<p>' . $this->excerpt() . '</p>';
				if ( $additional_wrap ) {
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</figcaption>';
				$html .= '</figure>';

				/**
				* Custom hook for adding content
				* after the portfolio item markup
				*
				* @since 1.0.0
				*/
				$html .= apply_filters( 'wpf_after_list_output', '' );

				$html .= '</div><!-- .wpf-portfolio-item -->';
				# /portfolio end

			}
			
		}

		/**
		 * Before list close tag filter
		 *
		 * @since 1.0.0
		 */
		$html .= apply_filters( 'wpf_before_list_close', '' );

		# /portfolio section end
		$html .= '</div><!-- .portfolios.clearfix-->';

		wp_reset_postdata();

		return $html;

	}

	/**
	* Add portfolio meta content to the
	* post content
	*
	* @since 1.0.0
	*
	*/
	public function content_filter($content)
	{
		global $post;
		$type = $post->post_type;

		if( is_singular() && 'wpf-portfolio' === $type ) 
		{
			$link = $this->get_meta($post, 'portf_link');
			$date = $this->get_meta($post, 'portf_date');

			# Meta contenet output
			$meta = '<div class="wpf-portfolio-meta">';

			/**
			* Filter that fires before default
			* meta content is added 
			*
			* @since 1.0.0
			*
			*/
			$meta .= apply_filters( 'wpf_pre_meta', '' );

			if( $link ) $meta .= '<span class="wpf-link"><strong>'.esc_html__('Portfolio Link','wpf').':</strong> <a href="' . esc_url($link) . '">' . esc_html( $link ) . '</a></span>';
			if( $date ) $meta .= '<span class="wpf-date"><strong>'.esc_html__('Date Finished','wpf').':</strong> ' . esc_html( $date ) . '</span>';

			/**
			* Filter that fires after default
			* meta content is added 
			*
			* @since 1.0.0
			*
			*/
			$meta .= apply_filters( 'wpf_after_meta', '' );

			$meta .= '</div>';

			# Final output
			$content = apply_filters( 'wpf_content_output', $content, $meta );
		}

		return $content;
	}

	/**
	* Add portfolio meta content to the
	* post content
	*
	* @since 1.0.0
	*
	*/
	public function css_output()
	{
		$css = '<style type="text/css" id="wpf-custom">';

		/**
		* Filter that holds all custom css
		* which will be applied inline on the page
		*
		* @since 1.0.0
		*
		*/
		$css .= apply_filters( 'wpf_css_output', '' );

		$css .= '</style>';

		echo $css;
	}

	/**
	* Get portfolio meta content
	*
	* @param object $post
	* @return string $data
	* @since 1.0.0
	*
	*/
	public function get_meta($post, $meta)
	{
		$id = $post->ID;
		$data = $this->get_val($id, $meta);

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
		if( $is_single ) {
			$data = '' != $value ? $value : false;
		}
		else {
			$data = !empty($value) && isset($value[0]) && isset($value[0][$val]) ? $value[0][$val] : false;
		}

		return $data;
	}

	/**
	* The excerpt function
	*
	* If there is no excerpt limit the contenet output
	*
	* @link https://codex.wordpress.org/Function_Reference/get_the_excerpt
	* @param mixed $val
	* @return mixed $data
	* @since 1.0.0
	*
	*/
	public function excerpt()
	{
		$excerpt = get_the_excerpt();
		$charlength = apply_filters('wpf_excerpt_length', 110);

		if ( mb_strlen( $excerpt ) > $charlength ) 
		{
			$subex = mb_substr( $excerpt, 0, $charlength );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) 
			{
				return mb_substr( $subex, 0, $excut );
			} 
			else 
			{
				return $subex;
			}
		} 
		else 
		{
			return $excerpt;
		}
	}
}