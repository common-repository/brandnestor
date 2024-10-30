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

class SettingsCheckboxesField extends SettingsField {

	/** @var array<array<string>> */
	protected $options;

	/**
	 * @param array<array<string>> $options
	 */
	public function __construct($name, $label, $description = '', $options = array()) {
		parent::__construct($name, $label, $description);
		$this->options = $options;
	}

	public function start() {
		return '<div class="form-group" ' . $this->condition . '>';
	}

	public function middle() {
		ob_start();
		?>
		<label><?php echo esc_html($this->label) ?></label>
		<div class="form-checkboxes">
		<?php foreach ($this->options as $option): ?>
			<?php $checked = isset($option['checked']) && $option['checked'] === 'on' ? 'checked' : ''; ?>
			<label>
				<input type="checkbox" name="<?php echo esc_attr($option['name']) ?>" <?php echo $checked ?> />
				<?php echo esc_html($option['label']) ?>
			</label>
		<?php endforeach ?>
		</div>
		<?php
		echo '<p class="description">' . wp_kses_post($this->description) . '</p>';
		return ob_get_clean();
	}

	public function end() {
		return '</div>';
	}

}
