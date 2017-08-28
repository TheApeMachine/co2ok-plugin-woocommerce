<?php
/**
 * Plugin Name: CO2ok WooCommerce Plugin
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 0.2.2
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

include( plugin_dir_path( __FILE__ ) . 'Co2ok_HelperComponent.php');

class Co2ok_Plugin
{
    /**
     * This plugin's version
     */
    const VERSION = '0.2.2';

    private $percentage = 0.0165;

    private $helperComponent;

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

        $this->helperComponent = new Co2ok_HelperComponent();

        /**
         * Check if WooCommerce is active
         **/
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters(
            'active_plugins', get_option( 'active_plugins' ) ) ) )
        {
            add_action('woocommerce_after_order_notes' ,array($this,'my_custom_checkout_field') );
            add_action('woocommerce_cart_calculate_fees', array($this,'woocommerce_custom_surcharge'));
            add_action('woocommerce_cart_collaterals' , array($this,'my_custom_cart_field'));

            /**
             * Register Front End
             */
            add_action( 'wp_enqueue_scripts', array($this,'co2_ok_stylesheet') );
            add_action( 'wp_enqueue_scripts', array($this,'co2_ok_javascript') );


        }
    }

    public function co2_ok_stylesheet()
    {
        wp_register_style( 'co2_ok_stylesheet', plugins_url('css/co2_ok.css', __FILE__) );
        wp_enqueue_style(  'co2_ok_stylesheet' );
    }
    public function co2_ok_javascript()
    {
        wp_register_script( 'co2_ok_js', plugins_url('js/co2_ok.js', __FILE__) );
        wp_enqueue_script(  'co2_ok_js',"" ,array(),null,true );
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

        echo '<h2>'.__('CO2 Compensation').'</h2>';
        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-cart',
            'class' => array(
                'co2-ok-cart'
            ),
            'label' =>
                __('<span class="co2_label"> Make'.$this->helperComponent->RenderImage('images/logo.svg',null,'co2-ok-logo')
                .' for €'.number_format($this->calculateSurcharge(), 2, ',', ' ')
                .$this->helperComponent->RenderImage('images/info.gif',null,'co2-ok-info').'</span>'
            ),
            'required' => false,
        ) ,$woocommerce->session->co2_ok);
    }

    public function my_custom_checkout_field( $checkout )
    {
        global $woocommerce;

        echo '<h2>' . __('CO2 Compensation') . '</h2>';
        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-checkout',
            'class' => array(
                'co2-ok'
            ),
            'label' =>
                __('<span class="co2_label"> Make'.$this->helperComponent->RenderImage('images/logo.svg',null,'co2-ok-logo')
                    .' for €'.number_format($this->calculateSurcharge(), 2, ',', ' ')
                    .$this->helperComponent->RenderImage('images/info.gif','co2-ok-info','co2-ok-info')
                    .'</span><div class="youtubebox" id="youtubebox" style="width:1px;height:1px;overflow:hidden"></div>'
        ),
            'required' => false,
        ), $woocommerce->session->co2_ok);
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
            $woocommerce->cart->add_fee( 'CO2 compensation', $this->calculateSurcharge(), true, '' );
        }
    }
}
$co2okPlugin = new Co2ok_Plugin();
register_activation_hook( __FILE__, array( 'Co2ok_Plugin', 'Activated' ) );
register_deactivation_hook( __FILE__, array( 'Co2ok_Plugin', 'Deactivated' ) );
?>
