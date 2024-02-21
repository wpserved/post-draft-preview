<?php

/**
 * Plugin Name: Post Draft Preview
 * Plugin URI: https://wpserved.com/plugins/post-draft-preview/
 * Description: Allow non logged-in users to check a draft of unpublished post by using secret link
 * Version: 1.2.2
 * Author: wpserved
 * Author URI: https://wpserved.com/
 * Text Domain: pdp
 * Domain Path: /resources/lang
 * Requires at least: 5.5
 * Requires PHP: 7.4
 */

define('PDP_ROOT_PATH', dirname(__FILE__));
define('PDP_ASSETS_PATH', dirname(__FILE__) . '/dist');
define('PDP_RESOURCES_PATH', dirname(__FILE__) . '/resources');
define('PDP_ASSETS_URI', plugin_dir_url(__FILE__) . 'dist');
define('PDP_RESOURCES_URI', plugin_dir_url(__FILE__) . 'resources');
define('PDP_PREFIX', 'pdp_');

register_activation_hook(__FILE__, function () {
    do_action('pdp/plugin/activate');
});

register_deactivation_hook(__FILE__, function () {
    do_action('pdp/plugin/deactivate');
});

require PDP_ROOT_PATH . '/inc/bootstrap.php';
