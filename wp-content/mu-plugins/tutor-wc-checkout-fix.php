<?php
/**
 * Plugin Name: Tutor LMS & WooCommerce Checkout Fix
 * Description: Soluciona los conflictos de redirección entre Tutor LMS y WooCommerce para asegurar un flujo de compra correcto.
 * Version: 1.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. Al añadir un producto, redirigir a la página del carrito correcta.
add_filter( 'woocommerce_add_to_cart_redirect', 'twcf_redirect_to_cart_page', 99 );
function twcf_redirect_to_cart_page() {
    // Usamos wc_get_cart_url() para asegurar que siempre sea la página correcta de WooCommerce.
    return wc_get_cart_url();
}

// 2. Forzar la redirección al checkout después del login si el usuario estaba comprando.
add_filter( 'login_redirect', 'twcf_force_checkout_redirect_after_login', 10, 3 );
function twcf_force_checkout_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
    // No afectar a administradores ni a procesos con errores.
    if ( is_wp_error( $user ) || user_can( $user, 'manage_options' ) ) {
        return $redirect_to;
    }

    // Si WooCommerce está activo y el carrito no está vacío, forzar redirección al checkout.
    if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
        return wc_get_checkout_url();
    }

    // Para cualquier otro caso, devolver la URL de redirección por defecto (ej. el escritorio de Tutor).
    return $redirect_to;
}
