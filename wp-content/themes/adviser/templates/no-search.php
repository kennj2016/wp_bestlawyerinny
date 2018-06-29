<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_template_no_search_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_template_no_search_theme_setup', 1 );
	function axiomthemes_template_no_search_theme_setup() {
		axiomthemes_add_template(array(
			'layout' => 'no-search',
			'mode'   => 'internal',
			'title'  => __('No search results found', 'axiomthemes'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'axiomthemes_template_no_search_output' ) ) {
	function axiomthemes_template_no_search_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php _e('Search Results for:', 'axiomthemes'); ?></h2>
				<h1 class="post_subtitle"><?php echo get_search_query(); ?></h1>
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'axiomthemes' ); ?></p>
				<p><?php echo sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'axiomthemes'), home_url(), get_bloginfo()); ?>
				<br><?php _e('Please report any broken links to our team.', 'axiomthemes'); ?></p>
				<?php echo do_shortcode('[trx_search open="fixed"]'); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>