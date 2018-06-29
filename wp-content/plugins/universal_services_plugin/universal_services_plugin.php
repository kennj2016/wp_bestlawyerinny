<?php
/*
  Plugin Name: Universal Services Plugin
  Description: Create additional options in the theme.
  Version: 1.0
  Author: axiomthemes
  Author URI: http://axiomthemes.net
 */

// Universal Services Plugin


// Plugin init
if (!function_exists('axiomthemes_universal_services_plugin')) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_universal_services_plugin', 10 );
    function axiomthemes_universal_services_plugin() {
        return;
    }
}



// Team
// Theme init
if (!function_exists('axiomthemes_team_theme_setup')) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_team_theme_setup' );
    function axiomthemes_team_theme_setup() {

        // Add item in the admin menu
        add_action('admin_menu',							'axiomthemes_team_add_meta_box');

        // Save data from meta box
        add_action('save_post',								'axiomthemes_team_save_data');

        // Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
        add_filter('axiomthemes_filter_get_blog_type',			'axiomthemes_team_get_blog_type', 9, 2);
        add_filter('axiomthemes_filter_get_blog_title',		'axiomthemes_team_get_blog_title', 9, 2);
        add_filter('axiomthemes_filter_get_current_taxonomy',	'axiomthemes_team_get_current_taxonomy', 9, 2);
        add_filter('axiomthemes_filter_is_taxonomy',			'axiomthemes_team_is_taxonomy', 9, 2);
        add_filter('axiomthemes_filter_get_stream_page_title',	'axiomthemes_team_get_stream_page_title', 9, 2);
        add_filter('axiomthemes_filter_get_stream_page_link',	'axiomthemes_team_get_stream_page_link', 9, 2);
        add_filter('axiomthemes_filter_get_stream_page_id',	'axiomthemes_team_get_stream_page_id', 9, 2);
        add_filter('axiomthemes_filter_query_add_filters',		'axiomthemes_team_query_add_filters', 9, 2);
        add_filter('axiomthemes_filter_detect_inheritance_key','axiomthemes_team_detect_inheritance_key', 9, 1);

        // Extra column for team members lists
        if (axiomthemes_get_theme_option('show_overriden_posts')=='yes') {
            add_filter('manage_edit-team_columns',			'axiomthemes_post_add_options_column', 9);
            add_filter('manage_team_posts_custom_column',	'axiomthemes_post_fill_options_column', 9, 2);
        }

        // Meta box fields
        global $AXIOMTHEMES_GLOBALS;
        $AXIOMTHEMES_GLOBALS['team_meta_box'] = array(
            'id' => 'team-meta-box',
            'title' => __('Team Member Details', 'axiomthemes'),
            'page' => 'team',
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                "team_member_position" => array(
                    "title" => __('Position',  'axiomthemes'),
                    "desc" => __("Position of the team member", 'axiomthemes'),
                    "class" => "team_member_position",
                    "std" => "",
                    "type" => "text"),
                "team_member_email" => array(
                    "title" => __("E-mail",  'axiomthemes'),
                    "desc" => __("E-mail of the team member - need to take Gravatar (if registered)", 'axiomthemes'),
                    "class" => "team_member_email",
                    "std" => "",
                    "type" => "text"),
                "team_member_link" => array(
                    "title" => __('Link to profile',  'axiomthemes'),
                    "desc" => __("URL of the team member profile page (if not this page)", 'axiomthemes'),
                    "class" => "team_member_link",
                    "std" => "",
                    "type" => "text"),
                "team_member_socials" => array(
                    "title" => __("Social links",  'axiomthemes'),
                    "desc" => __("Links to the social profiles of the team member", 'axiomthemes'),
                    "class" => "team_member_email",
                    "std" => "",
                    "type" => "social")
            )
        );

        // Prepare type "Team"
        axiomthemes_require_data( 'post_type', 'team', array(
                'label'               => __( 'Team member', 'axiomthemes' ),
                'description'         => __( 'Team Description', 'axiomthemes' ),
                'labels'              => array(
                    'name'                => _x( 'Team', 'Post Type General Name', 'axiomthemes' ),
                    'singular_name'       => _x( 'Team member', 'Post Type Singular Name', 'axiomthemes' ),
                    'menu_name'           => __( 'Team', 'axiomthemes' ),
                    'parent_item_colon'   => __( 'Parent Item:', 'axiomthemes' ),
                    'all_items'           => __( 'All Team', 'axiomthemes' ),
                    'view_item'           => __( 'View Item', 'axiomthemes' ),
                    'add_new_item'        => __( 'Add New Team member', 'axiomthemes' ),
                    'add_new'             => __( 'Add New', 'axiomthemes' ),
                    'edit_item'           => __( 'Edit Item', 'axiomthemes' ),
                    'update_item'         => __( 'Update Item', 'axiomthemes' ),
                    'search_items'        => __( 'Search Item', 'axiomthemes' ),
                    'not_found'           => __( 'Not found', 'axiomthemes' ),
                    'not_found_in_trash'  => __( 'Not found in Trash', 'axiomthemes' ),
                ),
                'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments'),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'menu_icon'			  => 'dashicons-admin-users',
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 25,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'query_var'           => true,
                'capability_type'     => 'page',
                'rewrite'             => true
            )
        );

        // Prepare taxonomy for team
        axiomthemes_require_data( 'taxonomy', 'team_group', array(
                'post_type'			=> array( 'team' ),
                'hierarchical'      => true,
                'labels'            => array(
                    'name'              => _x( 'Team Group', 'taxonomy general name', 'axiomthemes' ),
                    'singular_name'     => _x( 'Group', 'taxonomy singular name', 'axiomthemes' ),
                    'search_items'      => __( 'Search Groups', 'axiomthemes' ),
                    'all_items'         => __( 'All Groups', 'axiomthemes' ),
                    'parent_item'       => __( 'Parent Group', 'axiomthemes' ),
                    'parent_item_colon' => __( 'Parent Group:', 'axiomthemes' ),
                    'edit_item'         => __( 'Edit Group', 'axiomthemes' ),
                    'update_item'       => __( 'Update Group', 'axiomthemes' ),
                    'add_new_item'      => __( 'Add New Group', 'axiomthemes' ),
                    'new_item_name'     => __( 'New Group Name', 'axiomthemes' ),
                    'menu_name'         => __( 'Team Group', 'axiomthemes' ),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'team_group' ),
            )
        );
    }
}

if ( !function_exists( 'axiomthemes_team_settings_theme_setup2' ) ) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_team_settings_theme_setup2', 3 );
    function axiomthemes_team_settings_theme_setup2() {
        // Add post type 'team' and taxonomy 'team_group' into theme inheritance list
        axiomthemes_add_theme_inheritance( array('team' => array(
                'stream_template' => 'team',
                'single_template' => 'single-team',
                'taxonomy' => array('team_group'),
                'taxonomy_tags' => array(),
                'post_type' => array('team'),
                'override' => 'post'
            ) )
        );
    }
}


// Add meta box
if (!function_exists('axiomthemes_team_add_meta_box')) {
    //add_action('admin_menu', 'axiomthemes_team_add_meta_box');
    function axiomthemes_team_add_meta_box() {
        global $AXIOMTHEMES_GLOBALS;
        $mb = $AXIOMTHEMES_GLOBALS['team_meta_box'];
        add_meta_box($mb['id'], $mb['title'], 'axiomthemes_team_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
    }
}

// Callback function to show fields in meta box
if (!function_exists('axiomthemes_team_show_meta_box')) {
    function axiomthemes_team_show_meta_box() {
        global $post, $AXIOMTHEMES_GLOBALS;

        // Use nonce for verification
        $data = get_post_meta($post->ID, 'team_data', true);
        $fields = $AXIOMTHEMES_GLOBALS['team_meta_box']['fields'];
        ?>
        <input type="hidden" name="meta_box_team_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
        <table class="team_area">
            <?php
            foreach ($fields as $id=>$field) {
                $meta = isset($data[$id]) ? $data[$id] : '';
                ?>
                <tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
                    <td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
                    <td>
                        <?php
                        if ($id == 'team_member_socials') {
                            $upload_info = wp_upload_dir();
                            $upload_url = $upload_info['baseurl'];
                            $social_list = axiomthemes_get_theme_option('social_icons');
                            foreach ($social_list as $soc) {
                                $sn = basename($soc['icon']);
                                $sn = axiomthemes_substr($sn, 0, axiomthemes_strrpos($sn, '.'));
                                if (($pos=axiomthemes_strrpos($sn, '_'))!==false)
                                    $sn = axiomthemes_substr($sn, 0, $pos);
                                $link = isset($meta[$sn]) ? $meta[$sn] : '';
                                ?>
                                <label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(axiomthemes_strtoproper($sn)); ?></label><br>
                                <input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
                            <?php
                            }
                        } else {
                            ?>
                            <input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
                        <?php
                        }
                        ?>
                        <br><small><?php echo esc_attr($field['desc']); ?></small>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    <?php
    }
}


// Save data from meta box
if (!function_exists('axiomthemes_team_save_data')) {
    //add_action('save_post', 'axiomthemes_team_save_data');
    function axiomthemes_team_save_data($post_id) {
        // verify nonce
        if (!isset($_POST['meta_box_team_nonce']) || !wp_verify_nonce($_POST['meta_box_team_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        global $AXIOMTHEMES_GLOBALS;

        $data = array();

        $fields = $AXIOMTHEMES_GLOBALS['team_meta_box']['fields'];

        // Post type specific data handling
        foreach ($fields as $id=>$field) {
            if (isset($_POST[$id])) {
                if (is_array($_POST[$id])) {
                    foreach ($_POST[$id] as $sn=>$link) {
                        $_POST[$id][$sn] = stripslashes($link);
                    }
                    $data[$id] = $_POST[$id];
                } else {
                    $data[$id] = stripslashes($_POST[$id]);
                }
            }
        }

        update_post_meta($post_id, 'team_data', $data);
    }
}



// Return true, if current page is team member page
if ( !function_exists( 'axiomthemes_is_team_page' ) ) {
    function axiomthemes_is_team_page() {
        return get_query_var('post_type')=='team' || is_tax('team_group');
    }
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiomthemes_team_detect_inheritance_key' ) ) {
    //add_filter('axiomthemes_filter_detect_inheritance_key',	'axiomthemes_team_detect_inheritance_key', 9, 1);
    function axiomthemes_team_detect_inheritance_key($key) {
        if (!empty($key)) return $key;
        return axiomthemes_is_team_page() ? 'team' : '';
    }
}

// Filter to detect current page slug
if ( !function_exists( 'axiomthemes_team_get_blog_type' ) ) {
    //add_filter('axiomthemes_filter_get_blog_type',	'axiomthemes_team_get_blog_type', 9, 2);
    function axiomthemes_team_get_blog_type($page, $query=null) {
        if (!empty($page)) return $page;
        if ($query && $query->is_tax('team_group') || is_tax('team_group'))
            $page = 'team_category';
        else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
            $page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
        return $page;
    }
}

// Filter to detect current page title
if ( !function_exists( 'axiomthemes_team_get_blog_title' ) ) {
    //add_filter('axiomthemes_filter_get_blog_title',	'axiomthemes_team_get_blog_title', 9, 2);
    function axiomthemes_team_get_blog_title($title, $page) {
        if (!empty($title)) return $title;
        if ( axiomthemes_strpos($page, 'team')!==false ) {
            if ( $page == 'team_category' ) {
                $term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
                $title = $term->name;
            } else if ( $page == 'team_item' ) {
                $title = axiomthemes_get_post_title();
            } else {
                $title = __('All team', 'axiomthemes');
            }
        }

        return $title;
    }
}

// Filter to detect stream page title
if ( !function_exists( 'axiomthemes_team_get_stream_page_title' ) ) {
    //add_filter('axiomthemes_filter_get_stream_page_title',	'axiomthemes_team_get_stream_page_title', 9, 2);
    function axiomthemes_team_get_stream_page_title($title, $page) {
        if (!empty($title)) return $title;
        if (axiomthemes_strpos($page, 'team')!==false) {
            if (($page_id = axiomthemes_team_get_stream_page_id(0, $page)) > 0)
                $title = axiomthemes_get_post_title($page_id);
            else
                $title = __('All team', 'axiomthemes');
        }
        return $title;
    }
}

// Filter to detect stream page ID
if ( !function_exists( 'axiomthemes_team_get_stream_page_id' ) ) {
    //add_filter('axiomthemes_filter_get_stream_page_id',	'axiomthemes_team_get_stream_page_id', 9, 2);
    function axiomthemes_team_get_stream_page_id($id, $page) {
        if (!empty($id)) return $id;
        if (axiomthemes_strpos($page, 'team')!==false) $id = axiomthemes_get_template_page_id('team');
        return $id;
    }
}

// Filter to detect stream page URL
if ( !function_exists( 'axiomthemes_team_get_stream_page_link' ) ) {
    //add_filter('axiomthemes_filter_get_stream_page_link',	'axiomthemes_team_get_stream_page_link', 9, 2);
    function axiomthemes_team_get_stream_page_link($url, $page) {
        if (!empty($url)) return $url;
        if (axiomthemes_strpos($page, 'team')!==false) {
            $id = axiomthemes_get_template_page_id('team');
            if ($id) $url = get_permalink($id);
        }
        return $url;
    }
}

// Filter to detect current taxonomy
if ( !function_exists( 'axiomthemes_team_get_current_taxonomy' ) ) {
    //add_filter('axiomthemes_filter_get_current_taxonomy',	'axiomthemes_team_get_current_taxonomy', 9, 2);
    function axiomthemes_team_get_current_taxonomy($tax, $page) {
        if (!empty($tax)) return $tax;
        if ( axiomthemes_strpos($page, 'team')!==false ) {
            $tax = 'team_group';
        }
        return $tax;
    }
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiomthemes_team_is_taxonomy' ) ) {
    //add_filter('axiomthemes_filter_is_taxonomy',	'axiomthemes_team_is_taxonomy', 9, 2);
    function axiomthemes_team_is_taxonomy($tax, $query=null) {
        if (!empty($tax))
            return $tax;
        else
            return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
    }
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'axiomthemes_team_query_add_filters' ) ) {
    //add_filter('axiomthemes_filter_query_add_filters',	'axiomthemes_team_query_add_filters', 9, 2);
    function axiomthemes_team_query_add_filters($args, $filter) {
        if ($filter == 'team') {
            $args['post_type'] = 'team';
        }
        return $args;
    }
}


/*********************************************************************************************************************/












// Testimonial
// Theme init
if (!function_exists('axiomthemes_testimonial_theme_setup')) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_testimonial_theme_setup' );
    function axiomthemes_testimonial_theme_setup() {

        // Add item in the admin menu
        add_action('admin_menu',			'axiomthemes_testimonial_add_meta_box');

        // Save data from meta box
        add_action('save_post',				'axiomthemes_testimonial_save_data');

        // Meta box fields
        global $AXIOMTHEMES_GLOBALS;
        $AXIOMTHEMES_GLOBALS['testimonial_meta_box'] = array(
            'id' => 'testimonial-meta-box',
            'title' => __('Testimonial Details', 'axiomthemes'),
            'page' => 'testimonial',
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                "testimonial_author" => array(
                    "title" => __('Testimonial author',  'axiomthemes'),
                    "desc" => __("Name of the testimonial's author", 'axiomthemes'),
                    "class" => "testimonial_author",
                    "std" => "",
                    "type" => "text"),
                "testimonial_email" => array(
                    "title" => __("Author's e-mail",  'axiomthemes'),
                    "desc" => __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'axiomthemes'),
                    "class" => "testimonial_email",
                    "std" => "",
                    "type" => "text"),
                "testimonial_link" => array(
                    "title" => __('Testimonial link',  'axiomthemes'),
                    "desc" => __("URL of the testimonial source or author profile page", 'axiomthemes'),
                    "class" => "testimonial_link",
                    "std" => "",
                    "type" => "text")
            )
        );

        // Prepare type "Testimonial"
        axiomthemes_require_data( 'post_type', 'testimonial', array(
                'label'               => __( 'Testimonial', 'axiomthemes' ),
                'description'         => __( 'Testimonial Description', 'axiomthemes' ),
                'labels'              => array(
                    'name'                => _x( 'Testimonials', 'Post Type General Name', 'axiomthemes' ),
                    'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'axiomthemes' ),
                    'menu_name'           => __( 'Testimonials', 'axiomthemes' ),
                    'parent_item_colon'   => __( 'Parent Item:', 'axiomthemes' ),
                    'all_items'           => __( 'All Testimonials', 'axiomthemes' ),
                    'view_item'           => __( 'View Item', 'axiomthemes' ),
                    'add_new_item'        => __( 'Add New Testimonial', 'axiomthemes' ),
                    'add_new'             => __( 'Add New', 'axiomthemes' ),
                    'edit_item'           => __( 'Edit Item', 'axiomthemes' ),
                    'update_item'         => __( 'Update Item', 'axiomthemes' ),
                    'search_items'        => __( 'Search Item', 'axiomthemes' ),
                    'not_found'           => __( 'Not found', 'axiomthemes' ),
                    'not_found_in_trash'  => __( 'Not found in Trash', 'axiomthemes' ),
                ),
                'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
                'hierarchical'        => false,
                'public'              => false,
                'show_ui'             => true,
                'menu_icon'			  => 'dashicons-cloud',
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 25,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'capability_type'     => 'page',
            )
        );

        // Prepare taxonomy for testimonial
        axiomthemes_require_data( 'taxonomy', 'testimonial_group', array(
                'post_type'			=> array( 'testimonial' ),
                'hierarchical'      => true,
                'labels'            => array(
                    'name'              => _x( 'Testimonials Group', 'taxonomy general name', 'axiomthemes' ),
                    'singular_name'     => _x( 'Group', 'taxonomy singular name', 'axiomthemes' ),
                    'search_items'      => __( 'Search Groups', 'axiomthemes' ),
                    'all_items'         => __( 'All Groups', 'axiomthemes' ),
                    'parent_item'       => __( 'Parent Group', 'axiomthemes' ),
                    'parent_item_colon' => __( 'Parent Group:', 'axiomthemes' ),
                    'edit_item'         => __( 'Edit Group', 'axiomthemes' ),
                    'update_item'       => __( 'Update Group', 'axiomthemes' ),
                    'add_new_item'      => __( 'Add New Group', 'axiomthemes' ),
                    'new_item_name'     => __( 'New Group Name', 'axiomthemes' ),
                    'menu_name'         => __( 'Testimonial Group', 'axiomthemes' ),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'testimonial_group' ),
            )
        );
    }
}


// Add meta box
if (!function_exists('axiomthemes_testimonial_add_meta_box')) {
    //add_action('admin_menu', 'axiomthemes_testimonial_add_meta_box');
    function axiomthemes_testimonial_add_meta_box() {
        global $AXIOMTHEMES_GLOBALS;
        $mb = $AXIOMTHEMES_GLOBALS['testimonial_meta_box'];
        add_meta_box($mb['id'], $mb['title'], 'axiomthemes_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
    }
}

// Callback function to show fields in meta box
if (!function_exists('axiomthemes_testimonial_show_meta_box')) {
    function axiomthemes_testimonial_show_meta_box() {
        global $post, $AXIOMTHEMES_GLOBALS;

        // Use nonce for verification
        echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

        $data = get_post_meta($post->ID, 'testimonial_data', true);

        $fields = $AXIOMTHEMES_GLOBALS['testimonial_meta_box']['fields'];
        ?>
        <table class="testimonial_area">
            <?php
            foreach ($fields as $id=>$field) {
                $meta = isset($data[$id]) ? $data[$id] : '';
                ?>
                <tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
                    <td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
                    <td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
                        <br><small><?php echo esc_attr($field['desc']); ?></small></td>
                </tr>
            <?php
            }
            ?>
        </table>
    <?php
    }
}


// Save data from meta box
if (!function_exists('axiomthemes_testimonial_save_data')) {
    //add_action('save_post', 'axiomthemes_testimonial_save_data');
    function axiomthemes_testimonial_save_data($post_id) {
        // verify nonce
        if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        global $AXIOMTHEMES_GLOBALS;

        $data = array();

        $fields = $AXIOMTHEMES_GLOBALS['testimonial_meta_box']['fields'];

        // Post type specific data handling
        foreach ($fields as $id=>$field) {
            if (isset($_POST[$id]))
                $data[$id] = stripslashes($_POST[$id]);
        }

        update_post_meta($post_id, 'testimonial_data', $data);
    }
}



/*********************************************************************************************************************/









// attachment manipulations
// Theme init
if ( !function_exists( 'axiomthemes_attachment_settings_theme_setup2' ) ) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_attachment_settings_theme_setup2', 3 );
    function axiomthemes_attachment_settings_theme_setup2() {
        axiomthemes_add_theme_inheritance( array('attachment' => array(
                'stream_template' => '',
                'single_template' => 'attachment',
                'taxonomy' => array(),
                'taxonomy_tags' => array(),
                'post_type' => array('attachment'),
                'override' => 'post'
            ) )
        );
    }
}

if (!function_exists('axiomthemes_attachment_theme_setup')) {
    add_action( 'axiomthemes_action_before_init_theme', 'axiomthemes_attachment_theme_setup');
    function axiomthemes_attachment_theme_setup() {

        // Add folders in ajax query
        add_filter('ajax_query_attachments_args',				'axiomthemes_attachment_ajax_query_args');

        // Add folders in filters for js view
        add_filter('media_view_settings',						'axiomthemes_attachment_view_filters');

        // Add folders list in js view compat area
        add_filter('attachment_fields_to_edit',					'axiomthemes_attachment_view_compat');

        // Prepare media folders for save
        add_filter( 'attachment_fields_to_save',				'axiomthemes_attachment_save_compat');

        // Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
        add_filter('axiomthemes_filter_detect_inheritance_key',	'axiomthemes_attachmnent_detect_inheritance_key', 9, 1);

        // Prepare taxonomy for attachment
        axiomthemes_require_data( 'taxonomy', 'media_folder', array(
                'post_type'			=> array( 'attachment' ),
                'hierarchical' 		=> true,
                'labels' 			=> array(
                    'name'              => __('Media Folders', 'axiomthemes'),
                    'singular_name'     => __('Media Folder', 'axiomthemes'),
                    'search_items'      => __('Search Media Folders', 'axiomthemes'),
                    'all_items'         => __('All Media Folders', 'axiomthemes'),
                    'parent_item'       => __('Parent Media Folder', 'axiomthemes'),
                    'parent_item_colon' => __('Parent Media Folder:', 'axiomthemes'),
                    'edit_item'         => __('Edit Media Folder', 'axiomthemes'),
                    'update_item'       => __('Update Media Folder', 'axiomthemes'),
                    'add_new_item'      => __('Add New Media Folder', 'axiomthemes'),
                    'new_item_name'     => __('New Media Folder Name', 'axiomthemes'),
                    'menu_name'         => __('Media Folders', 'axiomthemes'),
                ),
                'query_var'			=> true,
                'rewrite' 			=> true,
                'show_admin_column'	=> true
            )
        );
    }
}


// Add folders in ajax query
if (!function_exists('axiomthemes_attachment_ajax_query_args')) {
    //add_filter('ajax_query_attachments_args', 'axiomthemes_attachment_ajax_query_args');
    function axiomthemes_attachment_ajax_query_args($query) {
        if (isset($query['post_mime_type'])) {
            $v = $query['post_mime_type'];
            if (axiomthemes_substr($v, 0, 13)=='media_folder.') {
                unset($query['post_mime_type']);
                if (axiomthemes_strlen($v) > 13)
                    $query['media_folder'] = axiomthemes_substr($v, 13);
                else {
                    $list_ids = array();
                    $terms = axiomthemes_get_terms_by_taxonomy('media_folder');
                    if (count($terms) > 0) {
                        foreach ($terms as $term) {
                            $list_ids[] = $term->term_id;
                        }
                    }
                    if (count($list_ids) > 0) {
                        $query['tax_query'] = array(
                            array(
                                'taxonomy' => 'media_folder',
                                'field' => 'id',
                                'terms' => $list_ids,
                                'operator' => 'NOT IN'
                            )
                        );
                    }
                }
            }
        }
        return $query;
    }
}

// Add folders in filters for js view
if (!function_exists('axiomthemes_attachment_view_filters')) {
    //add_filter('media_view_settings', 'axiomthemes_attachment_view_filters');
    function axiomthemes_attachment_view_filters($settings, $post=null) {
        $taxes = array('media_folder');
        foreach ($taxes as $tax) {
            $terms = axiomthemes_get_terms_by_taxonomy($tax);
            if (count($terms) > 0) {
                $settings['mimeTypes'][$tax.'.'] = __('Media without folders', 'axiomthemes');
                $settings['mimeTypes'] = array_merge($settings['mimeTypes'], axiomthemes_get_terms_hierarchical_list($terms, array(
                        'prefix_key' => 'media_folder.',
                        'prefix_level' => '-'
                    )
                ));
            }
        }
        return $settings;
    }
}

// Add folders list in js view compat area
if (!function_exists('axiomthemes_attachment_view_compat')) {
    //add_filter('attachment_fields_to_edit', 'axiomthemes_attachment_view_compat');
    function axiomthemes_attachment_view_compat($form_fields, $post=null) {
        static $terms = null, $id = 0;
        if (isset($form_fields['media_folder'])) {
            $field = $form_fields['media_folder'];
            if (!$terms) {
                $terms = axiomthemes_get_terms_by_taxonomy('media_folder', array(
                    'hide_empty' => false
                ));
                $terms = axiomthemes_get_terms_hierarchical_list($terms, array(
                    'prefix_key' => 'media_folder.',
                    'prefix_level' => '-'
                ));
            }
            $values = array_map('trim', explode(',', $field['value']));
            $readonly = ''; //! $user_can_edit && ! empty( $field['taxonomy'] ) ? " readonly='readonly' " : '';
            $required = !empty($field['required']) ? '<span class="alignright"><abbr title="required" class="required">*</abbr></span>' : '';
            $aria_required = !empty($field['required']) ? " aria-required='true' " : '';
            $html = '';
            if (count($terms) > 0) {
                foreach ($terms as $slug=>$name) {
                    $id++;
                    $slug = axiomthemes_substr($slug, 13);
                    $html .= ($html ? '<br />' : '') . '<input type="checkbox" class="text" id="media_folder_'.esc_attr($id).'" name="media_folder_' . esc_attr($slug) . '" value="' . esc_attr( $slug ) . '"' . (in_array($slug, $values) ? ' checked="checked"' : '' ) . ' ' . ($readonly) . ' ' . ($aria_required) . ' /><label for="media_folder_'.esc_attr($id).'"> ' . ($name) . '</label>';
                }
            }
            $form_fields['media_folder']['input'] = 'media_folder_input';
            $form_fields['media_folder']['media_folder_input'] = '<div class="media_folder_selector">' . ($html) . '</div>';
        }
        return $form_fields;
    }
}

// Prepare media folders for save
if (!function_exists('axiomthemes_attachment_save_compat')) {
    //add_filter( 'attachment_fields_to_save', 'axiomthemes_attachment_save_compat');
    function axiomthemes_attachment_save_compat($post=null, $attachment_data=null) {
        if (!empty($post['ID']) && ($id = intval($post['ID'])) > 0) {
            $folders = array();
            $from_media_library = !empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder']);
            // From AJAX query
            if (!$from_media_library) {
                foreach ($_REQUEST as $k => $v) {
                    if (axiomthemes_substr($k, 0, 12)=='media_folder')
                        $folders[] = $v;
                }
            } else {
                if (count($folders)==0) {
                    if (!empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder'])) {
                        foreach ($_REQUEST['tax_input']['media_folder'] as $k => $v) {
                            if ((int)$v > 0)
                                $folders[] = $v;
                        }
                    }
                }
            }
            if (count($folders) > 0) {
                foreach ($folders as $k=>$v) {
                    if ((int) $v > 0) {
                        $term = get_term_by('id', $v, 'media_folder');
                        $folders[$k] = $term->slug;
                    }
                }
            } else
                $folders = null;
            // Save folders list only from AJAX
            if (!$from_media_library)
                wp_set_object_terms( $id, $folders, 'media_folder', false );
        }
        return $post;
    }
}


// Filter to detect current page inheritance key
if ( !function_exists( 'axiomthemes_attachmnent_detect_inheritance_key' ) ) {
    //add_filter('axiomthemes_filter_detect_inheritance_key',	'axiomthemes_attachmnent_detect_inheritance_key', 9, 1);
    function axiomthemes_attachmnent_detect_inheritance_key($key) {
        if (!empty($key)) return $key;
        return is_attachment() ? 'attachment' : '';
    }
}



?>
