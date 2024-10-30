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

use \Brandnestor\Utilities\SingletonTrait;

/**
 * Simple wrapper for WP Cache
 *
 * @since 2.1.0
 */
final class Cache {

	use SingletonTrait;

	const KEY = 'brandnestor_cache';

	/** @var array<mixed> */
	private $cache;

	private function __construct() {
		$this->cache = wp_cache_get(self::KEY);

		if (!$this->cache) {
			$this->cache = array();
		}
	}

	/**
	 * @param string|false $key
	 *
	 * @return mixed|false
	 */
	public function get($key = false) {
		if (!$key) {
			return $this->cache;
		}

		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}

		return false;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function set($key, $value) {
		$this->cache[$key] = $value;
		wp_cache_set(self::KEY, $this->cache);
	}

	/**
	 * @return void
	 */
	public function delete() {
		wp_cache_delete(self::KEY);
	}

}
