export function calculate_brightness(color) {
  var rgb = color.substring(
    color.indexOf("(") + 1, color.lastIndexOf(")")
  ).split(/,\s*/), // Calculate the brightness of the element
    red = rgb[0],
    green = rgb[1],
    blue = rgb[2],

    return Math.sqrt(
      (0.241 * (red * red)) + (0.671 * (green * green)) + (0.068 * (blue * blue))
    );
}

export function adaptiveStyleClass(slug, brightness) {
  var isIE = /*@cc_on!@*/false || !!document.documentMode, // Internet Explorer 6-11
  isEdge = !isIE && !!window.StyleMedia; // Edge 20+

  // Check if Internet Explorer 6-11 OR Edge 20+
  if(isIE || isEdge) {
      jQuery("."+slug+"_default").removeClass(slug);
  }
  else if (brightness() > 185) { //Set the text color based on the brightness
      jQuery("."+slug+"_default").removeClass(slug);
  } else {
      jQuery("."+slug+"_default").addClass(slug);
  }
}
