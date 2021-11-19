export default class InfoBox {

  constructor(co2ok_global) {
    this.co2ok_global = co2ok_global;
  }

  place() {
    var infoButton = jQuery(".co2ok_info");
    var infoBox = jQuery(".co2ok_infobox_container");
    var offset = infoButton.offset();

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
  }

  show() {
    this.place()

    if (!jQuery(".co2ok_infobox_container").hasClass('ShowInfoBox')){
      this.setStyles('infobox-hidden', 'ShowInfoBox', 200);

      if (this.co2ok_global.is_mobile() == true ) {
        var elmnt = document.getElementById("infobox-view");
        elmnt.scrollIntoView(false); // false leads to bottom of the infobox
      }
    }
  }

  hide() {
    this.setStyles('ShowInfoBox', 'infobox-hidden', 0);
  }

  register(modalRegex) {
    var _this = this;

    jQuery(".co2ok_info_keyboardarea").focus(function() {
      _this.show();
      jQuery(".step-one").focus();
    });

    jQuery('body').click(function(e) {
      this.clickOrTouch(_this);
    });

    jQuery('body').on("touchstart",function(e) {
      this.clickOrTouch(_this);
    });

    if(!this.co2ok_global.is_mobile()) {
      jQuery(".co2ok_info , .co2ok_info_hitarea").mouseenter(function() {
        _this.place();
      });

      jQuery(document).mouseover(function(e) {
        if(!modalRegex(e)) {
          _this.hide(); return;
        }
        
        _this.show();
      });
    }
  }

  setStyles(showclass, hideclass, margin) {
    jQuery(".co2ok_infobox_container").removeClass(showclass);
    jQuery(".co2ok_infobox_container").addClass(hideclass);
    jQuery(".co2ok_container").css({
      marginBottom: margin
    });
  }
  
  clickOrTouch(_this) {
    if((!modalRegex(e)) || (jQuery(e.target).hasClass("exit-area"))) {
      _this.hide(); return;
    }

    _this.show();
  }

}
