<?php
/**
 * Axiomthemes Framework: return lists
 *
 * @package axiomthemes
 * @since axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Return list of the animations
if ( !function_exists( 'axiomthemes_get_list_animations' ) ) {
	function axiomthemes_get_list_animations($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_animations']))
			$list = $AXIOMTHEMES_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiomthemes');
			$list['bounced']		= __('Bounced',		'axiomthemes');
			$list['flash']			= __('Flash',		'axiomthemes');
			$list['flip']			= __('Flip',		'axiomthemes');
			$list['pulse']			= __('Pulse',		'axiomthemes');
			$list['rubberBand']		= __('Rubber Band',	'axiomthemes');
			$list['shake']			= __('Shake',		'axiomthemes');
			$list['swing']			= __('Swing',		'axiomthemes');
			$list['tada']			= __('Tada',		'axiomthemes');
			$list['wobble']			= __('Wobble',		'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_animations'] = $list = apply_filters('axiomthemes_filter_list_animations', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'axiomthemes_get_list_animations_in' ) ) {
	function axiomthemes_get_list_animations_in($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_animations_in']))
			$list = $AXIOMTHEMES_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiomthemes');
			$list['bounceIn']		= __('Bounce In',			'axiomthemes');
			$list['bounceInUp']		= __('Bounce In Up',		'axiomthemes');
			$list['bounceInDown']	= __('Bounce In Down',		'axiomthemes');
			$list['bounceInLeft']	= __('Bounce In Left',		'axiomthemes');
			$list['bounceInRight']	= __('Bounce In Right',		'axiomthemes');
			$list['fadeIn']			= __('Fade In',				'axiomthemes');
			$list['fadeInUp']		= __('Fade In Up',			'axiomthemes');
			$list['fadeInDown']		= __('Fade In Down',		'axiomthemes');
			$list['fadeInLeft']		= __('Fade In Left',		'axiomthemes');
			$list['fadeInRight']	= __('Fade In Right',		'axiomthemes');
			$list['fadeInUpBig']	= __('Fade In Up Big',		'axiomthemes');
			$list['fadeInDownBig']	= __('Fade In Down Big',	'axiomthemes');
			$list['fadeInLeftBig']	= __('Fade In Left Big',	'axiomthemes');
			$list['fadeInRightBig']	= __('Fade In Right Big',	'axiomthemes');
			$list['flipInX']		= __('Flip In X',			'axiomthemes');
			$list['flipInY']		= __('Flip In Y',			'axiomthemes');
			$list['lightSpeedIn']	= __('Light Speed In',		'axiomthemes');
			$list['rotateIn']		= __('Rotate In',			'axiomthemes');
			$list['rotateInUpLeft']		= __('Rotate In Down Left',	'axiomthemes');
			$list['rotateInUpRight']	= __('Rotate In Up Right',	'axiomthemes');
			$list['rotateInDownLeft']	= __('Rotate In Up Left',	'axiomthemes');
			$list['rotateInDownRight']	= __('Rotate In Down Right','axiomthemes');
			$list['rollIn']				= __('Roll In',			'axiomthemes');
			$list['slideInUp']			= __('Slide In Up',		'axiomthemes');
			$list['slideInDown']		= __('Slide In Down',	'axiomthemes');
			$list['slideInLeft']		= __('Slide In Left',	'axiomthemes');
			$list['slideInRight']		= __('Slide In Right',	'axiomthemes');
			$list['zoomIn']				= __('Zoom In',			'axiomthemes');
			$list['zoomInUp']			= __('Zoom In Up',		'axiomthemes');
			$list['zoomInDown']			= __('Zoom In Down',	'axiomthemes');
			$list['zoomInLeft']			= __('Zoom In Left',	'axiomthemes');
			$list['zoomInRight']		= __('Zoom In Right',	'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_animations_in'] = $list = apply_filters('axiomthemes_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'axiomthemes_get_list_animations_out' ) ) {
	function axiomthemes_get_list_animations_out($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_animations_out']))
			$list = $AXIOMTHEMES_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiomthemes');
			$list['bounceOut']		= __('Bounce Out',			'axiomthemes');
			$list['bounceOutUp']	= __('Bounce Out Up',		'axiomthemes');
			$list['bounceOutDown']	= __('Bounce Out Down',		'axiomthemes');
			$list['bounceOutLeft']	= __('Bounce Out Left',		'axiomthemes');
			$list['bounceOutRight']	= __('Bounce Out Right',	'axiomthemes');
			$list['fadeOut']		= __('Fade Out',			'axiomthemes');
			$list['fadeOutUp']		= __('Fade Out Up',			'axiomthemes');
			$list['fadeOutDown']	= __('Fade Out Down',		'axiomthemes');
			$list['fadeOutLeft']	= __('Fade Out Left',		'axiomthemes');
			$list['fadeOutRight']	= __('Fade Out Right',		'axiomthemes');
			$list['fadeOutUpBig']	= __('Fade Out Up Big',		'axiomthemes');
			$list['fadeOutDownBig']	= __('Fade Out Down Big',	'axiomthemes');
			$list['fadeOutLeftBig']	= __('Fade Out Left Big',	'axiomthemes');
			$list['fadeOutRightBig']= __('Fade Out Right Big',	'axiomthemes');
			$list['flipOutX']		= __('Flip Out X',			'axiomthemes');
			$list['flipOutY']		= __('Flip Out Y',			'axiomthemes');
			$list['hinge']			= __('Hinge Out',			'axiomthemes');
			$list['lightSpeedOut']	= __('Light Speed Out',		'axiomthemes');
			$list['rotateOut']		= __('Rotate Out',			'axiomthemes');
			$list['rotateOutUpLeft']	= __('Rotate Out Down Left',	'axiomthemes');
			$list['rotateOutUpRight']	= __('Rotate Out Up Right',		'axiomthemes');
			$list['rotateOutDownLeft']	= __('Rotate Out Up Left',		'axiomthemes');
			$list['rotateOutDownRight']	= __('Rotate Out Down Right',	'axiomthemes');
			$list['rollOut']			= __('Roll Out',		'axiomthemes');
			$list['slideOutUp']			= __('Slide Out Up',		'axiomthemes');
			$list['slideOutDown']		= __('Slide Out Down',	'axiomthemes');
			$list['slideOutLeft']		= __('Slide Out Left',	'axiomthemes');
			$list['slideOutRight']		= __('Slide Out Right',	'axiomthemes');
			$list['zoomOut']			= __('Zoom Out',			'axiomthemes');
			$list['zoomOutUp']			= __('Zoom Out Up',		'axiomthemes');
			$list['zoomOutDown']		= __('Zoom Out Down',	'axiomthemes');
			$list['zoomOutLeft']		= __('Zoom Out Left',	'axiomthemes');
			$list['zoomOutRight']		= __('Zoom Out Right',	'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_animations_out'] = $list = apply_filters('axiomthemes_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'axiomthemes_get_list_categories' ) ) {
	function axiomthemes_get_list_categories($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_categories']))
			$list = $AXIOMTHEMES_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			foreach ($taxonomies as $cat) {
				$list[$cat->term_id] = $cat->name;
			}
			$AXIOMTHEMES_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'axiomthemes_get_list_terms' ) ) {
    function axiomthemes_get_list_terms($prepend_inherit=false, $taxonomy='category') {
        global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_taxonomies_'.($taxonomy)]))
            $list = $AXIOMTHEMES_GLOBALS['list_taxonomies_' . ($taxonomy)];
        else {
            $list = array();
            $args = array(
                'child_of'                 => 0,
                'parent'                   => '',
                'orderby'                  => 'name',
                'order'                    => 'ASC',
                'hide_empty'               => 0,
                'hierarchical'             => 1,
                'exclude'                  => '',
                'include'                  => '',
                'number'                   => '',
                'taxonomy'                 => $taxonomy,
                'pad_counts'               => false );
            $taxonomies = get_terms( $taxonomy, $args );
                foreach ($taxonomies as $cat) {
                    $list[$cat->term_id] = $cat->name;    // . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
                }
            $AXIOMTHEMES_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
        }
        return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
    }
}

// Return list of post's types
if ( !function_exists( 'axiomthemes_get_list_posts_types' ) ) {
	function axiomthemes_get_list_posts_types($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_posts_types']))
			$list = $AXIOMTHEMES_GLOBALS['list_posts_types'];
		else {
			$list = array();
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = __('Post', 'axiomthemes');
			foreach ($types as $t) {
				if ($t == 'post') continue;
				$list[$t] = axiomthemes_strtoproper($t);
			}
			*/
			// Return only theme inheritance supported post types
			$AXIOMTHEMES_GLOBALS['list_posts_types'] = $list = apply_filters('axiomthemes_filter_list_post_types', array());
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'axiomthemes_get_list_posts' ) ) {
	function axiomthemes_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $AXIOMTHEMES_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($AXIOMTHEMES_GLOBALS[$hash]))
			$list = $AXIOMTHEMES_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'axiomthemes');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			foreach ($posts as $post) {
				$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
			}
			$AXIOMTHEMES_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'axiomthemes_get_list_users' ) ) {
	function axiomthemes_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_users']))
			$list = $AXIOMTHEMES_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'axiomthemes');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			foreach ($users as $user) {
				$accept = true;
				if (is_array($user->roles)) {
					if (count($user->roles) > 0) {
						$accept = false;
						foreach ($user->roles as $role) {
							if (in_array($role, $roles)) {
								$accept = true;
								break;
							}
						}
					}
				}
				if ($accept) $list[$user->user_login] = $user->display_name;
			}
			$AXIOMTHEMES_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return sliders list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'axiomthemes_get_list_sliders' ) ) {
	function axiomthemes_get_list_sliders($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_sliders']))
			$list = $AXIOMTHEMES_GLOBALS['list_sliders'];
		else {
			$list = array();
			$list["swiper"] = __("Posts slider (Swiper)", 'axiomthemes');
			if (axiomthemes_exists_revslider())
				$list["revo"] = __("Layer slider (Revolution)", 'axiomthemes');
			if (axiomthemes_exists_royalslider())
				$list["royal"] = __("Layer slider (Royal)", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_sliders'] = $list = apply_filters('axiomthemes_filter_list_sliders', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with popup engines
if ( !function_exists( 'axiomthemes_get_list_popup_engines' ) ) {
	function axiomthemes_get_list_popup_engines($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_popup_engines']))
			$list = $AXIOMTHEMES_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = __("Pretty photo", 'axiomthemes');
			$list["magnific"] = __("Magnific popup", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_popup_engines'] = $list = apply_filters('axiomthemes_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_menus' ) ) {
	function axiomthemes_get_list_menus($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_menus']))
			$list = $AXIOMTHEMES_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = __("Default", 'axiomthemes');
			$menus = wp_get_nav_menus();
			if ($menus) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$AXIOMTHEMES_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'axiomthemes_get_list_sidebars' ) ) {
	function axiomthemes_get_list_sidebars($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_sidebars'])) {
			$list = $AXIOMTHEMES_GLOBALS['list_sidebars'];
		} else {
			$list = isset($AXIOMTHEMES_GLOBALS['registered_sidebars']) ? $AXIOMTHEMES_GLOBALS['registered_sidebars'] : array();
			$AXIOMTHEMES_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'axiomthemes_get_list_sidebars_positions' ) ) {
	function axiomthemes_get_list_sidebars_positions($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_sidebars_positions']))
			$list = $AXIOMTHEMES_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['left']  = __('Left',  'axiomthemes');
			$list['right'] = __('Right', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'axiomthemes_get_sidebar_class' ) ) {
	function axiomthemes_get_sidebar_class($style, $pos) {
		return axiomthemes_sc_param_is_off($style) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($pos);
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_body_styles' ) ) {
	function axiomthemes_get_list_body_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_body_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= __('Boxed',		'axiomthemes');
            $list['fullboxed']	= __('Fullboxed',	'axiomthemes');
//			$list['wide']		= __('Wide',		'axiomthemes');
//			$list['fullwide']	= __('Fullwide',	'axiomthemes');
//			$list['fullscreen']	= __('Fullscreen',	'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_body_styles'] = $list = apply_filters('axiomthemes_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_skins' ) ) {
	function axiomthemes_get_list_skins($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_skins']))
			$list = $AXIOMTHEMES_GLOBALS['list_skins'];
		else
			$AXIOMTHEMES_GLOBALS['list_skins'] = $list = axiomthemes_get_list_folders("skins");
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'axiomthemes_get_list_themes' ) ) {
	function axiomthemes_get_list_themes($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_themes']))
			$list = $AXIOMTHEMES_GLOBALS['list_themes'];
		else
			$AXIOMTHEMES_GLOBALS['list_themes'] = $list = axiomthemes_get_list_files("css/themes");
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_templates' ) ) {
	function axiomthemes_get_list_templates($mode='') {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_templates_'.($mode)]))
			$list = $AXIOMTHEMES_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			foreach ($AXIOMTHEMES_GLOBALS['registered_templates'] as $k=>$v) {
				if ($mode=='' || axiomthemes_strpos($v['mode'], $mode)!==false)
					$list[$k] = !empty($v['title']) ? $v['title'] : axiomthemes_strtoproper($v['layout']);
			}
			$AXIOMTHEMES_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_templates_blog' ) ) {
	function axiomthemes_get_list_templates_blog($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_templates_blog']))
			$list = $AXIOMTHEMES_GLOBALS['list_templates_blog'];
		else {
			$list = axiomthemes_get_list_templates('blog');
			$AXIOMTHEMES_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_templates_blogger' ) ) {
	function axiomthemes_get_list_templates_blogger($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_templates_blogger']))
			$list = $AXIOMTHEMES_GLOBALS['list_templates_blogger'];
		else {
			$list = axiomthemes_array_merge(axiomthemes_get_list_templates('blogger'), axiomthemes_get_list_templates('blog'));
			$AXIOMTHEMES_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_templates_single' ) ) {
	function axiomthemes_get_list_templates_single($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_templates_single']))
			$list = $AXIOMTHEMES_GLOBALS['list_templates_single'];
		else {
			$list = axiomthemes_get_list_templates('single');
			$AXIOMTHEMES_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_article_styles' ) ) {
	function axiomthemes_get_list_article_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_article_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = __('Boxed', 'axiomthemes');
			$list["stretch"] = __('Stretch', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return color schemes list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_color_schemes' ) ) {
	function axiomthemes_get_list_color_schemes($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_color_schemes']))
			$list = $AXIOMTHEMES_GLOBALS['list_color_schemes'];
		else {
			$list = array();
			if (!empty($AXIOMTHEMES_GLOBALS['color_schemes'])) {
				foreach ($AXIOMTHEMES_GLOBALS['color_schemes'] as $k=>$v) {
					$list[$k] = $v['title'];
				}
			}
			$AXIOMTHEMES_GLOBALS['list_color_schemes'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return button styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_button_styles' ) ) {
	function axiomthemes_get_list_button_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_button_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_button_styles'];
		else {
			$list = array();
			$list["custom"]	= __('Custom', 'axiomthemes');
			$list["link"] 	= __('As links', 'axiomthemes');
			$list["menu"] 	= __('As main menu', 'axiomthemes');
			$list["user"] 	= __('As user menu', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_button_styles'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_post_formats_filters' ) ) {
	function axiomthemes_get_list_post_formats_filters($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_post_formats_filters']))
			$list = $AXIOMTHEMES_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = __('All posts', 'axiomthemes');
			$list["thumbs"]  = __('With thumbs', 'axiomthemes');
			$list["reviews"] = __('With reviews', 'axiomthemes');
			$list["video"]   = __('With videos', 'axiomthemes');
			$list["audio"]   = __('With audios', 'axiomthemes');
			$list["gallery"] = __('With galleries', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_portfolio_filters' ) ) {
	function axiomthemes_get_list_portfolio_filters($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_portfolio_filters']))
			$list = $AXIOMTHEMES_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = __('Hide', 'axiomthemes');
			$list["tags"] = __('Tags', 'axiomthemes');
			$list["categories"] = __('Categories', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_hovers' ) ) {
	function axiomthemes_get_list_hovers($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_hovers']))
			$list = $AXIOMTHEMES_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = __('Circle Effect 1',  'axiomthemes');
			$list['circle effect2']  = __('Circle Effect 2',  'axiomthemes');
			$list['circle effect3']  = __('Circle Effect 3',  'axiomthemes');
			$list['circle effect4']  = __('Circle Effect 4',  'axiomthemes');
			$list['circle effect5']  = __('Circle Effect 5',  'axiomthemes');
			$list['circle effect6']  = __('Circle Effect 6',  'axiomthemes');
			$list['circle effect7']  = __('Circle Effect 7',  'axiomthemes');
			$list['circle effect8']  = __('Circle Effect 8',  'axiomthemes');
			$list['circle effect9']  = __('Circle Effect 9',  'axiomthemes');
			$list['circle effect10'] = __('Circle Effect 10',  'axiomthemes');
			$list['circle effect11'] = __('Circle Effect 11',  'axiomthemes');
			$list['circle effect12'] = __('Circle Effect 12',  'axiomthemes');
			$list['circle effect13'] = __('Circle Effect 13',  'axiomthemes');
			$list['circle effect14'] = __('Circle Effect 14',  'axiomthemes');
			$list['circle effect15'] = __('Circle Effect 15',  'axiomthemes');
			$list['circle effect16'] = __('Circle Effect 16',  'axiomthemes');
			$list['circle effect17'] = __('Circle Effect 17',  'axiomthemes');
			$list['circle effect18'] = __('Circle Effect 18',  'axiomthemes');
			$list['circle effect19'] = __('Circle Effect 19',  'axiomthemes');
			$list['circle effect20'] = __('Circle Effect 20',  'axiomthemes');
			$list['square effect1']  = __('Square Effect 1',  'axiomthemes');
			$list['square effect2']  = __('Square Effect 2',  'axiomthemes');
			$list['square effect3']  = __('Square Effect 3',  'axiomthemes');
	//		$list['square effect4']  = __('Square Effect 4',  'axiomthemes');
			$list['square effect5']  = __('Square Effect 5',  'axiomthemes');
			$list['square effect6']  = __('Square Effect 6',  'axiomthemes');
			$list['square effect7']  = __('Square Effect 7',  'axiomthemes');
			$list['square effect8']  = __('Square Effect 8',  'axiomthemes');
			$list['square effect9']  = __('Square Effect 9',  'axiomthemes');
			$list['square effect10'] = __('Square Effect 10',  'axiomthemes');
			$list['square effect11'] = __('Square Effect 11',  'axiomthemes');
			$list['square effect12'] = __('Square Effect 12',  'axiomthemes');
			$list['square effect13'] = __('Square Effect 13',  'axiomthemes');
			$list['square effect14'] = __('Square Effect 14',  'axiomthemes');
			$list['square effect15'] = __('Square Effect 15',  'axiomthemes');
			$list['square effect_dir']   = __('Square Effect Dir',   'axiomthemes');
			$list['square effect_shift'] = __('Square Effect Shift', 'axiomthemes');
			$list['square effect_book']  = __('Square Effect Book',  'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_hovers'] = $list = apply_filters('axiomthemes_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_hovers_directions' ) ) {
	function axiomthemes_get_list_hovers_directions($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_hovers_directions']))
			$list = $AXIOMTHEMES_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = __('Left to Right',  'axiomthemes');
			$list['right_to_left'] = __('Right to Left',  'axiomthemes');
			$list['top_to_bottom'] = __('Top to Bottom',  'axiomthemes');
			$list['bottom_to_top'] = __('Bottom to Top',  'axiomthemes');
			$list['scale_up']      = __('Scale Up',  'axiomthemes');
			$list['scale_down']    = __('Scale Down',  'axiomthemes');
			$list['scale_down_up'] = __('Scale Down-Up',  'axiomthemes');
			$list['from_left_and_right'] = __('From Left and Right',  'axiomthemes');
			$list['from_top_and_bottom'] = __('From Top and Bottom',  'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_hovers_directions'] = $list = apply_filters('axiomthemes_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'axiomthemes_get_list_label_positions' ) ) {
	function axiomthemes_get_list_label_positions($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_label_positions']))
			$list = $AXIOMTHEMES_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= __('Top',		'axiomthemes');
			$list['bottom']	= __('Bottom',		'axiomthemes');
			$list['left']	= __('Left',		'axiomthemes');
			$list['over']	= __('Over',		'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_label_positions'] = $list = apply_filters('axiomthemes_filter_label_positions', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return background tints list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_bg_tints' ) ) {
	function axiomthemes_get_list_bg_tints($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_bg_tints']))
			$list = $AXIOMTHEMES_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['none']  = __('None',  'axiomthemes');
			$list['light'] = __('Light','axiomthemes');
			$list['dark']  = __('Dark',  'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_bg_tints'] = $list = apply_filters('axiomthemes_filter_bg_tints', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return background tints list for sidebars, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_sidebar_styles' ) ) {
	function axiomthemes_get_list_sidebar_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_sidebar_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_sidebar_styles'];
		else {
			$list = array();
			$list['none']  = __('None',  'axiomthemes');
			$list['light white'] = __('White','axiomthemes');
			$list['light'] = __('Light','axiomthemes');
			$list['dark']  = __('Dark',  'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_sidebar_styles'] = $list = apply_filters('axiomthemes_filter_sidebar_styles', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_field_types' ) ) {
	function axiomthemes_get_list_field_types($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_field_types']))
			$list = $AXIOMTHEMES_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = __('Text',  'axiomthemes');
			$list['textarea'] = __('Text Area','axiomthemes');
			$list['password'] = __('Password',  'axiomthemes');
			$list['radio']    = __('Radio',  'axiomthemes');
			$list['checkbox'] = __('Checkbox',  'axiomthemes');
			$list['button']   = __('Button','axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_field_types'] = $list = apply_filters('axiomthemes_filter_field_types', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'axiomthemes_get_list_googlemap_styles' ) ) {
	function axiomthemes_get_list_googlemap_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_googlemap_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = __('Default', 'axiomthemes');
			$list['simple'] = __('Simple', 'axiomthemes');
			$list['greyscale'] = __('Greyscale', 'axiomthemes');
			$list['greyscale2'] = __('Greyscale 2', 'axiomthemes');
			$list['invert'] = __('Invert', 'axiomthemes');
			$list['dark'] = __('Dark', 'axiomthemes');
			$list['style1'] = __('Custom style 1', 'axiomthemes');
			$list['style2'] = __('Custom style 2', 'axiomthemes');
			$list['style3'] = __('Custom style 3', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_googlemap_styles'] = $list = apply_filters('axiomthemes_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'axiomthemes_get_list_icons' ) ) {
	function axiomthemes_get_list_icons($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_icons']))
			$list = $AXIOMTHEMES_GLOBALS['list_icons'];
		else
			$AXIOMTHEMES_GLOBALS['list_icons'] = $list = axiomthemes_parse_icons_classes(axiomthemes_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'axiomthemes_get_list_socials' ) ) {
	function axiomthemes_get_list_socials($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_socials']))
			$list = $AXIOMTHEMES_GLOBALS['list_socials'];
		else
			$AXIOMTHEMES_GLOBALS['list_socials'] = $list = axiomthemes_get_list_files("images/socials", "png");
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'axiomthemes_get_list_flags' ) ) {
	function axiomthemes_get_list_flags($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_flags']))
			$list = $AXIOMTHEMES_GLOBALS['list_flags'];
		else
			$AXIOMTHEMES_GLOBALS['list_flags'] = $list = axiomthemes_get_list_files("images/flags", "png");
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'axiomthemes_get_list_yesno' ) ) {
	function axiomthemes_get_list_yesno($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_yesno']))
			$list = $AXIOMTHEMES_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = __("Yes", 'axiomthemes');
			$list["no"]  = __("No", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'axiomthemes_get_list_onoff' ) ) {
	function axiomthemes_get_list_onoff($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_onoff']))
			$list = $AXIOMTHEMES_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = __("On", 'axiomthemes');
			$list["off"] = __("Off", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'axiomthemes_get_list_showhide' ) ) {
	function axiomthemes_get_list_showhide($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_showhide']))
			$list = $AXIOMTHEMES_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = __("Show", 'axiomthemes');
			$list["hide"] = __("Hide", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'axiomthemes_get_list_orderings' ) ) {
	function axiomthemes_get_list_orderings($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_orderings']))
			$list = $AXIOMTHEMES_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = __("Ascending", 'axiomthemes');
			$list["desc"] = __("Descending", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'axiomthemes_get_list_directions' ) ) {
	function axiomthemes_get_list_directions($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_directions']))
			$list = $AXIOMTHEMES_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = __("Horizontal", 'axiomthemes');
			$list["vertical"] = __("Vertical", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'axiomthemes_get_list_floats' ) ) {
	function axiomthemes_get_list_floats($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_floats']))
			$list = $AXIOMTHEMES_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiomthemes');
			$list["left"] = __("Float Left", 'axiomthemes');
			$list["right"] = __("Float Right", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'axiomthemes_get_list_alignments' ) ) {
	function axiomthemes_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_alignments']))
			$list = $AXIOMTHEMES_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiomthemes');
			$list["left"] = __("Left", 'axiomthemes');
			$list["center"] = __("Center", 'axiomthemes');
			$list["right"] = __("Right", 'axiomthemes');
			if ($justify) $list["justify"] = __("Justify", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'axiomthemes_get_list_sortings' ) ) {
	function axiomthemes_get_list_sortings($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_sortings']))
			$list = $AXIOMTHEMES_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = __("Date", 'axiomthemes');
			$list["title"] = __("Alphabetically", 'axiomthemes');
			$list["views"] = __("Popular (views count)", 'axiomthemes');
			$list["comments"] = __("Most commented (comments count)", 'axiomthemes');
			$list["author_rating"] = __("Author rating", 'axiomthemes');
			$list["users_rating"] = __("Visitors (users) rating", 'axiomthemes');
			$list["random"] = __("Random", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_sortings'] = $list = apply_filters('axiomthemes_filter_list_sortings', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'axiomthemes_get_list_columns' ) ) {
	function axiomthemes_get_list_columns($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_columns']))
			$list = $AXIOMTHEMES_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiomthemes');
			$list["1_1"] = __("100%", 'axiomthemes');
			$list["1_2"] = __("1/2", 'axiomthemes');
			$list["1_3"] = __("1/3", 'axiomthemes');
			$list["2_3"] = __("2/3", 'axiomthemes');
			$list["1_4"] = __("1/4", 'axiomthemes');
			$list["3_4"] = __("3/4", 'axiomthemes');
			$list["1_5"] = __("1/5", 'axiomthemes');
			$list["2_5"] = __("2/5", 'axiomthemes');
			$list["3_5"] = __("3/5", 'axiomthemes');
			$list["4_5"] = __("4/5", 'axiomthemes');
			$list["1_6"] = __("1/6", 'axiomthemes');
			$list["5_6"] = __("5/6", 'axiomthemes');
			$list["1_7"] = __("1/7", 'axiomthemes');
			$list["2_7"] = __("2/7", 'axiomthemes');
			$list["3_7"] = __("3/7", 'axiomthemes');
			$list["4_7"] = __("4/7", 'axiomthemes');
			$list["5_7"] = __("5/7", 'axiomthemes');
			$list["6_7"] = __("6/7", 'axiomthemes');
			$list["1_8"] = __("1/8", 'axiomthemes');
			$list["3_8"] = __("3/8", 'axiomthemes');
			$list["5_8"] = __("5/8", 'axiomthemes');
			$list["7_8"] = __("7/8", 'axiomthemes');
			$list["1_9"] = __("1/9", 'axiomthemes');
			$list["2_9"] = __("2/9", 'axiomthemes');
			$list["4_9"] = __("4/9", 'axiomthemes');
			$list["5_9"] = __("5/9", 'axiomthemes');
			$list["7_9"] = __("7/9", 'axiomthemes');
			$list["8_9"] = __("8/9", 'axiomthemes');
			$list["1_10"]= __("1/10", 'axiomthemes');
			$list["3_10"]= __("3/10", 'axiomthemes');
			$list["7_10"]= __("7/10", 'axiomthemes');
			$list["9_10"]= __("9/10", 'axiomthemes');
			$list["1_11"]= __("1/11", 'axiomthemes');
			$list["2_11"]= __("2/11", 'axiomthemes');
			$list["3_11"]= __("3/11", 'axiomthemes');
			$list["4_11"]= __("4/11", 'axiomthemes');
			$list["5_11"]= __("5/11", 'axiomthemes');
			$list["6_11"]= __("6/11", 'axiomthemes');
			$list["7_11"]= __("7/11", 'axiomthemes');
			$list["8_11"]= __("8/11", 'axiomthemes');
			$list["9_11"]= __("9/11", 'axiomthemes');
			$list["10_11"]= __("10/11", 'axiomthemes');
			$list["1_12"]= __("1/12", 'axiomthemes');
			$list["5_12"]= __("5/12", 'axiomthemes');
			$list["7_12"]= __("7/12", 'axiomthemes');
			$list["10_12"]= __("10/12", 'axiomthemes');
			$list["11_12"]= __("11/12", 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_columns'] = $list = apply_filters('axiomthemes_filter_list_columns', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'axiomthemes_get_list_dedicated_locations' ) ) {
	function axiomthemes_get_list_dedicated_locations($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_dedicated_locations']))
			$list = $AXIOMTHEMES_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = __('As in the post defined', 'axiomthemes');
			$list["center"]  = __('Above the text of the post', 'axiomthemes');
			$list["left"]    = __('To the left the text of the post', 'axiomthemes');
			$list["right"]   = __('To the right the text of the post', 'axiomthemes');
			$list["alter"]   = __('Alternates for each post', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_dedicated_locations'] = $list = apply_filters('axiomthemes_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'axiomthemes_get_post_format_name' ) ) {
	function axiomthemes_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? __('gallery', 'axiomthemes') : __('galleries', 'axiomthemes');
		else if ($format=='video')	$name = $single ? __('video', 'axiomthemes') : __('videos', 'axiomthemes');
		else if ($format=='audio')	$name = $single ? __('audio', 'axiomthemes') : __('audios', 'axiomthemes');
		else if ($format=='image')	$name = $single ? __('image', 'axiomthemes') : __('images', 'axiomthemes');
		else if ($format=='quote')	$name = $single ? __('quote', 'axiomthemes') : __('quotes', 'axiomthemes');
		else if ($format=='link')	$name = $single ? __('link', 'axiomthemes') : __('links', 'axiomthemes');
		else if ($format=='status')	$name = $single ? __('status', 'axiomthemes') : __('statuses', 'axiomthemes');
		else if ($format=='aside')	$name = $single ? __('aside', 'axiomthemes') : __('asides', 'axiomthemes');
		else if ($format=='chat')	$name = $single ? __('chat', 'axiomthemes') : __('chats', 'axiomthemes');
		else						$name = $single ? __('standard', 'axiomthemes') : __('standards', 'axiomthemes');
		return apply_filters('axiomthemes_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'axiomthemes_get_post_format_icon' ) ) {
	function axiomthemes_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'picture-2';
		else if ($format=='video')	$icon .= 'video-2';
		else if ($format=='audio')	$icon .= 'musical-2';
		else if ($format=='image')	$icon .= 'picture-boxed-2';
		else if ($format=='quote')	$icon .= 'quote-2';
		else if ($format=='link')	$icon .= 'link-2';
		else if ($format=='status')	$icon .= 'agenda-2';
		else if ($format=='aside')	$icon .= 'chat-2';
		else if ($format=='chat')	$icon .= 'chat-all-2';
		else						$icon .= 'book-2';
		return apply_filters('axiomthemes_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'axiomthemes_get_list_fonts_styles' ) ) {
	function axiomthemes_get_list_fonts_styles($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_fonts_styles']))
			$list = $AXIOMTHEMES_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = __('I','axiomthemes');
			$list['u'] = __('U', 'axiomthemes');
			$AXIOMTHEMES_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'axiomthemes_get_list_fonts' ) ) {
	function axiomthemes_get_list_fonts($prepend_inherit=false) {
		global $AXIOMTHEMES_GLOBALS;
		if (isset($AXIOMTHEMES_GLOBALS['list_fonts']))
			$list = $AXIOMTHEMES_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = axiomthemes_array_merge($list, axiomthemes_get_list_fonts_custom());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>axiomthemes_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$AXIOMTHEMES_GLOBALS['list_fonts'] = $list = apply_filters('axiomthemes_filter_list_fonts', $list);
		}
		return $prepend_inherit ? axiomthemes_array_merge(array('inherit' => __("Inherit", 'axiomthemes')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'axiomthemes_get_list_fonts_custom' ) ) {
	function axiomthemes_get_list_fonts_custom($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = axiomthemes_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? axiomthemes_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? axiomthemes_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.__('uploaded font', 'axiomthemes').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>