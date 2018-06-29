<?php
/*	Color Schemes. Each scheme include set of colors to decorate inner elements:

	// Main color set:
	text				- body (paragraph) text
	text_link			- simple links in the text
	text_hover			- rollover for the simple links
	inverse				- inversed text (then we use accent colors as background)
	inverse_link		- inversed links in the text
	inverse_hover		- rollover for the inversed links
	header				- large headers
	header_link			- link in the large headers
	header_hover		- link in the large headers hover state
	subheader			- small size headers
	subheader_link		- link in the small size headers
	subheader_hover		- link in the small size headers hover state
	info				- info blocks (post date, author, comments counter)
	info_link			- link in the info blocks
	info_hover			- link in the info blocks hover state
	border				- main block's border
	border_hover		- main block's border hover state
	bg					- main block's background
	bg_hover			- main block's background hover state
	shadow				- main block's shadow
	shadow_hover		- main block's shadow (rollover state)
	accent1				- additional accent color 1
	accent1_hover		- additional accent color 1 hover state
	accent2				- additional accent color 2
	accent2_hover		- additional accent color 2 hover state
	accent3				- additional accent color 3
	accent3_hover		- additional accent color 3 hover state

	// Highlight colors (menu items, form's fields, etc.)
	highlight_text			- highlight block's text color
	highlight_link			- highlight block's link color
	highlight_hover			- highlight block's link color (rollover state)
	highlight_bg			- highlight block's background
	highlight_bg_hover		- highlight block's background (rollover state)
	highlight_border		- highlight block's border
	highlight_border_hover	- highlight block's border (rollover state)
	highlight_shadow		- highlight block's shadow
	highlight_shadow_hover	- highlight block's shadow (rollover state)

*/


// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_schemes_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_schemes_theme_setup', 1 );
	function axiomthemes_schemes_theme_setup() {

		if ( is_admin() ) {

			// Load Color schemes then Theme Options are loaded
			add_action('axiomthemes_action_load_main_options',			'axiomthemes_schemes_load_schemes');

			// Ajax Save and Export Action handler
			add_action('wp_ajax_axiomthemes_options_save', 			'axiomthemes_schemes_save');
			add_action('wp_ajax_nopriv_axiomthemes_options_save',		'axiomthemes_schemes_save');
		}
		
	}
}

if ( !function_exists( 'axiomthemes_schemes_theme_setup2' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_schemes_theme_setup2' );
	function axiomthemes_schemes_theme_setup2() {

		if ( is_admin() ) {

			// Add Theme Options in WP menu
			//add_action('admin_menu', 								'axiomthemes_schemes_admin_menu_item');
		}
		
	}
}


// Add 'Color Schemes' in the menu 'Theme Options'
if ( !function_exists( 'axiomthemes_schemes_admin_menu_item' ) ) {
	//add_action('admin_menu', 'axiomthemes_schemes_admin_menu_item');
	function axiomthemes_schemes_admin_menu_item() {
		add_submenu_page('axiomthemes_options', __('Color Schemes', 'axiomthemes'), __('Color Schemes', 'axiomthemes'), 'manage_options', 'axiomthemes_options_schemes', 'axiomthemes_schemes_page');
	}
}

// Load Color Schemes them Theme Options are loaded
if ( !function_exists( 'axiomthemes_schemes_load_schemes' ) ) {
	add_action( 'axiomthemes_action_load_main_options', 'axiomthemes_schemes_load_schemes' );
	function axiomthemes_schemes_load_schemes() {
		global $AXIOMTHEMES_GLOBALS;
		$schemes = get_option('axiomthemes_options_schemes');
		if (!empty($schemes)) $AXIOMTHEMES_GLOBALS['color_schemes'] = $schemes;
	}
}

// Add color scheme
if (!function_exists('axiomthemes_add_color_scheme')) {
	function axiomthemes_add_color_scheme($key, $data) {
		global $AXIOMTHEMES_GLOBALS;
		if (empty($AXIOMTHEMES_GLOBALS['color_schemes'])) $AXIOMTHEMES_GLOBALS['color_schemes'] = array();
		$AXIOMTHEMES_GLOBALS['color_schemes'][$key] = $data;
	}
}

// Return scheme color
if (!function_exists('axiomthemes_get_scheme_color')) {
	function axiomthemes_get_scheme_color($clr='') {
		global $AXIOMTHEMES_GLOBALS;
		$scheme = axiomthemes_get_custom_option('color_scheme');
		if (empty($scheme) || empty($AXIOMTHEMES_GLOBALS['color_schemes'][$scheme])) $scheme = 'original';
		return isset($AXIOMTHEMES_GLOBALS['color_schemes'][$scheme][$clr]) ? $AXIOMTHEMES_GLOBALS['color_schemes'][$scheme][$clr] : '';
	}
}

// Return link color
if (!function_exists('axiomthemes_get_link_color')) {
	function axiomthemes_get_link_color($clr='') {
		return apply_filters('axiomthemes_filter_get_link_color', $clr);
	}
}

// Return link dark color
if (!function_exists('axiomthemes_get_link_dark')) {
	function axiomthemes_get_link_dark($clr='') {
		return apply_filters('axiomthemes_filter_get_link_dark', $clr);
	}
}

// Return menu color
if (!function_exists('axiomthemes_get_menu_color')) {
	function axiomthemes_get_menu_color($clr='') {
		return apply_filters('axiomthemes_filter_get_menu_color', $clr);
	}
}

// Return menu dark color
if (!function_exists('axiomthemes_get_menu_dark')) {
	function axiomthemes_get_menu_dark($clr='') {
		return apply_filters('axiomthemes_filter_get_menu_dark', $clr);
	}
}

// Return usermenu color
if (!function_exists('axiomthemes_get_user_color')) {
	function axiomthemes_get_user_color($clr='') {
		return apply_filters('axiomthemes_filter_get_user_color', $clr);
	}
}

// Return usermenu dark color
if (!function_exists('axiomthemes_get_user_dark')) {
	function axiomthemes_get_user_dark($clr='') {
		return apply_filters('axiomthemes_filter_get_user_dark', $clr);
	}
}

// Return theme background color
if (!function_exists('axiomthemes_get_theme_bgcolor')) {
	function axiomthemes_get_theme_bgcolor($clr='') {
		return apply_filters('axiomthemes_filter_get_theme_bgcolor', $clr);
	}
}


if ( !function_exists( 'axiomthemes_schemes_page' ) ) {
	function axiomthemes_schemes_page() {
		global $AXIOMTHEMES_GLOBALS;

		$options = array();
		
		if (count($AXIOMTHEMES_GLOBALS['color_schemes']) > 0) {

			$start = true;

			foreach ($AXIOMTHEMES_GLOBALS['color_schemes'] as $slug=>$scheme) {

				$options["partition_{$slug}"] = array(
					"title" => $scheme['title'],
					"override" => "schemes",
					"icon" => "iconadmin-palette",
					"type" => "partition");
				if ($start) {
					$options["partition_{$slug}"]["start"] = "partitions";
					$start = false;
				}

				$options["{$slug}-description"] = array(
					"title" => sprintf(__('Color scheme "%s"', 'axiomthemes'), $scheme['title']),
					"desc" => sprintf(__('Customize colors for color scheme "%s"', 'axiomthemes'), $scheme['title']),
					"override" => "schemes",
					"type" => "info");

				// Scheme name and slug
				$options["{$slug}-title"] = array(
					"title" => __('Scheme title',  'axiomthemes'),
					"desc" => __("Title to represent this color scheme in the lists", 'axiomthemes'),
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['title'],
					"type" => "text");

				$options["{$slug}-slug"] = array(
					"title" => __('Scheme slug',  'axiomthemes'),
					"desc" => __("Slug (only english letters and digits) to use this color scheme in the shortcodes", 'axiomthemes'),
					"override" => "schemes",
					"std" => "",
					"val" => $slug,
					"type" => "text");
	
				// Divider
				$options["{$slug}-divider_1"] = array(
					"override" => "schemes",
					"type" => "divider");

				// Accent description
				$options["{$slug}-description"] = array(
					"title" => sprintf(__('Color scheme "%s"', 'axiomthemes'), $scheme['title']),
					"desc" => sprintf(__('Customize colors for color scheme "%s"', 'axiomthemes'), $scheme['title']),
					"override" => "schemes",
					"type" => "info");
	
				// Accent 1 color
				$options["{$slug}-accent1_label"] = array(
					"title" => __('Accent 1', 'axiomthemes'),
					"desc" => __('Select color for accented elements and their hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-accent1"] = array(
					"title" => __('Color', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['accent1'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent1_hover"] = array(
					"title" => __('Hover', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['accent1_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Accent 2 color
				$options["{$slug}-accent2_label"] = array(
					"title" => __('Accent 2', 'axiomthemes'),
					"desc" => __('Select color for accented elements and their hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-accent2"] = array(
					"std" => "",
					"val" => $scheme['accent2'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent2_hover"] = array(
					"std" => "",
					"val" => $scheme['accent2_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Accent 3 color
				$options["{$slug}-accent3_label"] = array(
					"title" => __('Accent 3', 'axiomthemes'),
					"desc" => __('Select color for accented elements and their hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-accent3"] = array(
					"std" => "",
					"val" => $scheme['accent3'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent3_hover"] = array(
					"std" => "",
					"val" => $scheme['accent3_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Divider
				$options["{$slug}-divider_2"] = array(
					"override" => "schemes",
					"type" => "divider");
	
				// Text - simple text, links in the text and their hover state
				$options["{$slug}-text_label"] = array(
					"title" => __('Plain text and links', 'axiomthemes'),
					"desc" => __('Select colors for paragraph text, plain links and their hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-text"] = array(
					"title" => __('Color', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['text'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_link"] = array(
					"title" => __('Link', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['text_link'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_hover"] = array(
					"title" => __('Hover', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['text_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Info text
				$options["{$slug}-info_label"] = array(
					"title" => __('Info blocks', 'axiomthemes'),
					"desc" => __('Select colors for the text in the info blocks (author, posted date, counters, etc.)', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-info"] = array(
					"std" => "",
					"val" => $scheme['info'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-info_link"] = array(
					"std" => "",
					"val" => $scheme['info_link'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-info_hover"] = array(
					"std" => "",
					"val" => $scheme['info_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Inverse text
				$options["{$slug}-inverse_label"] = array(
					"title" => __('Inverse text and links', 'axiomthemes'),
					"desc" => __('Select colors for inversed text (text on accented background)', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-inverse"] = array(
					"std" => "",
					"val" => $scheme['inverse'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_link"] = array(
					"std" => "",
					"val" => $scheme['inverse_link'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_hover"] = array(
					"std" => "",
					"val" => $scheme['inverse_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Headers - large size headers
				$options["{$slug}-header_label"] = array(
					"title" => sprintf(__('Headers', 'axiomthemes'), 'Default'),
					"desc" => sprintf(__('Select colors for large headers, links inside headers and it hover state', 'axiomthemes'), 'Default'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-header"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['header'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-header_link"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['header_link'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-header_hover"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['header_hover'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				// Subheaders - small size headers
				$options["{$slug}-subheader_label"] = array(
					"title" => sprintf(__('SubHeaders', 'axiomthemes'), 'Default'),
					"desc" => sprintf(__('Select colors for small headers, links inside subheaders and it hover state', 'axiomthemes'), 'Default'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-subheader"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['subheader'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-subheader_link"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['subheader_link'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-subheader_hover"] = array(
					"override" => "schemes",
					"std" => "",
					"val" => $scheme['subheader_hover'],
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Divider
				$options["{$slug}-divider_3"] = array(
					"override" => "schemes",
					"type" => "divider");
	
				// Border
				$options["{$slug}-border_label"] = array(
					"title" => __('Border color', 'axiomthemes'),
					"desc" => __('Select the border color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-border"] = array(
					"title" => __('Color', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['border'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-border_hover"] = array(
					"title" => __('Hover', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['border_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Background
				$options["{$slug}-bg_label"] = array(
					"title" => __('Background color', 'axiomthemes'),
					"desc" => __('Select the background color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg"] = array(
					"std" => "",
					"val" => $scheme['bg'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-bg_hover"] = array(
					"std" => "",
					"val" => $scheme['bg_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Shadow
				$options["{$slug}-shadow_label"] = array(
					"title" => __('Shadow color', 'axiomthemes'),
					"desc" => __('Select the shadow color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-shadow"] = array(
					"std" => "",
					"val" => $scheme['shadow'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-shadow_hover"] = array(
					"std" => "",
					"val" => $scheme['shadow_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Divider
				$options["{$slug}-divider_4"] = array(
					"override" => "schemes",
					"type" => "divider");
	
				// Highlight Text - highlight blocks (submenus) text, links in the text and their hover state
				$options["{$slug}-highlight_text_label"] = array(
					"title" => __('Highlight text and links', 'axiomthemes'),
					"desc" => __('Select colors for highlight text, links and their hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-highlight_text"] = array(
					"title" => __('Color', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['highlight_text'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-highlight_link"] = array(
					"title" => __('Link', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['highlight_link'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-highlight_hover"] = array(
					"title" => __('Hover', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['highlight_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
				
				// Highlight Border
				$options["{$slug}-highlight_border_label"] = array(
					"title" => __('Highlight Border color', 'axiomthemes'),
					"desc" => __('Select the border color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-highlight_border"] = array(
					"std" => "",
					"val" => $scheme['highlight_border'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-highlight_border_hover"] = array(
					"title" => __('Hover', 'axiomthemes'),
					"std" => "",
					"val" => $scheme['highlight_border_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Highlight Background
				$options["{$slug}-highlight_bg_label"] = array(
					"title" => __('Highlight Background color', 'axiomthemes'),
					"desc" => __('Select the highlight background color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-highlight_bg"] = array(
					"std" => "",
					"val" => $scheme['highlight_bg'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-highlight_bg_hover"] = array(
					"std" => "",
					"val" => $scheme['highlight_bg_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Highlight Shadow
				$options["{$slug}-highlight_shadow_label"] = array(
					"title" => __('Highlight Shadow color', 'axiomthemes'),
					"desc" => __('Select the highlight shadow color and it hover state', 'axiomthemes'),
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-highlight_shadow"] = array(
					"std" => "",
					"val" => $scheme['highlight_shadow'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "2_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-highlight_shadow_hover"] = array(
					"std" => "",
					"val" => $scheme['highlight_shadow_hover'],
					"override" => "schemes",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
			}
		}
		?>
		
		<script type="text/javascript">
			jQuery(document).ready(function() {
				// Prepare global values for the review procedure
				AXIOMTHEMES_GLOBALS['ajax_url']	= "<?php echo admin_url('admin-ajax.php'); ?>";
				AXIOMTHEMES_GLOBALS['ajax_nonce']	= "<?php echo wp_create_nonce('ajax_nonce'); ?>";
			});
		</script>
		
		<?php 
		axiomthemes_options_page_start(array(
			'title' => __('Color Schemes', 'axiomthemes'),
			"icon" => "iconadmin-palette",
			"subtitle" => __('Color Scheme Editor', 'axiomthemes'),
			"description" => __('Specify the color for each element in the schema. After that you will be able to use your color scheme for the entire page, any part thereof and/or for the shortcodes!', 'axiomthemes'),
			'data' => $options,
			'create_form' => true,
			'buttons' => array('save'),
			'override' => 'schemes'
		));

		foreach ($options as $id=>$option) { 
			axiomthemes_options_show_field($id, $option);
		}
	
		axiomthemes_options_page_stop();
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'axiomthemes_schemes_save' ) ) {
	//add_action('wp_ajax_axiomthemes_options_save', 'axiomthemes_schemes_save');
	//add_action('wp_ajax_nopriv_axiomthemes_options_save', 'axiomthemes_schemes_save');
	function axiomthemes_schemes_save() {

		$mode = $_POST['mode'];
		$override = empty($_POST['override']) ? '' : $_POST['override'];
		$slug = empty($_POST['slug']) ? '' : $_POST['slug'];

		if (!in_array($mode, array('save')) || !in_array($override, array('schemes')))
			return;

		if ( !wp_verify_nonce( $_POST['nonce'], 'ajax_nonce' ) )
			die();

		parse_str($_POST['data'], $data);

		// Refresh array with schemes from POST data
		global $AXIOMTHEMES_GLOBALS;
		$schemes = $AXIOMTHEMES_GLOBALS['color_schemes'];
		foreach ($schemes as $slug=>$scheme) {
			foreach ($scheme as $key=>$value) {
				if (isset($data[$slug.'-'.$key]))
					$schemes[$slug][$key] = $data[$slug.'-'.$key];
			}
			$new_slug = $data[$slug.'-slug'];
			if (empty($new_slug)) $new_slug = axiomthemes_get_slug($scheme['title']);
			if ($slug != $new_slug) {
				$schemes[$new_slug] = $schemes[$slug];
				unset($schemes[$slug]);
			}
		}

		update_option('axiomthemes_options_schemes', apply_filters('axiomthemes_filter_save_schemes', $schemes));
		
		die();
	}
}
?>