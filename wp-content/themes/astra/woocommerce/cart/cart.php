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

.cart-header {
    background-color: #592D36 !important;
    color: white !important;
    padding: 8px 25px !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    font-family: inherit !important; /* Quitar fuente Poppins */
    border-radius: 15px 15px 0 0 !important;
    margin-bottom: 0 !important;
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
}

.product-name-text {
    font-weight: bold !important;
    color: #592D36 !important;
    margin: 0 !important;
    font-size: 15px !important;
    font-family: inherit !important; /* Quitar fuente Poppins */
    flex: 1 !important;
    text-align: left !important;
}

/* Estilo para las palabras Esencial o Personalizado */
.product-name-text span.plan-type {
    color: #322828 !important;
    font-weight: bold !important;
}

.product-price {
    font-weight: bold !important;
    color: #322828 !important;
    font-size: 18px !important;
    margin: 0 40px !important;
    text-align: center !important;
    flex-shrink: 0 !important;
    font-family: 'Poppins', sans-serif !important;
    min-width: 120px !important;
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
    transition: all 0.3s ease !important;
    transform: translateY(0) !important;
}

.course-link:hover {
    transform: translateY(-2px) !important;
    color: #592D36 !important;
}

.course-link:hover .chevron-icon {
    background-color: #f0f0f0 !important;
    transform: translateX(3px) !important;
}

.course-link::after {
    content: '' !important;
    position: absolute !important;
    bottom: -2px !important;
    left: 0 !important;
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

.hidden-cart-data {
    display: none !important;
}

/* Fondo blanco para toda la página */
body.woocommerce-cart,
.page-template-default {
    background: #ffffff !important;
}

/* Ocultar elementos innecesarios */
.entry-header,
.entry-content .entry-header,
.page-header,
.breadcrumbs,
.site-header,
.woocommerce-cart-form__contents .cart-subtotal,
.woocommerce-cart-form__contents .cart-subtotal th,
.woocommerce-cart-form__contents .order-total,
.woocommerce-cart-form__contents .order-total th,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal th,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal,
.woocommerce-cart-form__contents .order-total,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal,
.woocommerce-cart-form__contents .cart-subtotal td,
.woocommerce-cart-form__contents .order-total td,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal td,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-currencySymbol,
.woocommerce-cart-form__contents .cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .order-total .woocommerce-Price-amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .woocommerce-Price-amount,
.woocommerce-cart-form__contents .cart-subtotal .amount,
.woocommerce-cart-form__contents .order-total .amount,
.woocommerce-cart-form__contents .woocommerce-cart-subtotal .amount {
    display: none !important;
}

/* Ocultar advertencias de "You cannot add another..." */
.woocommerce-error,
.woocommerce-message,
.woocommerce-info,
.woocommerce-notice,
.woocommerce-message--info,
.woocommerce-message--error,
.woocommerce-message--info,
.woocommerce-error li,
.woocommerce-message li,
.woocommerce-info li,
.woocommerce-notice li,
.woocommerce-message--info li,
.woocommerce-message--error li,
.woocommerce-message--info li,
.woocommerce-error:before,
.woocommerce-message:before,
.woocommerce-info:before,
.woocommerce-notice:before,
.woocommerce-message--info:before,
.woocommerce-message--error:before,
.woocommerce-message--info:before,
.woocommerce-error:after,
.woocommerce-message:after,
.woocommerce-info:after,
.woocommerce-notice:after,
.woocommerce-message--info:after,
.woocommerce-message--error:after,
.woocommerce-message--info:after {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    font-size: 0 !important;
    line-height: 0 !important;
}

/* Botón Ir a pagar posicionado */
.wc-proceed-to-checkout {
    text-align: right !important;
    margin: 30px 0 20px 0 !important;
    padding-right: 20px !important;
}

.wc-proceed-to-checkout .checkout-button {
    background-color: #592D36 !important;
    color: white !important;
    padding: 12px 25px !important;
    font-size: 14px !important;
    border-radius: 25px !important;
    text-decoration: none !important;
    display: inline-block !important;
    font-family: 'Poppins', sans-serif !important;
    font-weight: 500 !important;
    border: none !important;
    width: auto !important;
    box-shadow: 0 3px 10px rgba(89, 45, 54, 0.3) !important;
    transition: all 0.3s ease !important;
    text-transform: none !important;
}

/* Asegurar que el botón diga "Ir a pagar" */
.wc-proceed-to-checkout .checkout-button:before {
    content: 'Ir a pagar' !important;
    display: inline-block !important;
}

/* Ocultar el texto original del botón */
.wc-proceed-to-checkout .checkout-button span {
    display: none !important;
}

.wc-proceed-to-checkout .checkout-button:hover {
    background-color: #4a252b !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(89, 45, 54, 0.4) !important;
}</style>

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
							$product_name = $_product->get_name();
							// Buscar si el nombre contiene "Esencial" o "Personalizado"
							if (strpos($product_name, 'Esencial') !== false) {
								$product_name = str_replace('Esencial', '<span class="plan-type">Esencial</span>', $product_name);
							} elseif (strpos($product_name, 'Personalizado') !== false) {
								$product_name = str_replace('Personalizado', '<span class="plan-type">Personalizado</span>', $product_name);
							}
							
							if ( ! $product_permalink ) {
								echo wp_kses($product_name, array('span' => array('class' => true)));
							} else {
								echo wp_kses($product_name, array('span' => array('class' => true)));
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
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
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
									esc_html__( 'Remove this item', 'woocommerce' ),
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
			<div class="coupon">
				<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> 
				<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> 
				<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		<?php } ?>

		<a href="<?php echo esc_url( $checkout_page_url ? $checkout_page_url : '#' ); ?>">
			Ir a pagar
		</a>

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
