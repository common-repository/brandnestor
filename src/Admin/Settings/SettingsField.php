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

namespace Brandnestor\Admin\Settings;

abstract class SettingsField {

	/** @var string */
	public $name;
	/** @var string */
	protected $value = '';
	/** @var string */
	protected $label;
	/** @var string */
	protected $description;
	/** @var bool */
	protected $disabled = false;
	/** @var string */
	protected $condition = '';

	/**
	 * @param string $name
	 * @param string $label
	 * @param string $description
	 */
	public function __construct($name, $label, $description = '') {
		$this->name = $name;
		$this->label = $label;
		$this->description = $description;
	}

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function value($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * @param bool $disabled
	 * @return $this
	 */
	public function disabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

	/**
	 * @param mixed $condition
	 * @return $this
	 */
	public function condition($condition) {
		$out = '';

		if (is_array($condition)) {
			$out = 'data-condition="' . $condition[0] . '" data-condition-value="' . $condition[1] . '"';
		} else {
			$out = 'data-condition="' . $condition . '"';
		}

		$this->condition = $out;
		return $this;
	}

	/**
	 * @return string
	 */
	public function html() {
		return $this->start() . $this->middle() . $this->end();
	}

	/**
	 * @return string
	 */
	public abstract function start();

	/**
	 * @return string
	 */
	public abstract function middle();

	/**
	 * @return string
	 */
	public abstract function end();

}
