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

class SettingsTextField extends SettingsField {

	/** @var string */
	protected $prefix;

	/**
	 * @param string $name
	 * @param string $label
	 * @param string $description
	 * @param string $prefix
	 */
	public function __construct($name, $label, $description = '', $prefix = '') {
		parent::__construct($name, $label, $description);
		$this->prefix = $prefix;
	}

	public function start() {
		$out = '<div class="form-group" ' . $this->condition . '>';
		$out .= '<label>' . esc_html($this->label) . '</label>';
		$out .= '<div class="form-input form-input-text">';
		return $out;
	}

	public function middle() {
		ob_start();
		?>
			<?php if ($this->prefix !== ''): ?>
				<span class="prefix"><?php echo esc_attr($this->prefix) ?></span>
			<?php endif ?>
			<input type="text" name="<?php echo esc_attr($this->name) ?>" value="<?php echo esc_attr($this->value) ?>">
		<?php
		return ob_get_clean();
	}

	public function end() {
		$out = '</div>';
		$out .= '<p class="description">' . wp_kses_post($this->description) . '</p>';
		$out .= '</div>';
		return $out;
	}

}
