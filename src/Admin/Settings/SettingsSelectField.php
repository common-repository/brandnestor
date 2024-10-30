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

class SettingsSelectField extends SettingsField {

	/** @var array<array<string>> */
	protected $options;
	/** @var bool */
	protected $multiple;
	/** @var bool */
	protected $slim_select;

	/**
	 * @param array<array<string>> $options
	 * @param bool $multiple
	 */
	public function __construct($name, $label, $description = '', $options = array(), $multiple = false) {
		parent::__construct($name, $label, $description);
		$this->options = $options;
		$this->multiple = $multiple;
		$this->slim_select = true;
	}

	/**
	 * @param bool $enabled
	 * @return $this
	 */
	public function set_slim_select($enabled) {
		$this->slim_select = $enabled;
		return $this;
	}

	public function start() {
		return '<div class="form-group" ' . $this->condition . '>';
	}

	public function middle() {
		ob_start();
		?>
		<label><?php echo esc_html($this->label) ?></label>
		<div class="form-input">
			<select
				class="brandnestor-select <?php echo $this->slim_select ? 'slim-select' : '' ?>"
				name="<?php echo esc_attr($this->name) ?>"
				<?php echo $this->disabled ? 'disabled' : '' ?>
				<?php echo $this->multiple ? 'multiple' : '' ?>
			>
				<option data-placeholder="true"></option>
				<?php foreach ($this->options as $option_field): ?>
				<option
					value="<?php echo esc_attr($option_field['value']) ?>"
					<?php echo $this->value == $option_field['value'] ? 'selected' : '' ?>
				>
					<?php echo esc_html($option_field['label']) ?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
		<?php
		echo '<p class="description">' . wp_kses_post($this->description) . '</p>';
		return ob_get_clean();
	}

	public function end() {
		return '</div>';
	}

}
