<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table cart_table mb-20">
	<thead>
		<tr>
			<th class="cart-col-image"><?php echo esc_html__( 'Image', 'wellnez' ); ?></th>
			<th class="cart-col-productname"><?php echo esc_html__( 'Product Name', 'wellnez' ); ?></th>
			<th class="cart-col-price"><?php echo esc_html__( 'Price', 'wellnez' ); ?></th>
			<th class="cart-col-quantity"><?php echo esc_html__( 'Quantity', 'wellnez' ); ?></th>
			<th class="cart-col-total"><?php echo esc_html__( 'Total', 'wellnez' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name" data-title="<?php echo esc_attr__( 'Product', 'wellnez' ); ?>">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array('95','95') ), $cart_item, $cart_item_key );
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail ); // PHPCS: XSS ok.
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
						echo '<strong class="product-quantity">'.esc_html( $cart_item['quantity'] ).'</strong>';
					?>
					</td>

					<td class="product-subtotal" data-title="<?php echo esc_attr__( 'Total', 'wellnez' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
						?>
					</td>
				</tr>
				
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot class="checkout-ordertable">
		<tr class="cart-subtotal">
			<th><?php echo esc_html__( 'Subtotal', 'wellnez' ); ?></th>
			<td data-title="<?php echo esc_attr__( 'Subtotal', 'wellnez' );?>" colspan="6"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td colspan="6"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>
		
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			
			<?php wc_cart_totals_shipping_html(); ?>
			
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td colspan="6"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td colspan="6"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td colspan="6"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php echo esc_html__( 'Total', 'wellnez' ); ?></th>
			<td data-title="<?php echo esc_attr__( 'Total', 'wellnez' );?>" colspan="6"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</tfoot>
</table>