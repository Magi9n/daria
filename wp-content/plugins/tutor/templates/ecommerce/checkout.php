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
						<div class="tutor-checkout-billing-fields">
							<h5 class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-12">
								<?php esc_html_e( 'Billing Information', 'tutor' ); ?>
							</h5>
							
							<div class="tutor-row">
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'First Name', 'tutor' ); ?></label>
										<input type="text" name="billing_first_name" value="<?php echo esc_attr( $billing_info->billing_first_name ?? '' ); ?>" required>
									</div>
								</div>
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'Last Name', 'tutor' ); ?></label>
										<input type="text" name="billing_last_name" value="<?php echo esc_attr( $billing_info->billing_last_name ?? '' ); ?>" required>
									</div>
								</div>
							</div>
							
							<div class="tutor-form-group">
								<label><?php esc_html_e( 'Email', 'tutor' ); ?></label>
								<input type="email" name="billing_email" value="<?php echo esc_attr( $billing_info->billing_email ?? get_option( 'admin_email' ) ); ?>" required>
							</div>
							
							<div class="tutor-form-group">
								<label><?php esc_html_e( 'Phone', 'tutor' ); ?></label>
								<input type="text" name="billing_phone" value="<?php echo esc_attr( $billing_info->billing_phone ?? '' ); ?>">
							</div>
							
							<div class="tutor-row">
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'Country', 'tutor' ); ?></label>
										<select name="billing_country" id="billing_country" required>
											<?php
											$countries = WC()->countries->get_countries();
											$selected_country = $billing_info->billing_country ?? 'US';
											foreach ( $countries as $code => $name ) {
												echo '<option value="' . esc_attr( $code ) . '"' . selected( $selected_country, $code, false ) . '>' . esc_html( $name ) . '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'State', 'tutor' ); ?></label>
										<select name="billing_state" id="billing_state">
											<option value=""><?php esc_html_e( 'Select State', 'tutor' ); ?></option>
											<?php
											$states = WC()->countries->get_states( $selected_country );
											if ( $states ) {
												$selected_state = $billing_info->billing_state ?? '';
												foreach ( $states as $code => $name ) {
													echo '<option value="' . esc_attr( $code ) . '"' . selected( $selected_state, $code, false ) . '>' . esc_html( $name ) . '</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="tutor-form-group">
								<label><?php esc_html_e( 'Address', 'tutor' ); ?></label>
								<input type="text" name="billing_address_1" value="<?php echo esc_attr( $billing_info->billing_address_1 ?? '' ); ?>" required>
							</div>
							
							<div class="tutor-row">
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'City', 'tutor' ); ?></label>
										<input type="text" name="billing_city" value="<?php echo esc_attr( $billing_info->billing_city ?? '' ); ?>" required>
									</div>
								</div>
								<div class="tutor-col-md-6">
									<div class="tutor-form-group">
										<label><?php esc_html_e( 'Postal Code', 'tutor' ); ?></label>
										<input type="text" name="billing_postcode" value="<?php echo esc_attr( $billing_info->billing_postcode ?? '' ); ?>" required>
									</div>
								</div>
							</div>
						</div>
						
						<script>
						jQuery(document).ready(function($) {
							// Estados predefinidos para países comunes
							var countryStates = {
								'US': {
									'AL': 'Alabama', 'AK': 'Alaska', 'AZ': 'Arizona', 'AR': 'Arkansas', 'CA': 'California',
									'CO': 'Colorado', 'CT': 'Connecticut', 'DE': 'Delaware', 'FL': 'Florida', 'GA': 'Georgia',
									'HI': 'Hawaii', 'ID': 'Idaho', 'IL': 'Illinois', 'IN': 'Indiana', 'IA': 'Iowa',
									'KS': 'Kansas', 'KY': 'Kentucky', 'LA': 'Louisiana', 'ME': 'Maine', 'MD': 'Maryland',
									'MA': 'Massachusetts', 'MI': 'Michigan', 'MN': 'Minnesota', 'MS': 'Mississippi', 'MO': 'Missouri',
									'MT': 'Montana', 'NE': 'Nebraska', 'NV': 'Nevada', 'NH': 'New Hampshire', 'NJ': 'New Jersey',
									'NM': 'New Mexico', 'NY': 'New York', 'NC': 'North Carolina', 'ND': 'North Dakota', 'OH': 'Ohio',
									'OK': 'Oklahoma', 'OR': 'Oregon', 'PA': 'Pennsylvania', 'RI': 'Rhode Island', 'SC': 'South Carolina',
									'SD': 'South Dakota', 'TN': 'Tennessee', 'TX': 'Texas', 'UT': 'Utah', 'VT': 'Vermont',
									'VA': 'Virginia', 'WA': 'Washington', 'WV': 'West Virginia', 'WI': 'Wisconsin', 'WY': 'Wyoming'
								},
								'MX': {
									'AGU': 'Aguascalientes', 'BCN': 'Baja California', 'BCS': 'Baja California Sur',
									'CAM': 'Campeche', 'CHP': 'Chiapas', 'CHH': 'Chihuahua', 'COA': 'Coahuila',
									'COL': 'Colima', 'DIF': 'Ciudad de México', 'DUR': 'Durango', 'GUA': 'Guanajuato',
									'GRO': 'Guerrero', 'HID': 'Hidalgo', 'JAL': 'Jalisco', 'MEX': 'Estado de México',
									'MIC': 'Michoacán', 'MOR': 'Morelos', 'NAY': 'Nayarit', 'NLE': 'Nuevo León',
									'OAX': 'Oaxaca', 'PUE': 'Puebla', 'QUE': 'Querétaro', 'ROO': 'Quintana Roo',
									'SLP': 'San Luis Potosí', 'SIN': 'Sinaloa', 'SON': 'Sonora', 'TAB': 'Tabasco',
									'TAM': 'Tamaulipas', 'TLA': 'Tlaxcala', 'VER': 'Veracruz', 'YUC': 'Yucatán', 'ZAC': 'Zacatecas'
								},
								'PE': {
									'AMA': 'Amazonas', 'ANC': 'Áncash', 'APU': 'Apurímac', 'ARE': 'Arequipa',
									'AYA': 'Ayacucho', 'CAJ': 'Cajamarca', 'CAL': 'Callao', 'CUS': 'Cusco',
									'HUV': 'Huancavelica', 'HUC': 'Huánuco', 'ICA': 'Ica', 'JUN': 'Junín',
									'LAL': 'La Libertad', 'LAM': 'Lambayeque', 'LIM': 'Lima', 'LOR': 'Loreto',
									'MDD': 'Madre de Dios', 'MOQ': 'Moquegua', 'PAS': 'Pasco', 'PIU': 'Piura',
									'PUN': 'Puno', 'SAM': 'San Martín', 'TAC': 'Tacna', 'TUM': 'Tumbes', 'UCA': 'Ucayali'
								}
							};
							
							// Manejar cambio de país
							$('#billing_country').on('change', function() {
								var country = $(this).val();
								var $stateSelect = $('#billing_state');
								
								// Limpiar estados actuales
								$stateSelect.html('<option value=""><?php esc_html_e( "Select State", "tutor" ); ?></option>');
								
								// Cargar estados del país seleccionado
								if (countryStates[country]) {
									$.each(countryStates[country], function(code, name) {
										$stateSelect.append('<option value="' + code + '">' + name + '</option>');
									});
								} else {
									$stateSelect.html('<option value=""><?php esc_html_e( "No states available", "tutor" ); ?></option>');
								}
							});
						});
						</script>
						
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
						<button type="submit" <?php echo $enable_pay_now_btn ? '' : 'disabled'; ?>  id="tutor-checkout-pay-now-button" class="tutor-btn tutor-btn-primary tutor-btn-lg tutor-w-100 tutor-justify-center tutor-mt-16">
							<?php echo esc_html( $pay_now_btn_text ); ?>
						</button>
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
