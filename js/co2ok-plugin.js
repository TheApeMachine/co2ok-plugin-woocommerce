var Co2ok_JS = {

    //////// GENERAL UTILITY ////////
  
    // Returns true if we are running on a mobile device.
    isMobile: function() {
      // Check the user agent. If one of the Mobile models, return true.
      // var IsMobile = false;
      // if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
      // || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
      // IsMobile = true;
  
      // return IsMobile;
      // TODO: in theorie zou dit voldoende moeten zijn, anders moet t toch met bovenstaande (of de package waar ie ook in zit).
      return !!navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i);
    },
    // Returns true if jQuery can find an element that matches the given selector string.
    isExistingjQueryElement: function(selector) {
      return !!jQuery(selector).length;
    },
  
    /** 
     * Performs a service call to determine the CO2 compensation percentage and applies it 
     * to each product in the cart.
     */
    getPercentageFromMiddleware: function() {
        var merchant_id = jQuery('.co2ok_container').attr('data-merchant-id');
        var products = JSON.parse(decodeURIComponent(jQuery('.co2ok_container').attr('data-cart')));
  
        var cartData = {
            products: []
        }
  
        jQuery(products).each(function(i) {
            var productData = {
                name: products[i].name,
                brand: products[i].brand,
                description: products[i].description,
                shortDescription: products[i].shortDescription,
                sku: products[i].sku,
                gtin: products[i].gtin,
                price: products[i].price,
                taxClass: products[i].taxClass,
                weight: products[i].weight,
                attributes: products[i].attributes,
                defaultAttributes: products[i].defaultAttributes,
                quantity: products[i].quantity,
            }
            cartData.products.push(productData);
        });
  
        var promise = CO2ok.getFootprint(merchant_id, cartData);
  
        promise.then(function(percentage) {
            var data = {
                'action': 'co2ok_ajax_set_percentage',
                'percentage': percentage
            };
            jQuery.post(ajax_object.ajax_url, data, function(response) {
                if (typeof response.compensation_amount != 'undefined') {
                    jQuery('[class*="compensation_amount"]').html('+' + response.compensation_amount);
                }
            });
        });
    },
  
    //////// STYLING ////////
  
    /**
     * Gets the value of the given element's background-color attribute. If this attribute 
     * was not set, the parent element(s) is/are checked recursively until an element with 
     * a background color is found. If no such element can be found, 'false' is returned.
     */
    getBackgroundColor: function(jqueryElement) {
      // Is the given element's background color set?
      var color = jqueryElement.css("background-color");
  
      if (color !== "rgba(0, 0, 0, 0)") {
          // If so, return that color.
          return color;
      }
  
      // if not: are you at the body element?
      if (jqueryElement.is("body")) {
          // Return known 'false' value.
          return false;
      } else {
          // Call getBackgroundColor with parent item.
          return getBackgroundColor(jqueryElement.parent());
      }
    },
  
    // Calculates the brightness of the background of the co2ok cart element.
    calculateBackgroundBrightness: function() {
      // Grab the background colour of the element.
      var bgColor = getBackgroundColor(jQuery("#co2ok_cart")); 
  
      if (bgColor) {
        // Get the three comma-separated numbers between the parentheses and split on the comma's.
        var rgb = bgColor.substring(bgColor.indexOf("(") + 1, bgColor.lastIndexOf(")")).split(/,\s*/);
        // Get the values for the three colors from the resulting array.
        var red = rgb[0],
          green = rgb[1],
          blue = rgb[2];
        // Calculate the brightness and return result.
        return Math.sqrt((0.241 * (red * red)) + (0.671 * (green * green)) + (0.068 * (blue * blue)));
      }
    },
  
    // Checks whether adaptive text color can be applied and if so, does.
    adaptiveTextColor: function() {
      // IE < 11 supports conditional compilation (turned on by cc_on). If we're dealing with one
      // of these versions of IE, the exclamation mark will be compiled and the expression will evaluate
      // to !false (so, true). Otherwise, check whether IE-only document.documentMode has a value.
      var isIE = /*@cc_on!@*/false || document.documentMode; // Internet Explorer 6-11
      var isEdge = !isIE && window.StyleMedia; // Edge 20+
  
      // Check if Internet Explorer 6-11 OR Edge 20+
      if (isIE || isEdge) {
        // We are dealing with a crappy browser. Remove adaptive color attribute. 
        jQuery(".co2ok_adaptive_color_default").removeClass("co2ok_adaptive_color");
      }
      // Browser is not terrible. Set the text color based on the brightness of the background.
      else if (calculateBackgroundBrightness() > 185) { 
        // Background is too bright. remove adaptive color.
        jQuery(".co2ok_adaptive_color_default").removeClass("co2ok_adaptive_color");
      } else {
        jQuery(".co2ok_adaptive_color_default").addClass("co2ok_adaptive_color");
      }
    },
  
    //////// REWARD VIDEO ////////
  
    placeVideoRewardBox: function() {
  
      var infoButton = jQuery(".co2ok_info");
      var videoRewardBox = jQuery(".co2ok_videoRewardBox_container");
      var offset = infoButton.offset();
  
      videoRewardBox.remove();
      jQuery("body").append(videoRewardBox);
  
      if (jQuery(window).width() < 480) {
        offset.top = offset.top + infoButton.height();
        videoRewardBox.css({
          top: offset.top,
          margin: "0 auto",
          left: "50%",
          transform: "translateX(-50%)"
        });
      } else {
        offset.left = offset.left - videoRewardBox.width() / 2;
        offset.top = offset.top + infoButton.height();
        videoRewardBox.css({
          top: offset.top,
          left: offset.left,
          margin: "0",
          transform: "none"
        });
      }
    },
  
    showVideoRewardBox: function() {
      jQuery(".co2ok_videoRewardBox_container").removeClass('VideoRewardBox-hidden')
      jQuery(".co2ok_videoRewardBox_container").addClass('ShowVideoRewardBox')
      jQuery(".co2ok_videoRewardBox_container").css({
          marginBottom: 200
      });
  
      jQuery('#co2ok_videoReward').get(0).play();
  
      if (this.isMobile()) {
        var elmnt = document.getElementById("videoRewardBox-view");
        elmnt.scrollIntoView(false); // false leads to bottom of the infobox
  
        jQuery("#co2ok_videoReward").css(
            "width", "266px",
            "padding-bottom", "0px"
        );
  
        jQuery(".co2ok_videoRewardBox_container").css(
            "height", "230px"
        );
      }
    },
  
    hideVideoRewardBox: function() {
      jQuery(".co2ok_videoRewardBox_container").removeClass('ShowVideoRewardBox')
      jQuery(".co2ok_videoRewardBox_container").addClass('VideoRewardBox-hidden')
      jQuery(".co2ok_videoRewardBox_container").css({
          marginBottom: 0
      });
    },
  
    //////// INFO BOX ////////
  
    placeInfoBox : function() {
      var infoButton = jQuery(".co2ok_info");
      var infoBox = jQuery(".co2ok_infobox_container");
      var offset = infoButton.offset();
  
      // TODO: wederom, is deze 'reset' ergens goed voor?
      infoBox.remove();
      jQuery("body").append(infoBox);
  
      if (jQuery(window).width() < 480) {
        offset.top = offset.top + infoButton.height();
        infoBox.css({
          top: offset.top,
          margin: "0 auto",
          left: "50%",
          transform: "translateX(-50%)"
        });
      } else {
        offset.left = offset.left - infoBox.width() / 2;
        offset.top = offset.top + infoButton.height();
        infoBox.css({
          top: offset.top,
          left: offset.left,
          margin: "0",
          transform: "none"
        });
      }
    },
    
    showInfoBox: function() {
        jQuery(".co2ok_infobox_container").removeClass('infobox-hidden')
        jQuery(".co2ok_infobox_container").addClass('ShowInfoBox')
        jQuery(".co2ok_container").css({
          marginBottom: 200
        });
        if (this.isMobile()) {
          var elmnt = document.getElementById("infobox-view");
          elmnt.scrollIntoView(false); // false leads to bottom of the infobox
        }
    },
  
    hideInfoBox: function() {
        jQuery(".co2ok_infobox_container").removeClass('ShowInfoBox')
        jQuery(".co2ok_infobox_container").addClass('infobox-hidden')
        jQuery(".co2ok_container").css({
          marginBottom: 0
        });
    },
  
    /**
     * Hides or shows the info box, based on whether the element that was targeted
     * by an event can trigger the info box.
     */
    hideOrShowInfoBox: function(event) {
      var element = event.target;
  
      if (jQuery(element).hasClass("svg-img") 
        || jQuery(element).hasClass("svg-img-large") 
        || jQuery(element).hasClass("text-block") 
        || jQuery(element).hasClass("inner-wrapper") 
        || jQuery(element).hasClass("co2ok_info") 
        || jQuery(element).hasClass("co2ok_info_hitarea") 
        || jQuery(element).hasClass("co2ok_infobox_container") 
        || jQuery(element).hasClass("hover-link")) {
        this.showInfoBox();
      } else {
        this.hideInfoBox();
      }
    },
  
    // TODO: deze functie hangt nauw samen met hideOrShowInfoBox (waar ik modalRegex() in heb verwerkt). 
      // Ik heb niet het idee dat dit nu optimaal werkt - maar misschien is dit gewoon de jQuery manier.
      // Er worden nu bindings gezet op de gehele body, waardoor vrij vaak events worden getriggerd. Na
      // de trigger wordt gecheckt of het om een infobox-showing-element gaat en wordt de box getoond 
      // of verborgen. Ik zie ook niet hoe de infobox kan worden geinitialiseerd op een mobiel apparaat.
      // (dwz placeInfoBox() wordt alleen aangeroepen als isMobile() false is).
    /**
     * Registers the bindings for several elements that can trigger showing or hiding
     * the info box.
     */
    registerInfoBox : function() {
      // Reference to mighty Co2ok_JS object, so its functions can be called in event bindings.
      var _this = this;
  
      jQuery(".co2ok_info_keyboardarea").focus(function() {
          _this.showInfoBox();
          jQuery(".first-text-to-select").focus();
      });
  
      jQuery('body').click(this.hideOrShowInfoBox(event));
  
      jQuery('body').on("touchstart", this.hideOrShowInfoBox(event));
  
      if (!this.isMobile()) {
        jQuery(".co2ok_info , .co2ok_info_hitarea").mouseenter(function() {
          _this.placeInfoBox();
        });
  
        jQuery(document).mouseover(this.hideOrShowInfoBox(event));
      }
    },
  
    //////// FUNKY SHIT (THIS CODE SMELLS) ////////
  
    /**
     * Appends the hidden checkout elements to the woocommerce form.
     * Boolean parameter indicates whether elements should be registered as checked.
     */
    appendHiddenCheckout: function(isChecked) {
      var checkboxValue = isChecked ? 1 : 0;
      var co2okCartHtml = '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_cart_hidden"'
        + ' checked value="' + checkboxValue + '" style="display:none">';
      var co2okCheckoutHtml = '<input type="checkbox" class="input-checkbox " name="co2ok_cart"'
        + ' id="co2ok_checkout_hidden" checked value="'+ checkboxValue + '" style="display:none">';
  
      jQuery('.woocommerce-cart-form').append(co2okCartHtml);
  
      if (!this.isExistingjQueryElement('#co2ok_checkout_hidden')) {
        // If the hidden checkout element is not present, add it to the woocommerce form.
        jQuery('form.woocommerce-checkout, .woocommerce form').append(co2okCheckoutHtml);
      } else {
        // TODO: is dit ergens goed voor? Ziet er niet echt logisch uit...
        // If the hidden checkout is present, remove it, then add it again.
        jQuery('#co2ok_checkout_hidden').remove();
        jQuery('form.woocommerce-checkout, .woocommerce form').append(co2okCheckoutHtml);
      }
    },
  
    // TODO: een aanzienlijk deel van deze functie is (voor mij) niet te volgen, bv. remove() direct gevolgd door append() van hetzelfde element ('refresh' dus).
    // Defines the functionality for the interactive Co2ok elements.
    registerBindings: function() {
      // Reference to mighty Co2ok_JS object, so its functions can be called in lambdas.
      var _this = this;
  
      // Define functionality for the cart.
      jQuery('#co2ok_cart').click(function(event) {
  
        if (jQuery(this).is(":checked")) {
  
          jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
  
          if (_this.isExistingjQueryElement(".co2ok_videoRewardBox_container")) {
  
            _this.placeVideoRewardBox();
            _this.showVideoRewardBox();
            // Make sure the reward video is hidden once it ends.
            jQuery('#co2ok_videoReward').on('ended', function() {
              _this.hideVideoRewardBox();
            });
          }
  
          jQuery('.co2ok_checkbox_container').addClass('selected');
          jQuery('.co2ok_checkbox_container').removeClass('unselected');
          // Append checked hidden cart and checkbox.
          _this.appendHiddenCheckout(true);
  
        } else {
          // The co2ok cart is not checked. Hide it.
          _this.hideVideoRewardBox();
          jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
          
          jQuery('.co2ok_checkbox_container').removeClass('selected');
          jQuery('.co2ok_checkbox_container').addClass('unselected');
          // Append unchecked hidden cart and checkbox.
          _this.appendHiddenCheckout(false);
        }
        
        // TODO: naar welke elementen wordt hier verwezen? Ik kan update_cart en update_checkout nergens vinden...
        // Remove, then add functionality for when the quantity of the first item in the
        // form is changed.
        jQuery('.woocommerce-cart-form, .woocommerce form').find('input.qty').first().unbind();
        jQuery('.woocommerce-cart-form, .woocommerce form').find('input.qty').first()
          .bind('change', function() {
            setTimeout(function() {
                jQuery("[name='update_cart']").trigger("click");
            }, 200);
        });
        // For some reason, trigger these two events after 0.2 seconds.
        setTimeout(function() {
            jQuery('body').trigger('update_checkout');
            jQuery("[name='update_cart']").trigger("click");
        }, 200);
        // Trigger this one right away.
        jQuery('.woocommerce-cart-form').find('input.qty').first().trigger("change");
      });
  
      // Define functionality for the checkbox and the cart.
      jQuery('#co2ok_cart, #checkbox_label, .co2ok_checkbox_container').click(function(event) {
          if (!jQuery(this).is("#co2ok_cart")) {
              jQuery("[id='co2ok_cart']").trigger("click");
          }
          event.stopPropagation();
      });
    },
  
    /** 
     * Sets some size attributes for some elements, based on some values of some 
     * attributes on some other elements. 
     */
    compensationAmountTextSize: function() {
  
      this.getPercentageFromMiddleware();
  
      var quantityElement = document.querySelector('.qty');
      var co2ok_temp_global = document.querySelector('.co2ok_global_temp');
  
      if (co2ok_temp_global.id == 'default_co2ok_temp') {
        // Default mode. Get default elements.
        var cad = document.querySelector('.compensation_amount_default');
        var make = document.querySelector('.make');
        var co2ok_logo = document.querySelector('.co2ok_logo_default');
  
        var modifier;
  
        if (!quantityElement) {
          // The first selector didn't yield any results. Try with another.
          var productQuantity = document.querySelector('.product-quantity');
          modifier = productQuantity.textContent.length - 2;  
        } else {
          modifier = quantityElement.value.length;
        }
  
        if (modifier > 1) {
          cad.style.fontSize = 16 - modifier + 'px';
          cad.style.marginTop = 10 + modifier + 'px';
          make.style.fontSize = 18 - modifier + 'px';
          co2ok_logo.style.width = 55 - modifier + 'px';
        } else {
          cad.style.fontSize = '16px';
          cad.style.marginTop = '10px';
          make.style.fontSize = '18px';
          co2ok_logo.style.width = '55px';
        }
  
      } else {
        // We're in minimal mode. Get the minimal elements.
        var cad_minimal = document.querySelector('.compensation_amount_minimal');
        var make_minimal = document.querySelector('.make_co2ok_minimal');
        var co2ok_logo_minimal = document.querySelector('.co2ok_logo_minimal');
        var comp_amount_label_minimal = document.querySelector('.comp_amount_label_minimal');
  
        var modifier;
        var modifierCompensationAmount;
  
        if (!quantityElement) {
          // The first selector didn't yield any results. Try with another.
          var compensation_amount_global = document.querySelector('.compensation_amount_global');
          modifierCompensationAmount = compensation_amount_global.textContent.length;
        } else {
          modifier = quantityElement.value.length;
        }
  
        if (modifier && modifier > 1) {
          cad_minimal.style.fontSize = 15 - modifier + 'px';
          make_minimal.style.fontSize = 18 - modifier + 'px';
          co2ok_logo_minimal.style.width = 52 - modifier + 'px';
          comp_amount_label_minimal.style.marginLeft = -(10 + cad_minimal.textContent.length) - qtyVal +'px';
  
        } else if (modifierCompensationAmount && modifierCompensationAmount > 8) {
          cad_minimal.style.fontSize = 18 - (compensationAmountLength - 3) + 'px';
          make_minimal.style.fontSize = 21 - (compensationAmountLength - 3) + 'px';
          co2ok_logo_minimal.style.width = 55 - (compensationAmountLength - 3) + 'px';
          comp_amount_label_minimal.style.marginLeft = -(10 + cad_minimal.textContent.length) - compensationAmountLength +'px';
  
        } else {
          // Default minimal size.
          cad_minimal.style.fontSize = '18px';
          make_minimal.style.fontSize = '21px';
          co2ok_logo_minimal.style.width = '55px';
          comp_amount_label_minimal.style.marginLeft = '-3px';
        }
      }
    },
  
    //////// INITIALIZATION ////////
  
    // Main/mighty initialization function.
    init: function() {
      var image_url = plugin.url;
  
      this.registerBindings();
      this.registerInfoBox();
  
      if (this.isExistingjQueryElement(".co2ok_container")) {
          compensationAmountTextSize();
      }
  
      jQuery(document.body).on('updated_cart_totals', function() {
          compensationAmountTextSize();
      });
  
      this.getPercentageFromMiddleware();
  
      if (jQuery('#co2ok_cart').is(":checked")) {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo_wit.svg');
      } else {
        jQuery("#co2ok_logo").attr("src", image_url + '/logo.svg');
      }
  
      // TODO: Hier stond een check omheen op de aanwezigheid van de #co2ok_cart, maar die is al uitgevoerd
        // voordat init() werd aangeroepen. Leek mij vrij zinloos.
  
        // adaptiveTextColor();
  
        // if(calculateBackgroundBrightness() > 185){ // picks logo based on background brightness for minimal button design
            jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo.svg');
        // }
        // else {
        //     jQuery("#co2ok_logo_minimal").attr("src", image_url + '/logo_licht.svg');
        // }
  
    }
  }
  
  jQuery(document).ready(function() {
      if (Co2ok_JS.isExistingjQueryElement("#co2ok_cart")) {
          Co2ok_JS.init();
      }
  })