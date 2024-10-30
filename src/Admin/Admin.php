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

namespace Brandnestor\Admin;

use Brandnestor\Core\Cache;
use Brandnestor\Utilities\BaseController;
use Brandnestor\Utilities\Functions;

class Admin extends BaseController {

	/** @var string */
	protected $plugin_name = '';
	/** @var \Brandnestor\Core\Options<\Brandnestor\Utilities\Option> */
	protected $options;
	/** @var \Brandnestor\Admin\Settings\Settings */
	protected $settings;

	/**
	 * @param string $plugin_name
	 */
	public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;
		$this->options = \Brandnestor\Core\Options::instance();
		$this->settings = new \Brandnestor\Admin\Settings\Settings($this->plugin_name);

		if ($this->options->get('hide_help') === 'on') {
			$this->add_style('#contextual-help-link-wrap{display:none !important;}');
		}

		$this->add_style(wp_unslash($this->options->get('admin_css')));
		$this->add_style('.brandnestor-plugins-gopro,a[href="admin.php?page=brandnestor_pro"]{color:#ec8924!important;font-weight:bolder!important}');
	}

	/**
	 * @return void
	 */
	public function register_assets() {
		if (!is_admin()) {
			return;
		}

		// Register block editor assets
		$asset_file = include(BRANDNESTOR_DIR . 'assets/js/build/blocks/blocks.asset.php');
		wp_register_script(
			'brandnestor-block-editor',
			BRANDNESTOR_URL . 'assets/js/build/blocks/blocks.js',
			$asset_file['dependencies'],
			$asset_file['version']
		);

		// Register settings assets
		wp_register_style('brandnestor-settings', BRANDNESTOR_URL . 'assets/css/build/settings.css', array('wp-codemirror'), BRANDNESTOR_VERSION);
		wp_register_style('brandnestor-pro', BRANDNESTOR_URL . 'assets/css/build/pro.css', array(), BRANDNESTOR_VERSION);

		wp_register_script('brandnestor-settings', BRANDNESTOR_URL . 'assets/js/build/settings.js', array('wp-codemirror'), BRANDNESTOR_VERSION);
	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 *
	 * @return void
	 */
	public function admin_bar_menu($wp_admin_bar) {
		// Replace WordPress logo
		$brand_name = $this->options->get('brand_name');
		$branding_logo = $this->options->get('branding_logo');
		$branding_logo_url = $this->options->get('branding_logo_url');
		if (!empty($branding_logo)) {
			$wp_admin_bar->remove_node('wp-logo');
			$wp_admin_bar->add_node(array(
				'id'    => 'brandnestor-logo',
				'title' => '<div style="display:flex;height:100%;align-items:center"><img style="max-height:20px" src="' . esc_url($branding_logo) . '"></div>',
				'href'  => $branding_logo_url,
				'meta'  => array(
					'title'  => $brand_name,
					'target' => '_blank',
				),
			));
		}
	}

	/**
	 * @return string
	 */
	public function admin_footer_text() {
		$footer_text = $this->options->get('footer_text');
		$footer_url = $this->options->get('footer_url');
		$footer_image = $this->options->get('footer_image');

		$footer_html = esc_html($footer_text);

		if (!empty($footer_image)) {
			$footer_html = sprintf('<span style="display:flex;align-items:center;gap:8px"><img src="%s" height="25">%s</span>', esc_url($footer_image), $footer_html);
		}

		if (!empty($footer_url)) {
			$footer_html = sprintf('<a href="%s" target="_blank" style="display:flex;align-items:center;gap:8px">%s</a>', esc_url($footer_url), $footer_html);
		}

		return $footer_html;
	}

	/**
	 * @return void
	 */
	public function admin_init() {
		$this->settings->define_hooks();
		$this->settings->handle_redirects();

		if (!Functions::is_user_super_admin()) {
			AdminMenus::hide_menus();
		}
	}

	/**
	 * @return void
	 */
	public function admin_menu() {
		$this->settings->admin_menu();
	}

	/**
	 * @param array<string> $actions
	 *
	 * @return array<string>
	 */
	public function plugin_action_links($actions) {
		$actions = array(
			sprintf('<a href="%s">' . __('Settings') . '</a>', esc_url(admin_url('admin.php?page=brandnestor'))),
		) + $actions;

		if (!defined('BRANDNESTOR_PRO_FILE')) {
			$actions['pro'] = sprintf(
				'<a class="brandnestor-plugins-gopro" href="%s">' . __('Get Pro', 'brandnestor'),
				admin_url('admin.php?page=brandnestor_pro')
			);
		}

		return $actions;
	}

	/**
	 * @return void
	 */
	public function wp_dashboard_setup() {
		if ($this->options->get('dashboard_panel') === 'on') {
			$dashboard = new Dashboard();
			$dashboard->init();
		}
	}

	/**
	 * @param string $admin_title
	 *
	 * @return string
	 */
	public function admin_title($admin_title) {
		$branding_title = $this->options->get('brand_name');
		if ($branding_title) {
			$admin_title = str_replace('WordPress', $branding_title, $admin_title);
		}
		return $admin_title;
	}

	/**
	 * @param string $update
	 *
	 * @return string|false
	 */
	public function update_footer($update) {
		if ($this->options->get('hide_version') === 'on') {
			return false;
		}

		return $update;
	}

	/**
	 * Save wp_admin_bar to cache. Will be used to build the settings form
	 * fields
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public function cache_bar_menu($wp_admin_bar) {
		$cache = Cache::instance();
		$cache->set('admin_bar_menus', $wp_admin_bar->get_nodes());
	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public function hide_bar_menu($wp_admin_bar) {
		if (!Functions::is_user_super_admin()) {
			AdminMenus::hide_bar_menus($wp_admin_bar);
		}
	}

}
