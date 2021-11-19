import Binding from './binding.js';

export default class Refresh {

  constructor() {
    this.binding = new Binding();
  }

  do() {
    // Some shops actually rerender elements such as our button upon cart update
    // this ofc breaks our bindings.
    jQuery(document.body).on('updated_cart_totals', function() {
      // detect if elements are bound:
      if (!jQuery._data(jQuery('.co2ok_checkbox_container').get(0), "events")) {
        console.log('Rebinding CO2ok')
        this.binding.register();
      }
    });
  }

}
