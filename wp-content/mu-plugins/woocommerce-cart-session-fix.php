<?php
/**
 * Plugin Name: WooCommerce Cart Session Fix
 * Description: Soluciona el problema de pérdida de datos del carrito entre cart y checkout
 * Version: 1.0
 * Author: Cascade AI
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Forzar inicio de sesión de WooCommerce en todas las páginas
add_action( 'init', 'wcsf_force_wc_session_start', 1 );
function wcsf_force_wc_session_start() {
    if ( ! session_id() ) {
        session_start();
    }
    
    if ( function_exists( 'WC' ) && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }
}

// Interceptar y preservar datos del carrito antes de ir al checkout
add_action( 'wp_loaded', 'wcsf_preserve_cart_data' );
function wcsf_preserve_cart_data() {
    if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
        $cart_data = array();
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $cart_data[] = array(
                'product_id' => $cart_item['product_id'],
                'quantity' => $cart_item['quantity'],
                'variation_id' => isset( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : 0,
                'variation' => isset( $cart_item['variation'] ) ? $cart_item['variation'] : array()
            );
        }
        
        // Guardar en múltiples lugares para asegurar persistencia
        set_transient( 'wcsf_cart_backup_' . WC()->session->get_customer_id(), $cart_data, 3600 );
        WC()->session->set( 'wcsf_cart_backup', $cart_data );
        
        if ( ! empty( $_COOKIE ) ) {
            setcookie( 'wcsf_cart_backup', json_encode( $cart_data ), time() + 3600, '/' );
        }
    }
}

// Restaurar carrito en checkout si está vacío
add_action( 'template_redirect', 'wcsf_restore_cart_on_checkout' );
function wcsf_restore_cart_on_checkout() {
    if ( ! is_checkout() || ! function_exists( 'WC' ) ) {
        return;
    }
    
    // Si el carrito está vacío en checkout, intentar restaurar
    if ( WC()->cart->is_empty() ) {
        $cart_data = null;
        
        // Intentar obtener datos del carrito desde múltiples fuentes
        if ( WC()->session ) {
            $cart_data = WC()->session->get( 'wcsf_cart_backup' );
        }
        
        if ( ! $cart_data ) {
            $cart_data = get_transient( 'wcsf_cart_backup_' . WC()->session->get_customer_id() );
        }
        
        if ( ! $cart_data && isset( $_COOKIE['wcsf_cart_backup'] ) ) {
            $cart_data = json_decode( stripslashes( $_COOKIE['wcsf_cart_backup'] ), true );
        }
        
        // Restaurar productos al carrito
        if ( $cart_data && is_array( $cart_data ) ) {
            foreach ( $cart_data as $item ) {
                if ( isset( $item['product_id'] ) ) {
                    WC()->cart->add_to_cart(
                        $item['product_id'],
                        $item['quantity'],
                        $item['variation_id'],
                        $item['variation']
                    );
                }
            }
            
            // Forzar recálculo
            WC()->cart->calculate_totals();
            
            // Limpiar backup después de restaurar
            delete_transient( 'wcsf_cart_backup_' . WC()->session->get_customer_id() );
            WC()->session->__unset( 'wcsf_cart_backup' );
            setcookie( 'wcsf_cart_backup', '', time() - 3600, '/' );
        }
    }
}

// Desactivar MercadoPago completamente en checkout para evitar conflictos
add_action( 'wp_enqueue_scripts', 'wcsf_disable_mercadopago_scripts', 999 );
function wcsf_disable_mercadopago_scripts() {
    if ( is_checkout() ) {
        // Lista completa de scripts de MercadoPago a desactivar
        $mp_scripts = array(
            'woocommerce-mercadopago-checkout-custom',
            'woocommerce-mercadopago-custom-checkout',
            'mp-custom-checkout',
            'mercadopago-checkout',
            'mercadopago-custom-checkout',
            'wc-mercadopago-checkout',
            'wc-mp-checkout'
        );
        
        foreach ( $mp_scripts as $script ) {
            wp_dequeue_script( $script );
            wp_deregister_script( $script );
        }
        
        // También desactivar estilos relacionados
        $mp_styles = array(
            'woocommerce-mercadopago-checkout-custom',
            'mp-custom-checkout',
            'mercadopago-checkout'
        );
        
        foreach ( $mp_styles as $style ) {
            wp_dequeue_style( $style );
            wp_deregister_style( $style );
        }
    }
}

// Debug en consola para verificar funcionamiento
add_action( 'wp_footer', 'wcsf_debug_cart_status' );
function wcsf_debug_cart_status() {
    if ( ( is_cart() || is_checkout() ) && function_exists( 'WC' ) ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_total();
        $page_type = is_cart() ? 'CART' : 'CHECKOUT';
        
        ?>
        <script>
        console.log('=== WCSF DEBUG <?php echo $page_type; ?> ===');
        console.log('Items en carrito:', <?php echo $cart_count; ?>);
        console.log('Total:', '<?php echo esc_js( $cart_total ); ?>');
        console.log('Sesión ID:', '<?php echo WC()->session->get_customer_id(); ?>');
        </script>
        <?php
    }
}
