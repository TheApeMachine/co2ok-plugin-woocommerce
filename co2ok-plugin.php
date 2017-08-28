<?php
/**
 * Plugin Name: CO2ok WooCommerce Plugin
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 0.2.1
 *         (Remember to change the VERSION constant, below, as well!)
 * Author:
 * Chris Fuller,
 * Milo de Vries
 *
 * Author URI: http://www.co2ok.eco/
 * License: GPLv2
 * @package co2ok-plugin-woocommerce
 *
 */

class Co2ok_Plugin
{
    /**
     * This plugin's version
     */
    const VERSION = '0.2.1';

    private $percentage = 0.0165;

    //This function is called when the user activates the plugin.
    static function Activated()
    {

    }
    //This function is called when the user activates the plugin.
    static function Deactivated()
    {

    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        /**
         * Prevent data leaks
         */
        if ( ! defined( 'ABSPATH' ) ) {
            exit; // Exit if accessed directly
        }

        /**
         * Check if WooCommerce is active
         **/
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters(
            'active_plugins', get_option( 'active_plugins' ) ) ) )
        {
            add_action('woocommerce_after_order_notes' ,array($this,'my_custom_checkout_field') );
            add_action('woocommerce_cart_calculate_fees', array($this,'woocommerce_custom_surcharge'));
            add_action('woocommerce_cart_collaterals' , array($this,'my_custom_cart_field'));
        }
    }

    final private function calculateSurcharge()
    {
        global $woocommerce;

        $surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $this->percentage;

        return $surcharge;
    }

    public function my_custom_cart_field($cart)
    {
        global $woocommerce;

        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }
        echo '<div id="my_custom_checkout_field"><h2>'.__('CO2 Compensatie').'</h2>';

        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-cart',
            'class' => array(
                'co2-ok-cart'
            ) ,
            'label' => __('Ik wil de CO2 voor mijn product(en) compenseren voor €'.number_format($this->calculateSurcharge(), 2, ',', ' ')),
            'required' => false,
        ) ,$woocommerce->session->co2_ok);

        echo '</div>
            <script>
                 jQuery(\'#co2-ok-cart\').click(function ()
                 {
                    if(jQuery(this).is(":checked"))
                        jQuery(\'.woocommerce-cart-form\').append(\'<input type="checkbox" class="input-checkbox " name="co2-ok" id="co2-ok" checked value="1" style="display:none">\');
                    jQuery(\'.woocommerce-cart-form\').find(\'input\').trigger("change");
                    jQuery("[name=\'update_cart\']").trigger("click");
                });
            </script>';
    }

    public function my_custom_checkout_field( $checkout )
    {
        global $woocommerce;

        echo '<div id="co2-ok"><h2>' . __('CO2 Compensatie') . '</h2>';
        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-checkout',
            'class' => array(
                'co2-ok'
            ),
            'label' => __('Ik wil de CO2 voor mijn product(en) compenseren voor <span id="co2-amount">€'.number_format($this->calculateSurcharge(), 2, ',', ' ')."</span>"),
            'required' => false,
        ), $woocommerce->session->co2_ok);

        echo '</div>
        <script>
            jQuery(\'#co2-ok-checkout\').click(function ()
            {
                jQuery(\'body\').trigger(\'update_checkout\');
            });
        </script>';
    }

    public function woocommerce_custom_surcharge( $cart )
    {
        global $woocommerce;

        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }

        if( isset($post_data['co2-ok']) ) {
            if ($post_data['co2-ok'] == 1) {
                $woocommerce->session->co2_ok = 1;
            }
        }
        else if($_POST)
            $woocommerce->session->co2_ok = 0;

        if ($woocommerce->session->co2_ok == 1)
        {
            $woocommerce->cart->add_fee( 'CO2 compensatie', $this->calculateSurcharge(), true, '' );
        }
    }
}
$co2okPlugin = new Co2ok_Plugin();
register_activation_hook( __FILE__, array( 'Co2ok_Plugin', 'Activated' ) );
register_deactivation_hook( __FILE__, array( 'Co2ok_Plugin', 'Deactivated' ) );
?>
