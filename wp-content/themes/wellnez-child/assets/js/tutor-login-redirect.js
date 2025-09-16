(function(){
  function getTutorCheckoutUrl(){
    try {
      if (window.tutor_checkout_url) return window.tutor_checkout_url;
      var el = document.querySelector('[data-tutor-checkout-url]');
      if (el) return el.getAttribute('data-tutor-checkout-url');
    } catch(e){}
    return null;
  }

  function setRedirectToCheckout(){
    var checkoutUrl = getTutorCheckoutUrl();
    if(!checkoutUrl && window.tutor){
      // Intento construir desde body data si existe
      if (window.tutor.checkout_url){ checkoutUrl = window.tutor.checkout_url; }
    }
    if(!checkoutUrl) return;
    var form = document.querySelector('#tutor-login-form');
    if(!form) return;
    var redirect = form.querySelector('input[name="redirect_to"]');
    if(!redirect){
      redirect = document.createElement('input');
      redirect.type = 'hidden';
      redirect.name = 'redirect_to';
      form.appendChild(redirect);
    }
    redirect.value = checkoutUrl;
  }

  function ensureCheckoutDataAttr(){
    var existing = document.querySelector('[data-tutor-checkout-url]');
    if(existing) return;
    // Busca un enlace al checkout de Tutor en la página y refléjalo como data-attr
    var guess = document.querySelector('a[href*="/checkout"], a[href*="?step=tutor-2fa"]');
    if(guess){
      var wrapper = document.createElement('div');
      wrapper.setAttribute('data-tutor-checkout-url', guess.getAttribute('href'));
      document.body.appendChild(wrapper);
    }
  }

  document.addEventListener('click', function(e){
    var target = e.target.closest('.tutor-open-login-modal, .tutor-native-add-to-cart');
    if(!target) return;
    // El usuario no está logueado -> se abrirá el modal de Tutor. Preparamos redirect_to al checkout.
    setTimeout(function(){
      ensureCheckoutDataAttr();
      setRedirectToCheckout();
    }, 50);
  }, true);
})();
