<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wellnez_woo_relproduct_display = wellnez_opt('wellnez_woo_relproduct_display');

if ( $related_products && $wellnez_woo_relproduct_display) : ?>

	<div class="container space-top">

        <?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Related products', 'wellnez' ) );

		if ( $heading ) :
			?>
            <h2 class="sec-subtitle3">
			    <?php echo esc_html( $heading ); ?>
            </h2>
		<?php endif;?>

		<div class="row related-product vs-carousel" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" >
        <?php
            if( class_exists('ReduxFramework') ) {
                $wellnez_woo_related_product_col = wellnez_opt('wellnez_woo_related_product_col');
            } else{
                $wellnez_woo_related_product_col = '4';
            }
        ?>

			<?php foreach ( $related_products as $related_product ) : ?>
                <div class="col-md-6 col-lg-3">
                    <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object );

                        wc_get_template_part( 'content', 'product' );
                    ?>
                </div>

			<?php endforeach; ?>

		</div>

	</div>

<?php endif;

wp_reset_postdata();