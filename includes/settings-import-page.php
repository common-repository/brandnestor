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

?>

<div class="wrap">
	<?php
	$messages = apply_filters('brandnestor/messages', $messages);
	foreach ($messages as $message) {
		echo '<div class="brandnestor-message brandnestor-error">' . wp_kses_data($message) . '</div>';
	}


	$errors = apply_filters('brandnestor/errors', $errors);
	foreach ($errors as $error) {
		echo '<div class="brandnestor-message brandnestor-error">' . wp_kses_data($error) . '</div>';
	}
	?>

	<div class="brandnestor">
		<div class="settings-section">
			<h3 class="tab-panel-title"><?php esc_html_e('Import BrandNestor Settings', 'brandnestor') ?></h3>
			<p class="tab-panel-description"><?php esc_html_e('You can upload an exported BrandNestor settings file to import your settings.', 'brandnestor') ?></p>

			<form id="settings_import_form" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label><?php esc_html_e('Upload file', 'brandnestor') ?></label>
					<div class="form-input">
						<input type="file" name="import_file" />
						<input type="hidden" name="brandnestor_action" value="import_plugin" />
						<?php wp_nonce_field('brandnestor-import-plugin') ?>
						<input type="submit" class="brandnestor-btn brandnestor-btn-primary" value="<?php esc_attr_e('Upload', 'brandnestor') ?>" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
