<?php
/**
 * Cart Template
 * For tutor monetization.
 *
 * @package Tutor\Views
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Tutor\Ecommerce\CartController;
use Tutor\Ecommerce\CheckoutController;
use Tutor\Ecommerce\Tax;
use Tutor\Models\CourseModel;

$cart_controller = new CartController();
$get_cart        = $cart_controller->get_cart_items();
$courses         = $get_cart['courses'];
$total_count     = $courses['total_count'];
$course_list     = $courses['results'];

$subtotal         = 0;
$tax_exempt_price = 0;

$checkout_page_url = CheckoutController::get_page_url();

?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

.custom-cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.cart-header {
    background-color: #592D36;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 600;
}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    border-bottom: 1px solid #e0e0e0;
    gap: 20px;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-name {
    flex: 1;
    font-size: 16px;
    color: #333;
    font-weight: 500;
}

.item-price {
    flex: 0 0 auto;
    font-size: 18px;
    font-weight: 600;
    color: #333;
    text-align: center;
    min-width: 120px;
}

.item-actions {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.go-to-course {
    font-family: 'Poppins', sans-serif;
    color: #592D36;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid #592D36;
    padding-bottom: 2px;
    transition: all 0.3s ease;
}

.go-to-course:hover {
    color: #7a3d47;
    border-bottom-color: #7a3d47;
}

.chevron-icon {
    width: 16px;
    height: 16px;
    border: 1px solid #592D36;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    transition: all 0.3s ease;
}

.go-to-course:hover .chevron-icon {
    border-color: #7a3d47;
}

.remove-btn {
    background: none;
    border: none;
    color: #999;
    font-size: 12px;
    cursor: pointer;
    text-decoration: underline;
}

.remove-btn:hover {
    color: #666;
}

.checkout-section {
    margin-top: 30px;
    text-align: right;
}

.checkout-btn {
    background-color: #592D36;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.checkout-btn:hover {
    background-color: #7a3d47;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .custom-cart-container {
        padding: 15px;
    }
    
    .cart-header {
        padding: 12px 20px;
        font-size: 16px;
    }
    
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
    }
    
    .item-name {
        font-size: 15px;
        order: 1;
    }
    
    .item-price {
        font-size: 16px;
        order: 2;
        align-self: flex-start;
        min-width: auto;
    }
    
    .item-actions {
        order: 3;
        align-self: flex-end;
        flex-direction: row;
        align-items: center;
        gap: 15px;
    }
    
    .go-to-course {
        font-size: 13px;
    }
    
    .checkout-section {
        text-align: center;
        margin-top: 25px;
    }
    
    .checkout-btn {
        width: 100%;
        padding: 12px 20px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .custom-cart-container {
        padding: 10px;
    }
    
    .cart-header {
        padding: 10px 15px;
        font-size: 15px;
    }
    
    .cart-item {
        padding: 12px 0;
        gap: 12px;
    }
    
    .item-name {
        font-size: 14px;
    }
    
    .item-price {
        font-size: 15px;
    }
    
    .go-to-course {
        font-size: 12px;
    }
    
    .chevron-icon {
        width: 14px;
        height: 14px;
        font-size: 9px;
    }
}
</style>
<div class="tutor-cart-page">
	<div class="tutor-cart-page-wrapper">
		<div class="custom-cart-container">
			<?php if ( is_array( $course_list ) && count( $course_list ) ) : ?>
				<div class="cart-header">
					Detalles de tu Compra
				</div>
				
				<div class="cart-items">
					<?php
					foreach ( $course_list as $key => $course ) :
						$course_price     = tutor_utils()->get_raw_course_price( $course->ID );
						$regular_price    = $course_price->regular_price;
						$sale_price       = $course_price->sale_price;
						$display_price    = $sale_price ? $sale_price : $regular_price;

						$subtotal += $display_price;

						$tax_collection = CourseModel::is_tax_enabled_for_single_purchase( $course->ID );
						if ( ! $tax_collection ) {
							$tax_exempt_price += $display_price;
						}
						?>
						<div class="cart-item">
							<div class="item-name">
								<?php echo esc_html( $course->post_title ); ?>
							</div>
							<div class="item-price">
								<?php tutor_print_formatted_price( $display_price ); ?>
							</div>
							<div class="item-actions">
								<a href="<?php echo esc_url( get_the_permalink( $course ) ); ?>" class="go-to-course">
									Ir al curso
									<span class="chevron-icon">⌄</span>
								</a>
								<button class="remove-btn tutor-cart-remove-button" data-course-id="<?php echo esc_attr( $course->ID ); ?>">
									Remove
								</button>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php
				$should_calculate_tax     = Tax::should_calculate_tax();
				$is_tax_included_in_price = Tax::is_tax_included_in_price();
				$tax_rate                 = Tax::get_user_tax_rate();
				$show_tax_incl_text       = $should_calculate_tax && $tax_rate > 0 && $is_tax_included_in_price;
				$tax_amount               = 0;

				if ( $should_calculate_tax ) {
					$tax_amount        = Tax::calculate_tax( $subtotal, $tax_rate );
					$tax_exempt_amount = Tax::calculate_tax( $tax_exempt_price, $tax_rate );
					$tax_amount        = $tax_amount - $tax_exempt_amount;
				}

				$grand_total = $subtotal;
				if ( ! $is_tax_included_in_price ) {
					$grand_total += $tax_amount;
				}
				?>
				
				<div class="checkout-section">
					<a data-cy="tutor-native-checkout-button" class="checkout-btn <?php echo esc_attr( $checkout_page_url ? '' : 'tutor-checkout-page-not-configured' ); ?>" href="<?php echo esc_url( $checkout_page_url ? $checkout_page_url : '#' ); ?>">
						Ir a pagar
					</a>
				</div>
			<?php else : ?>
				<div class="tutor-cart-empty-state">
					<img src="<?php echo esc_url( tutor()->url ); ?>assets/images/empty-cart.svg" alt="<?php esc_html_e( 'Empty shopping cart', 'tutor' ); ?>" />
					<p><?php esc_html_e( 'No courses in the cart', 'tutor' ); ?></p>
					<a href="<?php echo esc_url( tutor_utils()->course_archive_page_url() ); ?>" class="tutor-btn tutor-btn-lg tutor-btn-primary"><?php esc_html_e( 'Continue Browsing', 'tutor' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
