var Co2ok_JS = function ()
{
    var player;
    return {

        Init: function ()
        {
            this.RegisterCartBindings();
            this.RegisterCheckoutBinding();
            this.RegisterInfoHoverVideo();
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
                    videoId: 'agwD0N1v46s',
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



            function onYouTubeIframeAPIReady() {

            }
        }

    }

}().Init();
