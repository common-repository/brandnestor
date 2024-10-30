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

use \Brandnestor\Utilities\Option;
use \Brandnestor\Utilities\Functions;

class DefaultOptions extends Option {

	const OPTION_NAME = 'brandnestor_options';

	// General
	/** @var string|false */
	public $brand_name        = false;
	/** @var string|false */
	public $branding_logo     = false;
	/** @var string|false */
	public $branding_logo_url = false;
	/** @var string|false */
	public $footer_image      = false;
	/** @var string|false */
	public $footer_text       = false;
	/** @var string|false */
	public $footer_url        = false;
	/** @var string */
	public $hide_help         = 'off';
	/** @var string */
	public $hide_version      = 'off';

	// Dashboard
	/** @var string|false */
	public $dashboard_css            = false;
	/** @var string */
	public $dashboard_hide_metaboxes = 'off';
	/** @var string|false */
	public $dashboard_html           = false;
	/** @var string */
	public $dashboard_panel          = 'off';
	/** @var string|false */
	public $dashboard_panel_type     = false;
	/** @var string|false */
	public $dashboard_template       = false;

	// Admin Menus
	/** @var array<array<string>> */
	public $admin_menus = array(
		'roles' => array(),
		'users' => array(),
	);

	// Newer admin menus rules option, replaces $admin_menus in v2.2.0
	public $admin_menus_rules = array();

	// Admin Bar
	/** @var array<string> */
	public $admin_bar = array(
		'administrator' => 'on',
		'editor'        => 'on',
		'author'        => 'on',
		'contributor'   => 'on',
		'subscriber'    => 'on',
		'shop_manager'  => 'on',
		'guest'         => 'off',
	);

	/** @var array<mixed> */
	public $bar_menu_rules = array();

	// Authentication
	/** @var string */
	public $disable_wp_admin_redirect = 'off';
	/** @var string */
	public $disable_wp_login          = 'off';
	/** @var string|false */
	public $login_page_template       = false;
	/** @var string|false */
	public $login_page_url            = false;
	/** @var string|false */
	public $login_redirect_to         = false;
	/** @var string|false */
	public $register_page_template    = false;
	/** @var string|false */
	public $register_page_url         = false;
	/** @var string|false */
	public $register_redirect_to      = false;
	/** @var string|false */
	public $wp_login_logo             = false;

	// Advanced
	/** @var string */
	public $admin_css        = '';
	/** @var bool */
	public $disable_rest_api = false;

	/**
	 * @param array<mixed> $options
	 * @return void
	 */
	public function set(&$options) {
		foreach ($options as $key => $value) {
			switch ($key) {
			case 'login_page_url':
			case 'register_page_url':
				if (Functions::is_forbidden_slug($value)) {
					$error = sprintf(
						/* translators: %s a forbidden URL slug that is set by the user */
						__('URL slug "%s" is not allowed.', 'brandnestor'),
						$value
					);

					throw new \Exception($error);
				}
				break;
			}
		}

		parent::set($options);
	}

}
