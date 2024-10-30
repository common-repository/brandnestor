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

namespace Brandnestor\Front\Models;

class LoginModel {

	/** @var string */
	public $action;
	/** @var array<mixed> */
	public $settings;
	/** @var array<string> */
	public $messages = array();
	/** @var array<string> */
	public $errors = array();
	/** @var string */
	public $rp_key;

	/**
	 * @param array<mixed> $settings
	 */
	public function __construct($settings) {
		$this->action = isset($_GET['brandnestor_action'])
			? sanitize_key($_GET['brandnestor_action'])
			: '';

		// Set default settings
		$this->settings = array(
			'label_login'               => __('Username or Email Address'),
			'label_password'            => __('Password'),
			'label_remember'            => __('Remember Me'),
			'label_button'              => __('Submit', 'brandnestor'),
			'label_new_password_button' => __('Get New Password', 'brandnestor'),
			'show_lost_password'        => true,
			'show_register'             => false,
		);

		if ($settings) {
			$this->settings = array_replace($this->settings, $settings);
		}

		$rp_cookie = 'brandnestor-resetpass' . COOKIEHASH;
		if (isset($_COOKIE[$rp_cookie])) {
			list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);
			$this->rp_key = $rp_key;
		}

		if (isset($_GET['loggedout'])) {
			$this->messages[] =  __('You are now logged out.', 'brandnestor');
		}

		if (isset($_GET['registration']) && $_GET['registration'] === 'disabled') {
			$this->errors[] = __('User registration is currently disabled.', 'brandnestor');
		}

		if (isset($_GET['registration']) && $_GET['registration'] === 'complete') {
			$this->messages[] = __('Your password has been reset. You can now log in with the form below.', 'brandnestor');
		}

		if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'registered') {
			/* translators: %s login page URL */
			$this->messages[] = sprintf(__('Registration complete. Please check your email, then visit the <a href="%s">login page</a>.', 'brandnestor'), wp_login_url());
		}

		if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm') {
			/* translators: %s register page URL */
			$this->messages[] = sprintf(__('Check your email for the confirmation link, then visit the <a href="%s">login page</a>.', 'brandnestor'), wp_login_url());
		}

		if (isset($_GET['rperror']) && $_GET['rperror'] === 'expiredkey') {
			$this->errors[] = __('Your password reset link has expired. Please request a new link below.', 'brandnestor');
		}

		if (isset($_GET['rperror']) && $_GET['rperror'] === 'invalidkey') {
			$this->errors[] = __('Your password reset link appears to be invalid. Please request a new link below.', 'brandnestor');
		}
	}

}
