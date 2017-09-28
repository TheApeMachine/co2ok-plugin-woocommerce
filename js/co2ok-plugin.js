var Co2ok_JS = function ()
{
    var player;
    return {

        Init: function ()
        {
            this.RegisterCartBindings();
            this.RegisterCheckoutBinding();
            this.RegisterInfoHoverVideo();

            setInterval(function()
            {
                var html = jQuery('.fee').find('[data-title="CO2 compensation"]').find('.amount').html();
                jQuery('.compensation_amount').html(html);
            },1000);

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

            var promise = CO2ok.getFootprint("test",CartData);

            promise.then(function(percentage) {
                // do something with result
                console.log( percentage );
                var data = {
                    'action': 'my_ajax_action',
                    'percentage': percentage
                };
                jQuery.post(ajax_object.ajax_url, data, function(response) {
                  //  alert('Got this from the server: ' + response);
                });
            });

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
        },
        RegisterInfoHoverVideo : function()
        {
            jQuery('.co2-ok-info').hover(function()
            {
                jQuery(".youtubebox_container").css({ "display" : "block","width" : "400px","height" : "300px" , "opacity" : 1 });// style="width:1px;height:1px;overflow:hidden">
                //jQuery(".youtubebox").append('<iframe id="youtube_mov" width="400" height="300" src="https://www.youtube.com/embed/agwD0N1v46s?t=38s?enablejsapi=1&amp;loop=0" frameborder="0" allowfullscreen> </iframe>');
                var player;

                function onPlayerReady (event)
                {
                    player.mute();
                    player.seekTo(38, true);
                    player.playVideo();
                };

                player = new YT.Player('youtubebox', {
                    height: '300',
                    width: '400',
                    videoId: 'W82ZmuEJq3g',
                    events: {
                        'onReady': onPlayerReady
                    }
                });
            });

            jQuery('*').click(function()
            {
                jQuery(".youtubebox_container").css({ "opacity" : 0  });// style="width:1px;height:1px;overflow:hidden">
                jQuery(".youtubebox_container").find('#youtubebox').remove();
                jQuery(".youtubebox_container").append('<div id="youtubebox"></div>');
            });

            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }
    }
}().Init();
