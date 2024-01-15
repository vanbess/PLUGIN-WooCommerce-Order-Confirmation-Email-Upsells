<?php

/**
 * Plugin Name: SBWC WooCommerce Order Confirmation Email Upsells
 * Description: This plugin adds upsell products to the WooCommerce order confirmation email.
 * Version: 1.0.1
 * Author: WC Bessinger
 * License: GPL2
 */

//  Exit if accessed directly.
defined('ABSPATH') or die();

// Define constants
define('SBWC_EMAIL_UPSELL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SBWC_EMAIL_UPSELL_PLUGIN_URI', plugin_dir_url(__FILE__));

// Create custom database table for tracking conversions
function sbwc_email_upsell_create_table()
{

    global $wpdb;

    $table_name      = $wpdb->prefix . 'sbwc_email_upsell_conversions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        send_count int(11) NOT NULL DEFAULT 0,
        click_count int(11) NOT NULL DEFAULT 0,
        conversion_rate float(5,2) NOT NULL DEFAULT 0.00,
        PRIMARY KEY (id),
        UNIQUE KEY post_id (post_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
}

register_activation_hook(__FILE__, 'sbwc_email_upsell_create_table');

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

    // wp footer tracking update code
    include_once SBWC_EMAIL_UPSELL_PLUGIN_PATH . 'inc/functions/footer-tracking.php';

    // register polylang strings
    $strings = [
        'Current Special Offers:',
        'You Might Be Interested In:'
    ];

    if (function_exists('pll_register_string')) :
        foreach ($strings as $string) :
            pll_register_string($string, $string, 'SBWC Email Upsells');
        endforeach;
    endif;
});
