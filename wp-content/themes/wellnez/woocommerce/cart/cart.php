<?php
/**
 * Custom Cart Page - Diseño personalizado responsive
 *
 * @package WooCommerce/Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

// Incluir el template personalizado de Tutor LMS
$custom_template = get_template_directory() . '/tutor/ecommerce/cart.php';
if ( file_exists( $custom_template ) ) {
    include $custom_template;
    return;
}

// Fallback al template original
do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form cart-table table-responsive" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table cart_table shop_table_responsive responsive_table cart woocommerce-cart-form__contents table table-bordered" cellspacing="0">
		<thead>
			<tr>
				<th class="cart-col-image"><?php echo esc_html__( 'Image', 'wellnez' ); ?></th>
				<th class="cart-col-productname"><?php echo esc_html__( 'Product Name', 'wellnez' ); ?></th>
				<th class="cart-col-price"><?php echo esc_html__( 'Price/Unit', 'wellnez' ); ?></th>
				<th class="cart-col-quantity"><?php echo esc_html__( 'Quantity', 'wellnez' ); ?></th>
				<th class="cart-col-total"><?php echo esc_html__( 'Total', 'wellnez' ); ?></th>
				<th class="cart-col-remove"><?php echo esc_html__( 'Remove', 'wellnez' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-name" data-title="<?php echo esc_attr__( 'Product', 'wellnez' ); ?>">
                            <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array('120','120') ), $cart_item, $cart_item_key );

                                if ( ! $product_permalink ) {
                                    echo wp_kses_post($thumbnail); // PHPCS: XSS ok.
                                } else {
                                    printf( '<a class="cart-productimage" href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                }

                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                // Meta data.
                                echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                // Backorder notification.
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'wellnez' ) . '</p>', $product_id ) );
                                }
                            ?>
						</td>
						<td data-title="<?php echo esc_attr__( 'Name', 'wellnez' ); ?>">
							<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="cart-productname" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}
							?>
                        </td>
						<td class="product-price" data-title="<?php echo esc_attr__( 'Price', 'wellnez' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php echo esc_attr__( 'Quantity', 'wellnez' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
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
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>

						<td class="product-subtotal" data-title="<?php echo esc_attr__( 'Total', 'wellnez' ); ?>">
							<?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
						<td class="mini_cart_item" data-title="<?php echo esc_attr__( 'Remove', 'wellnez' ); ?>">
							<?php
								// remove button
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove ml-3" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s"><i class="fal fa-trash-alt"></i></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'wellnez' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() ),
										esc_attr( $cart_item_key )
									),
									$cart_item_key
								);
							?>
	                    </td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">
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


                </td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
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