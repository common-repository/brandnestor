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

class SettingsImageField extends SettingsTextField {

	public function middle() {
		$out = sprintf('<img src="%s" />', esc_url($this->value));
		return $out . parent::middle();
	}

	public function end() {
		$out = '<button type="button" class="brandnestor-btn upload-image">' . __('Upload Image', 'brandnestor') . '</button>';
		$out .= parent::end();
		return $out;
	}

}
