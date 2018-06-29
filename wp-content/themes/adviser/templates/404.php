<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_template_404_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_template_404_theme_setup', 1 );
	function axiomthemes_template_404_theme_setup() {
		axiomthemes_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'axiomthemes_template_404_output' ) ) {
	function axiomthemes_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php _e( '404', 'axiomthemes' ); ?></h1>
				<h4 class="page_subtitle"><?php _e('This Page Could Not Be Found!', 'axiomthemes'); ?></h4>
				<p class="page_description"><?php echo sprintf( __('Can\'t find what you need? Take a moment<br> and do a search below or start from our <a href="%s">homepage</a>.', 'axiomthemes'), home_url() ); ?></p>
				<div class="page_search"><?php echo do_shortcode('[trx_search style="flat" open="fixed" title="'.__('keyword', 'axiomthemes').'"]'); ?></div>
			</div>
		</article>
		<?php
	}
}
?>