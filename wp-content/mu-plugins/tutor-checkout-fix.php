<?php
/**
 * Plugin Name: Tutor Checkout Fix Final
 * Description: Solución definitiva para el problema de redirección
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Añadir script en TODAS las páginas con máxima prioridad
add_action( 'wp_head', 'tcf_add_debug_script', 1 );
function tcf_add_debug_script() {
    ?>
    <script>
    console.log('TCF Plugin cargado correctamente');
    
    // Función que se ejecuta inmediatamente
    (function() {
        console.log('Script TCF iniciado');
        
        // Capturar clicks en botones de compra
        document.addEventListener('click', function(e) {
            var target = e.target;
            console.log('Click detectado en:', target);
            
            // Buscar diferentes tipos de botones de compra
            if (target.textContent && (
                target.textContent.toLowerCase().includes('inscribirse') ||
                target.textContent.toLowerCase().includes('comprar') ||
                target.textContent.toLowerCase().includes('añadir') ||
                target.classList.contains('tutor-btn-enroll') ||
                target.classList.contains('single_add_to_cart_button')
            )) {
                console.log('Botón de compra detectado, guardando URL:', window.location.href);
                localStorage.setItem('tcf_purchase_url', window.location.href);
                localStorage.setItem('tcf_purchase_time', Date.now());
            }
        }, true);
        
        // Vigilante que se ejecuta constantemente
        var checkRedirect = function() {
            var currentUrl = window.location.href;
            
            if (currentUrl.indexOf('/escritorio/') !== -1) {
                console.log('Estamos en escritorio, verificando intención de compra...');
                
                var purchaseUrl = localStorage.getItem('tcf_purchase_url');
                var purchaseTime = localStorage.getItem('tcf_purchase_time');
                
                if (purchaseUrl && purchaseTime) {
                    var timeDiff = Date.now() - parseInt(purchaseTime);
                    console.log('Intención encontrada, tiempo transcurrido:', timeDiff);
                    
                    // Si la intención es reciente (menos de 2 minutos)
                    if (timeDiff < 120000) {
                        console.log('Redirigiendo a:', purchaseUrl);
                        localStorage.removeItem('tcf_purchase_url');
                        localStorage.removeItem('tcf_purchase_time');
                        window.location.href = purchaseUrl;
                    }
                }
            }
        };
        
        // Ejecutar verificación cada 500ms
        setInterval(checkRedirect, 500);
        
        // También ejecutar inmediatamente
        setTimeout(checkRedirect, 100);
        
    })();
    </script>
    <?php
}

// Interceptar login con máxima prioridad
add_action( 'wp_login', 'tcf_login_redirect', 1, 2 );
function tcf_login_redirect( $user_login, $user ) {
    error_log( 'TCF: Login detectado para usuario: ' . $user_login );
    
    if ( !user_can( $user, 'manage_options' ) ) {
        if ( function_exists( 'WC' ) && WC()->cart && !WC()->cart->is_empty() ) {
            error_log( 'TCF: Carrito no vacío, redirigiendo al checkout' );
            wp_redirect( wc_get_checkout_url() );
            exit();
        }
    }
}
