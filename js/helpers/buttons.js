function minimumButton() {
  var cad_minimal = document.querySelector('.compensation_amount_minimal');
  var make_minimal = document.querySelector('.make_co2ok_global');
  var co2ok_logo_minimal = document.querySelector('.co2ok_logo_minimal');
  var comp_amount_label_minimal = document.querySelector('.comp_amount_label_minimal');
  var co2ok_info_hitare_minimal = document.querySelector('.co2ok_payoff_minimal');
  var inner_border_minimal = document.querySelector('.inner_comp_amount_label_minimal');

  //removes spaces in compensataion amount
  cad_minimal.innerText = cad_minimal.innerText.replace(/\\t|\\n|\\(?=")/g, '');
  var cad_length_minimal = cad_minimal.innerText.length;

  //changes style relative to length of compensation
  if (cad_length_minimal > 10) {

    var relative_font_size = Math.floor(14 - cad_length_minimal / 12);
    var relative_size_diff = 12 - relative_font_size;

  } else if (cad_length_minimal > 7) {

    var relative_font_size = Math.floor(14 - cad_length_minimal / 14);
    var relative_size_diff = 14 - relative_font_size;

  }

  if (cad_length_minimal > 7) {

    cad_minimal.style.fontSize = relative_font_size - relative_size_diff + 'px';
    make_minimal.style.fontSize = relative_font_size - relative_size_diff + 3 + 'px';
    co2ok_logo_minimal.style.width = 45 - relative_size_diff + 'px';
    comp_amount_label_minimal.style.left = '135px';
    comp_amount_label_minimal.style.width = 70 + cad_length_minimal + 'px';
    inner_border_minimal.style.width = 65 + cad_length_minimal + 'px';
    co2ok_info_hitare_minimal.style.paddingLeft = cad_length_minimal * 2 + 'px';
    co2ok_info_hitare_minimal.style.marginTop = '-3px';

  }
}

function defaultButton() {
  var make, cad, co2ok_logo = get_make_cad_logo()

  // Removes spaces from compensataion amount.
  cad.innerText = cad.innerText.replace(/\s+/g, '');

  // Changes style relative to length of compensation.
  relative_size_tuple = setMarginTop(cad);
  setLogoStyle(cad, make, co2ok_logo, relative_size_tuple)
}


