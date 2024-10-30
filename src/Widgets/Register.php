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

namespace Brandnestor\Widgets;

class Register extends Base_Form {

	/**
	 * Widget base constructor.
	 *
	 * Initializing the widget base class.
	 *
	 * @param array<mixed>       $data Widget data. Default is an empty array.
	 * @param array<mixed>|null  $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'brandnestor-register';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return __('BrandNestor Register');
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-plus-square-o';
	}

	/**
	 * @return array<string>
	 */
	public function get_categories() {
		return array('brandnestor');
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array<string> Element scripts dependencies.
	 */
	public function get_script_depends() {
		return array();
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array<string> Element styles dependencies.
	 */
	public function get_style_depends() {
		return array('brandnestor-base');
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __('Content', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label_username',
			array(
				'label' => __('Username Label', 'brandnestor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __('Username'),
				'placeholder' => __('Username'),
			)
		);

		$this->add_control(
			'label_email',
			array(
				'label'       => __('Email Label', 'brandnestor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __('Email'),
				'placeholder' => __('Email'),
			)
		);

		$this->add_control(
			'show_firstname',
			array(
				'label'         => __('Show First Name Input', 'brandnestor'),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
				'label_on'      => __('Show', 'brandnestor'),
				'label_off'     => __('Hide', 'brandnestor'),
				'return_value'  => 'yes',
				'default'       => 'no',
			)
		);

		$this->add_control(
			'label_firstname',
			array(
				'label'       => __('First Name Label', 'brandnestor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __('First Name'),
				'placeholder' => __('First Name'),
				'condition'   => array('show_firstname[value]' => 'yes'),
			)
		);

		$this->add_control(
			'show_lastname',
			array(
				'label'         => __('Show Last Name Input', 'brandnestor'),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
				'label_on'      => __('Show', 'brandnestor'),
				'label_off'     => __('Hide', 'brandnestor'),
				'return_value'  => 'yes',
				'default'       => 'no',
			)
		);

		$this->add_control(
			'label_lastname',
			array(
				'label'       => __('Last Name Label', 'brandnestor'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __('Last Name'),
				'placeholder' => __('Last Name'),
				'condition'   => array('show_lastname[value]' => 'yes'),
			)
		);

		$this->end_controls_section();

		parent::register_controls();
	}

	/**
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
			echo '<p class="brandnestor-form-message">' . wp_kses_post(__('Sample message.', 'brandnestor')) . '</p>';
			echo '<p class="brandnestor-form-message error">' . wp_kses_post(__('Sample <strong>error</strong> message.', 'brandnestor')) . '</p>';
		}

		$view = new \Brandnestor\Core\View(BRANDNESTOR_DIR . 'includes/register-form.php');
		$view->render(new \Brandnestor\Front\Models\RegisterModel($settings));
	}

}
