<?php
/**
 * Axiomthemes Framework: Theme options custom fields
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_options_custom_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_options_custom_theme_setup' );
	function axiomthemes_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'axiomthemes_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'axiomthemes_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'axiomthemes_options_custom_load_scripts');
	function axiomthemes_options_custom_load_scripts() {
		axiomthemes_enqueue_script( 'axiomthemes-options-custom-script',	axiomthemes_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function axiomthemes_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(axiomthemes_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'axiomthemes') : __( 'Choose Image', 'axiomthemes')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Add to Gallery', 'axiomthemes') : __( 'Choose Image', 'axiomthemes')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="axiomthemes_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'axiomthemes') : __( 'Choose Image', 'axiomthemes')) . '</a>';
			break;
	}
	return apply_filters('axiomthemes_filter_show_custom_field', $output, $id, $field, $value);
}
?>