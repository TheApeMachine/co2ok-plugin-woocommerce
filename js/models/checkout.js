function handle_checkout_styles() {
  jQuery('.co2ok_checkbox_container').addClass('selected');
  jQuery('.co2ok_checkbox_container').removeClass('unselected');
  jQuery('.woocommerce-cart-form').append(
    '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_cart_hidden" checked value="1" style="display:none">'
  );

  if (jQuery('#co2ok_checkout_hidden').length === 0) {
    jQuery('form.woocommerce-checkout, .woocommerce form').append(
      '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="1" style="display:none">'
    );
  } else {
    jQuery('#co2ok_checkout_hidden').remove();
    jQuery('form.woocommerce-checkout, .woocommerce form').append(
      '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="1" style="display:none">'
    );
  }

} else {
  jQuery('.co2ok_checkbox_container').removeClass('selected');
  jQuery('.co2ok_checkbox_container').addClass('unselected');
  jQuery('.woocommerce-cart-form').append(
    '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_cart_hidden"  checked value="0" style="display:none">'
  );

  if (jQuery('#co2ok_checkout_hidden').length === 0) {
    jQuery('form.woocommerce-checkout, .woocommerce form').append(
      '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="0" style="display:none">'
    );
  } else {
    jQuery('#co2ok_checkout_hidden').remove();
    jQuery('form.woocommerce-checkout, .woocommerce form').append(
      '<input type="checkbox" class="input-checkbox " name="co2ok_cart" id="co2ok_checkout_hidden" checked value="0" style="display:none">'
    );
  }
}
