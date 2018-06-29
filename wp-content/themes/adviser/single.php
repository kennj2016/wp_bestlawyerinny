<?php
/**
Template Name: Single post
 */

get_header(); 

$single_style = axiomthemes_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move axiomthemes_set_post_views to the javascript - counter will work under cache system
	if (axiomthemes_get_custom_option('use_ajax_views_counter')=='no') {
		axiomthemes_set_post_views(get_the_ID());
	}

	//axiomthemes_sc_clear_dedicated_content();
	axiomthemes_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !axiomthemes_sc_param_is_off(axiomthemes_get_custom_option('show_sidebar_main')),
			'content' => axiomthemes_get_template_property($single_style, 'need_content'),
			'terms_list' => axiomthemes_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>