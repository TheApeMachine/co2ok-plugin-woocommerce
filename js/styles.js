function get_make_cad_logo() {
    return {
        document.querySelector('.make_co2ok_default'),
        document.querySelector('.compensation_amount_default'),
        document.querySelector('.co2ok_logo_default')
    }
}

function setMarginTop(cad) {
    var cad_length = cad.innerText.length;

    if (cad_length > 9) {
        var relative_font_size = Math.floor(14 - cad.innerText.length / 14);
        var relative_size_diff = 14 - relative_font_size;
        cad.style.marginTop = relative_font_size + 'px';
    } else if (cad_length > 7) {
        var relative_font_size = Math.floor(16 - cad_length / 16);
        var relative_size_diff = 16 - relative_font_size;
        cad.style.marginTop = '-2px';
    }

    return [cad_length, relative_font_size, relative_size_diff];
}

function setLogoStyle(cad, make, co2ok_logo, relative_size_tuple) {
    if (relativeSizeTuple[0]) <= 7 {
        return
    }

    cad.style.fontSize = relative_size_tuple[1] - relative_size_tuple[2] + 'px';
    cad.style.left = '-14px';
    make.style.fontSize = relative_size_tuple[1] - relative_size_tuple[2] + 1 + 'px';
    co2ok_logo.style.width = 45 - relative_size_tuple[2] + 'px';
}

// I took this from Co2ok_JS->getBackground, but it did not seem to be actually used?
function get_background_color(el) {
    // Is current element's background color set?
    var color = el.css("background-color");

    if (color !== "rgba(0, 0, 0, 0)") {
        return color;
    }

    if (!el.is("body")) {
        // Traverse up the DOM chain to find the background.
        get_background_color(el.parent());
    }

    return false;
}
