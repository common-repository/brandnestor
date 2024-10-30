<?php

/*
 * BrandNestor
 * Copyright (C) 2021-2023  Nikos Papadakis <nikos@papadakis.xyz>
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

use Brandnestor\Admin\Admin;
use Brandnestor\Front\Front;
use Brandnestor\Utilities\Functions;
use Brandnestor\Utilities\SingletonTrait;

final class Plugin {

	use SingletonTrait;

	/** @var string */
	private $plugin_name = '';
	/** @var string */
	private $plugin_version = '1.0.0';
	/** @var \Brandnestor\Admin\Admin $admin */
	public $admin;
	/** @var \Brandnestor\Front\Front $front */
	public $front;
	/** @var \Brandnestor\Core\TemplateManager $template_manager */
	public $template_manager;

	/**
	 * @param string $plugin_name
	 * @param string $plugin_version
	 *
	 * @return void
	 */
	public function init($plugin_name, $plugin_version) {
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;

		$options = Options::instance();
		$options->register_option(DefaultOptions::class);

		/**
		 * Register a new BrandNestor Option object using the Options manager
		 *
		 * @since 2.2.0
		 *
		 * @param \BrandNestor\Core\Options $options The option manager
		 */
		do_action('brandnestor/registered_options', $options);

		$admin = new Admin($this->plugin_name);
		$front = new Front($this->plugin_name);

		/**
		 * Filters the BrandNestor Admin Controller
		 *
		 * @since 2.2.0
		 *
		 * @param \Brandnestor\Admin\Admin $admin The Admin Controller
		 */
		$this->admin = apply_filters('brandnestor/admin', $admin);

		/**
		 * Filters the BrandNestor Front Controller
		 *
		 * @since 2.2.0
		 *
		 * @param \Brandnestor\Front\Front $front The Front Controller
		 */
		$this->front = apply_filters('brandnestor/front', $front);

		$this->define_admin_hooks();
		$this->define_front_hooks();
		$this->register_shortcodes();

		register_activation_hook(BRANDNESTOR_FILE, array($this, 'activate'));
		register_deactivation_hook(BRANDNESTOR_FILE, array($this, 'deactivate'));

		add_action('plugins_loaded', function() {
			ElementorManager::instance()->register();
		});

		add_action('init', function() {
			load_plugin_textdomain('brandnestor', false, dirname(BRANDNESTOR_BASE) . '/languages');

			$this->front->register_assets();
			$this->admin->register_assets();
			$this->template_manager = new TemplateManager();

			Blocks::register_blocks();

			Functions::add_rewrite_rules();
		});

		add_action('activated_plugin', function() {
			Cache::instance()->delete();
		});

		add_action('deactivated_plugin', function() {
			Cache::instance()->delete();
		});
	}

	/**
	 * @return void
	 */
	private function define_admin_hooks() {
		add_action('admin_bar_menu', array($this->admin, 'admin_bar_menu'), 11);

		if (is_admin()) {
			add_action('admin_footer', array($this->admin, 'print_scripts'), 99);
			add_action('admin_footer_text', array($this->admin, 'admin_footer_text'), 99);
			add_action('admin_head', array($this->admin, 'print_styles'));
			add_action('admin_init', array($this->admin, 'admin_init'));
			add_action('admin_menu', array($this->admin, 'admin_menu'), 9);
			add_action('plugin_action_links_' . BRANDNESTOR_BASE, array($this->admin, 'plugin_action_links'));
			add_action('wp_dashboard_setup', array($this->admin, 'wp_dashboard_setup'), 99);
			add_action('admin_bar_menu', array($this->admin, 'cache_bar_menu'), 1000);
			add_action('admin_bar_menu', array($this->admin, 'hide_bar_menu'), 2000);

			add_filter('admin_title', array($this->admin, 'admin_title'));
			add_filter('update_footer', array($this->admin, 'update_footer'), 99);
		}
	}

	/**
	 * @return void
	 */
	private function define_front_hooks() {
		add_action('init', array($this->front, 'init'));
		add_action('login_footer', array($this->front, 'login_footer'));
		add_action('login_init', array($this->front, 'login_init'));
		add_action('wp_footer', array($this->front, 'print_scripts'));
		add_action('wp_head', array($this->front, 'print_styles'));

		add_filter('login_headertext', array($this->front, 'login_headertext'), 20);
		add_filter('login_headerurl', array($this->front, 'login_headerurl'), 20);
		add_filter('login_title', array($this->front, 'login_title'), 20, 2);
		add_filter('login_url', array($this->front, 'login_url'), 20, 3);
		add_filter('logout_url', array($this->front, 'logout_url'), 20, 2);
		add_filter('register_url', array($this->front, 'register_url'), 20);
		add_filter('rest_authentication_errors', array($this->front, 'rest_authentication_errors'));
		add_filter('retrieve_password_message', array($this->front, 'retrieve_password_message'), 99);
		add_filter('show_admin_bar', array($this->front, 'show_admin_bar'), 99);
		add_filter('wp_new_user_notification_email', array($this->front, 'wp_new_user_notification_email'), 99);
		add_filter('wp_redirect', array($this->front, 'wp_redirect'), 0);
	}

	/**
	 * @return void
	 */
	protected function register_shortcodes() {
		add_shortcode('brandnestor_login', function ($atts) {
			wp_enqueue_style('brandnestor-base');
			wp_enqueue_script('brandnestor-login');

			$view = new \Brandnestor\Core\View(BRANDNESTOR_DIR . 'includes/login-form.php');
			return $view->view(new \Brandnestor\Front\Models\LoginModel($atts));
		});

		add_shortcode('brandnestor_register', function ($atts) {
			wp_enqueue_style('brandnestor-base');

			$view = new \Brandnestor\Core\View(BRANDNESTOR_DIR . 'includes/register-form.php');
			return $view->view(new \Brandnestor\Front\Models\RegisterModel($atts));
		});
	}

	/**
	 * Executes on plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		// Set the user who's installing the plugin to the super admin.
		$current_user_id = get_current_user_id();
		if ($current_user_id !== 0 && get_option('brandnestor_superadmin') === false) {
			update_option('brandnestor_superadmin', $current_user_id);
		}
	}

	/**
	 * Executes on plugin deactivation.
	 *
	 * @return void
	 *
	 * @since 2.0.0
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function get_name() {
		return $this->plugin_name;
	}

	/**
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function get_version() {
		return $this->plugin_version;
	}

}
