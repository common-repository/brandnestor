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

use Brandnestor\Utilities\Option;
use Brandnestor\Utilities\SingletonTrait;

/**
 * @template T
 */
final class Options {

	use SingletonTrait;

	/** @var array<T> */
	private $options = array();

	/**
	 * Register an option by name
	 *
	 * @param class-string<T> $class The option class string
	 *
	 * @return void
	 */
	public function register_option($class) {
		$option = get_option($class::OPTION_NAME);
		if ($option instanceof Option) {
			$this->options[$class] = $option;
		} else {
			$this->options[$class] = new $class($option);
		}
	}

	/**
	 * Get a registered option by name
	 *
	 * @param class-string<T> $class The option class string
	 *
	 * @return T
	 */
	public function get_registered_option($class) {
		return $this->options[$class];
	}

	/**
	 * Get an option by key from any registered option object
	 *
	 * @param ?string $key The name of the Option key
	 *
	 * @return mixed
	 */
	public function get($key = null) {
		foreach ($this->options as $option) {
			if (isset($option->{$key})) {
				return $option->{$key};
			}
		}
	}

	/**
	 * Set all registered options
	 *
	 * @param array<Option> $options
	 * @return void
	 */
	public function set(&$options) {
		foreach ($this->options as $option) {
			$option->set($options);
		}
	}

	/**
	 * Save all registered options
	 *
	 * @return void
	 */
	public function save() {
		foreach ($this->options as $option) {
			$option->save();
		}
	}

	/**
	 * Reset all registered options
	 *
	 * @return void
	 */
	public function reset() {
		foreach ($this->options as &$option) {
			$option = new $option();
		}
	}

	/**
	 * Get all registered options into an array
	 *
	 * @return array<mixed>
	 */
	public function get_all() {
		$all = array();
		foreach ($this->options as $option) {
			$all += (array)$option->get();
		}
		return $all;
	}

}
