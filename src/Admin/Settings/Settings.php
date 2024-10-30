<?php

/*
 * BrandNestor
 * Copyright (C) 2021-2023  Nikos Papadakis <nikos@papadakis.xyz>
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

use \Brandnestor\Admin\AdminMenus;
use \Brandnestor\Core\DefaultOptions;
use \Brandnestor\Core\ElementorManager;
use \Brandnestor\Core\Options;
use \Brandnestor\Core\Plugin;
use \Brandnestor\Core\View;
use \Brandnestor\Utilities\Functions;

class Settings {

	const MENU_SLUG = 'brandnestor';

	/** @var string */
	public $plugin_name;
	/** @var array<SettingsGroup> */
	public $groups = array();
	/** @var string */
	public $reset_url;
	/** @var string */
	public $export_url;
	/** @var array<string> */
	public $messages = array();
	/** @var array<string> */
	public $errors = array();
	/** @var array<array<string>> */
	public $users = array();
	/** @var array<array<string>> */
	public $roles = array();
	/** @var array<mixed> */
	protected $templates = array();

	/**
	 * @param string $plugin_name
	 */
	public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;
	}

	public function define_hooks() {
		add_action('wp_ajax_brandnestor_save_settings', array($this, 'ajax_save_settings'));
		add_action('wp_ajax_brandnestor_get_admin_menus_rules', array($this, 'ajax_get_admin_menus_rules'));
		add_action('wp_ajax_brandnestor_add_admin_menus_rule', array($this, 'ajax_add_admin_menus_rule'));
		add_action('wp_ajax_brandnestor_delete_admin_menus_rule', array($this, 'ajax_delete_admin_menus_rule'));
		add_action('wp_ajax_brandnestor_add_bar_menu_rule', array($this, 'ajax_add_bar_menu_rule'));
		add_action('wp_ajax_brandnestor_delete_bar_menu_rule', array($this, 'ajax_delete_bar_menu_rule'));
		add_action('wp_ajax_brandnestor_get_bar_menu_rules', array($this, 'ajax_get_bar_menu_rules'));
		add_filter('wp_kses_allowed_html', array($this, 'kses_allowed_html'));
	}

	/**
	 * @return void
	 */
	public function admin_menu() {
		$this->reset_url = add_query_arg(
			array(
				'_wpnonce'           => wp_create_nonce('brandnestor-reset-plugin'),
				'brandnestor_action' => 'reset_plugin',
			),
			admin_url('options-general.php?page=brandnestor')
		);
		$this->export_url = add_query_arg(
			array(
				'_wpnonce'           => wp_create_nonce('brandnestor-export-plugin'),
				'brandnestor_action' => 'export_plugin',
			),
			admin_url()
		);

		$brandnestor_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB2aWV3Qm94PSIwIDAgMWUzIDk0NC40NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBmaWxsPSJjdXJyZW50Q29sb3IiPgo8cGF0aCBkPSJNNDAxLDQ2OS4zN2MzMC44OS0xNi4zMiw1NC44LTQzLjczLDU0LjgtODMuMzcsMC02Ni40Ni01MC43Mi0xMTcuMTktMTU0LjUtMTE3LjE5SDU0LjA1bDkyLjc0LDY2LjQ1YTc4LDc4LDAsMCwwLDQ1LjQzLDE0LjZIMjk2YzUwLjE0LDAsNTkuNDcsMjEsNTkuNDcsNDIuNTUsMCwyMS0xNS4xNiw0Mi41Ni02NS44OCw0Mi41NkgxODJhMTQuODcsMTQuODcsMCwwLDAtMTQuODYsMTQuODZ2NDcuODNBMTQuODYsMTQuODYsMCwwLDAsMTgyLDUxMi41MkgyOTQuMjZjNDkuNTYsMCw1OC44OSwyMi4xNSw1OC44OSw0NC4zLDAsMTkuODItMTIuODMsMzkuNjUtNjQuMTQsMzkuNjVIMTkyLjIyYTc4LDc4LDAsMCwwLTQ1LjQzLDE0LjU5TDU0LjA1LDY3Ny41MUgzMDguODNjODYuODgsMCwxNTIuMTctNDMuNzIsMTUyLjE3LTExNy4xOEM0NjEsNTIzLDQzNC4xOSw0ODYuODcsNDAxLDQ2OS4zN1oiLz4KPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNjAzLjQ0LDQ0MC41MWMwLDc3LjQ4LS41OCwxNTcuODYtLjU4LDIzNC43NWgtMTEzVjI2Ni45M2g2OS45TDgzNC4xMSw0OTEuMTljMC03NS4xNC41OC0xNDkuNy41OC0yMjQuMjZIOTQ2VjY3NS4yNkg4OTBaIi8+CjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0ibTExMS4wNyA2MDYuNDUtNTcgNDAuODV2LTM0OC4zbDU3IDQwLjg1YTQ5LjIxIDQ5LjIxIDAgMCAxIDIwLjU0IDQwdjE4Ni42YTQ5LjIxIDQ5LjIxIDAgMCAxLTIwLjU0IDQweiIvPgo8L3N2Zz4K';

		add_menu_page(
			$this->plugin_name,
			$this->plugin_name,
			'manage_options',
			self::MENU_SLUG,
			function() {
				return;
			},
			$brandnestor_icon
		);

		add_submenu_page(
			self::MENU_SLUG,
			__('BrandNestor Settings', 'brandnestor'),
			__('Settings', 'brandnestor'),
			'manage_options',
			self::MENU_SLUG,
			array($this, 'render_settings_page')
		);

		add_submenu_page(
			self::MENU_SLUG,
			__('Import BrandNestor', 'brandnestor'),
			__('Import Settings', 'brandnestor'),
			'manage_options',
			'brandnestor_import',
			array($this, 'render_import_page')
		);

		add_submenu_page(
			self::MENU_SLUG,
			'',
			__('Export Settings', 'brandnestor'),
			'manage_options',
			'brandnestor_export',
			array($this, 'handle_redirects')
		);

		define('BRANDNESTOR_PRO_FILE', true);
		if (!defined('BRANDNESTOR_PRO_FILE')) {
			add_submenu_page(
				self::MENU_SLUG,
				__('Get Pro', 'brandnestor'),
				__('Get Pro', 'brandnestor'),
				'manage_options',
				'brandnestor_pro',
				array($this, 'render_pro_page'),
				1
			);
		}
	}

	/**
	 * @param array<mixed> $allowed
	 *
	 * @return array<mixed>
	 */
	public function kses_allowed_html($allowed) {
		$allowed_attr = array(
			'checked'  => true,
			'class'    => true,
			'data-*'   => true,
			'disabled' => true,
			'id'       => true,
			'multiple' => true,
			'name'     => true,
			'type'     => true,
			'value'    => true,
			'selected' => true,
		);

		$allowed['input'] = $allowed_attr;
		$allowed['select'] = $allowed_attr;
		$allowed['optgroup'] = array(
			'label' => true,
		);
		$allowed['option'] = $allowed_attr;
		$allowed['textarea'] = $allowed_attr;
		if (isset($allowed['span'])) {
			$allowed['span'] = array_merge($allowed['span'], array('contenteditable' => true));
		} else {
			$allowed['span'] = array('contenteditable' => true);
		}

		return $allowed;
	}

	/**
	 * @return void
	 */
	public function render_import_page() {
		wp_enqueue_style('brandnestor-settings');

		$view = new View(BRANDNESTOR_DIR . 'includes/settings-import-page.php');
		$view->render($this);
	}

	/**
	 * @return void
	 */
	public function render_pro_page() {
		wp_enqueue_style('brandnestor-pro');
		wp_enqueue_style('brandnestor-settings');

		$view = new View(BRANDNESTOR_DIR . 'includes/pro-page.php');
		$view->render($this);
	}

	/**
	 * Redirect BrandNestor menu items like Export Settings
	 *
	 * @return void
	 *
	 * @since 2.1.1
	 */
	public function handle_redirects() {
		if (empty($_GET['page'])) {
			return;
		}

		if ($_GET['page'] === 'brandnestor_export') {
			wp_safe_redirect($this->export_url);
			die;
		}
	}

	/**
	 * @return void
	 */
	public function render_settings_page() {
		$wp_users = get_users(array(
			'fields' => array('display_name', 'user_login', 'user_email'),
			'capability__not_in' => array('customer'),
		));
		foreach ($wp_users as $user) {
			$this->users[] = array('key' => $user->user_login, 'label' => "{$user->display_name} ({$user->user_email})");
		}

		$wp_roles = wp_roles()->roles;
		foreach ($wp_roles as $key => $role) {
			$this->roles[] = array('key' => $key, 'label' => $role['name']);
		}

		$template_manager = Plugin::instance()->template_manager;
		$templates = $template_manager->get_templates();

		foreach ($templates as $template) {
			$this->templates[] = array(
				'label' => $template['title'],
				'value' => $template['template_id'],
				'status' => $template['status'],
			);
		}

		wp_enqueue_media();
		wp_enqueue_style('brandnestor-settings');
		wp_enqueue_script('brandnestor-settings');

		wp_add_inline_script('brandnestor-settings', 'const BRANDNESTOR = ' . json_encode(array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('brandnestor_settings'),
		)), 'before');

		wp_localize_script('brandnestor-settings', 'BRANDNESTOR_I18N', array(
			'are_you_sure'          => __('Are you sure?', 'brandnestor'),
			'reset_plugin_message'  => __('Are you sure you want to reset the plugin? This will permanently delete all of the plugin data!', 'brandnestor'),
			'request_error_message' => __('An error occured during the request. See the developer console for details.', 'brandnestor'),
			'rule_deleted'          => __('Rule deleted', 'brandnestor'),
			'settings_saved'        => __('Settings saved!', 'brandnestor'),
			'menu_edit_hint'        => __('Click on any menu item to start editing its name...', 'brandnestor'),
			'select_placeholder'    => __('None Selected', 'brandnestor'),
			'disable_login_confirm' => __('I understand that disabling the default login page may prevent me from accessing my website, if not setup correctly.', 'brandnestor'),
		));

		$general = new SettingsGroup(
			'general',
			__('General', 'brandnestor'),
			__('General options.', 'brandnestor')
		);
		$this->set_general_fields($general);

		$dashboard = new SettingsGroup(
			'dashboard',
			__('Dashboard',	'brandnestor'),
			__('Customize the Dashboard page with your own Welcome Panel. You can choose to create it with standard HTML, or using a BrandNestor Template page.', 'brandnestor')
		);
		$this->set_dashboard_fields($dashboard);

		$description = __('Hide admin menu items for user roles and for users.', 'brandnestor');
		if (Functions::is_user_super_admin()) {
			$description .= '<br /><span style="color:var(--brandnestor-secondary-color)">'
				. __('This user account is a "BrandNestor Superadmin". Admin Menus will not be hidden or renamed for you.', 'brandnestor')
				. '</span>';
		}

		$admin_menus = new SettingsGroup(
			'admin_menus',
			__('Admin Menus', 'brandnestor'),
			$description
		);
		$this->set_admin_menus_fields($admin_menus);

		$admin_bar = new SettingsGroup(
			'admin_bar',
			__('Admin Bar', 'brandnestor'),
			__('Options for the admin bar on top of the page.', 'brandnestor')
		);
		$this->set_admin_bar_fields($admin_bar);

		$description = __('Enable custom login & registration pages for your users or clients. BrandNestor registers new Elementor Login, Registration Widgets, new Gutenberg Login and Register blocks that allow authentication to exist on the frontend by skipping using the wp-login.php page.', 'brandnestor');
		if (!get_option('permalink_structure')) {
			$description .= '<br /><span style="color:var(--brandnestor-danger-color)">'
				. sprintf(
					/** translators: %s documentation URL */
					__('WARNING: You do not have a permalink structure <strong>(currently set to plain URLs)</strong>. BrandNestor requires a permalink structure to work with the custom login and registration pages. <a href="%s" target="_blank" rel="noopener noreferrer">Read more<span class="dashicons dashicons-external"></span></a>', 'brandnestor'),
					'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#permalink-structure'
				)
				. '</span>';
		}

		$frontend = new SettingsGroup(
			'authentication',
			__('Log in'),
			$description
		);
		$this->set_frontend_fields($frontend);

		$advanced = new SettingsGroup(
			'advanced',
			__('Advanced', 'brandnestor'),
			__('Advanced BrandNestor options. Some options are experimental and <strong>are not recommended</strong> for novice users.', 'brandnestor')
		);
		$this->set_advanced_fields($advanced);

		$this->groups += array(
			0  => $general,
			10 => $dashboard,
			20 => $admin_menus,
			30 => $admin_bar,
			40 => $frontend,
			50 => $advanced,
		);

		ksort($this->groups);

		$settings_view = new View(BRANDNESTOR_DIR . 'includes/settings-page.php');
		$settings_view->render($this);
	}

	/**
	 * @return void
	 */
	public function ajax_save_settings() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$options = Options::instance();
		$input = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		try {
			$options->set($input);
		} catch (\Exception $e) {
			wp_send_json_error($e->getMessage());
		}

		$options->save();

		Functions::add_rewrite_rules();
		flush_rewrite_rules();
	}

	/**
	 * @return void
	 */
	public function ajax_add_admin_menus_rule() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$data = json_decode(wp_unslash($_POST['data']), true);

		$fields = isset($data['fields']) ? array_map('sanitize_text_field', $data['fields']) : array();
		$targets = isset($data['targets']) ? array_map('sanitize_text_field', $data['targets']) : array();

		// FIXME: For target, "users:username" may produce problems when
		//        username has special characters. We should prefer username
		//        to be replaced with user ID.
		$options->admin_menus_rules[] = array(
			'targets' => $targets,
			'fields'  => $fields,
		);

		$options->save();
	}

	/**
	 * @return void
	 */
	public function ajax_delete_admin_menus_rule() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$id = sanitize_text_field($_POST['id']);

		$options = Options::instance()->get_registered_option(DefaultOptions::class);
		unset($options->admin_menus_rules[$id]);
		$options->save();
	}

	/**
	 * @return void
	 */
	public function ajax_get_admin_menus_rules() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$rules = Options::instance()->get_registered_option(DefaultOptions::class)->admin_menus_rules;
		wp_send_json($rules);
	}

	/**
	 * @return void
	 */
	public function ajax_add_bar_menu_rule() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$data = json_decode(wp_unslash($_POST['data']), true);

		$fields = isset($data['fields']) ? array_map('sanitize_text_field', $data['fields']) : array();
		$targets = isset($data['targets']) ? array_map('sanitize_text_field', $data['targets']) : array();

		$options->bar_menu_rules[] = array(
			'targets' => $targets,
			'fields'  => $fields,
		);

		$options->save();
	}

	/**
	 * @return void
	 */
	public function ajax_delete_bar_menu_rule() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$id = sanitize_text_field($_POST['id']);

		$options = Options::instance()->get_registered_option(DefaultOptions::class);
		unset($options->bar_menu_rules[$id]);
		$options->save();
	}

	/**
	 * @return void
	 */
	public function ajax_get_bar_menu_rules() {
		check_ajax_referer('brandnestor_settings');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Current user does not have the required capability', 'brandnestor'));
		}

		$rules = Options::instance()->get_registered_option(DefaultOptions::class)->bar_menu_rules;
		wp_send_json($rules);
	}

	/**
	 * @param SettingsGroup $field_group
	 *
	 * @return void
	 */
	protected function set_general_fields($field_group) {
		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$field_group->add_field((new SettingsTextField(
			'brand_name',
			__('Brand Name', 'brandnestor'),
			__('Your Brand name. Will be used to replace any WordPress mentions in admin pages.', 'brandnestor')
			))->value($options->brand_name)
		);

		$field_group->add_field((new SettingsTextField(
			'footer_text',
			__('Footer Text', 'brandnestor'),
			__('Text to display on the WordPress admin footer.', 'brandnestor')
			))->value($options->footer_text)
		);

		$field_group->add_field((new SettingsImageField(
			'footer_image',
			__('Footer Image', 'brandnestor'),
			__('Image to display next to the footer text.', 'brandnestor')
			))->value($options->footer_image)
		);

		$field_group->add_field((new SettingsTextField(
			'footer_url',
			__('Footer URL', 'brandnestor'),
			__('The link URL for the footer text.', 'brandnestor')
			))->value($options->footer_url)
		);

		$field_group->add_field((new SettingsSwitchField(
			'hide_help',
			__('Hide Contextual Help', 'brandnestor'),
			__('Hide the WordPress dashboard contextual help box that is on the top of admin pages.', 'brandnestor'),
			__('Hide', 'brandnestor')
			))->value($options->hide_help)
		);

		$field_group->add_field((new SettingsSwitchField(
			'hide_version',
			__('Hide WordPress Version', 'brandnestor'),
			__('Hide the WordPress version number that is displayed on the footer.', 'brandnestor'),
			__('Hide', 'brandnestor')
			))->value($options->hide_version)
		);
	}

	/**
	 * @param SettingsGroup $field_group
	 *
	 * @return void
	 */
	protected function set_dashboard_fields($field_group) {
		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$field_group->add_field((new SettingsSwitchField(
			'dashboard_panel',
			__('Dashboard', 'brandnestor'),
			'',
			__('Enable', 'brandnestor')
			))->value($options->dashboard_panel)
		);

		$field_group->add_field((new SettingsSwitchField(
			'dashboard_hide_metaboxes',
			__('Hide Widgets', 'brandnestor'),
			__('Hide all Dashboard widgets.', 'brandnestor'),
			__('Hide', 'brandnestor')
			))->value($options->dashboard_hide_metaboxes)->condition('dashboard_panel')
		);

		$field_group->add_field((new SettingsRadioField(
			'dashboard_panel_type',
			__('Welcome Panel Type', 'brandnestor'),
			__('Choose between writing as HTML or with a BrandNestor Template as the type of the Dashboard Welcome Panel.', 'brandnestor'),
			array(
				array('label' => 'HTML', 'value' => 'html'),
				array('label' => __('Template', 'brandnestor'), 'value' => 'template'),
			)
			))->value($options->dashboard_panel_type)->condition('dashboard_panel')
		);

		$field_group->add_field((new SettingsSelectField(
			'dashboard_template',
			__('Template', 'brandnestor'),
			sprintf(
				/* translators: %s BrandNestor Templates URL */
				__('Choose a custom Welcome Panel from a BrandNestor Template page. <a href="%s">Create one from here.</a>', 'brandnestor'),
				admin_url('post-new.php?post_type=brandnestor_template')
			),
			$this->templates
			))->value($options->dashboard_template)->condition(array('dashboard_panel_type', 'template'))
		);

		$field_group->add_field((new SettingsCodeEditorField(
			'dashboard_html',
			__('Dashboard HTML', 'brandnestor'),
			__('Insert custom HTML that is going to be placed on the Dashboard page.', 'brandnestor'),
			))->value($options->dashboard_html)->condition(array('dashboard_panel_type', 'html'))
		);

		$field_group->add_field((new SettingsCodeEditorField(
			'dashboard_css',
			__('Dashboard CSS', 'brandnestor'),
			__('Insert custom CSS styling that is going to be placed on the Dashboard page.', 'brandnestor'),
			))->value($options->dashboard_css)->condition(array('dashboard_panel_type', 'html'))
		);
	}

	/**
	 * @param SettingsGroup $admin_menus
	 *
	 * @return void
	 */
	protected function set_admin_menus_fields($admin_menus) {
		$menus = AdminMenus::get_menus();
		$fields = array();

		foreach ($menus as $menu) {
			$field_options = array(
				'label' => $menu['label'],
				'name'  => json_encode(array(
					'option_name' => 'admin_menus',
					'menu'        => $menu['slug'],
				)),
			);

			foreach ($menu['submenus'] as $submenu) {
				$field_options['children'][] = array(
					'label' => $submenu['label'],
					'name'  => json_encode(array(
						'option_name' => 'admin_menus',
						'parent'      => $menu['slug'],
						'menu'        => $submenu['slug'],
					)),
				);
			}

			$field = new class($field_options) extends SettingsField {
				/** @var array<mixed> */
				protected $children;

				/** @param array<mixed> $options */
				public function __construct($options) {
					parent::__construct($options['name'], $options['label']);
					$this->children = $options['children'] ?? false;
				}

				public function start() {
					ob_start();
					?>
					<div class="form-group collapse">
						<label>
							<span class="editable" contenteditable spellcheck="false" data-placeholder="<?php esc_attr_e('Rename menu…', 'brandnestor') ?>"><?php echo esc_html($this->label) ?></span>
							<?php if ($this->children): ?>
								<a class="collapse-toggle"><i class="dashicons"></i></a>
							<?php endif ?>
						</label>
					<?php
					return ob_get_clean();
				}

				public function middle() {
					ob_start();
					?>
					<label class="form-input">
						<input
							type="checkbox"
							class="form-switch"
							name="<?php echo esc_attr($this->name) ?>"
							checked="checked"
							disabled="disabled"
						/>
						<?php esc_html_e('Enable', 'brandnestor') ?>
					</label>
					<?php
					return ob_get_clean();
				}

				public function end() {
					$out = '';
					if ($this->children) {
						$out .= '<ul>';
						foreach ($this->children as $child_options) {
							$field = new $this($child_options);
							$out .= '<li>' . $field->html() . '</li>';
						}
						$out .= '</ul>';
					}

					return $out . '</div>';
				}
			};

			$fields[] = $field;
		}

		$admin_menus->add_field(new SettingsRuleField(
			'admin_menus',
			__('Hide admin menus for:', 'brandnestor'),
			array(
				'roles' => __('Roles', 'brandnestor'),
				'users' => __('Users', 'brandnestor'),
			),
			array(
				'roles' => $this->roles,
				'users' => $this->users,
			),
			$fields
		));
	}

	/**
	 * @param SettingsGroup $field_group
	 *
	 * @return void
	 */
	protected function set_admin_bar_fields($field_group) {
		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$wp_roles = wp_roles();
		$roles = array();
		$admin_bar_option = $options->admin_bar;

		foreach ($wp_roles->roles as $key => $role) {
			$roles[] = array(
				'name'    => "admin_bar[{$key}]",
				'label'   => $role['name'],
				'checked' => isset($admin_bar_option[$key]) ? $admin_bar_option[$key] : 'off',
			);
		}

		$roles[] = array(
			'name'    => "admin_bar[guest]",
			'label'   => __('Guest', 'brandnestor'),
			'checked' => isset($admin_bar_option['guest']) ? $admin_bar_option['guest'] : 'off',
		);

		$field_group->add_field((new SettingsImageField(
			'branding_logo',
			__('Branding Logo', 'brandnestor'),
			__('Image logo to display on the Dashboard Admin Bar.', 'brandnestor')
			))->value($options->branding_logo)
		);

		$field_group->add_field((new SettingsTextField(
			'branding_logo_url',
			__('Branding Logo URL', 'brandnestor'),
			__('Link URL for the Branding Logo on the Dashboard Admin Bar.', 'brandnestor')
			))->value($options->branding_logo_url)
		);

		$field_group->add_field((new SettingsCheckboxesField(
			'bar_menu_visibility',
			__('Bar Menu Visibility', 'brandnestor'),
			__('With this, you can change the Admin Bar menu visibility for logged in or logged out users on the frontend.', 'brandnestor'),
			$roles
			))
		);

		$fields = array();
		$menus = AdminMenus::get_bar_menus();

		foreach ($menus as $key => $menu) {
			$field_options = array(
				'label' => $menu->title,
				'name'  => json_encode(array('menu' => $menu->id))
			);

			foreach ($menu->get_children() as $submenu) {
				$field_options['children'][] = array(
					'label' => $submenu->title,
					'name'  => json_encode(array(
						'parent' => $menu->id,
						'menu' => $submenu->id
					))
				);
			}

			$fields[] = new class($field_options) extends SettingsField {
				/** @var array<mixed> */
				protected $children;

				/**
				 * @param array<mixed> $options
				 */
				public function __construct($options) {
					parent::__construct($options['name'], $options['label']);
					$this->children = $options['children'] ?? false;
				}

				public function start() {
					ob_start();
					?>
					<div class="form-group collapse">
						<label>
							<?php echo esc_html($this->label) ?>
							<?php if ($this->children): ?>
								<a class="collapse-toggle"><i class="dashicons"></i></a>
							<?php endif ?>
						</label>
					<?php
					return ob_get_clean();
				}

				public function middle() {
					ob_start();
					?>
					<label class="form-input">
						<input
							type="checkbox"
							class="form-switch"
							name="<?php echo esc_attr($this->name) ?>"
							checked="checked"
							disabled="disabled"
						/>
						<?php esc_html_e('Enable', 'brandnestor') ?>
					</label>
					<?php
					return ob_get_clean();
				}

				public function end() {
					$out = '';

					if ($this->children) {
						$out .= '<ul>';
						foreach ($this->children as $child) {
							$field = new $this($child);
							$out .= '<li>' . $field->html() . '</li>';
						}
						$out .= '</ul>';
					}

					return $out . '</div>';
				}
			};
		}

		$field_group->add_field(new SettingsRuleField(
			'bar_menu_rules',
			__('Hide bar menus for:', 'brandnestor'),
			array(
				'roles' => __('Roles', 'brandnestor'),
				'users' => __('Users', 'brandnestor'),
			),
			array(
				'roles' => $this->roles,
				'users' => $this->users,
			),
			$fields
		));
	}

	/**
	 * @param SettingsGroup $field_group
	 *
	 * @return void
	 */
	protected function set_frontend_fields($field_group) {
		$options = Options::instance()->get_registered_option(DefaultOptions::class);
		$templates = array_filter($this->templates, function($template) {
			return $template['status'] !== 'private';
		});

		$field_group->add_field((new SettingsSelectField(
			'login_page_template',
			__('Login Page Template', 'brandnestor'),
			sprintf(
				/* translators: %1$s Add New BrandNestor Template URL, %2$s Shortcode help URL, %3$s Widget Help URL, %4$s Gutenber Blocks Help URL */
				__('Enable a custom Login Page for WordPress. Login URLs and redirects will point to this page. Create then select a <a href="%1$s">BrandNestor Template</a>, then add a login form to a page using the <a href="%2$s" target="_blank">brandnestor_login shortcode <span class="dashicons dashicons-external"></span></a>, the Elementor Widget <a href="%3$s" target="_blank">BrandNestor Login <span class="dashicons dashicons-external"></span></a>, or the <a href="%4$s" target="_blank">Gutenberg Login Block <span class="dashicons dashicons-external"></span></a>.', 'brandnestor'),
				admin_url('post-new.php?post_type=brandnestor_template'),
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#shortcodes',
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#elementor-widgets',
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#gutenberg-blocks'
			),
			$templates
			))->value($options->login_page_template)
		);

		$field_group->add_field((new SettingsTextField(
			'login_page_url',
			__('Login Page URL', 'brandnestor'),
			__('<strong>(Required)</strong> Set a custom URL slug for your custom Login page.', 'brandnestor'),
			site_url('/')
			))->value($options->login_page_url)->condition('login_page_template')
		);

		$field_group->add_field((new SettingsTextField(
			'login_redirect_to',
			__('Redirect URL On Login', 'brandnestor'),
			__('URL to redirect to on successful user login. <strong>Note</strong>: Only redirects to the same <strong>site domain</strong> are allowed.', 'brandnestor'),
			site_url('/')
			))->value($options->login_redirect_to)->condition('login_page_template')
		);

		$field_group->add_field((new SettingsSelectField(
			'register_page_template',
			__('Register Page Template', 'brandnestor'),
			sprintf(
				/* translators: %1$s Add New BrandNestor Template URL, %2$s Shortcode help URL, %3$s Widget Help URL, %4$s Gutenber Blocks Help URL */
				__('Enable a custom Register Page for WordPress. Register URLs and redirects will point to this page. Create then select a <a href="%1$s">BrandNestor Template</a>, then add a register form to a page using the <a href="%2$s" target="_blank">brandnestor_register shortcode <span class="dashicons dashicons-external"></span></a>, the Elementor Widget <a href="%3$s" target="_blank">BrandNestor Register <span class="dashicons dashicons-external"></span></a>, or the <a href="%4$s" target="_blank">Gutenberg Register Block <span class="dashicons dashicons-external"></span></a>.', 'brandnestor'),
				admin_url('post-new.php?post_type=brandnestor_template'),
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#shortcodes',
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#elementor-widgets',
				'https://gitlab.com/webnestors/brandnestor/-/wikis/Authentication#gutenberg-blocks'
			),
			$templates
			))->value($options->register_page_template)
		);

		$field_group->add_field((new SettingsTextField(
			'register_page_url',
			__('Register Page URL', 'brandnestor'),
			__('<strong>(Required)</strong> Set a custom URL slug for your custom Register page.', 'brandnestor'),
			site_url('/')
			))->value($options->register_page_url)->condition('register_page_template')
		);

		$field_group->add_field((new SettingsTextField(
			'register_redirect_to',
			__('Redirect URL On Register', 'brandnestor'),
			__('URL to redirect to on successful user registration. <strong>Note</strong>: Only redirects to the same <strong>site domain</strong> are allowed.', 'brandnestor'),
			site_url('/')
			))->value($options->register_redirect_to)->condition('register_page_template')
		);

		$field_group->add_field((new SettingsImageField(
			'wp_login_logo',
			__('WordPress Login Page Logo', 'brandnestor'),
			__('Replace the default WordPress login page logo with your own <strong>(84x84 pixels).</strong> Useful if there are plugins that rely on the default WP login form, such as Two Factor.', 'brandnestor'),
			))->value($options->wp_login_logo)
		);

		$field_group->add_field((new SettingsSwitchField(
			'disable_wp_login',
			__('Disable Default WordPress Login Page', 'brandnestor'),
			__('Disable the default WordPress login & register page. <span style="color:var(--brandnestor-danger-color)">WARNING: Make sure that your custom login and register pages work correctly, otherwise <strong>nobody</strong> will be able to sign in!</span>', 'brandnestor'),
			__('Disable', 'brandnestor')
			))->value($options->disable_wp_login)->condition('login_page_template')
		);

		$field_group->add_field((new SettingsSwitchField(
			'disable_wp_admin_redirect',
			__('Disable /wp-admin/ Page Redirect', 'brandnestor'),
			__('Disables /wp-admin/ to login page redirection for non-logged in users to proactively hide your login and registration pages as a security feature.', 'brandnestor'),
			__('Disable', 'brandnestor')
			))->value($options->disable_wp_admin_redirect)->condition('login_page_template')
		);
	}

	/**
	 * @param SettingsGroup $field_group
	 *
	 * @return void
	 */
	protected function set_advanced_fields($field_group) {
		$options = Options::instance()->get_registered_option(DefaultOptions::class);

		$field_group->add_field((new SettingsCodeEditorField(
			'admin_css',
			__('Custom CSS', 'brandnestor'),
			__('Add Custom CSS to all Admin pages.', 'brandnestor')
			))->value($options->admin_css)
		);

		$field_group->add_field((new SettingsSwitchField(
			'disable_rest_api',
			__('Disable REST API for Logged Out Users', 'brandnestor'),
			__('<strong>(Experimental)</strong> Disables all REST API endpoints for all unauthenticated users (Core WordPress enpoints only).', 'brandnestor'),
			__('Disable', 'brandnestor')
			))->value($options->disable_rest_api)
		);
	}

}
