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

use Brandnestor\Core\View;

class Blocks {

	/**
	 * @return void
	 */
	public static function register_blocks() {
		register_block_type('brandnestor/login', array(
			'api_version'     => 2,
			'editor_script'   => 'brandnestor-block-editor',
			'editor_style'    => 'brandnestor-base',
			'render_callback' => __CLASS__ . '::login_render_callback'
		));

		register_block_type('brandnestor/register', array(
			'api_version'     => 2,
			'editor_script'   => 'brandnestor-block-editor',
			'editor_style'    => 'brandnestor-base',
			'render_callback' => __CLASS__ . '::register_render_callback'
		));
	}

	/**
	 * @param array<mixed> $block_attributes
	 *
	 * @return string
	 */
	public static function login_render_callback($block_attributes) {
		wp_enqueue_script('brandnestor-login');
		wp_enqueue_style('brandnestor-base');

		$view = new View(BRANDNESTOR_DIR . 'includes/login-form.php');
		return $view->view(new \Brandnestor\Front\Models\LoginModel($block_attributes));
	}

	/**
	 * @param array<mixed> $block_attributes
	 *
	 * @return string
	 */
	public static function register_render_callback($block_attributes) {
		wp_enqueue_style('brandnestor-base');

		$view = new View(BRANDNESTOR_DIR . 'includes/register-form.php');
		return $view->view(new \Brandnestor\Front\Models\RegisterModel($block_attributes));
	}

}
