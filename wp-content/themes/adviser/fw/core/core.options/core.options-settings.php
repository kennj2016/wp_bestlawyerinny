<?php

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_options_settings_theme_setup2' ) ) {
	add_action( 'axiomthemes_action_after_init_theme', 'axiomthemes_options_settings_theme_setup2', 1 );
	function axiomthemes_options_settings_theme_setup2() {
		if (axiomthemes_options_is_used()) {
			global $AXIOMTHEMES_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			foreach ($AXIOMTHEMES_GLOBALS['options'] as $k=>$v) {
				if (isset($v['options']) && is_array($v['options'])) {
					foreach ($v['options'] as $k1=>$v1) {
						if (axiomthemes_substr($k1, 0, 13) == '$axiomthemes_' || axiomthemes_substr($v1, 0, 13) == '$axiomthemes_') {
							$list_func = axiomthemes_substr(axiomthemes_substr($k1, 0, 13) == '$axiomthemes_' ? $k1 : $v1, 1);
							unset($AXIOMTHEMES_GLOBALS['options'][$k]['options'][$k1]);
							if (isset($lists[$list_func]))
								$AXIOMTHEMES_GLOBALS['options'][$k]['options'] = axiomthemes_array_merge($AXIOMTHEMES_GLOBALS['options'][$k]['options'], $lists[$list_func]);
							else {
								if (function_exists($list_func)) {
									$AXIOMTHEMES_GLOBALS['options'][$k]['options'] = $lists[$list_func] = axiomthemes_array_merge($AXIOMTHEMES_GLOBALS['options'][$k]['options'], $list_func == 'axiomthemes_get_list_menus' ? $list_func(true) : $list_func());
							   	} else
							   		echo sprintf(__('Wrong function name %s in the theme options array', 'axiomthemes'), $list_func);
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'axiomthemes_options_reset' ) ) {
	function axiomthemes_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(axiomthemes_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'axiomthemes_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "axiomthemes_options%"');
				// Add Templates Options
				if (file_exists(axiomthemes_get_file_dir('demo/templates_options.txt'))) {
					$theme_options_txt = axiomthemes_fgc(axiomthemes_get_file_dir('demo/templates_options.txt'));
					$data = unserialize( base64_decode( $theme_options_txt) );
					// Replace upload url in options
					foreach ($data as $k=>$v) {
						foreach ($v as $k1=>$v1) {
							$v[$k1] = axiomthemes_replace_uploads_url(axiomthemes_replace_uploads_url($v1, 'uploads'), 'imports');
						}
						add_option( $k, $v, '', 'yes' );
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}

// Prepare default Theme Options
if ( !function_exists( 'axiomthemes_options_settings_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_options_settings_theme_setup', 2 );	// Priority 1 for add axiomthemes_filter handlers
	function axiomthemes_options_settings_theme_setup() {
		global $AXIOMTHEMES_GLOBALS;
		
		// Remove 'false' to clear all saved Theme Options on next run.
		// Attention! Use this way only on new theme installation, not in updates!
		axiomthemes_options_reset();
		
		// Prepare arrays 
		$AXIOMTHEMES_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$axiomthemes_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$axiomthemes_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$axiomthemes_get_list_socials' => ''),
			'list_icons' 		=> array('$axiomthemes_get_list_icons' => ''),
			'list_posts_types' 	=> array('$axiomthemes_get_list_posts_types' => ''),
			'list_categories' 	=> array('$axiomthemes_get_list_categories' => ''),
			'list_menus'		=> array('$axiomthemes_get_list_menus' => ''),
			'list_sidebars'		=> array('$axiomthemes_get_list_sidebars' => ''),
			'list_positions' 	=> array('$axiomthemes_get_list_sidebars_positions' => ''),
			'list_tints'	 	=> array('$axiomthemes_get_list_bg_tints' => ''),
			'list_sidebar_styles' => array('$axiomthemes_get_list_sidebar_styles' => ''),
			'list_skins'		=> array('$axiomthemes_get_list_skins' => ''),
			'list_color_schemes'=> array('$axiomthemes_get_list_color_schemes' => ''),
			'list_body_styles'	=> array('$axiomthemes_get_list_body_styles' => ''),
			'list_blog_styles'	=> array('$axiomthemes_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$axiomthemes_get_list_templates_single' => ''),
			'list_article_styles'=> array('$axiomthemes_get_list_article_styles' => ''),
			'list_animations_in' => array('$axiomthemes_get_list_animations_in' => ''),
			'list_animations_out'=> array('$axiomthemes_get_list_animations_out' => ''),
			'list_filters'		=> array('$axiomthemes_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$axiomthemes_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$axiomthemes_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$axiomthemes_get_list_sliders' => ''),
			'list_popups' 		=> array('$axiomthemes_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$axiomthemes_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$axiomthemes_get_list_yesno' => ''),
			'list_on_off' 		=> array('$axiomthemes_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$axiomthemes_get_list_showhide' => ''),
			'list_sorting' 		=> array('$axiomthemes_get_list_sortings' => ''),
			'list_ordering' 	=> array('$axiomthemes_get_list_orderings' => ''),
			'list_locations' 	=> array('$axiomthemes_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$AXIOMTHEMES_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => __('Customization', 'axiomthemes'),
					"start" => "partitions",
					"override" => "category,courses_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),


		// Customization -> General
		//-------------------------------------------------
		
		'customization_general' => array(
					"title" => __('General', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"icon" => 'iconadmin-cog',
					"start" => "customization_tabs",
					"type" => "tab"
					),

		'info_custom_1' => array(
					"title" => __('Theme customization general parameters', 'axiomthemes'),
					"desc" => __('Select main theme skin, customize colors and enable responsive layouts for the small screens', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"
					),

		'theme_skin' => array(
					"title" => __('Select theme skin', 'axiomthemes'),
					"desc" => __('Select skin for the theme decoration', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "adviser",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"icon" => array(
					"title" => __('Select icon', 'axiomthemes'),
					"desc" => __('Select icon for output before post/category name in some layouts', 'axiomthemes'),
					"override" => "category,courses_group,post",
					"std" => "",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"color_scheme" => array(
					"title" => __('Color scheme', 'axiomthemes'),
					"desc" => __('Select predefined color scheme. Or set separate colors in fields below', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"link_color" => array(
					"title" => __('Links color', 'axiomthemes'),
					"desc" => __('Links color. Also used as background color for the page header area and some other elements', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"link_dark" => array(
					"title" => __('Links dark color', 'axiomthemes'),
					"desc" => __('Used as background color for the buttons, hover states and some other elements', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"menu_color" => array(
					"title" => __('Main menu color', 'axiomthemes'),
					"desc" => __('Used as background color for the active menu item, calendar item, tabs and some other elements', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"menu_dark" => array(
					"title" => __('Main menu dark color', 'axiomthemes'),
					"desc" => __('Used as text color for the menu items (in the Light style), as background color for the selected menu item, etc.', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"user_color" => array(
					"title" => __('User menu color', 'axiomthemes'),
					"desc" => __('Used as background color for the user menu items and some other elements', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"user_dark" => array(
					"title" => __('User menu dark color', 'axiomthemes'),
					"desc" => __('Used as background color for the selected user menu item, etc.', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),


		'show_theme_customizer' => array(
					"title" => __('Show Theme customizer', 'axiomthemes'),
					"desc" => __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => __('Theme customizer panel demo time', 'axiomthemes'),
					"desc" => __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'axiomthemes'),
					"divider" => false,
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => __('Extended CSS animations', 'axiomthemes'),
					"desc" => __('Do you want use extended animations effects on your site?', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => __('Remember visitor\'s settings', 'axiomthemes'),
					"desc" => __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => __('Responsive Layouts', 'axiomthemes'),
					"desc" => __('Do you want use responsive layouts on small screen or still use main layout?', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'info_custom_2' => array(
					"title" => __('Additional CSS and HTML/JS code', 'axiomthemes'),
					"desc" => __('Put here your custom CSS and JS code', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"
					),
		
		'custom_css' => array(
					"title" => __('Your CSS code',  'axiomthemes'),
					"desc" => __('Put here your css code to correct main theme styles',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_code' => array(
					"title" => __('Your HTML/JS code',  'axiomthemes'),
					"desc" => __('Put here your invisible html/js code: Google analitics, counters, etc',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => __('Body style', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"icon" => 'iconadmin-picture',
					"type" => "tab"
					),
		
		'info_custom_3' => array(
					"title" => __('Body parameters', 'axiomthemes'),
					"desc" => __('Background color, pattern and image used only for fixed body style.', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"
					),
					
		'body_style' => array(
					"title" => __('Body style', 'axiomthemes'),
					//"desc" => __('Select body style:<br><b>boxed</b> - if you want use background color and/or image,<br><b>fullboxed</b> - page is boxed without vertical paddings,<br><b>wide</b> - page fill whole window with centered content,<br><b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings),<br><b>fullscreen</b> - page content fill whole window without any paddings', 'axiomthemes'),
					"desc" => __('Select body style:<br><b>boxed</b> - if you want use background color and/or image,<br><b>fullboxed</b> - page is boxed without vertical paddings', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "wide",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_filled' => array(
					"title" => __('Fill body', 'axiomthemes'),
					"desc" => __('Fill the body background with the solid color (white or grey) or leave it transparend to show background image (or video)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'load_bg_image' => array(
					"title" => __('Load background image', 'axiomthemes'),
					"desc" => __('Always load background images or only for boxed body style', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"options" => array(
						'boxed' => __('Boxed', 'axiomthemes'),
						'always' => __('Always', 'axiomthemes')
					),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => __('Background color',  'axiomthemes'),
					"desc" => __('Body background color',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "#bfbfbf",
					"type" => "color"
					),
		
		'bg_pattern' => array(
					"title" => __('Background predefined pattern',  'axiomthemes'),
					"desc" => __('Select theme background pattern (first case - without pattern)',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"options" => array(
						0 => axiomthemes_get_file_url('/images/spacer.png'),
						1 => axiomthemes_get_file_url('/images/bg/pattern_1.png'),
						2 => axiomthemes_get_file_url('/images/bg/pattern_2.png'),
						3 => axiomthemes_get_file_url('/images/bg/pattern_3.png'),
						4 => axiomthemes_get_file_url('/images/bg/pattern_4.png'),
						5 => axiomthemes_get_file_url('/images/bg/pattern_5.png'),
						6 => axiomthemes_get_file_url('/images/bg/pattern_6.png'),
						7 => axiomthemes_get_file_url('/images/bg/pattern_7.png'),
						8 => axiomthemes_get_file_url('/images/bg/pattern_8.png'),
						9 => axiomthemes_get_file_url('/images/bg/pattern_9.png')
					),
					"style" => "list",
					"type" => "images"
					),

		'bg_custom_pattern' => array(
					"title" => __('Background custom pattern',  'axiomthemes'),
					"desc" => __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'bg_image' => array(
					"title" => __('Background predefined image',  'axiomthemes'),
					"desc" => __('Select theme background image (first case - without image)',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"options" => array(
						0 => axiomthemes_get_file_url('/images/spacer.png'),
						1 => axiomthemes_get_file_url('/images/bg/image_1_thumb.jpg'),
						2 => axiomthemes_get_file_url('/images/bg/image_2_thumb.jpg'),
						3 => axiomthemes_get_file_url('/images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),

		'bg_custom_image' => array(
					"title" => __('Background custom image',  'axiomthemes'),
					"desc" => __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'bg_custom_image_position' => array( 
					"title" => __('Background custom image position',  'axiomthemes'),
					"desc" => __('Select custom image position',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "left_top",
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),

		'show_video_bg' => array(
					"title" => __('Show video background',  'axiomthemes'),
					"desc" => __("Show video on the site background (only for Fullscreen body style)", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => __('Youtube code for video bg',  'axiomthemes'),
					"desc" => __("Youtube code of video", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => __('Local video for video bg',  'axiomthemes'),
					"desc" => __("URL to video-file (uploaded on your site)", 'axiomthemes'),
					"readonly" =>false,
					"override" => "category,courses_group,post,page",
					"before" => array(	'title' => __('Choose video', 'axiomthemes'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => __( 'Choose Video', 'axiomthemes'),
															'update' => __( 'Select Video', 'axiomthemes')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => __('Use overlay for video bg', 'axiomthemes'),
					"desc" => __('Use overlay texture for the video background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		// Customization -> Logo
		//-------------------------------------------------
		
		'customization_logo' => array(
					"title" => __('Logo', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"icon" => 'iconadmin-heart',
					"type" => "tab"
					),
		
		'info_custom_4' => array(
					"title" => __('Main logo', 'axiomthemes'),
					"desc" => __('Select or upload logos for the site\'s header and select it position', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => __('Favicon', 'axiomthemes'),
					"desc" => __('Upload a 16px x 16px image that will represent your website\'s favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (www.favicon.cc)</em>', 'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_dark' => array(
					"title" => __('Logo image (dark header)', 'axiomthemes'),
					"desc" => __('Main logo image for the dark header', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_light' => array(
					"title" => __('Logo image (light header)', 'axiomthemes'),
					"desc" => __('Main logo image for the light header', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => __('Logo image (fixed header)', 'axiomthemes'),
					"desc" => __('Logo image for the header (if menu is fixed after the page is scrolled)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),
		
		'logo_from_skin' => array(
					"title" => __('Logo from skin',  'axiomthemes'),
					"desc" => __("Use logo images from current skin folder if not filled out fields above", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'logo_text' => array(
					"title" => __('Logo text', 'axiomthemes'),
					"desc" => __('Logo text - display it after logo image', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => __('Logo slogan', 'axiomthemes'),
					"desc" => __('Logo slogan - display it under logo image (instead the site slogan)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => __('Logo height', 'axiomthemes'),
					"desc" => __('Height for the logo in the header area', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => __('Logo top offset', 'axiomthemes'),
					"desc" => __('Top offset for the logo in the header area', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),

//		'logo_align' => array(
//					"title" => __('Logo alignment', 'axiomthemes'),
//					"desc" => __('Logo alignment (only if logo above menu)', 'axiomthemes'),
//					"override" => "category,courses_group,post,page",
//					"std" => "left",
//					"options" =>  array("left"=>__("Left", 'axiomthemes'), "center"=>__("Center", 'axiomthemes'), "right"=>__("Right", 'axiomthemes')),
//					"dir" => "horizontal",
//					"type" => "checklist"
//					),

		'iinfo_custom_5' => array(
					"title" => __('Logo for footer', 'axiomthemes'),
					"desc" => __('Select or upload logos for the site\'s footer and set it height', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"
					),

		'logo_footer' => array(
					"title" => __('Logo image for footer', 'axiomthemes'),
					"desc" => __('Logo image for the footer', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => __('Logo height', 'axiomthemes'),
					"desc" => __('Height for the logo in the footer area (in contacts)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		// Customization -> Menus
		//-------------------------------------------------
		
		"customization_menus" => array(
					"title" => __('Menus', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"icon" => 'iconadmin-menu',
					"type" => "tab"),
		
		"info_custom_6" => array(
					"title" => __('Top panel', 'axiomthemes'),
					"desc" => __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"),
		
		"top_panel_position" => array( 
					"title" => __('Top panel position', 'axiomthemes'),
					"desc" => __('Select position for the top panel with logo and main menu', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "above",
					"options" => array(
						'hide'  => __('Hide', 'axiomthemes'),
						'above' => __('Above slider', 'axiomthemes'),
						'below' => __('Below slider', 'axiomthemes'),
						'over'  => __('Over slider', 'axiomthemes')
					),
					"type" => "checklist"),
		
		"top_panel_style" => array( 
					"title" => __('Top panel style', 'axiomthemes'),
					"desc" => __('Select background style for the top panel with logo and main menu', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "light",
					"options" => array(
						//'dark' => __('Dark', 'axiomthemes'),
						'light' => __('Light', 'axiomthemes')
					),
					"type" => "checklist"),
		
		"top_panel_opacity" => array( 
					"title" => __('Top panel opacity', 'axiomthemes'),
					"desc" => __('Select background opacity for the top panel with logo and main menu', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "solid",
					"options" => array(
						'solid' => __('Solid', 'axiomthemes'),
						'transparent' => __('Transparent', 'axiomthemes')
					),
					"type" => "checklist"),
		
		'top_panel_bg_color' => array(
					"title" => __('Top panel bg color',  'axiomthemes'),
					"desc" => __('Background color for the top panel',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"
					),
		
		"top_panel_bg_image" => array( 
					"title" => __('Top panel bg image', 'axiomthemes'),
					"desc" => __('Upload top panel background image', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "media"),
		
		
		"info_custom_7" => array( 
					"title" => __('Main menu style and position', 'axiomthemes'),
					"desc" => __('Select the Main menu style and position', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => __('Select main menu',  'axiomthemes'),
					"desc" => __('Select main menu for the current page',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "default",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_position" => array( 
					"title" => __('Main menu position', 'axiomthemes'),
					"desc" => __('Attach main menu to top of window then page scroll down', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "fixed",
					"options" => array("fixed"=>__("Fix menu position", 'axiomthemes'), "none"=>__("Don't fix menu position", 'axiomthemes')),
					"dir" => "vertical",
					"type" => "radio"),
		
		"menu_align" => array( 
					"title" => __('Main menu alignment', 'axiomthemes'),
					"desc" => __('Main menu alignment', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "center",
					"options" => array(
						//"left"   => __("Left (under logo)", 'axiomthemes'),
						"center" => __("Center (under logo)", 'axiomthemes'),
						//"right"	 => __("Right (at same line with logo)", 'axiomthemes')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => __('Main menu slider', 'axiomthemes'),
					"desc" => __('Use slider background for main menu items', 'axiomthemes'),
					"std" => "yes",
					"type" => "switch",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => __('Submenu show animation', 'axiomthemes'),
					"desc" => __('Select animation to show submenu ', 'axiomthemes'),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => __('Submenu hide animation', 'axiomthemes'),
					"desc" => __('Select animation to hide submenu ', 'axiomthemes'),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => __('Main menu relayout', 'axiomthemes'),
					"desc" => __('Allow relayout main menu if window width less then this value', 'axiomthemes'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => __('Main menu responsive', 'axiomthemes'),
					"desc" => __('Allow responsive version for the main menu if window width less then this value', 'axiomthemes'),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => __('Submenu width', 'axiomthemes'),
					"desc" => __('Width for dropdown menus in main menu', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_custom_8" => array(
					"title" => __("User's menu area components", 'axiomthemes'),
					"desc" => __("Select parts for the user's menu area", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),

		"show_contact_info" => array(
					"title" => __('Show contact info', 'axiomthemes'),
					"desc" => __("Show the contact details for the owner of the site at the top right corner of the page", 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_menu_user" => array(
					"title" => __('Show user menu area', 'axiomthemes'),
					"desc" => __('Show user menu area on top of page', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"menu_user" => array(
					"title" => __('Select user menu',  'axiomthemes'),
					"desc" => __('Select user menu for the current page',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "default",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"show_languages" => array(
					"title" => __('Show language selector', 'axiomthemes'),
					"desc" => __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => __('Show Login/Logout buttons', 'axiomthemes'),
					"desc" => __('Show Login and Logout buttons in the user menu area', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => __('Show bookmarks', 'axiomthemes'),
					"desc" => __('Show bookmarks selector in the user menu', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
		"info_custom_9" => array( 
					"title" => __("Table of Contents (TOC)", 'axiomthemes'),
					"desc" => __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => __('TOC position', 'axiomthemes'),
					"desc" => __('Show TOC for the current page', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => __('Hide', 'axiomthemes'),
						'fixed' => __('Fixed', 'axiomthemes'),
						'float' => __('Float', 'axiomthemes')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => __('Add "Home" into TOC', 'axiomthemes'),
					"desc" => __('Automatically add "Home" item into table of contents - return to home page of the site', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => __('Add "To Top" into TOC', 'axiomthemes'),
					"desc" => __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => __('Sidebars', 'axiomthemes'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,courses_group,post,page",
					"type" => "tab"),
		
		"info_custom_10" => array( 
					"title" => __('Custom sidebars', 'axiomthemes'),
					"desc" => __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'axiomthemes'),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => __('Custom sidebars',  'axiomthemes'),
					"desc" => __('Manage custom sidebars. You can use it with each category (page, post) independently',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_custom_11" => array(
					"title" => __('Sidebars settings', 'axiomthemes'),
					"desc" => __('Show / Hide and Select sidebar in each location', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => __('Show main sidebar',  'axiomthemes'),
					"desc" => __('Select style for the main sidebar or hide it',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "light",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		'sidebar_main_position' => array( 
					"title" => __('Main sidebar position',  'axiomthemes'),
					"desc" => __('Select main sidebar position on blog page',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "right",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_positions'],
					"size" => "medium",
					"type" => "switch"),
		
		"sidebar_main" => array( 
					"title" => __('Select main sidebar',  'axiomthemes'),
					"desc" => __('Select main sidebar for the blog page',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "sidebar_main",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"show_sidebar_footer" => array(
					"title" => __('Show footer sidebar', 'axiomthemes'),
					"desc" => __('Select style for the footer sidebar or hide it', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "light",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => __('Select footer sidebar',  'axiomthemes'),
					"desc" => __('Select footer sidebar for the blog page',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "sidebar_footer",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),


		"sidebar_footer_columns" => array( 
					"title" => __('Footer sidebar columns',  'axiomthemes'),
					"desc" => __('Select columns number for the footer sidebar',  'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

            "show_sidebar_footer2" => array(
                "title" => __('Show additional footer sidebar', 'axiomthemes'),
                "desc" => __('Select style for the additional footer sidebar or hide it', 'axiomthemes'),
                "override" => "category,courses_group,post,page",
                "std" => "light",
                "options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebar_styles'],
                "dir" => "horizontal",
                "type" => "checklist"),

            "sidebar_footer2" => array(
                "title" => __('Select additional footer sidebar',  'axiomthemes'),
                "desc" => __('Select additional footer sidebar for the blog page',  'axiomthemes'),
                "override" => "category,courses_group,post,page",
                "divider" => false,
                "std" => "sidebar_footer2",
                "options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sidebars'],
                "type" => "select"),

            "sidebar_footer2_columns" => array(
                "title" => __('Footer additional sidebar columns',  'axiomthemes'),
                "desc" => __('Select columns number for the additional footer sidebar',  'axiomthemes'),
                "override" => "category,courses_group,post,page",
                "divider" => false,
                "std" => 3,
                "min" => 1,
                "max" => 6,
                "type" => "spinner"),
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => __('Slider', 'axiomthemes'),
					"icon" => "iconadmin-picture",
					"override" => "category,courses_group,page",
					"type" => "tab"),
		
		"info_custom_13" => array(
					"title" => __('Main slider parameters', 'axiomthemes'),
					"desc" => __('Select parameters for main slider (you can override it in each category and page)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => __('Show Slider', 'axiomthemes'),
					"desc" => __('Do you want to show slider on each page (post)', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => __('Slider display', 'axiomthemes'),
					"desc" => __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "none",
					"options" => array(
						"boxed"=>__("Boxed", 'axiomthemes')
//						"fullwide"=>__("Fullwide", 'axiomthemes'),
//						"fullscreen"=>__("Fullscreen", 'axiomthemes')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => __("Height (in pixels)", 'axiomthemes'),
					"desc" => __("Slider height (in pixels) - only if slider display with fixed height.", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => __('Slider engine', 'axiomthemes'),
					"desc" => __('What engine use to show slider?', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "flex",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => __('Layer Slider: Alias (for Revolution) or ID (for Royal)',  'axiomthemes'),
					"desc" => __("Revolution Slider alias or Royal Slider ID (see in slider settings on plugin page)", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "",
					"type" => "text"),
		
		"slider_category" => array(
					"title" => __('Posts Slider: Category to show', 'axiomthemes'),
					"desc" => __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "",
					"options" => axiomthemes_array_merge(array(0 => __('- Select category -', 'axiomthemes')), $AXIOMTHEMES_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => __('Posts Slider: Number posts or comma separated posts list',  'axiomthemes'),
					"desc" => __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "5",
					"type" => "text"),
		"slider_orderby" => array(
					"title" => __("Posts Slider: Posts order by",  'axiomthemes'),
					"desc" => __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "date",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => __("Posts Slider: Posts order", 'axiomthemes'),
					"desc" => __('Select the desired ordering method for posts', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "desc",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => __("Posts Slider: Slide change interval", 'axiomthemes'),
					"desc" => __("Interval (in ms) for slides change in slider", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => __("Posts Slider: Pagination", 'axiomthemes'),
					"desc" => __("Choose pagination style for the slider", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "no",
					"options" => array(
						'no'   => __('None', 'axiomthemes'),
						'yes'  => __('Dots', 'axiomthemes'),
						'over' => __('Titles', 'axiomthemes')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => __("Posts Slider: Show infobox", 'axiomthemes'),
					"desc" => __("Do you want to show post's title, reviews rating and description on slides in slider", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "slide",
					"options" => array(
						'no'    => __('None',  'axiomthemes'),
						'slide' => __('Slide', 'axiomthemes'),
						'fixed' => __('Fixed', 'axiomthemes')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => __("Posts Slider: Show post's category", 'axiomthemes'),
					"desc" => __("Do you want to show post's category on slides in slider", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => __("Posts Slider: Show post's reviews rating", 'axiomthemes'),
					"desc" => __("Do you want to show post's reviews rating on slides in slider", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => __("Posts Slider: Show post's descriptions", 'axiomthemes'),
					"desc" => __("How many characters show in the post's description in slider. 0 - no descriptions", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		// Customization -> Header & Footer
		//-------------------------------------------------
		
		'customization_header_footer' => array(
					"title" => __("Header &amp; Footer", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => __("Header settings", 'axiomthemes'),
					"desc" => __("Select components of the page header, set style and put the content for the user's header area", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),

        "disclaimer" => array(
                    "title" => __('Disclaimer in top',  'axiomthemes'),
                    "desc" => __("Enter disclaimer in top", 'axiomthemes'),
                    "std" => "",
                    "type" => "text"),

        "flower_title" => array(
                    "title" => __('Shedule title',  'axiomthemes'),
                    "desc" => __("Shedule title text", 'axiomthemes'),
                    "std" => "",
                    "type" => "text"),

        "text_under_flower_title" => array(
                    "title" => __('Text under shedule title',  'axiomthemes'),
                    "desc" => __("Text under shedule title", 'axiomthemes'),
                    "std" => "",
                    "type" => "text"),

        "top_panel_block_email" => array(
                    "title" => __('Top panel email',  'axiomthemes'),
                    "desc" => __("Email in top panel block", 'axiomthemes'),
                    "std" => "",
                    "divider" => false,
                    "type" => "text"),

		"show_user_header" => array(
					"title" => __("Show user's header", 'axiomthemes'),
					"desc" => __("Show custom user's header", 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page,post",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_header_content" => array(
					"title" => __("User's header content", 'axiomthemes'),
					"desc" => __('Put header html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),
		
		"show_page_top" => array(
					"title" => __('Show Top of page section', 'axiomthemes'),
					"desc" => __('Show top section with post/page/category title and breadcrumbs', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_page_title" => array(
					"title" => __('Show Page title', 'axiomthemes'),
					"desc" => __('Show post/page/category title', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => __('Show Breadcrumbs', 'axiomthemes'),
					"desc" => __('Show path to current category (post, page)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => __('Breadcrumbs max nesting', 'axiomthemes'),
					"desc" => __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'axiomthemes'),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),
		
		
		
		
		"info_footer_2" => array(
					"title" => __("Footer settings", 'axiomthemes'),
					"desc" => __("Select components of the footer, set style and put the content for the user's footer area", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),
		
		"show_user_footer" => array(
					"title" => __("Show user's footer", 'axiomthemes'),
					"desc" => __("Show custom user's footer", 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page,post",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_footer_content" => array(
					"title" => __("User's footer content", 'axiomthemes'),
					"desc" => __('Put footer html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),
		
		"show_contacts_in_footer" => array(
					"title" => __('Show Contacts in footer', 'axiomthemes'),
					"desc" => __('Show contact information area in footer: site logo, contact info and large social icons', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "dark",
					"options" => array(
						'hide' 	=> __('Hide', 'axiomthemes'),
						'light'	=> __('Light', 'axiomthemes'),
						'dark'	=> __('Dark', 'axiomthemes')
					),
					"dir" => "horizontal",
					"type" => "checklist"),

		"show_copyright_in_footer" => array(
					"title" => __('Show Copyright area in footer', 'axiomthemes'),
					"desc" => __('Show area with copyright information and small social icons in footer', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"footer_copyright" => array(
					"title" => __('Footer copyright text',  'axiomthemes'),
					"desc" => __("Copyright text to show in footer area (bottom of site)", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "Axiomthemes &copy; 2017 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),
		
		
		"info_footer_3" => array(
					"title" => __('Testimonials in Footer', 'axiomthemes'),
					"desc" => __('Select parameters for Testimonials in the Footer (you can override it in each category and page)', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => __('Show Testimonials in footer', 'axiomthemes'),
					"desc" => __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_tints'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => __('Testimonials count', 'axiomthemes'),
					"desc" => __('Number testimonials to show', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),

		"testimonials_bg_image" => array( 
					"title" => __('Testimonials bg image', 'axiomthemes'),
					"desc" => __('Select image or put image URL from other site to use it as testimonials block background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"readonly" => false,
					"std" => "",
					"type" => "media"),

		"testimonials_bg_color" => array( 
					"title" => __('Testimonials bg color', 'axiomthemes'),
					"desc" => __('Select color to use it as testimonials block background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"testimonials_bg_overlay" => array( 
					"title" => __('Testimonials bg overlay', 'axiomthemes'),
					"desc" => __('Select background color opacity to create overlay effect on background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "spinner"),
		
		
		"info_footer_4" => array(
					"title" => __('Twitter in Footer', 'axiomthemes'),
					"desc" => __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => __('Show Twitter in footer', 'axiomthemes'),
					"desc" => __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_tints'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => __('Twitter count', 'axiomthemes'),
					"desc" => __('Number twitter to show', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),

		"twitter_bg_image" => array( 
					"title" => __('Twitter bg image', 'axiomthemes'),
					"desc" => __('Select image or put image URL from other site to use it as Twitter block background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "media"),

		"twitter_bg_color" => array( 
					"title" => __('Twitter bg color', 'axiomthemes'),
					"desc" => __('Select color to use it as Twitter block background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "",
					"type" => "color"),

		"twitter_bg_overlay" => array( 
					"title" => __('Twitter bg overlay', 'axiomthemes'),
					"desc" => __('Select background color opacity to create overlay effect on background', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "spinner"),


		"info_footer_5" => array(
					"title" => __('Google map parameters', 'axiomthemes'),
					"desc" => __('Select parameters for Google map (you can override it in each category and page)', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => __('Show Google Map', 'axiomthemes'),
					"desc" => __('Do you want to show Google map on each page (post)', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page,post",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => __("Map height", 'axiomthemes'),
					"desc" => __("Map height (default - in pixels, allows any CSS units of measure)", 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => __('Address to show on map',  'axiomthemes'),
					"desc" => __("Enter address to show on map center", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => __('Latitude and Longtitude to show on map',  'axiomthemes'),
					"desc" => __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => __('Google map initial zoom',  'axiomthemes'),
					"desc" => __("Enter desired initial zoom for Google map", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => __('Google map style',  'axiomthemes'),
					"desc" => __("Select style to show Google map", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => 'style1',
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => __('Google map marker',  'axiomthemes'),
					"desc" => __("Select or upload png-image with Google map marker", 'axiomthemes'),
					"std" => '',
					"type" => "media"),
		
		
		
		
		// Customization -> Media
		//-------------------------------------------------
		
		'customization_media' => array(
					"title" => __('Media', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"icon" => 'iconadmin-picture',
					"type" => "tab"),
		
		"info_media_1" => array(
					"title" => __('Retina ready', 'axiomthemes'),
					"desc" => __("Additional parameters for the Retina displays", 'axiomthemes'),
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => __('Image dimensions', 'axiomthemes'),
					"desc" => __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'axiomthemes'),
					"divider" => false,
					"std" => "1",
					"size" => "medium",
					"options" => array("1"=>__("Original", 'axiomthemes'), "2"=>__("Retina", 'axiomthemes')),
					"type" => "switch"),
		
		"info_media_2" => array(
					"title" => __('Media Substitution parameters', 'axiomthemes'),
					"desc" => __("Set up the media substitution parameters and slider's options", 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"type" => "info"),
		
		"substitute_gallery" => array(
					"title" => __('Substitute standard Wordpress gallery', 'axiomthemes'),
					"desc" => __('Substitute standard Wordpress gallery with our slider on the single pages', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,post,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"substitute_slider_engine" => array(
					"title" => __('Substitution Slider engine', 'axiomthemes'),
					"desc" => __('What engine use to show slider instead standard gallery?', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "swiper",
					"options" => array(
						//"chop" => __("Chop slider", 'axiomthemes'),
						"swiper" => __("Swiper slider", 'axiomthemes')
					),
					"type" => "radio"),
		
		"gallery_instead_image" => array(
					"title" => __('Show gallery instead featured image', 'axiomthemes'),
					"desc" => __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => __('Max images number in the slider', 'axiomthemes'),
					"desc" => __('Maximum images number from gallery into slider', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => __('Gallery popup engine', 'axiomthemes'),
					"desc" => __('Select engine to show popup windows with galleries', 'axiomthemes'),
					"std" => "magnific",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"popup_gallery" => array(
					"title" => __('Enable Gallery mode in the popup', 'axiomthemes'),
					"desc" => __('Enable Gallery mode in the popup or show only single image', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"substitute_audio" => array(
					"title" => __('Substitute audio tags', 'axiomthemes'),
					"desc" => __('Substitute audio tag with source from soundcloud to embed player', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => __('Substitute video tags', 'axiomthemes'),
					"desc" => __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => __('Use Media Element script for audio and video tags', 'axiomthemes'),
					"desc" => __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Typography
		//-------------------------------------------------
		
		'customization_typography' => array(
					"title" => __("Typography", 'axiomthemes'),
					"icon" => 'iconadmin-font',
					"type" => "tab"),
		
		"info_typo_1" => array(
					"title" => __('Typography settings', 'axiomthemes'),
					"desc" => __('Select fonts, sizes and styles for the headings and paragraphs. You can use Google fonts and custom fonts.<br><br>How to install custom @font-face fonts into the theme?<br>All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!<br>Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.<br>Create your @font-face kit by using Fontsquirrel @font-face Generator and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install.', 'axiomthemes'),
					"type" => "info"),
		
		"typography_custom" => array(
					"title" => __('Use custom typography', 'axiomthemes'),
					"desc" => __('Use custom font settings or leave theme-styled fonts', 'axiomthemes'),
					"divider" => false,
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"typography_h1_font" => array(
					"title" => __('Heading 1', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h1_size" => array(
					"title" => __('Size', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "48",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h1_lineheight" => array(
					"title" => __('Line height', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "60",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h1_weight" => array(
					"title" => __('Weight', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h1_style" => array(
					"title" => __('Style', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h1_color" => array(
					"title" => __('Color', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h2_font" => array(
					"title" => __('Heading 2', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h2_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "36",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h2_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "43",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h2_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h2_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h2_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h3_font" => array(
					"title" => __('Heading 3', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h3_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h3_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "28",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h3_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h3_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h3_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h4_font" => array(
					"title" => __('Heading 4', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h4_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h4_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h4_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h4_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h4_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h5_font" => array(
					"title" => __('Heading 5', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h5_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h5_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h5_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h5_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h5_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h6_font" => array(
					"title" => __('Heading 6', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h6_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "16",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h6_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h6_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h6_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h6_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_p_font" => array(
					"title" => __('Paragraph text', 'axiomthemes'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Source Sans Pro",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_p_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "14",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_p_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "21",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_p_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "300",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_p_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_p_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8 last",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		
		
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => __('Blog &amp; Single', 'axiomthemes'),
					"icon" => "iconadmin-docs",
					"override" => "category,courses_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => __('Stream page', 'axiomthemes'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,courses_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => __('Blog streampage parameters', 'axiomthemes'),
					"desc" => __('Select desired blog streampage parameters (you can override it in each category)', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => __('Blog style', 'axiomthemes'),
					"desc" => __('Select desired blog style', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page",
					"std" => "excerpt",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => __('Article style', 'axiomthemes'),
					"desc" => __('Select article display method: boxed or stretch', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "stretch",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"hover_style" => array(
					"title" => __('Hover style', 'axiomthemes'),
					"desc" => __('Select desired hover style (only for Blog style = Portfolio)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "square effect_shift",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => __('Hover dir', 'axiomthemes'),
					"desc" => __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "left_to_right",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"dedicated_location" => array(
					"title" => __('Dedicated location', 'axiomthemes'),
					"desc" => __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'axiomthemes'),
					"override" => "category,courses_group,page,post",
					"std" => "default",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => __('Show filters', 'axiomthemes'),
					"desc" => __('Show filter buttons (only for Blog style = Portfolio, Masonry, Classic)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "hide",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => __('Blog posts sorted by', 'axiomthemes'),
					"desc" => __('Select the desired sorting method for posts', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "date",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => __('Blog posts order', 'axiomthemes'),
					"desc" => __('Select the desired ordering method for posts', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "desc",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => __('Blog posts per page',  'axiomthemes'),
					"desc" => __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => __('Excerpt(Obituaries) maxlength for streampage',  'axiomthemes'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => __('Excerpt maxlength for classic and masonry',  'axiomthemes'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),


            // Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => __('Single page', 'axiomthemes'),
					"icon" => "iconadmin-doc",
					"override" => "category,courses_group,post,page",
					"type" => "tab"),
		
		
		"info_blog_2" => array(
					"title" => __('Single (detail) pages parameters', 'axiomthemes'),
					"desc" => __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"type" => "info"),
		
		"single_style" => array(
					"title" => __('Single page style', 'axiomthemes'),
					"desc" => __('Select desired style for single page', 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group,page,post",
					"std" => "single-standard",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),
		
		"allow_editor" => array(
					"title" => __('Frontend editor',  'axiomthemes'),
					"desc" => __("Allow authors to edit their posts in frontend area)", 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_featured_image" => array(
					"title" => __('Show featured image before post',  'axiomthemes'),
					"desc" => __("Show featured image (if selected) before post content on single pages", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => __('Show post title', 'axiomthemes'),
					"desc" => __('Show area with post title on single pages', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => __('Show post title on links, chat, quote, status', 'axiomthemes'),
					"desc" => __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => __('Show post info', 'axiomthemes'),
					"desc" => __('Show area with post info on single pages', 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => __('Show text before "Read more" tag', 'axiomthemes'),
					"desc" => __('Show text before "Read more" tag on single pages', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => __('Show post author details',  'axiomthemes'),
					"desc" => __("Show post author information block on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => __('Show post tags',  'axiomthemes'),
					"desc" => __("Show tags block on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_counters" => array(
					"title" => __('Show post counters',  'axiomthemes'),
					"desc" => __("Show counters block on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => __('Show related posts',  'axiomthemes'),
					"desc" => __("Show related posts block on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => __('Related posts number',  'axiomthemes'),
					"desc" => __("How many related posts showed on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => __('Related posts columns',  'axiomthemes'),
					"desc" => __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => __('Related posts sorted by', 'axiomthemes'),
					"desc" => __('Select the desired sorting method for related posts', 'axiomthemes'),
		//			"override" => "category,courses_group,page",
					"std" => "date",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => __('Related posts order', 'axiomthemes'),
					"desc" => __('Select the desired ordering method for related posts', 'axiomthemes'),
		//			"override" => "category,courses_group,page",
					"std" => "desc",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => __('Show comments',  'axiomthemes'),
					"desc" => __("Show comments block on single post page", 'axiomthemes'),
					"override" => "category,courses_group,post,page",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_general' => array(
					"title" => __('Other parameters', 'axiomthemes'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,courses_group,page",
					"type" => "tab"),
		
		"info_blog_3" => array(
					"title" => __('Other Blog parameters', 'axiomthemes'),
					"desc" => __('Select excluded categories, substitute parameters, etc.', 'axiomthemes'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => __('Exclude categories', 'axiomthemes'),
					"desc" => __('Select categories, which posts are exclude from blog page', 'axiomthemes'),
					"divider" => false,
					"std" => "",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => __('Blog pagination', 'axiomthemes'),
					"desc" => __('Select type of the pagination on blog streampages', 'axiomthemes'),
					"std" => "pages",
					"override" => "category,courses_group,page",
					"options" => array(
						'pages'    => __('Standard page numbers', 'axiomthemes'),
						'viewmore' => __('"View more" button', 'axiomthemes'),
						'infinite' => __('Infinite scroll', 'axiomthemes')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => __('Blog pagination style', 'axiomthemes'),
					"desc" => __('Select pagination style for standard page numbers', 'axiomthemes'),
					"std" => "pages",
					"override" => "category,courses_group,page",
					"options" => array(
						'pages'  => __('Page numbers list', 'axiomthemes'),
						'slider' => __('Slider with page numbers', 'axiomthemes')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => __('Blog counters', 'axiomthemes'),
					"desc" => __('Select counters, displayed near the post title', 'axiomthemes'),
					"std" => "views",
					"override" => "category,courses_group,page",
					"options" => array(
						'views' => __('Views', 'axiomthemes'),
						'likes' => __('Likes', 'axiomthemes'),
						'rating' => __('Rating', 'axiomthemes'),
						'comments' => __('Comments', 'axiomthemes')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => __("Post's category announce", 'axiomthemes'),
					"desc" => __('What category display in announce block (over posts thumb) - original or nearest parental', 'axiomthemes'),
					"std" => "parental",
					"override" => "category,courses_group,page",
					"options" => array(
						'parental' => __('Nearest parental category', 'axiomthemes'),
						'original' => __("Original post's category", 'axiomthemes')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => __('Show post date after', 'axiomthemes'),
					"desc" => __('Show post date after N days (before - show post age)', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => __('Reviews', 'axiomthemes'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,courses_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => __('Reviews criterias', 'axiomthemes'),
					"desc" => __('Set up list of reviews criterias. You can override it in any category.', 'axiomthemes'),
					"override" => "category,courses_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => __('Show reviews block',  'axiomthemes'),
					"desc" => __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'axiomthemes'),
					"divider" => false,
					"override" => "category,courses_group",
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => __('Max reviews level',  'axiomthemes'),
					"desc" => __("Maximum level for reviews marks", 'axiomthemes'),
					"std" => "5",
					"options" => array(
						'5'=>__('5 stars', 'axiomthemes'),
						'10'=>__('10 stars', 'axiomthemes'),
						'100'=>__('100%', 'axiomthemes')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => __('Show rating as',  'axiomthemes'),
					"desc" => __("Show rating marks as text or as stars/progress bars.", 'axiomthemes'),
					"std" => "stars",
					"options" => array(
						'text' => __('As text (for example: 7.5 / 10)', 'axiomthemes'),
						'stars' => __('As stars or bars', 'axiomthemes')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => __('Reviews Criterias Levels', 'axiomthemes'),
					"desc" => __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'axiomthemes'),
					"std" => __("bad,poor,normal,good,great", 'axiomthemes'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => __('Show first reviews',  'axiomthemes'),
					"desc" => __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'axiomthemes'),
					"std" => "author",
					"options" => array(
						'author' => __('By author', 'axiomthemes'),
						'users' => __('By visitors', 'axiomthemes')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => __('Hide second reviews',  'axiomthemes'),
					"desc" => __("Do you want hide second reviews tab in widgets and single posts?", 'axiomthemes'),
					"std" => "show",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => __('What visitors can vote',  'axiomthemes'),
					"desc" => __("What visitors can vote: all or only registered", 'axiomthemes'),
					"std" => "all",
					"options" => array(
						'all'=>__('All visitors', 'axiomthemes'),
						'registered'=>__('Only registered', 'axiomthemes')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => __('Reviews criterias',  'axiomthemes'),
					"desc" => __('Add default reviews criterias.',  'axiomthemes'),
					"override" => "category,courses_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => __('Contact info', 'axiomthemes'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => __('Contact information', 'axiomthemes'),
					"desc" => __('Company address, phones and e-mail', 'axiomthemes'),
					"type" => "info"),
		
		"contact_email" => array(
					"title" => __('Contact form email', 'axiomthemes'),
					"desc" => __('E-mail for send contact form and user registration data', 'axiomthemes'),
					"divider" => false,
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => __('Company address (part 1)', 'axiomthemes'),
					"desc" => __('Company country, post code and city', 'axiomthemes'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => __('Company address (part 2)', 'axiomthemes'),
					"desc" => __('Street and house number', 'axiomthemes'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => __('Phone', 'axiomthemes'),
					"desc" => __('Phone number', 'axiomthemes'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => __('Fax', 'axiomthemes'),
					"desc" => __('Fax number', 'axiomthemes'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_info" => array(
					"title" => __('Contacts in header', 'axiomthemes'),
					"desc" => __('String with contact info in the site header', 'axiomthemes'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => __('Contact and Comments form', 'axiomthemes'),
					"desc" => __('Maximum length of the messages in the contact form shortcode and in the comments form', 'axiomthemes'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => __('Contact form message', 'axiomthemes'),
					"desc" => __("Message's maxlength in the contact form shortcode", 'axiomthemes'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => __('Comments form message', 'axiomthemes'),
					"desc" => __("Message's maxlength in the comments form", 'axiomthemes'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => __('Default mail function', 'axiomthemes'),
					"desc" => __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'axiomthemes'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => __("Mail function", 'axiomthemes'),
					"desc" => __("What function you want to use for sending mail?", 'axiomthemes'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => __('WP mail', 'axiomthemes'),
						'mail' => __('PHP mail', 'axiomthemes')
					),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => __('Socials', 'axiomthemes'),
					"icon" => "iconadmin-users",
					"override" => "category,courses_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => __('Social networks', 'axiomthemes'),
					"desc" => __("Social networks list for site footer and Social widget", 'axiomthemes'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => __('Social networks',  'axiomthemes'),
					"desc" => __('Select icon and write URL to your profile in desired social networks.',  'axiomthemes'),
					"divider" => false,
					"std" => array(array('url'=>'', 'icon'=>'')),
					//"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_socials'],
                    "options" => $AXIOMTHEMES_GLOBALS['options_params']['list_icons'],
                    //"options" => array ('icon-facebook','icon-twitter','icon-gplus', 'icon-skype'),
					"cloneable" => true,
					"size" => "small",
					"style" => 'icons',
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => __('Share buttons', 'axiomthemes'),
					"override" => "category,courses_group,page",
					"desc" => __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'axiomthemes'),
					"type" => "info"),
		
		"show_share" => array(
					"title" => __('Show social share buttons',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"desc" => __("Show social share buttons block", 'axiomthemes'),
					"std" => "horizontal",
					"options" => array(
						'hide'		=> __('Hide', 'axiomthemes'),
						'vertical'	=> __('Vertical', 'axiomthemes'),
						'horizontal'=> __('Horizontal', 'axiomthemes')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => __('Show share counters',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"desc" => __("Show share counters after social buttons", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => __('Share block caption',  'axiomthemes'),
					"override" => "category,courses_group,page",
					"desc" => __('Caption for the block with social share buttons',  'axiomthemes'),
					"std" => __('Share:', 'axiomthemes'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => __('Share buttons',  'axiomthemes'),
					"desc" => __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'axiomthemes'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					//"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_socials'],
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_icons'],
					"cloneable" => true,
					"size" => "small",
					//"style" => 'images',
					"style" => 'icons',
					"type" => "socials"),


		"info_socials_3" => array(
					"title" => __('Twitter API keys', 'axiomthemes'),
					"desc" => __("Put to this section Twitter API 1.1 keys.<br>
					You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'axiomthemes'),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => __('Twitter username',  'axiomthemes'),
					"desc" => __('Your login (username) in Twitter',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => __('Consumer Key',  'axiomthemes'),
					"desc" => __('Twitter API Consumer key',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => __('Consumer Secret',  'axiomthemes'),
					"desc" => __('Twitter API Consumer secret',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => __('Token Key',  'axiomthemes'),
					"desc" => __('Twitter API Token key',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => __('Token Secret',  'axiomthemes'),
					"desc" => __('Twitter API Token secret',  'axiomthemes'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => __('Search', 'axiomthemes'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => __('Search parameters', 'axiomthemes'),
					"desc" => __('Enable/disable AJAX search and output settings for it', 'axiomthemes'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => __('Show search field', 'axiomthemes'),
					"desc" => __('Show search field in the top area and side menus', 'axiomthemes'),
					"divider" => false,
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => __('Enable AJAX search', 'axiomthemes'),
					"desc" => __('Use incremental AJAX search for the search field in top of page', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => __('Min search string length',  'axiomthemes'),
					"desc" => __('The minimum length of the search string',  'axiomthemes'),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => __('Delay before search (in ms)',  'axiomthemes'),
					"desc" => __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'axiomthemes'),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => __('Search area', 'axiomthemes'),
					"desc" => __('Select post types, what will be include in search results. If not selected - use all types.', 'axiomthemes'),
					"std" => "",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => __('Posts number in output',  'axiomthemes'),
					"desc" => __('Number of the posts to show in search results',  'axiomthemes'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => __("Show post's image", 'axiomthemes'),
					"desc" => __("Show post's thumbnail in the search results", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => __("Show post's date", 'axiomthemes'),
					"desc" => __("Show post's publish date in the search results", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => __("Show post's author", 'axiomthemes'),
					"desc" => __("Show post's author in the search results", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => __("Show post's counters", 'axiomthemes'),
					"desc" => __("Show post's counters (views, comments, likes) in the search results", 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => __('Service', 'axiomthemes'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => __('Theme functionality', 'axiomthemes'),
					"desc" => __('Basic theme functionality settings', 'axiomthemes'),
					"type" => "info"),
		
		"use_ajax_views_counter" => array(
					"title" => __('Use AJAX post views counter', 'axiomthemes'),
					"desc" => __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => __('Additional filters in the admin panel', 'axiomthemes'),
					"desc" => __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => __('Show overriden options for taxonomies', 'axiomthemes'),
					"desc" => __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => __('Show overriden options for posts and pages', 'axiomthemes'),
					"desc" => __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => __('Enable Dummy Data Installer', 'axiomthemes'),
					"desc" => __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => __('Dummy Data Installer Timeout',  'axiomthemes'),
					"desc" => __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'axiomthemes'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_update_notifier" => array(
					"title" => __('Enable Update Notifier', 'axiomthemes'),
					"desc" => __('Show update notifier in admin panel. <b>Attention!</b> When this option is enabled, the theme periodically (every few hours) will communicate with our server, to check the current version. When the connection is slow, it may slow down Dashboard.', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_emailer" => array(
					"title" => __('Enable Emailer in the admin panel', 'axiomthemes'),
					"desc" => __('Allow to use Axiomthemes Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => __('Enable PO Composer in the admin panel', 'axiomthemes'),
					"desc" => __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"), // switch

		"clear_shortcodes" => array(
					"title" => __('Remove line breaks around shortcodes', 'axiomthemes'),
					"desc" => __('Do you want remove spaces and line breaks around shortcodes? <b>Be attentive!</b> This option thoroughly tested on our theme, but may affect third party plugins.', 'axiomthemes'),
					"std" => "yes",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => __('Debug mode', 'axiomthemes'),
					"desc" => __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services (for example http://yui.2clics.net/) or utility <b>yuicompressor-x.y.z.jar</b>', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"packed_scripts" => array(
					"title" => __('Use packed css and js files', 'axiomthemes'),
					"desc" => __('Do you want to use one packed css and one js file with most theme scripts and styles instead many separate files (for speed up page loading). This reduces the number of HTTP requests when loading pages.', 'axiomthemes'),
					"std" => "no",
					"options" => $AXIOMTHEMES_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gtm_code" => array(
					"title" => __('Google tags manager or Google analitics code',  'axiomthemes'),
					"desc" => __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'axiomthemes'),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => __('Google remarketing code',  'axiomthemes'),
					"desc" => __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'axiomthemes'),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),

		"info_service_3" => array(
					"title" => esc_html__('API Keys', 'axiomthemes'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'axiomthemes') ),
					"type" => "info"),
        'api_google' => array(
					"title" => esc_html__('Google API Key', 'axiomthemes'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'axiomthemes') ),
					"std" => "",
					"type" => "text"),
		
		);


		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$AXIOMTHEMES_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => __('Custom stylesheet file', 'axiomthemes'),
			"desc" => __('Path to the custom stylesheet (stored in the uploads folder)', 'axiomthemes'),
			"std" => "",
			"type" => "hidden");
		
		$AXIOMTHEMES_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => __('Custom stylesheet url', 'axiomthemes'),
			"desc" => __('URL to the custom stylesheet (stored in the uploads folder)', 'axiomthemes'),
			"std" => "",
			"type" => "hidden");
		*/

	}
}
?>