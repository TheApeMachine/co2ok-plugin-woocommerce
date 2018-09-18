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
            $img_html = '<img alt="Maak mijn aankoop klimaatneutraal " title="Maak mijn aankoop klimaatneutraal " src="' .esc_url(plugins_url($uri, __FILE__)) . '" ';
            $img_html = str_ireplace( '/Components', '', $img_html );
            if (isset($class))
                $img_html .= 'class="' . $class . '" ';
            if (isset($id))
                $img_html .= 'id="' . $id . '" ';

            return $img_html . ' />';
        }

        static public function RenderRandomizedVideo()
        {
            $rewardVideo[] = array();
            $rewardVideo[1] = 'happy-flower';
            // $rewardVideo[2] = ''; // new video's can be put here

            $pickedVideo = rand(1,count($rewardVideo));

            $video_html = '<source src="/wp-content/plugins/co2ok-plugin-woocommerce/images/' . $rewardVideo[$pickedVideo] . '.mp4" type="video/mp4">';

            return $video_html;
        }

        public function RenderCheckbox($surcharge, $cart)
        {
            global $woocommerce;

            $templateRenderer = new Co2ok_TemplateRenderer(plugin_dir_path(__FILE__).'../Templates/');

            // Render checkbox / button according to admin settings
            echo $templateRenderer->render(get_option('co2ok_button_template', 'co2ok_button_template_default'),
            array('cart' => $cart,
                    'co2ok_session_opted' =>  $woocommerce->session->co2ok,
                    'currency_symbol' =>get_woocommerce_currency_symbol(),
                    'surcharge' => $surcharge
                )
            );
            

            
        }

    }

endif;
