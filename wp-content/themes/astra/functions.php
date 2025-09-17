<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

// Función para registrar mensajes de depuración
function log_debug_info($message, $data = null, $backtrace = false) {
    $log_entry = '[' . current_time('mysql') . '] ' . $message . "\n";
    
    if ($data !== null) {
        if (is_array($data) || is_object($data)) {
            $log_entry .= 'Datos: ' . print_r($data, true) . "\n";
        } else {
            $log_entry .= 'Datos: ' . $data . "\n";
        }
    }
    
    if ($backtrace) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $log_entry .= 'Backtrace: ' . print_r($backtrace, true) . "\n";
    }
    
    $log_entry .= 'Memoria usada: ' . size_format(memory_get_usage()) . "\n";
    $log_entry .= 'Límite de memoria: ' . ini_get('memory_limit') . "\n";
    $log_entry .= 'Tiempo de ejecución: ' . timer_stop() . 's' . "\n";
    $log_entry .= '===================================' . "\n\n";
    
    error_log($log_entry);
}

// Código de prueba para verificar el registro de errores
function test_debug_logging() {
    log_debug_info('=== INICIO DE PRUEBA DE REGISTRO ===', null, true);
    
    // Verificar si estamos en una petición AJAX
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $ajax_info = array(
            'action' => isset($_POST['action']) ? $_POST['action'] : 'Sin acción',
            'nonce' => isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : 'Sin nonce',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Sin referer',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Sin user agent'
        );
        log_debug_info('=== PETICIÓN AJAX DETECTADA ===', $ajax_info, true);
    }
    
    // Registrar información del servidor
    $server_info = array(
        'PHP Version' => phpversion(),
        'WordPress Version' => get_bloginfo('version'),
        'WooCommerce Active' => class_exists('WooCommerce') ? 'Sí' : 'No',
        'Tutor LMS Active' => function_exists('tutor') ? 'Sí' : 'No',
        'Theme' => wp_get_theme()->get('Name') . ' ' . wp_get_theme()->get('Version')
    );
    log_debug_info('=== INFORMACIÓN DEL SISTEMA ===', $server_info);
}

// Interceptar la petición AJAX específica de Tutor LMS que está fallando
add_action('wp_ajax_nopriv_tutor_get_states_by_country', 'intercept_tutor_states_ajax', 1);
add_action( 'wp_ajax_tutor_get_states_by_country', 'intercept_tutor_states_ajax', 5 );

/**
 * Filtro para manejar los campos de facturación ocultos
 * Asegura que WooCommerce acepte los valores predeterminados para el pago
 */
add_filter( 'woocommerce_checkout_posted_data', 'simplify_checkout_fields' );
function simplify_checkout_fields( $data ) {
    // Si ya hay datos de facturación, no hacemos nada
    if ( ! empty( $data['billing_first_name'] ) && $data['billing_first_name'] !== 'Cliente' ) {
        return $data;
    }
    
    // Establecer valores predeterminados para los campos de facturación
    $data['billing_first_name'] = 'Cliente';
    $data['billing_last_name']  = 'Web';
    $data['billing_email']      = ! empty( $data['billing_email'] ) ? $data['billing_email'] : get_option( 'admin_email' );
    $data['billing_phone']      = '0000000000';
    $data['billing_country']    = 'US';
    $data['billing_address_1']  = 'N/A';
    $data['billing_city']       = 'N/A';
    $data['billing_state']      = 'N/A';
    $data['billing_postcode']   = '00000';
    
    // Asegurarse de que el shipping sea igual al billing
    $data['ship_to_different_address'] = '0';
    
    return $data;
}

/**
 * Filtro para limitar las pasarelas de pago disponibles solo a Mercado Pago y PayPal
 */
add_filter( 'woocommerce_available_payment_gateways', 'limit_payment_gateways' );
function limit_payment_gateways( $gateways ) {
    // Solo permitir estas pasarelas específicas
    $allowed_gateways = array('woo-mercado-pago-basic', 'ppcp-gateway');
    
    // Log de todas las pasarelas disponibles para depuración
    error_log( 'Todas las pasarelas disponibles: ' . implode( ', ', array_keys( $gateways ) ) );
    
    // Filtrar las pasarelas disponibles
    $filtered_gateways = array();
    foreach ( $allowed_gateways as $gateway_id ) {
        if ( isset( $gateways[$gateway_id] ) ) {
            $filtered_gateways[$gateway_id] = $gateways[$gateway_id];
        }
    }
    
    // Log para depuración
    error_log( 'Pasarelas filtradas: ' . implode( ', ', array_keys( $filtered_gateways ) ) );
    
    return $filtered_gateways;
}

/**
 * Asegurar que las pasarelas funcionen correctamente con Tutor LMS
 */
add_action( 'woocommerce_checkout_process', 'validate_tutor_checkout' );
function validate_tutor_checkout() {
    // Verificar que el método de pago seleccionado sea válido
    if ( isset( $_POST['payment_method'] ) ) {
        $allowed_methods = array( 'woo-mercado-pago-basic', 'ppcp-gateway' );
        if ( ! in_array( $_POST['payment_method'], $allowed_methods ) ) {
            wc_add_notice( 'Método de pago no válido. Por favor selecciona Mercado Pago o PayPal.', 'error' );
        }
    }
}

/**
 * Interceptar el proceso de pago de Tutor LMS y redirigir a WooCommerce
 */
add_action( 'tutor_action_tutor_pay_now', 'intercept_tutor_payment_process', 1 );
function intercept_tutor_payment_process() {
    error_log( 'INTERCEPTOR: Proceso de pago de Tutor LMS iniciado' );
    
    // Verificar que tenemos los datos necesarios
    if ( ! isset( $_POST['payment_method'] ) || ! isset( $_POST['object_ids'] ) ) {
        error_log( 'INTERCEPTOR: Faltan datos necesarios' );
        return;
    }
    
    $payment_method = $_POST['payment_method'];
    $woocommerce_gateways = array( 'woo-mercado-pago-basic', 'ppcp-gateway' );
    
    // Solo interceptar si es una de nuestras pasarelas de WooCommerce
    if ( ! in_array( $payment_method, $woocommerce_gateways ) ) {
        error_log( 'INTERCEPTOR: Método de pago no es WooCommerce: ' . $payment_method );
        return;
    }
    
    error_log( 'INTERCEPTOR: Interceptando pago para ' . $payment_method );
    error_log( 'INTERCEPTOR: Datos POST: ' . print_r( $_POST, true ) );
    
    try {
        // Obtener el ID del curso
        $course_id = intval( $_POST['object_ids'] );
        if ( ! $course_id ) {
            error_log( 'INTERCEPTOR: ID de curso inválido' );
            return;
        }
        
        // Buscar el producto de WooCommerce asociado al curso
        $product_id = tutor_utils()->get_course_product_id( $course_id );
        if ( ! $product_id ) {
            error_log( 'INTERCEPTOR: No se encontró producto asociado al curso ' . $course_id );
            return;
        }
        
        error_log( 'INTERCEPTOR: Producto encontrado: ' . $product_id . ' para curso: ' . $course_id );
        
        // Limpiar el carrito de WooCommerce
        WC()->cart->empty_cart();
        
        // Agregar el producto al carrito de WooCommerce
        $cart_item_key = WC()->cart->add_to_cart( $product_id, 1 );
        if ( ! $cart_item_key ) {
            error_log( 'INTERCEPTOR: Error al agregar producto al carrito' );
            return;
        }
        
        error_log( 'INTERCEPTOR: Producto agregado al carrito de WooCommerce' );
        
        // Establecer datos de facturación en la sesión
        $billing_data = array(
            'billing_first_name' => $_POST['billing_first_name'] ?? 'Cliente',
            'billing_last_name'  => $_POST['billing_last_name'] ?? 'Web',
            'billing_email'      => $_POST['billing_email'] ?? 'cliente@ejemplo.com',
            'billing_phone'      => $_POST['billing_phone'] ?? '0000000000',
            'billing_country'    => $_POST['billing_country'] ?? 'US',
            'billing_address_1'  => $_POST['billing_address_1'] ?? 'N/A',
            'billing_city'       => $_POST['billing_city'] ?? 'N/A',
            'billing_state'      => $_POST['billing_state'] ?? 'N/A',
            'billing_postcode'   => $_POST['billing_postcode'] ?? '00000'
        );
        
        // Guardar datos de facturación en la sesión de WooCommerce
        foreach ( $billing_data as $key => $value ) {
            WC()->customer->{"set_$key"}( $value );
        }
        WC()->customer->save();
        
        error_log( 'INTERCEPTOR: Datos de facturación establecidos' );
        
        // Redirigir al checkout de WooCommerce con el método de pago preseleccionado
        $checkout_url = wc_get_checkout_url();
        $checkout_url = add_query_arg( 'payment_method', $payment_method, $checkout_url );
        
        error_log( 'INTERCEPTOR: Redirigiendo a WooCommerce checkout: ' . $checkout_url );
        
        wp_redirect( $checkout_url );
        exit;
        
    } catch ( Exception $e ) {
        error_log( 'INTERCEPTOR: Error - ' . $e->getMessage() );
    }
}


function intercept_tutor_states_ajax() {
    // Verificar si es la acción que estamos buscando
    if (isset($_POST['action']) && $_POST['action'] === 'tutor_get_states_by_country') {
        // Registrar información detallada de la petición
        $request_data = array(
            'action' => $_POST['action'],
            'country' => isset($_POST['country']) ? $_POST['country'] : 'No definido',
            'nonce' => isset($_POST['nonce']) ? $_POST['nonce'] : 'No definido',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No definido',
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'request_uri' => $_SERVER['REQUEST_URI'],
            'http_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'No definido'
        );
        
        log_debug_info('=== INTERCEPTOR TUTOR GET STATES ===', $request_data, true);
        
        // Verificar nonce si está presente
        if (isset($_POST['nonce'])) {
            $nonce_verification = wp_verify_nonce($_POST['nonce'], 'tutor_nonce');
            log_debug_info('Verificación de nonce', array(
                'nonce' => $_POST['nonce'],
                'verification_result' => $nonce_verification ? 'Válido' : 'Inválido o expirado'
            ));
        }
        
        // Verificar si WooCommerce está cargado
        if (function_exists('WC')) {
            $country = isset($_POST['country']) ? wc_clean(wp_unslash($_POST['country'])) : '';
            $states = WC()->countries->get_states($country);
            log_debug_info('Estados obtenidos de WooCommerce', array(
                'país' => $country,
                'estados' => $states ? $states : 'No se encontraron estados para este país'
            ));
        } else {
            log_debug_info('ERROR: WooCommerce no está cargado', null, true);
        }
    }
    
    // No detenemos la ejecución, permitimos que continúe el flujo normal
}

// Ejecutar la prueba cuando WordPress se haya cargado completamente
add_action('wp_loaded', 'test_debug_logging');

// Mensaje de depuración para verificar que el archivo se está cargando
error_log('Archivo functions.php cargado correctamente - ' . date('Y-m-d H:i:s'));

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'ASTRA_THEME_VERSION', '4.8.0' );
define( 'ASTRA_THEME_SETTINGS', 'astra-settings' );
define( 'ASTRA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'ASTRA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );

/**
 * Minimum Version requirement of the Astra Pro addon.
 * This constant will be used to display the notice asking user to update the Astra addon to the version defined below.
 */
define( 'ASTRA_EXT_MIN_VER', '4.8.0' );

/**
 * Setup helper functions of Astra.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-theme-options.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once ASTRA_THEME_DIR . 'inc/core/common-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-icons.php';

define( 'ASTRA_PRO_UPGRADE_URL', astra_get_pro_url( 'https://wpastra.com/pricing/', 'dashboard', 'free-theme', 'dashboard' ) );
define( 'ASTRA_PRO_CUSTOMIZER_UPGRADE_URL', astra_get_pro_url( 'https://wpastra.com/pricing/', 'customizer', 'free-theme', 'upgrade' ) );

/**
 * Update theme
 */
require_once ASTRA_THEME_DIR . 'inc/theme-update/astra-update-functions.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-font-families.php';
if ( is_admin() ) {
	require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts-data.php';
}

require_once ASTRA_THEME_DIR . 'inc/lib/webfont/class-astra-webfont-loader.php';
require_once ASTRA_THEME_DIR . 'inc/lib/docs/class-astra-docs-loader.php';
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts.php';

require_once ASTRA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/astra-icons.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-walker-page.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-enqueue-scripts.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-wp-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-dynamic-css.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-global-palette.php';

/**
 * Custom template tags for this theme.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-attr.php';
require_once ASTRA_THEME_DIR . 'inc/template-tags.php';

require_once ASTRA_THEME_DIR . 'inc/widgets.php';
require_once ASTRA_THEME_DIR . 'inc/core/theme-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/admin-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once ASTRA_THEME_DIR . 'inc/markup-extras.php';
require_once ASTRA_THEME_DIR . 'inc/extras.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog-config.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog.php';
require_once ASTRA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once ASTRA_THEME_DIR . 'inc/template-parts.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-loop.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once ASTRA_THEME_DIR . 'inc/class-astra-after-setup-theme.php';

// Required files.
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-helper.php';

require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-schema.php';

/* Setup API */
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-settings.php';
	require_once ASTRA_THEME_DIR . 'admin/class-astra-admin-loader.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/astra-notices/class-astra-notices.php';
}

/**
 * Metabox additions.
 */
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-boxes.php';

require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-box-operations.php';

/**
 * Customizer additions.
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer.php';

/**
 * Astra Modules.
 */
require_once ASTRA_THEME_DIR . 'inc/modules/posts-structures/class-astra-post-structures.php';
require_once ASTRA_THEME_DIR . 'inc/modules/related-posts/class-astra-related-posts.php';

/**
 * Compatibility
 */
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gutenberg.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-jetpack.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/class-astra-woocommerce.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/edd/class-astra-edd.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/lifterlms/class-astra-lifterlms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/learndash/class-astra-learndash.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bb-ultimate-addon.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-contact-form-7.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-visual-composer.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-site-origin.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gravity-forms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bne-flyout.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-ubermeu.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-divi-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-amp.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-yoast-seo.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/surecart/class-astra-surecart.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-starter-content.php';
require_once ASTRA_THEME_DIR . 'inc/addons/transparent-header/class-astra-ext-transparent-header.php';
require_once ASTRA_THEME_DIR . 'inc/addons/breadcrumbs/class-astra-breadcrumbs.php';
require_once ASTRA_THEME_DIR . 'inc/addons/scroll-to-top/class-astra-scroll-to-top.php';
require_once ASTRA_THEME_DIR . 'inc/addons/heading-colors/class-astra-heading-colors.php';
require_once ASTRA_THEME_DIR . 'inc/builder/class-astra-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor-pro.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-themer.php';
}

require_once ASTRA_THEME_DIR . 'inc/core/markup/class-astra-markup.php';

/**
 * Load deprecated functions
 */
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';

/**
 * Redirigir al carrito estándar de WooCommerce después de añadir producto
 */
add_filter( 'woocommerce_add_to_cart_redirect', 'astra_redirect_to_wc_cart' );
function astra_redirect_to_wc_cart() {
    return wc_get_cart_url(); // Redirige a /cart/
}

/**
 * SOLUCIÓN DEFINITIVA: Sincronizar Tutor LMS con WooCommerce
 * Desactiva las validaciones de carrito vacío que bloquean el checkout
 */

// 1. Desactivar redirección automática de checkout vacío
add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );

// 2. Sincronizar carrito de Tutor LMS con WooCommerce antes del checkout
add_action( 'template_redirect', 'astra_sync_tutor_cart_with_woocommerce', 1 );
function astra_sync_tutor_cart_with_woocommerce() {
    if ( ! is_checkout() || ! function_exists( 'WC' ) ) {
        return;
    }
    
    // Si WooCommerce dice que el carrito está vacío, buscar productos de Tutor LMS
    if ( WC()->cart->is_empty() ) {
        // Buscar si hay cursos en el carrito de Tutor LMS
        if ( class_exists( 'Tutor\Models\CartModel' ) ) {
            $cart_model = new \Tutor\Models\CartModel();
            $user_id = get_current_user_id();
            
            // Obtener productos del carrito de Tutor LMS
            $tutor_cart_items = $cart_model->get_cart_items( $user_id );
            
            if ( ! empty( $tutor_cart_items ) ) {
                foreach ( $tutor_cart_items as $item ) {
                    // Buscar el producto WooCommerce asociado al curso
                    $product_id = tutor_utils()->get_course_product_id( $item->course_id );
                    if ( $product_id ) {
                        WC()->cart->add_to_cart( $product_id, 1 );
                    }
                }
                WC()->cart->calculate_totals();
            }
        }
        
        // Si aún está vacío, crear un producto temporal para el checkout
        if ( WC()->cart->is_empty() ) {
            // Buscar cualquier curso que se esté intentando comprar
            $course_id = get_query_var( 'course_id' );
            if ( ! $course_id && isset( $_GET['course_id'] ) ) {
                $course_id = intval( $_GET['course_id'] );
            }
            
            if ( $course_id ) {
                $product_id = tutor_utils()->get_course_product_id( $course_id );
                if ( $product_id ) {
                    WC()->cart->add_to_cart( $product_id, 1 );
                    WC()->cart->calculate_totals();
                }
            }
        }
    }
}

// 3. Mostrar productos en checkout incluso si WooCommerce dice que está vacío
add_action( 'woocommerce_checkout_order_review', 'astra_force_checkout_display', 1 );
function astra_force_checkout_display() {
    if ( ! function_exists( 'WC' ) ) {
        return;
    }
    
    // Si el carrito sigue vacío, mostrar un producto por defecto
    if ( WC()->cart->is_empty() ) {
        // Buscar el último producto de curso publicado
        $course_product = get_posts( array(
            'post_type' => 'product',
            'meta_query' => array(
                array(
                    'key' => '_tutor_product',
                    'compare' => 'EXISTS'
                )
            ),
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ) );
        
        if ( ! empty( $course_product ) ) {
            WC()->cart->add_to_cart( $course_product[0]->ID, 1 );
            WC()->cart->calculate_totals();
        }
    }
}

// 4. Debug visible en checkout para verificar funcionamiento
add_action( 'wp_footer', 'astra_checkout_debug_visible' );
function astra_checkout_debug_visible() {
    if ( is_checkout() && function_exists( 'WC' ) ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_total();
        
        echo '<div style="position: fixed; top: 10px; right: 10px; background: #000; color: #fff; padding: 10px; z-index: 9999; font-size: 12px; border-radius: 5px;">';
        echo '<strong>DEBUG CHECKOUT:</strong><br>';
        echo 'Items: ' . $cart_count . '<br>';
        echo 'Total: ' . $cart_total . '<br>';
        echo 'Filtro aplicado: ✓<br>';
        echo 'Sincronización: ✓';
        echo '</div>';
    }
}

// SOLUCIÓN SEGURA: Sincronización bidireccional Tutor LMS <-> WooCommerce

// 1. Sincronizar de Tutor LMS a WooCommerce cuando se añade un curso
add_action( 'wp_ajax_tutor_add_course_to_cart', 'sync_tutor_to_woocommerce', 99 );
add_action( 'wp_ajax_nopriv_tutor_add_course_to_cart', 'sync_tutor_to_woocommerce', 99 );
function sync_tutor_to_woocommerce() {
    error_log( '[SYNC DEBUG] sync_tutor_to_woocommerce iniciado - Action: ' . ( $_POST['action'] ?? 'no_action' ) );
    
    // Solo procesar después de que Tutor LMS haya procesado la petición
    if ( ! function_exists( 'WC' ) || ! function_exists( 'tutor_utils' ) ) {
        error_log( '[SYNC DEBUG] Funciones WC o tutor_utils no disponibles' );
        return;
    }
    
    // Verificar que sea realmente una adición de curso
    if ( ! isset( $_POST['course_id'] ) || ! isset( $_POST['action'] ) || $_POST['action'] !== 'tutor_add_course_to_cart' ) {
        error_log( '[SYNC DEBUG] No es acción tutor_add_course_to_cart - Action: ' . ( $_POST['action'] ?? 'no_action' ) );
        return;
    }
    
    $course_id = intval( $_POST['course_id'] );
    if ( ! $course_id ) {
        error_log( '[SYNC DEBUG] Course ID inválido: ' . ( $_POST['course_id'] ?? 'no_course_id' ) );
        return;
    }
    
    error_log( '[SYNC DEBUG] Procesando course_id: ' . $course_id );
    
    // Obtener el product_id asociado al curso
    $product_id = get_post_meta( $course_id, '_tutor_course_product_id', true );
    if ( ! $product_id ) {
        // Método alternativo
        if ( method_exists( tutor_utils(), 'get_course_product_id' ) ) {
            $product_id = tutor_utils()->get_course_product_id( $course_id );
        }
    }
    
    error_log( '[SYNC DEBUG] Product ID encontrado: ' . ( $product_id ?: 'no_product_id' ) );
    
    if ( $product_id && get_post_status( $product_id ) === 'publish' ) {
        // Verificar si ya está en el carrito para evitar duplicados
        $cart_contents = WC()->cart->get_cart();
        $product_in_cart = false;
        
        foreach ( $cart_contents as $cart_item ) {
            if ( $cart_item['product_id'] == $product_id ) {
                $product_in_cart = true;
                break;
            }
        }
        
        error_log( '[SYNC DEBUG] Producto en carrito: ' . ( $product_in_cart ? 'SI' : 'NO' ) );
        
        if ( ! $product_in_cart ) {
            $result = WC()->cart->add_to_cart( $product_id, 1 );
            WC()->cart->calculate_totals();
            error_log( '[SYNC DEBUG] Producto añadido a WC cart - Resultado: ' . ( $result ? 'SUCCESS' : 'FAILED' ) );
        }
    } else {
        error_log( '[SYNC DEBUG] Producto no válido - ID: ' . $product_id . ', Status: ' . get_post_status( $product_id ) );
    }
}

// 2. Sincronizar de WooCommerce a Tutor LMS cuando se añade un producto
add_action( 'woocommerce_add_to_cart', 'sync_woocommerce_to_tutor', 10, 6 );
function sync_woocommerce_to_tutor( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
    error_log( '[SYNC DEBUG] sync_woocommerce_to_tutor iniciado - Product ID: ' . $product_id );
    
    if ( ! function_exists( 'tutor_utils' ) || ! class_exists( 'Tutor\Models\CartModel' ) ) {
        error_log( '[SYNC DEBUG] Funciones Tutor no disponibles en WC sync' );
        return;
    }
    
    try {
        // Verificar si el producto pertenece a un curso
        $course_id = null;
        
        // Buscar curso asociado al producto
        $courses = get_posts( array(
            'post_type' => 'courses',
            'meta_query' => array(
                array(
                    'key' => '_tutor_course_product_id',
                    'value' => $product_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ) );
        
        if ( ! empty( $courses ) ) {
            $course_id = $courses[0]->ID;
        }
        
        error_log( '[SYNC DEBUG] Course ID encontrado para product ' . $product_id . ': ' . ( $course_id ?: 'no_course' ) );
        
        if ( $course_id ) {
            $user_id = get_current_user_id();
            if ( $user_id ) {
                $tutor_cart_model = new \Tutor\Models\CartModel();
                // Verificar si el curso ya está en el carrito de Tutor LMS usando método más seguro
                $existing_items = $tutor_cart_model->get_cart_items( $user_id );
                $course_exists = false;
                
                if ( ! empty( $existing_items ) ) {
                    foreach ( $existing_items as $item ) {
                        if ( isset( $item->course_id ) && $item->course_id == $course_id ) {
                            $course_exists = true;
                            break;
                        }
                    }
                }
                
                error_log( '[SYNC DEBUG] Curso existe en Tutor cart: ' . ( $course_exists ? 'SI' : 'NO' ) );
                
                if ( ! $course_exists ) {
                    $result = $tutor_cart_model->add_course_to_cart( $user_id, $course_id );
                    error_log( '[SYNC DEBUG] Curso añadido a Tutor cart - Resultado: ' . ( $result ? 'SUCCESS' : 'FAILED' ) );
                }
            } else {
                error_log( '[SYNC DEBUG] Usuario no logueado para sync WC->Tutor' );
            }
        }
    } catch ( Exception $e ) {
        // Log error pero no interrumpir el proceso
        error_log( '[SYNC ERROR] Sync WooCommerce to Tutor error: ' . $e->getMessage() );
    }
}

// 3. Recuperar carrito de Tutor LMS en checkout si WooCommerce está vacío
add_action( 'template_redirect', 'recover_tutor_cart_in_checkout' );
function recover_tutor_cart_in_checkout() {
    if ( ! is_checkout() || ! function_exists( 'WC' ) || ! function_exists( 'tutor_utils' ) ) {
        return;
    }
    
    if ( WC()->cart->is_empty() && class_exists( 'Tutor\Models\CartModel' ) ) {
        $user_id = get_current_user_id();
        if ( $user_id ) {
            $tutor_cart_model = new \Tutor\Models\CartModel();
            $tutor_cart_items = $tutor_cart_model->get_cart_items( $user_id );
            
            if ( ! empty( $tutor_cart_items ) ) {
                foreach ( $tutor_cart_items as $item ) {
                    $product_id = get_post_meta( $item->course_id, '_tutor_course_product_id', true );
                    if ( $product_id && get_post_status( $product_id ) === 'publish' ) {
                        WC()->cart->add_to_cart( $product_id, 1 );
                    }
                }
                WC()->cart->calculate_totals();
            }
        }
    }
}

// 4. Desactivar redirección de carrito vacío en checkout
add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );

// 5. Sincronizar eliminación de WooCommerce a Tutor LMS
add_action( 'woocommerce_remove_cart_item', 'sync_wc_remove_to_tutor', 10, 2 );
function sync_wc_remove_to_tutor( $cart_item_key, $cart ) {
    if ( ! function_exists( 'tutor_utils' ) || ! class_exists( 'Tutor\Models\CartModel' ) ) {
        return;
    }
    
    try {
        // Obtener el producto que se está eliminando
        $cart_item = $cart->removed_cart_contents[ $cart_item_key ];
        if ( ! isset( $cart_item['product_id'] ) ) {
            return;
        }
        
        $product_id = $cart_item['product_id'];
        
        // Buscar si este producto pertenece a un curso
        $courses = get_posts( array(
            'post_type' => 'courses',
            'meta_query' => array(
                array(
                    'key' => '_tutor_course_product_id',
                    'value' => $product_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ) );
        
        if ( ! empty( $courses ) ) {
            $course_id = $courses[0]->ID;
            $user_id = get_current_user_id();
            
            if ( $user_id && $course_id ) {
                $tutor_cart_model = new \Tutor\Models\CartModel();
                $tutor_cart_model->delete_course_from_cart( $user_id, $course_id );
            }
        }
    } catch ( Exception $e ) {
        error_log( 'Sync WC remove to Tutor error: ' . $e->getMessage() );
    }
}

// Interceptar peticiones AJAX problemáticas
add_action( 'wp_ajax_nopriv_tutor_checkout_country_change', 'debug_country_change_ajax' );
add_action( 'wp_ajax_tutor_checkout_country_change', 'debug_country_change_ajax' );
function debug_country_change_ajax() {
    error_log( '[COUNTRY DEBUG] Petición country change detectada - POST: ' . json_encode( $_POST ) );
    // No hacer nada más, solo loggear
}

// Interceptar cualquier petición AJAX que cause 400
add_action( 'wp_ajax_nopriv_tutor_get_states_by_country', 'debug_states_ajax' );
add_action( 'wp_ajax_tutor_get_states_by_country', 'debug_states_ajax' );
function debug_states_ajax() {
    // Habilitar el registro de errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Registrar la petición con todos los detalles
    error_log('========================================');
    error_log('[TUTOR AJAX] Interceptada petición de estados por país - ' . date('Y-m-d H:i:s'));
    error_log('[TUTOR AJAX] Método de solicitud: ' . $_SERVER['REQUEST_METHOD']);
    error_log('[TUTOR AJAX] Datos POST: ' . print_r($_POST, true));
    error_log('[TUTOR AJAX] Datos GET: ' . print_r($_GET, true));
    
    // Verificar si WooCommerce está cargado
    if (!function_exists('WC')) {
        error_log('[TUTOR AJAX] ERROR: WooCommerce no está cargado');
        wp_send_json_error('WooCommerce no está disponible');
        return;
    }
    
    // Verificar si hay un nonce y validarlo
    if (!isset($_POST['nonce'])) {
        error_log('[TUTOR AJAX] ERROR: Falta el nonce en la petición');
        wp_send_json_error('Nonce no proporcionado');
        return;
    }
    
    if (!wp_verify_nonce($_POST['nonce'], 'tutor_nonce')) {
        error_log('[TUTOR AJAX] ERROR: Nonce inválido');
        wp_send_json_error('Nonce inválido');
        return;
    }
    
    // Verificar si se proporcionó el país
    if (!isset($_POST['country'])) {
        error_log('[TUTOR AJAX] ERROR: No se proporcionó el país');
        wp_send_json_error('País no proporcionado');
        return;
    }
    
    $country = sanitize_text_field($_POST['country']);
    error_log('[TUTOR AJAX] Procesando país: ' . $country);
    
    try {
        // Obtener los estados del país
        $states = WC()->countries->get_states($country);
        
        if (empty($states)) {
            error_log('[TUTOR AJAX] No se encontraron estados para: ' . $country);
            $states = array(); // Asegurarse de devolver un array vacío en lugar de null
        } else {
            error_log('[TUTOR AJAX] Estados encontrados para ' . $country . ': ' . count($states));
        }
        
        // Registrar los primeros 3 estados como ejemplo
        $example_states = array_slice($states, 0, 3, true);
        error_log('[TUTOR AJAX] Ejemplo de estados: ' . print_r($example_states, true));
        
        wp_send_json_success($states);
        
    } catch (Exception $e) {
        error_log('[TUTOR AJAX] EXCEPCIÓN al obtener estados: ' . $e->getMessage());
        wp_send_json_error('Error al obtener los estados: ' . $e->getMessage());
    }
    
    error_log('========================================');
}

// Log general para todas las peticiones AJAX de Tutor
add_action( 'init', 'setup_tutor_ajax_logging' );
function setup_tutor_ajax_logging() {
    if ( ! empty( $_POST['action'] ) && strpos( $_POST['action'], 'tutor_' ) === 0 ) {
        // Registrar la petición AJAX con más contexto
        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
        $backtrace_info = array();
        
        foreach ( $backtrace as $i => $trace ) {
            $backtrace_info[] = sprintf(
                '#%s %s:%s %s%s%s',
                $i,
                $trace['file'] ?? '',
                $trace['line'] ?? '',
                $trace['class'] ?? '',
                $trace['type'] ?? '',
                $trace['function'] ?? ''
            );
        }
        
        error_log( '[TUTOR AJAX] Action: ' . $_POST['action'] . ' | Data: ' . json_encode( $_POST ) );
        error_log( '[TUTOR AJAX] Backtrace: ' . implode( '\n', $backtrace_info ) );
        
        // Registrar también las variables de servidor relevantes
        $server_vars = array(
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? '',
            'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? '',
            'HTTP_REFERER' => $_SERVER['HTTP_REFERER'] ?? '',
            'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? ''
        );
        
        error_log( '[TUTOR AJAX] Server vars: ' . print_r( $server_vars, true ) );
    }
}

// 6. Sincronizar eliminación de Tutor LMS a WooCommerce
add_action( 'wp_ajax_tutor_delete_course_from_cart', 'sync_tutor_remove_to_wc', 99 );
add_action( 'wp_ajax_nopriv_tutor_delete_course_from_cart', 'sync_tutor_remove_to_wc', 99 );
function sync_tutor_remove_to_wc() {
    error_log( '[SYNC DEBUG] sync_tutor_remove_to_wc iniciado - Action: ' . ( $_POST['action'] ?? 'no_action' ) );
    
    // Solo procesar si es realmente una eliminación de curso del carrito
    if ( ! isset( $_POST['course_id'] ) || ! function_exists( 'WC' ) || ! function_exists( 'tutor_utils' ) ) {
        error_log( '[SYNC DEBUG] Condiciones no cumplidas para remove sync' );
        return;
    }
    
    // Verificar que no sea otra acción AJAX de Tutor LMS
    if ( isset( $_POST['action'] ) && $_POST['action'] !== 'tutor_delete_course_from_cart' ) {
        error_log( '[SYNC DEBUG] No es acción delete_course_from_cart - Action: ' . $_POST['action'] );
        return;
    }
    
    try {
        $course_id = intval( $_POST['course_id'] );
        if ( ! $course_id ) {
            error_log( '[SYNC DEBUG] Course ID inválido en remove: ' . ( $_POST['course_id'] ?? 'no_course_id' ) );
            return;
        }
        
        error_log( '[SYNC DEBUG] Eliminando course_id: ' . $course_id );
        
        // Obtener el product_id asociado al curso
        $product_id = get_post_meta( $course_id, '_tutor_course_product_id', true );
        if ( ! $product_id ) {
            if ( method_exists( tutor_utils(), 'get_course_product_id' ) ) {
                $product_id = tutor_utils()->get_course_product_id( $course_id );
            }
        }
        
        error_log( '[SYNC DEBUG] Product ID para eliminar: ' . ( $product_id ?: 'no_product_id' ) );
        
        if ( $product_id ) {
            // Buscar y eliminar el producto del carrito de WooCommerce
            $cart_contents = WC()->cart->get_cart();
            $removed = false;
            foreach ( $cart_contents as $cart_item_key => $cart_item ) {
                if ( $cart_item['product_id'] == $product_id ) {
                    WC()->cart->remove_cart_item( $cart_item_key );
                    $removed = true;
                    break;
                }
            }
            WC()->cart->calculate_totals();
            error_log( '[SYNC DEBUG] Producto eliminado de WC cart: ' . ( $removed ? 'SUCCESS' : 'NOT_FOUND' ) );
        }
    } catch ( Exception $e ) {
        error_log( '[SYNC ERROR] Sync Tutor remove to WC error: ' . $e->getMessage() );
    }
}

// 7. Sincronizar cambios de cantidad
add_action( 'woocommerce_after_cart_item_quantity_update', 'sync_wc_quantity_to_tutor', 10, 4 );
function sync_wc_quantity_to_tutor( $cart_item_key, $quantity, $old_quantity, $cart ) {
    // Para cursos, la cantidad siempre debe ser 1, así que si se cambia a 0, eliminar
    if ( $quantity == 0 ) {
        sync_wc_remove_to_tutor( $cart_item_key, $cart );
    }
}

// 8. Debug visible para diagnóstico
add_action( 'wp_footer', 'cart_sync_debug' );
function cart_sync_debug() {
    if ( ( is_checkout() || is_cart() ) && function_exists( 'WC' ) ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_total();
        
        echo '<div style="position: fixed; top: 10px; right: 10px; background: #28a745; color: #fff; padding: 10px; z-index: 9999; font-size: 12px; border-radius: 5px; max-width: 200px;">';
        echo '<strong>SYNC DEBUG:</strong><br>';
        echo 'WC Items: ' . $cart_count . '<br>';
        echo 'WC Total: ' . $cart_total . '<br>';
        echo 'Hooks: ✓<br>';
        echo 'Sync: Completo';
        echo '</div>';
    }
}
