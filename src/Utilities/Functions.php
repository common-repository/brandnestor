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

namespace Brandnestor\Utilities;

use \Brandnestor\Core\Options;
use \Brandnestor\Core\Plugin;
use \Brandnestor\Core\TemplateManager;

/**
 * Utility functions
 */
class Functions {

	/**
	 * Displays a 404 template page (or a fallback one) and stops all
	 * execution.  Should be used before headers are sent (e.g during
	 * 'wp-init')
	 *
	 * @return void
	 */
	public static function exit_404_page() {
		global $wp_query;

		$wp_query->set_404();
		status_header(404);
		nocache_headers();

		// Display 404 Template
		$templates = array('partials/404.php', 'template-parts/404.php');
		if ($template = locate_template($templates)) {
			try {
				get_header();
				include $template;
				get_footer();
			} finally {
				exit;
			}
		}

		if ($template = get_query_template('404')) {
			try {
				include $template;
			} finally {
				exit;
			}
		}

		// Fallback 404 Page
		header('HTTP/1.0 404 Not Found', true, 404);
		echo '<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL ' . esc_url($_SERVER['REQUEST_URI']) . ' was not found on this server.</p></body></html>';
		exit;
	}

	/**
	 * Checks if the currently logged in user is a super admin. A super admin
	 * is the user who has installed the plugin, or a multisite admin.
	 *
	 * @return bool
	 */
	public static function is_user_super_admin() {
		if (is_multisite() && current_user_can('setup_network')) {
			return true;
		}

		$super_admin = get_option('brandnestor_superadmin');
		if ($super_admin && (int)$super_admin === get_current_user_id()) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $slug
	 *
	 * @return bool
	 */
	public static function is_forbidden_slug($slug) {
		$wp = new \WP;
		$forbidden_slugs = array('wp-login', 'wp-admin', 'wp-includes', 'wp-content');
		return in_array($slug, array_merge($wp->public_query_vars, $wp->private_query_vars, $forbidden_slugs));
	}

	/**
	 * @return void
	 */
	public static function add_rewrite_rules() {
		$options = Options::instance();
		$login_slug = $options->get('login_page_url');
		$login_template_id = $options->get('login_page_template');
		$register_slug = $options->get('register_page_url');
		$register_template_id = $options->get('register_page_template');

		$template_manager = Plugin::instance()->template_manager;
		$cpt = TemplateManager::CPT;

		if ($login_slug && $login_template_id) {
			$template = $template_manager->get_template($login_template_id);

			add_rewrite_rule("^$login_slug", "index.php?$cpt={$template['slug']}", 'top');
		}

		if ($register_slug && $register_template_id) {
			$template = $template_manager->get_template($register_template_id);

			add_rewrite_rule("^$register_slug", "index.php?$cpt={$template['slug']}", 'top');
		}
	}

}
