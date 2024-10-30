<?php

/*
 * BrandNestor
 * Copyright (C) 2021  Nikos Papadakis <nikos@papadakis.xyz>
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

namespace Brandnestor\Front\Models;

class RegisterModel {

	/** @var array<mixed> */
	public $settings;
	/** @var array<string> */
	public $messages = array();
	/** @var array<string> */
	public $errors = array();

	/**
	 * @param array<mixed> $settings
	 */
	public function __construct($settings) {
		// Set default settings
		$this->settings = array(
			'label_username'  => __('Username'),
			'label_email'     => __('Email'),
			'show_firstname'  => false,
			'label_firstname' => __('First Name'),
			'show_lastname'   => false,
			'label_lastname'  => __('Last Name'),
			'label_button'    => __('Submit', 'brandnestor'),
		);

		if ($settings) {
			$this->settings = array_replace($this->settings, $settings);
		}
	}

}
