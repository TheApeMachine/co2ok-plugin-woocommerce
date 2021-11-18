var co2ok_temp_global = document.querySelector('.co2ok_global_temp');

if(document.querySelector('.qty') != null && document.querySelector(
  '.compensation_amount_default'
) != null) {
  defaultButton();
} else if(document.querySelector('.qty') != null && document.querySelector(
  '.compensation_amount_minimal') != null
) {
  minimumButton();
}

var Co2ok_JS = function () {
  var image_url = plugin.url;

  function getBackground(jqueryElement) {
    get_background_color(jqueryElement)
  }

  function calcBackgroundBrightness($) {
    return calculate_brightness(getBackground(
      jQuery("#co2ok_cart")
    )); //Grab the background colour of the element.
  }

  function adaptiveTextColor() {
    adaptiveStyleClass("co2ok_adaptive_color", calcBackgroundBrightness());
  };

  return {
    Init: function () {
      // check .co2ok_checkbox_container div has cfp-selected, if it does, 
      // button only needs to RegisterInfoBox().
      if (jQuery('.co2ok_container').hasClass('cfp-selected')) {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
        this.RegisterInfoBox();
        return;
      }

      this.RegisterBindings();
      this.RegisterInfoBox();
      this.RegisterRefreshHandling();

      var _this = this;

      jQuery(document).ready(function () {
        function compensationAmountTextSize() {
          _this.GetPercentageFromMiddleware();

          if(co2ok_temp_global.id == 'default_co2ok_temp') {
            defaultButton();
          } else {
            minimumButton();
          }
        }

        if(jQuery(".co2ok_container").length ) {
          compensationAmountTextSize();
        }

        jQuery( document.body ).on( 'updated_cart_totals', function() {
          compensationAmountTextSize();
        });

        _this.GetPercentageFromMiddleware();
      });

      if (!(jQuery('#co2ok_cart').is(":checked"))) {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
      }

      if(jQuery('#co2ok_cart').is(":checked")) {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
      }

      if(jQuery("#co2ok_cart").length) { 
        // if the co2ok cart is present, set text and logo based on 
        // background brightness;
        adaptiveTextColor();

        if(calcBackgroundBrightness() > 185) { 
          // Picks logo based on background brightness for minimal button design.
          jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo.svg');
        } else {
          jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo_licht.svg');
        }
      }
    },

    GetPercentageFromMiddleware: function() {
      var merchant_id = jQuery('.co2ok_container').attr('data-merchant-id');
      var CartData    = {
        products: build_cart(get_products(), [])
      }

      set_percentage(
        CO2ok.getFootprint(merchant_id, CartData)
      );
    },

    RegisterBindings: function() {
      jQuery('#co2ok_cart').click(function (event) {
        if (!(jQuery(this).is(":checked"))) {
          jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
          hideVideoRewardBox();
        }

        if(jQuery(this).is(":checked")) {
          jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');

          if (jQuery(".co2ok_videoRewardBox_container").length) {
            placeVideoRewardBox();
            showVideoRewardBox();

            jQuery('#co2ok_videoReward').on('ended',function(){
              hideVideoRewardBox();
            });
          }

          handle_checkout_styles();
        }


        jQuery('.woocommerce-cart-form, .woocommerce form').find(
          'input.qty'
        ).first().unbind();

        jQuery('.woocommerce-cart-form, .woocommerce form').find(
          'input.qty'
        ).first().bind('change', function() {
          // This timeout it to prevent multiple ajax calls when a user clicks multiple 
          // times (e.g. from 1 to 5 apples).
          // TODO: Would this be more robust setting a flag and unsetting when the ajax call
          // returns or errors out?
          setTimeout(function() {
            jQuery("[name='update_cart']").trigger("click");
          },200);
        });

        setTimeout(function() {
          jQuery('body').trigger('update_checkout');

          // Prevent update cart firing on cart+checkout pages.
          if (!jQuery('form.checkout').length) {
            // This fixes fee adding for shops with a disabled update cart button.
            jQuery("[name='update_cart']").removeAttr("disabled").trigger("click");
            jQuery("[name='update_cart']").trigger("click");
          }
        }, 200);

        jQuery('.woocommerce-cart-form').find('input.qty').first().trigger("change");
      }); // TODO: hmm, we're a little deep in the weeds here, can't even see the opener.

      jQuery('#co2ok_cart, #checkbox_label, .co2ok_checkbox_container').click(function(event) {
        if(!jQuery(this).is("#co2ok_cart")) {
          jQuery("[id='co2ok_cart']").trigger("click");
        }

        event.stopPropagation();
      }).find('.co2ok_info_hitarea').click(function (event) {
        event.stopPropagation();
      })
    },

    placeInfoBox:    placeInfoBox(),
    showInfoBox:     showInfoBox(),
    hideInfoBox:     hideInfoBox(),
    registerInfoBox: registerInfoBox(),

    modalRegex: has_classes(e, [
      "svg-img", "svg-img-large", "text-block", "inner-wrapper", 
      "co2ok_info", "co2ok_info_hitarea", "co2ok_infobox_container", "cfp-hovercard",
      "default-info-hovercard", "hover-link"
    ]),

    registerRefreshHandling: function() {
      // Some shops actually rerender elements such as our button upon cart update
      // this ofc breaks our bindings.
      jQuery(document.body).on('updated_cart_totals', function() {
        // detect if elements are bound:
        if (!jQuery._data(jQuery('.co2ok_checkbox_container').get(0), "events")) {
          console.log('Rebinding CO2ok')
          Co2ok_JS().RegisterBindings()
        }
      });
    },

    getCookieValue: function (a) {
      var b = document.cookie.match('(^|[^;]+)\\s*' + a + '\\s*=\\s*([^;]+)');
      return b ? b.pop() : '';
    },
  }
}

jQuery(document).ready(function() {
  // Checks wether A/B testing is enabled and dis/en-ables JS accordingly and removes 
  // the co2ok button.
  if (
    Co2ok_JS().getCookieValue('co2ok_ab_enabled') == 1 
    && !Co2ok_JS().getCookieValue('co2ok_ab_hide')
  ) {
    var future = new Date();
    future.setTime(future.getTime() + 30 * 24 * 3600 * 1000);

    var random_A_or_B = Math.round(Math.random());
    document.cookie = "co2ok_ab_hide=" + 
      random_A_or_B + 
      "; expires=" + 
      future.toUTCString() + 
      "; path=/";
  }

  if (
    Co2ok_JS().getCookieValue('co2ok_ab_enabled') == 1 && Co2ok_JS().getCookieValue('co2ok_ab_hide')
  ) {
    if (Co2ok_JS().getCookieValue('co2ok_ab_hide') % 2 == 0) {
      jQuery('.co2ok_container').remove();
      return;
    }
  }

  if(jQuery("#co2ok_cart").length) {
    Co2ok_JS().Init();
  }
});
