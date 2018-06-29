<?php
/**
 * Axiomthemes Framework: messages subsystem
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiomthemes_messages_theme_setup')) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_messages_theme_setup' );
	function axiomthemes_messages_theme_setup() {
		// Core messages strings
		add_action('axiomthemes_action_add_scripts_inline', 'axiomthemes_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('axiomthemes_get_error_msg')) {
	function axiomthemes_get_error_msg() {
		global $AXIOMTHEMES_GLOBALS;
		return !empty($AXIOMTHEMES_GLOBALS['error_msg']) ? $AXIOMTHEMES_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('axiomthemes_set_error_msg')) {
	function axiomthemes_set_error_msg($msg) {
		global $AXIOMTHEMES_GLOBALS;
		$msg2 = axiomthemes_get_error_msg();
		$AXIOMTHEMES_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('axiomthemes_get_success_msg')) {
	function axiomthemes_get_success_msg() {
		global $AXIOMTHEMES_GLOBALS;
		return !empty($AXIOMTHEMES_GLOBALS['success_msg']) ? $AXIOMTHEMES_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('axiomthemes_set_success_msg')) {
	function axiomthemes_set_success_msg($msg) {
		global $AXIOMTHEMES_GLOBALS;
		$msg2 = axiomthemes_get_success_msg();
		$AXIOMTHEMES_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('axiomthemes_get_notice_msg')) {
	function axiomthemes_get_notice_msg() {
		global $AXIOMTHEMES_GLOBALS;
		return !empty($AXIOMTHEMES_GLOBALS['notice_msg']) ? $AXIOMTHEMES_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('axiomthemes_set_notice_msg')) {
	function axiomthemes_set_notice_msg($msg) {
		global $AXIOMTHEMES_GLOBALS;
		$msg2 = axiomthemes_get_notice_msg();
		$AXIOMTHEMES_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('axiomthemes_set_system_message')) {
	function axiomthemes_set_system_message($msg, $status='info', $hdr='') {
		update_option('axiomthemes_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('axiomthemes_get_system_message')) {
	function axiomthemes_get_system_message($del=false) {
		$msg = get_option('axiomthemes_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			axiomthemes_del_system_message();
		return $msg;
	}
}

if (!function_exists('axiomthemes_del_system_message')) {
	function axiomthemes_del_system_message() {
		delete_option('axiomthemes_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('axiomthemes_messages_add_scripts_inline')) {
	function axiomthemes_messages_add_scripts_inline() {
		global $AXIOMTHEMES_GLOBALS;
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			// Strings for translation
			. 'AXIOMTHEMES_GLOBALS["strings"] = {'
				. 'bookmark_add: 		"' . addslashes(__('Add the bookmark', 'axiomthemes')) . '",'
				. 'bookmark_added:		"' . addslashes(__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'axiomthemes')) . '",'
				. 'bookmark_del: 		"' . addslashes(__('Delete this bookmark', 'axiomthemes')) . '",'
				. 'bookmark_title:		"' . addslashes(__('Enter bookmark title', 'axiomthemes')) . '",'
				. 'bookmark_exists:		"' . addslashes(__('Current page already exists in the bookmarks list', 'axiomthemes')) . '",'
				. 'search_error:		"' . addslashes(__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'axiomthemes')) . '",'
				. 'email_confirm:		"' . addslashes(__('On the e-mail address <b>%s</b> we sent a confirmation email.<br>Please, open it and click on the link.', 'axiomthemes')) . '",'
				. 'reviews_vote:		"' . addslashes(__('Thanks for your vote! New average rating is:', 'axiomthemes')) . '",'
				. 'reviews_error:		"' . addslashes(__('Error saving your vote! Please, try again later.', 'axiomthemes')) . '",'
				. 'error_like:			"' . addslashes(__('Error saving your like! Please, try again later.', 'axiomthemes')) . '",'
				. 'error_global:		"' . addslashes(__('Global error text', 'axiomthemes')) . '",'
				. 'name_empty:			"' . addslashes(__('The name can\'t be empty', 'axiomthemes')) . '",'
				. 'name_long:			"' . addslashes(__('Too long name', 'axiomthemes')) . '",'
				. 'email_empty:			"' . addslashes(__('Too short (or empty) email address', 'axiomthemes')) . '",'
				. 'email_long:			"' . addslashes(__('Too long email address', 'axiomthemes')) . '",'
				. 'email_not_valid:		"' . addslashes(__('Invalid email address', 'axiomthemes')) . '",'
				. 'subject_empty:		"' . addslashes(__('The subject can\'t be empty', 'axiomthemes')) . '",'
				. 'subject_long:		"' . addslashes(__('Too long subject', 'axiomthemes')) . '",'
				. 'text_empty:			"' . addslashes(__('The message text can\'t be empty', 'axiomthemes')) . '",'
				. 'text_long:			"' . addslashes(__('Too long message text', 'axiomthemes')) . '",'
				. 'send_complete:		"' . addslashes(__("Send message complete!", 'axiomthemes')) . '",'
				. 'send_error:			"' . addslashes(__('Transmit failed!', 'axiomthemes')) . '",'
				. 'login_empty:			"' . addslashes(__('The Login field can\'t be empty', 'axiomthemes')) . '",'
				. 'login_long:			"' . addslashes(__('Too long login field', 'axiomthemes')) . '",'
				. 'login_success:		"' . addslashes(__('Login success! The page will be reloaded in 3 sec.', 'axiomthemes')) . '",'
				. 'login_failed:		"' . addslashes(__('Login failed!', 'axiomthemes')) . '",'
				. 'password_empty:		"' . addslashes(__('The password can\'t be empty and shorter then 4 characters', 'axiomthemes')) . '",'
				. 'password_long:		"' . addslashes(__('Too long password', 'axiomthemes')) . '",'
				. 'password_not_equal:	"' . addslashes(__('The passwords in both fields are not equal', 'axiomthemes')) . '",'
				. 'registration_success:"' . addslashes(__('Registration success! Please log in!', 'axiomthemes')) . '",'
				. 'registration_failed:	"' . addslashes(__('Registration failed!', 'axiomthemes')) . '",'
				. 'geocode_error:		"' . addslashes(__('Geocode was not successful for the following reason:', 'axiomthemes')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(__('Google map API not available!', 'axiomthemes')) . '",'
				. 'editor_save_success:	"' . addslashes(__("Post content saved!", 'axiomthemes')) . '",'
				. 'editor_save_error:	"' . addslashes(__("Error saving post data!", 'axiomthemes')) . '",'
				. 'editor_delete_post:	"' . addslashes(__("You really want to delete the current post?", 'axiomthemes')) . '",'
				. 'editor_delete_post_header:"' . addslashes(__("Delete post", 'axiomthemes')) . '",'
				. 'editor_delete_success:	"' . addslashes(__("Post deleted!", 'axiomthemes')) . '",'
				. 'editor_delete_error:		"' . addslashes(__("Error deleting post!", 'axiomthemes')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(__('Cancel', 'axiomthemes')) . '",'
				. 'editor_caption_close:	"' . addslashes(__('Close', 'axiomthemes')) . '"'
				. '};'
			. '});'
			. '</script>';
	}
}
?>