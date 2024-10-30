<?php

/*
 * BrandNestor
 * Copyright (C) 2021-2022  Nikos Papadakis <nikos@papadakis.xyz>
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
 * Apply filters to the register form's messages array.
 *
 * - array $messages the messages that will show above the register form.
 *
 * @since 1.0.0
 */
$messages = apply_filters('brandnestor/register_messages', $messages);

/**
 * BrandNestor form response errors.
 *
 * - array $messages the error messages that will show above the form.
 *
 * @since 1.0.0
 */
$errors = apply_filters('brandnestor/errors', $errors);

/**
 * Apply filters to the register form's error messages array.
 *
 * - array $errors the error messages that will show above the register form.
 *
 * @since 1.0.0
 */
$errors = apply_filters('brandnestor/register_errors', $errors);

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

<form method="post">
	<div class="brandnestor-form-group">
		<label for="user_login"><?php echo wp_kses_post($settings['label_username']) ?></label>
		<input id="user_login" type="text" class="form-control" name="user_login" required />
	</div>
	<div class="brandnestor-form-group">
		<label for="user_email"><?php echo wp_kses_post($settings['label_email']) ?></label>
		<input id="user_email" type="email" class="form-control" name="user_email" required />
	</div>
	<?php if ($settings['show_firstname']): ?>
	<div class="brandnestor-form-group">
		<label for="user_firstname"><?php echo wp_kses_post($settings['label_firstname']) ?></label>
		<input id="user_firstname" type="text" class="form-control" name="user_firstname" required />
	</div>
	<?php endif ?>
	<?php if ($settings['show_lastname']): ?>
	<div class="brandnestor-form-group">
		<label for="user_lastname"><?php echo wp_kses_post($settings['label_lastname']) ?></label>
		<input id="user_lastname" type="text" class="form-control" name="user_lastname" required />
	</div>
	<?php endif ?>
	<div class="brandnestor-form-group">
	<?php
		/** This hook is documented in wp-login.php */
		do_action('register_form');

		/**
		 * Fires before the submit button for the BrandNestor register form.
		 *
		 * @since 1.1.0
		 */
		do_action('brandnestor/register_form');
	?>
	</div>
	<p class="brandnestor-form-group"><?php esc_html_e('Registration confirmation will be emailed to you.') ?></p>
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
	<p class="brandnestor-form-group">
		<a href="<?php echo esc_url(wp_login_url()) ?>"><?php esc_html_e('Log in') ?></a>
	</p>
	<input type="hidden" name="brandnestor_action" value="register" />
	<?php echo wp_nonce_field('brandnestor-register') ?>
</form>

</div><!--wrapper-->
