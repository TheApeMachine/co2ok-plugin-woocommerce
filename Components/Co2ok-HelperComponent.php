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
                                'label' =>
                                    __(''),
                                'required' => false,
                            ), $woocommerce->session->co2ok);

                            echo '<span id="checkbox"></span>
                            Maak CO&#8322;ok voor <span class="compensation_amount">€' . $surcharge . '</span>
                        </span>
                        
                        <span id="p">
                            Bouw mee aan een betere toekomst!' . $this->RenderImage('images/info.svg', 'co2-ok-info', 'co2-ok-info') .
                        '</span>'
                        .'<a target="_blank" href="http://co2ok.eco">'. $this->RenderImage('images/logo.svg', null, 'co2-ok-logo').'</a>'

                    . '</span>
            
            <div class="co2ok_infobox_container" style="width:1px;height:1px;overflow:hidden"> <img src="https://s3.eu-central-1.amazonaws.com/co2ok-static/info-hover.png" /> </div> ';

        }

    }

endif;
