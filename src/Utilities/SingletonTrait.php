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

trait SingletonTrait {

	/** @var static */
	protected static $instance;

	/**
	 * @return static
	 */
	final public static function instance() {
		if (static::$instance === null) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	protected function __construct() {}

	final public function __clone() {}

	final public function __wakeup() {}

}
