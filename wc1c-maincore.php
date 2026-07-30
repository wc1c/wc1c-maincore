<?php
/**
 * Plugin Name: WC1C-Maincore
 * Plugin URI: https://wordpress.org/plugins/wc1c-maincore/
 * Description: Implementing a flexible mechanism for exchanging various data between 1C Company products and the WooCommerce plugin.
 * Version: 0.24.1
 * WC requires at least: 4.5
 * WC tested up to: 10.9
 * Requires at least: 5.3
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * Text Domain: wc1c-maincore
 * Domain Path: /assets/languages
 * Copyright: WC1C team © 2018-2026
 * Author: WC1C team
 * Author URI: https://wc1c.info
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 **/
namespace
{
	defined('ABSPATH') || exit;

	if(version_compare(PHP_VERSION, '7.4') < 0)
	{
        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
        trigger_error('Minimal PHP version for used plugin: 7.4. Please update PHP version.');
		return false;
	}

	if(false === defined('WC1C_PLUGIN_FILE'))
	{
		define('WC1C_PLUGIN_FILE', __FILE__);

		$wc1c_autoloader = __DIR__ . '/vendor/autoload.php';

		if(!is_readable($wc1c_autoloader))
		{
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
            trigger_error(sprintf('%1$s: %2$s','File is not found', esc_attr($wc1c_autoloader)));
			return false;
		}

		require_once $wc1c_autoloader;

        /**
         * Adds an action to declare compatibility with High Performance Order Storage (HPOS) before WooCommerce initialization.
         */
        add_action
        (
            'before_woocommerce_init',
            function()
            {
                if(class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class))
                {
                    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', WC1C_PLUGIN_FILE, true);
                }
            }
        );

        /**
         * For external use
         *
         * @return Wc1c\Main\Core Main instance of core
         */
		function wc1c(): Wc1c\Main\Core
		{
			return Wc1c\Main\Core::instance();
		}
	}
}

/**
 * @package Wc1c\Main
 */
namespace Wc1c\Main
{
	$loader = new \Digiom\Woplucore\Loader(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

    $loader->addNamespace(__NAMESPACE__, plugin_dir_path(__FILE__) . 'src');

	try
	{
		$loader->register(__FILE__);

		$loader->registerActivation([Activation::class, 'instance']);
		$loader->registerDeactivation([Deactivation::class, 'instance']);
		$loader->registerUninstall([Uninstall::class, 'instance']);
	}
	catch(\Throwable $e)
	{
        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
		trigger_error(esc_html($e->getMessage()));
		return false;
	}

	$context = new Context(__FILE__, 'wc1c', $loader); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

	wc1c()->register($context);
}