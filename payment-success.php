<?php
/**
 * Payment Success Page
 */

// Obtener el order_id de la URL
$order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';

if ($order_id) {
    // Obtener datos de la orden
    $order_data = get_option('tutor_order_' . $order_id);
    
    if ($order_data) {
        // Actualizar estado de la orden a completada
        $order_data['status'] = 'completed';
        $order_data['completed_at'] = current_time('mysql');
        update_option('tutor_order_' . $order_id, $order_data);
        
        // Registrar en logs
        error_log('[PAYMENT SUCCESS] Orden completada: ' . $order_id);
        error_log('[PAYMENT SUCCESS] Datos: ' . print_r($order_data, true));
        
        // Aquí se podría enrollar al usuario en el curso
        if (function_exists('tutor_utils')) {
            $course_id = $order_data['course_id'];
            $user_id = $order_data['user_id'];
            
            // Enrollar usuario en el curso
            tutor_utils()->do_enroll($course_id, $order_id, $user_id);
            error_log('[PAYMENT SUCCESS] Usuario ' . $user_id . ' enrollado en curso ' . $course_id);
        }
    }
}

get_header();
?>

<div class="container">
    <div class="payment-result-page">
        <div class="payment-success">
            <h1>¡Pago Exitoso!</h1>
            <div class="success-icon">✅</div>
            
            <?php if ($order_id && $order_data): ?>
                <p>Tu pago ha sido procesado exitosamente.</p>
                <div class="order-details">
                    <h3>Detalles de la Orden</h3>
                    <p><strong>ID de Orden:</strong> <?php echo esc_html($order_id); ?></p>
                    <p><strong>Curso:</strong> <?php echo esc_html(get_the_title($order_data['course_id'])); ?></p>
                    <p><strong>Monto:</strong> $<?php echo esc_html($order_data['amount']); ?> <?php echo esc_html($order_data['currency']); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo esc_html(ucfirst($order_data['payment_method'])); ?></p>
                </div>
                
                <div class="next-steps">
                    <h3>Próximos Pasos</h3>
                    <p>Ya puedes acceder a tu curso. Haz clic en el botón de abajo para comenzar.</p>
                    <a href="<?php echo get_permalink($order_data['course_id']); ?>" class="btn btn-primary">
                        Ir al Curso
                    </a>
                </div>
            <?php else: ?>
                <p>Tu pago ha sido procesado exitosamente.</p>
                <a href="<?php echo home_url('/courses/'); ?>" class="btn btn-primary">
                    Ver Mis Cursos
                </a>
            <?php endif; ?>
            
            <div class="support-info">
                <p>Si tienes alguna pregunta, no dudes en <a href="<?php echo home_url('/contact/'); ?>">contactarnos</a>.</p>
            </div>
        </div>
    </div>
</div>

<style>
.payment-result-page {
    max-width: 600px;
    margin: 50px auto;
    padding: 30px;
    text-align: center;
    background: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.payment-success h1 {
    color: #28a745;
    margin-bottom: 20px;
}

.success-icon {
    font-size: 60px;
    margin: 20px 0;
}

.order-details {
    background: white;
    padding: 20px;
    margin: 20px 0;
    border-radius: 5px;
    text-align: left;
}

.order-details h3 {
    margin-top: 0;
    color: #333;
}

.next-steps {
    margin: 30px 0;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    background: #007cba;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin: 10px;
}

.btn:hover {
    background: #005a87;
    color: white;
}

.btn-primary {
    background: #28a745;
}

.btn-primary:hover {
    background: #218838;
}

.support-info {
    margin-top: 30px;
    font-size: 14px;
    color: #666;
}
</style>

<?php get_footer(); ?>
