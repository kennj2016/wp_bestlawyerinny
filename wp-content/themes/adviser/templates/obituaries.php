<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiomthemes_template_obituaries_theme_setup' ) ) {
	add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_template_obituaries_theme_setup', 1 );
	function axiomthemes_template_obituaries_theme_setup() {
		axiomthemes_add_template(array(
			'layout' => 'obituaries',
			'mode'   => 'blog',
			'title'  => __('Obituaries', 'axiomthemes'),
            'thumb_title'  => __('Small square image (crop)', 'axiomthemes'),
            'w'		 => 250,
            'h'		 => 250
		));
        axiomthemes_add_template(array(
            'layout' => 'obituaries_3',
            'template' => 'obituaries',
            'mode'   => 'blog',
            'title'  => __('Obituaries tile /3 columns/', 'axiomthemes'),
            'thumb_title'  => __('Small square image (crop)', 'axiomthemes'),
            'w'		 => 250,
            'h'		 => 250
        ));
	}
}

// Template output
if ( !function_exists( 'axiomthemes_template_obituaries_output' ) ) {
	function axiomthemes_template_obituaries_output($post_options, $post_data) {

		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
        $parts = explode('_', $post_options['layout']);
        $columns = max(1, min(4, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		$tag = axiomthemes_sc_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<<?php echo ($tag); ?> <?php post_class('post_item post_item_obituaries column-1_'.$columns.' post_featured_' . esc_attr($post_options['post_class']) . ' post_format_'.esc_attr($post_data['post_format']) . ($post_options['number']%2==0 ? ' even' : ' odd') . ($post_options['number']==0 ? ' first' : '') . ($post_options['number']==$post_options['posts_on_page']? ' last' : '') . ($post_options['add_view_more'] ? ' viewmore' : '')); ?>>
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}

			if ($show_title && $post_options['location'] == 'center' && !empty($post_data['post_title'])) {
				?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo ($post_data['post_title']); ?></a></h3><?php
			}
			
			if (!$post_data['post_protected'] && (!empty($post_options['dedicated']) || $post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio'])) {
				?>
				<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
					echo ($post_options['dedicated']);
				} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio']) {
					require(axiomthemes_get_file_dir('templates/parts/post-featured.php'));
				}
				?>
				</div>
			<?php
			}
			?>
	
			<div class="post_content clearfix">
                <div class="obituaries_title">
				<?php
				if ($show_title && $post_options['location'] != 'center' && !empty($post_data['post_title'])) {
					?><h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo ($post_data['post_title']); ?></a></h4><?php
				}
				
				if (!$post_data['post_protected'] && $post_options['info']) {
                    $info_parts = array(
                        'author' => false,
                        'terms' => false,
                        'counters' => false,
                    );
					require(axiomthemes_get_file_dir('templates/parts/post-info.php'));
				}
				?>
		        </div>
				<div class="post_descr clearfix">
				<?php
					if ($post_data['post_protected']) {
						echo ($post_data['post_excerpt']);
					} else {
						if ($post_data['post_excerpt']) {
							echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(axiomthemes_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : axiomthemes_get_custom_option('post_excerpt_maxlength'))).'</p>';
						}
					}
					if (empty($post_options['readmore'])) $post_options['readmore'] = __('READ MORE', 'axiomthemes');
					if (!axiomthemes_sc_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
						echo '<a href="'.esc_url($post_data['post_link']).'">Read life story</a>';
					}
				?>
				</div>

			</div>	<!-- /.post_content -->

		</<?php echo ($tag); ?>>	<!-- /.post_item -->

	<?php
        if($columns == 1) {
            echo do_shortcode('[trx_line top="2em"][/trx_line]');
        }
	}
}
?>