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

class SettingsRuleField extends SettingsField {

	/** @var array<string, string> */
	protected $target_groups;
	/** @var array<array> */
	protected $target_options;
	/** @var array<SettingsField> */
	protected $rule_fields;

	/**
	 * @param array<string, string> $target_groups
	 * @param array<array> $target_options
	 * @param array<SettingsField> $fields
	 */
	public function __construct($name, $label, $target_groups = array(), $target_options = array(), $fields = array()) {
		parent::__construct($name, $label);
		$this->target_groups = $target_groups;
		$this->target_options = $target_options;
		$this->rule_fields = $fields;
	}

	public function start() {
		return '<div id="' . esc_attr($this->name) . '_ruleset" class="form-ruleset" ' . $this->condition . '>';
	}

	public function middle() {
		ob_start();
		?>
		<div class="form-ruleset-head form-group">
			<label><?php echo esc_html($this->label) ?></label>
			<div class="form-input">
				<select class="brandnestor-select slim-select" multiple>
					<?php foreach ($this->target_groups as $group_key => $group_title): ?>
					<optgroup label="<?php echo esc_attr($group_title) ?>">
						<?php foreach ($this->target_options[$group_key] as $option): ?>
						<option
							value="<?php echo esc_attr($group_key . ':' . $option['key']) ?>"
						><?php echo esc_html($option['label']) ?></option>
						<?php endforeach ?>
					</optgroup>
					<?php endforeach ?>
				</select>
			</div>
			<button type="button" class="brandnestor-btn form-rule-add">
				<?php esc_html_e('Add New', 'brandnestor') ?>
			</button>
		</div>
		<div class="form-rule">
			<div class="form-rule-head">
			</div>
			<div class="form-rule-body">
				<?php foreach ($this->rule_fields as $rule_field) {
					if ($rule_field instanceof SettingsField) {
						echo $rule_field->html();
					}
				} ?>
			</div>
			<div class="form-rule-delete">
				<button type="button" class="brandnestor-btn brandnestor-btn-danger">
					<?php esc_html_e('Delete', 'brandnestor') ?>
				</button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function end() {
		return '</div>';
	}

}
