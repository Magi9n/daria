<?php
/**
 * Checkout info template
 *
 * @package Tutor\Views
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 3.0.0
 */

use TUTOR\Input;
use Tutor\Ecommerce\Tax;
use Tutor\Models\OrderModel;
use Tutor\Ecommerce\Settings;
use Tutor\Models\CouponModel;
use Tutor\Ecommerce\CartController;
use Tutor\Ecommerce\CheckoutController;

/**
 * User ID is required.
 * Renders this view only (excluding checkout.php) when the country or state changes via AJAX.
 */
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
<!-- Estilos CSS para el nuevo diseño del checkout -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body.tutor-page-checkout, html {
    background: #ffffff !important;
}

#page {
    background-color: #ffffff !important;
}

.tutor-checkout-page .tutor-checkout-container {
    max-width: 95% !important;
}

@media (min-width: 1400px) {
    .tutor-container-xxl, .tutor-container-xl, .tutor-container-lg, .tutor-container-md, .tutor-container-sm, .tutor-container {
        max-width: 98% !important;
    }
}

.tutor-checkout-detail-item.tutor-checkout-summary,
.tutor-pt-12.tutor-pb-20 {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

/* Desktop layout adjustments */
@media (min-width: 768px) {
    .tutor-col-md-6[tutor-checkout-details] {
        width: 60% !important;
    }
    
    .tutor-col-md-6:not([tutor-checkout-details]) {
        width: 40% !important;
    }
    
    .billing-address-container {
        margin-top: 10% !important;
    }
}

/* Billing Address Styling */
.billing-address-container {
    background-color: #F6F5F0 !important;
    border-radius: 25px !important;
    padding: 30px !important;
    margin: 20px 0 !important;
}

.billing-title {
    font-family: 'Times New Roman', serif !important;
    font-size: 30px !important;
    font-weight: 400 !important;
    letter-spacing: -3% !important;
    text-align: left !important;
    color: #592D36 !important;
    margin-bottom: 25px !important;
}

.tutor-form-group label {
    font-family: 'Poppins', sans-serif !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    letter-spacing: 2% !important;
    color: #592D36 !important;
    margin-bottom: 8px !important;
}

.tutor-form-control {
    border: 2px solid #592D36 !important;
    background-color: transparent !important;
    font-family: 'Poppins', sans-serif !important;
    font-weight: 300 !important;
    letter-spacing: 2% !important;
    color: #592D36 !important;
    border-radius: 25px !important;
    padding: 12px 20px !important;
}

.tutor-form-control::placeholder {
    color: #592D36 !important;
    opacity: 0.7 !important;
}

/* Payment Methods Styling */
.tutor-payment-method-wrapper {
    margin-top: 30px !important;
}

.tutor-payment-method-wrapper h5 {
    font-family: 'Poppins', sans-serif !important;
    font-size: 18px !important;
    color: #592D36 !important;
    text-align: center !important;
    margin-bottom: 20px !important;
}

.tutor-checkout-payment-options {
    display: flex !important;
    justify-content: center !important;
    gap: 20px !important;
    flex-wrap: wrap !important;
}

.tutor-checkout-payment-item {
    cursor: pointer !important;
    border: 2px solid #592D36 !important;
    border-radius: 15px !important;
    padding: 15px 25px !important;
    background-color: #F6F5F0 !important;
    transition: all 0.3s ease !important;
    min-width: 120px !important;
    text-align: center !important;
}

.tutor-checkout-payment-item:hover {
    background-color: #592D36 !important;
    color: white !important;
}

.tutor-checkout-payment-item input[type="radio"] {
    display: none !important;
}

.tutor-payment-item-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 8px !important;
    font-family: 'Poppins', sans-serif !important;
    font-weight: 600 !important;
    color: #592D36 !important;
}

.tutor-checkout-payment-item:hover .tutor-payment-item-content {
    color: white !important;
}

.tutor-payment-item-content img {
    width: 40px !important;
    height: 40px !important;
    object-fit: contain !important;
}

/* Hide Pay Now button */
#tutor-checkout-pay-now-button {
    display: none !important;
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

.cart-header > *,
.cart-header-text {
    position: relative !important;
    z-index: 1 !important;
    display: inline-block !important;
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

.cart-item:last-child {
    border-bottom: none !important;
}

.product-info {
    display: flex !important;
    align-items: center !important;
    flex: 1 !important;
    justify-content: flex-start !important;
    text-align: left !important;
}

.product-name-text {
    font-weight: 300 !important;
    color: #000000 !important;
    margin: 0 !important;
    font-size: 16px !important;
    font-family: 'Poppins', sans-serif !important;
    text-align: left !important;
}

.plan-type {
    font-weight: bold !important;
    color: #322828 !important;
    font-size: 16px !important;
}

.product-price {
    font-weight: bold !important;
    color: #322828 !important;
    font-size: 16px !important;
    margin: 0 !important;
    text-align: center !important;
    flex: 1 !important;
    font-family: 'Poppins', sans-serif !important;
}

.course-link {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    color: #333 !important;
    text-decoration: none !important;
    font-family: 'Poppins', sans-serif !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    position: relative;
    flex: 1 !important;
    justify-content: flex-end !important;
    text-align: right !important;
    transition: all 0.3s ease !important;
    transform: translateX(0) !important;
}

.course-link:hover {
    transform: translateX(-5px) !important;
    color: #592D36 !important;
}

.course-link::after {
    content: '' !important;
    position: absolute !important;
    bottom: -2px !important;
    left: 350px !important;
    right: 0 !important;
    height: 1px !important;
    background-color: #333 !important;
}

.course-link:hover::after {
    background-color: #592D36 !important;
}

.chevron-icon {
    width: 20px !important;
    height: 20px !important;
    border: 1px solid #333 !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 12px !important;
}

.course-link:hover .chevron-icon {
    border-color: #592D36 !important;
    color: #592D36 !important;
}
</style>
<?php
?>

<div class="tutor-checkout-details">

	<?php do_action( 'tutor_before_checkout_order_details', $course_list ); ?>

	<div class="tutor-checkout-details-inner">
		<div class="custom-cart-container">
			<div class="cart-header">
				<span class="cart-header-text">Detalles de tu Compra</span>
			</div>

			<?php
			foreach ( $checkout_data->items as $item ) :
				$course      = get_post( $item->item_id );
				$product_permalink = get_the_permalink( $course );
				array_push( $object_ids, $item->item_id );
				?>
				<div class="cart-item">
					<div class="product-info">
						<p class="product-name-text">
							Sé tu propia maquillista - 
							<span class="plan-type">
								<?php echo esc_html( $item->item_name ); ?>
							</span>
						</p>
					</div>

					<div class="product-price">
						<?php tutor_print_formatted_price( $item->display_price ); ?> MXN
					</div>

					<a href="<?php echo esc_url( $product_permalink ); ?>" class="course-link">
						Ir al curso
						<span class="chevron-icon">›</span>
					</a>
									</div>
			<?php endforeach; ?>
		</div>

		<div class="tutor-checkout-detail-item tutor-checkout-summary">
			<div class="tutor-checkout-summary-item">
				<div class="tutor-fw-medium"><?php esc_html_e( 'Subtotal', 'tutor' ); ?></div>
				<div class="tutor-fw-bold">
					<?php tutor_print_formatted_price( $checkout_data->subtotal_price ); ?>
				</div>
			</div>

			<?php if ( $checkout_data->sale_discount > 0 ) : ?>
			<div class="tutor-checkout-summary-item">
				<div>
				<?php
					$sale_discount_label = apply_filters( 'tutor_checkout_sale_discount_label', __( 'Sale discount', 'tutor' ) );
					echo esc_html( $sale_discount_label );
				?>
					</div>
				<div class="tutor-fw-bold">
					- <?php tutor_print_formatted_price( $checkout_data->sale_discount ); ?>
				</div>
			</div>
			<?php endif ?>

			<?php if ( $show_coupon_box ) : ?>
				<div class="tutor-checkout-summary-item tutor-have-a-coupon">
					<div><?php esc_html_e( 'Have a coupon?', 'tutor' ); ?></div>
					<button type="button" id="tutor-toggle-coupon-button" class="tutor-btn tutor-btn-link">
							<?php esc_html_e( 'Click here', 'tutor' ); ?>
					</button>
				</div>
				<div class="tutor-apply-coupon-form tutor-d-none">
					<input type="text" name="coupon_code"
							<?php if ( 'manual' === $checkout_data->coupon_type && $checkout_data->is_coupon_applied ) : ?>
							value="<?php echo esc_attr( $coupon_code ); ?>"
					<?php endif; ?>
					placeholder="<?php esc_html_e( 'Add coupon code', 'tutor' ); ?>">
					<button type="button" id="tutor-apply-coupon-button" class="tutor-btn tutor-btn-secondary" data-object-ids="<?php echo esc_attr( implode( ',', $object_ids ) ); ?>"><?php esc_html_e( 'Apply', 'tutor' ); ?></button>
				</div>
			<?php endif; ?>

			<div class="tutor-checkout-summary-item tutor-checkout-coupon-wrapper <?php echo esc_attr( $checkout_data->is_coupon_applied ? '' : 'tutor-d-none' ); ?>">
				<div class="tutor-checkout-coupon-badge tutor-has-delete-button">
					<i class="tutor-icon-tag" area-hidden="true"></i>
					<span><?php echo esc_html( $checkout_data->coupon_title ); ?></span>

					<?php if ( $checkout_data->is_coupon_applied ) : ?>
					<button type="button" id="tutor-checkout-remove-coupon" class="tutor-btn">
						<i class="tutor-icon-times" area-hidden="true"></i>
					</button>
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
				<div class="tutor-fs-5 tutor-fw-bold tutor-checkout-grand-total">
					<?php tutor_print_formatted_price( $checkout_data->total_price ); ?>
				</div>
			</div>
			<div class="tutor-checkout-summary-item tutor-checkout-incl-tax-label">
				<div></div>
					<?php if ( $checkout_data->tax_amount > 0 && $is_tax_included_in_price ) : ?>
					<div class="tutor-fs-7 tutor-color-muted">
						<?php
						/* translators: %s: tax amount */
						echo esc_html( sprintf( __( '(Incl. Tax %s)', 'tutor' ), tutor_get_formatted_price( $checkout_data->tax_amount ) ) );
						?>
					</div>
					<?php endif ?>
			</div>

			<input type="hidden" name="coupon_code" value="<?php echo esc_attr( $coupon_code ); ?>">
			<input type="hidden" name="object_ids" value="<?php echo esc_attr( implode( ',', $object_ids ) ); ?>">
			<input type="hidden" name="order_type" value="<?php echo esc_attr( $order_type ); ?>">
		</div>

		<?php
		$is_zero_price        = empty( $checkout_data->total_price ) && OrderModel::TYPE_SINGLE_ORDER === $checkout_data->order_type;
		$pay_now_btn_text     = $is_zero_price ? __( 'Enroll Now', 'tutor' ) : __( 'Pay Now', 'tutor' );
		$pay_now_btn_text     = apply_filters( 'tutor_checkout_pay_now_btn_text', $pay_now_btn_text, $checkout_data );
		$show_payment_methods = apply_filters( 'tutor_checkout_show_payment_methods', ! $is_zero_price, $checkout_data );

		$checkout_data->pay_now_btn_text        = $pay_now_btn_text;
		$checkout_data->payment_method_required = $show_payment_methods;
		?>
		<input type="hidden" id="checkout_data" value="<?php echo esc_attr( wp_json_encode( $checkout_data ) ); ?>">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make payment method buttons act as direct payment buttons
    const paymentItems = document.querySelectorAll('.tutor-checkout-payment-item');
    paymentItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Select the radio button
            const radioButton = this.querySelector('input[type="radio"]');
            if (radioButton) {
                radioButton.checked = true;
                
                // Trigger change event to update payment method
                radioButton.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Wait a moment for any payment method setup, then trigger payment
                setTimeout(() => {
                    const payNowButton = document.getElementById('tutor-checkout-pay-now-button');
                    if (payNowButton) {
                        payNowButton.click();
                    } else if (typeof processDirectPayment === 'function') {
                        processDirectPayment();
                    }
                }, 100);
            }
        });
    });
});
</script>
	</div>
</div>
