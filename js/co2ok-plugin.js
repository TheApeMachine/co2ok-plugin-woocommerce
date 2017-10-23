var Co2ok_JS = function ()
{
    var player;
    return {

        Init: function ()
        {
            this.RegisterBindings();
            this.RegisterInfoBox();

            var _this = this;


            jQuery( document.body ).on( 'updated_cart_totals', function(){
                _this.GetPercentageFromMiddleware();
            });

            this.GetPercentageFromMiddleware();
        },
        GetPercentageFromMiddleware: function()
        {
            var merchant_id = jQuery('.co2ok_container').attr('data-merchant-id');
            var products = JSON.parse(decodeURIComponent(jQuery('.co2ok_container').attr('data-cart')));

            var CartData = {
                products: []
            }

            jQuery(products).each(function(i)
            {
                var ProductData ={
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
                CartData.products.push(ProductData);
            });

            var promise = CO2ok.getFootprint(merchant_id,CartData);

            promise.then(function(percentage)
            {
                var data = {
                    'action': 'co2ok_ajax_set_percentage',
                    'percentage': percentage
                };
                jQuery.post(ajax_object.ajax_url, data, function(response)
                {
                    jQuery('.compensation_amount').html(response.compensation_amount);
                });
            });
        },
        RegisterBindings: function()
        {
            jQuery('#co2-ok-cart').click(function (event)
            {
                if(jQuery(this).is(":checked"))
                    jQuery('.woocommerce-cart-form').append('<input type="checkbox" class="input-checkbox " name="co2-ok" id="co2-ok" checked value="1" style="display:none">');

                jQuery('.woocommerce-cart-form').find('input.qty').first().unbind();
                jQuery('.woocommerce-cart-form').find('input.qty').first().bind('change', function()
                {
                    setTimeout(function()
                    {
                        jQuery(".woocommerce-cart-form input[name=update_cart]").click();
                    },200);
                });

                jQuery('body').trigger('update_checkout');

                jQuery('.woocommerce-cart-form').find('input.qty').first().trigger("change");
            });

            jQuery('#checkbox_label').click(function(event)
            {
                jQuery("[id='co2-ok-cart']").trigger("click");
            });
        },
        ShowInfoBox  : function()
        {
            jQuery(".co2ok_infobox_container").css({ "display" : "block","width" : "348px","height" : "260px" , "opacity" : 1 });
        },
        RegisterInfoBox : function()
        {
            var _this = this;

            jQuery('body').click(function()
            {
                jQuery(".co2ok_infobox_container").css({ "opacity" : 0  });
                jQuery(".co2ok_infobox_container").find('#co2ok_infobox').remove();
            });

            jQuery('.co2-ok-info').click(function(e)
            {
                _this.ShowInfoBox();
                e.stopPropagation();
            });
        }
    }
}().Init();
