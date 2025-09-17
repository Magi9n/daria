<?php
/**
 * Payment Failure Page
 */

// Obtener el order_id de la URL
$order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';

if ($order_id) {
    // Obtener datos de la orden
    $order_data = get_option('tutor_order_' . $order_id);
    
    if ($order_data) {
        // Actualizar estado de la orden a fallida
        $order_data['status'] = 'failed';
        $order_data['failed_at'] = current_time('mysql');
        update_option('tutor_order_' . $order_id, $order_data);
        
        // Registrar en logs
        error_log('[PAYMENT FAILURE] Orden fallida: ' . $order_id);
        error_log('[PAYMENT FAILURE] Datos: ' . print_r($order_data, true));
    }
}

get_header();
?>

<div class="container">
    <div class="payment-result-page">
        <div class="payment-failure">
            <h1>Pago No Completado</h1>
            <div class="failure-icon">❌</div>
            
            <?php if ($order_id && $order_data): ?>
                <p>Hubo un problema al procesar tu pago. No te preocupes, no se ha realizado ningún cargo.</p>
                <div class="order-details">
                    <h3>Detalles de la Orden</h3>
                    <p><strong>ID de Orden:</strong> <?php echo esc_html($order_id); ?></p>
                    <p><strong>Curso:</strong> <?php echo esc_html(get_the_title($order_data['course_id'])); ?></p>
                    <p><strong>Monto:</strong> $<?php echo esc_html($order_data['amount']); ?> <?php echo esc_html($order_data['currency']); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo esc_html(ucfirst($order_data['payment_method'])); ?></p>
                </div>
                
                <div class="retry-options">
                    <h3>¿Qué puedes hacer?</h3>
                    <ul>
                        <li>Verificar que tu información de pago sea correcta</li>
                        <li>Asegurarte de tener fondos suficientes</li>
                        <li>Intentar con otro método de pago</li>
                        <li>Contactar a tu banco si el problema persiste</li>
                    </ul>
                    
                    <a href="<?php echo home_url('/checkout/?course_id=' . $order_data['course_id']); ?>" class="btn btn-primary">
                        Intentar Nuevamente
                    </a>
                </div>
            <?php else: ?>
                <p>Hubo un problema al procesar tu pago. Por favor intenta nuevamente.</p>
                <a href="<?php echo home_url('/courses/'); ?>" class="btn btn-primary">
                    Ver Cursos
                </a>
            <?php endif; ?>
            
            <div class="support-info">
                <p>Si continúas teniendo problemas, <a href="<?php echo home_url('/contact/'); ?>">contáctanos</a> y te ayudaremos.</p>
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

.payment-failure h1 {
    color: #dc3545;
    margin-bottom: 20px;
}

.failure-icon {
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

.retry-options {
    margin: 30px 0;
}

.retry-options ul {
    text-align: left;
    max-width: 400px;
    margin: 0 auto 20px;
}

.retry-options li {
    margin: 8px 0;
    color: #666;
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
    background: #dc3545;
}

.btn-primary:hover {
    background: #c82333;
}

.support-info {
    margin-top: 30px;
    font-size: 14px;
    color: #666;
}
</style>

<?php get_footer(); ?>
