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

/**
 * Tras login, si hay productos en el carrito, redirigir a checkout de Woo (usuarios no administradores)
 */
add_filter( 'login_redirect', function( $redirect_to, $requested_redirect_to, $user ){
    if ( is_wp_error( $user ) ) { return $redirect_to; }
    if ( function_exists( 'WC' ) && WC()->cart ) {
        $has_items = WC()->cart->get_cart_contents_count() > 0;
        if ( $has_items && ! user_can( $user, 'administrator' ) ) {
            // Mantener si ya pidió ir a checkout
            if ( $requested_redirect_to && strpos( $requested_redirect_to, 'checkout' ) !== false ) {
                return $requested_redirect_to;
            }
            return wc_get_checkout_url();
        }
    }
    return $redirect_to;
}, 20, 3 );

/**
 * Fallback de precio para cursos de Tutor cuando el producto WC trae 0 o vacío
 *
 * - Si el precio de WooCommerce es 0/empty, obtenemos el precio del curso Tutor
 *   asociado (sale o regular) y lo usamos como precio del producto.
 * - Aplica a get_price, get_regular_price y get_sale_price.
 */
function wellnez_child_get_course_id_by_product( $product_id ) {
    global $wpdb;
    $product_id = absint( $product_id );
    if ( ! $product_id ) return 0;
    $post_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %d LIMIT 1",
        '_tutor_course_product_id',
        $product_id
    ) );
    return $post_id ? absint( $post_id ) : 0;
}

function wellnez_child_tutor_course_price_fallback( $price, $product ) {
    // Si ya hay precio válido (>0), respetar.
    $numeric = is_numeric( $price ) ? (float) $price : (float) 0;
    if ( $numeric > 0 ) {
        return $price;
    }

    if ( ! $product || ! method_exists( $product, 'get_id' ) ) {
        return $price;
    }

    $course_id = wellnez_child_get_course_id_by_product( $product->get_id() );
    if ( ! $course_id ) {
        return $price;
    }

    // Intentar obtener precio desde Tutor.
    if ( function_exists( 'tutor_utils' ) ) {
        $raw = tutor_utils()->get_raw_course_price( $course_id );
        if ( is_object( $raw ) ) {
            $fallback = 0;
            $sale     = isset( $raw->sale_price ) ? (float) $raw->sale_price : 0;
            $regular  = isset( $raw->regular_price ) ? (float) $raw->regular_price : 0;
            if ( $sale > 0 ) {
                $fallback = $sale;
            } elseif ( $regular > 0 ) {
                $fallback = $regular;
            }
            if ( $fallback > 0 ) {
                return (string) $fallback;
            }
        }
    }

    return $price;
}
add_filter( 'woocommerce_product_get_price', 'wellnez_child_tutor_course_price_fallback', 10, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'wellnez_child_tutor_course_price_fallback', 10, 2 );
add_filter( 'woocommerce_product_get_sale_price', 'wellnez_child_tutor_course_price_fallback', 10, 2 );

/**
 * Ajustar decimales a 2 cuando la divisa sea MXN (para evitar 0.000)
 */
add_filter( 'woocommerce_price_num_decimals', function( $decimals ) {
    if ( function_exists( 'get_woocommerce_currency' ) ) {
        $currency = get_woocommerce_currency();
        if ( 'MXN' === $currency ) {
            return 2;
        }
    }
    return $decimals;
}, 20 );

/**
 * Forzar monetización por WooCommerce para que el flujo use Woo (carrito + checkout)
 */
add_filter( 'monetize_by', function( $value ) { return 'wc'; }, 999 );

/**
 * Evitar que Tutor LMS reemplace la plantilla de la página del carrito de WooCommerce.
 *
 * - Tutor LMS usa el filtro 'template_include' para forzar su propia plantilla de carrito.
 * - Esta función detecta si estamos en la página del carrito de WooCommerce y, si es así,
 *   elimina el filtro de Tutor LMS para permitir que se cargue la plantilla correcta.
 */
add_filter( 'template_include', 'wellnez_child_prevent_tutor_cart_hijack', 20 );
function wellnez_child_prevent_tutor_cart_hijack( $template ) {
    // Solo actuar si WooCommerce está activo y estamos en la página del carrito.
    if ( function_exists( 'is_cart' ) && is_cart() && function_exists( 'tutor' ) ) {
        // Identificar y eliminar el filtro específico de Tutor LMS.
        $tutor_template_loader = array( tutor()->template, 'load_template_from_include' );
        if ( has_filter( 'template_include', $tutor_template_loader ) ) {
            remove_filter( 'template_include', $tutor_template_loader );
        }
    }
    return $template;
}


