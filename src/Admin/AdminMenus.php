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

use Brandnestor\Core\DefaultOptions;
use \Brandnestor\Core\Cache;
use \Brandnestor\Core\Options;

class AdminMenus {

	/**
	 * Returns the WordPress admin panel menus in a more useful and structured
	 * form.
	 *
	 * @return array<mixed>
	 */
	public static function get_menus() {
		global $menu, $submenu;

		$cache = Cache::instance();
		$menus = $cache->get('admin_menus');

		if ($menus) {
			return $menus;
		}

		$menus = array();

		if (is_array($menu) && count($menu) > 0) {
			foreach ($menu as $i => $item) {
				if ($item[0] === '') {
					continue;
				}

				$name = preg_replace('/(<span.*?>).*?(<\/span>)+/', '', $item[0]);
				$slug = $item[2];

				$menus[$i] = array('slug' => urlencode($slug), 'page' => $item[5], 'label' => $name, 'submenus' => array());

				if (!isset($submenu[$slug])) {
					continue;
				}

				foreach ($submenu[$slug] as $j => $sub_item) {
					$sub_name = preg_replace('/(<span.*?>).*?(<\/span>)+/', '', $sub_item[0]);
					$sub_slug = $sub_item[2];

					$menus[$i]['submenus'][$j] = array('slug' => urlencode($sub_slug), 'label' => $sub_name);
				}
			}
		}

		$cache->set('admin_menus', $menus);
		return $menus;
	}

	/**
	 * Return a parsed WordPress admin bar menu for use on the settings page
	 *
	 * @return array<BarMenuItem>
	 */
	public static function get_bar_menus() {
		$wp_admin_bar_nodes = Cache::instance()->get('admin_bar_menus');
		$menus = array();

		if ($wp_admin_bar_nodes) {
			$menus = self::parse_bar_menu($wp_admin_bar_nodes);
		}

		return $menus;
	}

	/**
	 * @param \WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public static function hide_bar_menus($wp_admin_bar) {
		$current_user = wp_get_current_user();
		if ($current_user->ID == 0) {
			return;
		}

		$menus_rules = Options::instance()->get_registered_option(DefaultOptions::class)->bar_menu_rules;
		$menus = self::get_bar_menus();
		[$role_rules, $user_rules] = self::get_rules_for_user($current_user, $menus_rules);

		if (empty($role_rules) && empty($user_rules)) {
			return;
		}

		foreach ($menus as $menu_item) {
			foreach ($menu_item->get_children() as $submenu_item) {
				if (self::is_menu_hidden($submenu_item->id, $role_rules, $user_rules)) {
					$wp_admin_bar->remove_node($submenu_item->id);
				}
			}

			if (self::is_menu_hidden($menu_item->id, $role_rules, $user_rules)) {
				$wp_admin_bar->remove_node($menu_item->id);
			}
		}
	}

	/**
	 * @return void
	 */
	public static function hide_menus() {
		$current_user = wp_get_current_user();
		if ($current_user->ID == 0) {
			return;
		}

		// NOTE: Backwards compatibility - use the old "admin_menus" option if
		//       it is currently used
		$options = Options::instance()->get_registered_option(DefaultOptions::class);
		$admin_menus_rules = $options->admin_menus;
		[$role_rules, $user_rules] = self::_get_rules_for_user($current_user, $admin_menus_rules);

		if (empty($role_rules) && empty($user_rules)) {
			$admin_menus_rules = $options->admin_menus_rules;
			[$role_rules, $user_rules] = self::get_rules_for_user($current_user, $admin_menus_rules);

			if (empty($role_rules) && empty($user_rules)) {
				return;
			}
		}

		$menus = self::get_menus();

		foreach ($menus as $index => $menu_item) {
			foreach ($menu_item['submenus'] as $sub_index => $submenu_item) {
				// Skip customize.php because it has a redirect URI appended to
				// it which affects it to be always hidden.
				if (strpos($slug, 'customize.php') !== false) {
					continue;
				}

				$slug = $submenu_item['slug'];

				if (self::is_menu_hidden($slug, $role_rules, $user_rules)) {
					remove_submenu_page(urldecode($menu_item['slug']), urldecode($submenu_item['slug']));
				}

				if (isset($role_rules[$slug]) && $role_rules[$slug] !== 'on') {
					self::rename_menu($GLOBALS['submenu'][$menu_item['slug']][$sub_index][0], $submenu_item['label'], $role_rules[$slug]);
				}

				if (isset($user_rules[$slug]) && $user_rules[$slug] !== 'on') {
					self::rename_menu($GLOBALS['submenu'][$menu_item['slug']][$sub_index][0], $submenu_item['label'], $user_rules[$slug]);
				}
			}

			$slug = $menu_item['slug'];

			if (self::is_menu_hidden($slug, $role_rules, $user_rules)) {
				remove_menu_page(urldecode($menu_item['slug']));
			}

			if (isset($role_rules[$slug]) && $role_rules[$slug] !== 'on') {
				self::rename_menu($GLOBALS['menu'][$index][0], $menu_item['label'], $role_rules[$slug]);
			}

			if (isset($user_rules[$slug]) && $user_rules[$slug] !== 'on') {
				self::rename_menu($GLOBALS['menu'][$index][0], $menu_item['label'], $user_rules[$slug]);
			}
		}
	}

	/**
	 * @param array<string> $admin_bar
	 *
	 * @return bool
	 */
	public static function is_admin_bar_visible($admin_bar) {
		if (!is_user_logged_in() && isset($admin_bar['guest']) && $admin_bar['guest'] === 'on') {
			return true;
		}

		foreach ($admin_bar as $role => $visible) {
			if (current_user_can($role) && $visible === 'on') {
				return true;
			}
		}

		return false;
	}

	/**
	 * Backwards compatible interface
	 *
	 * @param \WP_User $user
	 * @param array<array<string>> $rules
	 * @return array{array<string>, array<string>}
	 */
	protected static function _get_rules_for_user($user, $rules) {
		$user_rules = $rules['users'][$user->user_login] ?? array();
		$role_rules = array();
		foreach ($user->roles as $role) {
			if (isset($rules['roles'][$role])) {
				$role_rules = $rules['roles'][$role];
				break;
			}
		}

		return array($role_rules, $user_rules);
	}

	/**
	 * @param \WP_User $user
	 * @param array<array<string>> $rules
	 * @return array{array<string>, array<string>}
	 */
	protected static function get_rules_for_user($user, $rules) {
		$user_rules = array();
		$role_rules = array();

		foreach ($rules as $rule) {
			foreach ($rule['targets'] as $target) {
				[$target_type, $target_value] = explode(':', $target);

				if ($target_type === 'users' && $target_value === $user->user_login) {
					$user_rules += $rule['fields'];
				} elseif ($target_type === 'roles') {
					foreach ($user->roles as $role) {
						if ($target_value === $role) {
							$role_rules += $rule['fields'];
						}
					}
				}
			}
		}

		return array($role_rules, $user_rules);
	}

	/**
	 * @param string $menu_id  The menu id or slug
	 * @param array<mixed> $role_rules
	 * @param array<mixed> $user_rules
	 * @return bool
	 */
	protected static function is_menu_hidden($menu_id, $role_rules, $user_rules) {
		// When a "Rule" exists for the user (either by their login or
		// their role) the following logic will remove an admin menu item
		// when:
		//  * The menu item doesn't exist in either a user rule OR a role
		//    rule
		//  * The menu item doesn't exist in a user rule but exists in a
		//    role rule
		// Basically, a "User Role" overwrites a "Role Rule"
		return (!isset($role_rules[$menu_id]) && !isset($user_rules[$menu_id])) ||
			(isset($role_rules[$menu_id]) && !isset($user_rules[$menu_id]) && !empty($user_rules));
	}

	/**
	 * @param string $menu
	 * @param string $search
	 * @param string $replace
	 *
	 * @return void
	 */
	private static function rename_menu(&$menu, $search, $replace) {
		$menu = str_replace($search, esc_html($replace), $menu);
	}

	/**
	 * Checks if the bar menu with id $menu_id is in the exclude list.
	 *
	 * @param string $menu_id
	 * @return bool
	 */
	protected static function is_bar_menu_item_excluded($menu_id) {
		$exclude = array('menu-toggle', 'top-secondary');
		return in_array($menu_id, $exclude);
	}

	/**
	 * @param array<object{id: string, title: string, parent: string, href: string, group: bool, meta: mixed[]}> $nodes  Result from WP_Admin_Bar::get_nodes()
	 * @return array<BarMenuItem>
	 */
	protected static function parse_bar_menu($nodes) {
		$parents = array();

		/* $node:
			string $id     ID of the item.
			string $title  Title of the node.
			string $parent Optional. ID of the parent node.
			string $href   Optional. Link for the item.
			bool   $group  Optional. Whether or not the node is a group. Default false.
			array  $meta   Meta data including the following keys: 'html', 'class', 'rel', 'lang', 'dir',
							'onclick', 'target', 'title', 'tabindex'. Default empty.
		*/

		foreach ($nodes as $node) {
			if (self::is_bar_menu_item_excluded($node->id)) {
				continue;
			}

			$parent = strlen($node->parent) ? $node->parent : false;
			$node->title = strip_tags($node->title) . " ({$node->id})";

			if ($parent === false) {
				$parents[$node->id] = new BarMenuItem($node->id, $node->title);
			}
		}

		foreach ($nodes as $node) {
			if (self::is_bar_menu_item_excluded($node->id)) {
				continue;
			}

			$parent = strlen($node->parent) ? $node->parent : false;

			if (isset($parents[$parent])) {
				$parents[$parent]->add_child(new BarMenuItem($node->id, $node->title));
			}
		}

		return $parents;
	}

}
