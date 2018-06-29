<?php
/* Royal Slider support functions
------------------------------------------------------------------------------- */

// Check if Royal Slider installed and activated
if ( !function_exists( 'axiomthemes_exists_royalslider' ) ) {
	function axiomthemes_exists_royalslider() {
		return class_exists("NewRoyalSliderMain");
		//return is_plugin_active('new-royalslider/newroyalslider.php');
	}
}
?>