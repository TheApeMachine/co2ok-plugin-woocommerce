<div class="co2ok_container co2ok_container_default" data-cart="<?php echo $cart ?>">

    <span class="co2ok_checkbox_container co2ok_checkbox_container_default <?php echo ($co2ok_session_opted == 1 ? 'selected' : 'unselected' )?>">
        <?php
            woocommerce_form_field('co2ok_cart', array(
                'type' => 'checkbox',
                'id' => 'co2ok_cart',
                'class' => array('co2ok_cart'),
                'required' => false,
            ), $co2ok_session_opted);
        ?>

        <div id="checkbox_label">
            <a href="#!" input type="button" role="button" tabindex="0" style="outline: none; -webkit-appearance: none;">
                <div class="inner_checkbox_label inner_checkbox_label_default co2ok_global_temp" id="default_co2ok_temp">
                    <div id="checkbox">
                    </div>

                    <span class="make make_co2ok_default"><?php echo __( 'Fight Corona ', 'co2ok-for-woocommerce' ); ?> </span>
                    
                    <span class="compensation_amount_default compensation_amount_global">+<?php echo $currency_symbol.''. $surcharge ?> </span>

                    <?php

                        $priceArr = str_split($surcharge);
                        $price_length = count($priceArr);

                     ?>

                </div>
            </a>
        </div>
    </span>



    <span class="co2ok_payoff">
        <span class="co2ok_payoff_text co2ok_adaptive_color_default">

        <?php 
            $merchantUrl = $_SERVER['SERVER_NAME'];
            $merchantName = ucfirst(explode(".", $merchantUrl)[0]);
            if (strpos($merchantUrl, 'naifcare') !== false) {
                $merchantName = "Naïf";
            }
            if (strpos($merchantUrl, '192') !== false) {
                $merchantName = "Deze shop";
            }
            if (strpos($merchantUrl, '54') !== false) {
                $merchantName = "Naïf";
            }
            ?>
        
                <span>
                    <?php
                        echo $merchantName . __( ' doubles your donation!', 'co2ok-for-woocommerce' );
                        ?>
                </span>
                <!-- <span>
                    <?php
                        echo  __( 'Make my purchase climate neutral', 'co2ok-for-woocommerce' );
                        ?>
                </span>
                <span>
                    <?php 

                    $variables = array(
                        '{COMPENSATION_COUNT}' => $compensation_count,
                        '{IMPACT}' => $impact_total);
                    echo strtr( __('{COMPENSATION_COUNT}x compensated; {IMPACT}t CO&#8322 reduction', 'co2ok-for-woocommerce' ), $variables); 
                    ?>
                </span>
                <span>
                    <?php 

                    $variables = array(
                        '{KM}' => $impact_total * 5000);
                    echo strtr( __('This is equivalent to {KM} km of flying ✈️', 'co2ok-for-woocommerce' ), $variables); 
                    ?>
              </span> -->
        </span>
        <a href="#!" input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea" style="outline: none; -webkit-appearance: none;">
        <span id="p">
            <span class="co2ok_info_hitarea">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info-gray.svg', 'co2ok_info', 'co2ok_info'); ?>
            </span>
        </span>
        </a>

        <div class="co2ok_infobox_container co2ok-popper" id="infobox-view">

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text first-text-to-select" style="outline: none; -webkit-appearance: none;">
        <p class="text-block greyBorder"><?php echo __('By clicking this button, you give us a donation which we will match and then pass on to the Red Cross Foundation. We want to help them combat the Corona crisis as well as possible.',  'co2ok-for-woocommerce' );?></p>
        </a>
        <?php //echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/fout.svg', 'svg-img', '  co2ok_info_hover_image', 'a3-notlazy'); ?>
        </div>

        <div class="inner-wrapper">
        <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/corona-hover.jpg', 'img-large', '  co2ok_info_hover_image', 'a3-notlazy'); ?>
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text" style="outline: none; -webkit-appearance: none;">
        <!-- <p class="text-block greyBorder"><?php echo __('CO&#8322ok prevents the same amount of emissions',  'co2ok-for-woocommerce' );?></p> -->
        </a>
        </div>

        <div class="inner-wrapper">
        <a href="#!" input type="text" role="button" tabindex="0" class="selectable-text" style="outline: none; -webkit-appearance: none;">
        <p class="text-block"><?php echo __('200% of your donation goes to the Red Cross!',  'co2ok-for-woocommerce' );?></p>
        </a>
        </div>

        <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo_default_info hover-link', 'co2ok_logo_default_info', 'a3-notlazy'); ?></a>
        <span class="hover-link">
          <a  class="hover-link" target="_blank" href="https://www.co2ok.eco/corona"><?php
            echo  __( 'more information on our site', 'co2ok-for-woocommerce' );
            ?></a> </span>
        </div>

        <?php if ( $co2ok_gif_feature == 'on' ): ?>
        <div class="co2ok_videoRewardBox_container" id="videoRewardBox-view">

            <video width="320" height="240" autoplay id="co2ok_videoReward" playsinline>
            <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderRandomizedVideo(); ?>
                Your browser does not support the video tag.
            </video>

        </div>
        <?php endif; ?>


    </span>


</div>
