<?php
/**
 * Plugin Name: CO2ok for WooCommerce
 *
 * Description: A WooCommerce plugin to integrate CO2ok
 *
 * Plugin URI: https://github.com/Mil0dV/co2ok-plugin-woocommerce
 * GitHub Plugin URI: Mil0dV/co2ok-plugin-woocommerce
 * Version: 1.0.0.9
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
namespace co2ok_plugin_woocommerce;

/**
 * Prevent data leaks
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Check if class exists
if ( !class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' ) ) :

    class Co2ok_Plugin
    {
    /**
     * This plugin's version
     */
    const VERSION = '1.0.0.9';

    static $co2okApiUrl = "https://test-api.co2ok.eco/graphql";

    // Percentage is returned by the Middleware..
    private $percentage = 0;

    private $helperComponent;

    final static function registerMerchant()
    {
        $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

        $merchantName = $_SERVER['SERVER_NAME'];
        $merchantEmail = get_option('admin_email');

        $graphQLClient->mutation(function ($mutation) use ($merchantName, $merchantEmail)
        {
            $mutation->setFunctionName('registerMerchant');
            $mutation->setFunctionParams(array('name' => $merchantName, 'email' => $merchantEmail));
            $mutation->setFunctionReturnTypes(array('merchant' => array("secret", "id"), 'ok'));
        }
            , function ($response)// Callback after request
            {
                if(!is_array($response['body']))
                    $response = json_decode($response['body'], 1);

                if ($response['data']['registerMerchant']['ok'] == true)
                {
                    add_option('co2ok_id', sanitize_text_field($response['data']['registerMerchant']['merchant']['id']));
                    add_option('co2ok_secret', sanitize_text_field($response['data']['registerMerchant']['merchant']['secret']));
                }
                else // TO DO error handling...
                {
                    //Something went wrong.. we did not recieve a secret or id from the api.
                }
            });
    }

    //This function is called when the user activates the plugin.
    final static function co2ok_Activated()
    {
        $alreadyActivated = get_option('co2ok_id', false);

        if (!$alreadyActivated)
        {
            Co2ok_Plugin::registerMerchant();

        }
        else {
            // The admin has updated this plugin ..
        }
    }

    //This function is called when the user activates the plugin.
    static function co2ok_Deactivated()
    {
    }

    /**
     * Constructor.
     */
    final public function __construct()
    {
       // delete_option('co2ok_id');
       // delete_option('co2ok_secret');

        require_once(  plugin_dir_path( __FILE__ ) . '/co2ok-autoloader.php' );

        $this->helperComponent = new \co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent();

        /**
         * Check if WooCommerce is active
         **/
        if (in_array('woocommerce/woocommerce.php', apply_filters(
            'active_plugins', get_option('active_plugins'))))
        {
            add_action('woocommerce_after_order_notes',   array($this, 'co2ok_checkout_checkbox'));
            add_action('woocommerce_cart_collaterals',    array($this, 'co2ok_cart_checkbox'));
            add_action('woocommerce_cart_calculate_fees', array($this, 'co2ok_woocommerce_custom_surcharge'));

            /**
             * Woocommerce' state for an order that's accepted and should be
             * stored on our end is 'processing'
             */
            add_action('woocommerce_order_status_changed',
                array($this, 'co2ok_store_transaction_when_compensating'), 99, 3);

            /**
             * Register Front End
             */
            add_action('wp_enqueue_scripts', array($this, 'co2ok_stylesheet'));
            add_action('wp_enqueue_scripts', array($this, 'co2ok_javascript'));

            add_action('wp_ajax_nopriv_co2ok_ajax_set_percentage', array($this, 'co2ok_ajax_set_percentage'));
            add_action('wp_ajax_co2ok_ajax_set_percentage', array($this, 'co2ok_ajax_set_percentage'));

            // Check if merchant is registered, if for whatever reason this merchant is in fact not a registered merchant,
            // Maybe the api was down when this user registered the plugin, in that case we want to re-register !
            $alreadyActivated = get_option('co2ok_id', false);
            if (!$alreadyActivated)
                Co2ok_Plugin::registerMerchant();
        }
    }

    final public function co2ok_ajax_set_percentage()
    {
        if( empty($_POST) )
            die('Security check');

        global $woocommerce;

        $this->percentage = filter_var ( $_POST['percentage'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

        $woocommerce->session->percentage = $this->percentage;

        $return = array(
            'compensation_amount'	=> '€' . number_format($this->co2ok_calculateSurcharge(), 2, ',', ' ')
        );

        wp_send_json($return);
    }

    final public function co2ok_stylesheet()
    {
        wp_register_style('co2ok_stylesheet', plugins_url('css/co2ok.css', __FILE__).'?plugin_version='.self::VERSION);
        wp_enqueue_style('co2ok_stylesheet');
    }

    final public function co2ok_javascript()
    {
        wp_register_script('co2ok_js_cdn', 'https://s3.eu-central-1.amazonaws.com/co2ok-static/co2ok.js', null, null, true);
        wp_enqueue_script('co2ok_js_cdn');

        wp_register_script('co2ok_js_wp', plugins_url('js/co2ok-plugin.js', __FILE__).'?plugin_version='.self::VERSION);
        wp_enqueue_script('co2ok_js_wp', "", array(), null, true);
        wp_localize_script('co2ok_js_wp', 'ajax_object',
            array('ajax_url' => admin_url('admin-ajax.php')));
    }

    final private function co2ok_storeTransaction($order_id)
    {
        $order = wc_get_order($order_id);
        $fees = $order->get_fees();

        $compensationCost = 0;
        foreach ($fees as $fee) {
            if ($fee->get_name() == "CO&#8322; compensatie") {
                $compensationCost = $fee->get_total();
                break;
            }
        }

        $graphQLClient = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLClient(Co2ok_Plugin::$co2okApiUrl);

        $merchantId = get_option('co2ok_id', false);
        $orderTotal = $order->get_total();

        $graphQLClient->mutation(function ($mutation) use ($merchantId, $order_id, $compensationCost, $orderTotal) {
            $mutation->setFunctionName('storeTransaction');

            $mutation->setFunctionParams(
                array(
                    'merchantId' => $merchantId,
                    'orderId' => $order_id,
                    'compensationCost' => number_format($compensationCost, 2, '.', ''),
                    'orderTotal' => number_format($orderTotal, 2, '.', '')
                )
            );

            $mutation->setFunctionReturnTypes(array('ok'));
        }
            , function ($response)// Callback after request
            {
               // echo print_r($response,1);
                // TODO error handling
            });
    }

    final public function co2ok_store_transaction_when_compensating($order_id, $old_status, $new_status)
    {
        if ($new_status == "processing") {
            global $woocommerce;

            if ($woocommerce->session->co2ok == 1) {
                // The user did opt for co2 compensation
                $this->co2ok_storeTransaction($order_id);
            }
        }
    }

    final private function co2ok_calculateSurcharge()
    {
        global $woocommerce;

        if ($woocommerce->session->percentage)
            $this->percentage = $woocommerce->session->percentage;

        $surcharge = (($woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total) / 100) * $this->percentage;
        $surcharge = filter_var ( $surcharge, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // $surcharge *= 1.2;

        return $surcharge;
    }

    final private function co2ok_CartDataToJson()
    {
        global $woocommerce;
        $cart = array();

        $items = $woocommerce->cart->get_cart();
        foreach ($items as $item => $values)
        {
            $_product = $values['data'];

            $product_data = array();
            $product_data['name'] = $_product->name;
            $product_data['quantity'] = $values['quantity'];
            $product_data['brand'] = "";
            $product_data['description'] = $_product->description;
            $product_data['shortDescription'] = $_product->shortDescription;
            $product_data['sku'] = $_product->sku;
            $product_data['gtin'] = $_product->gtin;
            $product_data['price'] = $_product->price;
            $product_data['taxClass'] = $_product->taxClass;
            $product_data['weight'] = $_product->weight;
            $product_data['attributes'] = $_product->attributes;
            $product_data['defaultAttributes'] = $_product->defaultAttributes;

            $cart[] = $product_data;
        }

        return $cart;
    }

    final private function renderCheckbox()
    {
        global $woocommerce;
        $this->helperComponent->RenderCheckbox( esc_html(number_format($this->co2ok_calculateSurcharge() * 1.21, 2, ',', ' ') ) , esc_attr(urlencode(json_encode($this->co2ok_CartDataToJson())) ));
    }

    final public function co2ok_cart_checkbox()
    {
        $this->renderCheckbox();
    }

    final public function co2ok_checkout_checkbox()
    {
        $this->renderCheckbox();
    }

    final public function co2ok_woocommerce_custom_surcharge($cart)
    {
        global $woocommerce;

        if (isset($_POST['post_data'])) {
            parse_str($_POST['post_data'], $post_data);
        } else {
            $post_data = $_POST;
        }

        if (isset($post_data['co2-ok'])) {
            if ($post_data['co2-ok'] == 1) {
                $woocommerce->session->co2ok = 1;
            }
        } else if ($_POST)
            $woocommerce->session->co2ok = 0;

        if ($woocommerce->session->co2ok == 1) {
            $woocommerce->cart->add_fee('CO&#8322; compensatie', $this->co2ok_calculateSurcharge(), true, '');
        }
    }
}
endif; //! class_exists( 'co2ok_plugin_woocommerce\Co2ok_Plugin' )

$co2okPlugin = new \co2ok_plugin_woocommerce\Co2ok_Plugin();
register_activation_hook( __FILE__, array( 'co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Activated' ) );
register_deactivation_hook( __FILE__, array( 'co2ok_plugin_woocommerce\Co2ok_Plugin', 'co2ok_Deactivated' ) );
?>
