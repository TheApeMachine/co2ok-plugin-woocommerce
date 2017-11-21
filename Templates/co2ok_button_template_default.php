<span class="co2ok_container" data-cart="<?php echo $cart ?>">
    <span class="co2ok_checkbox_container <?php echo ($co2_ok_session_opted == 1 ? 'selected' : 'unselected' )?>" style="display:block;">
        <?php
            woocommerce_form_field('co2ok_cart', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2_ok_session_opted);
        ?>

        <div id="checkbox_label" style="margin-left: 10px;display: inline-block;">
            <?php echo __( 'Make CO&#8322;ok for ', 'co2ok-for-woocommerce' ) ?>
                        <span class="compensation_amount">+<?php echo $currency_symbol.''. $surcharge ?> </span>
                        </div>
    </span>
                                                
                        <a target="_blank" href="http://co2ok.eco" style="display:inline-block;color:#606468; width: 245px; margin-top: 0px;">


                            <span style="display:inline-block;width: 110%;}">
                                <?php
                                    echo  __( 'Help build a better future', 'co2ok-for-woocommerce' );
                                ?>
                                <span id="p"  style="display:inline-block;">
                                    <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.png', 'co2ok_info', 'co2ok_info'); ?>
                                </span>
                            </span>


                            <?php
                            echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.png', 'co2ok_logo', 'co2ok_logo');
                            ?>

                        </a>



                    <div class="co2ok_infobox_container" style="width:1px;height:1px;overflow:hidden">
                        <img id="co2ok_info_hover_image" src="https://s3.eu-central-1.amazonaws.com/co2ok-static/info-hover_<?php echo get_locale(); ?>.png" />
                       <span> <a target="_blank" href="http://www.co2ok.eco/co2-compensatie"><?php
                               echo  __( 'How CO&#8322; compensation works', 'co2ok-for-woocommerce' );
                               ?></a> </span></div>
                  </span>
