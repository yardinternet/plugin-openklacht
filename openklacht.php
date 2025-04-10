<?php

declare(strict_types=1);

/**
 * Plugin Name:       Yard | OpenKlacht
 * Plugin URI:        https://www.yard.nl/
 * Description:       OpenKlacht implementation
 * Version:           1.0.3
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           EUPL-1.2
 * License URI:       https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 * Text Domain:       openklacht
 * Domain Path:       /languages
 */

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

if (!class_exists(OWC\OpenKlacht\OpenKlachtServiceProvider::class)) {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    } else {
        require_once __DIR__ . '/autoloader.php';
        $autoloader = new Autoloader();
    }
}

define('OWC_OPENKLACHT_ROOT_PATH', __DIR__);
define('OWC_OPENKLACHT_PREFIX', 'okl');

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
	add_action('after_setup_theme', function () {
    	(new OWC\OpenKlacht\OpenKlachtServiceProvider())->register();
	});
}, 10);
