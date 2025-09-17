<?php
/**
 * Payment Pending Page
 */

// Obtener el order_id de la URL
$order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';

if ($order_id) {
    // Obtener datos de la orden
    $order_data = get_option('tutor_order_' . $order_id);
    
    if ($order_data) {
        // Mantener estado pendiente
        $order_data['status'] = 'pending';
        $order_data['pending_at'] = current_time('mysql');
        update_option('tutor_order_' . $order_id, $order_data);
        
        // Registrar en logs
        error_log('[PAYMENT PENDING] Orden pendiente: ' . $order_id);
        error_log('[PAYMENT PENDING] Datos: ' . print_r($order_data, true));
    }
}

get_header();
?>

<div class="container">
    <div class="payment-result-page">
        <div class="payment-pending">
            <h1>Pago Pendiente</h1>
            <div class="pending-icon">⏳</div>
            
            <?php if ($order_id && $order_data): ?>
                <p>Tu pago está siendo procesado. Te notificaremos cuando se complete.</p>
                <div class="order-details">
                    <h3>Detalles de la Orden</h3>
                    <p><strong>ID de Orden:</strong> <?php echo esc_html($order_id); ?></p>
                    <p><strong>Curso:</strong> <?php echo esc_html(get_the_title($order_data['course_id'])); ?></p>
                    <p><strong>Monto:</strong> $<?php echo esc_html($order_data['amount']); ?> <?php echo esc_html($order_data['currency']); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo esc_html(ucfirst($order_data['payment_method'])); ?></p>
                </div>
                
                <div class="pending-info">
                    <h3>¿Qué sigue?</h3>
                    <ul>
                        <li>El pago puede tardar unos minutos en procesarse</li>
                        <li>Recibirás un email de confirmación cuando se complete</li>
                        <li>Podrás acceder al curso una vez confirmado el pago</li>
                        <li>Si tienes dudas, puedes contactarnos</li>
                    </ul>
                </div>
            <?php else: ?>
                <p>Tu pago está siendo procesado. Te notificaremos cuando se complete.</p>
            <?php endif; ?>
            
            <div class="actions">
                <a href="<?php echo home_url('/'); ?>" class="btn btn-secondary">
                    Volver al Inicio
                </a>
                <a href="<?php echo home_url('/my-account/'); ?>" class="btn btn-primary">
                    Mi Cuenta
                </a>
            </div>
            
            <div class="support-info">
                <p>Si tienes alguna pregunta, <a href="<?php echo home_url('/contact/'); ?>">contáctanos</a>.</p>
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

.payment-pending h1 {
    color: #ffc107;
    margin-bottom: 20px;
}

.pending-icon {
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

.pending-info {
    margin: 30px 0;
}

.pending-info ul {
    text-align: left;
    max-width: 400px;
    margin: 0 auto 20px;
}

.pending-info li {
    margin: 8px 0;
    color: #666;
}

.actions {
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
    background: #007cba;
}

.btn-primary:hover {
    background: #005a87;
}

.btn-secondary {
    background: #6c757d;
}

.btn-secondary:hover {
    background: #545b62;
}

.support-info {
    margin-top: 30px;
    font-size: 14px;
    color: #666;
}
</style>

<?php get_footer(); ?>
