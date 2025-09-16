<?php
/**
 *
 * @Packge      Wellnez
 * @Author      Vecuro
 * @version     1.0
 *
 */

/**
 * Enqueue style of child theme
 */
function wellnez_child_enqueue_styles() {

    wp_enqueue_style( 'wellnez-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'wellnez-child-style', get_stylesheet_directory_uri() . '/style.css',array( 'wellnez-style' ),wp_get_theme()->get('Version'));

    wp_enqueue_script( 'custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'wellnez_child_enqueue_styles', 100000 );

/**
 * Corrección del bucle de login en checkout (WooCommerce + Tutor Pro 2FA)
 *
 * - Si el login proviene del checkout o de un formulario de WooCommerce con
 *   redirección al checkout, eliminamos el filtro de 2FA de Tutor Pro solo
 *   para esa autenticación, evitando el paso OTP que rompe el flujo.
 * - Además, deshabilitamos el caché en páginas sensibles (checkout, carrito,
 *   mi cuenta) para evitar estados desactualizados que refuercen el bucle.
 */
add_filter( 'wp_authenticate_user', 'wellnez_child_bypass_tutor_2fa_on_wc_checkout', 1, 2 );
function wellnez_child_bypass_tutor_2fa_on_wc_checkout( $user, $password ) {
    if ( is_wp_error( $user ) ) {
        return $user;
    }

    // Detectar si el login proviene del checkout de WooCommerce.
    $redirect_to = '';
    if ( isset( $_REQUEST['redirect_to'] ) ) {
        $redirect_to = (string) $_REQUEST['redirect_to'];
    }
    if ( ! $redirect_to && isset( $_REQUEST['redirect'] ) ) {
        $redirect_to = (string) $_REQUEST['redirect'];
    }

    $is_checkout_redirect = (bool) ( $redirect_to && ( strpos( $redirect_to, 'checkout' ) !== false ) );
    $is_wc_login_form     = ( ! empty( $_POST['login'] ) && ( isset( $_POST['woocommerce-login-nonce'] ) || isset( $_POST['woocommerce-login-nonce'] ) ) );

    if ( $is_checkout_redirect || $is_wc_login_form ) {
        wellnez_child_remove_tutor_pro_2fa_filter();
    }

    return $user;
}

function wellnez_child_remove_tutor_pro_2fa_filter() {
    global $wp_filter;
    $hook = 'wp_authenticate_user';

    if ( ! isset( $wp_filter[ $hook ] ) ) {
        return;
    }

    $filters = $wp_filter[ $hook ];

    // WordPress >= 4.7 usa la clase WP_Hook con ->callbacks
    if ( is_object( $filters ) && isset( $filters->callbacks ) && is_array( $filters->callbacks ) ) {
        foreach ( $filters->callbacks as $priority => $callbacks ) {
            foreach ( $callbacks as $id => $callback ) {
                if ( isset( $callback['function'] ) ) {
                    $func = $callback['function'];
                    if ( is_array( $func ) && is_object( $func[0] ) ) {
                        $obj = $func[0];
                        // Coincidir con la clase de 2FA de Tutor Pro
                        if ( is_a( $obj, '\\TutorPro\\Auth\\_2FA' ) && $func[1] === 'check_login' ) {
                            remove_filter( $hook, $func, $priority );
                        }
                    }
                }
            }
        }
    }
}

// Evitar caché en páginas críticas de WooCommerce para impedir estados obsoletos
add_action( 'template_redirect', function () {
    if ( function_exists( 'is_checkout' ) && ( is_checkout() || is_cart() || is_account_page() ) ) {
        if ( ! defined( 'DONOTCACHEPAGE' ) ) {
            define( 'DONOTCACHEPAGE', true );
        }
        if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
            define( 'DONOTCACHEOBJECT', true );
        }
        if ( ! defined( 'DONOTCACHEDB' ) ) {
            define( 'DONOTCACHEDB', true );
        }
    }
}, 0 );
