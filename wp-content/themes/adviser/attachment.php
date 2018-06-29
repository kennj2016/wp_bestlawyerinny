<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move axiomthemes_set_post_views to the javascript - counter will work under cache system
	if (axiomthemes_get_custom_option('use_ajax_views_counter')=='no') {
		axiomthemes_set_post_views(get_the_ID());
	}

	axiomthemes_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !axiomthemes_sc_param_is_off(axiomthemes_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>