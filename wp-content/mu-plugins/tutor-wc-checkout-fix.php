<?php
/**
 * Plugin Name: Tutor LMS & WooCommerce Checkout Fix (Final)
 * Description: Solución definitiva con JavaScript y LocalStorage para forzar la redirección al checkout.
 * Version: 4.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Al añadir un producto, redirigir al carrito de WooCommerce.
add_filter( 'woocommerce_add_to_cart_redirect', function() {
    return wc_get_cart_url();
}, 99 );

// 2. Inyectar los scripts de JavaScript en el pie de página.
add_action( 'wp_footer', 'twcf_final_redirect_scripts' );
function twcf_final_redirect_scripts() {
    $checkout_url = wc_get_checkout_url();

    // Script 1: En la página de checkout, si el usuario no está logueado, establece la marca.
    if ( is_checkout() && ! is_user_logged_in() ) {
        ?>
        <script type="text/javascript">
            localStorage.setItem('twcf_redirect_flag', 'true');
        </script>
        <?php
    }

    // Script 2: En la página de checkout, si el usuario SÍ está logueado, elimina la marca.
    if ( is_checkout() && is_user_logged_in() ) {
        ?>
        <script type="text/javascript">
            localStorage.removeItem('twcf_redirect_flag');
        </script>
        <?php
    }

    // Script 3: Script VIGILANTE GLOBAL - se ejecuta en TODAS las páginas.
    ?>
    <script type="text/javascript">
        (function() {
            // Si la marca existe y NO estamos en la página de checkout, forzar redirección.
            if ( localStorage.getItem('twcf_redirect_flag') === 'true' && window.location.href.indexOf('<?php echo $checkout_url; ?>') === -1 ) {
                window.location.href = '<?php echo esc_js( $checkout_url ); ?>';
            }
        })();
    </script>
    <?php
}
