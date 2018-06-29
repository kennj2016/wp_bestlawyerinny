<?php
// WP custom header
$header_image = $header_image2 = $header_color = '';
if ($top_panel_style=='dark') {
    if (($header_image = get_header_image()) == '') {
        $header_image = axiomthemes_get_custom_option('top_panel_bg_image');
    }
    if (file_exists(axiomthemes_get_file_dir('skins/'.($theme_skin).'/images/top_bg21.jpg'))) {
        $header_image2 = axiomthemes_get_file_url('skins/'.($theme_skin).'/images/top_bg21.jpg');
    }
    $header_color = axiomthemes_get_link_color(axiomthemes_get_custom_option('top_panel_bg_color'));
}

$header_style = $top_panel_opacity!='transparent' && ($header_image!='' || $header_image2!='' || $header_color!='')
    ? ' style="background: '
    . ($header_image2!='' ? 'url('.esc_url($header_image2).') repeat center bottom' : '')
    . ($header_image!=''  ? ($header_image2!='' ? ',' : '') . 'url('.esc_url($header_image).') repeat center top' : '')
    .'"'
    : '';
?>

<div class="top_panel_fixed_wrap"></div>

<header class="top_panel_wrap bg_tint_<?php echo esc_attr($top_panel_style); ?>" <?php echo ($header_style); ?>>


    <?php if (axiomthemes_get_custom_option('show_menu_user')=='yes') { ?>

        <div class="menu_user_wrap">
            <div class="content_wrap clearfix">
                <div class="menu_user_area menu_user_nav_area">
                    <?php require_once( axiomthemes_get_file_dir('templates/parts/user-panel.php') ); ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="border_bottom_grey font_086em display_none">
        <div class="content_wrap clearfix top_div">
	        <?php if(axiomthemes_get_custom_option('disclaimer')) { ?>
		        <div class="inline bottom top-panel_disclaimer">
			        <?php axiomthemes_show_layout( axiomthemes_get_custom_option('disclaimer') ); ?>
		        </div>
	        <?php
	        }
	        if (axiomthemes_get_custom_option('show_contact_info')=='yes' && axiomthemes_get_custom_option('contact_info')) { ?>
            <div class="inline bottom side-right">
                    <div class="menu_user_area menu_user_left menu_user_contact_area"><?php axiomthemes_show_layout( force_balance_tags(trim(axiomthemes_get_custom_option('contact_info'))) ); ?></div>
            </div>
	        <?php } ?>
	        <?php if (axiomthemes_get_custom_option('show_search')=='yes'){ ?>
            <div class="inline side-right search_s">
                <?php axiomthemes_show_layout( do_shortcode('[trx_search open="no" title=""]') ); ?>
            </div>
	        <?php } ?>
        </div>
    </div>

    <div class="menu_main_wrap logo_<?php axiomthemes_show_layout( esc_attr(axiomthemes_get_custom_option('logo_align')) ); ?><?php axiomthemes_show_layout($AXIOMTHEMES_GLOBALS['logo_text'] ? ' with_text' : '' ); ?>">
        <div class="content_wrap clearfix display_none">

            <div class="logo">
                <div class="logo_img">
                    <a href="<?php esc_url(home_url()); ?>">
                        <?php axiomthemes_show_layout( !empty($AXIOMTHEMES_GLOBALS['logo_'.($logo_style)]) ? '<img src="'.esc_url($AXIOMTHEMES_GLOBALS['logo_'.($logo_style)]).'" class="logo_main" alt=""><img src="'.esc_url($AXIOMTHEMES_GLOBALS['logo_fixed']).'" class="logo_fixed" alt=""></a></div>' : '' ); ?><div class="contein_logo_text"><a href="<?php home_url()?>">
                                <?php  axiomthemes_show_layout(($AXIOMTHEMES_GLOBALS['logo_text'] ? '<span class="logo_text">'.($AXIOMTHEMES_GLOBALS['logo_text']).'</span>' : ''), 'axiomthemes'); ?>
                                <?php axiomthemes_show_layout($AXIOMTHEMES_GLOBALS['logo_slogan'] ? '<span class="logo_slogan">' . esc_html($AXIOMTHEMES_GLOBALS['logo_slogan']) . '</span>' : ''); ?></a></div>
                </div>
                <div class="inline image side-right marg_top_2em top-panel_blocks">
                    <?php if(axiomthemes_get_custom_option('contact_phone') || axiomthemes_get_custom_option('top_panel_block_email')){ ?>
	                <div class="inline">
                        <div class="inline-wrapper">
                            <div class="side-right marg_null marg_top top-panel_left">
                                <div class="icon_user-top">
                                    <i class="user_top_icon icon-telephone"></i>
                                </div>
                                <h4><?php echo force_balance_tags(axiomthemes_get_custom_option('contact_phone')); ?></h4>
                                <span class="font_086em"><a href="mailto:<?php axiomthemes_show_layout( axiomthemes_get_custom_option('top_panel_block_email') ); ?>"><?php axiomthemes_show_layout( axiomthemes_get_custom_option('top_panel_block_email') ); ?></a></span>
                            </div>
                        </div>
                    </div>
	                <?php }
	                if(axiomthemes_get_custom_option('flower_title') || axiomthemes_get_custom_option('text_under_flower_title')){ ?>
                    <div class="inline pad_left_2em">
                        <div class="inline-wrapper">
                            <div class="side-right marg_null marg_top top-panel_right">
                                <div class="icon_user-top">
                                    <i class="user_top_icon icon-clock-4"></i>
                                </div>
                                <h4><?php axiomthemes_show_layout( axiomthemes_get_custom_option('flower_title') ); ?></h4>
                                <span class="font_086em"><?php axiomthemes_show_layout( axiomthemes_get_custom_option('text_under_flower_title') ); ?></span>
                            </div>
                        </div>
                    </div>
	                <?php } ?>
                </div>
                <a href="#" class="menu_main_responsive_button icon-menu"><?php _e('Menu', 'axiomthemes') ?></a>
            </div>
            <div class="main-menu_wrap_bg">
                <div class="main-menu_wrap_content">
                    <nav role="navigation" class="menu_main_nav_area">
                        <?php
                        if (empty($AXIOMTHEMES_GLOBALS['menu_main'])) $AXIOMTHEMES_GLOBALS['menu_main'] = axiomthemes_get_nav_menu('menu_main');
                        if (empty($AXIOMTHEMES_GLOBALS['menu_main'])) $AXIOMTHEMES_GLOBALS['menu_main'] = axiomthemes_get_nav_menu();
                        echo ($AXIOMTHEMES_GLOBALS['menu_main']);
                        ?>
                    </nav>
                </div>
            </div>
        </div>

</header>
