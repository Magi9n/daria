<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    <div class="row">
		<?php
            if( class_exists('ReduxFramework') ) {
                $wellnez_woo_singlepage_sidebar = wellnez_opt('wellnez_woo_singlepage_sidebar');
                if( $wellnez_woo_singlepage_sidebar == '2' && is_active_sidebar('wellnez-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9 order-last">';
                } elseif( $wellnez_woo_singlepage_sidebar == '3' && is_active_sidebar('wellnez-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }
				echo '<div class="row gx-60">';
					echo '<div class="col-lg-6">';
						echo '<div class="product-details-img product-img">';
							/**
							 * Hook: woocommerce_before_single_product_summary.
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							do_action( 'woocommerce_before_single_product_summary' );
						echo '</div>';
					echo '</div>';
					echo '<div class="col-lg-6 align-self-center">';
						echo '<div class="product-about">';
							/**
							 * Hook: woocommerce_single_product_summary.
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked wellnez_woocommerce_single_product_price_rating - 30
							 * @hooked woocommerce_template_single_excerpt - 40
							 * @hooked wellnez_woocommerce_single_product_availability - 50
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 * @hooked WC_Structured_Data::generate_product_data() - 60
							 */
							do_action( 'woocommerce_single_product_summary' );

						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';

            /**
             * Hook: wellnez_woocommerce_single_product_sidebar.
             *
             * @hooked wellnez_woocommerce_single_product_sidebar_cb - 10
             */
            do_action( 'wellnez_woocommerce_single_product_sidebar' );
        ?>

    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="position-relative">
	    <div class="product-details-inner">
	    	<div class="container">
		        <?php
			        /**
			         * Hook: woocommerce_after_single_product_summary.
			         *
			         * @hooked woocommerce_output_product_data_tabs - 10
			         * @hooked woocommerce_upsell_display - 15
			         * @hooked woocommerce_output_related_products - 20
			         */
			        do_action( 'woocommerce_after_single_product_summary' );
		        ?>
	    	</div>
	    </div>
	</div>
	<?php do_action( 'vemdora_woocommerce_output_related_products' ); ?>

<?php do_action( 'woocommerce_after_single_product' ); ?>