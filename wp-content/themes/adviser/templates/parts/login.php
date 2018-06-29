<div id="popup_login" class="popup_wrap popup_login bg_tint_light">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<div class="form_left">
			<form action="<?php echo wp_login_url(); ?>" method="post" name="login_form" class="popup_form login_form">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr(home_url()); ?>">
				<div class="popup_form_field login_field iconed_field icon-user-2"><input type="text" id="log" name="log" value="" placeholder="<?php _e('Login or Email', 'axiomthemes'); ?>"></div>
				<div class="popup_form_field password_field iconed_field icon-lock-1"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('Password', 'axiomthemes'); ?>"></div>
				<div class="popup_form_field remember_field">
					<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" class="forgot_password"><?php _e('Forgot password?', 'axiomthemes'); ?></a>
					<input type="checkbox" value="forever" id="rememberme" name="rememberme">
					<label for="rememberme"><?php _e('Remember me', 'axiomthemes'); ?></label>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php _e('Login', 'axiomthemes'); ?>"></div>
			</form>
		</div>
		<div class="form_right">
			<div class="login_socials_title"><?php _e('You can login using your social profile', 'axiomthemes'); ?></div>
			<div class="login_socials_list sc_socials sc_socials_size_tiny">
				<div class="sc_socials_item">
					<a class="social_icons icon-facebook icons" target="_blank" href="#" title=""></a>
				</div>
				<div class="sc_socials_item">
					<a class="social_icons icon-twitter icons" target="_blank" href="#" title=""></a>
				</div>
				<div class="sc_socials_item">
					<a class="social_icons icon-gplus icons" target="_blank" href="#" title=""></a>
				</div>
			</div>
			<div class="login_socials_problem"><a href="#"><?php _e('Problem with login?', 'axiomthemes'); ?></a></div>
			<div class="result message_block"></div>
		</div>
	</div>	<!-- /.login_wrap -->
</div>		<!-- /.popup_login -->
