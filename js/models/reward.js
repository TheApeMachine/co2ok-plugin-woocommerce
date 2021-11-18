function placeVideoRewardBox() {
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
}

function showVideoRewardBox() {
  jQuery(".co2ok_videoRewardBox_container").removeClass('VideoRewardBox-hidden');
  jQuery(".co2ok_videoRewardBox_container").addClass('ShowVideoRewardBox');
  jQuery(".co2ok_videoRewardBox_container").css({marginBottom: 200});
  jQuery('#co2ok_videoReward').get(0).play();

  if (co2ok_global.IsMobile() == true ) {
    var elmnt = document.getElementById("videoRewardBox-view");
    elmnt.scrollIntoView(false); // false leads to bottom of the infobox

    jQuery("#co2ok_videoReward").css(
      "width", "266px",
      "padding-bottom", "0px"
    );

    jQuery(".co2ok_videoRewardBox_container").css("height", "230px");
  }
}

function hideVideoRewardBox() {
  jQuery(".co2ok_videoRewardBox_container").removeClass('ShowVideoRewardBox');
  jQuery(".co2ok_videoRewardBox_container").addClass('VideoRewardBox-hidden');
  jQuery(".co2ok_videoRewardBox_container").css({marginBottom: 0});
}
