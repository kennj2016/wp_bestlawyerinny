<?php
/*
Template Name: Blog streampage
*/

global $AXIOMTHEMES_GLOBALS;
$AXIOMTHEMES_GLOBALS['blog_streampage'] = true;

get_header(); 

global $wp_query, $post;

$blog_style 	= axiomthemes_get_custom_option('blog_style');
$blog_columns	= max(1, (int) axiomthemes_substr($blog_style, -1));
$show_sidebar 	= axiomthemes_get_custom_option('show_sidebar_main');
$show_filters 	= axiomthemes_get_custom_option('show_filters');
$ppp			= (int) axiomthemes_get_custom_option('posts_per_page');
$hover			= axiomthemes_get_custom_option('hover_style');
if (empty($hover)) $hover = 'square effect_shift';
$hover_dir		= axiomthemes_get_custom_option('hover_dir');
if (empty($hover_dir)) $hover_dir = 'left_to_right';

$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

$args = $wp_query->query_vars;
$args['post_status'] = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish';

if ( is_page() || !empty($AXIOMTHEMES_GLOBALS['blog_filters']) ) {
	unset($args['p']);
	unset($args['page_id']);
	unset($args['pagename']);
	unset($args['name']);
	$args['posts_per_page'] = $ppp;
	if ($page_number > 1) {
		$args['paged'] = $page_number;
		$args['ignore_sticky_posts'] = true;
	}
	$args = axiomthemes_query_add_sort_order($args);
	$args = axiomthemes_query_add_filters($args, !empty($AXIOMTHEMES_GLOBALS['blog_filters']) ? $AXIOMTHEMES_GLOBALS['blog_filters'] : '');
	query_posts( $args );
}

$per_page = count($wp_query->posts);
$post_number = 0;
$parent_tax_id = (int) axiomthemes_get_custom_option('taxonomy_id');
$flt_ids = array();

$container = apply_filters('axiomthemes_filter_blog_container', axiomthemes_get_template_property($blog_style, 'container'), array('style'=>$blog_style, 'dir'=>'horizontal'));
$container_start = $container_end = '';
if (!empty($container)) {
	$container = explode('%s', $container);
	$container_start = !empty($container[0]) ? $container[0] : '';
	$container_end = !empty($container[1]) ? $container[1] : '';
}

echo ($container_start);

if (axiomthemes_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) {
	?>
	<div class="columns_wrap">
	<?php
}

if (axiomthemes_get_template_property($blog_style, 'need_isotope')) {
	if (!axiomthemes_sc_param_is_off($show_filters)) {
		?>
		<div class="isotope_filters"></div>
		<?php
	}
	?>
	<div class="isotope_wrap <?php echo esc_attr(axiomthemes_get_template_property($blog_style, 'container_classes')); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
	<?php
}
if ($blog_style == 'obituaries') {
    echo do_shortcode('[vc_row][vc_column width="1/1" el_class="obituaries"][trx_section dedicated="" align="none" columns="none" pan="" scroll="" scroll_dir="horizontal" scroll_controls="horizontal" bg_tint="none" font_weight="inherit" animation="none"][trx_title type="2" style="regular" align="center" font_weight="inherit" fig_border="fig_border" icon="inherit" image="none" image_size="small" position="top" animation="none" top="0"]Search the Obituaries[/trx_title][trx_block dedicated="" bottom="2.8em" align="none" columns="none" pan="" scroll="" scroll_dir="horizontal" scroll_controls="horizontal" bg_tint="none" bg_image="332" font_weight="inherit" animation="none"][trx_search style="regular" title="Enter name" ajax="" animation="none"][/trx_block][/trx_section][/vc_column][/vc_row]');
}

while ( have_posts() ) { the_post(); 
	$post_number++;
	$post_args = array(
		'layout' => $blog_style,
		'number' => $post_number,
		'add_view_more' => false,
		'posts_on_page' => $per_page,
		'columns_count' => $blog_columns,
		// Get post data
		'strip_teaser' => false,
		'content' => axiomthemes_get_template_property($blog_style, 'need_content'),
		'terms_list' => !axiomthemes_sc_param_is_off($show_filters) || axiomthemes_get_template_property($blog_style, 'need_terms'),
		'parent_tax_id' => $parent_tax_id,
		'descr' => axiomthemes_get_custom_option('post_excerpt_maxlength'.($blog_columns > 1 ? '_masonry' : '')),
		'sidebar' => !axiomthemes_sc_param_is_off($show_sidebar),
		'filters' => $show_filters != 'hide' ? $show_filters : '',
		'hover' => $hover,
		'hover_dir' => $hover_dir
	);

	$post_data = axiomthemes_get_post_data($post_args);

	axiomthemes_show_post_layout($post_args, $post_data);

	if ($show_filters=='tags') {					// Use tags as filter items
		if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
			foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
				$flt_ids[$tag->term_id] = $tag->name;
			}
		}
	}
}

if (axiomthemes_get_template_property($blog_style, 'need_isotope')) {
	?>
	</div> <!-- /.isotope_wrap -->
	<?php 
}

if (axiomthemes_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) {
	?>
	</div> <!-- /.columns_wrap -->
	<?php
}

echo ($container_end);

if (!$post_number) { 
	if ( is_search() ) {
		axiomthemes_show_post_layout( array('layout' => 'no-search'), false );
	} else {
		axiomthemes_show_post_layout( array('layout' => 'no-articles'), false );
	}
} else {
	// Isotope filters list
	$filters = '';
	$filter_button_classes = 'isotope_filters_button';
    if ($show_filters == 'categories') {			// Use categories as filter items
		$taxonomy = axiomthemes_is_taxonomy();
		$cur_term = $taxonomy ? axiomthemes_get_current_term($taxonomy) : 0;
        $cur_term_id = $cur_term ? $cur_term->term_id : 0;
		$portfolio_parent = $cur_term ? max(0, axiomthemes_get_parent_taxonomy_by_property($cur_term_id, 'show_filters', 'yes', true, $taxonomy)) : 0;
		$args2 = array(
			'type'			=> !empty($args['post_type']) ? $args['post_type'] : 'post',
			'child_of'		=> $portfolio_parent,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $taxonomy,
			'pad_counts'	=> false
		);
		$portfolio_list = get_categories($args2);


		if (count($portfolio_list) > 0) {
			$filters .= '<a href="#" data-filter="*" class="'.esc_attr($filter_button_classes . ($portfolio_parent==$cur_term_id ? ' active' : '')) . '">' . __('All', 'axiomthemes').'</a>';
            if ($cat) {

                foreach ($portfolio_list as $cat) {
				$filters .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="'.esc_attr($filter_button_classes . ($cat->term_id==$cur_term_id ? ' active' : '')).'">'.($cat->name).'</a>';
			}
            }

		}


	} else if ($show_filters == 'tags') {																	// Use tags as filter items
		if (count($flt_ids) > 0) {
			$filters .= '<a href="#" data-filter="*" class="'.esc_attr($filter_button_classes).' active">'.__('All', 'axiomthemes').'</a>';
			foreach ($flt_ids as $flt_id=>$flt_name) {
				$filters .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="'.esc_attr($filter_button_classes).'">'.($flt_name).'</a>';
			}
		}
	}
	if ($filters) {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				AXIOMTHEMES_GLOBALS['ppp'] = <?php echo intval($ppp); ?>;
				jQuery(".isotope_filters").append('<?php echo ($filters); ?>');
			});
		</script>
		<?php
	}
}

if ($post_number > 0) {
	// Pagination
	$pagination = axiomthemes_get_custom_option('blog_pagination');
	if (in_array($pagination, array('viewmore', 'infinite'))) {
		if ($page_number < $wp_query->max_num_pages) {
			?>
			<div id="viewmore" class="pagination_wrap pagination_<?php echo esc_attr($pagination); ?>">
				<a href="#" id="viewmore_link" class="theme_button viewmore_button"><span class="icon-spin3 animate-spin viewmore_loading"></span><span class="viewmore_text_1"><?php _e('LOAD MORE', 'axiomthemes'); ?></span><span class="viewmore_text_2"><?php _e('Loading ...', 'axiomthemes'); ?></span></a>
				<span class="viewmore_loader"></span>
				<script type="text/javascript">
					jQuery(document).ready(function () {
						AXIOMTHEMES_GLOBALS['viewmore_page'] = <?php echo intval($page_number); ?>;
						AXIOMTHEMES_GLOBALS['viewmore_data'] = '<?php echo str_replace("'", "\\'", serialize($args)); ?>';
						AXIOMTHEMES_GLOBALS['viewmore_vars'] = '<?php echo str_replace("'", "\\'", serialize(array(
							'blog_style' => $blog_style,
							'columns_count' => $blog_columns,
							'parent_tax_id' => $parent_tax_id,
							'show_sidebar' => $show_sidebar,
							'filters' => $show_filters!='hide' ? $show_filters : '',
							'hover' => $hover,
							'hover_dir' => $hover_dir,
							'ppp' => $ppp
						))); ?>';
					});
				</script>
			</div>
			<?php
		}
	} else {
		axiomthemes_show_pagination(array(
			'class' => 'pagination_wrap pagination_'.esc_attr(axiomthemes_get_theme_option('blog_pagination_style')),
			'style' => axiomthemes_get_theme_option('blog_pagination_style'),
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => axiomthemes_get_theme_option('blog_pagination_style')=='pages' ? 10 : 20
			)
		);
	}
}

// Add template specific scripts and styles
do_action('axiomthemes_action_blog_scripts', $blog_style);

// Restore main WP query
wp_reset_postdata();

get_footer();
?>