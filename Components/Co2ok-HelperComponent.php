<?php
namespace co2ok_plugin_woocommerce\Components;

if ( !class_exists( 'co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent' ) ) :

    class Co2ok_HelperComponent
    {
        public function __construct()
        {

        }

        public function RenderImage($uri, $class = null, $id = null)
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

            echo '<span class="co2ok_container" data-cart="' . $cart . '"> 
                        <span class="co2ok_checkbox_container">';

                            woocommerce_form_field('co2-ok', array(
                                'type' => 'checkbox',
                                'id' => 'co2-ok-cart',
                                'class' => array(
                                    'co2-ok-cart'
                                ),

                                'required' => false,
                            ), $woocommerce->session->co2ok);

                            $currency_symbol = get_woocommerce_currency_symbol();

                            echo '<span id="checkbox_label"> '. __( 'Make CO&#8322;ok for', 'co2ok-for-woocommerce' ).' <span class="compensation_amount">'.$currency_symbol.''. $surcharge . '</span> </span>
                           
                        </span>
                        
                        <span id="p">'

                        . __( 'Help build a better future', 'co2ok-for-woocommerce' );
                        echo $this->RenderImage('images/info.svg', 'co2-ok-info', 'co2-ok-info') .
                        '</span>'
                        .'<a target="_blank" href="http://co2ok.eco">'. $this->RenderImage('images/logo.svg', null, 'co2-ok-logo').'</a>'

                    . '<div class="co2ok_infobox_container" style="width:1px;height:1px;overflow:hidden"> <img src="https://s3.eu-central-1.amazonaws.com/co2ok-static/info-hover.png" /> 
                       <span> <a target="_blank" href="http://www.co2ok.eco/co2-compensatie">hoe CO&#8322; compensatie werkt&nbsp;</a> </span></div> 
                  </span>';

        }

    }

endif;
