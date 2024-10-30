<?php

/*
 * BrandNestor
 * Copyright (C) 2023  Nikos Papadakis <nikos@papadakis.xyz>
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

namespace Brandnestor\Admin;

class BarMenuItem {

	/** @var string */
	public $id;
	/** @var string */
	public $title;
	/** @var array<BarMenuItem> */
	private $children = array();

	/**
	 * @param string $id;
	 * @param string $title;
	 */
	public function __construct($id, $title) {
		$this->id = $id;
		$this->title = $title;
	}

	/**
	 * @param BarMenuItem $item
	 * @return void
	 */
	public function add_child($item) {
		$this->children[] = $item;
	}

	/**
	 * @return array<BarMenuItem>
	 */
	public function get_children() {
		return $this->children;
	}

}
