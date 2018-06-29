<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiomthemes_woocommerce_theme_setup')) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_woocommerce_theme_setup', 1 );
	function axiomthemes_woocommerce_theme_setup() {

		if (axiomthemes_exists_woocommerce()) {
			
			add_theme_support( 'woocommerce' );
			
			// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
			add_theme_support( 'wc-product-gallery-zoom' );
			
			// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
			add_theme_support( 'wc-product-gallery-slider' );
			
			// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
			add_theme_support( 'wc-product-gallery-lightbox' );
		 
			add_action('axiomthemes_action_add_styles', 				'axiomthemes_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('axiomthemes_filter_get_blog_type',				'axiomthemes_woocommerce_get_blog_type', 9, 2);
			add_filter('axiomthemes_filter_get_blog_title',			'axiomthemes_woocommerce_get_blog_title', 9, 2);
			add_filter('axiomthemes_filter_get_current_taxonomy',		'axiomthemes_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('axiomthemes_filter_is_taxonomy',				'axiomthemes_woocommerce_is_taxonomy', 9, 2);
			add_filter('axiomthemes_filter_get_stream_page_title',		'axiomthemes_woocommerce_get_stream_page_title', 9, 2);
			add_filter('axiomthemes_filter_get_stream_page_link',		'axiomthemes_woocommerce_get_stream_page_link', 9, 2);
			add_filter('axiomthemes_filter_get_stream_page_id',		'axiomthemes_woocommerce_get_stream_page_id', 9, 2);
			add_filter('axiomthemes_filter_detect_inheritance_key',	'axiomthemes_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('axiomthemes_filter_detect_template_page_id',	'axiomthemes_woocommerce_detect_template_page_id', 9, 2);

			add_filter('axiomthemes_filter_list_post_types', 			'axiomthemes_woocommerce_list_post_types', 10, 1);
		}
	}
}

if ( !function_exists( 'axiomthemes_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_woocommerce_settings_theme_setup2', 3 );
	function axiomthemes_woocommerce_settings_theme_setup2() {
		if (axiomthemes_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			axiomthemes_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => '',
				'single_template' => '',
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options
			global $AXIOMTHEMES_GLOBALS;

			axiomthemes_array_insert_before($AXIOMTHEMES_GLOBALS['options'], 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => __('WooCommerce', 'axiomthemes'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => __('WooCommerce products list parameters', 'axiomthemes'),
					"desc" => __("Select WooCommerce products list's style and crop parameters", 'axiomthemes'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => __('Shop list style',  'axiomthemes'),
					"desc" => __("WooCommerce products list's style: thumbs or list with description", 'axiomthemes'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => __('Thumbs', 'axiomthemes'),
						'list' => __('List', 'axiomthemes')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => __('Show style buttons',  'axiomthemes'),
					"desc" => __("Show buttons to allow visitors change list style", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
				"show_currency" => array(
					"title" => __('Show currency selector', 'axiomthemes'),
					"desc" => __('Show currency selector in the user menu', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => __('Show cart button', 'axiomthemes'),
					"desc" => __('Show cart button in the user menu', 'axiomthemes'),
					"std" => "shop",
					"options" => array(
						'hide'   => __('Hide', 'axiomthemes'),
						'always' => __('Always', 'axiomthemes'),
						'shop'   => __('Only on shop pages', 'axiomthemes')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => __('Crop product thumbnail',  'axiomthemes'),
					"desc" => __("Crop product's thumbnails on search results page", 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
				"show_category_bg" => array(
					"title" => __('Show category background',  'axiomthemes'),
					"desc" => __("Show background under thumbnails for the product's categories", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('axiomthemes_woocommerce_theme_setup3')) {
	add_action( 'axiomthemes_action_after_init_theme', 'axiomthemes_woocommerce_theme_setup3' );
	function axiomthemes_woocommerce_theme_setup3() {
		if (axiomthemes_is_woocommerce_page()) {
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'axiomthemes_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'axiomthemes_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'axiomthemes_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'axiomthemes_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'axiomthemes_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 9 );
			add_action(    'woocommerce_after_shop_loop',				'axiomthemes_woocommerce_pagination', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'axiomthemes_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'axiomthemes_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'axiomthemes_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'axiomthemes_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'axiomthemes_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'axiomthemes_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'axiomthemes_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'axiomthemes_woocommerce_after_subcategory_title', 10 );

			add_action(    'woocommerce_product_meta_end',				'axiomthemes_woocommerce_show_product_id', 10);
			
			if (axiomthemes_sc_param_is_on(axiomthemes_get_custom_option('show_post_related'))) {
				add_filter('woocommerce_output_related_products_args', 'axiomthemes_woocommerce_output_related_products_args');
				add_filter('woocommerce_related_products_args', 'axiomthemes_woocommerce_related_products_args');
			} else {
				remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
			}
			
			add_filter(    'woocommerce_product_thumbnails_columns',	'axiomthemes_woocommerce_product_thumbnails_columns' );

			add_filter(    'get_product_search_form',					'axiomthemes_woocommerce_get_product_search_form' );
			
			add_filter(    'loop_shop_columns',							'axiomthemes_woocommerce_loop_shop_columns' );
			add_filter(    'post_class',								'axiomthemes_woocommerce_loop_shop_columns_class' );
			add_filter(    'product_cat_class',							'axiomthemes_woocommerce_loop_shop_columns_class', 10, 3 );
			
			add_action(    'the_title',									'axiomthemes_woocommerce_the_title');
			
			axiomthemes_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'axiomthemes_exists_woocommerce' ) ) {
	function axiomthemes_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'axiomthemes_is_woocommerce_page' ) ) {
	function axiomthemes_is_woocommerce_page() {
		return function_exists('is_woocommerce') ? is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_account_page() : false;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiomthemes_woocommerce_detect_inheritance_key' ) ) {
	//add_filter('axiomthemes_filter_detect_inheritance_key',	'axiomthemes_woocommerce_detect_inheritance_key', 9, 1);
	function axiomthemes_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiomthemes_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'axiomthemes_woocommerce_detect_template_page_id' ) ) {
	//add_filter('axiomthemes_filter_detect_template_page_id',	'axiomthemes_woocommerce_detect_template_page_id', 9, 2);
	function axiomthemes_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'axiomthemes_woocommerce_get_blog_type' ) ) {
	//add_filter('axiomthemes_filter_get_blog_type',	'axiomthemes_woocommerce_get_blog_type', 9, 2);
	function axiomthemes_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';

		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'axiomthemes_woocommerce_get_blog_title' ) ) {
	//add_filter('axiomthemes_filter_get_blog_title',	'axiomthemes_woocommerce_get_blog_title', 9, 2);
	function axiomthemes_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( axiomthemes_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = __('Tag:', 'axiomthemes') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = __( 'Your cart', 'axiomthemes' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = __( 'Checkout', 'axiomthemes' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = __( 'Account', 'axiomthemes' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = axiomthemes_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = axiomthemes_get_post_title($page_id);
			} else {
				$title = __( 'Shop', 'axiomthemes' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'axiomthemes_woocommerce_get_stream_page_title' ) ) {
	//add_filter('axiomthemes_filter_get_stream_page_title',	'axiomthemes_woocommerce_get_stream_page_title', 9, 2);
	function axiomthemes_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (axiomthemes_strpos($page, 'woocommerce')!==false) {
			if (($page_id = axiomthemes_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = axiomthemes_get_post_title($page_id);
			else
				$title = __('Shop', 'axiomthemes');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'axiomthemes_woocommerce_get_stream_page_id' ) ) {
	//add_filter('axiomthemes_filter_get_stream_page_id',	'axiomthemes_woocommerce_get_stream_page_id', 9, 2);
	function axiomthemes_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (axiomthemes_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'axiomthemes_woocommerce_get_stream_page_link' ) ) {
	//add_filter('axiomthemes_filter_get_stream_page_link',	'axiomthemes_woocommerce_get_stream_page_link', 9, 2);
	function axiomthemes_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (axiomthemes_strpos($page, 'woocommerce')!==false) {
			$id = axiomthemes_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'axiomthemes_woocommerce_get_current_taxonomy' ) ) {
	//add_filter('axiomthemes_filter_get_current_taxonomy',	'axiomthemes_woocommerce_get_current_taxonomy', 9, 2);
	function axiomthemes_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( axiomthemes_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiomthemes_woocommerce_is_taxonomy' ) ) {
	//add_filter('axiomthemes_filter_is_taxonomy',	'axiomthemes_woocommerce_is_taxonomy', 9, 2);
	function axiomthemes_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Add custom post type into list
if ( !function_exists( 'axiomthemes_woocommerce_list_post_types' ) ) {
	//add_filter('axiomthemes_filter_list_post_types', 	'axiomthemes_woocommerce_list_post_types', 10, 1);
	function axiomthemes_woocommerce_list_post_types($list) {
		$list['product'] = __('Products', 'axiomthemes');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'axiomthemes_woocommerce_frontend_scripts' ) ) {
	//add_action( 'axiomthemes_action_add_styles', 'axiomthemes_woocommerce_frontend_scripts' );
	function axiomthemes_woocommerce_frontend_scripts() {
		if (axiomthemes_is_woocommerce_page() || axiomthemes_get_custom_option('show_cart')=='always')
			axiomthemes_enqueue_style( 'axiomthemes-woo-style',  axiomthemes_get_file_url('css/woo-style.css'), array(), null );
	}
}

// Replace standard WooCommerce function
/*
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post;
		if ( has_post_thumbnail() ) {
			$s = wc_get_image_size( $size );
			return axiomthemes_get_resized_image_tag($post->ID, $s['width'], axiomthemes_get_theme_option('crop_product_thumb')=='no' ? null :  $s['height']);
			//return get_the_post_thumbnail( $post->ID, array($s['width'], $s['height']) );
		} else if ( wc_placeholder_img_src() )
			return wc_placeholder_img( $size );
	}
}
*/

// Before main content
if ( !function_exists( 'axiomthemes_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//add_action('woocommerce_before_main_content', 'axiomthemes_woocommerce_wrapper_start', 10);
	function axiomthemes_woocommerce_wrapper_start() {
		global $AXIOMTHEMES_GLOBALS;
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !empty($AXIOMTHEMES_GLOBALS['shop_mode']) ? $AXIOMTHEMES_GLOBALS['shop_mode'] : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'axiomthemes_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//add_action('woocommerce_after_main_content', 'axiomthemes_woocommerce_wrapper_end', 10);
	function axiomthemes_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'axiomthemes_woocommerce_show_page_title' ) ) {
	//add_action('woocommerce_show_page_title', 'axiomthemes_woocommerce_show_page_title', 10);
	function axiomthemes_woocommerce_show_page_title($defa=true) {
		//return axiomthemes_get_custom_option('show_post_title')=='yes' || axiomthemes_get_custom_option('show_page_title')=='no' || axiomthemes_get_custom_option('show_page_top')=='no';
		return axiomthemes_get_custom_option('show_page_title')=='no' || axiomthemes_get_custom_option('show_page_top')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'axiomthemes_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//add_action( 'woocommerce_single_product_summary', 'axiomthemes_woocommerce_show_product_title', 5 );
	function axiomthemes_woocommerce_show_product_title() {
		if (axiomthemes_get_custom_option('show_post_title')=='yes' || axiomthemes_get_custom_option('show_page_title')=='no' || axiomthemes_get_custom_option('show_page_top')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'axiomthemes_woocommerce_before_shop_loop' ) ) {
	//add_action( 'woocommerce_before_shop_loop', 'axiomthemes_woocommerce_before_shop_loop', 10 );
	function axiomthemes_woocommerce_before_shop_loop() {
		global $AXIOMTHEMES_GLOBALS;
		if (axiomthemes_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url('http://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])).'" method="post">'
				. '<input type="hidden" name="axiomthemes_shop_mode" value="'.esc_attr($AXIOMTHEMES_GLOBALS['shop_mode']).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr(__('Show products as thumbs', 'axiomthemes')).'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr(__('Show products as list', 'axiomthemes')).'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'axiomthemes_woocommerce_open_thumb_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'axiomthemes_woocommerce_open_thumb_wrapper', 9 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'axiomthemes_woocommerce_open_thumb_wrapper', 9 );
	function axiomthemes_woocommerce_open_thumb_wrapper($cat='') {
		axiomthemes_set_global('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo get_permalink(); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'axiomthemes_woocommerce_open_item_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'axiomthemes_woocommerce_open_item_wrapper', 20 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'axiomthemes_woocommerce_open_item_wrapper', 20 );
	function axiomthemes_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'axiomthemes_woocommerce_close_item_wrapper' ) ) {
	//add_action( 'woocommerce_after_subcategory', 'axiomthemes_woocommerce_close_item_wrapper', 20 );
	//add_action( 'woocommerce_after_shop_loop_item', 'axiomthemes_woocommerce_close_item_wrapper', 20 );
	function axiomthemes_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		axiomthemes_set_global('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'axiomthemes_woocommerce_after_shop_loop_item_title' ) ) {
	//add_action( 'woocommerce_after_shop_loop_item_title', 'axiomthemes_woocommerce_after_shop_loop_item_title', 7);
	function axiomthemes_woocommerce_after_shop_loop_item_title() {
		global $AXIOMTHEMES_GLOBALS;
		if ($AXIOMTHEMES_GLOBALS['shop_mode'] == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'axiomthemes_woocommerce_after_subcategory_title' ) ) {
	//add_action( 'woocommerce_after_subcategory_title', 'axiomthemes_woocommerce_after_subcategory_title', 10 );
	function axiomthemes_woocommerce_after_subcategory_title($category) {
		global $AXIOMTHEMES_GLOBALS;
		if ($AXIOMTHEMES_GLOBALS['shop_mode'] == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'axiomthemes_woocommerce_show_product_id' ) ) {
	//add_action( 'woocommerce_product_meta_end', 'axiomthemes_woocommerce_show_product_id', 10);
	function axiomthemes_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.__('Product ID: ', 'axiomthemes') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'axiomthemes_woocommerce_output_related_products_args' ) ) {
	//add_filter( 'woocommerce_output_related_products_args', 'axiomthemes_woocommerce_output_related_products_args' );
	function axiomthemes_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (axiomthemes_sc_param_is_on(axiomthemes_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(axiomthemes_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  axiomthemes_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (axiomthemes_sc_param_is_off(axiomthemes_get_custom_option('show_sidebar_main')) ? 4+$ccc_add : 3+$ccc_add);
			$ppp = axiomthemes_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'axiomthemes_woocommerce_product_thumbnails_columns' ) ) {
	//add_filter( 'woocommerce_product_thumbnails_columns', 'axiomthemes_woocommerce_product_thumbnails_columns' );
	function axiomthemes_woocommerce_product_thumbnails_columns($cols) {
		return 5;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'axiomthemes_woocommerce_loop_shop_columns_class' ) ) {
	//add_filter( 'post_class', 'axiomthemes_woocommerce_loop_shop_columns_class' );
	function axiomthemes_woocommerce_loop_shop_columns_class($class) {
		if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(axiomthemes_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$class[] = ' column-1_'.(axiomthemes_sc_param_is_off(axiomthemes_get_custom_option('show_sidebar_main')) ? 4+$ccc_add : 3+$ccc_add);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'axiomthemes_woocommerce_loop_shop_columns' ) ) {
	//add_filter( 'loop_shop_columns', 'axiomthemes_woocommerce_loop_shop_columns' );
	function axiomthemes_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(axiomthemes_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		return axiomthemes_sc_param_is_off(axiomthemes_get_custom_option('show_sidebar_main')) ? 4+$ccc_add : 3+$ccc_add;
	}
}

// Search form
if ( !function_exists( 'axiomthemes_woocommerce_get_product_search_form' ) ) {
	//add_filter( 'get_product_search_form', 'axiomthemes_woocommerce_get_product_search_form' );
	function axiomthemes_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/'  ) ) . '">
			<input type="text" class="search_field" placeholder="' . __('Search for products &hellip;', 'axiomthemes') . '" value="' . get_search_query() . '" name="s" title="' . __('Search for products:', 'axiomthemes') . '" /><button class="search_button icon-search-2" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'axiomthemes_woocommerce_the_title' ) ) {
	//add_filter( 'the_title', 'axiomthemes_woocommerce_the_title' );
	function axiomthemes_woocommerce_the_title($title) {
		if (axiomthemes_get_global('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'axiomthemes_woocommerce_pagination' ) ) {
	add_filter( 'woocommerce_after_shop_loop', 'axiomthemes_woocommerce_pagination', 9 );
	function axiomthemes_woocommerce_pagination() {
		axiomthemes_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr(axiomthemes_get_theme_option('blog_pagination_style')),
			'style' => axiomthemes_get_theme_option('blog_pagination_style'),
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => axiomthemes_get_theme_option('blog_pagination_style')=='pages' ? 9 : 9
			)
		);
	}
}
?>