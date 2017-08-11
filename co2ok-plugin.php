<?php
/**
 * Plugin Name: CO2ok WooCommerce Plugin
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 0.1.3
 *         (Remember to change the VERSION constant, below, as well!)
 * Author: Milo de Vries
 * Author URI: http://www.co2ok.eco/
 * License: GPLv2
 * @package co2ok-plugin-woocommerce
 *
 */

/**
 * Prevent data leaks
*/
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

 /**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Hook in
    add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields'
    );

    // Our hooked in function - $fields is passed via the filter!
    function custom_override_checkout_fields( $fields ) {
        $fields['billing']['co2ok_compensation_checkbox']['options'] = array(
            'option_1' => 'compensate',
            'option_2' => "don't compensate"
        );
        return $fields;
    }
}
?>
