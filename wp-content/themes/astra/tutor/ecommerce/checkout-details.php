<?php
/**
 * Checkout Details Override - Custom Cart-Like Design
 *
 * This template overrides Tutor LMS checkout details to use the same
 * visual layout as the custom WooCommerce cart design, while preserving
 * Tutor LMS logic and data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TUTOR\Input;
use Tutor\Ecommerce\Tax;
use Tutor\Models\OrderModel;
use Tutor\Ecommerce\Settings;
use Tutor\Models\CouponModel;
use Tutor\Ecommerce\CartController;
use Tutor\Ecommerce\CheckoutController;

// Keep Tutor LMS logic intact (copied from original template)
$user_id = apply_filters( 'tutor_checkout_user_id', get_current_user_id() );

$coupon_model        = new CouponModel();
$cart_controller     = new CartController( false );
$checkout_controller = new CheckoutController( false );
$get_cart            = $cart_controller->get_cart_items();
$courses             = $get_cart['courses'];
$total_count         = $courses['total_count'];
$course_id           = (int) Input::sanitize_request_data( 'course_id', 0 );
$course_list         = Settings::is_buy_now_enabled() && $course_id ? array( get_post( $course_id ) ) : $courses['results'];

$plan_id       = (int) Input::sanitize_request_data( 'plan' );
$plan_info     = apply_filters( 'tutor_get_plan_info', null, $plan_id );
$has_plan_info = $plan_id && $plan_info;

// Contains Course/Bundle/Plan ids.
$object_ids = array();
$item_ids   = $has_plan_info ? array( $plan_info->id ) : array_column( $course_list, 'ID' );
$order_type = $has_plan_info ? OrderModel::TYPE_SUBSCRIPTION : OrderModel::TYPE_SINGLE_ORDER;

$coupon_code            = apply_filters( 'tutor_checkout_coupon_code', Input::sanitize_request_data( 'coupon_code', '' ), $order_type, $item_ids );
$has_manual_coupon_code = ! empty( $coupon_code );

$should_calculate_tax     = Tax::should_calculate_tax();
$is_tax_included_in_price = Tax::is_tax_included_in_price();
$tax_rate                 = Tax::get_user_tax_rate( $user_id );

$checkout_data   = $checkout_controller->prepare_checkout_items( $item_ids, $order_type, $coupon_code );
$show_coupon_box = Settings::is_coupon_usage_enabled() && ! $checkout_data->is_coupon_applied;
?>

<!-- Custom styles replicated from cart design -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* Fondo blanco para toda la página de checkout */
.tutor-checkout-page,
body.tutor-lms,
body.page,
body {
	background: #ffffff !important;
}

.custom-cart-container {
	font-family: 'Poppins', sans-serif !important;
	max-width: 95% !important;
	margin: 20px auto !important;
	background: #ffffff !important;
	border-radius: 0 !important;
	overflow: hidden;
	box-shadow: none !important;
	border: none !important;
	padding: 0 20px !important;
}

/* Espaciado adicional solo en desktop */
@media (min-width: 768px) {
	.custom-cart-container {
		margin: 40px auto !important;
		padding: 20px 20px !important;
	}
}

.cart-header {
	background-color: #ffffff !important;
	color: white !important;
	padding: 8px 25px !important;
	font-weight: 300 !important;
	font-size: 24px !important;
	font-family: 'Poppins', sans-serif !important;
	border-radius: 15px 15px 0 0 !important;
	margin-bottom: 0 !important;
	position: relative !important;
	overflow: hidden !important;
}

.cart-header::before {
	content: '' !important;
	position: absolute !important;
	top: 50% !important;
	left: 50% !important;
	width: 100% !important;
	height: 80% !important;
	background: #592D36 !important;
	border-radius: 9999px !important;
	transform: translate(-50%, -50%) !important;
	z-index: 0 !important;
}

.cart-header-text {
	position: relative !important;
	z-index: 1 !important;
	display: inline-block !important;
	color: #ffffff !important;
}

.cart-item {
	background: white !important;
	margin: 0 !important;
	padding: 25px 25px !important;
	border-bottom: 1px solid #f0f0f0 !important;
	display: flex !important;
	align-items: center !important;
	justify-content: space-between !important;
	position: relative;
	min-height: 80px !important;
}

.cart-item:last-child { border-bottom: none !important; }

.product-info { display: flex !important; align-items: center !important; flex: 1 !important; justify-content: flex-start !important; text-align: left !important; }
.product-name-text { font-weight: 300 !important; color: #000000 !important; margin: 0 !important; font-size: 16px !important; font-family: 'Poppins', sans-serif !important; text-align: left !important; }
.plan-type { font-weight: bold !important; color: #322828 !important; font-size: 16px !important; }
.product-price { font-weight: bold !important; color: #322828 !important; font-size: 16px !important; margin: 0 !important; text-align: center !important; flex: 1 !important; font-family: 'Poppins', sans-serif !important; }
.course-link { display: flex !important; align-items: center !important; gap: 8px !important; color: #333 !important; text-decoration: none !important; font-family: 'Poppins', sans-serif !important; font-weight: 500 !important; font-size: 14px !important; position: relative; flex: 1 !important; justify-content: flex-end !important; text-align: right !important; transition: all 0.3s ease !important; transform: translateX(0) !important; }
.course-link:hover { transform: translateX(-5px) !important; color: #592D36 !important; }
.course-link::after { content: '' !important; position: absolute !important; bottom: -2px !important; left: 350px !important; right: 0 !important; height: 1px !important; background-color: #333 !important; }
.course-link:hover::after { background-color: #592D36 !important; }
.chevron-icon { width: 20px !important; height: 20px !important; border: 1px solid #333 !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 12px !important; }
.course-link:hover .chevron-icon { border-color: #592D36 !important; color: #592D36 !important; }
.hidden-cart-data { display: none !important; }
</style>

<div class="tutor-checkout-details">
	<?php do_action( 'tutor_before_checkout_order_details', $course_list ); ?>

	<div class="tutor-checkout-details-inner">
		<!-- Reemplazo del título y lista por el diseño del carrito -->
		<div class="custom-cart-container">
			<div class="cart-header">
				<span class="cart-header-text">Detalles de tu Compra</span>
			</div>

			<?php
			if ( $plan_info ) {
				// Si hubiera planes de suscripción, mostramos un único item con el título del plan
				array_push( $object_ids, $plan_info->id );
				?>
				<div class="cart-item">
					<div class="product-info">
						<p class="product-name-text">
							Sé tu propia maquillista - 
							<span class="plan-type"><?php echo esc_html( $plan_info->title ?? __( 'Plan', 'tutor' ) ); ?></span>
						</p>
					</div>
					<div class="product-price">
						<?php tutor_print_formatted_price( $checkout_data->total_price ); ?> MXN
					</div>
					<a href="#" class="course-link">Ir al curso <span class="chevron-icon">›</span></a>
					<div class="hidden-cart-data"></div>
				</div>
				<?php
			} else {
				if ( is_array( $checkout_data->items ) && count( $checkout_data->items ) ) :
					foreach ( $checkout_data->items as $item ) :
						$course = get_post( $item->item_id );
						$permalink = $course ? get_the_permalink( $course ) : '#';
						array_push( $object_ids, $item->item_id );
						?>
						<div class="cart-item">
							<div class="product-info">
								<p class="product-name-text">
									Sé tu propia maquillista - 
									<span class="plan-type"><?php echo esc_html( $course ? $course->post_title : ( $item->item_name ?? '' ) ); ?></span>
								</p>
							</div>
							<div class="product-price"><?php tutor_print_formatted_price( $item->display_price ); ?> MXN</div>
							<a href="<?php echo esc_url( $permalink ); ?>" class="course-link">Ir al curso <span class="chevron-icon">›</span></a>
							<div class="hidden-cart-data"></div>
						</div>
						<?php
					endforeach;
				endif;
			}
			?>
		</div>

		<!-- Conservamos el bloque de Resumen, Cupones e Impuestos de Tutor LMS -->
		<div class="tutor-checkout-detail-item tutor-checkout-summary">
			<div class="tutor-checkout-summary-item">
				<div class="tutor-fw-medium"><?php esc_html_e( 'Subtotal', 'tutor' ); ?></div>
				<div class="tutor-fw-bold"><?php tutor_print_formatted_price( $checkout_data->subtotal_price ); ?></div>
			</div>

			<?php if ( $checkout_data->sale_discount > 0 ) : ?>
			<div class="tutor-checkout-summary-item">
				<div>
					<?php
						$sale_discount_label = apply_filters( 'tutor_checkout_sale_discount_label', __( 'Sale discount', 'tutor' ) );
						echo esc_html( $sale_discount_label );
					?>
				</div>
				<div class="tutor-fw-bold">- <?php tutor_print_formatted_price( $checkout_data->sale_discount ); ?></div>
			</div>
			<?php endif; ?>

			<?php if ( $show_coupon_box ) : ?>
				<div class="tutor-checkout-summary-item tutor-have-a-coupon">
					<div><?php esc_html_e( 'Have a coupon?', 'tutor' ); ?></div>
					<button type="button" id="tutor-toggle-coupon-button" class="tutor-btn tutor-btn-link"><?php esc_html_e( 'Click here', 'tutor' ); ?></button>
				</div>
				<div class="tutor-apply-coupon-form tutor-d-none">
					<input type="text" name="coupon_code" <?php if ( 'manual' === $checkout_data->coupon_type && $checkout_data->is_coupon_applied ) : ?> value="<?php echo esc_attr( $coupon_code ); ?>" <?php endif; ?> placeholder="<?php esc_attr_e( 'Add coupon code', 'tutor' ); ?>">
					<button type="button" id="tutor-apply-coupon-button" class="tutor-btn tutor-btn-secondary" data-object-ids="<?php echo esc_attr( implode( ',', $object_ids ) ); ?>"><?php esc_html_e( 'Apply', 'tutor' ); ?></button>
				</div>
			<?php endif; ?>

			<div class="tutor-checkout-summary-item tutor-checkout-coupon-wrapper <?php echo esc_attr( $checkout_data->is_coupon_applied ? '' : 'tutor-d-none' ); ?>">
				<div class="tutor-checkout-coupon-badge tutor-has-delete-button">
					<i class="tutor-icon-tag" area-hidden="true"></i>
					<span><?php echo esc_html( $checkout_data->coupon_title ); ?></span>
					<?php if ( $checkout_data->is_coupon_applied ) : ?>
					<button type="button" id="tutor-checkout-remove-coupon" class="tutor-btn"><i class="tutor-icon-times" area-hidden="true"></i></button>
					<?php endif; ?>
				</div>
				<div class="tutor-fw-bold tutor-discount-amount">-<?php tutor_print_formatted_price( $checkout_data->coupon_discount ); ?></div>
			</div>

			<?php if ( $checkout_data->tax_amount > 0 && ! $is_tax_included_in_price ) : ?>
			<div class="tutor-checkout-summary-item tutor-checkout-tax-amount">
				<div><?php esc_html_e( 'Tax', 'tutor' ); ?></div>
				<div class="tutor-fw-bold"><?php tutor_print_formatted_price( $checkout_data->tax_amount ); ?></div>
			</div>
			<?php endif; ?>
		</div>

		<div class="tutor-pt-12 tutor-pb-20">
			<div class="tutor-checkout-summary-item">
				<div class="tutor-fw-medium"><?php esc_html_e( 'Grand Total', 'tutor' ); ?></div>
				<div class="tutor-fs-5 tutor-fw-bold tutor-checkout-grand-total"><?php tutor_print_formatted_price( $checkout_data->total_price ); ?></div>
			</div>
			<div class="tutor-checkout-summary-item tutor-checkout-incl-tax-label">
				<div></div>
				<?php if ( $checkout_data->tax_amount > 0 && $is_tax_included_in_price ) : ?>
				<div class="tutor-fs-7 tutor-color-muted">
					<?php echo esc_html( sprintf( __( '(Incl. Tax %s)', 'tutor' ), tutor_get_formatted_price( $checkout_data->tax_amount ) ) ); ?>
				</div>
				<?php endif; ?>
			</div>

			<input type="hidden" name="coupon_code" value="<?php echo esc_attr( $coupon_code ); ?>">
			<input type="hidden" name="object_ids" value="<?php echo esc_attr( implode( ',', $object_ids ) ); ?>">
			<input type="hidden" name="order_type" value="<?php echo esc_attr( $order_type ); ?>">
		</div>

		<input type="hidden" id="checkout_data" value="<?php echo esc_attr( wp_json_encode( $checkout_data ) ); ?>">
	</div>
</div>
