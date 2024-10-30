=== BrandNestor ===
Contributors: webnestors, rwxnikos
Tags: whitelabel, branding, white label, dashboard, custom login, elementor, wp login
Requires at least: 5.6
Tested up to: 6.2
Requires PHP: 7.2
Stable tag: 2.2.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Customize the WordPress dashboard, admin pages, login and register pages, and more.

== Description ==

BrandNestor is aimed for companies and developers looking to give access to a simplified WordPress to their clients, tailored to their given workflow.

= Features =

- Add your own logo and remove WordPress mentions on the WordPress admin pages.
- Control which menus appear for your clients and rename them.
- Create custom Login and Register pages with the use of Elementor, Gutenberg blocks, or if you prefer a shortcode, using our custom BrandNestor Template pages.
- Flexible Elementor Widgets and Gutenberg blocks that add login and register forms on your page.
- Hide the default WordPress wp-login and wp-admin pages, to allow only sign-ons from your custom Login and Register pages.
- Create a custom Dashboard Welcome Panel using BrandNestor Templates or plain HTML.
- Dashboard customizability with custom CSS.
- And more.

= Builders First-class Support =

BrandNestor has first-class support for the following page builders:

- Elementor
- Gutenberg or WordPress Block Editor

= Compatibility =

The following plugins have been tested and **are compatible** with BrandNestor:

- WPS Hide Login (ver 1.9)
- Two Factor (ver 0.7.1)
- hCaptcha (ver 1.13.1)
- Advanced noCaptcha & invisible Captcha (ver 6.1.7)

**Important**:

If you're using a **page caching plugin** you should add the custom login and register URLs to the **Exclude** list.

== Frequently Asked Questions ==

= Who is BrandNestor aimed for? =

BrandNestor is aimed for companies and developers looking to give access to a simplified WordPress to their clients, tailored to their given workflow.

= Will my options get deleted if I deactive and uninstall BrandNestor? =

Yes, deleting BrandNestor entirely from your site will delete all options. This will be useful in case your login page is lost or not working anymore.

= How do I gain access to my Admin Panel back? =

In case you forgot your login URL, or if your login page is not working, you can access your site's files using FTP or a Control Panel File manager and then rename the "brandnestor" folder under `wp-content/plugins`, then login using the normal `wp-login.php` page and remove BrandNestor from "Plugins". Finally, download BrandNestor again and fix the corrected options.

= Where can I find documentation? =

You can read the [Wiki](https://gitlab.com/webnestors/brandnestor/-/wikis/home) page for documentation.

= I have an issue, where can I report it? =

It is recommended to report issues and request features on our [GitLab Issues](https://gitlab.com/webnestors/brandnestor/-/issues) page. Otherwise, you can use the official WordPress plugin's page [Support forum](https://wordpress.org/support/plugin/brandnestor/).

= Can I request a feature? =

If you've got a feature request, let us know at the project's [issues page](https://gitlab.com/webnestors/brandnestor/-/issues).

== Screenshots ==

1. A custom login page
2. A custom registration page
3. The Settings Menu
4. A custom Administrator Dashboard
5. Dashboard Settings
6. Hide admin menus
7. Admin Bar settings
8. Login and Registration settings
9. New Elementor Widgets

== Changelog ==

= 2.2.0 =
* Feature - Add admin bar menu hide rules
* Fix - Translation fixes

= 2.1.10 =
* Improve - Improve query performance in admin menus

= 2.1.9 =
* Fix - Fix compatibility with WordPress 6.1

= 2.1.8 =
* Fix - Fixed HTML entities shown on edited admin menus names
* Fix - Fixed some menu items being hidden when they are not supposed to be
* Tweak - Improve on Select Fields

= 2.1.7 =
* **Important** - If you have updated to version 2.1.6 please update immediately to version 2.1.7
* Fix - Fixed broken admin menus not showing when updating from 2.1.5 to 2.1.6

= 2.1.6 =
* Feature - Multi Select Fields for all BrandNestor rules. You can now add a single rule for multiple roles/users
* Feature - Preview image for image fields
* Feature - Add a confirmation modal to the "Disable Default WordPress Login Page" field
* Tweak - Do not delete options on plugin uninstall
* Tweak(Settings) - Tweaked descriptions
* Tweak(Settings) - Better input sanitization for settings
* Tweak(Settings) - Hint that the save button will need a page refresh to see changes
* Tweak(Settings) - Add email to list of users
* Fix - Improve notifications
* Fix - Improve loading indicator
* Fix - Fix errors getting thrown for some themes that use Gutenberg blocks
* Fix - admin_bar option "Guest" not working
* Refactor - More extendable BrandNestor internals
* Other minor improvements

= 2.1.5 =
* Fix - Fixed missing First & Last name inputs for the Register Widget

= 2.1.4 =
* Improve - Improved 404 template loading for a disabled /wp-admin/ or wp-login.php page
* Tweak - Add external link indicators and improve text explanations
* Tweak - Disallow common WordPress slugs to be used in BrandNestor URLs

= 2.1.3 =
* Feature - Add an image preview next to image input fields
* Tweak - Improve Gutenberg Dashboard style loading for blocks
* Tweak - Also show private templates for use with the custom Dashboard
* Tweak - Improve Greek language translation strings
* Fix - Do not break the site when option data from database is incompatible
* Mark as tested up to WordPress 5.9

= 2.1.2 =
* Fix - Fixed issue that allowed users to see the wp-login.php page
* Fix - Thrown error on login form submit fail
* Tweak - change label for dashboard_hide_help
* Internal tweaks and fixes

= 2.1.1 =
* Fix - Fixed not renaming of submenu item that is the same as the parent
* Fix - Fixed Export Settings menu item being hidden always when a hide menu rule is added
* Fix - Load BrandNestor Blocks styles in the editor
* Other fixes

= 2.1.0 =
* Feature - Add advanced options section
* Feature - Add new option to disable the REST API
* Feature - Add new option for Custom CSS on all administrator pages
* Enhancment - Performance improvement by caching the admin menus
* Tweak - Settings styling changes
* Minor changes

= 2.0.0 =
* Major Feature - Added BrandNestor Templates that will now work with Login, Register pages and the Dashboard
  * Now instead of selecting a Page, the user will select a custom Template post type that is going to to be used instead
  * The user will now create a custom Template and then select it from the Settings page to use it
  * The user can now set a custom URL slug for the login and register forms
* Feature - Added ability to rename Admin Menus for users and roles
* Feature - Added a repeat password field for the reset password form
* Feature - Added a weak password hint for the reset password form
* Tweak - Higher priority footer text
* Tweak - Stop loading block assets when not needed
* Tweak - Admin Bar Logo alignment on frontend
* Tweak - Improved UX for the login and register forms
* Tweak - Moved Settings to top level, tweaked the page layout
* Tweak - Remove dashboard title if a custom dashboard is enabled
* Other fixes
* Performance improvements

= 1.1.0 =
* Feature - Added new Gutenberg Login and Register widgets
* Feature - Added redirect options on user login and registration
* Feature - Added WordPress login page logo and text replace options
* Feature - Added the Brand Name to the WordPress login page title
* Enhancement - Added Two Factor plugin compatibility
* Enhancement - Added action hooks for the login and registration forms
* Fix - Show/hide password icon hidden from the frontend
* Fix - Cleaned up the admin menus' texts on the settings page
* Fix - Login and Register form Elementor styling fixes
* Fix - WPS Hide Login plugin compatibility

= 1.0.2 =
* Fix: Fixed login form styling errors

= 1.0.1 =
Fix: Fixed supported PHP version to 7.1

= 1.0.0 =
Released


