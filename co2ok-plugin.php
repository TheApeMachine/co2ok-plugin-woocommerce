<?php
/**
 * Plugin Name: CO2ok WooCommerce Plugin
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 0.3.8
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
include( plugin_dir_path( __FILE__ ) . '/Components/GraphQLClient.php');

class Co2ok_Plugin
{
    /**
     * This plugin's version
     */
    const VERSION = '0.3.9';

    static $co2okApiUrl = "https://api.co2ok.eco/graphql";

    // Percentage is returned by the Middleware..
    private $percentage = 0;

    private $helperComponent;

    //This function is called when the user activates the plugin.
    static function Activated()
    {
        $alreadyActivated = get_option( 'co2ok_id', false );
        //  $alreadyActivated = false;
        //  delete_option('co2ok_id');
        //  delete_option('co2ok_secret');

        if(!$alreadyActivated)
        {
            $graphQLClient = new GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

            $merchantName = $_SERVER['SERVER_NAME'];
            $merchantEmail = get_option('admin_email');

            $graphQLClient->mutation(function ($mutation) use ($merchantName, $merchantEmail) {
                $mutation->setFunctionName('registerMerchant');
                $mutation->setFunctionParams(array('name' => $merchantName, 'email' => $merchantEmail));
                $mutation->setFunctionReturnTypes(array('merchant' => array("secret", "id"), 'ok'));
            }
                , function ($response)// Callback after request
                {
                    $response = json_decode($response,1);
                    if($response['data']['registerMerchant']['ok'] == 1)
                    {
                        add_option('co2ok_id', $response['data']['registerMerchant']['merchant']['id']);
                        add_option('co2ok_secret', $response['data']['registerMerchant']['merchant']['secret']);
                    }
                    else // TO DO error handling...
                    {
                        //Something went wrong.. we did not recieve a secret or id from the api.
                    }
                });
        }
        else
        {
            // The admin has updated this plugin ..
        }
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
            * Woocommerce' state for an order that's accepted and should be
            * stored on our end is 'processing'
            */
            add_action('woocommerce_order_status_changed', 
                array($this,'co2ok_store_transaction_when_compensating') , 99, 3);

            /**
             * Register Front End
             */
            add_action( 'wp_enqueue_scripts', array($this,'co2ok_stylesheet') );
            add_action( 'wp_enqueue_scripts', array($this,'co2ok_javascript') );

            add_action( 'wp_ajax_nopriv_my_ajax_action',  array($this,'my_ajax_action') );
        }
    }

    public function my_ajax_action()
    {
        global $woocommerce;

        $this->percentage = $_POST['percentage'];
        $woocommerce->session->percentage = $_POST['percentage'];
    }

    public function co2ok_stylesheet()
    {
        wp_register_style( 'co2ok_stylesheet', plugins_url('css/co2ok.css', __FILE__) );
        wp_enqueue_style(  'co2ok_stylesheet' );
    }
    public function co2ok_javascript()
    {
        wp_register_script( 'co2ok_js_cdn', 'https://s3.eu-central-1.amazonaws.com/co2ok-static/co2ok.js', null, null, true );
        wp_enqueue_script('co2ok_js_cdn');

        wp_register_script( 'co2ok_js_wp', plugins_url('js/co2ok-plugin.js', __FILE__) );
        wp_enqueue_script(  'co2ok_js_wp',"" ,array(),null,true );
        wp_localize_script( 'co2ok_js_wp', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function storeTransaction( $order_id )
    {
            $order = wc_get_order( $order_id );
            $fees = $order->get_fees();

            $compensationCost = 0;
            foreach($fees as $fee)
            {
                if($fee->get_name() == "CO2 compensation")
                {
                    $compensationCost = $fee->get_total();
                    break;
                }
            }

            $graphQLClient = new GraphQLClient( Co2ok_Plugin::$co2okApiUrl );

            $merchantId = get_option( 'co2ok_id', false );
            $orderTotal = $order->get_total();

            $graphQLClient->mutation(function ($mutation) use ($merchantId, $order_id, $compensationCost,$orderTotal)
            {
                $mutation->setFunctionName('storeTransaction');

                $mutation->setFunctionParams(
                    array(
                        'merchantId' => $merchantId,
                        'orderId' => $order_id,
                        'compensationCost' => number_format($compensationCost,2,'.',''),
                        'orderTotal' => number_format($orderTotal,2,'.','')
                    )
                );

                $mutation->setFunctionReturnTypes(array('ok'));
            }
            , function ($response)// Callback after request
            {
               // TODO error handling
            });

    }

    public function co2ok_store_transaction_when_compensating( $order_id, $old_status, $new_status)
    {
        if( $new_status == "processing" ) {
            global $woocommerce;

            if ($woocommerce->session->co2ok == 1)
            {
                // The user did opt for co2 compensation
                $this->storeTransaction( $order_id );
            }
        }
    }

    final private function calculateSurcharge()
    {
        global $woocommerce;

        if ($woocommerce->session->percentage)
            $this->percentage = $woocommerce->session->percentage;

        $surcharge = ( ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) /100 )* $this->percentage;

        return $surcharge;
    }

    public function getCartData()
    {
        global $woocommerce;
        $cart = array();

        $items = $woocommerce->cart->get_cart();
        foreach($items as $item => $values)
        {
            $_product = $values['data'];

            $product_data = array();
            $product_data['name'] = $_product->name;
            $product_data['quantity'] = $values['quantity'];
            $product_data['brand'] = "";
            $product_data['description'] = $_product->description;
            $product_data['shortDescription'] = $_product->shortDescription;
            $product_data['sku']  = $_product->sku;
            $product_data['gtin']  = $_product->gtin;
            $product_data['price'] = $_product->price;
            $product_data['taxClass'] = $_product->taxClass;
            $product_data['weight'] = $_product->weight;
            $product_data['attributes'] = $_product->attributes;
            $product_data['defaultAttributes'] = $_product->defaultAttributes;

            $cart[] = $product_data;
        }

        return $cart;
    }

    public function my_custom_cart_field()
    {
        global $woocommerce;

        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }

        echo '<span class="co2ok_container" data-cart="'.urlencode(json_encode( $this->getCartData() )).'"></span>';
        echo '<h2>' . __('CO2 Compensation') . '</h2>';

        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-cart',
            'class' => array(
                'co2-ok-cart'
            ),
            'label' =>
                __('<span class="co2_label"> Maak'.$this->helperComponent->RenderImage('images/logo.svg',null,'co2-ok-logo')
                    .' voor <span class="compensation_amount">€'.number_format($this->calculateSurcharge(), 2, ',', ' ').'</span>'
                    .$this->helperComponent->RenderImage('images/info.svg','co2-ok-info','co2-ok-info')
                    .'</span><div class="youtubebox_container" style="width:1px;height:1px;overflow:hidden"> <div class="youtubebox" id="youtubebox" width="400" height="300" ></div> </div>'
                ),
            'required' => false,
        ) ,$woocommerce->session->co2ok);

        //echo $this->helperComponent->RenderCheckbox(number_format($this->calculateSurcharge(), 2, ',', ' '),$cart);
    }

    public function my_custom_checkout_field( $checkout )
    {
        global $woocommerce;

        echo '<span class="co2ok_container" data-cart="'.urlencode(json_encode( $this->getCartData() )).'"></span>';
        echo '<h2>' . __('CO2 Compensation') . '</h2>';
        woocommerce_form_field('co2-ok', array(
            'type' => 'checkbox',
            'id' => 'co2-ok-checkout',
            'class' => array(
                'co2-ok'
            ),
            'label' =>
                __('<span class="co2_label"> Maak'.$this->helperComponent->RenderImage('images/logo.svg',null,'co2-ok-logo')
                    .' voor <span class="compensation_amount">€'.number_format($this->calculateSurcharge(), 2, ',', ' ').'</span>'
                    .$this->helperComponent->RenderImage('images/info.svg','co2-ok-info','co2-ok-info')
                    .'</span><div class="youtubebox_container" style="width:1px;height:1px;overflow:hidden"> <div class="youtubebox" id="youtubebox" width="400" height="300" ></div> </div>'
        ),
            'required' => false,
        ), $woocommerce->session->co2ok);
    }

    public function woocommerce_custom_surcharge( $cart )
    {
        //$this->storeTransaction();
        //Co2ok_Plugin::Activated();
        global $woocommerce;

        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }

        if( isset($post_data['co2-ok']) ) {
            if ($post_data['co2-ok'] == 1) {
                $woocommerce->session->co2ok = 1;
            }
        }
        else if($_POST)
            $woocommerce->session->co2ok = 0;

        if ($woocommerce->session->co2ok == 1)
        {
            $woocommerce->cart->add_fee( 'CO2 compensation', $this->calculateSurcharge(), true, '' );
        }
    }
}
$co2okPlugin = new Co2ok_Plugin();
register_activation_hook( __FILE__, array( 'Co2ok_Plugin', 'Activated' ) );
register_deactivation_hook( __FILE__, array( 'Co2ok_Plugin', 'Deactivated' ) );
?>
