<?php
/**
 * Plugin Name: CO2ok WooCommerce Plugin
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 0.1.4
 *         (Remember to change the VERSION constant, below, as well!)
 * Author: Milo de Vries
 * Author URI: http://www.co2ok.eco/
 * License: GPLv2
 * @package co2ok-plugin-woocommerce
 *
 */

/**
 * This plugin's version
 */
const VERSION = '0.1.4';

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

    add_action( 'woocommerce_review_order_after_shipping', 'co2ok_compensation');

    $checked = '';
    function co2ok_compensation() { echo '<tr id="carbon-item">
        <th>'.__('Carbon Offset','woocommerce').'<a class="question-mark tip" href="http://co2ok.eco" target="_blank">
        <span>Cool story about this offsetting stuff.</span>
        </a></th>
        <td><input type="checkbox" name="co2-ok" id="co2-ok" '.$checked.' /></td>
    </tr>';

    echo '</td></tr>';
    }
}
?>
