<?php
/**
 * Axiomthemes Framework: Theme specific actions
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_core_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_core_theme_setup', 11 );
	function axiomthemes_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(axiomthemes_get_file_url('css/editor-style.css'));
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'axiomthemes', axiomthemes_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */

			// Get theme calendar (instead standard WP calendar) to support Events
			add_filter( 'get_calendar',						'axiomthemes_get_calendar' );
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'axiomthemes_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'axiomthemes_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('axiomthemes_filter_prepare_logo_text',	'axiomthemes_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'axiomthemes_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'axiomthemes_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'axiomthemes_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'axiomthemes_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'axiomthemes_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'axiomthemes_core_frontend_scripts');
			add_action('wp_footer',		 					'axiomthemes_core_frontend_scripts_inline');
			add_action('axiomthemes_action_add_scripts_inline','axiomthemes_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('axiomthemes_action_prepare_globals',	'axiomthemes_core_prepare_globals');

		}

		// Register theme specific nav menus
		axiomthemes_register_theme_menus();

		// Register theme specific sidebars
		axiomthemes_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function axiomthemes_core_init_theme() {
	global $AXIOMTHEMES_GLOBALS;
	if (!empty($AXIOMTHEMES_GLOBALS['theme_inited'])) return;
	$AXIOMTHEMES_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (axiomthemes_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	axiomthemes_load_custom_options();

	// Load skin
	$skin = axiomthemes_esc(axiomthemes_get_custom_option('theme_skin'));
	$AXIOMTHEMES_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(axiomthemes_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( axiomthemes_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after custom options loaded)
	do_action('axiomthemes_action_init_theme');

	// Prepare theme core global variables
	do_action('axiomthemes_action_prepare_globals');

	// Fire after init theme actions
	do_action('axiomthemes_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'axiomthemes_core_prepare_globals' ) ) {
	function axiomthemes_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $AXIOMTHEMES_GLOBALS;
			$AXIOMTHEMES_GLOBALS['ajax_nonce'] = wp_create_nonce('ajax_nonce');
			$AXIOMTHEMES_GLOBALS['ajax_url'] = admin_url('admin-ajax.php');
		
			// Logo text and slogan
			$AXIOMTHEMES_GLOBALS['logo_text'] = apply_filters('axiomthemes_filter_prepare_logo_text', axiomthemes_get_custom_option('logo_text'));
			$slogan = axiomthemes_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$AXIOMTHEMES_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = axiomthemes_get_logo_icon('logo_side');
			$logo_fixed  = axiomthemes_get_logo_icon('logo_fixed');
			$logo_footer = axiomthemes_get_logo_icon('logo_footer');
			$AXIOMTHEMES_GLOBALS['logo_icon']   = axiomthemes_get_logo_icon('logo_icon');
			$AXIOMTHEMES_GLOBALS['logo_dark']   = axiomthemes_get_logo_icon('logo_dark');
			$AXIOMTHEMES_GLOBALS['logo_light']  = axiomthemes_get_logo_icon('logo_light');
			$AXIOMTHEMES_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $AXIOMTHEMES_GLOBALS['logo_dark'];
			$AXIOMTHEMES_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $AXIOMTHEMES_GLOBALS['logo_dark'];
			$AXIOMTHEMES_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $AXIOMTHEMES_GLOBALS['logo_dark'];
	
			$shop_mode = '';
			if (axiomthemes_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = axiomthemes_get_value_gpc('axiomthemes_shop_mode');
			if (empty($shop_mode))
				$shop_mode = axiomthemes_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$AXIOMTHEMES_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'axiomthemes_get_logo_icon' ) ) {
	function axiomthemes_get_logo_icon($slug) {
		global $AXIOMTHEMES_GLOBALS;
		$skin = axiomthemes_esc($AXIOMTHEMES_GLOBALS['theme_skin']);
		$logo_icon = axiomthemes_get_custom_option($slug);
		if (empty($logo_icon) && axiomthemes_get_theme_option('logo_from_skin')=='yes' && file_exists(axiomthemes_get_file_dir('skins/' . ($skin) . '/images/' . ($slug) . '.png')))
			$logo_icon = axiomthemes_get_file_url('skins/' . ($skin) . '/images/' . ($slug) . '.png');
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'axiomthemes_register_theme_menus' ) ) {
	function axiomthemes_register_theme_menus() {
		register_nav_menus(apply_filters('axiomthemes_filter_add_theme_menus', array(
			'menu_main' => __('Main Menu', 'axiomthemes'),
			'menu_user' => __('User Menu', 'axiomthemes'),
			'menu_side' => __('Side Menu', 'axiomthemes')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'axiomthemes_register_theme_sidebars' ) ) {
	function axiomthemes_register_theme_sidebars($sidebars=array()) {
		global $AXIOMTHEMES_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = axiomthemes_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'axiomthemes_filter_add_theme_sidebars', $sidebars );
		$AXIOMTHEMES_GLOBALS['registered_sidebars'] = $sidebars;
		if (count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
				) );
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'axiomthemes_core_frontend_scripts' ) ) {
	function axiomthemes_core_frontend_scripts() {
		global $wp_styles, $AXIOMTHEMES_GLOBALS;
		
		// Modernizr will load in head before other scripts and styles
		//axiomthemes_enqueue_script( 'axiomthemes-core-modernizr-script', axiomthemes_get_file_url('js/modernizr.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = axiomthemes_get_list_fonts(false);
		$theme_fonts = array();
		if (axiomthemes_get_custom_option('typography_custom')=='yes') {
			$selectors = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
			foreach ($selectors as $s) {
				$font = axiomthemes_get_custom_option('typography_'.($s).'_font');
				if (!empty($font)) $theme_fonts[$font] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('axiomthemes_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		foreach ($theme_fonts as $font=>$v) {
			if (isset($fonts[$font])) {
				$font_name = ($pos=axiomthemes_strpos($font,' ('))!==false ? axiomthemes_substr($font, 0, $pos) : $font;
				$css = !empty($fonts[$font]['css']) 
					? $fonts[$font]['css'] 
					: 'http://fonts.googleapis.com/css?family='
						.(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic')
						.(empty($fonts[$font]['link']) || axiomthemes_strpos($fonts[$font]['link'], 'subset=')===false ? '&subset=latin,latin-ext,cyrillic,cyrillic-ext' : '');
				axiomthemes_enqueue_style( 'theme-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
			}
		}
		
		// Fontello styles must be loaded before main stylesheet
		axiomthemes_enqueue_style( 'axiomthemes-fontello-style',  axiomthemes_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//axiomthemes_enqueue_style( 'axiomthemes-fontello-animation-style', axiomthemes_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		axiomthemes_enqueue_style( 'axiomthemes-main-style', get_stylesheet_uri(), array(), null );
		
		if (axiomthemes_get_theme_option('debug_mode')=='no' && axiomthemes_get_theme_option('packed_scripts')=='yes' && file_exists(axiomthemes_get_file_dir('css/__packed.css'))) {
			// Load packed styles
			axiomthemes_enqueue_style( 'axiomthemes-packed-style',  		axiomthemes_get_file_url('css/__packed.css'), array(), null );
		} else {
			// Shortcodes
			axiomthemes_enqueue_style( 'axiomthemes-shortcodes-style',	axiomthemes_get_file_url('shortcodes/shortcodes.css'), array(), null );
			// Animations
			if (axiomthemes_get_theme_option('css_animation')=='yes')
				axiomthemes_enqueue_style( 'axiomthemes-animation-style',	axiomthemes_get_file_url('css/core.animation.css'), array(), null );
		}
		// Theme skin stylesheet
		do_action('axiomthemes_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		axiomthemes_enqueue_custom_styles();

		// Responsive
		if (axiomthemes_get_theme_option('responsive_layouts') == 'yes') {
			axiomthemes_enqueue_style( 'axiomthemes-responsive-style', axiomthemes_get_file_url('css/responsive.css'), array(), null );
			do_action('axiomthemes_action_add_responsive');
			if (axiomthemes_get_custom_option('theme_skin')!='') {
				$css = apply_filters('axiomthemes_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'axiomthemes-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		if (axiomthemes_get_theme_option('debug_mode')=='no' && axiomthemes_get_theme_option('packed_scripts')=='yes' && file_exists(axiomthemes_get_file_dir('js/__packed.js'))) {
			// Load packed theme scripts
			axiomthemes_enqueue_script( 'axiomthemes-packed-scripts', axiomthemes_get_file_url('js/__packed.js'), array('jquery'), null, true);
		} else {
			// Load separate theme scripts
			axiomthemes_enqueue_script( 'superfish', axiomthemes_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
			if (axiomthemes_get_theme_option('menu_slider')=='yes') {
				axiomthemes_enqueue_script( 'axiomthemes-slidemenu-script', axiomthemes_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
				//axiomthemes_enqueue_script( 'axiomthemes-jquery-easing-script', axiomthemes_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
			}
			
			// Load this script only if any shortcode run
			//axiomthemes_enqueue_script( 'axiomthemes-shortcodes-script', axiomthemes_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );

			if ( is_single() && axiomthemes_get_custom_option('show_reviews')=='yes' ) {
				axiomthemes_enqueue_script( 'axiomthemes-core-reviews-script', axiomthemes_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
			}

			axiomthemes_enqueue_script( 'axiomthemes-core-utils-script', axiomthemes_get_file_url('js/core.utils.js'), array('jquery'), null, true );
			axiomthemes_enqueue_script( 'axiomthemes-core-init-script', axiomthemes_get_file_url('js/core.init.js'), array('jquery'), null, true );
		}

		// Media elements library	
		if (axiomthemes_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (axiomthemes_get_custom_option('show_video_bg') == 'yes' && axiomthemes_get_custom_option('video_bg_youtube_code') != '') {
			axiomthemes_enqueue_script( 'axiomthemes-video-bg-script', axiomthemes_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( axiomthemes_get_custom_option('show_googlemap')=='yes' ) {
			$api_key = axiomthemes_get_theme_option('api_google');
			axiomthemes_enqueue_script( 'googlemap', axiomthemes_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			axiomthemes_enqueue_script( 'axiomthemes-googlemap-script', axiomthemes_get_file_url('js/core.googlemap.js'), array(), null, true );
		}

		// Social share buttons
		if (is_singular() && !axiomthemes_get_global('blog_streampage') && axiomthemes_get_custom_option('show_share')!='hide') {
			axiomthemes_enqueue_script( 'axiomthemes-social-share-script', axiomthemes_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !axiomthemes_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (axiomthemes_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(axiomthemes_get_file_dir('core/core.customizer/front.customizer.css')))
				axiomthemes_enqueue_style(  'axiomthemes-customizer-style',  axiomthemes_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(axiomthemes_get_file_dir('core/core.customizer/front.customizer.js')))
				axiomthemes_enqueue_script( 'axiomthemes-customizer-script', axiomthemes_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (axiomthemes_get_theme_option('debug_mode')=='yes') {
			axiomthemes_enqueue_script( 'axiomthemes-core-debug-script', axiomthemes_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('axiomthemes_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'axiomthemes_enqueue_slider' ) ) {
	function axiomthemes_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('css/__packed.css'))) {
				axiomthemes_enqueue_style( 'axiomthemes-swiperslider-style', axiomthemes_get_file_url('js/swiper/idangerous.swiper.css'), array(), null );
			}
			if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('js/__packed.js'))) {
				axiomthemes_enqueue_script( 'axiomthemes-swiperslider-script', 			axiomthemes_get_file_url('js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
				axiomthemes_enqueue_script( 'axiomthemes-swiperslider-scrollbar-script',	axiomthemes_get_file_url('js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
			}
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'axiomthemes_enqueue_messages' ) ) {
	function axiomthemes_enqueue_messages() {
		if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('css/__packed.css'))) {
			axiomthemes_enqueue_style( 'axiomthemes-messages-style',		axiomthemes_get_file_url('js/core.messages/core.messages.css'), array(), null );
		}
		if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('js/__packed.js'))) {
			axiomthemes_enqueue_script( 'axiomthemes-messages-script',	axiomthemes_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
		}
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'axiomthemes_enqueue_portfolio' ) ) {
	function axiomthemes_enqueue_portfolio($hover='') {
		if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('css/__packed.css'))) {
			axiomthemes_enqueue_style( 'axiomthemes-portfolio-style',  axiomthemes_get_file_url('css/core.portfolio.css'), array(), null );
			if (axiomthemes_strpos($hover, 'effect_dir')!==false)
				axiomthemes_enqueue_script( 'hoverdir', axiomthemes_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
		}
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'axiomthemes_enqueue_diagram' ) ) {
	function axiomthemes_enqueue_diagram($type='all') {
		if (axiomthemes_get_theme_option('debug_mode')=='yes' || axiomthemes_get_theme_option('packed_scripts')=='no' || !file_exists(axiomthemes_get_file_dir('js/__packed.js'))) {
			if ($type=='all' || $type=='pie') axiomthemes_enqueue_script( 'axiomthemes-diagram-chart-script',	axiomthemes_get_file_url('js/diagram/chart.min.js'), array(), null, true );
			if ($type=='all' || $type=='arc') axiomthemes_enqueue_script( 'axiomthemes-diagram-raphael-script',	axiomthemes_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
		}
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'axiomthemes_enqueue_popup' ) ) {
	function axiomthemes_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && axiomthemes_get_theme_option('popup_engine')=='pretty')) {
			axiomthemes_enqueue_style(  'axiomthemes-prettyphoto-style',	axiomthemes_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			axiomthemes_enqueue_script( 'axiomthemes-prettyphoto-script',	axiomthemes_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && axiomthemes_get_theme_option('popup_engine')=='magnific')) {
			axiomthemes_enqueue_style(  'axiomthemes-magnific-style',	axiomthemes_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			axiomthemes_enqueue_script( 'axiomthemes-magnific-script',axiomthemes_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && axiomthemes_get_theme_option('popup_engine')=='internal')) {
			axiomthemes_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'axiomthemes_core_frontend_scripts_inline' ) ) {
	function axiomthemes_core_frontend_scripts_inline() {
		do_action('axiomthemes_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('axiomthemes_core_add_scripts_inline')) {
	function axiomthemes_core_add_scripts_inline() {
		global $AXIOMTHEMES_GLOBALS;

		$msg = axiomthemes_get_system_message(true);
		if (!empty($msg['message'])) axiomthemes_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			. "jQuery(document).ready(function() {"
			
			// AJAX parameters
			. "AXIOMTHEMES_GLOBALS['ajax_url']			= '" . esc_url($AXIOMTHEMES_GLOBALS['ajax_url']) . "';"
			. "AXIOMTHEMES_GLOBALS['ajax_nonce']		= '" . esc_attr($AXIOMTHEMES_GLOBALS['ajax_nonce']) . "';"
			. "AXIOMTHEMES_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('axiomthemes_editor_nonce')) . "';"
			
			// Site base url
			. "AXIOMTHEMES_GLOBALS['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "AXIOMTHEMES_GLOBALS['vc_edit_mode']		= " . (axiomthemes_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "AXIOMTHEMES_GLOBALS['theme_font']		= '" . (axiomthemes_get_custom_option('typography_custom')=='yes' ? axiomthemes_get_custom_option('typography_p_font') : '') . "';"
			
			// Theme skin
			. "AXIOMTHEMES_GLOBALS['theme_skin']		= '" . esc_attr(axiomthemes_get_custom_option('theme_skin')) . "';"
			. "AXIOMTHEMES_GLOBALS['theme_skin_bg']	= '" . axiomthemes_get_theme_bgcolor() . "';"
			
			// Slider height
			. "AXIOMTHEMES_GLOBALS['slider_height']	= " . max(100, axiomthemes_get_custom_option('slider_height')) . ";"
			
			// System message
			. "AXIOMTHEMES_GLOBALS['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "AXIOMTHEMES_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "AXIOMTHEMES_GLOBALS['toc_menu']		= '" . esc_attr(axiomthemes_get_custom_option('menu_toc')) . "';"
			. "AXIOMTHEMES_GLOBALS['toc_menu_home']	= " . (axiomthemes_get_custom_option('menu_toc')!='hide' && axiomthemes_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "AXIOMTHEMES_GLOBALS['toc_menu_top']	= " . (axiomthemes_get_custom_option('menu_toc')!='hide' && axiomthemes_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "AXIOMTHEMES_GLOBALS['menu_fixed']		= " . (axiomthemes_get_theme_option('menu_position')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "AXIOMTHEMES_GLOBALS['menu_relayout']	= " . max(0, (int) axiomthemes_get_theme_option('menu_relayout')) . ";"
			. "AXIOMTHEMES_GLOBALS['menu_responsive']	= " . (axiomthemes_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) axiomthemes_get_theme_option('menu_responsive')) : 0) . ";"
			. "AXIOMTHEMES_GLOBALS['menu_slider']     = " . (axiomthemes_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "AXIOMTHEMES_GLOBALS['demo_time']		= " . (axiomthemes_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) axiomthemes_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "AXIOMTHEMES_GLOBALS['media_elements_enabled'] = " . (axiomthemes_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "AXIOMTHEMES_GLOBALS['ajax_search_enabled'] 	= " . (axiomthemes_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "AXIOMTHEMES_GLOBALS['ajax_search_min_length']	= " . min(3, axiomthemes_get_theme_option('ajax_search_min_length')) . ";"
			. "AXIOMTHEMES_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, axiomthemes_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "AXIOMTHEMES_GLOBALS['css_animation']      = " . (axiomthemes_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "AXIOMTHEMES_GLOBALS['menu_animation_in']  = '" . esc_attr(axiomthemes_get_theme_option('menu_animation_in')) . "';"
			. "AXIOMTHEMES_GLOBALS['menu_animation_out'] = '" . esc_attr(axiomthemes_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "AXIOMTHEMES_GLOBALS['popup_engine']	= '" . esc_attr(axiomthemes_get_theme_option('popup_engine')) . "';"
			. "AXIOMTHEMES_GLOBALS['popup_gallery']	= " . (axiomthemes_get_theme_option('popup_gallery')=='yes' ? 'true' : 'false') . ";"

			// E-mail mask
			. "AXIOMTHEMES_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "AXIOMTHEMES_GLOBALS['contacts_maxlength']	= " . intval(axiomthemes_get_theme_option('message_maxlength_contacts')) . ";"
			. "AXIOMTHEMES_GLOBALS['comments_maxlength']	= " . intval(axiomthemes_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "AXIOMTHEMES_GLOBALS['remember_visitors_settings']	= " . (axiomthemes_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "AXIOMTHEMES_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "AXIOMTHEMES_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "AXIOMTHEMES_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "AXIOMTHEMES_GLOBALS['viewmore_busy']		= false;"
			. "AXIOMTHEMES_GLOBALS['video_resize_inited']	= false;"
			. "AXIOMTHEMES_GLOBALS['top_panel_height']		= 0;"
			. "});"
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'axiomthemes_enqueue_custom_styles' ) ) {
	function axiomthemes_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//axiomthemes_get_custom_option('custom_stylesheet_url');
		axiomthemes_enqueue_style( 'axiomthemes-custom-style', $custom_css ? $custom_css : axiomthemes_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'axiomthemes-custom-style', axiomthemes_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'axiomthemes_add_widget_number' ) ) {
	function axiomthemes_add_widget_number($prm) {
		global $AXIOMTHEMES_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = $AXIOMTHEMES_GLOBALS['current_sidebar'];
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) axiomthemes_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
// add_filter( 'wp_title', 'axiomthemes_wp_title', 10, 2 );
if ( !function_exists( 'axiomthemes_wp_title' ) ) {
	function axiomthemes_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = axiomthemes_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', 'axiomthemes' ), max( $paged, $page ) );
		return $title;
	}
}

// Show content with the html layout (if not empty)
if ( !function_exists('axiomthemes_show_layout') ) {
	function axiomthemes_show_layout($str, $before='', $after='') {
		if ($str != '') {
			printf("%s%s%s", $before, $str, $after);
		}
	}
}

// Add main menu classes
// add_filter('wp_nav_menu_objects', 'axiomthemes_add_mainmenu_classes', 10, 2);
if ( !function_exists( 'axiomthemes_add_mainmenu_classes' ) ) {
	function axiomthemes_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && axiomthemes_get_theme_option('menu_colored')=='yes') {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = axiomthemes_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !axiomthemes_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'axiomthemes_callback_frontend_editor_save' ) ) {
	function axiomthemes_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'axiomthemes_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( axiomthemes_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = __('Post update error!', 'axiomthemes');
			} else {
				$response['error'] = __('Post update error!', 'axiomthemes');
			}
		} else
			$response['error'] = __('Post update denied!', 'axiomthemes');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'axiomthemes_callback_frontend_editor_delete' ) ) {
	function axiomthemes_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'axiomthemes_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( axiomthemes_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = __('Post delete error!', 'axiomthemes');
			} else {
				$response['error'] = __('Post delete error!', 'axiomthemes');
			}
		} else
			$response['error'] = __('Post delete denied!', 'axiomthemes');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'axiomthemes_prepare_logo_text' ) ) {
	function axiomthemes_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>