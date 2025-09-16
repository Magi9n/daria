<?php
/**
 * Plugin Name: Tutor LMS KILLER Fix (IRROMPIBLE)
 * Description: Solución definitiva que ANULA completamente la redirección de Tutor LMS
 * Version: 2.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// INTERCEPTAR EL LOGIN CON MÁXIMA PRIORIDAD
add_action( 'wp_login', 'tcf_nuclear_redirect_fix', 1, 2 );
function tcf_nuclear_redirect_fix( $user_login, $user ) {
    if ( !user_can( $user, 'manage_options' ) ) {
        // Si hay productos en el carrito, FORZAR redirección al checkout
        if ( function_exists( 'WC' ) && WC()->cart && !WC()->cart->is_empty() ) {
            // REDIRECCIÓN NUCLEAR - No se puede anular
            wp_redirect( wc_get_checkout_url() );
            exit();
        }
    }
}

// SCRIPT VIGILANTE ULTRA-AGRESIVO
add_action( 'wp_head', 'tcf_nuclear_javascript', 1 );
function tcf_nuclear_javascript() {
    ?>
    <script type="text/javascript">
    // EJECUTAR INMEDIATAMENTE - NO ESPERAR A DOM
    (function() {
        // VIGILANTE PERMANENTE
        setInterval(function() {
            if (window.location.href.indexOf('/escritorio/') !== -1) {
                var intent = localStorage.getItem('tcf_buy_intent');
                if (intent) {
                    localStorage.removeItem('tcf_buy_intent');
                    window.location.replace(intent);
                }
            }
        }, 100); // Cada 100ms
        
        // CAPTURAR INTENCIÓN DE COMPRA
        document.addEventListener('click', function(e) {
            var target = e.target;
            if (target.classList.contains('tutor-btn-enroll') || 
                target.classList.contains('single_add_to_cart_button') ||
                target.href && target.href.indexOf('add-to-cart') !== -1) {
                localStorage.setItem('tcf_buy_intent', window.location.href);
            }
        }, true);
        
        // OVERRIDE BRUTAL DE CUALQUIER REDIRECCIÓN
        var originalReplace = window.location.replace;
        var originalAssign = window.location.assign;
        
        window.location.replace = function(url) {
            if (url.indexOf('/escritorio/') !== -1 && localStorage.getItem('tcf_buy_intent')) {
                url = localStorage.getItem('tcf_buy_intent');
                localStorage.removeItem('tcf_buy_intent');
            }
            originalReplace.call(window.location, url);
        };
        
        window.location.assign = function(url) {
            if (url.indexOf('/escritorio/') !== -1 && localStorage.getItem('tcf_buy_intent')) {
                url = localStorage.getItem('tcf_buy_intent');
                localStorage.removeItem('tcf_buy_intent');
            }
            originalAssign.call(window.location, url);
        };
    })();
    </script>
    <?php
}
