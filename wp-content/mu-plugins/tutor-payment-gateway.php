<?php
/**
 * Plugin Name: Tutor Payment Gateway Direct
 * Description: Integración directa con Mercado Pago y PayPal sin WooCommerce
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TutorPaymentGatewayDirect {
    
    public function __construct() {
        error_log( 'DIRECT GATEWAY: Plugin inicializado' );
        add_action( 'tutor_action_tutor_pay_now', array( $this, 'process_payment' ), 1 );
        add_action( 'wp_ajax_mercadopago_webhook', array( $this, 'handle_mercadopago_webhook' ) );
        add_action( 'wp_ajax_nopriv_mercadopago_webhook', array( $this, 'handle_mercadopago_webhook' ) );
        add_action( 'wp_ajax_paypal_webhook', array( $this, 'handle_paypal_webhook' ) );
        add_action( 'wp_ajax_nopriv_paypal_webhook', array( $this, 'handle_paypal_webhook' ) );
    }
    
    public function process_payment() {
        error_log( 'DIRECT GATEWAY: Procesando pago directo' );
        
        if ( ! isset( $_POST['payment_method'] ) || ! isset( $_POST['object_ids'] ) ) {
            error_log( 'DIRECT GATEWAY: Faltan datos necesarios' );
            return;
        }
        
        $payment_method = $_POST['payment_method'];
        $course_id = intval( $_POST['object_ids'] );
        
        // Solo procesar nuestras pasarelas
        if ( ! in_array( $payment_method, array( 'woo-mercado-pago-basic', 'ppcp-gateway' ) ) ) {
            error_log( 'DIRECT GATEWAY: Método no soportado: ' . $payment_method );
            return;
        }
        
        error_log( 'DIRECT GATEWAY: Procesando ' . $payment_method . ' para curso ' . $course_id );
        
        try {
            // Obtener información del curso
            $course = get_post( $course_id );
            if ( ! $course ) {
                throw new Exception( 'Curso no encontrado' );
            }
            
            // Obtener precio del curso
            $price = tutor_utils()->get_course_price( $course_id );
            if ( ! $price || $price <= 0 ) {
                throw new Exception( 'Precio del curso no válido' );
            }
            
            // Crear orden interna
            $order_id = $this->create_internal_order( $course_id, $price, $_POST );
            
            if ( $payment_method === 'woo-mercado-pago-basic' ) {
                $this->process_mercadopago( $order_id, $course, $price, $_POST );
            } elseif ( $payment_method === 'ppcp-gateway' ) {
                $this->process_paypal( $order_id, $course, $price, $_POST );
            }
            
        } catch ( Exception $e ) {
            error_log( 'DIRECT GATEWAY: Error - ' . $e->getMessage() );
            wp_die( 'Error en el procesamiento del pago: ' . $e->getMessage() );
        }
    }
    
    private function create_internal_order( $course_id, $price, $billing_data ) {
        global $wpdb;
        
        $order_data = array(
            'course_id' => $course_id,
            'user_id' => get_current_user_id(),
            'amount' => $price,
            'currency' => 'USD', // Cambiar según necesidad
            'status' => 'pending',
            'billing_data' => json_encode( $billing_data ),
            'created_at' => current_time( 'mysql' )
        );
        
        // Crear tabla si no existe
        $table_name = $wpdb->prefix . 'tutor_payment_orders';
        $wpdb->query( "CREATE TABLE IF NOT EXISTS {$table_name} (
            id int(11) NOT NULL AUTO_INCREMENT,
            course_id int(11) NOT NULL,
            user_id int(11) NOT NULL,
            amount decimal(10,2) NOT NULL,
            currency varchar(3) NOT NULL,
            status varchar(20) NOT NULL,
            payment_id varchar(255) NULL,
            billing_data text NULL,
            created_at datetime NOT NULL,
            updated_at datetime NULL,
            PRIMARY KEY (id)
        )" );
        
        $wpdb->insert( $table_name, $order_data );
        $order_id = $wpdb->insert_id;
        
        error_log( 'DIRECT GATEWAY: Orden interna creada con ID: ' . $order_id );
        
        return $order_id;
    }
    
    private function process_mercadopago( $order_id, $course, $price, $billing_data ) {
        error_log( 'DIRECT GATEWAY: Procesando Mercado Pago' );
        
        // Configuración de Mercado Pago (obtener de opciones de WordPress)
        $access_token = get_option( 'mercadopago_access_token', '' );
        if ( empty( $access_token ) ) {
            // Usar token de prueba por defecto o mostrar error
            $access_token = 'TEST-YOUR-ACCESS-TOKEN'; // Reemplazar con token real
        }
        
        $preference_data = array(
            'items' => array(
                array(
                    'title' => $course->post_title,
                    'description' => 'Curso: ' . $course->post_title,
                    'quantity' => 1,
                    'currency_id' => 'USD',
                    'unit_price' => floatval( $price )
                )
            ),
            'payer' => array(
                'name' => $billing_data['billing_first_name'] ?? 'Cliente',
                'surname' => $billing_data['billing_last_name'] ?? 'Web',
                'email' => $billing_data['billing_email'] ?? 'cliente@ejemplo.com'
            ),
            'back_urls' => array(
                'success' => home_url( '/payment-success/?order_id=' . $order_id ),
                'failure' => home_url( '/payment-failure/?order_id=' . $order_id ),
                'pending' => home_url( '/payment-pending/?order_id=' . $order_id )
            ),
            'auto_return' => 'approved',
            'external_reference' => strval( $order_id ),
            'notification_url' => admin_url( 'admin-ajax.php?action=mercadopago_webhook' )
        );
        
        $response = wp_remote_post( 'https://api.mercadopago.com/checkout/preferences', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $preference_data ),
            'timeout' => 30
        ) );
        
        if ( is_wp_error( $response ) ) {
            throw new Exception( 'Error al conectar con Mercado Pago: ' . $response->get_error_message() );
        }
        
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        
        if ( ! isset( $data['init_point'] ) ) {
            error_log( 'DIRECT GATEWAY: Error en respuesta de Mercado Pago: ' . $body );
            throw new Exception( 'Error al crear preferencia de Mercado Pago' );
        }
        
        error_log( 'DIRECT GATEWAY: Redirigiendo a Mercado Pago: ' . $data['init_point'] );
        
        wp_redirect( $data['init_point'] );
        exit;
    }
    
    private function process_paypal( $order_id, $course, $price, $billing_data ) {
        error_log( 'DIRECT GATEWAY: Procesando PayPal' );
        
        // Configuración de PayPal (obtener de opciones de WordPress)
        $client_id = get_option( 'paypal_client_id', '' );
        $client_secret = get_option( 'paypal_client_secret', '' );
        $sandbox = get_option( 'paypal_sandbox', true );
        
        $base_url = $sandbox ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
        
        // Obtener token de acceso
        $auth_response = wp_remote_post( $base_url . '/v1/oauth2/token', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => 'grant_type=client_credentials',
            'timeout' => 30
        ) );
        
        if ( is_wp_error( $auth_response ) ) {
            throw new Exception( 'Error al autenticar con PayPal: ' . $auth_response->get_error_message() );
        }
        
        $auth_body = wp_remote_retrieve_body( $auth_response );
        $auth_data = json_decode( $auth_body, true );
        
        if ( ! isset( $auth_data['access_token'] ) ) {
            throw new Exception( 'Error al obtener token de PayPal' );
        }
        
        $access_token = $auth_data['access_token'];
        
        // Crear orden de PayPal
        $order_data = array(
            'intent' => 'CAPTURE',
            'purchase_units' => array(
                array(
                    'reference_id' => strval( $order_id ),
                    'amount' => array(
                        'currency_code' => 'USD',
                        'value' => number_format( $price, 2, '.', '' )
                    ),
                    'description' => 'Curso: ' . $course->post_title
                )
            ),
            'application_context' => array(
                'return_url' => home_url( '/payment-success/?order_id=' . $order_id ),
                'cancel_url' => home_url( '/payment-failure/?order_id=' . $order_id )
            )
        );
        
        $create_response = wp_remote_post( $base_url . '/v2/checkout/orders', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $order_data ),
            'timeout' => 30
        ) );
        
        if ( is_wp_error( $create_response ) ) {
            throw new Exception( 'Error al crear orden de PayPal: ' . $create_response->get_error_message() );
        }
        
        $create_body = wp_remote_retrieve_body( $create_response );
        $create_data = json_decode( $create_body, true );
        
        if ( ! isset( $create_data['links'] ) ) {
            error_log( 'DIRECT GATEWAY: Error en respuesta de PayPal: ' . $create_body );
            throw new Exception( 'Error al crear orden de PayPal' );
        }
        
        // Buscar URL de aprobación
        $approval_url = '';
        foreach ( $create_data['links'] as $link ) {
            if ( $link['rel'] === 'approve' ) {
                $approval_url = $link['href'];
                break;
            }
        }
        
        if ( empty( $approval_url ) ) {
            throw new Exception( 'No se encontró URL de aprobación de PayPal' );
        }
        
        error_log( 'DIRECT GATEWAY: Redirigiendo a PayPal: ' . $approval_url );
        
        wp_redirect( $approval_url );
        exit;
    }
    
    public function handle_mercadopago_webhook() {
        error_log( 'DIRECT GATEWAY: Webhook de Mercado Pago recibido' );
        
        $input = file_get_contents( 'php://input' );
        $data = json_decode( $input, true );
        
        error_log( 'DIRECT GATEWAY: Datos del webhook: ' . print_r( $data, true ) );
        
        // Procesar webhook según el tipo
        if ( isset( $data['type'] ) && $data['type'] === 'payment' ) {
            $this->process_mercadopago_payment_notification( $data );
        }
        
        wp_die( 'OK', '', 200 );
    }
    
    public function handle_paypal_webhook() {
        error_log( 'DIRECT GATEWAY: Webhook de PayPal recibido' );
        
        $input = file_get_contents( 'php://input' );
        $data = json_decode( $input, true );
        
        error_log( 'DIRECT GATEWAY: Datos del webhook: ' . print_r( $data, true ) );
        
        // Procesar webhook de PayPal
        $this->process_paypal_payment_notification( $data );
        
        wp_die( 'OK', '', 200 );
    }
    
    private function process_mercadopago_payment_notification( $data ) {
        // Implementar lógica de procesamiento de notificación de Mercado Pago
        error_log( 'DIRECT GATEWAY: Procesando notificación de pago de Mercado Pago' );
    }
    
    private function process_paypal_payment_notification( $data ) {
        // Implementar lógica de procesamiento de notificación de PayPal
        error_log( 'DIRECT GATEWAY: Procesando notificación de pago de PayPal' );
    }
}

// Inicializar el plugin
new TutorPaymentGatewayDirect();

// Agregar páginas de resultado de pago
add_action( 'init', function() {
    add_rewrite_rule( '^payment-success/?', 'index.php?payment_result=success', 'top' );
    add_rewrite_rule( '^payment-failure/?', 'index.php?payment_result=failure', 'top' );
    add_rewrite_rule( '^payment-pending/?', 'index.php?payment_result=pending', 'top' );
} );

add_filter( 'query_vars', function( $vars ) {
    $vars[] = 'payment_result';
    return $vars;
} );

add_action( 'template_redirect', function() {
    $payment_result = get_query_var( 'payment_result' );
    if ( $payment_result ) {
        $order_id = $_GET['order_id'] ?? 0;
        
        switch ( $payment_result ) {
            case 'success':
                echo '<h1>¡Pago exitoso!</h1><p>Tu inscripción al curso ha sido procesada correctamente.</p>';
                break;
            case 'failure':
                echo '<h1>Pago fallido</h1><p>Hubo un problema con tu pago. Por favor intenta nuevamente.</p>';
                break;
            case 'pending':
                echo '<h1>Pago pendiente</h1><p>Tu pago está siendo procesado. Te notificaremos cuando se complete.</p>';
                break;
        }
        exit;
    }
} );
