<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
(function() {
    // Define la URL de la página de pago y la del escritorio.
    const checkoutUrl = 'https://daria.mate4kids.com/checkout/';
    const dashboardUrl = 'https://daria.mate4kids.com/escritorio/';
    const currentUrl = window.location.href;

    // Lógica para la página de PAGO (Checkout)
    // Si estamos en la página de pago y el cuerpo de la página indica que el usuario NO ha iniciado sesión,
    // establecemos la marca en localStorage.
    if (currentUrl.startsWith(checkoutUrl)) {
        if (document.body.classList.contains('woocommerce-checkout') && !document.body.classList.contains('logged-in')) {
            localStorage.setItem('tutor_checkout_redirect_in_progress', 'true');
        }
    }

    // Lógica para la página del ESCRITORIO de Tutor
    // Si estamos en la página del escritorio Y la marca existe, forzamos la redirección.
    if (currentUrl.startsWith(dashboardUrl)) {
        if (localStorage.getItem('tutor_checkout_redirect_in_progress') === 'true') {
            // Eliminamos la marca para que no afecte a futuras visitas.
            localStorage.removeItem('tutor_checkout_redirect_in_progress');
            // Redirigimos al usuario a la página de pago.
            window.location.href = checkoutUrl;
        }
    }
    
    // Limpieza final: Si el usuario llega a la página de pago y SÍ ha iniciado sesión,
    // la marca ya no es necesaria y se elimina.
    if (currentUrl.startsWith(checkoutUrl)) {
        if (document.body.classList.contains('logged-in')) {
            localStorage.removeItem('tutor_checkout_redirect_in_progress');
        }
    }

})();

</script>
<!-- end Simple Custom CSS and JS -->
