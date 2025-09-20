<?php
/**
 * Checkout Template.
 *
 * @package Tutor\Views
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Tutor\Ecommerce\CheckoutController;
use Tutor\Ecommerce\CartController;
use TUTOR\Input;

$user_id = apply_filters( 'tutor_checkout_user_id', get_current_user_id() );

$tutor_toc_page_link     = tutor_utils()->get_toc_page_link();
$tutor_privacy_page_link = tutor_utils()->get_privacy_page_link();

$cart_controller = new CartController();
$get_cart        = $cart_controller->get_cart_items();
$courses         = $get_cart['courses'];
$total_count     = $courses['total_count'];
$course_list     = $courses['results'];
$subtotal        = 0;
$course_ids      = implode( ', ', array_values( array_column( $course_list, 'ID' ) ) );
$plan_id         = Input::get( 'plan', 0, Input::TYPE_INT );

$is_checkout_page = true;

?>
<div class="tutor-checkout-page">
<div class="tutor-container">
<div class="tutor-checkout-container">
	<?php
	$echo_before_return    = true;
	$user_has_subscription = apply_filters( 'tutor_checkout_user_has_subscription', false, $plan_id, $echo_before_return );
	if ( $user_has_subscription ) {
		return;
	}
	?>

	<form method="post" id="tutor-checkout-form">
		<?php tutor_nonce_field(); ?>
		<input type="hidden" name="tutor_action" value="tutor_pay_now">
		<div class="tutor-row tutor-g-5">
			<div class="tutor-col-md-6" tutor-checkout-details>
				<?php
				$file = __DIR__ . '/checkout-details.php';
				if ( file_exists( $file ) ) {
					include $file;
				}
				?>
			</div>
			<div class="tutor-col-md-6">
				<div class="tutor-checkout-billing">
					<div class="tutor-checkout-billing-inner">
					<?php
					if ( ! is_user_logged_in() ) {
						$login_url = tutor_utils()->get_option( 'enable_tutor_native_login', null ) ? '' : wp_login_url( tutor()->current_url );
						?>
							<div class="tutor-mb-32 tutor-d-flex tutor-align-center tutor-justify-between tutor-border tutor-radius-6 tutor-p-12">
								<p class="tutor-mb-0"><?php esc_html_e( 'Already have an account?', 'tutor' ); ?></p>
								<button type="button" class="tutor-btn tutor-btn-secondary tutor-btn-sm tutor-open-login-modal" data-login_url="<?php echo esc_url( $login_url ); ?>">
								<?php esc_html_e( 'Login', 'tutor' ); ?>
								</button>
							</div>
					<?php } ?>

						<!-- Formulario de facturación restaurado pero con manejo de errores AJAX -->
						<div class="billing-address-container">
						<h2 class="billing-title">Dirección de facturación</h2>

						<!-- Campos visibles -->
						<div class="billing-row">
							<div class="billing-field">
								<input type="text" name="billing_first_name" class="billing-input" placeholder="First Name" required>
							</div>
							<div class="billing-field">
								<input type="text" name="billing_last_name" class="billing-input" placeholder="Last Name" required>
							</div>
						</div>

						<div class="billing-field">
							<input type="email" name="billing_email" class="billing-input" placeholder="Email Address" value="<?php echo is_user_logged_in() ? wp_get_current_user()->user_email : ''; ?>" required>
						</div>

						<div class="billing-field">
							<input type="tel" name="billing_phone" class="billing-input" placeholder="Phone">
						</div>

						<div class="billing-field">
							<input type="text" name="billing_address_1" class="billing-input" placeholder="Address" required>
						</div>

						<!-- Campos ocultos con valores por defecto -->
						<input type="hidden" name="billing_country" value="MX">
						<input type="hidden" name="billing_state" value="MEX">
						<input type="hidden" name="billing_city" value="Ciudad de México">
						<input type="hidden" name="billing_postcode" value="00000">

						<style>
						.billing-address-container {
							background: ##FFFFFF00;
							padding: 24px;
							border-radius: 8px;
							margin-bottom: 24px;
							font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
						}

						.billing-title {
							color: #8B7355;
							font-size: 20px;
							font-weight: 400;
							margin: 0 0 20px 0;
							letter-spacing: 0.5px;
						}

						.billing-row {
							display: flex;
							gap: 12px;
							margin-bottom: 12px;
						}

						.billing-field {
							flex: 1;
							margin-bottom: 12px;
						}

						.billing-input {
							width: 100%;
							padding: 12px 16px;
							border: 1px solid #d1d5db;
							border-radius: 6px;
							font-size: 14px;
							color: #6b7280;
							background: white;
							transition: all 0.2s ease;
							box-sizing: border-box;
						}

						.billing-input:focus {
							outline: none;
							border-color: #8B7355;
							box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
						}

						.billing-input::placeholder {
							color: #9ca3af;
							font-weight: 300;
						}

						@media (max-width: 768px) {
							.billing-row {
								flex-direction: column;
								gap: 0;
							}
						}
						</style>
					</div>	
						<div class="tutor-payment-method-wrapper tutor-mt-20 <?php echo esc_attr( $show_payment_methods ? '' : 'tutor-d-none' ); ?>">
							<h5 class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-12">
								<?php esc_html_e( 'Payment Method', 'tutor' ); ?>
							</h5>
							<div class="tutor-checkout-payment-options tutor-mb-24">
								<input type="hidden" name="payment_type">

								<?php if ( ! $show_payment_methods ) : ?>
									<input type="hidden" name="payment_method" value="free" id="tutor-temp-payment-method">
								<?php endif; ?>

								<?php
								// Configurar solo las pasarelas específicas: Mercado Pago y PayPal
								$allowed_gateways = array('woo-mercado-pago-basic', 'ppcp-gateway');
								$supported_gateways = array();

								// Intentar obtener las pasarelas de WooCommerce
								if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
									try {
										$wc_gateways = WC()->payment_gateways()->get_available_payment_gateways();
										
										// Filtrar solo las pasarelas permitidas
										foreach ( $allowed_gateways as $gateway_id ) {
											if ( isset( $wc_gateways[$gateway_id] ) && $wc_gateways[$gateway_id]->is_available() ) {
												$gateway = $wc_gateways[$gateway_id];
												$supported_gateways[] = array(
													'name' => $gateway->id,
													'label' => $gateway->get_title(),
													'icon' => $gateway->get_icon(),
													'is_manual' => false
												);
											}
										}
										
										error_log('Pasarelas configuradas: ' . implode(', ', array_column($supported_gateways, 'name')));
									} catch (Exception $e) {
										error_log('Error al obtener pasarelas de pago: ' . $e->getMessage());
									}
								}

								// Si no hay pasarelas disponibles, mostrar mensaje
								if ( empty( $supported_gateways ) ) {
									error_log('No se encontraron pasarelas de pago disponibles');
								}
								?>
								<?php if ( empty( $supported_gateways ) ) : ?>
								<div class="tutor-alert tutor-warning">
									<?php esc_html_e( 'No payment method found. Please contact the site administrator.', 'tutor' ); ?>
								</div>
							<?php else : ?>
								<?php 
								// Mostrar las pasarelas configuradas (siempre en formato de array)
								foreach ($supported_gateways as $gateway) {
									if (!is_array($gateway)) continue;
									
									$name = $gateway['name'] ?? '';
									$label = $gateway['label'] ?? $name;
									$icon = $gateway['icon'] ?? '';
									$is_manual = $gateway['is_manual'] ?? false;
									?>
									<label class="tutor-checkout-payment-item" data-payment-type="<?php echo $is_manual ? 'manual' : 'automate'; ?>">
										<input type="radio" name="payment_method" value="<?php echo esc_attr($name); ?>" class="tutor-form-check-input">
										<div class="tutor-payment-item-content">
											<?php echo $icon; // El ícono ya está procesado por WooCommerce ?>
											<?php echo esc_html($label); ?>
										</div>
										<?php if ($is_manual && !empty($gateway['payment_instructions'])) : ?>
											<div class="tutor-d-none tutor-payment-item-instructions"><?php echo wp_kses_post($gateway['payment_instructions']); ?></div>
										<?php endif; ?>
									</label>
									<?php
								}
								?>
							<?php endif; ?>
							</div>

							<div class="tutor-payment-instructions tutor-mb-20 tutor-d-none"></div>
						</div>
						<?php if ( null !== $tutor_toc_page_link ) : ?>
							<div class="tutor-mt-20">
								<div class="tutor-form-check tutor-d-flex">
									<input type="checkbox" id="tutor_checkout_agree_to_terms" name="agree_to_terms" class="tutor-form-check-input" required>
									<label for="tutor_checkout_agree_to_terms">
										<span class="tutor-color-subdued tutor-fw-normal">
											<?php esc_html_e( 'I agree with the website\'s', 'tutor' ); ?> 
											<a target="_blank" href="<?php echo esc_url( $tutor_toc_page_link ); ?>" class="tutor-color-primary"><?php esc_html_e( 'Terms of Use', 'tutor' ); ?></a> 
											<?php if ( null !== $tutor_privacy_page_link ) : ?>
												<?php esc_html_e( 'and', 'tutor' ); ?> 
												<a target="_blank" href="<?php echo esc_url( $tutor_privacy_page_link ); ?>" class="tutor-color-primary"><?php esc_html_e( 'Privacy Policy', 'tutor' ); ?></a>
											<?php endif; ?>
										</span>
									</label>
								</div>
							</div>
						<?php endif; ?>
						<!-- handle errors -->
						<?php
						$pay_now_errors    = get_transient( CheckoutController::PAY_NOW_ERROR_TRANSIENT_KEY . $user_id );
						$pay_now_alert_msg = get_transient( CheckoutController::PAY_NOW_ALERT_MSG_TRANSIENT_KEY . $user_id );

						delete_transient( CheckoutController::PAY_NOW_ALERT_MSG_TRANSIENT_KEY . $user_id );
						delete_transient( CheckoutController::PAY_NOW_ERROR_TRANSIENT_KEY . $user_id );
						if ( $pay_now_errors || $pay_now_alert_msg ) :
							?>
						<div class="tutor-break-word tutor-mt-16">
							<?php
							if ( ! empty( $pay_now_alert_msg ) ) :
								list( $alert, $message ) = array_values( $pay_now_alert_msg );
								?>
								<div class="tutor-alert tutor-<?php echo esc_attr( $alert ); ?>">
									<div class="tutor-color-<?php echo esc_attr( $alert ); ?>"><?php echo esc_html( $message ); ?></div>
								</div>
							<?php endif; ?>

							<?php if ( is_array( $pay_now_errors ) && count( $pay_now_errors ) ) : ?>
							<div class="tutor-alert tutor-danger">
								<ul class="tutor-mb-0">
									<?php foreach ( $pay_now_errors as $pay_now_err ) : ?>
										<li class="tutor-color-danger"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $pay_now_err ) ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<!-- handle errors end -->
						<?php $enable_pay_now_btn = apply_filters( 'tutor_checkout_enable_pay_now_btn', true, $checkout_data ); ?>
						<button type="button" <?php echo $enable_pay_now_btn ? '' : 'disabled'; ?>  id="tutor-checkout-pay-now-button" class="tutor-btn tutor-btn-primary tutor-btn-lg tutor-w-100 tutor-justify-center tutor-mt-16" onclick="processDirectPayment()">
							<?php echo esc_html( $pay_now_btn_text ); ?>
						</button>
						
						<script>
						function processDirectPayment() {
							console.log('DIRECT PAYMENT: Iniciando proceso de pago directo');
							
							// Obtener datos del formulario
							var formData = new FormData(document.getElementById('tutor-checkout-form'));
							var paymentMethod = document.querySelector('input[name="payment_method"]:checked');
							
							if (!paymentMethod) {
								alert('Por favor selecciona un método de pago');
								return;
							}
							
							console.log('DIRECT PAYMENT: Método seleccionado:', paymentMethod.value);
							
							// Validar campos requeridos
							var requiredFields = ['billing_first_name', 'billing_last_name', 'billing_email', 'billing_country', 'billing_address_1', 'billing_city'];
							var missingFields = [];
							
							requiredFields.forEach(function(field) {
								var input = document.querySelector('[name="' + field + '"]');
								if (!input || !input.value.trim()) {
									missingFields.push(field);
								}
							});
							
							if (missingFields.length > 0) {
								alert('Por favor completa todos los campos requeridos: ' + missingFields.join(', '));
								return;
							}
							
							// Deshabilitar botón para evitar doble click
							var button = document.getElementById('tutor-checkout-pay-now-button');
							button.disabled = true;
							button.innerHTML = 'Procesando...';
							
							// Procesar según el método de pago
							if (paymentMethod.value === 'woo-mercado-pago-basic') {
								processMercadoPago(formData);
							} else if (paymentMethod.value === 'ppcp-gateway') {
								processPayPal(formData);
							} else {
								alert('Método de pago no soportado');
								button.disabled = false;
								button.innerHTML = '<?php echo esc_js( $pay_now_btn_text ); ?>';
							}
						}
						
						function processMercadoPago(formData) {
							console.log('DIRECT PAYMENT: Procesando Mercado Pago');
							
							// Crear preferencia de Mercado Pago
							var courseId = formData.get('object_ids');
							var billingData = {
								first_name: formData.get('billing_first_name'),
								last_name: formData.get('billing_last_name'),
								email: formData.get('billing_email'),
								phone: formData.get('billing_phone') || '',
								country: formData.get('billing_country'),
								address: formData.get('billing_address_1'),
								city: formData.get('billing_city'),
								state: formData.get('billing_state') || '',
								postcode: formData.get('billing_postcode') || ''
							};
							
							// Enviar datos al backend para crear preferencia
							jQuery.ajax({
								url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
								type: 'POST',
								data: {
									action: 'create_mercadopago_preference',
									course_id: courseId,
									billing_data: billingData,
									nonce: '<?php echo wp_create_nonce( "mercadopago_nonce" ); ?>'
								},
								success: function(response) {
									console.log('DIRECT PAYMENT: Respuesta MP:', response);
									if (response.success && response.data.init_point) {
										window.location.href = response.data.init_point;
									} else {
										alert('Error al procesar el pago con Mercado Pago');
										resetButton();
									}
								},
								error: function() {
									alert('Error de conexión con Mercado Pago');
									resetButton();
								}
							});
						}
						
						function processPayPal(formData) {
							console.log('DIRECT PAYMENT: Procesando PayPal');
							
							var courseId = formData.get('object_ids');
							var billingData = {
								first_name: formData.get('billing_first_name'),
								last_name: formData.get('billing_last_name'),
								email: formData.get('billing_email'),
								phone: formData.get('billing_phone') || '',
								country: formData.get('billing_country'),
								address: formData.get('billing_address_1'),
								city: formData.get('billing_city'),
								state: formData.get('billing_state') || '',
								postcode: formData.get('billing_postcode') || ''
							};
							
							// Enviar datos al backend para crear orden PayPal
							jQuery.ajax({
								url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
								type: 'POST',
								data: {
									action: 'create_paypal_order',
									course_id: courseId,
									billing_data: billingData,
									nonce: '<?php echo wp_create_nonce( "paypal_nonce" ); ?>'
								},
								success: function(response) {
									console.log('DIRECT PAYMENT: Respuesta PayPal:', response);
									if (response.success && response.data.approval_url) {
										window.location.href = response.data.approval_url;
									} else {
										alert('Error al procesar el pago con PayPal');
										resetButton();
									}
								},
								error: function() {
									alert('Error de conexión con PayPal');
									resetButton();
								}
							});
						}
						
						function resetButton() {
							var button = document.getElementById('tutor-checkout-pay-now-button');
							button.disabled = false;
							button.innerHTML = '<?php echo esc_js( $pay_now_btn_text ); ?>';
						}
						</script>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</div>
</div>
<?php
if ( ! is_user_logged_in() ) {
	tutor_load_template_from_custom_path( tutor()->path . '/views/modal/login.php' );
}
?>
