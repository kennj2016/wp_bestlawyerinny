<?php
/* Universal Services support functions
------------------------------------------------------------------------------- */

// Check if Universal Services Plugin installed and activated
if ( !function_exists( 'axiomthemes_exists_universal_services_plugin' ) ) {
    function axiomthemes_exists_universal_services_plugin() {
        return function_exists('axiomthemes_universal_services_plugin');
    }
}
?>