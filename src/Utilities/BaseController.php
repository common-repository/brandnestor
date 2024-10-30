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

class BaseController {

	/** @var array<string> */
	protected $scripts = array();
	/** @var array<string> */
	protected $styles = array();

	/**
	 * @param string $script
	 *
	 * @return void
	 */
	public function add_script($script) {
		$this->scripts[] = $script;
	}

	/**
	 * @param string $style
	 *
	 * @return void
	 */
	public function add_style($style) {
		$this->styles[] = $style;
	}

	/**
	 * @return void
	 */
	public function print_scripts() {
		echo '<script type="text/javascript">jQuery(document).ready(function(){' . implode('', $this->scripts) . '})</script>';
	}

	/**
	 * @return void
	 */
	public function print_styles() {
		echo '<style>' . implode(' ', $this->styles) . '</style>';
	}

}
