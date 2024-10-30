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

namespace Brandnestor\Widgets;

abstract class Base_Form extends \Elementor\Widget_Base {

	/**
	 * @return void
	 */
	protected function register_controls() {
		/**
		 * Section: Style
		 */
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __('Style', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'label'    => __('Form Label Typography', 'brandnestor'),
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} label',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'label'    => __('Text Typography', 'brandnestor'),
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} p',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __('Text Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} label' => 'color: {{VALUE}};',
					'{{WRAPPER}} p'     => 'color: {{VALUE}};',
					'{{WRAPPER}} a'     => 'color: {{VALUE}};',
				)
			)
		);

		$this->end_controls_section();

		/**
		 * Section: Input Style
		 */
		$this->start_controls_section(
			'style_input_section',
			array(
				'label' => __('Inputs', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_text_typography',
				'selector' => '{{WRAPPER}} .form-control',
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => __('Text Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-control' => 'color: {{VALUE}};',
					'{{WRAPPER}} .hide-pw'      => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_background_color',
			array(
				'label'     => __('Background Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-control' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label' => __('Padding', 'elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'input_border',
				'selector'  => '{{WRAPPER}} .form-control',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'input_border_radius',
			array(
				'label'      => __('Border Radius', 'elementor'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}} .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Section: Button Style
		 */
		$this->start_controls_section(
			'style_button_section',
			array(
				'label' => __('Button', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_text_typography',
				'selector' => '{{WRAPPER}} .brandnestor-button',
			)
		);

		/* $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'button_text_shadow',
				'selector' => '{{WRAPPER}} .brandnestor-button'
			),
		); */

		$this->add_responsive_control(
			'button_padding',
			array(
				'label' => __('Padding', 'elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs('tabs_style_button');

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __('Normal', 'elementor')
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __('Text Color', 'elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __('Background Color', 'elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .brandnestor-button',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __('Border Radius', 'elementor'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}} .brandnestor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .brandnestor-button'
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __('Hover', 'elementor')
			)
		);

		$this->add_control(
			'button_text_hover_color',
			array(
				'label'     => __('Text Color', 'elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button:hover, {{WRAPPER}} .brandnestor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .brandnestor-button:hover svg, {{WRAPPER}} .brandnestor-button:focus svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => __('Background Color', 'elementor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button:hover, {{WRAPPER}} .brandnestor-button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => __('Hover Animation', 'elementor'),
				'type'  => \Elementor\Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_control(
			'button_hover_transition_delay',
			array(
				'label'     => __('Transition Duration (in milliseconds)', 'elementor'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 10000,
				'default'   => 100,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button' => 'transition-duration: {{VALUE}}ms; transition-property: all',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'button_hover_border',
				'selector'  => '{{WRAPPER}} .brandnestor-button:hover',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_hover_border_radius',
			array(
				'label' => __('Border Radius', 'elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_shadow',
				'selector' => '{{WRAPPER}} .brandnestor-button:hover, {{WRAPPER}} .brandnestor-button:focus'
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'style_message_section',
			array(
				'label' => __('Message Box', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'message_heading',
			array(
				'label' => __('Message Styling', 'brandnestor'),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'message_text_color',
			array(
				'label'     => __('Text Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-form-message' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'message_background_color',
			array(
				'label'     => __('Background Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-form-message' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'message_border',
				'selector'  => '{{WRAPPER}} .brandnestor-form-message',
			)
		);

		$this->add_control(
			'message_error_heading',
			array(
				'label' => __('Error Message Styling', 'brandnestor'),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'message_error_text_color',
			array(
				'label'     => __('Error Text Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-form-message.error' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'message_error_background_color',
			array(
				'label'     => __('Error Background Color', 'brandnestor'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .brandnestor-form-message.error' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'message_error_border',
				'selector'  => '{{WRAPPER}} .brandnestor-form-message.error',
			)
		);

		$this->add_control(
			'message_border_radius',
			array(
				'label'      => __('Border Radius', 'elementor'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}} .brandnestor-form-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'message_padding',
			array(
				'label'      => __('Padding', 'elementor'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors'  => array(
					'{{WRAPPER}} .brandnestor-form-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'message_margin',
			array(
				'label'      => __('Margin', 'elementor'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors'  => array(
					'{{WRAPPER}} .brandnestor-form-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Section: Button Content
		 */
		$this->start_controls_section(
			'content_button_section',
			array(
				'label' => __('Button', 'brandnestor'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label_button',
			array(
				'label'       => __( 'Button Label', 'brandnestor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Submit', 'brandnestor' ),
				'placeholder' => __( 'Submit', 'brandnestor' ),
			)
		);

		$this->add_control(
			'button_align',
			array(
				'label'   => __('Alignment', 'brandnestor'),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left'      => array(
						'title' => __('Left', 'brandnestor'),
						'icon'  => 'eicon-text-align-left'
					),
					'center'    => array(
						'title' => __('Center', 'brandnestor'),
						'icon'  => 'eicon-text-align-center'
					),
					'right'     => array(
						'title' => __('Right', 'brandnestor'),
						'icon'  => 'eicon-text-align-right'
					),
					'justify' => array(
						'title' => __('Justified', 'brandnestor'),
						'icon'  => 'eicon-text-align-justify'
					),
				),
			)
		);

		$this->add_control(
			'icon_button',
			array(
				'label' => __('Button Icon', 'brandnestor'),
				'type'  => \Elementor\Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'      => __('Icon Spacing', 'elementor'),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'selectors'  => array(
					'{{WRAPPER}} .brandnestor-button .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

}
