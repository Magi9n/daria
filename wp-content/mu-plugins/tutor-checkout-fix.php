<?php
/**
 * Plugin Name: Tutor LMS Checkout Fix
 * Description: Captura la intención de compra y redirige correctamente después del login
 * Version: 1.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Inyectar JavaScript en todas las páginas
add_action( 'wp_footer', 'tcf_inject_purchase_intent_script' );
function tcf_inject_purchase_intent_script() {
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Capturar clics en botones de "añadir al carrito"
        var addToCartButtons = document.querySelectorAll('.tutor-btn-enroll, .single_add_to_cart_button, [href*="add-to-cart"]');
        
        addToCartButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                // Guardar la URL actual como intención de compra
                localStorage.setItem('tutor_purchase_intent', window.location.href);
                localStorage.setItem('tutor_purchase_timestamp', Date.now());
            });
        });
        
        // Si estamos en la página del escritorio y hay intención de compra reciente
        if (window.location.href.indexOf('/escritorio/') !== -1) {
            var purchaseIntent = localStorage.getItem('tutor_purchase_intent');
            var timestamp = localStorage.getItem('tutor_purchase_timestamp');
            
            // Verificar que la intención sea reciente (menos de 5 minutos)
            if (purchaseIntent && timestamp) {
                var timeDiff = Date.now() - parseInt(timestamp);
                if (timeDiff < 300000) { // 5 minutos en milisegundos
                    // Limpiar el localStorage
                    localStorage.removeItem('tutor_purchase_intent');
                    localStorage.removeItem('tutor_purchase_timestamp');
                    
                    // Redirigir de vuelta al producto
                    window.location.href = purchaseIntent;
                }
            }
        }
    });
    </script>
    <?php
}
