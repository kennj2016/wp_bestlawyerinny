<?php
/**
 * Axiomthemes Framework: Supported post types settings
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if (!function_exists('axiomthemes_post_type_theme_setup')) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_post_type_theme_setup', 9 );
	function axiomthemes_post_type_theme_setup() {
		if ( !axiomthemes_options_is_used() ) return;
		$post_type = axiomthemes_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = axiomthemes_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'axiomthemes_post_type_save_options');
			add_action('admin_menu',			'axiomthemes_post_type_add_meta_box');
			add_action('admin_enqueue_scripts', 'axiomthemes_post_type_admin_scripts');
			// Create meta box
			global $AXIOMTHEMES_GLOBALS;
			$AXIOMTHEMES_GLOBALS['post_meta_box'] = array(
				'id' => 'post-meta-box',
				'title' => __('Post Options', 'axiomthemes'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
			);
		}
	}
}


// Admin scripts
if (!function_exists('axiomthemes_post_type_admin_scripts')) {
	//add_action('admin_enqueue_scripts', 'axiomthemes_post_type_admin_scripts');
	function axiomthemes_post_type_admin_scripts() {
	}
}


// Add meta box
if (!function_exists('axiomthemes_post_type_add_meta_box')) {
	//add_action('admin_menu', 'axiomthemes_post_type_add_meta_box');
	function axiomthemes_post_type_add_meta_box() {
		global $AXIOMTHEMES_GLOBALS;
		$mb = $AXIOMTHEMES_GLOBALS['post_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'axiomthemes_post_type_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('axiomthemes_post_type_show_meta_box')) {
	function axiomthemes_post_type_show_meta_box() {
		global $post, $AXIOMTHEMES_GLOBALS;
		
		$post_type = axiomthemes_admin_get_current_post_type();
		$override_key = axiomthemes_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_post_nonce" value="' .esc_attr(wp_create_nonce(basename(__FILE__))).'" />';
		echo '<input type="hidden" name="meta_box_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('axiomthemes_filter_post_load_custom_options', get_post_meta($post->ID, 'post_custom_options', true), $post_type, $post->ID);

		$mb = $AXIOMTHEMES_GLOBALS['post_meta_box'];
		$post_options = array_merge($AXIOMTHEMES_GLOBALS['options'], $mb['fields']);
		?>
		
		<script type="text/javascript">
			jQuery(document).ready(function() {
				// Prepare global values for the review procedure
				AXIOMTHEMES_GLOBALS['ajax_url']	= "<?php echo admin_url('admin-ajax.php'); ?>";
				AXIOMTHEMES_GLOBALS['ajax_nonce']	= "<?php echo wp_create_nonce('ajax_nonce'); ?>";
			});
		</script>
		
		<?php 
		do_action('axiomthemes_action_post_before_show_meta_box', $post_type, $post->ID);
	
		axiomthemes_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'show_page_layout' => false,
			'override' => $override_key
		));

		foreach ($post_options as $id=>$option) { 
			if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

			$option = apply_filters('axiomthemes_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
			$meta = isset($custom_options[$id]) ? apply_filters('axiomthemes_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) : '';

			do_action('axiomthemes_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

			axiomthemes_options_show_field($id, $option, $meta);

			do_action('axiomthemes_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
		}
	
		axiomthemes_options_page_stop();
		
		do_action('axiomthemes_action_post_after_show_meta_box', $post_type, $post->ID);
	}
}


// Save data from meta box
if (!function_exists('axiomthemes_post_type_save_options')) {
	//add_action('save_post', 'axiomthemes_post_type_save_options');
	function axiomthemes_post_type_save_options($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_post_nonce']) || !wp_verify_nonce($_POST['meta_box_post_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = isset($_POST['meta_box_post_type']) ? $_POST['meta_box_post_type'] : $_POST['post_type'];
		$override_key = axiomthemes_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		global $AXIOMTHEMES_GLOBALS;

		$custom_options = array();

		$post_options = array_merge($AXIOMTHEMES_GLOBALS['options'], $AXIOMTHEMES_GLOBALS['post_meta_box']['fields']);

		if (axiomthemes_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, 'post_custom_options', apply_filters('axiomthemes_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}
	}
}
?>