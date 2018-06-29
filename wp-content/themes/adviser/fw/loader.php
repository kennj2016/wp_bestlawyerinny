<?php
/**
 * Axiomthemes Framework
 *
 * @package themerex
 * @since themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'AXIOMTHEMES_FW_DIR' ) )		define( 'AXIOMTHEMES_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'AXIOMTHEMES_START_TIME' ) )	define( 'AXIOMTHEMES_START_TIME', microtime());			// Framework start time
if ( ! defined( 'AXIOMTHEMES_START_MEMORY' ) )	define( 'AXIOMTHEMES_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $AXIOMTHEMES_GLOBALS;
$AXIOMTHEMES_GLOBALS = array();

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'axiomthemes_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'axiomthemes_loader_theme_setup', 20 );
	function axiomthemes_loader_theme_setup() {
		// Before init theme
		do_action('axiomthemes_action_before_init_theme');

		// Load current values for main theme options
		axiomthemes_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			axiomthemes_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// core.strings must be first - we use axiomthemes_str...() in the axiomthemes_get_file_dir()
// core.files must be first - we use axiomthemes_get_file_dir() to include all rest parts
require_once( (file_exists(get_stylesheet_directory().(AXIOMTHEMES_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(AXIOMTHEMES_FW_DIR).'core/core.strings.php' );
require_once( (file_exists(get_stylesheet_directory().(AXIOMTHEMES_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(AXIOMTHEMES_FW_DIR).'core/core.files.php' );
axiomthemes_autoload_folder( 'core' );

// Include custom theme files
axiomthemes_autoload_folder( 'includes' );

// Include theme templates
axiomthemes_autoload_folder( 'templates' );

// Include theme widgets
axiomthemes_autoload_folder( 'widgets' );
?>