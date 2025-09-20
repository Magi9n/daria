<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<!-- Estilos CSS para el nuevo diseño del carrito -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.custom-cart-container {
    font-family: 'Poppins', sans-serif;
    max-width: 800px;
    margin: 0 auto;
    background: #f8f8f8;
    border-radius: 8px;
    overflow: hidden;
}

.cart-header {
    background-color: #592D36;
    color: white;
    padding: 15px 25px;
    font-weight: 600;
    font-size: 16px;
}

.cart-item {
    background: white;
    margin: 0;
    padding: 20px 25px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.cart-item:last-child {
    border-bottom: none;
}

.product-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.product-name-text {
    font-weight: 500;
    color: #333;
    margin: 0;
    font-size: 14px;
}

.product-price {
    font-weight: 600;
    color: #333;
    font-size: 16px;
    margin: 0 40px;
    text-align: center;
    flex-shrink: 0;
}

.course-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    font-size: 14px;
    position: relative;
}

.course-link:hover {
    color: #592D36;
}

.course-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 1px;
    background-color: #333;
}

.course-link:hover::after {
    background-color: #592D36;
}

.chevron-icon {
    width: 20px;
    height: 20px;
    border: 1px solid #333;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.course-link:hover .chevron-icon {
    border-color: #592D36;
    color: #592D36;
}

.hidden-cart-data {
    display: none;
}
</style>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="custom-cart-container">
		<div class="cart-header">
			Detalles de tu Compra
		</div>

		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="product-info">
						<p class="product-name-text">
							Sé tu propia maquillista - 
							<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
								}
							?>
						</p>
					</div>

					<div class="product-price">
						<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?> MXN
					</div>

					<?php if ( $product_permalink ) : ?>
					<a href="<?php echo esc_url( $product_permalink ); ?>" class="course-link">
						Ir al curso
						<span class="chevron-icon">›</span>
					</a>
					<?php endif; ?>

					<!-- Datos ocultos necesarios para WooCommerce -->
					<div class="hidden-cart-data">
						<?php
							// Thumbnail (oculto pero necesario para hooks)
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array('120','120') ), $cart_item, $cart_item_key );
							echo $thumbnail;

							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'wellnez' ) . '</p>', $product_id ) );
							}

							// Quantity (oculto)
							if ( $_product->is_sold_individually() ) {
								printf( '<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							}

							// Remove button (oculto)
							echo apply_filters(
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s" style="display:none;">Remove</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'wellnez' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() ),
									esc_attr( $cart_item_key )
								),
								$cart_item_key
							);
						?>
					</div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_contents' ); ?>
	</div>

	<!-- Mantener acciones ocultas pero funcionales -->
	<div style="display: none;">
		<?php if ( wc_coupons_enabled() ) { ?>
			<div class="vs-cart-coupon">
				<input type="text" name="coupon_code" class="form-control" id="coupon_code" value="" placeholder="<?php echo esc_attr__( 'Enter Coupon Code', 'wellnez' ); ?>" />
				<button type="submit" class="vs-btn" name="apply_coupon" value="<?php echo esc_attr__( 'Apply coupon', 'wellnez' ); ?>"><?php echo esc_attr__( 'Submit', 'wellnez' ); ?></button>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		<?php } ?>
		<?php
			$wellnez_shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
		?>
		<button type="submit" class="vs-btn" name="update_cart" value="<?php echo esc_attr__( 'Update cart', 'wellnez' ); ?>"><?php echo esc_html__( ' Update cart', 'wellnez' ); ?></button>
		<a href="<?php echo esc_url( $wellnez_shop_page_url ); ?>" class="vs-btn"><?php echo esc_html__('Continue Shopping','wellnez'); ?></a>

		<?php do_action( 'woocommerce_cart_actions' ); ?>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action( 'woocommerce_cart_collaterals' );
?>

<?php do_action( 'woocommerce_after_cart' ); ?>