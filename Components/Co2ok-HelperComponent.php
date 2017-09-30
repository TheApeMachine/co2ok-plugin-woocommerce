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

        /*
        public function RenderCheckbox($surcharge, $cart)
        {
            $html = '<span class="co2ok_container" data-cart="' . urlencode(json_encode($cart)) . '"> 
                <span class="co2ok_checkbox_container">
                    <input type="checkbox" id="co2-ok-cart" class="co2-ok" name="henk"/>    
                    <label for="c1" class="c1"><span></span>
                    <span id="checkbox"></span>
                    Maak CO2ok voor <span class="compensation_amount">€' . $surcharge . '</span>
                </span>
                <span id="p">Bouw je mee aan een betere toekomst ?' . $this->RenderImage('images/info.svg', 'co2-ok-info', 'co2-ok-info') . '</span>'
                . $this->RenderImage('images/logo.svg', null, 'co2-ok-logo')
                . '</span>
            
            <div class="info_container"></div>
            <div class="youtubebox_container" style="width:1px;height:1px;overflow:hidden"> <div class="youtubebox" id="youtubebox" width="400" height="300" ></div> </div> ';

            return $html;
        }
        */
    }

endif;