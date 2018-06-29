<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'axiomthemes_shortcodes_is_used' ) ) {
	function axiomthemes_shortcodes_is_used() {
		return axiomthemes_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| axiomthemes_vc_is_frontend();															// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'axiomthemes_shortcodes_width' ) ) {
	function axiomthemes_shortcodes_width($w="") {
		return array(
			"title" => __("Width", "axiomthemes"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'axiomthemes_shortcodes_height' ) ) {
	function axiomthemes_shortcodes_height($h='') {
		return array(
			"title" => __("Height", "axiomthemes"),
			"desc" => __("Width (in pixels or percent) and height (only in pixels) of element", "axiomthemes"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_shortcodes_settings_theme_setup' ) ) {
//	if ( axiomthemes_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'axiomthemes_action_after_init_theme', 'axiomthemes_shortcodes_settings_theme_setup' );
	function axiomthemes_shortcodes_settings_theme_setup() {
		if (axiomthemes_shortcodes_is_used()) {
			global $AXIOMTHEMES_GLOBALS;

			// Prepare arrays 
			$AXIOMTHEMES_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => __("Element ID", "axiomthemes"),
					"desc" => __("ID for current element", "axiomthemes"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => __("Element CSS class", "axiomthemes"),
					"desc" => __("CSS class for current element (optional)", "axiomthemes"),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => __("CSS styles", "axiomthemes"),
					"desc" => __("Any additional CSS rules (if need)", "axiomthemes"),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => __("Top margin", "axiomthemes"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => __("Bottom margin", "axiomthemes"),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => __("Left margin", "axiomthemes"),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => __("Right margin", "axiomthemes"),
					"desc" => __("Margins around list (in pixels).", "axiomthemes"),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> __('Unordered', 'axiomthemes'),
					'ol'	=> __('Ordered', 'axiomthemes'),
					'iconed'=> __('Iconed', 'axiomthemes')
				),
				'yes_no'	=> axiomthemes_get_list_yesno(),
				'on_off'	=> axiomthemes_get_list_onoff(),
				'dir' 		=> axiomthemes_get_list_directions(),
				'align'		=> axiomthemes_get_list_alignments(),
				'float'		=> axiomthemes_get_list_floats(),
				'show_hide'	=> axiomthemes_get_list_showhide(),
				'sorting' 	=> axiomthemes_get_list_sortings(),
				'ordering' 	=> axiomthemes_get_list_orderings(),
				'sliders'	=> axiomthemes_get_list_sliders(),
				'users'		=> axiomthemes_get_list_users(),
				'members'	=> axiomthemes_get_list_posts(false, array('post_type'=>'team', 'orderby'=>'title', 'order'=>'asc', 'return'=>'title')),
				'categories'=> axiomthemes_get_list_categories(),
				'testimonials_groups'=> axiomthemes_get_list_terms(false, 'testimonial_group'),
				'team_groups'=> axiomthemes_get_list_terms(false, 'team_group'),
				'columns'	=> axiomthemes_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), axiomthemes_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), axiomthemes_get_list_icons()),
				'locations'	=> axiomthemes_get_list_dedicated_locations(),
				'filters'	=> axiomthemes_get_list_portfolio_filters(),
				'formats'	=> axiomthemes_get_list_post_formats_filters(),
				'hovers'	=> axiomthemes_get_list_hovers(),
				'hovers_dir'=> axiomthemes_get_list_hovers_directions(),
				'tint'		=> axiomthemes_get_list_bg_tints(),
				'animations'=> axiomthemes_get_list_animations_in(),
				'blogger_styles'	=> axiomthemes_get_list_templates_blogger(),
				'posts_types'		=> axiomthemes_get_list_posts_types(),
				'button_styles'		=> axiomthemes_get_list_button_styles(),
				'googlemap_styles'	=> axiomthemes_get_list_googlemap_styles(),
				'field_types'		=> axiomthemes_get_list_field_types(),
				'label_positions'	=> axiomthemes_get_list_label_positions()
			);

			$AXIOMTHEMES_GLOBALS['sc_params']['animation'] = array(
				"title" => __("Animation",  'axiomthemes'),
				"desc" => __('Select animation while object enter in the visible area of page',  'axiomthemes'),
				"value" => "none",
				"type" => "select",
				"options" => $AXIOMTHEMES_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$AXIOMTHEMES_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => __("Accordion", "axiomthemes"),
					"desc" => __("Accordion items", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Accordion style", "axiomthemes"),
							"desc" => __("Select style for display accordion", "axiomthemes"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "axiomthemes"),
							"desc" => __("Display counter before each accordion title", "axiomthemes"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => __("Initially opened item", "axiomthemes"),
							"desc" => __("Number of initially opened item", "axiomthemes"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'axiomthemes'),
							"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'axiomthemes'),
							"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => __("Item", "axiomthemes"),
						"desc" => __("Accordion item", "axiomthemes"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Accordion item title", "axiomthemes"),
								"desc" => __("Title for current accordion item", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'axiomthemes'),
								"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'axiomthemes'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'axiomthemes'),
								"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'axiomthemes'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Accordion item content", "axiomthemes"),
								"desc" => __("Current accordion item content", "axiomthemes"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => __("Anchor", "axiomthemes"),
					"desc" => __("Insert anchor for the TOC (table of content)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __("Anchor's icon",  'axiomthemes'),
							"desc" => __('Select icon for the anchor from Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => __("Short title", "axiomthemes"),
							"desc" => __("Short title of the anchor (for the table of content)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Long description", "axiomthemes"),
							"desc" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => __("External URL", "axiomthemes"),
							"desc" => __("External URL for this TOC item", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => __("Add separator", "axiomthemes"),
							"desc" => __("Add separator under item in the TOC", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => __("Audio", "axiomthemes"),
					"desc" => __("Insert audio player", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for audio file", "axiomthemes"),
							"desc" => __("URL for audio file", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose audio', 'axiomthemes'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose audio file', 'axiomthemes'),
									'update' => __('Select audio file', 'axiomthemes')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
                        "style" => array(
                            "title" => __("Style", "axiomthemes"),
                            "desc" => __("Select style", "axiomthemes"),
                            "value" => "none",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => array('audio_normal' => 'Normal', 'audio_dark' => 'Dark'),
                        ),
						"image" => array(
							"title" => __("Cover image", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for audio cover", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Title of the audio file", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => __("Author", "axiomthemes"),
							"desc" => __("Author of the audio file", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Show controls", "axiomthemes"),
							"desc" => __("Show controls in audio player", "axiomthemes"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => __("Autoplay audio", "axiomthemes"),
							"desc" => __("Autoplay audio on page load", "axiomthemes"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select block alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => __("Block container", "axiomthemes"),
					"desc" => __("Container for any block ([section] analog - to enable nesting)", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "axiomthemes"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select block alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "axiomthemes"),
							"desc" => __("Select width for columns emulation", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => __("Use pan effect", "axiomthemes"),
							"desc" => __("Use pan effect to show section content", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiomthemes"),
							"desc" => __("Use scroller to show section content", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll direction", "axiomthemes"),
							"desc" => __("Scroll direction (if Use scroller = yes)", "axiomthemes"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "axiomthemes"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "axiomthemes"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => __("Fore color", "axiomthemes"),
							"desc" => __("Any color for objects in this section", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiomthemes"),
							"desc" => __("Main background tint: dark or light", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Any background color for this section", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiomthemes"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiomthemes"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiomthemes"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "axiomthemes"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiomthemes"),
							"desc" => __("Font weight of the text", "axiomthemes"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiomthemes'),
								'300' => __('Light (300)', 'axiomthemes'),
								'400' => __('Normal (400)', 'axiomthemes'),
								'700' => __('Bold (700)', 'axiomthemes')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "axiomthemes"),
							"desc" => __("Content for section container", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => __("Blogger", "axiomthemes"),
					"desc" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Posts output style", "axiomthemes"),
							"desc" => __("Select desired style for posts output", "axiomthemes"),
							"value" => "regular",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => __("Show filters", "axiomthemes"),
							"desc" => __("Use post's tags or categories as filter buttons", "axiomthemes"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => __("Hover effect", "axiomthemes"),
							"desc" => __("Select hover effect (only if style=Portfolio)", "axiomthemes"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','courses')
							),
							"value" => "",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => __("Hover direction", "axiomthemes"),
							"desc" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "axiomthemes"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','courses'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => __("Posts direction", "axiomthemes"),
							"desc" => __("Display posts in horizontal or vertical direction", "axiomthemes"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => __("Post type", "axiomthemes"),
							"desc" => __("Select post type to show", "axiomthemes"),
							"value" => "post",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiomthemes"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => __("Categories list", "axiomthemes"),
							"desc" => __("Select the desired categories. If not selected - show posts from any category or from IDs list", "axiomthemes"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => __("Total posts to show", "axiomthemes"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns number", "axiomthemes"),
							"desc" => __("How many columns used to show posts? If empty or 0 - equal to posts number", "axiomthemes"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiomthemes"),
							"desc" => __("Skip posts before select next part.", "axiomthemes"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiomthemes"),
							"desc" => __("Select desired posts sorting method", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiomthemes"),
							"desc" => __("Select desired posts order", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => __("Select posts only", "axiomthemes"),
							"desc" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "axiomthemes"),
							"value" => "no",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiomthemes"),
							"desc" => __("Use scroller to show all posts", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => __("Show slider controls", "axiomthemes"),
							"desc" => __("Show arrows to control scroll slider", "axiomthemes"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => __("Dedicated content location", "axiomthemes"),
							"desc" => __("Select position for dedicated content (only for style=excerpt)", "axiomthemes"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => __("Show rating stars", "axiomthemes"),
							"desc" => __("Show rating stars under post's header", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => __("Show post info block", "axiomthemes"),
							"desc" => __("Show post info block (author, date, tags, etc.)", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => __("Allow links on the post", "axiomthemes"),
							"desc" => __("Allow links on the post from each blogger item", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => __("Description length", "axiomthemes"),
							"desc" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "axiomthemes"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => __("More link text", "axiomthemes"),
							"desc" => __("Read more link text. If empty - show 'More', else - used as link text", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => __("Break", "axiomthemes"),
					"desc" => __("Line break with clear floating (if need)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => __("Clear floating", "axiomthemes"),
							"desc" => __("Clear floating (if need)", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => __('None', 'axiomthemes'),
								'left' => __('Left', 'axiomthemes'),
								'right' => __('Right', 'axiomthemes'),
								'both' => __('Both', 'axiomthemes')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => __("Button", "axiomthemes"),
					"desc" => __("Button with link", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Caption", "axiomthemes"),
							"desc" => __("Button caption", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => __("Button's shape", "axiomthemes"),
							"desc" => __("Select button's shape", "axiomthemes"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => __('Square', 'axiomthemes'),
								'round' => __('Round', 'axiomthemes')
							),
							"type" => "switch"
						), 
						"style" => array(
							"title" => __("Button's style", "axiomthemes"),
							"desc" => __("Select button's style", "axiomthemes"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
                                'clear' => __('Clear', 'axiomthemes'),
								'dark' => __('Dark', 'axiomthemes'),
								'light' => __('Light', 'axiomthemes'),
                                'red' => __('Red', 'axiomthemes'),
                                'blue' => __('Blue', 'axiomthemes')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => __("Button's size", "axiomthemes"),
							"desc" => __("Select button's size", "axiomthemes"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => __('Small', 'axiomthemes'),
								'medium' => __('Medium', 'axiomthemes'),
								'large' => __('Large', 'axiomthemes')
							),
							"type" => "checklist"
						), 
//						"icon" => array(
//							"title" => __("Button's icon",  'axiomthemes'),
//							"desc" => __('Select icon for the title from Fontello icons set',  'axiomthemes'),
//							"value" => "",
//							"type" => "icons",
//							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
//						),
//						"bg_style" => array(
//							"title" => __("Button's color scheme", "axiomthemes"),
//							"desc" => __("Select button's color scheme", "axiomthemes"),
//							"value" => "custom",
//							"type" => "checklist",
//							"dir" => "horizontal",
//							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['button_styles']
//						),
						"color" => array(
							"title" => __("Button's text color", "axiomthemes"),
							"desc" => __("Any color for button's caption", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Button's backcolor", "axiomthemes"),
							"desc" => __("Any color for button's background", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => __("Button's alignment", "axiomthemes"),
							"desc" => __("Align button to left, center or right", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => __("Link URL", "axiomthemes"),
							"desc" => __("URL for link on button click", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => __("Link target", "axiomthemes"),
							"desc" => __("Target for link on button click", "axiomthemes"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => __("Open link in popup", "axiomthemes"),
							"desc" => __("Open link target in popup window", "axiomthemes"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => __("Rel attribute", "axiomthemes"),
							"desc" => __("Rel attribute for button's link (if need)", "axiomthemes"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiomthemes_shortcodes_width(),
						//"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => __("Chat", "axiomthemes"),
					"desc" => __("Chat message", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Item title", "axiomthemes"),
							"desc" => __("Chat item title", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => __("Item photo", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the item photo (avatar)", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => __("Item link", "axiomthemes"),
							"desc" => __("Chat item link", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Chat item content", "axiomthemes"),
							"desc" => __("Current chat item content", "axiomthemes"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => __("Columns", "axiomthemes"),
					"desc" => __("Insert up to 5 columns in your page (post)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => __("Fluid columns", "axiomthemes"),
							"desc" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						), 
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => __("Column", "axiomthemes"),
						"desc" => __("Column item", "axiomthemes"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => __("Merge columns", "axiomthemes"),
								"desc" => __("Count merged columns from current", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => __("Alignment", "axiomthemes"),
								"desc" => __("Alignment text in the column", "axiomthemes"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => __("Fore color", "axiomthemes"),
								"desc" => __("Any color for objects in this column", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "axiomthemes"),
								"desc" => __("Any background color for this column", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => __("URL for background image file", "axiomthemes"),
								"desc" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => __("Column item content", "axiomthemes"),
								"desc" => __("Current column item content", "axiomthemes"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_contact_form" => array(
					"title" => __("Contact form", "axiomthemes"),
					"desc" => __("Insert contact form", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"custom" => array(
							"title" => __("Custom", "axiomthemes"),
							"desc" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						), 
						"action" => array(
							"title" => __("Action", "axiomthemes"),
							"desc" => __("Contact form action (URL to handle form data). If empty - use internal action", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select form alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Contact form title", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "axiomthemes"),
							"desc" => __("Short description for contact form", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => __("Field", "axiomthemes"),
						"desc" => __("Custom field", "axiomthemes"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => __("Type", "axiomthemes"),
								"desc" => __("Type of the custom field", "axiomthemes"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => __("Name", "axiomthemes"),
								"desc" => __("Name of the custom field", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => __("Default value", "axiomthemes"),
								"desc" => __("Default value of the custom field", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => __("Label", "axiomthemes"),
								"desc" => __("Label for the custom field", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => __("Label position", "axiomthemes"),
								"desc" => __("Label position relative to the field", "axiomthemes"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
							"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
							"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
							"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => __("Content block", "axiomthemes"),
					"desc" => __("Container for main content block with desired class and style (use it only on fullscreen pages)", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Container content", "axiomthemes"),
							"desc" => __("Content for section container", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => __("Countdown", "axiomthemes"),
					"desc" => __("Insert countdown object", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => __("Date", "axiomthemes"),
							"desc" => __("Upcoming date (format: yyyy-mm-dd)", "axiomthemes"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => __("Time", "axiomthemes"),
							"desc" => __("Upcoming time (format: HH:mm:ss)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => __("Style", "axiomthemes"),
							"desc" => __("Countdown style", "axiomthemes"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Align counter to left, center or right", "axiomthemes"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => __("Dropcaps", "axiomthemes"),
					"desc" => __("Make first letter as dropcaps", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiomthemes"),
							"desc" => __("Dropcaps style", "axiomthemes"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes'),
								3 => __('Style 3', 'axiomthemes'),
								4 => __('Style 4', 'axiomthemes')
							)
						),
						"_content_" => array(
							"title" => __("Paragraph content", "axiomthemes"),
							"desc" => __("Paragraph with dropcaps content", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => __("E-mail collector", "axiomthemes"),
					"desc" => __("Collect the e-mail address into specified group", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => __("Group", "axiomthemes"),
							"desc" => __("The name of group to collect e-mail address", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => __("Open", "axiomthemes"),
							"desc" => __("Initially open the input field on show object", "axiomthemes"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Align object to left, center or right", "axiomthemes"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Gap
				"trx_gap" => array(
					"title" => __("Gap", "axiomthemes"),
					"desc" => __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Gap content", "axiomthemes"),
							"desc" => __("Gap inner content", "axiomthemes"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => __("Google map", "axiomthemes"),
					"desc" => __("Insert Google map with desired address or coordinates", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"address" => array(
							"title" => __("Address", "axiomthemes"),
							"desc" => __("Address to show in map center", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"latlng" => array(
							"title" => __("Latitude and Longtitude", "axiomthemes"),
							"desc" => __("Comma separated map center coorditanes (instead Address)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"zoom" => array(
							"title" => __("Zoom", "axiomthemes"),
							"desc" => __("Map zoom factor", "axiomthemes"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => __("Map style", "axiomthemes"),
							"desc" => __("Select map style", "axiomthemes"),
							"value" => "default",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => axiomthemes_shortcodes_width('100%'),
						"height" => axiomthemes_shortcodes_height(240),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => __("Hide/Show any block", "axiomthemes"),
					"desc" => __("Hide or Show any block with desired CSS-selector", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => __("Selector", "axiomthemes"),
							"desc" => __("Any block's CSS-selector", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => __("Hide or Show", "axiomthemes"),
							"desc" => __("New state for the block: hide or show", "axiomthemes"),
							"value" => "yes",
							"size" => "small",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => __("Highlight text", "axiomthemes"),
					"desc" => __("Highlight text with selected color, background color and other styles", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => __("Type", "axiomthemes"),
							"desc" => __("Highlight type", "axiomthemes"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => __('Custom', 'axiomthemes'),
								1 => __('Type 1', 'axiomthemes'),
								2 => __('Type 2', 'axiomthemes'),
								3 => __('Type 3', 'axiomthemes')
							)
						),
						"color" => array(
							"title" => __("Color", "axiomthemes"),
							"desc" => __("Color for the highlighted text", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Background color for the highlighted text", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "axiomthemes"),
							"desc" => __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Highlighting content", "axiomthemes"),
							"desc" => __("Content for highlight", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => __("Icon", "axiomthemes"),
					"desc" => __("Insert icon", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __('Icon',  'axiomthemes'),
							"desc" => __('Select font icon from the Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Icon's color", "axiomthemes"),
							"desc" => __("Icon's color", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => __("Background shape", "axiomthemes"),
							"desc" => __("Shape of the icon background", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => __('None', 'axiomthemes'),
								'round' => __('Round', 'axiomthemes'),
								'square' => __('Square', 'axiomthemes')
							)
						),
						"bg_style" => array(
							"title" => __("Background style", "axiomthemes"),
							"desc" => __("Select icon's color scheme", "axiomthemes"),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['button_styles']
						), 
						"bg_color" => array(
							"title" => __("Icon's background color", "axiomthemes"),
							"desc" => __("Icon's background color", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "axiomthemes"),
							"desc" => __("Icon's font size", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiomthemes"),
							"desc" => __("Icon font weight", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiomthemes'),
								'300' => __('Light (300)', 'axiomthemes'),
								'400' => __('Normal (400)', 'axiomthemes'),
								'700' => __('Bold (700)', 'axiomthemes')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Icon text alignment", "axiomthemes"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => __("Link URL", "axiomthemes"),
							"desc" => __("Link URL from this icon (if not empty)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => __("Image", "axiomthemes"),
					"desc" => __("Insert image into your post (page)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for image file", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Image title (if need)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __("Icon before title",  'axiomthemes'),
							"desc" => __('Select icon for the title from Fontello icons set',  'axiomthemes'),
							"value" => "none",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => __("Float image", "axiomthemes"),
							"desc" => __("Float image to left or right side", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => __("Image Shape", "axiomthemes"),
							"desc" => __("Shape of the image: square (rectangle) or round", "axiomthemes"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => __('Square', 'axiomthemes'),
								"round" => __('Round', 'axiomthemes')
							)
						), 
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => __("Infobox", "axiomthemes"),
					"desc" => __("Insert infobox into your post (page)", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiomthemes"),
							"desc" => __("Infobox style", "axiomthemes"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => __('Regular', 'axiomthemes'),
								'info' => __('Info', 'axiomthemes'),
								'success' => __('Success', 'axiomthemes'),
								'error' => __('Error', 'axiomthemes'),
                                'warning'=> __('Warning','axiomthemes')
							)
						),
						"closeable" => array(
							"title" => __("Closeable box", "axiomthemes"),
							"desc" => __("Create closeable box (with close button)", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => __("Custom icon",  'axiomthemes'),
							"desc" => __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Text color", "axiomthemes"),
							"desc" => __("Any color for text and headers", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Any background color for this infobox", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => __("Infobox content", "axiomthemes"),
							"desc" => __("Content for infobox", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => __("Line", "axiomthemes"),
					"desc" => __("Insert Line into your post (page)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiomthemes"),
							"desc" => __("Line style", "axiomthemes"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => __('Solid', 'axiomthemes'),
								'dashed' => __('Dashed', 'axiomthemes'),
								'dotted' => __('Dotted', 'axiomthemes'),
								'double' => __('Double', 'axiomthemes')
							)
						),
						"color" => array(
							"title" => __("Color", "axiomthemes"),
							"desc" => __("Line color", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => __("List", "axiomthemes"),
					"desc" => __("List items with specific bullets", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Bullet's style", "axiomthemes"),
							"desc" => __("Bullet's style for each list item", "axiomthemes"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => __("Color", "axiomthemes"),
							"desc" => __("List items color", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => __('List icon',  'axiomthemes'),
							"desc" => __("Select list icon from Fontello icons set (only for style=Iconed)",  'axiomthemes'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => __("Icon color", "axiomthemes"),
							"desc" => __("List icons color", "axiomthemes"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
                        "top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => __("Item", "axiomthemes"),
						"desc" => __("List item with specific bullet", "axiomthemes"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => __("List item content", "axiomthemes"),
								"desc" => __("Current list item content", "axiomthemes"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => __("List item title", "axiomthemes"),
								"desc" => __("Current list item title (show it as tooltip)", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => __("Color", "axiomthemes"),
								"desc" => __("Text color for this item", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => __('List icon',  'axiomthemes'),
								"desc" => __("Select list item icon from Fontello icons set (only for style=Iconed)",  'axiomthemes'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => __("Icon color", "axiomthemes"),
								"desc" => __("Icon color for this item", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => __("Link URL", "axiomthemes"),
								"desc" => __("Link URL for the current list item", "axiomthemes"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => __("Link target", "axiomthemes"),
								"desc" => __("Link target for the current list item", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => __("Number", "axiomthemes"),
					"desc" => __("Insert number or any word as set separate characters", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => __("Value", "axiomthemes"),
							"desc" => __("Number or any word", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select block alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => __("Parallax", "axiomthemes"),
					"desc" => __("Create the parallax container (with asinc background image)", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => __("Create gap", "axiomthemes"),
							"desc" => __("Create gap around parallax container", "axiomthemes"),
							"value" => "no",
							"size" => "small",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => __("Dir", "axiomthemes"),
							"desc" => __("Scroll direction for the parallax background", "axiomthemes"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => __('Up', 'axiomthemes'),
								'down' => __('Down', 'axiomthemes')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => __("Speed", "axiomthemes"),
							"desc" => __("Image motion speed (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"color" => array(
							"title" => __("Text color", "axiomthemes"),
							"desc" => __("Select color for text object inside parallax block", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Bg tint", "axiomthemes"),
							"desc" => __("Select tint of the parallax background (for correct font color choise)", "axiomthemes"),
							"value" => "light",
							"size" => "medium",
							"options" => array(
								'light' => __('Light', 'axiomthemes'),
								'dark' => __('Dark', 'axiomthemes')
							),
							"type" => "switch"
						), 
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Select color for parallax background", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the parallax background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => __("Image X position", "axiomthemes"),
							"desc" => __("Image horizontal position (as background of the parallax block) - in percent", "axiomthemes"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => __("Video background", "axiomthemes"),
							"desc" => __("Select video from media library or paste URL for video file from other site to show it as parallax background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'axiomthemes'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'axiomthemes'),
									'update' => __('Select video file', 'axiomthemes')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => __("Video ratio", "axiomthemes"),
							"desc" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "axiomthemes"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiomthemes"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiomthemes"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiomthemes"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => __("Content", "axiomthemes"),
							"desc" => __("Content for the parallax container", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => __("Popup window", "axiomthemes"),
					"desc" => __("Container for any html-block with desired class and style for popup window", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Container content", "axiomthemes"),
							"desc" => __("Content for section container", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => __("Price", "axiomthemes"),
					"desc" => __("Insert price with decoration", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => __("Money", "axiomthemes"),
							"desc" => __("Money value (dot or comma separated)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "axiomthemes"),
							"desc" => __("Currency character", "axiomthemes"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "axiomthemes"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Align price to left or right side", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						), 
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => __("Price block", "axiomthemes"),
					"desc" => __("Insert price block with title, price and description", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Block title", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => __("Link URL", "axiomthemes"),
							"desc" => __("URL for link from button (at bottom of the block)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => __("Link text", "axiomthemes"),
							"desc" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __("Icon",  'axiomthemes'),
							"desc" => __('Select icon from Fontello icons set (placed before/instead price)',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => __("Money", "axiomthemes"),
							"desc" => __("Money value (dot or comma separated)", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "axiomthemes"),
							"desc" => __("Currency character", "axiomthemes"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "axiomthemes"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Align price to left or right side", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => __("Description", "axiomthemes"),
							"desc" => __("Description for this price block", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => __("Quote", "axiomthemes"),
					"desc" => __("Quote text", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
                        "style" => array(
                            "title" => __("Style", "axiomthemes"),
                            "desc" => __("Quote style", "axiomthemes"),
                            "value" => "",
                            "type" => "checklist",
                            "options" => array ( '1' => 'Dark', '2' => 'White')
                        ),
						"cite" => array(
							"title" => __("Quote cite", "axiomthemes"),
							"desc" => __("URL for quote cite", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => __("Title (author)", "axiomthemes"),
							"desc" => __("Quote title (author name)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Quote content", "axiomthemes"),
							"desc" => __("Quote content", "axiomthemes"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => __("Reviews", "axiomthemes"),
					"desc" => __("Insert reviews block in the single post", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Align counter to left, center or right", "axiomthemes"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => __("Search", "axiomthemes"),
					"desc" => __("Show search form", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"ajax" => array(
							"title" => __("Style", "axiomthemes"),
							"desc" => __("Select style to display search field", "axiomthemes"),
							"value" => "regular",
							"options" => array(
								"regular" => __('Regular', 'axiomthemes'),
								"flat" => __('Flat', 'axiomthemes')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Title (placeholder) for the search field", "axiomthemes"),
							"value" => __("Search &hellip;", 'axiomthemes'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => __("AJAX", "axiomthemes"),
							"desc" => __("Search via AJAX or reload page", "axiomthemes"),
							"value" => "yes",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => __("Section container", "axiomthemes"),
					"desc" => __("Container for any block with desired class and style", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "axiomthemes"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "axiomthemes"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select block alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "axiomthemes"),
							"desc" => __("Select width for columns emulation", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => __("Use pan effect", "axiomthemes"),
							"desc" => __("Use pan effect to show section content", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiomthemes"),
							"desc" => __("Use scroller to show section content", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll and Pan direction", "axiomthemes"),
							"desc" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "axiomthemes"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "axiomthemes"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "axiomthemes"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => __("Fore color", "axiomthemes"),
							"desc" => __("Any color for objects in this section", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiomthemes"),
							"desc" => __("Main background tint: dark or light", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Any background color for this section", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiomthemes"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiomthemes"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiomthemes"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "axiomthemes"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiomthemes"),
							"desc" => __("Font weight of the text", "axiomthemes"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiomthemes'),
								'300' => __('Light (300)', 'axiomthemes'),
								'400' => __('Normal (400)', 'axiomthemes'),
								'700' => __('Bold (700)', 'axiomthemes')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "axiomthemes"),
							"desc" => __("Content for section container", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => __("Skills", "axiomthemes"),
					"desc" => __("Insert skills diagramm in your page (post)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => __("Max value", "axiomthemes"),
							"desc" => __("Max value for skills items", "axiomthemes"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => __("Skills type", "axiomthemes"),
							"desc" => __("Select type of skills block", "axiomthemes"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => __('Bar', 'axiomthemes'),
								'pie' => __('Pie chart', 'axiomthemes'),
								'counter' => __('Counter', 'axiomthemes'),
								'arc' => __('Arc', 'axiomthemes')
							)
						), 
						"layout" => array(
							"title" => __("Skills layout", "axiomthemes"),
							"desc" => __("Select layout of skills block", "axiomthemes"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => __('Rows', 'axiomthemes'),
								'columns' => __('Columns', 'axiomthemes')
							)
						),
						"dir" => array(
							"title" => __("Direction", "axiomthemes"),
							"desc" => __("Select direction of skills block", "axiomthemes"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => __("Counters style", "axiomthemes"),
							"desc" => __("Select style of skills items (only for type=counter)", "axiomthemes"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => __("Skills items color", "axiomthemes"),
							"desc" => __("Color for all skills items", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Background color for all skills items (only for type=pie)", "axiomthemes"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => __("Border color", "axiomthemes"),
							"desc" => __("Border color for all skills items (only for type=pie)", "axiomthemes"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"title" => array(
							"title" => __("Skills title", "axiomthemes"),
							"desc" => __("Skills block title", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Skills subtitle", "axiomthemes"),
							"desc" => __("Skills block subtitle - text in the center (only for type=arc)", "axiomthemes"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align skills block", "axiomthemes"),
							"desc" => __("Align skills block to left or right side", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						), 
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => __("Skill", "axiomthemes"),
						"desc" => __("Skills item", "axiomthemes"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => __("Title", "axiomthemes"),
								"desc" => __("Current skills item title", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => __("Value", "axiomthemes"),
								"desc" => __("Current skills level", "axiomthemes"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => __("Color", "axiomthemes"),
								"desc" => __("Current skills item color", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "axiomthemes"),
								"desc" => __("Current skills item background color (only for type=pie)", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => __("Border color", "axiomthemes"),
								"desc" => __("Current skills item border color (only for type=pie)", "axiomthemes"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => __("Counter tyle", "axiomthemes"),
								"desc" => __("Select style for the current skills item (only for type=counter)", "axiomthemes"),
								"value" => 1,
								"min" => 1,
								"max" => 4,
								"type" => "spinner"
							), 
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => __("Slider", "axiomthemes"),
					"desc" => __("Insert slider into your post (page)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => __("Slider engine", "axiomthemes"),
							"desc" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "axiomthemes"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => __("Float slider", "axiomthemes"),
							"desc" => __("Float slider to left or right side", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => __("Custom slides", "axiomthemes"),
							"desc" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						)
						),
						axiomthemes_exists_revslider() || axiomthemes_exists_royalslider() ? array(
						"alias" => array(
							"title" => __("Revolution slider alias or Royal Slider ID", "axiomthemes"),
							"desc" => __("Alias for Revolution slider or Royal slider ID", "axiomthemes"),
							"dependency" => array(
								'engine' => array('revo','royal')
							),
							"divider" => true,
							"value" => "",
							"type" => "text"
						)) : array(), array(
						"cat" => array(
							"title" => __("Swiper: Category list", "axiomthemes"),
							"desc" => __("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => __("Swiper: Number of posts", "axiomthemes"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Swiper: Offset before select posts", "axiomthemes"),
							"desc" => __("Skip posts before select next part.", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Swiper: Post order by", "axiomthemes"),
							"desc" => __("Select desired posts sorting method", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Swiper: Post order", "axiomthemes"),
							"desc" => __("Select desired posts order", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Swiper: Post IDs list", "axiomthemes"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Swiper: Show slider controls", "axiomthemes"),
							"desc" => __("Show arrows inside slider", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => __("Swiper: Show slider pagination", "axiomthemes"),
							"desc" => __("Show bullets for switch slides", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "checklist",
							"options" => array(
								'yes'  => __('Dots', 'axiomthemes'),
								'full' => __('Side Titles', 'axiomthemes'),
								'over' => __('Over Titles', 'axiomthemes'),
								'no'   => __('None', 'axiomthemes')
							)
						),
						"titles" => array(
							"title" => __("Swiper: Show titles section", "axiomthemes"),
							"desc" => __("Show section with post's title and short post's description", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => __('Not show', 'axiomthemes'),
								"slide" => __('Show/Hide info', 'axiomthemes'),
								"fixed" => __('Fixed info', 'axiomthemes')
							)
						),
						"descriptions" => array(
							"title" => __("Swiper: Post descriptions", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => __("Show post's excerpt max length (characters)", "axiomthemes"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => __("Swiper: Post's title as link", "axiomthemes"),
							"desc" => __("Make links from post's titles", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => __("Swiper: Crop images", "axiomthemes"),
							"desc" => __("Crop images in each slide or live it unchanged", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => __("Swiper: Autoheight", "axiomthemes"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Swiper: Slides change interval", "axiomthemes"),
							"desc" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => __("Slide", "axiomthemes"),
						"desc" => __("Slider item", "axiomthemes"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => __("URL (source) for image file", "axiomthemes"),
								"desc" => __("Select or upload image or write URL from other site for the current slide", "axiomthemes"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => __("Social icons", "axiomthemes"),
					"desc" => __("List of social icons (with hovers)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"size" => array(
							"title" => __("Icon's size", "axiomthemes"),
							"desc" => __("Size of the icons", "axiomthemes"),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								"tiny" => __('Tiny', 'axiomthemes'),
								"small" => __('Small', 'axiomthemes'),
								"large" => __('Large', 'axiomthemes')
							)
						), 
						"socials" => array(
							"title" => __("Manual socials list", "axiomthemes"),
							"desc" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => __("Custom socials", "axiomthemes"),
							"desc" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => __("Custom social item", "axiomthemes"),
						"desc" => __("Custom social item: name, profile url and icon url", "axiomthemes"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => __("Social name", "axiomthemes"),
								"desc" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => __("Your profile URL", "axiomthemes"),
								"desc" => __("URL of your profile in specified social network", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => __("URL (source) for icon file", "axiomthemes"),
								"desc" => __("Select or upload image or write URL from other site for the current social icon", "axiomthemes"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => __("Table", "axiomthemes"),
					"desc" => __("Insert a table into post (page). ", "axiomthemes"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => __("Content alignment", "axiomthemes"),
							"desc" => __("Select alignment for each table cell", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => __("Table content", "axiomthemes"),
							"desc" => __("Content, created with any table-generator", "axiomthemes"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => axiomthemes_shortcodes_width(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => __("Tabs", "axiomthemes"),
					"desc" => __("Insert tabs in your page (post)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Tabs style", "axiomthemes"),
							"desc" => __("Select style for tabs items", "axiomthemes"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes')
							),
							"type" => "radio"
						),
						"initial" => array(
							"title" => __("Initially opened tab", "axiomthemes"),
							"desc" => __("Number of initially opened tab", "axiomthemes"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiomthemes"),
							"desc" => __("Use scroller to show tab content (height parameter required)", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => __("Tab", "axiomthemes"),
						"desc" => __("Tab item", "axiomthemes"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Tab title", "axiomthemes"),
								"desc" => __("Current tab title", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Tab content", "axiomthemes"),
								"desc" => __("Current tab content", "axiomthemes"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Team
				"trx_team" => array(
					"title" => __("Team", "axiomthemes"),
					"desc" => __("Insert team in your page (post)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Team style", "axiomthemes"),
							"desc" => __("Select style to display team members", "axiomthemes"),
							"value" => "1",
							"type" => "select",
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes')
							)
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns use to show team members", "axiomthemes"),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"custom" => array(
							"title" => __("Custom", "axiomthemes"),
							"desc" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "axiomthemes"),
							"desc" => __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['team_groups']
						),
						"count" => array(
							"title" => __("Number of posts", "axiomthemes"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiomthemes"),
							"desc" => __("Skip posts before select next part.", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiomthemes"),
							"desc" => __("Select desired posts sorting method", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiomthemes"),
							"desc" => __("Select desired posts order", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiomthemes"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => __("Member", "axiomthemes"),
						"desc" => __("Team member", "axiomthemes"),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => __("Registerd user", "axiomthemes"),
								"desc" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "axiomthemes"),
								"value" => "",
								"type" => "select",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['users']
							),
							"member" => array(
								"title" => __("Team member", "axiomthemes"),
								"desc" => __("Select one of team members (if present) or put name, position, etc. in fields below", "axiomthemes"),
								"value" => "",
								"type" => "select",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['members']
							),
							"link" => array(
								"title" => __("Link", "axiomthemes"),
								"desc" => __("Link on team member's personal page", "axiomthemes"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => __("Name", "axiomthemes"),
								"desc" => __("Team member's name", "axiomthemes"),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => __("Position", "axiomthemes"),
								"desc" => __("Team member's position", "axiomthemes"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "axiomthemes"),
								"desc" => __("Team member's e-mail", "axiomthemes"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "axiomthemes"),
								"desc" => __("Team member's photo (avatar)", "axiomthemes"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => __("Socials", "axiomthemes"),
								"desc" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "axiomthemes"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Description", "axiomthemes"),
								"desc" => __("Team member's short description", "axiomthemes"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => __("Testimonials", "axiomthemes"),
					"desc" => __("Insert testimonials into post (page)", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"controls" => array(
							"title" => __("Show arrows", "axiomthemes"),
							"desc" => __("Show control buttons", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Testimonials change interval", "axiomthemes"),
							"desc" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Alignment of the testimonials block", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => __("Autoheight", "axiomthemes"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => __("Custom", "axiomthemes"),
							"desc" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "axiomthemes"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "axiomthemes"),
							"desc" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['testimonials_groups']
						),
						"count" => array(
							"title" => __("Number of posts", "axiomthemes"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiomthemes"),
							"desc" => __("Skip posts before select next part.", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiomthemes"),
							"desc" => __("Select desired posts sorting method", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiomthemes"),
							"desc" => __("Select desired posts order", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiomthemes"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiomthemes"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiomthemes"),
							"desc" => __("Main background tint: dark or light", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Any background color for this section", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiomthemes"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiomthemes"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiomthemes"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => __("Item", "axiomthemes"),
						"desc" => __("Testimonials item", "axiomthemes"),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => __("Author", "axiomthemes"),
								"desc" => __("Name of the testimonmials author", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => __("Link", "axiomthemes"),
								"desc" => __("Link URL to the testimonmials author page", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "axiomthemes"),
								"desc" => __("E-mail of the testimonmials author (to get gravatar)", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "axiomthemes"),
								"desc" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "axiomthemes"),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => __("Testimonials text", "axiomthemes"),
								"desc" => __("Current testimonials text", "axiomthemes"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Title
				"trx_title" => array(
					"title" => __("Title", "axiomthemes"),
					"desc" => __("Create header tag (1-6 level) with many styles", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Title content", "axiomthemes"),
							"desc" => __("Title content", "axiomthemes"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => __("Title type", "axiomthemes"),
							"desc" => __("Title type (header level)", "axiomthemes"),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => __('Header 1', 'axiomthemes'),
								'2' => __('Header 2', 'axiomthemes'),
								'3' => __('Header 3', 'axiomthemes'),
								'4' => __('Header 4', 'axiomthemes'),
								'5' => __('Header 5', 'axiomthemes'),
								'6' => __('Header 6', 'axiomthemes'),
							)
						),
						"style" => array(
							"title" => __("Title style", "axiomthemes"),
							"desc" => __("Title style", "axiomthemes"),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => __('Regular', 'axiomthemes'),
								'underline' => __('Underline', 'axiomthemes'),
								'divider' => __('Divider', 'axiomthemes'),
								'iconed' => __('With icon (image)', 'axiomthemes')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Title text alignment", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => __("Font_size", "axiomthemes"),
							"desc" => __("Custom font size. If empty - use theme default", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiomthemes"),
							"desc" => __("Custom font weight. If empty or inherit - use theme default", "axiomthemes"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => __('Default', 'axiomthemes'),
								'100' => __('Thin (100)', 'axiomthemes'),
								'300' => __('Light (300)', 'axiomthemes'),
								'400' => __('Normal (400)', 'axiomthemes'),
								'600' => __('Semibold (600)', 'axiomthemes'),
								'700' => __('Bold (700)', 'axiomthemes'),
								'900' => __('Black (900)', 'axiomthemes')
							)
						),
                        "fig_border" => array(
                            "title" => __("Figure botoom border", "axiomthemes"),
                            "desc" => __("Apply a figure botoom border", "axiomthemes"),
                            "value" => "",
                            "type" => "checklist",
                            "options" => array('' => 'None', 'fig_border' => 'Red', 'fig_border_white' => 'White', 'fig_border_blue' => 'Blue' )
                        ),
                        "color" => array(
							"title" => __("Title color", "axiomthemes"),
							"desc" => __("Select color for the title", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => __('Title font icon',  'axiomthemes'),
							"desc" => __("Select font icon for the title from Fontello icons set (if style=iconed)",  'axiomthemes'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => __('or image icon',  'axiomthemes'),
							"desc" => __("Select image icon for the title instead icon above (if style=iconed)",  'axiomthemes'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => __('or URL for image file', "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site (if style=iconed)", "axiomthemes"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => __('Image (picture) size', "axiomthemes"),
							"desc" => __("Select image (picture) size (if style='iconed')", "axiomthemes"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => __('Small', 'axiomthemes'),
								'medium' => __('Medium', 'axiomthemes'),
								'large' => __('Large', 'axiomthemes')
							)
						),
						"position" => array(
							"title" => __('Icon (image) position', "axiomthemes"),
							"desc" => __("Select icon (image) position (if style=iconed)", "axiomthemes"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => __('Top', 'axiomthemes'),
								'left' => __('Left', 'axiomthemes')
							)
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => __("Toggles", "axiomthemes"),
					"desc" => __("Toggles items", "axiomthemes"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Toggles style", "axiomthemes"),
							"desc" => __("Select style for display toggles", "axiomthemes"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiomthemes'),
								2 => __('Style 2', 'axiomthemes')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "axiomthemes"),
							"desc" => __("Display counter before each toggles title", "axiomthemes"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'axiomthemes'),
							"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'axiomthemes'),
							"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'axiomthemes'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
						),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => __("Toggles item", "axiomthemes"),
						"desc" => __("Toggles item", "axiomthemes"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Toggles item title", "axiomthemes"),
								"desc" => __("Title for current toggles item", "axiomthemes"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => __("Open on show", "axiomthemes"),
								"desc" => __("Open current toggles item on show", "axiomthemes"),
								"value" => "no",
								"type" => "switch",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'axiomthemes'),
								"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'axiomthemes'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'axiomthemes'),
								"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'axiomthemes'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOMTHEMES_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Toggles item content", "axiomthemes"),
								"desc" => __("Current toggles item content", "axiomthemes"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
							"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
							"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => __("Tooltip", "axiomthemes"),
					"desc" => __("Create tooltip for selected text", "axiomthemes"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "axiomthemes"),
							"desc" => __("Tooltip title (required)", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Tipped content", "axiomthemes"),
							"desc" => __("Highlighted content with tooltip", "axiomthemes"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),



			
				// Twitter
				"trx_twitter" => array(
					"title" => __("Twitter", "axiomthemes"),
					"desc" => __("Insert twitter feed into post (page)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => __("Twitter Username", "axiomthemes"),
							"desc" => __("Your username in the twitter account. If empty - get it from Theme Options.", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => __("Consumer Key", "axiomthemes"),
							"desc" => __("Consumer Key from the twitter account", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => __("Consumer Secret", "axiomthemes"),
							"desc" => __("Consumer Secret from the twitter account", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => __("Token Key", "axiomthemes"),
							"desc" => __("Token Key from the twitter account", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => __("Token Secret", "axiomthemes"),
							"desc" => __("Token Secret from the twitter account", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => __("Tweets number", "axiomthemes"),
							"desc" => __("Tweets number to show", "axiomthemes"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => __("Show arrows", "axiomthemes"),
							"desc" => __("Show control buttons", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Tweets change interval", "axiomthemes"),
							"desc" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "axiomthemes"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => __("Alignment", "axiomthemes"),
							"desc" => __("Alignment of the tweets block", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => __("Autoheight", "axiomthemes"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiomthemes"),
							"desc" => __("Main background tint: dark or light", "axiomthemes"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiomthemes"),
							"desc" => __("Any background color for this section", "axiomthemes"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiomthemes"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiomthemes"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiomthemes"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiomthemes"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => __("Video", "axiomthemes"),
					"desc" => __("Insert video player", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for video file", "axiomthemes"),
							"desc" => __("Select video from media library or paste URL for video file from other site", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'axiomthemes'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'axiomthemes'),
									'update' => __('Select video file', 'axiomthemes')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => __("Ratio", "axiomthemes"),
							"desc" => __("Ratio of the video", "axiomthemes"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => __("16:9", 'axiomthemes'),
								"4:3" => __("4:3", 'axiomthemes')
							)
						),
						"autoplay" => array(
							"title" => __("Autoplay video", "axiomthemes"),
							"desc" => __("Autoplay video on page load", "axiomthemes"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "axiomthemes"),
							"desc" => __("Select block alignment", "axiomthemes"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => __("Cover image", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for video preview", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => __("Background image", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiomthemes"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "axiomthemes"),
							"desc" => __("Top offset (padding) inside background image to video block (in percent). For example: 3%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "axiomthemes"),
							"desc" => __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "axiomthemes"),
							"desc" => __("Left offset (padding) inside background image to video block (in percent). For example: 20%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "axiomthemes"),
							"desc" => __("Right offset (padding) inside background image to video block (in percent). For example: 12%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => __("Zoom", "axiomthemes"),
					"desc" => __("Insert the image with zoom/lens effect", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => __("Effect", "axiomthemes"),
							"desc" => __("Select effect to display overlapping image", "axiomthemes"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => __('Lens', 'axiomthemes'),
								"zoom" => __('Zoom', 'axiomthemes')
							)
						),
						"url" => array(
							"title" => __("Main image", "axiomthemes"),
							"desc" => __("Select or upload main image", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => __("Overlaping image", "axiomthemes"),
							"desc" => __("Select or upload overlaping image", "axiomthemes"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => __("Float zoom", "axiomthemes"),
							"desc" => __("Float zoom to left or right side", "axiomthemes"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => __("Background image", "axiomthemes"),
							"desc" => __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "axiomthemes"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "axiomthemes"),
							"desc" => __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "axiomthemes"),
							"desc" => __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "axiomthemes"),
							"desc" => __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "axiomthemes"),
							"desc" => __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "axiomthemes"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiomthemes_shortcodes_width(),
						"height" => axiomthemes_shortcodes_height(),
						"top" => $AXIOMTHEMES_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOMTHEMES_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOMTHEMES_GLOBALS['sc_params']['left'],
						"right" => $AXIOMTHEMES_GLOBALS['sc_params']['right'],
						"id" => $AXIOMTHEMES_GLOBALS['sc_params']['id'],
						"class" => $AXIOMTHEMES_GLOBALS['sc_params']['class'],
						"animation" => $AXIOMTHEMES_GLOBALS['sc_params']['animation'],
						"css" => $AXIOMTHEMES_GLOBALS['sc_params']['css']
					)
				)
			);
	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (axiomthemes_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$AXIOMTHEMES_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => __("Woocommerce: Cart", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show Cart page", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$AXIOMTHEMES_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => __("Woocommerce: Checkout", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show Checkout page", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$AXIOMTHEMES_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => __("Woocommerce: My Account", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show My Account page", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$AXIOMTHEMES_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => __("Woocommerce: Order Tracking", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show Order Tracking page", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$AXIOMTHEMES_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => __("Woocommerce: Shop Messages", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show shop messages", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$AXIOMTHEMES_GLOBALS['shortcodes']["product_page"] = array(
					"title" => __("Woocommerce: Product Page", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: display single product page", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "axiomthemes"),
							"desc" => __("SKU code of displayed product", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "axiomthemes"),
							"desc" => __("ID of displayed product", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => __("Post type", "axiomthemes"),
							"desc" => __("Post type for the WP query (leave 'product')", "axiomthemes"),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => __("Post status", "axiomthemes"),
							"desc" => __("Display posts only with this status", "axiomthemes"),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => __('Publish', 'axiomthemes'),
								"protected" => __('Protected', 'axiomthemes'),
								"private" => __('Private', 'axiomthemes'),
								"pending" => __('Pending', 'axiomthemes'),
								"draft" => __('Draft', 'axiomthemes')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$AXIOMTHEMES_GLOBALS['shortcodes']["product"] = array(
					"title" => __("Woocommerce: Product", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: display one product", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "axiomthemes"),
							"desc" => __("SKU code of displayed product", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "axiomthemes"),
							"desc" => __("ID of displayed product", "axiomthemes"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => __("Woocommerce: Best Selling Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show best selling products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => __("Woocommerce: Recent Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show recent products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["related_products"] = array(
					"title" => __("Woocommerce: Related Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show related products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Featured Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show featured products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Top Rated Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show top rated products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Sale Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: list products on sale", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$AXIOMTHEMES_GLOBALS['shortcodes']["product_category"] = array(
					"title" => __("Woocommerce: Products from category", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: list products in specified category(-ies)", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => __("Categories", "axiomthemes"),
							"desc" => __("Comma separated category slugs", "axiomthemes"),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => __("Operator", "axiomthemes"),
							"desc" => __("Categories operator", "axiomthemes"),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => __('IN', 'axiomthemes'),
								"NOT IN" => __('NOT IN', 'axiomthemes'),
								"AND" => __('AND', 'axiomthemes')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$AXIOMTHEMES_GLOBALS['shortcodes']["products"] = array(
					"title" => __("Woocommerce: Products", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: list all products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => __("SKUs", "axiomthemes"),
							"desc" => __("Comma separated SKU codes of products", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "axiomthemes"),
							"desc" => __("Comma separated ID of products", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$AXIOMTHEMES_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => __("Woocommerce: Products by Attribute", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show products with specified attribute", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many products showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for products output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => __("Attribute", "axiomthemes"),
							"desc" => __("Attribute name", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => __("Filter", "axiomthemes"),
							"desc" => __("Attribute value", "axiomthemes"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$AXIOMTHEMES_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => __("Woocommerce: Product Categories", "axiomthemes"),
					"desc" => __("WooCommerce shortcode: show categories with products", "axiomthemes"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => __("Number", "axiomthemes"),
							"desc" => __("How many categories showed", "axiomthemes"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiomthemes"),
							"desc" => __("How many columns per row use for categories output", "axiomthemes"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiomthemes'),
								"title" => __('Title', 'axiomthemes')
							)
						),
						"order" => array(
							"title" => __("Order", "axiomthemes"),
							"desc" => __("Sorting order for products output", "axiomthemes"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => __("Parent", "axiomthemes"),
							"desc" => __("Parent category slug", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "axiomthemes"),
							"desc" => __("Comma separated ID of products", "axiomthemes"),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => __("Hide empty", "axiomthemes"),
							"desc" => __("Hide empty categories", "axiomthemes"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOMTHEMES_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('axiomthemes_action_shortcodes_list');

		}
	}
}
?>