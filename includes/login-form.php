<?php

/*
 * BrandNestor
 * Copyright (C) 2021  Nikos Papadakis <nikos@papadakis.xyz>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * BrandNestor form response messages.
 *
 * - array $messages the messages that will show above the form.
 *
 * @since 1.0.0
 */
$messages = apply_filters('brandnestor/messages', $messages);

/**
 * Apply filters to the login form's messages array.
 *
 * - array $messages the messages that will show above the login form.
 *
 * @since 1.0.0
 */
$messages = apply_filters('brandnestor/login_messages', $messages);

/**
 * BrandNestor form response errors.
 *
 * - array $messages the error messages that will show above the form.
 *
 * @since 1.0.0
 */
$errors = apply_filters('brandnestor/errors', $errors);

/**
 * Apply filters to the login form's error messages array.
 *
 * - array $errors the error messages that will show above the login form.
 *
 * @since 1.0.0
 */
$errors = apply_filters('brandnestor/login_errors', $errors);

foreach ($messages as $message) {
	echo '<p class="brandnestor-form-message">' . wp_kses_data($message) . '</p>';
}

foreach ($errors as $error) {
	echo '<p class="brandnestor-form-message error">' . wp_kses_data($error) . '</p>';
}

$wrapper_styles = '';
$wrapper_classes = 'wp-block-brandnestor-login';
if (isset($settings['textColor'])) {
	$wrapper_classes .= ' has-' . $settings['textColor'] . '-color';
}
if (isset($settings['backgroundColor'])) {
	$wrapper_classes .= ' has-' . $settings['backgroundColor'] . '-background-color';
}
if (isset($settings['gradient'])) {
	$wrapper_classes .= ' has-' . $settings['gradient'] . '-gradient-background';
}
if (isset($settings['style'])) {
	if (isset($settings['style']['color']['text'])) {
		$wrapper_styles .= 'color:' . $settings['style']['color']['text'] . ';';
	}
	if (isset($settings['style']['color']['background'])) {
		$wrapper_styles .= 'background-color:' . $settings['style']['color']['background'] . ';';
	}
	if (isset($settings['style']['spacing']['padding'])) {
		$wrapper_styles .= 'padding:' . implode(' ', $settings['style']['spacing']['padding']) . ';';
	}
	if (isset($settings['style']['spacing']['margin'])) {
		$wrapper_styles .= 'margin:' . implode(' ', $settings['style']['spacing']['margin']) . ';';
	}
}

$button_classes = 'brandnestor-button';
if (isset($settings['button_hover_animation'])) {
	$button_classes .= ' elementor-animation-' . $settings['button_hover_animation'];
}

$button_group_classes = 'brandnestor-form-group';
if (isset($settings['button_align'])) {
	$button_group_classes .= ' brandnestor-align-' . $settings['button_align'];
}
?>

<div class="<?php echo esc_attr($wrapper_classes) ?>" style="<?php echo esc_attr($wrapper_styles) ?>">

<?php if ($action === 'reset_password_request' && !empty($rp_key)): ?>

<form id="reset_password_form" method="post">
	<div class="brandnestor-form-group">
		<label for="password_1"><?php esc_html_e('New password') ?></label>
		<input id="password_1" type="password" class="form-control" name="pass" required />
		<div class="brandnestor-password-strength"></div>
	</div>
	<div class="brandnestor-form-group">
		<small><?php echo esc_html(wp_get_password_hint()) ?></small>
	</div>
	<div class="brandnestor-form-group">
		<label for="password_2"><?php esc_html_e('Re-enter new password', 'brandnestor') ?></label>
		<input id="password_2" type="password" class="form-control" name="pass2" required />
	</div>
	<div class="<?php echo esc_attr($button_group_classes) ?>">
		<button type="submit" class="<?php echo esc_attr($button_classes) ?>">
			<?php esc_html_e('Confirm new password') ?>
		</button>
	</div>
	<input type="hidden" name="brandnestor_action" value="reset_password" />
	<input type="hidden" name="rp_key" value="<?php echo esc_attr($rp_key) ?>" />
	<?php echo wp_nonce_field('brandnestor-resetpass') ?>
</form>

<?php else: ?>

<form id="login_form" method="post">
	<div class="brandnestor-form-group">
		<label for="username"><?php echo wp_kses_post($settings['label_login']) ?></label>
		<input id="username" type="text" class="form-control" name="log" required />
	</div>
	<div class="brandnestor-form-group">
		<label for="password"><?php echo wp_kses_post($settings['label_password']) ?></label>
		<div class="input-group">
			<input id="password" type="password" class="form-control" id="user_password" name="pwd" required />
			<span class="hide-pw"></span>
		</div>
	</div>
	<div class="brandnestor-form-group">
	<?php
		/** This hook is documented in wp-login.php */
		do_action('login_form');

		/**
		 * Fires after the password field for the BrandNestor login form.
		 *
		 * @since 1.1.0
		 */
		do_action('brandnestor/login_form');
	?>
	</div>
	<div class="brandnestor-form-group">
		<label class="brandnestor-checkbox">
			<input type="checkbox" name="rememberme" />
			<span><?php echo wp_kses_post($settings['label_remember']) ?></span>
		</label>
	</div>
	<div class="<?php echo esc_attr($button_group_classes) ?>">
		<button type="submit" class="<?php echo esc_attr($button_classes) ?>">
			<?php if (!empty($settings['icon_button']['value'])): ?>
			<span class="icon">
				<?php \Elementor\Icons_Manager::render_icon($settings['icon_button'], array('aria-hidden' => 'true')) ?>
			</span>
			<?php endif ?>
			<?php echo wp_kses_post($settings['label_button']) ?>
		</button>
	</div>
	<input type="hidden" name="brandnestor_action" value="login" />
	<?php
	echo wp_nonce_field('brandnestor-login');
	echo '<p class="brandnestor-form-group">';
	echo $settings['show_lost_password'] ? '<a class="brandnestor-toggle-form" href="#">' .  esc_html__('Lost your password?') . '</a>' : '';
	echo $settings['show_register'] ? ' | <a href="' . esc_attr(wp_registration_url()) . '">' . esc_html__('Register') . '</a>' : '';
	echo '</p>';
	?>
</form>

<form id="lost_password_form" method="post" style="display:none">
	<p class="brandnestor-form-message">
		<?php _e('Please enter your username or email address. You will receive an email message with instructions on how to reset your password.') ?>
	</p>
	<div class="brandnestor-form-group">
		<label for="lp_login"><?php echo wp_kses_post($settings['label_login']) ?></label>
		<div class="input-group">
			<input id="lp_login" type="text" class="form-control" name="user_login" required />
		</div>
	</div>
	<div class="brandnestor-form-group">
	<?php
		/** This hook is documented in wp-login.php */
		do_action('lostpassword_form');

		/**
		 * Fires after the login field for the BrandNestor lost password form.
		 *
		 * @since 1.1.0
		 */
		do_action('brandnestor/lostpassword_form');
	?>
	</div>
	<div class="<?php echo esc_attr($button_group_classes) ?>">
		<button type="submit" class="<?php echo esc_attr($button_classes) ?>">
			<?php echo esc_html($settings['label_new_password_button']) ?>
		</button>
	</div>
	<p class="brandnestor-form-group">
		<a class="brandnestor-toggle-form" href="#"><?php esc_html_e('Log in') ?></a>
	</p>
	<input type="hidden" name="brandnestor_action" value="lost_password" />
	<?php echo wp_nonce_field('brandnestor-lost-password') ?>
</form>

<?php endif ?>

</div><!--wrapper-->
