export default class Binding {

  register() {
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
    });
  }
  
}
