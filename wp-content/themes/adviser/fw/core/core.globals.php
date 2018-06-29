<?php
/**
 * Axiomthemes Framework: global variables storage
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('axiomthemes_get_global')) {
	function axiomthemes_get_global($var_name) {
		global $AXIOMTHEMES_GLOBALS;
		return isset($AXIOMTHEMES_GLOBALS[$var_name]) ? $AXIOMTHEMES_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('axiomthemes_set_global')) {
	function axiomthemes_set_global($var_name, $value) {
		global $AXIOMTHEMES_GLOBALS;
		$AXIOMTHEMES_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('axiomthemes_inc_global')) {
	function axiomthemes_inc_global($var_name, $value=1) {
		global $AXIOMTHEMES_GLOBALS;
		$AXIOMTHEMES_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('axiomthemes_concat_global')) {
	function axiomthemes_concat_global($var_name, $value) {
		global $AXIOMTHEMES_GLOBALS;
		$AXIOMTHEMES_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('axiomthemes_get_global_array')) {
	function axiomthemes_get_global_array($var_name, $key) {
		global $AXIOMTHEMES_GLOBALS;
		return isset($AXIOMTHEMES_GLOBALS[$var_name][$key]) ? $AXIOMTHEMES_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('axiomthemes_set_global_array')) {
	function axiomthemes_set_global_array($var_name, $key, $value) {
		global $AXIOMTHEMES_GLOBALS;
		if (!isset($AXIOMTHEMES_GLOBALS[$var_name])) $AXIOMTHEMES_GLOBALS[$var_name] = array();
		$AXIOMTHEMES_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('axiomthemes_inc_global_array')) {
	function axiomthemes_inc_global_array($var_name, $key, $value=1) {
		global $AXIOMTHEMES_GLOBALS;
		$AXIOMTHEMES_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('axiomthemes_concat_global_array')) {
	function axiomthemes_concat_global_array($var_name, $key, $value) {
		global $AXIOMTHEMES_GLOBALS;
		$AXIOMTHEMES_GLOBALS[$var_name][$key] .= $value;
	}
}
?>