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

namespace Brandnestor\Core;

use \Brandnestor\Utilities\SingletonTrait;
use \Elementor\Plugin as Elementor;

/**
 * Elementor specific stuff.
 */
final class ElementorManager {

	use SingletonTrait;

	/** @var bool */
	private $is_elementor_loaded = false;

	/** @var \Elementor\Plugin */
	private $elementor;

	private function __construct() {
		if (did_action('elementor/loaded')) {
			$this->is_elementor_loaded = true;
			$this->elementor = Elementor::instance();
		}
	}

	/**
	 * Registers Elementor functionality (widgets) to the plugin.
	 *
	 * @return void
	 */
	public function register() {
		if ($this->is_elementor_loaded()) {
			add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
			add_action('elementor/elements/categories_registered', array($this, 'register_categories'));
		}
	}

	/**
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 * @return void
	 */
	public function register_widgets($widgets_manager) {
		if (!did_action('brandnestor/widgets_registered')) {
			$widgets_manager->register_widget_type(new \Brandnestor\Widgets\Login());
			$widgets_manager->register_widget_type(new \Brandnestor\Widgets\Register());

			do_action('brandnestor/widgets_registered', $widgets_manager);
		}
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 * @return void
	 */
	public function register_categories($elements_manager) {
		$elements_manager->add_category('brandnestor', array(
			'title' => esc_html__('BrandNestor', 'brandnestor'),
			'icon'  => 'fa fa-plug',
		));
	}

	/**
	 * Returns elementor's templates filtering any non local template sources.
	 *
	 * @return array<mixed>
	 */
	public function get_templates() {
		if ($this->is_elementor_loaded()) {
			$templates = $this->elementor->templates_manager->get_templates(array('local'));
		}

		return array();
	}

	/**
	 * Returns true if the post with id post_id is an Elementor post.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id
	 * @return bool
	 */
	public function is_built_with_elementor($post_id) {
		if ($this->is_elementor_loaded()) {
			return $this->elementor->documents->get($post_id)->is_built_with_elementor();
		} else {
			return false;
		}
	}

	/**
	 * Returns an elementor document content.
	 *
	 * @since 2.0.0
	 *
	 * @param int  $post_id
	 * @param bool $with_css
	 * @return string
	 */
	public function get_content($post_id, $with_css = false) {
		if ($with_css) {
			$this->elementor->frontend->register_styles();
			$this->elementor->frontend->enqueue_styles();
			$this->elementor->frontend->register_scripts();
			$this->elementor->frontend->enqueue_scripts();
		}

		return $this->elementor->frontend->get_builder_content_for_display($post_id, $with_css);
	}

	/**
	 * Returns true if Elementor is loaded as a plugin
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_elementor_loaded() {
		return $this->is_elementor_loaded;
	}

}
