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

/* Ocultar el diseño original de WooCommerce */
.woocommerce-cart-form,
.cart-collaterals {
    display: none !important;
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

<!-- Diseño personalizado del carrito -->
<div class="custom-cart-container">
    <div class="cart-header">
        Detalles de tu Compra
    </div>
    
    <div class="cart-items">
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                <div class="cart-item">
                    <div class="item-name">
                        <?php echo esc_html( $_product->get_name() ); ?>
                    </div>
                    <div class="item-price">
                        <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                    </div>
                    <div class="item-actions">
                        <?php if ( $product_permalink ) : ?>
                        <a href="<?php echo esc_url( $product_permalink ); ?>" class="go-to-course">
                            Ir al curso
                            <span class="chevron-icon">⌄</span>
                        </a>
                        <?php endif; ?>
                        <?php
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<button class="remove-btn" onclick="window.location.href=\'%s\'" data-product_id="%s" data-cart_item_key="%s">Remove</button>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr( $product_id ),
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
    </div>
    
    <div class="checkout-section">
        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-btn">
            Ir a pagar
        </a>
    </div>
</div>

<!-- Formulario oculto para mantener funcionalidad -->
<form class="woocommerce-cart-form cart-table table-responsive" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" style="display: none;">
    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    <?php do_action( 'woocommerce_cart_actions' ); ?>
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