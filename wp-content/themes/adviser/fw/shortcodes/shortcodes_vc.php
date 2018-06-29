<?php

// Width and height params
if ( !function_exists( 'axiomthemes_vc_width' ) ) {
	function axiomthemes_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => __("Width", "axiomthemes"),
			"description" => __("Width (in pixels or percent) of the current element", "axiomthemes"),
			"group" => __('Size &amp; Margins', 'axiomthemes'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'axiomthemes_vc_height' ) ) {
	function axiomthemes_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => __("Height", "axiomthemes"),
			"description" => __("Height (only in pixels) of the current element", "axiomthemes"),
			"group" => __('Size &amp; Margins', 'axiomthemes'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'axiomthemes_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'axiomthemes_shortcodes_vc_scripts_admin' );
	function axiomthemes_shortcodes_vc_scripts_admin() {
		// Include CSS 
		axiomthemes_enqueue_style ( 'shortcodes_vc-style', axiomthemes_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		axiomthemes_enqueue_script( 'shortcodes_vc-script', axiomthemes_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'axiomthemes_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'axiomthemes_shortcodes_vc_scripts_front' );
	function axiomthemes_shortcodes_vc_scripts_front() {
		if (axiomthemes_vc_is_frontend()) {
			// Include CSS 
			axiomthemes_enqueue_style ( 'shortcodes_vc-style', axiomthemes_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
			axiomthemes_enqueue_script( 'shortcodes_vc-script', axiomthemes_get_file_url('shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'axiomthemes_shortcodes_vc_add_init_script' ) ) {
	//add_filter('axiomthemes_shortcode_output', 'axiomthemes_shortcodes_vc_add_init_script', 10, 4);
	function axiomthemes_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (axiomthemes_strpos($output, 'axiomthemes_vc_init_shortcodes')===false) {
				$id = "axiomthemes_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							axiomthemes_init_post_formats();
							axiomthemes_init_shortcodes(jQuery("body").eq(0));
							axiomthemes_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_shortcodes_vc_theme_setup' ) ) {
	//if ( axiomthemes_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'axiomthemes_action_after_init_theme', 'axiomthemes_shortcodes_vc_theme_setup' );
	function axiomthemes_shortcodes_vc_theme_setup() {
		if (axiomthemes_shortcodes_is_used()) {
			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team', 'courses') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'axiomthemes_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'axiomthemes_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('axiomthemes_shortcode_output', 'axiomthemes_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
//			vc_remove_element("vc_button");
//			vc_remove_element("vc_posts_slider");
//			vc_remove_element("vc_gmaps");
//			vc_remove_element("vc_teaser_grid");
//			vc_remove_element("vc_progress_bar");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_tweetmeme");
//			vc_remove_element("vc_googleplus");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_pinterest");
//			vc_remove_element("vc_message");
//			vc_remove_element("vc_posts_grid");
//			vc_remove_element("vc_carousel");
//			vc_remove_element("vc_flickr");
//			vc_remove_element("vc_tour");
//			vc_remove_element("vc_separator");
//			vc_remove_element("vc_single_image");
//			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
//			vc_remove_element("vc_toggle");
//			vc_remove_element("vc_tabs");
//			vc_remove_element("vc_tab");
//			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $AXIOMTHEMES_GLOBALS;
			
			$AXIOMTHEMES_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => __("Axiomthemes shortcodes", "axiomthemes"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => __("Element ID", "axiomthemes"),
					"description" => __("ID for current element", "axiomthemes"),
					"group" => __('ID &amp; Class', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => __("Element CSS class", "axiomthemes"),
					"description" => __("CSS class for current element", "axiomthemes"),
					"group" => __('ID &amp; Class', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => __("Animation", "axiomthemes"),
					"description" => __("Select animation while object enter in the visible area of page", "axiomthemes"),
					"group" => __('ID &amp; Class', 'axiomthemes'),
					"class" => "",
					"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => __("CSS styles", "axiomthemes"),
					"description" => __("Any additional CSS rules (if need)", "axiomthemes"),
					"group" => __('ID &amp; Class', 'axiomthemes'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => __("Top margin", "axiomthemes"),
					"description" => __("Top margin (in pixels).", "axiomthemes"),
					"group" => __('Size &amp; Margins', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => __("Bottom margin", "axiomthemes"),
					"description" => __("Bottom margin (in pixels).", "axiomthemes"),
					"group" => __('Size &amp; Margins', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => __("Left margin", "axiomthemes"),
					"description" => __("Left margin (in pixels).", "axiomthemes"),
					"group" => __('Size &amp; Margins', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => __("Right margin", "axiomthemes"),
					"description" => __("Right margin (in pixels).", "axiomthemes"),
					"group" => __('Size &amp; Margins', 'axiomthemes'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => __("Accordion", "axiomthemes"),
				"description" => __("Accordion items", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Accordion style", "axiomthemes"),
						"description" => __("Select style for display accordion", "axiomthemes"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiomthemes') => 1,
							__('Style 2', 'axiomthemes') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", "axiomthemes"),
						"description" => __("Display counter before each accordion title", "axiomthemes"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened item", "axiomthemes"),
						"description" => __("Number of initially opened item", "axiomthemes"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiomthemes"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiomthemes"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_accordion_item title="' . __( 'Item 1 title', 'axiomthemes' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . __( 'Item 2 title', 'axiomthemes' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "axiomthemes").'">'.__("Add item", "axiomthemes").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => __("Accordion item", "axiomthemes"),
				"description" => __("Inner accordion item", "axiomthemes"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title for current accordion item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiomthemes"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiomthemes"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends AXIOMTHEMES_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends AXIOMTHEMES_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => __("Anchor", "axiomthemes"),
				"description" => __("Insert anchor for the TOC (table of content)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Anchor's icon", "axiomthemes"),
						"description" => __("Select icon for the anchor from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Short title", "axiomthemes"),
						"description" => __("Short title of the anchor (for the table of content)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Long description", "axiomthemes"),
						"description" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("External URL", "axiomthemes"),
						"description" => __("External URL for this TOC item", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => __("Add separator", "axiomthemes"),
						"description" => __("Add separator under item in the TOC", "axiomthemes"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => __("Audio", "axiomthemes"),
				"description" => __("Insert audio player", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
                    array(
                        "param_name" => "style",
                        "heading" => __("Style", "axiomthemes"),
                        "description" => __("Select style", "axiomthemes"),
                        "class" => "",
                        "value" => array('Normal' => 'audio_normal', 'Dark' => 'audio_dark' ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "url",
						"heading" => __("URL for audio file", "axiomthemes"),
						"description" => __("Put here URL for audio file", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for audio cover", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title of the audio file", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => __("Author", "axiomthemes"),
						"description" => __("Author of the audio file", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Controls", "axiomthemes"),
						"description" => __("Show/hide controls", "axiomthemes"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay", "axiomthemes"),
						"description" => __("Autoplay audio on page load", "axiomthemes"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select block alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => __("Block container", "axiomthemes"),
				"description" => __("Container for any block ([section] analog - to enable nesting)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "axiomthemes"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select block alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "axiomthemes"),
						"description" => __("Select width for columns emulation", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "axiomthemes"),
						"description" => __("Use pan effect to show section content", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Content scroller', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiomthemes"),
						"description" => __("Use scroller to show section content", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll direction", "axiomthemes"),
						"description" => __("Scroll direction (if Use scroller = yes)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "axiomthemes"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "axiomthemes"),
						"class" => "",
						"group" => __('Scroll', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiomthemes"),
						"description" => __("Any color for objects in this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiomthemes"),
						"description" => __("Main background tint: dark or light", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiomthemes"),
						"description" => __("Select background image from library for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiomthemes"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiomthemes"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiomthemes"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiomthemes"),
						"description" => __("Font weight of the text", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Default', 'axiomthemes') => 'inherit',
							__('Thin (100)', 'axiomthemes') => '100',
							__('Light (300)', 'axiomthemes') => '300',
							__('Normal (400)', 'axiomthemes') => '400',
							__('Bold (700)', 'axiomthemes') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiomthemes"),
						"description" => __("Content for section container", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => __("Blogger", "axiomthemes"),
				"description" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Output style", "axiomthemes"),
						"description" => __("Select desired style for posts output", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => __("Show filters", "axiomthemes"),
						"description" => __("Use post's tags or categories as filter buttons", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => __("Hover effect", "axiomthemes"),
						"description" => __("Select hover effect (only if style=Portfolio)", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','courses_2','courses_3','courses_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => __("Hover direction", "axiomthemes"),
						"description" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','courses_2','courses_3','courses_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => __("Dedicated content location", "axiomthemes"),
						"description" => __("Select position for dedicated content (only for style=excerpt)", "axiomthemes"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Posts direction", "axiomthemes"),
						"description" => __("Display posts in horizontal or vertical direction", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => __("Show rating stars", "axiomthemes"),
						"description" => __("Show rating stars under post's header", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Show rating', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => __("Show post info block", "axiomthemes"),
						"description" => __("Show post info block (author, date, tags, etc.)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Show info', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => __("Description length", "axiomthemes"),
						"description" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Allow links to the post", "axiomthemes"),
						"description" => __("Allow links to the post from each blogger item", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Allow links', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => __("More link text", "axiomthemes"),
						"description" => __("Read more link text. If empty - show 'More', else - used as link text", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => __("Post type", "axiomthemes"),
						"description" => __("Select post type to show", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "axiomthemes"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "axiomthemes"),
						"description" => __("Put here comma separated category slugs or ids. If empty - show posts from any category or from IDs list", "axiomthemes"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => array_flip(axiomthemes_array_merge(array(0 => __('- Select category -', 'axiomthemes')), $AXIOMTHEMES_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Total posts to show", "axiomthemes"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns number", "axiomthemes"),
						"description" => __("How many columns used to display posts?", "axiomthemes"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiomthemes"),
						"description" => __("Skip posts before select next part.", "axiomthemes"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'axiomthemes'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post order by", "axiomthemes"),
						"description" => __("Select desired posts sorting method", "axiomthemes"),
						"class" => "",
						"group" => __('Query', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiomthemes"),
						"description" => __("Select desired posts order", "axiomthemes"),
						"class" => "",
						"group" => __('Query', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => __("Select posts only", "axiomthemes"),
						"description" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "axiomthemes"),
						"class" => "",
						"group" => __('Query', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiomthemes"),
						"description" => __("Use scroller to show all posts", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Use scroller', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show slider controls", "axiomthemes"),
						"description" => __("Show arrows to control scroll slider", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Show controls', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => __("Line break", "axiomthemes"),
				"description" => __("Line break or Clear Floating", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => __("Clear floating", "axiomthemes"),
						"description" => __("Select clear side (if need)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'axiomthemes') => 'none',
							__('Left', 'axiomthemes') => 'left',
							__('Right', 'axiomthemes') => 'right',
							__('Both', 'axiomthemes') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => __("Button", "axiomthemes"),
				"description" => __("Button with link", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Caption", "axiomthemes"),
						"description" => __("Button caption", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Button's shape", "axiomthemes"),
						"description" => __("Select button's shape", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Square', 'axiomthemes') => 'square',
							__('Round', 'axiomthemes') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Button's style", "axiomthemes"),
						"description" => __("Select button's style", "axiomthemes"),
						"class" => "",
						"value" => array(
                            __('Clear', 'axiomthemes') => 'clear',
                            __('Dark', 'axiomthemes') => 'dark',
                            __('Light', 'axiomthemes') => 'light',
                            __('Red', 'axiomthemes') => 'red',
                            __('Blue', 'axiomthemes') => 'blue'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Button's size", "axiomthemes"),
						"description" => __("Select button's size", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'axiomthemes') => 'mini',
							__('Medium', 'axiomthemes') => 'medium',
							__('Large', 'axiomthemes') => 'big'
						),
						"type" => "dropdown"
					),
//					array(
//						"param_name" => "icon",
//						"heading" => __("Button's icon", "axiomthemes"),
//						"description" => __("Select icon for the title from Fontello icons set", "axiomthemes"),
//						"class" => "",
//						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
//						"type" => "dropdown"
//					),
//					array(
//						"param_name" => "bg_style",
//						"heading" => __("Button's color scheme", "axiomthemes"),
//						"description" => __("Select button's color scheme", "axiomthemes"),
//						"class" => "",
//						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['button_styles']),
//						"type" => "dropdown"
//					),
					array(
						"param_name" => "color",
						"heading" => __("Button's text color", "axiomthemes"),
						"description" => __("Any color for button's caption", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Button's backcolor", "axiomthemes"),
						"description" => __("Any color for button's background", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Button's alignment", "axiomthemes"),
						"description" => __("Align button to left, center or right", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiomthemes"),
						"description" => __("URL for the link on button click", "axiomthemes"),
						"class" => "",
						"group" => __('Link', 'axiomthemes'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", "axiomthemes"),
						"description" => __("Target for the link on button click", "axiomthemes"),
						"class" => "",
						"group" => __('Link', 'axiomthemes'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => __("Open link in popup", "axiomthemes"),
						"description" => __("Open link target in popup window", "axiomthemes"),
						"class" => "",
						"group" => __('Link', 'axiomthemes'),
						"value" => array(__('Open in popup', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => __("Rel attribute", "axiomthemes"),
						"description" => __("Rel attribute for the button's link (if need", "axiomthemes"),
						"class" => "",
						"group" => __('Link', 'axiomthemes'),
						"value" => "",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					//axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => __("Chat", "axiomthemes"),
				"description" => __("Chat message", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Item title", "axiomthemes"),
						"description" => __("Title for current chat item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Item photo", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for the item photo (avatar)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiomthemes"),
						"description" => __("URL for the link on chat title click", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Chat item content", "axiomthemes"),
						"description" => __("Current chat item content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends AXIOMTHEMES_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => __("Columns", "axiomthemes"),
				"description" => __("Insert columns with margins", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => __("Columns count", "axiomthemes"),
						"description" => __("Number of the columns in the container.", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => __("Fluid columns", "axiomthemes"),
						"description" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Fluid columns', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => __("Column", "axiomthemes"),
				"description" => __("Column item", "axiomthemes"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => __("Merge columns", "axiomthemes"),
						"description" => __("Count merged columns from current", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Alignment text in the column", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiomthemes"),
						"description" => __("Any color for objects in this column", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this column", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("URL for background image file", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Column's content", "axiomthemes"),
						"description" => __("Content of the current column", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends AXIOMTHEMES_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => __("Contact form", "axiomthemes"),
				"description" => __("Insert contact form", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiomthemes"),
						"description" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Create custom form', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "action",
						"heading" => __("Action", "axiomthemes"),
						"description" => __("Contact form action (URL to handle form data). If empty - use internal action", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select form alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title above contact form", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description (under the title)", "axiomthemes"),
						"description" => __("Contact form description", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiomthemes_vc_width(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => __("Form item (custom field)", "axiomthemes"),
				"description" => __("Custom field for the contact form", "axiomthemes"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "axiomthemes"),
						"description" => __("Select type of the custom field", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "axiomthemes"),
						"description" => __("Name of the custom field", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Default value", "axiomthemes"),
						"description" => __("Default value of the custom field", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => __("Label", "axiomthemes"),
						"description" => __("Label for the custom field", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => __("Label position", "axiomthemes"),
						"description" => __("Label position relative to the field", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends AXIOMTHEMES_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends AXIOMTHEMES_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => __("Content block", "axiomthemes"),
				"description" => __("Container for main content block (use it only on fullscreen pages)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiomthemes"),
						"description" => __("Content for section container", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => __("Countdown", "axiomthemes"),
				"description" => __("Insert countdown object", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => __("Date", "axiomthemes"),
						"description" => __("Upcoming date (format: yyyy-mm-dd)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => __("Time", "axiomthemes"),
						"description" => __("Upcoming time (format: HH:mm:ss)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Countdown style", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiomthemes') => 1,
							__('Style 2', 'axiomthemes') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align counter to left, center or right", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => __("Dropcaps", "axiomthemes"),
				"description" => __("Make first letter of the text as dropcaps", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Dropcaps style", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiomthemes') => 1,
							__('Style 2', 'axiomthemes') => 2,
							__('Style 3', 'axiomthemes') => 3,
							__('Style 4', 'axiomthemes') => 4
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Paragraph text", "axiomthemes"),
						"description" => __("Paragraph with dropcaps content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => __("E-mail collector", "axiomthemes"),
				"description" => __("Collect e-mails into specified group", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => __("Group", "axiomthemes"),
						"description" => __("The name of group to collect e-mail address", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Opened", "axiomthemes"),
						"description" => __("Initially open the input field on show object", "axiomthemes"),
						"class" => "",
						"value" => array(__('Initially opened', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align field to left, center or right", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => __("Gap", "axiomthemes"),
				"description" => __("Insert gap (fullwidth area) in the post content", "axiomthemes"),
				"category" => __('Structure', 'axiomthemes'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Gap content", "axiomthemes"),
						"description" => __("Gap inner content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
					*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => __("Google map", "axiomthemes"),
				"description" => __("Insert Google map with desired address or coordinates", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_single trx_sc_googlemap",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => __("Address", "axiomthemes"),
						"description" => __("Address to show in map center", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => __("Latitude and Longtitude", "axiomthemes"),
						"description" => __("Comma separated map center coorditanes (instead Address)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "zoom",
						"heading" => __("Zoom", "axiomthemes"),
						"description" => __("Map zoom factor", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Map custom style", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					axiomthemes_vc_width('100%'),
					axiomthemes_vc_height(240),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => __("Highlight text", "axiomthemes"),
				"description" => __("Highlight text with selected color, background color and other styles", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "axiomthemes"),
						"description" => __("Highlight type", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'axiomthemes') => 0,
								__('Type 1', 'axiomthemes') => 1,
								__('Type 2', 'axiomthemes') => 2,
								__('Type 3', 'axiomthemes') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiomthemes"),
						"description" => __("Color for the highlighted text", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Background color for the highlighted text", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiomthemes"),
						"description" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Highlight text", "axiomthemes"),
						"description" => __("Content for highlight", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => __("Icon", "axiomthemes"),
				"description" => __("Insert the icon", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiomthemes"),
						"description" => __("Select icon class from Fontello icons set", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiomthemes"),
						"description" => __("Icon's color", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Background color for the icon", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => __("Background shape", "axiomthemes"),
						"description" => __("Shape of the icon background", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'axiomthemes') => 'none',
							__('Round', 'axiomthemes') => 'round',
                            __('Image', 'axiomthemes') => 'round',
							__('Square', 'axiomthemes') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => __("Icon's color scheme", "axiomthemes"),
						"description" => __("Select icon's color scheme", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiomthemes"),
						"description" => __("Icon's font size", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiomthemes"),
						"description" => __("Icon's font weight", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Default', 'axiomthemes') => 'inherit',
							__('Thin (100)', 'axiomthemes') => '100',
							__('Light (300)', 'axiomthemes') => '300',
							__('Normal (400)', 'axiomthemes') => '400',
							__('Bold (700)', 'axiomthemes') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Icon's alignment", "axiomthemes"),
						"description" => __("Align icon to left, center or right", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiomthemes"),
						"description" => __("Link URL from this icon (if not empty)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => __("Image", "axiomthemes"),
				"description" => __("Insert image", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("Select image", "axiomthemes"),
						"description" => __("Select image from library", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Image alignment", "axiomthemes"),
						"description" => __("Align image to left or right side", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Image shape", "axiomthemes"),
						"description" => __("Shape of the image: square or round", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'axiomthemes') => 'square',
							__('Round', 'axiomthemes') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Image's title", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title's icon", "axiomthemes"),
						"description" => __("Select icon for the title from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => __("Infobox", "axiomthemes"),
				"description" => __("Box with info or error message", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Infobox style", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'axiomthemes') => 'regular',
								__('Info', 'axiomthemes') => 'info',
								__('Success', 'axiomthemes') => 'success',
								__('Error', 'axiomthemes') => 'error',
								__('Warning', 'axiomthemes') => 'warning',
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => __("Closeable", "axiomthemes"),
						"description" => __("Create closeable box (with close button)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Close button', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Custom icon", "axiomthemes"),
						"description" => __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiomthemes"),
						"description" => __("Any color for the text and headers", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this infobox", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Message text", "axiomthemes"),
						"description" => __("Message for the infobox", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends AXIOMTHEMES_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => __("Line", "axiomthemes"),
				"description" => __("Insert line (delimiter)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Line style", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'axiomthemes') => 'solid',
								__('Dashed', 'axiomthemes') => 'dashed',
								__('Dotted', 'axiomthemes') => 'dotted',
								__('Double', 'axiomthemes') => 'double',
								__('Shadow', 'axiomthemes') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Line color", "axiomthemes"),
						"description" => __("Line color", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => __("List", "axiomthemes"),
				"description" => __("List items with specific bullets", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Bullet's style", "axiomthemes"),
						"description" => __("Bullet's style for each list item", "axiomthemes"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List icon", "axiomthemes"),
						"description" => __("Select list icon from Fontello icons set (only for style=Iconed)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", "axiomthemes"),
						"description" => __("List icons color", "axiomthemes"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_list_item]' . __( 'Item 1', 'axiomthemes' ) . '[/trx_list_item]
					[trx_list_item]' . __( 'Item 2', 'axiomthemes' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => __("List item", "axiomthemes"),
				"description" => __("List item with specific bullet", "axiomthemes"),
				"class" => "trx_sc_container trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("List item title", "axiomthemes"),
						"description" => __("Title for the current list item (show it as tooltip)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List item icon", "axiomthemes"),
						"description" => __("Select list item icon from Fontello icons set (only for style=Iconed)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("List item text", "axiomthemes"),
						"description" => __("Current list item content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			
			) );
			
			class WPBakeryShortCode_Trx_List extends AXIOMTHEMES_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends AXIOMTHEMES_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => __("Number", "axiomthemes"),
				"description" => __("Insert number or any word as set of separated characters", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => __("Value", "axiomthemes"),
						"description" => __("Number or any word to separate", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select block alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends AXIOMTHEMES_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => __("Parallax", "axiomthemes"),
				"description" => __("Create the parallax container (with asinc background image)", "axiomthemes"),
				"category" => __('Structure', 'axiomthemes'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => __("Create gap", "axiomthemes"),
						"description" => __("Create gap around parallax container (not need in fullscreen pages)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Create gap', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "axiomthemes"),
						"description" => __("Scroll direction for the parallax background", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'axiomthemes') => 'up',
								__('Down', 'axiomthemes') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => __("Speed", "axiomthemes"),
						"description" => __("Parallax background motion speed (from 0.0 to 1.0)", "axiomthemes"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiomthemes"),
						"description" => __("Select color for text object inside parallax block", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Bg tint", "axiomthemes"),
						"description" => __("Select tint of the parallax background (for correct font color choise)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Light', 'axiomthemes') => 'light',
								__('Dark', 'axiomthemes') => 'dark'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Backgroud color", "axiomthemes"),
						"description" => __("Select color for parallax background", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for the parallax background", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => __("Image X position", "axiomthemes"),
						"description" => __("Parallax background X position (in percents)", "axiomthemes"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => __("Video background", "axiomthemes"),
						"description" => __("Paste URL for video file to show it as parallax background", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => __("Video ratio", "axiomthemes"),
						"description" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "axiomthemes"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiomthemes"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiomthemes"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Content", "axiomthemes"),
						"description" => __("Content for the parallax container", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => __("Popup window", "axiomthemes"),
				"description" => __("Container for any html-block with desired class and style for popup window", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiomthemes"),
						"description" => __("Content for popup container", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => __("Price", "axiomthemes"),
				"description" => __("Insert price with decoration", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => __("Money", "axiomthemes"),
						"description" => __("Money value (dot or comma separated)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "axiomthemes"),
						"description" => __("Currency character", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "axiomthemes"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align price to left or right side", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => __("Price block", "axiomthemes"),
				"description" => __("Insert price block with title, price and description", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Block title", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiomthemes"),
						"description" => __("URL for link from button (at bottom of the block)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => __("Link text", "axiomthemes"),
						"description" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiomthemes"),
						"description" => __("Select icon from Fontello icons set (placed before/instead price)", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => __("Money", "axiomthemes"),
						"description" => __("Money value (dot or comma separated)", "axiomthemes"),
						"admin_label" => true,
						"group" => __('Money', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "axiomthemes"),
						"description" => __("Currency character", "axiomthemes"),
						"admin_label" => true,
						"group" => __('Money', 'axiomthemes'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "axiomthemes"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "axiomthemes"),
						"admin_label" => true,
						"group" => __('Money', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align price to left or right side", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Description", "axiomthemes"),
						"description" => __("Description for this price block", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends AXIOMTHEMES_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => __("Quote", "axiomthemes"),
				"description" => __("Quote text", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
                    array(
                        "param_name" => "style",
                        "heading" => __("Style", "axiomthemes"),
                        "description" => __("Quote style", "axiomthemes"),
                        "class" => "",
                        "value" => array( 'Dark' => '1', 'White' => '2'),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "cite",
						"heading" => __("Quote cite", "axiomthemes"),
						"description" => __("URL for the quote cite link", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title (author)", "axiomthemes"),
						"description" => __("Quote title (author name)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Quote content", "axiomthemes"),
						"description" => __("Quote content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiomthemes_vc_width(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => __("Reviews", "axiomthemes"),
				"description" => __("Insert reviews block in the single post", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align counter to left, center or right", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => __("Search form", "axiomthemes"),
				"description" => __("Insert search form", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiomthemes"),
						"description" => __("Select style to display search field", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Regular', 'axiomthemes') => "regular",
							__('Flat', 'axiomthemes') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title (placeholder) for the search field", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => __("Search &hellip;", 'axiomthemes'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => __("AJAX", "axiomthemes"),
						"description" => __("Search via AJAX or reload page", "axiomthemes"),
						"class" => "",
						"value" => array(__('Use AJAX search', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => __("Section container", "axiomthemes"),
				"description" => __("Container for any block ([block] analog - to enable nesting)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "axiomthemes"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select block alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "axiomthemes"),
						"description" => __("Select width for columns emulation", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "axiomthemes"),
						"description" => __("Use pan effect to show section content", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"class" => "",
						"value" => array(__('Content scroller', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiomthemes"),
						"description" => __("Use scroller to show section content", "axiomthemes"),
						"group" => __('Scroll', 'axiomthemes'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll and Pan direction", "axiomthemes"),
						"description" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "axiomthemes"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "axiomthemes"),
						"class" => "",
						"group" => __('Scroll', 'axiomthemes'),
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiomthemes"),
						"description" => __("Any color for objects in this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiomthemes"),
						"description" => __("Main background tint: dark or light", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiomthemes"),
						"description" => __("Select background image from library for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiomthemes"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiomthemes"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiomthemes"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiomthemes"),
						"description" => __("Font weight of the text", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Default', 'axiomthemes') => 'inherit',
							__('Thin (100)', 'axiomthemes') => '100',
							__('Light (300)', 'axiomthemes') => '300',
							__('Normal (400)', 'axiomthemes') => '400',
							__('Bold (700)', 'axiomthemes') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiomthemes"),
						"description" => __("Content for section container", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends AXIOMTHEMES_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => __("Skills", "axiomthemes"),
				"description" => __("Insert skills diagramm", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => __("Max value", "axiomthemes"),
						"description" => __("Max value for skills items", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Skills type", "axiomthemes"),
						"description" => __("Select type of skills block", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'axiomthemes') => 'bar',
							__('Pie chart', 'axiomthemes') => 'pie',
							__('Counter', 'axiomthemes') => 'counter',
							__('Arc', 'axiomthemes') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => __("Skills layout", "axiomthemes"),
						"description" => __("Select layout of skills block", "axiomthemes"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'axiomthemes') => 'rows',
							__('Columns', 'axiomthemes') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "axiomthemes"),
						"description" => __("Select direction of skills block", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counters style", "axiomthemes"),
						"description" => __("Select style of skills items (only for type=counter)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiomthemes') => '1',
							__('Style 2', 'axiomthemes') => '2',
							__('Style 3', 'axiomthemes') => '3',
							__('Style 4', 'axiomthemes') => '4'
						),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns count", "axiomthemes"),
						"description" => __("Skills columns count (required)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiomthemes"),
						"description" => __("Color for all skills items", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Background color for all skills items (only for type=pie)", "axiomthemes"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "axiomthemes"),
						"description" => __("Border color for all skills items (only for type=pie)", "axiomthemes"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title of the skills block", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "axiomthemes"),
						"description" => __("Default subtitle of the skills block (only if type=arc)", "axiomthemes"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Align skills block to left or right side", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => __("Skill", "axiomthemes"),
				"description" => __("Skills item", "axiomthemes"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title for the current skills item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Value", "axiomthemes"),
						"description" => __("Value for the current skills item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "50",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiomthemes"),
						"description" => __("Color for current skills item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Background color for current skills item (only for type=pie)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "axiomthemes"),
						"description" => __("Border color for current skills item (only for type=pie)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => __("Item style", "axiomthemes"),
						"description" => __("Select style for the current skills item (only for type=counter)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiomthemes') => '1',
							__('Style 2', 'axiomthemes') => '2',
							__('Style 3', 'axiomthemes') => '3',
							__('Style 4', 'axiomthemes') => '4'
						),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends AXIOMTHEMES_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => __("Slider", "axiomthemes"),
				"description" => __("Insert slider", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => __("Engine", "axiomthemes"),
						"description" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Float slider", "axiomthemes"),
						"description" => __("Float slider to left or right side", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom slides", "axiomthemes"),
						"description" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "axiomthemes"),
						"class" => "",
						"value" => array(__('Custom slides', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					)
					),
					axiomthemes_exists_revslider() || axiomthemes_exists_royalslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => __("Revolution slider alias or Royal Slider ID", "axiomthemes"),
						"description" => __("Alias for Revolution slider or Royal slider ID", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo','royal')
						),
						"value" => "",
						"type" => "textfield"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "axiomthemes"),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(axiomthemes_array_merge(array(0 => __('- Select category -', 'axiomthemes')), $AXIOMTHEMES_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Swiper: Number of posts", "axiomthemes"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Swiper: Offset before select posts", "axiomthemes"),
						"description" => __("Skip posts before select next part.", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Swiper: Post sorting", "axiomthemes"),
						"description" => __("Select desired posts sorting method", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Swiper: Post order", "axiomthemes"),
						"description" => __("Select desired posts order", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Swiper: Post IDs list", "axiomthemes"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Swiper: Show slider controls", "axiomthemes"),
						"description" => __("Show arrows inside slider", "axiomthemes"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Show controls', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => __("Swiper: Show slider pagination", "axiomthemes"),
						"description" => __("Show bullets or titles to switch slides", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Dots', 'axiomthemes') => 'yes',
								__('Side Titles', 'axiomthemes') => 'full',
								__('Over Titles', 'axiomthemes') => 'over',
								__('None', 'axiomthemes') => 'no'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => __("Swiper: Show titles section", "axiomthemes"),
						"description" => __("Show section with post's title and short post's description", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'axiomthemes') => "no",
								__('Show/Hide info', 'axiomthemes') => "slide",
								__('Fixed info', 'axiomthemes') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => __("Swiper: Post descriptions", "axiomthemes"),
						"description" => __("Show post's excerpt max length (characters)", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Swiper: Post's title as link", "axiomthemes"),
						"description" => __("Make links from post's titles", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Titles as a links', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => __("Swiper: Crop images", "axiomthemes"),
						"description" => __("Crop images in each slide or live it unchanged", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Crop images', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Swiper: Autoheight", "axiomthemes"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Autoheight', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Swiper: Slides change interval", "axiomthemes"),
						"description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
						"group" => __('Details', 'axiomthemes'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => __("Slide", "axiomthemes"),
				"description" => __("Slider item - single slide", "axiomthemes"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => __("URL (source) for image file", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for the current slide", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends AXIOMTHEMES_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => __("Social icons", "axiomthemes"),
				"description" => __("Custom social icons", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "size",
						"heading" => __("Icon's size", "axiomthemes"),
						"description" => __("Size of the icons", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Tiny', 'axiomthemes') => 'tiny',
							__('Small', 'axiomthemes') => 'small',
							__('Large', 'axiomthemes') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Manual socials list", "axiomthemes"),
						"description" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom socials", "axiomthemes"),
						"description" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "axiomthemes"),
						"class" => "",
						"value" => array(__('Custom socials', 'axiomthemes') => 'yes'),
						"type" => "checkbox"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => __("Custom social item", "axiomthemes"),
				"description" => __("Custom social item: name, profile url and icon url", "axiomthemes"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => __("Social name", "axiomthemes"),
						"description" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("Your profile URL", "axiomthemes"),
						"description" => __("URL of your profile in specified social network", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("URL (source) for icon file", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for the current social icon", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends AXIOMTHEMES_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends AXIOMTHEMES_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => __("Table", "axiomthemes"),
				"description" => __("Insert a table", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Cells content alignment", "axiomthemes"),
						"description" => __("Select alignment for each table cell", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Table content", "axiomthemes"),
						"description" => __("Content, created with any table-generator", "axiomthemes"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					axiomthemes_vc_width(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends AXIOMTHEMES_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => __("Tabs", "axiomthemes"),
				"description" => __("Tabs", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Tabs style", "axiomthemes"),
						"description" => __("Select style of tabs items", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiomthemes') => '1',
							__('Style 2', 'axiomthemes') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened tab", "axiomthemes"),
						"description" => __("Number of initially opened tab", "axiomthemes"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Scroller", "axiomthemes"),
						"description" => __("Use scroller to show tab content (height parameter required)", "axiomthemes"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_tab title="' . __( 'Tab 1', 'axiomthemes' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . __( 'Tab 2', 'axiomthemes' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => __("Tab item", "axiomthemes"),
				"description" => __("Single tab item", "axiomthemes"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Tab title", "axiomthemes"),
						"description" => __("Title for current tab", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => __("Tab ID", "axiomthemes"),
						"description" => __("ID for current tab (required). Please, start it from letter.", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends AXIOMTHEMES_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends AXIOMTHEMES_VC_ShortCodeTab {}
			
			
			
			
			// Team
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_team",
				"name" => __("Team", "axiomthemes"),
				"description" => __("Insert team members", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Team style", "axiomthemes"),
						"description" => __("Select style to display team members", "axiomthemes"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiomthemes') => 1,
							__('Style 2', 'axiomthemes') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns", "axiomthemes"),
						"description" => __("How many columns use to show team members", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiomthemes"),
						"description" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "axiomthemes"),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories", "axiomthemes"),
						"description" => __("Put here comma separated categories (ids or slugs) to show team members. If empty - select team members from any category (group) or from IDs list", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "axiomthemes"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiomthemes"),
						"description" => __("Skip posts before select next part.", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post sorting", "axiomthemes"),
						"description" => __("Select desired posts sorting method", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiomthemes"),
						"description" => __("Select desired posts order", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Team member's IDs list", "axiomthemes"),
						"description" => __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . __( 'Member 1', 'axiomthemes' ) . '"][/trx_team_item]
					[trx_team_item user="' . __( 'Member 2', 'axiomthemes' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_team_item",
				"name" => __("Team member", "axiomthemes"),
				"description" => __("Team member - all data pull out from it account on your site", "axiomthemes"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Registered user", "axiomthemes"),
						"description" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['users']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => __("Team member", "axiomthemes"),
						"description" => __("Select one of team members (if present) or put name, position, etc. in fields below", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['members']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "axiomthemes"),
						"description" => __("Link on team member's personal page", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "axiomthemes"),
						"description" => __("Team member's name", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => __("Position", "axiomthemes"),
						"description" => __("Team member's position", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "axiomthemes"),
						"description" => __("Team member's e-mail", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Member's Photo", "axiomthemes"),
						"description" => __("Team member's photo (avatar", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Socials", "axiomthemes"),
						"description" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Team extends AXIOMTHEMES_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Team_Item extends AXIOMTHEMES_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Testimonials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_testimonials",
				"name" => __("Testimonials", "axiomthemes"),
				"description" => __("Insert testimonials slider", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", "axiomthemes"),
						"description" => __("Show control buttons", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Testimonials change interval", "axiomthemes"),
						"description" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Alignment of the testimonials block", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "axiomthemes"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiomthemes"),
						"description" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "axiomthemes"),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories", "axiomthemes"),
						"description" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "axiomthemes"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiomthemes"),
						"description" => __("Skip posts before select next part.", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post sorting", "axiomthemes"),
						"description" => __("Select desired posts sorting method", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiomthemes"),
						"description" => __("Select desired posts order", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "axiomthemes"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
						"group" => __('Query', 'axiomthemes'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiomthemes"),
						"description" => __("Main background tint: dark or light", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiomthemes"),
						"description" => __("Select background image from library for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiomthemes"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiomthemes"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => __("Testimonial", "axiomthemes"),
				"description" => __("Single testimonials item", "axiomthemes"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => __("Author", "axiomthemes"),
						"description" => __("Name of the testimonmials author", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "axiomthemes"),
						"description" => __("Link URL to the testimonmials author page", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "axiomthemes"),
						"description" => __("E-mail of the testimonmials author", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Photo", "axiomthemes"),
						"description" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "content",
						"heading" => __("Testimonials text", "axiomthemes"),
						"description" => __("Current testimonials text", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Testimonials extends AXIOMTHEMES_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Testimonials_Item extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => __("Title", "axiomthemes"),
				"description" => __("Create header tag (1-6 level) with many styles", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Title content", "axiomthemes"),
						"description" => __("Title content", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => __("Title type", "axiomthemes"),
						"description" => __("Title type (header level)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Header 1', 'axiomthemes') => '1',
							__('Header 2', 'axiomthemes') => '2',
							__('Header 3', 'axiomthemes') => '3',
							__('Header 4', 'axiomthemes') => '4',
							__('Header 5', 'axiomthemes') => '5',
							__('Header 6', 'axiomthemes') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Title style", "axiomthemes"),
						"description" => __("Title style: only text (regular) or with icon/image (iconed)", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Regular', 'axiomthemes') => 'regular',
							__('Underline', 'axiomthemes') => 'underline',
							__('Divider', 'axiomthemes') => 'divider',
							__('With icon (image)', 'axiomthemes') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Title text alignment", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiomthemes"),
						"description" => __("Custom font size. If empty - use theme default", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiomthemes"),
						"description" => __("Custom font weight. If empty or inherit - use theme default", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('Default', 'axiomthemes') => 'inherit',
							__('Thin (100)', 'axiomthemes') => '100',
							__('Light (300)', 'axiomthemes') => '300',
							__('Normal (400)', 'axiomthemes') => '400',
							__('Semibold (600)', 'axiomthemes') => '600',
							__('Bold (700)', 'axiomthemes') => '700',
							__('Black (900)', 'axiomthemes') => '900'
						),
						"type" => "dropdown"
					),
                    array(
                        "param_name" => "fig_border",
                        "heading" => __("Figure bottom border", "axiomthemes"),
                        "description" => __("Apply a figure bottom border", "axiomthemes"),
                        "class" => "",
                        "value" => array('None' => '', 'Red' => 'fig_border', 'White' => 'fig_border_white', 'Blue' => 'fig_border_blue' ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "color",
						"heading" => __("Title color", "axiomthemes"),
						"description" => __("Select color for the title", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title font icon", "axiomthemes"),
						"description" => __("Select font icon for the title from Fontello icons set (if style=iconed)", "axiomthemes"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'axiomthemes'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("or image icon", "axiomthemes"),
						"description" => __("Select image icon for the title instead icon above (if style=iconed)", "axiomthemes"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'axiomthemes'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => __("or select uploaded image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site (if style=iconed)", "axiomthemes"),
						"group" => __('Icon &amp; Image', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => __("Image (picture) size", "axiomthemes"),
						"description" => __("Select image (picture) size (if style=iconed)", "axiomthemes"),
						"group" => __('Icon &amp; Image', 'axiomthemes'),
						"class" => "",
						"value" => array(
							__('Small', 'axiomthemes') => 'small',
							__('Medium', 'axiomthemes') => 'medium',
							__('Large', 'axiomthemes') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => __("Icon (image) position", "axiomthemes"),
						"description" => __("Select icon (image) position (if style=iconed)", "axiomthemes"),
						"group" => __('Icon &amp; Image', 'axiomthemes'),
						"class" => "",
						"value" => array(
							__('Top', 'axiomthemes') => 'top',
							__('Left', 'axiomthemes') => 'left'
						),
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => __("Toggles", "axiomthemes"),
				"description" => __("Toggles items", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Toggles style", "axiomthemes"),
						"description" => __("Select style for display toggles", "axiomthemes"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiomthemes') => 1,
							__('Style 2', 'axiomthemes') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", "axiomthemes"),
						"description" => __("Display counter before each toggles title", "axiomthemes"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiomthemes"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiomthemes"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class']
				),
				'default_content' => '
					[trx_toggles_item title="' . __( 'Item 1 title', 'axiomthemes' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . __( 'Item 2 title', 'axiomthemes' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "axiomthemes").'">'.__("Add item", "axiomthemes").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => __("Toggles item", "axiomthemes"),
				"description" => __("Single toggles item", "axiomthemes"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiomthemes"),
						"description" => __("Title for current toggles item", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Open on show", "axiomthemes"),
						"description" => __("Open current toggle item on show", "axiomthemes"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiomthemes"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiomthemes"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "axiomthemes"),
						"class" => "",
						"value" => $AXIOMTHEMES_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends AXIOMTHEMES_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends AXIOMTHEMES_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => __("Twitter", "axiomthemes"),
				"description" => __("Insert twitter feed into post (page)", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Twitter Username", "axiomthemes"),
						"description" => __("Your username in the twitter account. If empty - get it from Theme Options.", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => __("Consumer Key", "axiomthemes"),
						"description" => __("Consumer Key from the twitter account", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => __("Consumer Secret", "axiomthemes"),
						"description" => __("Consumer Secret from the twitter account", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => __("Token Key", "axiomthemes"),
						"description" => __("Token Key from the twitter account", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => __("Token Secret", "axiomthemes"),
						"description" => __("Token Secret from the twitter account", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Tweets number", "axiomthemes"),
						"description" => __("Number tweets to show", "axiomthemes"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", "axiomthemes"),
						"description" => __("Show control buttons", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Tweets change interval", "axiomthemes"),
						"description" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Alignment of the tweets block", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "axiomthemes"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiomthemes"),
						"description" => __("Main background tint: dark or light", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiomthemes"),
						"description" => __("Any background color for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiomthemes"),
						"description" => __("Select background image from library for this section", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiomthemes"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiomthemes"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiomthemes"),
						"group" => __('Colors and Images', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => __("Video", "axiomthemes"),
				"description" => __("Insert video player", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for video file", "axiomthemes"),
						"description" => __("Paste URL for video file", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => __("Ratio", "axiomthemes"),
						"description" => __("Select ratio for display video", "axiomthemes"),
						"class" => "",
						"value" => array(
							__('16:9', 'axiomthemes') => "16:9",
							__('4:3', 'axiomthemes') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay video", "axiomthemes"),
						"description" => __("Autoplay video on page load", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Select block alignment", "axiomthemes"),
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for video preview", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "axiomthemes"),
						"description" => __("Top offset (padding) from background image to video block (in percent). For example: 3%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "axiomthemes"),
						"description" => __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "axiomthemes"),
						"description" => __("Left offset (padding) from background image to video block (in percent). For example: 20%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "axiomthemes"),
						"description" => __("Right offset (padding) from background image to video block (in percent). For example: 12%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => __("Zoom", "axiomthemes"),
				"description" => __("Insert the image with zoom/lens effect", "axiomthemes"),
				"category" => __('Content', 'axiomthemes'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => __("Effect", "axiomthemes"),
						"description" => __("Select effect to display overlapping image", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Lens', 'axiomthemes') => 'lens',
							__('Zoom', 'axiomthemes') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => __("Main image", "axiomthemes"),
						"description" => __("Select or upload main image", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => __("Overlaping image", "axiomthemes"),
						"description" => __("Select or upload overlaping image", "axiomthemes"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiomthemes"),
						"description" => __("Float zoom to left or right side", "axiomthemes"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiomthemes"),
						"description" => __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "axiomthemes"),
						"description" => __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "axiomthemes"),
						"description" => __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "axiomthemes"),
						"description" => __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "axiomthemes"),
						"description" => __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "axiomthemes"),
						"group" => __('Background', 'axiomthemes'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiomthemes_vc_width(),
					axiomthemes_vc_height(),
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_top'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_bottom'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_left'],
					$AXIOMTHEMES_GLOBALS['vc_params']['margin_right'],
					$AXIOMTHEMES_GLOBALS['vc_params']['id'],
					$AXIOMTHEMES_GLOBALS['vc_params']['class'],
					$AXIOMTHEMES_GLOBALS['vc_params']['animation'],
					$AXIOMTHEMES_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends AXIOMTHEMES_VC_ShortCodeSingle {}
			

			do_action('axiomthemes_action_shortcodes_list_vc');
			
			
			if (false && axiomthemes_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => __("Cart", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show cart page", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends AXIOMTHEMES_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => __("Checkout", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show checkout page", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends AXIOMTHEMES_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => __("My Account", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show my account page", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends AXIOMTHEMES_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => __("Order Tracking", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show order tracking page", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends AXIOMTHEMES_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => __("Shop Messages", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show shop messages", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Shop_Messages extends AXIOMTHEMES_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => __("Product Page", "axiomthemes"),
					"description" => __("WooCommerce shortcode: display single product page", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiomthemes"),
							"description" => __("SKU code of displayed product", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiomthemes"),
							"description" => __("ID of displayed product", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => __("Post type", "axiomthemes"),
							"description" => __("Post type for the WP query (leave 'product')", "axiomthemes"),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => __("Post status", "axiomthemes"),
							"description" => __("Display posts only with this status", "axiomthemes"),
							"class" => "",
							"value" => array(
								__('Publish', 'axiomthemes') => 'publish',
								__('Protected', 'axiomthemes') => 'protected',
								__('Private', 'axiomthemes') => 'private',
								__('Pending', 'axiomthemes') => 'pending',
								__('Draft', 'axiomthemes') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => __("Product", "axiomthemes"),
					"description" => __("WooCommerce shortcode: display one product", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiomthemes"),
							"description" => __("Product's SKU code", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiomthemes"),
							"description" => __("Product's ID", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => __("Best Selling Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show best selling products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => __("Recent Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show recent products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => __("Related Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show related products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => __("Featured Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show featured products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => __("Top Rated Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show top rated products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => __("Sale Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: list products on sale", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => __("Products from category", "axiomthemes"),
					"description" => __("WooCommerce shortcode: list products in specified category(-ies)", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => __("Categories", "axiomthemes"),
							"description" => __("Comma separated category slugs", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => __("Operator", "axiomthemes"),
							"description" => __("Categories operator", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('IN', 'axiomthemes') => 'IN',
								__('NOT IN', 'axiomthemes') => 'NOT IN',
								__('AND', 'axiomthemes') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => __("Products", "axiomthemes"),
					"description" => __("WooCommerce shortcode: list all products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => __("SKUs", "axiomthemes"),
							"description" => __("Comma separated SKU codes of products", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "axiomthemes"),
							"description" => __("Comma separated ID of products", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => __("Products by Attribute", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show products with specified attribute", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many products showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => __("Attribute", "axiomthemes"),
							"description" => __("Attribute name", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => __("Filter", "axiomthemes"),
							"description" => __("Attribute value", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => __("Product Categories", "axiomthemes"),
					"description" => __("WooCommerce shortcode: show categories with products", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => __("Number", "axiomthemes"),
							"description" => __("How many categories showed", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiomthemes"),
							"description" => __("How many columns per row use for categories output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiomthemes') => 'date',
								__('Title', 'axiomthemes') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiomthemes"),
							"description" => __("Sorting order for products output", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOMTHEMES_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => __("Parent", "axiomthemes"),
							"description" => __("Parent category slug", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "axiomthemes"),
							"description" => __("Comma separated ID of products", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => __("Hide empty", "axiomthemes"),
							"description" => __("Hide empty categories", "axiomthemes"),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends AXIOMTHEMES_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => __("Add to cart", "axiomthemes"),
					"description" => __("WooCommerce shortcode: Display a single product price + cart button", "axiomthemes"),
					"category" => __('WooCommerce', 'axiomthemes'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiomthemes"),
							"description" => __("Product's ID", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiomthemes"),
							"description" => __("Product's SKU code", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => __("Quantity", "axiomthemes"),
							"description" => __("How many item add", "axiomthemes"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => __("Show price", "axiomthemes"),
							"description" => __("Show price near button", "axiomthemes"),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => __("Class", "axiomthemes"),
							"description" => __("CSS class", "axiomthemes"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => __("CSS style", "axiomthemes"),
							"description" => __("CSS style for additional decoration", "axiomthemes"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends AXIOMTHEMES_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>