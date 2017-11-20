<?php
namespace co2ok_plugin_woocommerce\Components;

if ( !class_exists( 'co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent' ) ) :

    class Co2ok_HelperComponent
    {
        public function __construct()
        {

        }

        static public function RenderImage($uri, $class = null, $id = null)
        {
            $img_html = '<img src="' .esc_url(plugins_url($uri, __FILE__)) . '" ';
            $img_html = str_ireplace( '/Components', '', $img_html );
            if (isset($class))
                $img_html .= 'class="' . $class . '" ';
            if (isset($id))
                $img_html .= 'id="' . $id . '" ';

            return $img_html . '" />';
        }


        public function RenderCheckbox($surcharge, $cart)
        {
            global $woocommerce;

            $templateRenderer = new Co2ok_TemplateRenderer(plugin_dir_path(__FILE__).'../Templates/');

            echo $templateRenderer->render(get_option('co2ok_button_template', 'co2ok_button_template_default'),
                array('cart' => $cart,
                      'co2_ok_session_opted' =>  1,//$woocommerce->session->co2ok,
                      'currency_symbol' =>get_woocommerce_currency_symbol(),
                      'surcharge' => $surcharge
                )
            );
        }

    }

endif;
