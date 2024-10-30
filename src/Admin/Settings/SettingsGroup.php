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

use \Brandnestor\Core\View;

class SettingsGroup {

	/** @var string */
	public $id;
	/** @var string */
	public $title;
	/** @var string */
	public $description;
	/** @var array<SettingsField> */
	public $fields;

	/**
	 * @param string $id
	 * @param string $title
	 * @param string $description
	 */
	public function __construct($id, $title, $description) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->fields = array();
	}

	/**
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function description() {
		return $this->description;
	}

	/**
	 * @since 2.2.0
	 *
	 * @param SettingsField $field
	 * @return void
	 */
	public function add_field($field) {
		if ($field instanceof SettingsField) {
			$this->fields[$field->name] = $field;
		}
	}

	/**
	 * @since 2.2.0
	 *
	 * @param string $field_name
	 * @return \Brandnestor\Admin\Settings\SettingsField|false
	 */
	public function get_field($field_name) {
		return $this->fields[$field_name] ?? false;
	}

	/**
	 * @since 2.2.0
	 *
	 * @param string $field_name
	 * @return void
	 */
	public function delete_field($field_name) {
		if (isset($this->fields[$field_name])) {
			unset($this->fields[$field_name]);
		}
	}

	/**
	 * @return string
	 */
	public function render() {
		$out = '';
		foreach ($this->fields as $field) {
			$out .= $field->html();
		}
		return $out;
	}

}
