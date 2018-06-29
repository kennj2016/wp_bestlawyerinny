<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'axiomthemes_widget_big_banner' );

/**
 * Register our widget.
 */
function axiomthemes_widget_big_banner() {
	register_widget( 'axiomthemes_widget_big_banner' );
}

/**
 * big banner Widget class.
 */
class axiomthemes_widget_big_banner extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_big_banner', 'description' => __('Big banner', 'axiomthemes') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'axiomthemes_widget_big_banner' );

		/* Create the widget. */
		parent::__construct( 'axiomthemes_widget_big_banner', __('Axiomthemes - Big banner', 'axiomthemes'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
        $text = isset($instance['text']) ? $instance['text'] : '';
		$show_form = isset($instance['show_form']) ? $instance['show_form'] : '';
        $bg_image = isset($instance['bg_image']) ? $instance['bg_image'] : '';
		
		
		/* Before widget (defined by themes). */			
		echo ($before_widget);

		/* Display the widget title if one was input (before and after defined by themes). */

		//here will be displayed widget content for Footer 1st column 
		?>
		<div class="big_banner"  style="background-image: url('<?php if ($bg_image != '') esc_attr($bg_image);?>');">
            <h3 class="big_banner_title">
                <?php axiomthemes_show_layout($title);  ?>
            </h3>
            <div class="big_banner_text">

                <?php axiomthemes_show_layout($text); ?>
            </div>

            <?php if($show_form == 1) { ?>

            <div class="big_banner_form">
                <?php echo do_shortcode('[trx_contact_form custom="yes" align="none" description="" animation="none"][trx_form_item type="text" name="email" label_position="left" animation="none" value="Enter Email"][trx_form_item type="button" name="Subscribe" label="Subscribe" label_position="left" animation="none"][/trx_contact_form]');?>
            </div>
            <?php } ?>
		</div>

		<?php
		/* After widget (defined by themes). */
		echo ($after_widget);
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		$instance['show_form'] = (int) $new_instance['show_form'];
        $instance['bg_image'] = strip_tags( $new_instance['bg_image'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'text' => '', 'show_form' => '1', 'bg_image' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 

        $title = isset($instance['title']) ? $instance['title'] : '';
        $text = isset($instance['text']) ? $instance['text'] : '';
        $bg_image = isset($instance['bg_image']) ? $instance['bg_image'] : '';
        $show_form = isset($instance['show_form']) ? $instance['show_form'] : 1;
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'axiomthemes'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php _e('Text', 'axiomthemes'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>" value="<?php echo esc_attr($instance['text']); ?>" style="width:100%;" />
		</p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'bg_image' )); ?>"><?php _e('Background image (url):', 'axiomthemes'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id( 'bg_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'bg_image' )); ?>" value="<?php echo esc_attr($instance['bg_image']); ?>" style="width:100%;" />
        </p>
		<p>
            <label for="<?php echo esc_attr($this->get_field_id('show_form')); ?>_1"><?php _e('Show form:', 'axiomthemes'); ?></label><br />
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('show_form')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_form')); ?>" value="1" <?php echo ($show_form==1 ? ' checked="checked"' : ''); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_form')); ?>_1"><?php _e('Show', 'axiomthemes'); ?></label>
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('show_form')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_form')); ?>" value="0" <?php echo ($show_form==0 ? ' checked="checked"' : ''); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_form')); ?>_0"><?php _e('Hide', 'axiomthemes'); ?></label>
		</p>

	<?php
	}
}
?>