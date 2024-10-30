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

namespace Brandnestor\Core;

use Brandnestor\Core\Options;

class RequestHandler {

	/** @var string */
	private $action;
	/** @var array<string> */
	private $messages = array();
	/** @var array<string> */
	private $errors = array();

	public function __construct() {
		$this->action = sanitize_key($_REQUEST['brandnestor_action']);

		add_filter('brandnestor/messages', array($this, 'form_messages'));
		add_filter('brandnestor/errors', array($this, 'form_errors'));
	}

	/**
	 * @return void
	 */
	public function handle() {
		if (method_exists($this, $this->action)) {
			call_user_func(array($this, $this->action));
		}
	}

	/**
	 * @param array<string> $messages
	 *
	 * @return array<string>
	 */
	public function form_messages($messages) {
		return array_merge($messages, $this->messages);
	}

	/**
	 * @param array<string> $errors
	 *
	 * @return array<string>
	 */
	public function form_errors($errors) {
		return array_merge($errors, $this->errors);
	}

	/**
	 * @param string $code
	 * @param string $error_message
	 *
	 * @return string
	 */
	private function filter_error_message($code, $error_message) {
		// Do not show the invalid username error for security reasons. Instead show a generic error message.
		if ($code === 'incorrect_password' || $code === 'invalid_username') {
			$error_message = __('<strong>Error:</strong> Incorrect username/email or password.', 'brandnestor');
		}

		return $error_message;
	}

	/**
	 * @param \WP_Error $errors
	 *
	 * @return void
	 */
	private function parse_errors($errors) {
		foreach($errors->get_error_codes() as $code) {
			$severity = $errors->get_error_data($code);
			foreach ($errors->get_error_messages($code) as $error_message) {
				$error_message = $this->filter_error_message($code, $error_message);

				if ($severity === 'message') {
					$this->messages[] = $error_message;
				} else {
					$this->errors[] = $error_message;
				}
			}
		}
	}

	/**
	 * @return void
	 */
	private function login() {
		if (!check_admin_referer('brandnestor-login')) {
			return;
		}

		$secure_cookie = is_ssl();

		$redirect_to = Options::instance()->get('login_redirect_to');

		if (empty($redirect_to)) {
			$redirect_to = isset($_GET['redirect_to'])
				? esc_url_raw($_GET['redirect_to'])
				: site_url();
		} else {
			$redirect_to = esc_url_raw(site_url($redirect_to));
		}

		$user = wp_signon(array(), $secure_cookie);

		if (!is_wp_error($user)) {
			wp_safe_redirect($redirect_to);
			exit;
		}

		$this->parse_errors($user);
	}

	/**
	 * @return void
	 */
	private function logout() {
		if (check_admin_referer('log-out')) {
			wp_logout();

			$redirect_to = isset($_GET['redirect_to'])
				? esc_url_raw($_GET['redirect_to'])
				: add_query_arg('loggedout', 'true', wp_login_url());

			wp_safe_redirect($redirect_to);
			exit;
		}
	}

	/**
	 * @return void
	 */
	private function register() {
		if (!check_admin_referer('brandnestor-register')) {
			return;
		}

		if (!get_option('users_can_register')) {
			$redirect_to = add_query_arg('registration', 'disabled', wp_login_url());
			wp_safe_redirect($redirect_to);
			exit;
		}

		$user_login = '';
		$user_email = '';

		if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
			$user_login = sanitize_user($_POST['user_login']);
		}

		if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
			$user_email = sanitize_email($_POST['user_email']);
		}

		$errors = register_new_user($user_login, $user_email);

		if (!is_wp_error($errors)) {
			$user_id =  $errors;

			if (!empty($_POST['user_firstname'])) {
				$lastname = sanitize_text_field($_POST['user_firstname']);
				update_user_meta($user_id, wp_slash('first_name'), wp_slash($lastname));
			}

			if (!empty($_POST['user_lastname'])) {
				$lastname = sanitize_text_field($_POST['user_lastname']);
				update_user_meta($user_id, wp_slash('last_name'), wp_slash($lastname));
			}

			$redirect_to = Options::instance()->get('register_redirect_to');
			$redirect_to = !empty($redirect_to)
				? esc_url_raw(site_url($redirect_to))
				: add_query_arg('checkemail', 'registered', wp_login_url());

			wp_safe_redirect($redirect_to);
			exit;
		}

		$this->parse_errors($errors);
	}

	/**
	 * @return void
	 */
	private function reset_password() {
		if (!check_admin_referer('brandnestor-resetpass')) {
			return;
		}

		$cookie = 'brandnestor-resetpass' . COOKIEHASH;
		$user = false;
		list($path) = explode('?', wp_unslash($_SERVER['REQUEST_URI']));

		if (isset($_COOKIE[$cookie]) && 0 < strpos($_COOKIE[$cookie], ':')) {
			$cookie_value = sanitize_text_field($_COOKIE[$cookie]);
			list($rp_login, $rp_key) = explode(':', $cookie_value);
			$user = check_password_reset_key($rp_key, $rp_login);

			// Cookie and form value mismatch
			if (isset($_POST['pass'])) {
				$sent_rp_key = sanitize_text_field($_POST['rp_key']);
				if (!hash_equals($rp_key, $sent_rp_key)) {
					$user = false;
				}
			}
		}

		if (!$user || is_wp_error($user)) {
			setcookie($cookie, ' ', time() - YEAR_IN_SECONDS, $path, COOKIE_DOMAIN, is_ssl(), true);

			if ($user && $user->get_error_code() === 'expired_key') {
				$redirect_to = add_query_arg(
					array(
						'brandnestor_action' => 'lostpassword',
						'rperror'            => 'expiredkey',
					),
					wp_login_url()
				);
			} else {
				$redirect_to = add_query_arg(
					array(
						'brandnestor_action' => 'lostpassword',
						'rperror'            => 'invalidkey',
					),
					wp_login_url()
				);
			}

			wp_safe_redirect($redirect_to);
			exit;
		}

		$errors = new \WP_Error();

		if (isset($_POST['pass']) && $_POST['pass'] !== $_POST['pass2']) {
			$errors->add('password_mismatch', __('<strong>Error</strong>: The passwords do not match.'));
		}

		/** This hook is documented in wp-login.php */
		do_action('validate_password_reset', $errors, $user);

		if (!$errors->has_errors() && isset($_POST['pass']) && !empty($_POST['pass'])) {
			$pass = sanitize_text_field($_POST['pass']);
			reset_password($user, $pass);
			setcookie($cookie, ' ', time() - YEAR_IN_SECONDS, $path, COOKIE_DOMAIN, is_ssl(), true);

			$redirect_to = add_query_arg('registration', 'complete', wp_login_url());
			wp_safe_redirect($redirect_to);
			exit;
		}

		$this->parse_errors($errors);
	}

	/**
	 * @return void
	 */
	private function reset_password_request() {
		$cookie = 'brandnestor-resetpass' . COOKIEHASH;

		if (isset($_GET['key'])) {
			list($path) = explode('?', wp_unslash($_SERVER['REQUEST_URI']));
			$value = sprintf('%s:%s', sanitize_user($_GET['login']), sanitize_text_field($_GET['key']));
			setcookie($cookie, $value, 0, $path, COOKIE_DOMAIN, is_ssl(), true);
			wp_safe_redirect(remove_query_arg(array('key', 'login')));
			exit;
		}
	}

	/**
	 * @return void
	 */
	private function lost_password() {
		$errors = retrieve_password();

		if (!is_wp_error($errors)) {
			$redirect_to = isset($_POST['redirect_to'])
				? esc_url_raw($_POST['redirect_to'])
				: add_query_arg('checkemail', 'confirm', wp_login_url());

			wp_safe_redirect($redirect_to);
			exit;
		}

		$this->parse_errors($errors);
	}

	/**
	 * @return void
	 */
	private function reset_plugin() {
		if (!check_admin_referer('brandnestor-reset-plugin')) {
			return;
		}

		if (!current_user_can('manage_options')) {
			die('You do not have the permission to do that.');
		}

		$options = Options::instance();
		$options->reset();
		$options->save();

		$redirect_to = admin_url('admin.php?page=brandnestor');
		wp_safe_redirect($redirect_to);
		exit;
	}

	/**
	 * @return void
	 */
	private function import_plugin() {
		$errors = new \WP_Error();

		if (!check_admin_referer('brandnestor-import-plugin')) {
			return;
		}

		if (!current_user_can('manage_options')) {
			die('You do not have the permission to do that.');
		}

		if (!isset($_FILES['import_file'])) {
			return;
		}

		if ($_FILES['import_file']['size'] == 0 || empty($_FILES['import_file']['name'])) {
			return;
		}

		$file = $_FILES['import_file'];
		$filename = esc_attr($file['tmp_name']);
		$allowed_file_type = 'application/json';

		if ($file['type'] !== $allowed_file_type) {
			$errors->add('filetype_not_allowed', __('Import error: The uploaded file type is not allowed.', 'brandnestor'));
			$this->parse_errors($errors);
			return;
		}

		$file_contents = file_get_contents($filename);
		unlink($filename);
		if ($file_contents !== false) {
			$settings = json_decode($file_contents, true);

			if (json_last_error() === JSON_ERROR_NONE) {
				$options = Options::instance();
				$options->set($settings);
				$options->save();

				$errors->add('import_success', __('Settings successfully imported. Please manually check your options, some options were disabled during export for safety reasons.', 'brandnestor'), 'message');
			} else {
				$errors->add('json_decode_error', __('Import error: The upload file is invalid.', 'brandnestor'));
			}
		}

		$this->parse_errors($errors);
	}

	/**
	 * @return void
	 */
	private function export_plugin() {
		if (!check_admin_referer('brandnestor-export-plugin')) {
			return;
		}

		if (!current_user_can('manage_options')) {
			die('You do not have the permission to do that.');
		}

		$options = Options::instance();
		$default_options = $options->get_registered_option(DefaultOptions::class);

		// Unset disabling wp-login and wp-admin so the user borks the site on import.
		$default_options->login_page_url = false;
		$default_options->disable_wp_admin_redirect = 'off';
		$default_options->disable_wp_login = 'off';

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="brandnestor-settings.json"');
		header('Expires: 0');

		$all_options = $options->get_all();

		/**
		 * Filters all BrandNestor options before exporting a JSON file
		 *
		 * @since 2.2.0
		 *
		 * @param array $all_options The options that will be exported
		 */
		$all_options = apply_filters('brandnestor/exported_options', $all_options);

		wp_send_json($all_options);
	}

}
