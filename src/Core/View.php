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

class View {

	/** @var \WP_Error|string $template */
	protected $template;

	/**
	 * @param string $template
	 */
	public function __construct($template) {
		$this->template = !file_exists($template)
			? new \WP_Error('brandnestor', 'Missing template file ' . $template)
			: $template;
	}

	/**
	 * @param object $context
	 *
	 * @return void
	 */
	public function render($context) {
		echo $this->_render($context);
	}

	/**
	 * @param object $context
	 *
	 * @return string
	 */
	public function view($context) {
		return $this->_render($context);
	}

	/**
	 * @param object $context
	 *
	 * @return string
	 */
	private function _render($context) {
		if (is_wp_error($this->template)) {
			throw new \Exception($this->template->get_error_message());
		}

		$callback = function($template) {
			$vars = get_object_vars($this);
			extract($vars);
			ob_start();
			include $template;
			return ob_get_clean();
		};

		return call_user_func(
			$callback->bindTo($context),
			$this->template
		);
	}

}
