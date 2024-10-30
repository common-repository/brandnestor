![](assets/image/logo_150.png)

This is the official git repository for BrandNestor on GitLab. You can [report issues](https://gitlab.com/webnestors/brandnestor/-/issues) and read the documentation on the [Wiki page](https://gitlab.com/webnestors/brandnestor/-/wikis/home).

BrandNestor enables custom branding customizability for you WordPress sites. It aims to be lightweight and simple to use and deploy.

## Features

- Add your own logo and remove WordPress mentions on the WordPress admin pages.
- Control which menus appear for your clients and rename them.
- Create custom Login and Register pages with the use of Elementor, Gutenberg blocks, or if you prefer a shortcode, using our custom BrandNestor Template pages.
- Flexible Elementor Widgets and Gutenberg blocks that add login and register forms on your page.
- Hide the default WordPress wp-login and wp-admin pages, to allow only sign ons from your custom Login and Register pages.
- Create a custom Dashboard Welcome Panel using BrandNestor Templates or plain HTML.
- Dashboard customizability with custom CSS.
- And more.

See the [Wiki](https://gitlab.com/webnestors/brandnestor/-/wikis/home) for more details.

## Builders First-class Support

BrandNestor has first-class support for the following page builders:

- Elementor
- Gutenberg or WordPress Block Editor

## Compatibility

The following plugins have been tested and **are compatible** with BrandNestor:

- WPS Hide Login (ver 1.9)
- Two Factor (ver 0.7.1)
- hCaptcha (ver 1.13.1)
- Advanced noCaptcha & invisible Captcha (ver 6.1.7)

**Important**:

If you're using a **page caching plugin** you should add the custom login and register URLs to the **Exclude** list.

## Install from package

You can download the prebuilt package from:

* **(Recommended)** The official WordPress plugin repo: https://wordpress.org/plugins/brandnestor/
* From GitLab releases under the **Packages** section: https://gitlab.com/webnestors/brandnestor/-/releases

Install the downloaded .zip package using the WordPress plugin installer.

## Install from source

There are some steps required to install this plugin to WordPress from source.

1. Clone this repository with `git clone https://gitlab.com/webnestors/brandnestor.git brandnestor` in the wp-contents/plugins directory.
2. `cd brandnestor`
3. _Optionally_, install composer dev-dependencies: `composer install`
4. Generate the autoload `composer dumpautoload -o`
5. Install npm dependencies with `npm install`
6. Build assets with `npm run build`

## Frequently Asked Questions

### Who is BrandNestor aimed for?

BrandNestor is aimed for companies and developers looking to give access to a simplified WordPress to their clients, tailored to their given workflow.

### Will my options get deleted if I deactive and uninstall BrandNestor?

Yes, deleting BrandNestor entirely from your site will delete all options. This will be useful in case your login page is lost or not working anymore.

### How do I gain access to my Admin Panel back?

In case you forgot your login URL, or if your login page is not working, you can access your site's files using FTP or a Control Panel File manager and then rename the "brandnestor" folder under `wp-content/plugins`, then login using the normal `wp-login.php` page and remove BrandNestor from "Plugins". Finally, download BrandNestor again and fix the corrected options.

### Where can I find documentation?

You can read the [Wiki](https://gitlab.com/webnestors/brandnestor/-/wikis/home) page for documentation.

### Where can I report issues and request features?

It is recommended to report issues and request features on our [GitLab Issues](https://gitlab.com/webnestors/brandnestor/-/issues) page. Otherwise, you can use the official WordPress plugin's page [Support forum](https://wordpress.org/support/plugin/brandnestor/).
