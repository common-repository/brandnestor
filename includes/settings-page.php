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

?>
<div class="wrap">
	<h1 class="brandnestor-page-title" aria-label="BrandNestor">
		<p id="brandnestor_logo">
			<img src="<?php echo esc_url(BRANDNESTOR_URL . 'assets/image/logo_150.png') ?>" />
		</p>
	</h1>

	<div class="brandnestor-modal">
		<div class="modal-container brandnestor-depth-4">
			<h1 class="modal-title"></h1>
			<div class="modal-content">
			</div>
			<button type="button" class="brandnestor-btn brandnestor-btn-secondary modal-close"><?php esc_html_e('Cancel', 'brandnestor') ?></button>
			<button type="button" class="brandnestor-btn brandnestor-btn-neutral modal-ok"><?php esc_html_e('OK', 'brandnestor') ?></button>
		</div>
	</div>

	<div class="brandnestor">
		<div class="notifications"></div>

		<nav>
			<?php foreach ($groups as $group): ?>
			<a class="tab-button" href="#tab=<?php echo esc_attr($group->id()) ?>" type="button">
				<?php echo esc_html($group->title()) ?>
			</a>
			<?php endforeach ?>
		</nav>

		<div class="settings-section">
			<div id="loading_indicator">
				<i class="loading-spinner"></i>
			</div>
			<div id="settings_buttons">
				<a href="https://gitlab.com/webnestors/brandnestor/-/wikis/home" target="_blank">
					<?php esc_html_e('View Documentation', 'brandnestor') ?>
					<span class="dashicons dashicons-external"></span>
				</a>
			</div>
			<form id="settings_form" class="brandnestor-panel">
				<?php foreach ($groups as $group): ?>
				<div id="<?php echo esc_attr($group->id()) ?>" class="tab-panel">
					<h3 class="tab-panel-title"><?php echo esc_html($group->title()) ?></h3>
					<p class="tab-panel-description"><?php echo wp_kses_post($group->description()) ?></p>
					<?php echo wp_kses_post($group->render()); ?>
				</div>
				<?php endforeach ?>
				<input id="submit" type="submit" name="submit"
					title="<?php esc_attr_e('Changes will appear on the next page refresh.', 'brandnestor') ?>"
					class="brandnestor-btn brandnestor-btn-primary"
					value="<?php esc_attr_e('Save Changes', 'brandnestor') ?>"
				/>
			</form>
		</div>
	</div>

	<a id="link_reset_plugin" href="<?php echo esc_url($reset_url) ?>">
		<?php echo esc_html__('Reset Plugin', 'brandnestor') ?>
	</a>
</div>
