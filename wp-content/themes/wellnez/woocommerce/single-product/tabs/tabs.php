<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

$average_rating = $product->get_average_rating();
$review_count   = $product->get_review_count();

$rating_1 = $product->get_rating_count(1);
$rating_2 = $product->get_rating_count(2);
$rating_3 = $product->get_rating_count(3);
$rating_4 = $product->get_rating_count(4);
$rating_5 = $product->get_rating_count(5);

if ( ! empty( $tabs ) ) : ?>

        <ul class="nav product-tab1" id="productTab" role="tablist">

            <?php
                $tabcount = 1;
                foreach ( $tabs as $key => $tab ) :
                    if( $tabcount == 1 ) {
                        $tabactivecls = 'active';
						$area = 'true';
                    } else {
                        $tabactivecls = '';
                        $area = 'false';
                    }
            ?>
				<li class="nav-item" role="presentation"><a class="nav-link <?php echo esc_attr( $tabactivecls );?>" data-bs-toggle="tab" href="#<?php echo esc_attr( $key );?>" role="tab" aria-controls="<?php echo esc_attr( $key );?>" id="tab-<?php echo esc_attr( $key ); ?>" area-selected="<?php echo esc_attr( $area );?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a></li>
            <?php
                $tabcount++;
                endforeach;
            ?>
        </ul>
        <!-- End Tab Buttons -->
        <!-- Tab Content -->
		<div class="col-lg-12">
			<div class="tab-content" id="productDetailsTab">
				<div class="tab-content" id="productDetailsTab">
					<?php
						$tabcontentcount = 1;
						foreach ( $tabs as $key => $tab ) :
							if( $tabcontentcount == 1 ) {
								$tabcontentactivecls = ' active';
							} else {
								$tabcontentactivecls = '';
							}
					?>
						<div class="tab-pane fade show <?php echo esc_attr($tabcontentactivecls); ?>" role="tabpanel" id="<?php echo esc_attr( $key ); ?>" aria-labelledby="tab-<?php echo esc_attr( $key ); ?>">
							<div class="inner-pane">
								<?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
							</div>
						</div>
					<?php
						$tabcontentcount++;
						endforeach;
					?>
				</div>
			</div>
		</div>
<?php endif; ?>