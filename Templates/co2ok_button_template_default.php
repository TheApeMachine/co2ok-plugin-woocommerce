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

                    <span class="make make_co2ok_default"><?php echo __( 'Make ', 'co2ok-for-woocommerce' ); ?> </span>
                    <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'co2ok_logo', 'co2ok_logo_default', 'co2ok_logo', 'skip-lazy'); ?>
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
                <span>
                    <?php
                        echo  __( 'Make my purchase climate friendly', 'co2ok-for-woocommerce' );
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
              </span>
        </span>
        <a href="#!" input type="button" role="button" tabindex="0" class="co2ok_info_keyboardarea" style="outline: none; -webkit-appearance: none;">
        <span id="p">
            <span class="co2ok_info_hitarea">
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/info.svg', 'co2ok_info', 'co2ok_info'); ?>
            </span>
        </span>
        </a>

        <div class="co2ok_infobox_container co2ok-popper checkout-hovercard infobox-hidden" id="infobox-view">

            <div class="co2ok-hovercard-exit checkout-hovercard">
                <!-- <img alt="exit" title="exit_hovercard" src="${_this.image_url}/exit.png" class="co2ok-exit-button checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/exit.png', 'co2ok-exit-button', 'co2ok-exit-button checkout-hovercard', 'a3-notlazy'); ?>
            </div>

            <div class="hovercard-wrapper checkout-hovercard">
                <!-- <img alt="Production emissions" title="Production emissions" src="${_this.image_url}/factory.png" class="info-hover-png png-right checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/factory.png', 'info-hover-png', 'info-hover-png png-right checkout-hovercard', 'a3-notlazy'); ?>
                <p class="steps step-one left checkout-hovercard" style="padding-top: 19px">
                    <?php echo __('Every product has a climate impact through transport and production',  'co2ok-for-woocommerce' );?>>

                </p>
            </div>

            <div class="hovercard-road checkout-hovercard">
                <!-- <img alt="road" title="Production emissions" src="${_this.image_url}/gray_road.png" class="info-hover-road-png top checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/gray_road.png', 'info-hover-road-png', 'info-hover-road-png top-road checkout-hovercard', 'a3-notlazy'); ?>
            </div>

            <div class="hovercard-wrapper checkout-hovercard">
                <!-- <img alt="Shipping emissions" title="Shipping emissions" src="${_this.image_url}/green_truck.png" class="info-hover-png png-left png-truck checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_truck.png', 'info-hover-png', 'png-left info-hover-png png-truck checkout-hovercard', 'a3-notlazy'); ?>
                <p class="steps step-two right checkout-hovercard" style="padding-top: 40px;">
                    <?php echo __('By financing projects that prevent the same amount of emissions, this webshop neutralises these effects',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <div class="hovercard-road checkout-hovercard">
                <!-- <img alt="road" title="Production emissions" src="${_this.image_url}/green_road_left.png" class="info-hover-road-png middle checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_road_left.png', 'info-hover-road-png', 'info-hover-road-png middle-road checkout-hovercard', 'a3-notlazy'); ?>

            </div>

            <div class="hovercard-wrapper checkout-hovercard">
                <!-- <img alt="checkmark" title="checkmark" src="${_this.image_url}/checkmark11.png" class="info-hover-png png-button checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/checkmark11.png', 'info-hover-png', 'png-button info-hover-png checkout-hovercard', 'a3-notlazy'); ?>
                <p class="steps step-three left checkout-hovercard" style="padding-bottom: 53px; padding-top: 32px;">
                    <?php echo __('By financing projects that prevent the same amount of emissions, this webshop neutralises these effects',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <div class="hovercard-road checkout-hovercard">
                <!-- <img alt="road" title="Production emissions" src="${_this.image_url}/green_road_right.png" class="info-hover-road-png bottom checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/green_road_right.png', 'info-hover-road-png', 'bottom-road info-hover-road-png checkout-hovercard', 'a3-notlazy'); ?>
            </div>

            <div class="hovercard-wrapper checkout-hovercard">
                <!-- <img alt="Compensate production" title="Compensate production" src="${_this.image_url}/renewable_energy.png" class="info-hover-png png-left png-renewable checkout-hovercard"> -->
                <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/renewable_energy.png', 'info-hover-png', 'info-hover-png png-left png-renewable checkout-hovercard', 'a3-notlazy'); ?>
                <p class="steps step-four right checkout-hovercard" style="padding-bottom: 22px; margin-top: -5px;">
                    <?php echo __('That means you can shop guilt-free and together we help the climate 💚',  'co2ok-for-woocommerce' );?>
                </p>
            </div>

            <!-- <img class="logo-hovercard checkout-hovercard" href="http://www.co2ok.eco/co2-compensatie" src="${_this.image_url}/logo.svg"> -->
            <a class="hover-link" target="_blank" href="http://co2ok.eco"><?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/logo.svg', 'logo-hovercard', 'logo-hovercard checkout-hovercard', 'a3-notlazy'); ?></a>

            <span class="button-hovercard-links checkout-hovercard">
                <a class="co2ok-button checkout-hovercard" href="http://www.co2ok.eco/co2-compensatie"><?php
                    echo  __( 'How CO&#8322; compensation works', 'co2ok-for-woocommerce' );
                ?></a>
            </span>
            <!-- <img class="branch-png checkout-hovercard" src="${_this.image_url}/branch.png"> -->
            <?php echo co2ok_plugin_woocommerce\Components\Co2ok_HelperComponent::RenderImage('images/branch.png', 'branch-png', 'branch-png checkout-hovercard', 'a3-notlazy'); ?>

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
