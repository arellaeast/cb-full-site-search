(function () {
  // Client-side fallback: covers search forms rendered without the
  // get_product_search_form / get_search_form filters (e.g. hardcoded
  // theme header markup). Removes the products-only restriction so the
  // form submits a plain ?s= query, which WordPress searches site-wide.
  function fixSearchForms() {
    var forms = document.querySelectorAll('form.woocommerce-product-search, form[role="search"]');
    for (var i = 0; i < forms.length; i++) {
      var form = forms[i];
      var hidden = form.querySelectorAll('input[name="post_type"][value="product"]');
      for (var j = 0; j < hidden.length; j++) {
        hidden[j].remove();
      }
      var inputs = form.querySelectorAll('input[type="search"]');
      for (var k = 0; k < inputs.length; k++) {
        if (inputs[k].placeholder && /products/i.test(inputs[k].placeholder)) {
          inputs[k].placeholder = 'Search\u2026';
        }
      }
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fixSearchForms);
  } else {
    fixSearchForms();
  }
})();
