export default class Product {

  build_cart(products, collector) {
    jQuery(products).each(function(i) {
        collector.push({
          name: products[i].name,
          brand: products[i].brand,
          description: products[i].description,
          shortDescription: products[i].shortDescription,
          sku: products[i].sku,
          gtin: products[i].gtin,
          price: products[i].price,
          taxClass: products[i].taxClass,
          weight: products[i].weight,
          attributes: products[i].attributes,
          defaultAttributes: products[i].defaultAttributes,
          quantity: products[i].quantity,
        });
    });

    return collector;
  }

  get_products() {
    return JSON.parse(
      decodeURIComponent(
        jQuery('.co2ok_container').attr('data-cart')
      )
    );
  }

  set_percentage(promise) {
    promise.then(function(percentage) {
      var data = {
        'action': 'co2ok_ajax_set_percentage',
        'percentage': percentage
      };

      jQuery.post(ajax_object.ajax_url, data, function(response) {
        if (typeof response.compensation_amount != 'undefined') {
          jQuery('[class*="compensation_amount"]').html('+'+response.compensation_amount);
        }
      });
    });
  }

}
