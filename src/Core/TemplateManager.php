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

/**
 * BrandNestor Templates manager.
 *
 * Handles registering custom post types for custom BrandNestor template pages.
 *
 * @since 2.0.0
 */
class TemplateManager {

	/**
	 * BrandNestor template post type slug
	 */
	const CPT = 'brandnestor_template';

	public function __construct() {
		$this->register_data();
	}

	/**
	 * @return void
	 */
	public function register_data() {
		$args = array(
			'label'               => __('BrandNestor Templates', 'brandnestor'),
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => 'brandnestor',
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'supports'            => array('title', 'editor', 'post-formats', 'page-attributes', 'custom-fields', 'elementor'),
			'show_in_rest'        => true,
		);

		register_post_type(self::CPT, $args);
	}

	/**
	 * Retrieves all BrandNestor templates
	 *
	 * @since 2.0.0
	 *
	 * @return array<array<mixed>>
	 */
	public function get_templates() {
		$args = array(
			'post_type'      => self::CPT,
			'post_status'    => array('publish', 'private'),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		$templates = array();

		foreach (get_posts($args) as $post) {
			$templates[] = $this->get_item($post);
		}

		return $templates;
	}

	/**
	 * Retrieves a BrandNestor template by id
	 *
	 * @param int $template_id
	 *
	 * @return array<mixed>
	 *
	 * @since 2.0.0
	 */
	public function get_template($template_id) {
		$post = get_post($template_id);
		return $this->get_item($post);
	}

	/**
	 * Retrieves a BrandNestor template by WP_Post
	 *
	 * @param \WP_Post $post
	 *
	 * @return array<mixed>
	 *
	 * @since 2.0.0
	 */
	public function get_item($post) {
		return array(
			'template_id' => $post->ID,
			'slug'        => $post->post_name,
			'title'       => $post->post_title,
			'status'      => $post->post_status,
			'url'         => get_permalink($post->ID),
		);
	}

}
