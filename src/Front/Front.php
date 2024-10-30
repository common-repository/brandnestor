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

namespace Brandnestor\Front;

use Brandnestor\Utilities\BaseController;
use Brandnestor\Utilities\Functions;

class Front extends BaseController {

	/** @var string */
	protected $plugin_name = '';
	/** @var \Brandnestor\Core\Options<\Brandnestor\Utilities\Option> */
	protected $options;

	/**
	 * @param string $plugin_name
	 */
	public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;
		$this->options = \Brandnestor\Core\Options::instance();

		if ($this->options->get('disable_wp_login') === 'on') {
			remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
		}
	}

	/**
	 * @return void
	 */
	public function register_assets() {
		wp_register_style('brandnestor-base', BRANDNESTOR_URL . 'assets/css/build/base-style.css', array(), BRANDNESTOR_VERSION);
		wp_register_script('brandnestor-login', BRANDNESTOR_URL . 'assets/js/build/login.js', array('jquery', 'wp-i18n', 'password-strength-meter'), BRANDNESTOR_VERSION);
	}

	/**
	 * @return void
	 */
	public function init() {
		if (isset($_REQUEST['brandnestor_action'])) {
			$request_handler = new \Brandnestor\Core\RequestHandler();
			$request_handler->handle();
		}
	}

	/**
	 * @return void
	 */
	public function login_footer() {
		$logo = $this->options->get('wp_login_logo');
		if (!empty($logo)) {
			echo '<style type="text/css">#login h1 a {background-image: url(' . esc_url($logo) . ')}';
		}
	}

	/**
	 * @return void
	 */
	public function login_init() {
		if ($this->options->get('disable_wp_login') === 'on') {
			// Enables `Two Factor` plugin compatibility.
			$action = isset($_REQUEST['action'])
				? sanitize_key($_REQUEST['action'])
				: '';

			if ($action === 'validate_2fa') {
				do_action('login_form_validate_2fa');
			}

			if ($action === 'backup_2fa') {
				do_action('login_form_backup_2fa');
			}

			Functions::exit_404_page();
		}
	}

	/**
	 * @param string $location
	 *
	 * @return string
	 */
	public function wp_redirect($location) {
		global $current_user;
		// Only for not logged in users
		if ((!$current_user || $current_user->ID == 0) && $this->options->get('disable_wp_admin_redirect') === 'on') {
			$redirect = explode('redirect_to=', $location);
			if (isset($redirect[1])) {
				$redirect_to = urldecode($redirect[1]);
				if (strpos($redirect_to, '/wp-admin/')) {
					Functions::exit_404_page();
				}
			}
		}

		return $location;
	}

	/**
	 * @param string $login_header_text
	 *
	 * @return string
	 */
	public function login_headertext($login_header_text) {
		$brand_name = $this->options->get('brand_name');

		if (!empty($brand_name)) {
			$login_header_text = $brand_name;
		}

		return $login_header_text;
	}

	/**
	 * @param string $login_header_url
	 *
	 * @return string
	 */
	public function login_headerurl($login_header_url) {
		$login_logo = $this->options->get('wp_login_logo');

		if (!empty($login_logo)) {
			$login_header_url = get_bloginfo('url');
		}

		return $login_header_url;
	}

	/**
	 * @param string $login_title
	 * @param string $title
	 *
	 * @return string
	 */
	public function login_title($login_title, $title) {
		$brand_name = $this->options->get('brand_name');

		if (!empty($brand_name)) {
			$login_title = $title . ' ‹ ' . get_bloginfo('name') . ' — ' . $brand_name;
		}

		return $login_title;
	}

	/**
	 * @param string $login_url
	 * @param string $redirect
	 * @param bool $force_reauth
	 *
	 * @return string
	 */
	public function login_url($login_url, $redirect, $force_reauth) {
		$new_login_template = $this->options->get('login_page_template');
		$new_login_slug = $this->options->get('login_page_url');

		$args = array();
		if (!empty($redirect)) {
			$args['redirect_to'] = urlencode($redirect);
		}
		if ($force_reauth) {
			$args['reauth'] = '1';
		}

		if ($new_login_template && $new_login_slug) {
			$new_login_url = esc_url_raw(site_url($new_login_slug));
		} else {
			return $login_url;
		}

		$new_login_url = add_query_arg($args, $new_login_url);
		return $new_login_url;
	}

	/**
	 * @param string $logout_url
	 * @param string $redirect
	 *
	 * @return string
	 */
	public function logout_url($logout_url, $redirect) {
		// Use the custom Login Page as the logout page.
		$logout_url = $this->login_url($logout_url, $redirect, false);
		$logout_url = add_query_arg(array('brandnestor_action' => 'logout'), $logout_url);
		$logout_url = wp_nonce_url($logout_url, 'log-out');
		return $logout_url;
	}

	/**
	 * @param string $register
	 *
	 * @return string
	 */
	public function register_url($register) {
		$new_register_template = $this->options->get('register_page_template');
		$new_register_slug = $this->options->get('register_page_url');

		if ($new_register_template && $new_register_slug) {
			return site_url($new_register_slug);
		} else {
			return $register;
		}
	}

	/**
	 * @param \WP_Error|null|true $errors
	 *
	 * @return \WP_Error|null|true
	 */
	public function rest_authentication_errors($errors) {
		if ($errors === true || is_wp_error($errors)) {
			return $errors;
		}

		if ($this->options->get('disable_rest_api') == 'on' && !is_user_logged_in()) {
			return new \WP_Error(
				'rest_not_logged_in',
				__('You are currently not logged in.', 'brandnestor'),
				array('status' => 401)
			);
		}

		return $errors;
	}

	/**
	 * Filters the login URL link of the retrieve password email message sent
	 * to the new user when they send a reset password request.
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public function retrieve_password_message($message) {
		if ($this->options->get('login_page_url') || $this->options->get('login_page')) {
			$url = add_query_arg('brandnestor_action', 'reset_password_request', wp_login_url());
			$message = preg_replace('@https?://.*action=rp(.*)?@', $url . '\1', $message);
		}

		return $message;
	}

	/**
	 * Shows or hides the admin bar menu on the front end based on the user's
	 * role.
	 *
	 * @return bool
	 */
	public function show_admin_bar() {
		return \Brandnestor\Admin\AdminMenus::is_admin_bar_visible($this->options->get('admin_bar'));
	}

	/**
	 * Filters the contents of the new user notification email sent to the new
	 * user.
	 *
	 * @param array<mixed> $email
	 *
	 * @return array<mixed>
	 */
	public function wp_new_user_notification_email($email) {
		if ($this->options->get('login_page_url') || $this->options->get('login_page')) {
			$url = add_query_arg('brandnestor_action', 'reset_password_request', wp_login_url());
			$email['message'] = preg_replace('@https?://.*action=rp(.*)?@', $url . '\1', $email['message']);
		}

		return $email;
	}

}
