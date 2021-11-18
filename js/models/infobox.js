function placeInfoBox() {
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

function showInfoBox() {
  this.placeInfoBox()
 
  if (!jQuery(".co2ok_infobox_container").hasClass('ShowInfoBox')){
    jQuery(".co2ok_infobox_container").removeClass('infobox-hidden')
    jQuery(".co2ok_infobox_container").addClass('ShowInfoBox')
    jQuery(".co2ok_container").css({
      marginBottom: 200
    });
    if (co2ok_global.IsMobile() == true ) {
      var elmnt = document.getElementById("infobox-view");
      elmnt.scrollIntoView(false); // false leads to bottom of the infobox
    }
  }
}

function hideInfoBox() {
  jQuery(".co2ok_infobox_container").removeClass('ShowInfoBox')
  jQuery(".co2ok_infobox_container").addClass('infobox-hidden')
  jQuery(".co2ok_container").css({
    marginBottom: 0
  });
}

function registerInfoBox() {
  var _this = this;

  jQuery(".co2ok_info_keyboardarea").focus(function() {
    _this.ShowInfoBox();
    jQuery(".step-one").focus();
  });

  jQuery('body').click(function(e) {
    if((!_this.modalRegex(e)) || (jQuery(e.target).hasClass("exit-area"))) {
      _this.hideInfoBox();
    }
    else {
      _this.ShowInfoBox();
    }
  });

  jQuery('body').on("touchstart",function(e) {
    if((!_this.modalRegex(e)) || (jQuery(e.target).hasClass("exit-area"))) {
      _this.hideInfoBox();
    }
    else {
      _this.ShowInfoBox();
    }
  });

  if(!co2ok_global.IsMobile()) {
    jQuery(".co2ok_info , .co2ok_info_hitarea").mouseenter(function() {
      _this.placeInfoBox();
    });

    jQuery(document).mouseover(function(e) {
      if(!_this.modalRegex(e)) {
        _this.hideInfoBox();
      }
      else {
        _this.ShowInfoBox();
      }
    });
  }
}

