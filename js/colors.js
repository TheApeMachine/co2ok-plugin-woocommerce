export default class Colors {

  calculate_brightness(color) {
    console.log("bright", color);
    var rgb = color.substring(
      color.indexOf("(") + 1, color.lastIndexOf(")")
    ).split(/,\s*/); // Calculate the brightness of the element

    return Math.sqrt(
      (0.241 * (rgb[0] * rgb[0])) + (0.671 * (rgb[1] * rgb[1])) + (0.068 * (rgb[2] * rgb[2]))
    );
  }

  adaptiveStyleClass(slug, brightness) {
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

  get_background_color(el) {
    // Is current element's background color set?
    var color = el.css("background-color");

    if (color !== "rgba(0, 0, 0, 0)") {
      return color;
    }

    if (!el.is("body")) {
      // Traverse up the DOM chain to find the background.
      this.get_background_color(el.parent());
    }

    return false;
  }

}
