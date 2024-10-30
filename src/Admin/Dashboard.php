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

namespace Brandnestor\Admin;

use \Brandnestor\Core\ElementorManager;
use \Brandnestor\Core\Options;
use \Brandnestor\Core\Plugin;

class Dashboard {

	/** @var Options<\Brandnestor\Utilities\Option> */
	private $options;
	/** @var Admin */
	private $admin;

	public function __construct() {
		$this->options = Options::instance();
		$this->admin = Plugin::instance()->admin;
	}

	/**
	 * @return void
	 */
	public function init() {
		add_action('in_admin_header', array($this, 'render'));
		remove_action('welcome_panel', 'wp_welcome_panel');

		$this->admin->add_style('#wpbody-content .wrap > h1{display:none !important;}');

		if ($this->options->get('dashboard_hide_metaboxes') === 'on') {
			global $wp_meta_boxes;
			unset($wp_meta_boxes['dashboard']);
			$this->admin->add_style('#dashboard-widgets-wrap{display:none !important;}');
		}

		if ($dashboard_css = $this->options->get('dashboard_css')) {
			$this->admin->add_style(wp_unslash($dashboard_css));
		}
	}

	/**
	 * @return void
	 */
	public function render() {
		echo '<div id="welcome-panel">';

		/**
		 * Executes before the custom dashboard welcome panel is rendered
		 */
		do_action('brandnestor/before_dashboard_panel');

		echo $this->options->get('dashboard_panel_type') === 'html'
			? wp_kses_post($this->html())
			: $this->template($this->options->get('dashboard_template'));

		/**
		 * Executes after the custom dashboard welcome panel is rendered
		 */
		do_action('brandnestor/after_dashboard_panel');

		echo '</div>';

		$this->admin->add_script(';jQuery("#welcome-panel").insertBefore("#dashboard-widgets-wrap")');
	}

	/**
	 * @return string
	 */
	public function html() {
		return htmlspecialchars_decode($this->options->get('dashboard_html'));
	}

	/**
	 * @param int $template_id
	 * @return string
	 */
	public function template($template_id) {
		if (!$template_id) {
			return '';
		}

		$elementor = ElementorManager::instance();

		if ($elementor->is_built_with_elementor($template_id)) {
			return $elementor->get_content($template_id, true);
		} else {
			$post = get_post($template_id);
			wp_enqueue_style('wp-block-library');
			wp_enqueue_style('wp-block-library-theme');
			do_action('enqueue_block_assets');

			return apply_filters('the_content', $post->post_content);
		}
	}

}
