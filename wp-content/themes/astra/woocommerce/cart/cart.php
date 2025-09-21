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
/* Forzar que cada item del carrito apile su contenido en columna */
.cart-item {
    flex-direction: column !important;
}

/* Quitar margen izquierdo del contenedor de totales, incluso si viene inline */
.cart_totals {
    margin-left: 0 !important;
}
/* Ocultar columna derecha de totales de Elementor */
.e-cart__column.e-cart__column-end {
    display: none !important;
}

/* Forzar layout en una sola columna */
.elementor-widget-woocommerce-cart .e-cart__container {
    display: block !important;
}

/* Asegurar que la columna izquierda ocupe todo el ancho */
.e-cart__column.e-cart__column-start {
    width: 100% !important;
}
/* Ocultar la sección del cupón */
.coupon.e-cart-section.shop_table {
    display: none !important;
}

/* Eliminar bordes de los contenedores del carrito de Elementor */
.elementor-widget-woocommerce-cart .e-cart-section {
    border-width: 0px !important;
}

/* Contenedor para alinear el botón de pago a la derecha */
.cart_totals {
    display: flex;
    justify-content: flex-end;
}

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

@media (max-width: 768px) {
    .cart-header {
        font-size: 20px !important;
    }
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
.site-header {
    display: none !important;
}

/* Ocultar tabla completa de totales */
.cart_totals table.shop_table,
.cart_totals .shop_table_responsive,
.cart-subtotal,
.woocommerce-shipping-totals,
.order-total,
.cart_totals tbody,
.cart_totals tr {
    display: none !important;
}

/* Ocultar título Cart totals */
.cart_totals h2 {
    display: none !important;
}

/* Ocultar mensajes de error de WooCommerce */
.woocommerce-error,
ul.woocommerce-error {
    display: none !important;
}

/* Botón Ir a pagar posicionado */
.wc-proceed-to-checkout {
    text-align: right !important;
    margin: 30px 0 20px 0 !important;
    padding-right: 80px !important;
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
			<span class="cart-header-text">Detalles de tu Compra</span>
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
							<span class="plan-type">
							<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
								}
							?>
							</span>
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

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>

	</div>

	<div class="cart_totals">
		<div class="wc-proceed-to-checkout">
			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
				Ir a pagar
			</a>
		</div>
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

		<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

		<?php do_action( 'woocommerce_cart_actions' ); ?>

		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
