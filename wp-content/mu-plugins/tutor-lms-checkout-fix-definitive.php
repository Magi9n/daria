<?php
/**
 * Plugin Name: Tutor LMS Pro & WooCommerce Checkout Fix (Definitive)
 * Description: Solución definitiva que restaura el formulario de login de WooCommerce en la página de pago, anulando la modificación de Tutor LMS Pro.
 * Version: 1.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'wp_loaded', 'twcf_restore_woocommerce_login_form', 20 );

function twcf_restore_woocommerce_login_form() {
    // Asegurarse de que las clases de Tutor Pro y WooCommerce existan.
    if ( class_exists( '\TutorPro\WooCommerce\Monetization' ) && class_exists( 'WooCommerce' ) ) {
        // Obtener la instancia de la clase de Tutor Pro que causa el problema.
        $tutor_monetization = tutor_pro_container()->get(\TutorPro\WooCommerce\Monetization::class);

        // Eliminar la acción de Tutor Pro que reemplaza el formulario de login.
        remove_action( 'woocommerce_before_checkout_form', array( $tutor_monetization, 'maybe_show_login_form' ), 10 );

        // Restaurar la acción original de WooCommerce para mostrar su formulario de login.
        add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
    }\n}
