<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'axiomthemes_add_theme_inheritance' ) ) {
	function axiomthemes_add_theme_inheritance($options, $append=true) {
		global $AXIOMTHEMES_GLOBALS;
		if (!isset($AXIOMTHEMES_GLOBALS["inheritance"])) $AXIOMTHEMES_GLOBALS["inheritance"] = array();
		$AXIOMTHEMES_GLOBALS['inheritance'] = $append
			? axiomthemes_array_merge($AXIOMTHEMES_GLOBALS['inheritance'], $options)
			: axiomthemes_array_merge($options, $AXIOMTHEMES_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'axiomthemes_get_theme_inheritance' ) ) {
	function axiomthemes_get_theme_inheritance($key = '') {
		global $AXIOMTHEMES_GLOBALS;
		return $key ? $AXIOMTHEMES_GLOBALS['inheritance'][$key] : $AXIOMTHEMES_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'axiomthemes_detect_inheritance_key' ) ) {
	function axiomthemes_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$inheritance_key = apply_filters('axiomthemes_filter_detect_inheritance_key', '');
		return $inheritance_key;
	}
}


// Return key for override parameter
if ( !function_exists( 'axiomthemes_get_override_key' ) ) {
	function axiomthemes_get_override_key($value, $by) {
		$key = '';
		$inheritance = axiomthemes_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'axiomthemes_get_taxonomy_categories_by_post_type' ) ) {
	function axiomthemes_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = axiomthemes_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'axiomthemes_get_taxonomy_tags_by_post_type' ) ) {
	function axiomthemes_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = axiomthemes_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>