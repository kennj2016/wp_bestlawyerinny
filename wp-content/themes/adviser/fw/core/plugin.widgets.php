<?php
/**
 * Axiomthemes Framework: Widgets detection
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Check if specified widget installed and activated
if (!function_exists('axiomthemes_widget_is_active')) {
	function axiomthemes_widget_is_active($slug) {
		if (!function_exists('is_plugin_inactive')) { 
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); 
		}
		return !is_plugin_inactive("{$slug}.php");
	}
}


// Check if Instagram widget installed and activated
if (!function_exists('axiomthemes_exists_instagram')) {
	function axiomthemes_exists_instagram($slug) {
		return axiomthemes_widget_is_active('wp-instagram-widget/wp-instagram-widget');
	}
}

?>