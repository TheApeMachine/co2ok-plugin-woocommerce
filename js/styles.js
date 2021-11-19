export default class Logo {

    constructor() {
        this.make       = document.querySelector('.make_co2ok_default'),
        this.cad        = document.querySelector('.compensation_amount_default'),
        this.co2ok_logo = document.querySelector('.co2ok_logo_default')
    }

    setMarginTop() {
        var cad_length = this.cad.innerText.length;

        if (cad_length > 9) {
            var relative_font_size = Math.floor(14 - cad_length / 14);
            var relative_size_diff = 14 - relative_font_size;
            this.cad.style.marginTop = relative_font_size + 'px';
        } else if (cad_length > 7) {
            var relative_font_size = Math.floor(16 - cad_length / 16);
            var relative_size_diff = 16 - relative_font_size;
            this.cad.style.marginTop = '-2px';
        }

        return [cad_length, relative_font_size, relative_size_diff];
    }

    setLogoStyle(relative_size_tuple) {
        if (relative_size_tuple[0] <= 7) {
            return
        }

        this.cad.style.fontSize = relative_size_tuple[1] - relative_size_tuple[2] + 'px';
        this.cad.style.left = '-14px';
        this.make.style.fontSize = relative_size_tuple[1] - relative_size_tuple[2] + 1 + 'px';
        this.co2ok_logo.style.width = 45 - relative_size_tuple[2] + 'px';
    }

}
