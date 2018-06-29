<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_template_related_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_template_related_theme_setup', 1 );
	function axiomthemes_template_related_theme_setup() {
		axiomthemes_add_template(array(
			'layout' => 'related',
			'mode'   => 'blogger',
			'need_columns' => true,
			'title'  => __('Related posts /no columns/', 'axiomthemes'),
			'thumb_title'  => __('Medium image (crop)', 'axiomthemes'),
			'w'		 => 400,
			'h'		 => 225
		));
		axiomthemes_add_template(array(
			'layout' => 'related_2',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => __('Related posts /2 columns/', 'axiomthemes'),
			'thumb_title'  => __('Large image (crop)', 'axiomthemes'),
			'w'		 => 750,
			'h'		 => 422
		));
		axiomthemes_add_template(array(
			'layout' => 'related_3',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => __('Related posts /3 columns/', 'axiomthemes'),
			'thumb_title'  => __('Medium image (crop)', 'axiomthemes'),
			'w'		 => 400,
			'h'		 => 225
		));
		axiomthemes_add_template(array(
			'layout' => 'related_4',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => __('Related posts /4 columns/', 'axiomthemes'),
			'thumb_title'  => __('Small image (crop)', 'axiomthemes'),
			'w'		 => 250,
			'h'		 => 141
		));
	}
}

// Template output
if ( !function_exists( 'axiomthemes_template_related_output' ) ) {
	function axiomthemes_template_related_output($post_options, $post_data) {
		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(4, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		$tag = axiomthemes_sc_in_shortcode_blogger(true) ? 'div' : 'article';
		//require(axiomthemes_get_file_dir('templates/parts/reviews-summary.php'));
		if ($columns > 1) {
			?>
			<div class="<?php echo 'column-1_'.esc_attr($columns); ?> column_padding_bottom">
			<?php
		}
		?>
		<<?php echo ($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['number']); ?>">

			<div class="post_content">
				<?php if ($post_data['post_video'] || $post_data['post_thumb'] || $post_data['post_gallery']) { ?>
				<div class="post_featured">
					<?php require(axiomthemes_get_file_dir('templates/parts/post-featured.php')); ?>
				</div>
				<?php } ?>

				<?php if ($show_title) { ?>
					<div class="post_content_wrap">
					<?php if (!isset($post_options['links']) || $post_options['links']) { ?>
						<h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h4>
					<?php } else { ?>
						<h4 class="post_title"><?php echo ($post_data['post_title']); ?></h4>
					<?php }
					//echo ($reviews_summary);
					?>
					</div>
				<?php } ?>
			</div>	<!-- /.post_content -->
		</<?php echo ($tag); ?>>	<!-- /.post_item -->
		<?php
		if ($columns > 1) {
			?>
			</div>
			<?php
		}
	}
}
?>