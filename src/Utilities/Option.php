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

abstract class Option {

	const OPTION_NAME = 'brandnestor_options';

	/**
	 * @param ?array<mixed> $data   Data that might come from an array
	 *
	 * @return void
	 */
	public function __construct($data = null) {
		if ($data) {
			$this->set($data);
		}
	}

	/**
	 * Get an option by $key
	 *
	 * @param ?string $key
	 *
	 * @return mixed|false
	 */
	public function get($key = null) {
		if (!$key) {
			return $this;
		}
		return $this->{$key} ?? false;
	}

	/**
	 * Save options to Database. Returns true on successful save
	 *
	 * @return bool
	 */
	public function save() {
		return update_option(static::OPTION_NAME, $this);
	}

	/**
	 * Set options, used for batch-setting options from the settings page
	 *
	 * @param array<mixed> $options
	 * @return void
	 */
	public function set(&$options) {
		foreach (get_object_vars(new $this) as $property => $default) {
			$this->{$property} = $options[$property] ?? $default;
			unset($options[$property]);
		}
	}

}
