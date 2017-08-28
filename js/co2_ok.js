var Co2ok_JS = function ()
{
    return {

        Init: function ()
        {
            this.RegisterCartBindings();
            this.RegisterCheckoutBinding();
        },
        RegisterCartBindings: function()
        {
            jQuery('#co2-ok-cart').click(function ()
            {
                if(jQuery(this).is(":checked"))
                    jQuery('.woocommerce-cart-form').append('<input type="checkbox" class="input-checkbox " name="co2-ok" id="co2-ok" checked value="1" style="display:none">');
                jQuery('.woocommerce-cart-form').find('input').trigger("change");
                jQuery("[name='update_cart']").trigger("click");
            });
        },
        RegisterCheckoutBinding : function()
        {
            jQuery('#co2-ok-checkout').click(function ()
            {
                jQuery('body').trigger('update_checkout');
            });
        }
    }

}().Init();
