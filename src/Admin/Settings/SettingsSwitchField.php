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

class SettingsSwitchField extends SettingsField {

	/** @var string */
	protected $switch_label;

	/**
	 * @param string $switch_label
	 */
	public function __construct($name, $label, $description = '', $switch_label = '') {
		parent::__construct($name, $label, $description);
		$this->switch_label = $switch_label;
	}

	public function start() {
		$out = '<div class="form-group" ' . $this->condition . '>';
		$out .= '<label>' . esc_html($this->label) . '</label>';
		return $out;
	}

	public function middle() {
		ob_start();
		$checked = $this->value === 'on' ? 'checked' : '';
		$disabled = $this->disabled ? 'disabled' : '';

		?>
		<label class="form-input">
			<input
				type="checkbox"
				class="form-switch"
				name="<?php echo esc_attr($this->name) ?>"
				<?php echo $disabled ?>
				<?php echo $checked ?>
			>
			<?php echo esc_html($this->switch_label) ?>
		</label>

		<?php
		echo '<p class="description">' . wp_kses_post($this->description) . '</p>';
		return ob_get_clean();
	}

	public function end() {
		return '</div>';
	}

}
