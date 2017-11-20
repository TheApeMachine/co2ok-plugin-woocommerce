<span class="co2ok_container" data-cart="<?php echo $cart ?>">
    <span class="co2ok_checkbox_container <?php echo ($co2_ok_session_opted == 1 ? 'selected' : 'unselected' )?>" style="display:block;">
        <?php
            woocommerce_form_field('co2-ok', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2_ok_session_opted);
        ?>

        <span id="checkbox_label" style="margin-left: 10px;">

            <?php echo __( 'Make CO&#8322;ok for ', 'co2ok-for-woocommerce' ) ?>
                        <span class="compensation_amount"> <?php echo $currency_symbol.''. $surcharge ?> </span>
                        </span>
                        </span>
                                                
                        <a target="_blank" href="http://co2ok.eco" style="display:inline-block;color:#606468; width: 245px;">
                            <?php
                                echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.png', 'co2ok_logo', 'co2ok_logo');
                                echo  __( 'Help build a better future', 'co2ok-for-woocommerce' );
                            ?>
                        </a>

                        <span id="p"  style="display:inline-block;">
                            <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.png', 'co2ok_info', 'co2ok_info'); ?>
                        </span>

                    <div class="co2ok_infobox_container" style="width:1px;height:1px;overflow:hidden">
                        <img src="https://s3.eu-central-1.amazonaws.com/co2ok-static/info-hover_NL.png" />
                       <span> <a target="_blank" href="http://www.co2ok.eco/co2-compensatie">hoe CO&#8322; compensatie werkt&nbsp;</a> </span></div> 
                  </span>
