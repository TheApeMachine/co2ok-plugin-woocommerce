import Global from './globals.js';
import Meta from './lib/meta.js';
import Conditional from './helpers/style_classes.js';
import Buttons from './helpers/buttons.js';
import InfoBox from './models/infobox.js';
import Refresh from './handlers/refresh.js';
import Colors from './colors.js';
import Product from './models/product.js';

const co2ok_global = new Global();
const co2ok_temp_global = document.querySelector('.co2ok_global_temp');
const buttons = new Buttons([
  ['.qty', '.compensation_amount_default', 'defaultButton'],
  ['.qty', '.compensation_amount_minimal', 'minimumButton']
]);
const infobox = new InfoBox(co2ok_global);
const conditional = new Conditional();
const refresh = new Refresh();
const colors = new Colors();
const product = new Product();

function modalRegex(e) {
  return conditional.has_classes(e, [
    "svg-img", "svg-img-large", "text-block", "inner-wrapper", 
    "co2ok_info", "co2ok_info_hitarea", "co2ok_infobox_container", "cfp-hovercard",
    "default-info-hovercard", "hover-link"
  ]);
}

var Co2ok_JS = function() {
  var image_url = plugin.url;

  return {
    Init: function () {
      // check .co2ok_checkbox_container div has cfp-selected, if it does, 
      // button only needs to RegisterInfoBox().
      if (jQuery('.co2ok_container').hasClass('cfp-selected')) {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
        infobox.register(modalRegex);
        return;
      }

      infobox.register(modalRegex);
      refresh.do();

      var _this = this;

      jQuery(document).ready(function () {
        function compensationAmountTextSize() {
          _this.GetPercentageFromMiddleware();

          if(co2ok_temp_global.id == 'default_co2ok_temp') {
            buttons.defaultButton();
          } else {
            buttons.minimumButton();
          }
        }

        if(jQuery(".co2ok_container").length ) {
          compensationAmountTextSize();
        }

        jQuery(document.body).on('updated_cart_totals', function() {
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
        // // if the co2ok cart is present, set text and logo based on 
        // // background brightness;
        // colors.adaptiveStyleClass("co2ok_adaptive_color",
        //   colors.calculate_brightness(colors.get_background_color(
        //     jQuery("#co2ok_cart")
        //   ))
        // );

        jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo.svg');
      }
    },

    GetPercentageFromMiddleware: function() {
      var merchant_id = jQuery('.co2ok_container').attr('data-merchant-id');
      var CartData    = {
        products: product.build_cart(product.get_products(), [])
      }

      product.set_percentage(
        CO2ok.getFootprint(merchant_id, CartData)
      );
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
    Co2ok_JS().getCookieValue('co2ok_ab_enabled') == 1 && !Co2ok_JS().getCookieValue('co2ok_ab_hide')
  ) {
    var future = new Date();
    future.setTime(future.getTime() + 30 * 24 * 3600 * 1000);

    var random_A_or_B = Math.round(Math.random());
    document.cookie = "co2ok_ab_hide=" + random_A_or_B + "; expires=" + future.toUTCString() + "; path=/";
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
