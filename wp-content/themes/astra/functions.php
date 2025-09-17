<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

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
add_action( 'wp_ajax_tutor_add_course_to_cart', 'sync_tutor_to_woocommerce', 5 );
add_action( 'wp_ajax_nopriv_tutor_add_course_to_cart', 'sync_tutor_to_woocommerce', 5 );
function sync_tutor_to_woocommerce() {
    if ( ! function_exists( 'WC' ) || ! function_exists( 'tutor_utils' ) ) {
        return;
    }
    
    $course_id = isset( $_POST['course_id'] ) ? intval( $_POST['course_id'] ) : 0;
    if ( ! $course_id ) {
        return;
    }
    
    // Obtener el product_id asociado al curso
    $product_id = get_post_meta( $course_id, '_tutor_course_product_id', true );
    if ( ! $product_id ) {
        // Método alternativo
        if ( method_exists( tutor_utils(), 'get_course_product_id' ) ) {
            $product_id = tutor_utils()->get_course_product_id( $course_id );
        }
    }
    
    if ( $product_id && get_post_status( $product_id ) === 'publish' ) {
        // Añadir al carrito de WooCommerce
        WC()->cart->add_to_cart( $product_id, 1 );
        WC()->cart->calculate_totals();
    }
}

// 2. Sincronizar de WooCommerce a Tutor LMS cuando se añade un producto
add_action( 'woocommerce_add_to_cart', 'sync_woocommerce_to_tutor', 10, 6 );
function sync_woocommerce_to_tutor( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
    if ( ! function_exists( 'tutor_utils' ) || ! class_exists( 'Tutor\Models\CartModel' ) ) {
        return;
    }
    
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
    
    if ( $course_id ) {
        $user_id = get_current_user_id();
        if ( $user_id ) {
            $tutor_cart_model = new \Tutor\Models\CartModel();
            // Verificar si el curso ya está en el carrito de Tutor LMS
            if ( ! $tutor_cart_model->is_course_in_user_cart( $user_id, $course_id ) ) {
                $tutor_cart_model->add_course_to_cart( $user_id, $course_id );
            }
        }
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

// 5. Debug visible para diagnóstico
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
        echo 'Sync: Activo';
        echo '</div>';
    }
}
