<?php

/**
 * Plugin Name: SBWC WooCommerce Order Confirmation Email Upsells
 * Description: This plugin adds upsell products to the WooCommerce order confirmation email.
 * Version: 1.0.0
 * Author: WC Bessinger
 * License: GPL2
 */

//  Exit if accessed directly.
defined('ABSPATH') or die();

// Define constants
define('SBWC_EMAIL_UPSELL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SBWC_EMAIL_UPSELL_PLUGIN_URI', plugin_dir_url(__FILE__));

// Plugins loaded
add_action('plugins_loaded', function () {

    // Check if WooCommerce is installed and activated
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            echo '<div class="error"><p>The SBWC WooCommerce Order Confirmation Email Upsells plugin requires WooCommerce to be installed and activated.</p></div>';
        });
        die();
    }

    // admin page
    include_once SBWC_EMAIL_UPSELL_PLUGIN_PATH . 'inc/functions/admin_menu.php';

    // email
    include_once SBWC_EMAIL_UPSELL_PLUGIN_PATH . 'inc/functions/order_confirmation_email_mod.php';
    
});
