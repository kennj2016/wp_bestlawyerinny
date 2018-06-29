<?php
/* Calculator (calculated-fields-form) support functions
------------------------------------------------------------------------------- */

// Check if Calculated Fields Form installed and activated
if ( !function_exists( 'axiomthemes_exists_calculator' ) ) {
	function axiomthemes_exists_calculator() {
		return function_exists('cp_calculatedfieldsf_get_site_url');
	}
}
?>