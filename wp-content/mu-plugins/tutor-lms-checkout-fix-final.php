<?php
/**
 * Plugin Name: Tutor LMS & WooCommerce Checkout Fix (AJAX Intercept)
 * Description: Solución definitiva que intercepta la respuesta AJAX de Tutor LMS para forzar la redirección al checkout.
 * Version: 5.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Al añadir un producto, redirigir al carrito de WooCommerce.
add_filter( 'woocommerce_add_to_cart_redirect', function() {
    return wc_get_cart_url();
}, 99 );

// 2. Interceptar la respuesta AJAX de login de Tutor LMS.
add_action( 'wp_ajax_nopriv_tutor_user_login', 'twcf_intercept_tutor_ajax_login', 0 );
add_action( 'wp_ajax_tutor_user_login', 'twcf_intercept_tutor_ajax_login', 0 );

function twcf_intercept_tutor_ajax_login() {
    // Verificar el nonce para seguridad.
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'tutor_login_nonce' ) ) {
        return;
    }

    // Si el carrito de WooCommerce tiene productos, forzar la redirección al checkout.
    if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
        // Iniciar sesión del usuario.
        $creds = array(
            'user_login'    => $_POST['log'],
            'user_password' => $_POST['pwd'],
            'remember'      => isset( $_POST['rememberme'] ),
        );
        $user = wp_signon( $creds, is_ssl() );

        // Si el login es exitoso, enviar una respuesta JSON con la URL del checkout y detener todo.
        if ( ! is_wp_error( $user ) ) {
            wp_send_json_success( array( 'redirect_to' => wc_get_checkout_url() ) );
            exit();
        }
    }
    // Si el carrito está vacío, no hacer nada y dejar que Tutor LMS continúe su proceso normal.
}
