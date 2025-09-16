<?php
/**
 * Plugin Name: Tutor LMS & WooCommerce Checkout Fix (Definitive)
 * Description: Soluciona los conflictos de redirección entre Tutor LMS y WooCommerce para asegurar un flujo de compra correcto.
 * Version: 2.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. Al añadir un producto, redirigir a la página del carrito de WooCommerce.
add_filter( 'woocommerce_add_to_cart_redirect', function() {
    return wc_get_cart_url();
}, 99 );

// 2. Solución definitiva para la redirección post-login.
add_action( 'wp_login', 'twcf_definitive_checkout_redirect', 10, 2 );
function twcf_definitive_checkout_redirect( $user_login, $user ) {
    // No afectar a administradores.
    if ( user_can( $user, 'manage_options' ) ) {
        return;
    }

    // Si el carrito de WooCommerce tiene productos, redirigir forzosamente al checkout.
    if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
        wp_redirect( wc_get_checkout_url() );
        exit(); // Detener la ejecución para anular cualquier otra redirección.
    }
}
