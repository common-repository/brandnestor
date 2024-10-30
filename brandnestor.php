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

/*
 * Plugin Name: BrandNestor
 * Description: Customize the WordPress dashboard, login/register pages, and more.
 * Version: 2.2.0
 * Author: Nikos Papadakis
 * Author URI: https://gitlab.com/webnestors/brandnestor
 * Text Domain: brandnestor
 * Domain Path: /languages
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die();
}

define('BRANDNESTOR_VERSION', '2.2.0');
define('BRANDNESTOR_NAME', 'BrandNestor');
define('BRANDNESTOR_FILE', __FILE__);
define('BRANDNESTOR_DIR', plugin_dir_path(__FILE__));
define('BRANDNESTOR_BASE', plugin_basename(__FILE__));
define('BRANDNESTOR_URL', plugins_url('/', __FILE__));

require BRANDNESTOR_DIR . 'vendor/autoload.php';

// Begin Execution
Brandnestor\Core\Plugin::instance()->init(BRANDNESTOR_NAME, BRANDNESTOR_VERSION);
