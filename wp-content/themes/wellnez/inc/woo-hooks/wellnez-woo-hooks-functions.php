<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Wellnez
 * @Author URI : https://www.vecuro.com/
 *
 */

if( ! function_exists( 'wellnez_shop_categorysection_start_cb' ) ){
    function wellnez_shop_categorysection_start_cb(){
        // global $post;
        $terms = get_terms( array(
            'taxonomy'      => 'product_cat',
            'hide_empty'    => false,
        ) );
        if( $terms && class_exists( 'ReduxFramework' ) && wellnez_opt( 'wellnez_category_show_hide' ) ){
            echo '<section class="space-top arrow-wrap">';
                echo '<div class="container">';
                    echo '<div class="row category justify-content-between align-items-center vs-carousel arrow-margin" data-arrows="true" data-slide-show="6" data-ml-slide-show="6" data-lg-slide-show="5" data-md-slide-show="4" data-sm-slide-show="3" data-xs-slide-show="2">';
                        foreach ( $terms as $term ){
                            $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                            $catImg = wp_get_attachment_image_src( $thumbnail_id, 'full' );
                            echo '<div class="col-xl-2">';
                                echo '<div class="cat_rounded">';
                                    if( ! empty( $catImg ) ){
                                        echo '<div class="cat-img">';
                                            echo '<a href="'.esc_url( get_term_link( $term ) ).'">';
                                                echo wellnez_img_tag( array(
                                                    'url'   => esc_url( $catImg[0] )
                                                ) );
                                            echo '</a>';
                                        echo '</div>';
                                    }
                                    echo '<h3 class="cat-name"><a class="text-inherit" href="'.esc_url( get_term_link( $term ) ).'">'.esc_html( $term->name ).'</a></h3>';
                                echo '</div>';
                            echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }
}

// wellnez shop main content hook functions
if( !function_exists('wellnez_shop_main_content_cb') ) {
    function wellnez_shop_main_content_cb( ) {

       

        if( is_shop() || is_product_category() || is_product_tag() ) {
            if( !empty( wellnez_opt("wellnez_shop_bg") ) ){
                $wellnez_shop_bg_image = wellnez_opt("wellnez_shop_bg");
                $url = $wellnez_shop_bg_image['url'];
            }else{
                $url = '';
            }
            
            echo '<section class="vs-product-wrapper background-image" style="background-image: url('. $url .')">';
                 echo '<div class="outer-wrap3">';
            if( class_exists('ReduxFramework') ) {
                $wellnez_woo_product_col = wellnez_opt('wellnez_woo_product_col');
                if( $wellnez_woo_product_col == '2' ) {
                    echo '<div class="container">';
                } elseif( $wellnez_woo_product_col == '3' ) {
                    echo '<div class="container">';
                } elseif( $wellnez_woo_product_col == '4' ) {
                    echo '<div class="container">';
                } elseif( $wellnez_woo_product_col == '5' ) {
                    echo '<div class="wellnez-container">';
                } elseif( $wellnez_woo_product_col == '6' ) {
                    echo '<div class="wellnez-container">';
                }
            } else {
                echo '<div class="container">';
            }
        } else {
            echo '<section class="vs-product-wrapper product-details space-top space-extra-bottom">';
                echo '<div class="container">';
        }
            echo '<div class="row gx-60">';
    }
}

// wellnez shop main content hook function
if( !function_exists('wellnez_shop_main_content_end_cb') ) {
    function wellnez_shop_main_content_end_cb( ) {
                echo '</div>';
             echo '</div>';
        echo '</div>';
    echo '</section>';
    }
}

// shop column start hook function
if( !function_exists('wellnez_shop_col_start_cb') ) {
    function wellnez_shop_col_start_cb( ) {
        if( class_exists('ReduxFramework') ) {
            if( class_exists('woocommerce') && is_shop() ) {
                $wellnez_woo_shoppage_sidebar = wellnez_opt('wellnez_woo_shoppage_sidebar');
                if( $wellnez_woo_shoppage_sidebar == '2' && is_active_sidebar('wellnez-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9 order-last">';
                } elseif( $wellnez_woo_shoppage_sidebar == '3' && is_active_sidebar('wellnez-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }
        } else {
            if( class_exists('woocommerce') && is_shop() ) {
                if( is_active_sidebar('wellnez-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }
        }

    }
}

// shop column end hook function
if( !function_exists('wellnez_shop_col_end_cb') ) {
    function wellnez_shop_col_end_cb( ) {
        echo '</div>';
    }
}

// wellnez woocommerce pagination hook function
if( ! function_exists('wellnez_woocommerce_pagination') ) {
    function wellnez_woocommerce_pagination( ) {
        if( ! empty( wellnez_pagination() ) ) {
            echo '<div class="row">';
                echo '<div class="col-12">';
                    echo '<div class="vs-pagination pt-20 pb-30">';
                        echo '<ul>';
                        $prev 	= '<i class="fas fa-chevron-left"></i>';
                        $next 	= '<i class="fas fa-chevron-right"></i>';
                            // previous
                            if( get_previous_posts_link() ){
                                echo '<li>';
                                previous_posts_link( $prev );
                                echo '</li>';
                            }
                            echo wellnez_pagination();
                            // next
                            if( get_next_posts_link() ){
                                echo '<li>';
                                next_posts_link( $next );
                                echo '</li>';
                            }
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    }
}
// woocommerce filter wrapper hook function
if( ! function_exists('wellnez_woocommerce_filter_wrapper') ) {
    function wellnez_woocommerce_filter_wrapper( ) {
        echo '<div class="vs-sort-bar">';
            echo '<div class="row justify-content-between align-items-center">';
                echo '<div class="col-md-auto">';
                    echo '<p class="woocommerce-result-count">'.woocommerce_result_count().'</p>';
                echo '</div>';
                echo '<div class="col-md-auto">';
                    echo '<div class="col-sm-auto">';
                        echo woocommerce_catalog_ordering();
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

// woocommerce tab content wrapper start hook function
if( ! function_exists('wellnez_woocommerce_tab_content_wrapper_start') ) {
    function wellnez_woocommerce_tab_content_wrapper_start( ) {
        echo '<!-- Tab Content -->';
        echo '<div class="tab-content" id="nav-tabContent">';
    }
}

// woocommerce tab content wrapper start hook function
if( ! function_exists('wellnez_woocommerce_tab_content_wrapper_end') ) {
    function wellnez_woocommerce_tab_content_wrapper_end( ) {
        echo '</div>';
        echo '<!-- End Tab Content -->';
    }
}
// wellnez grid tab content hook function
if( !function_exists('wellnez_grid_tab_content_cb') ) {
    function wellnez_grid_tab_content_cb( ) {
        echo '<!-- Grid -->';
            echo '<div class="tab-pane fade show active" id="tab-grid" role="tabpanel" aria-labelledby="tab-shop-grid">';
                woocommerce_product_loop_start();
                if( class_exists('ReduxFramework') ) {
                    $wellnez_woo_product_col = wellnez_opt('wellnez_woo_product_col');
                    if( $wellnez_woo_product_col == '2' ) {
                        $wellnez_woo_product_col_val = 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
                    } elseif( $wellnez_woo_product_col == '3' ) {
                        $wellnez_woo_product_col_val = 'col-sm-6 col-md-6 col-lg-6 col-xl-4';
                    } elseif( $wellnez_woo_product_col == '4' ) {
                        $wellnez_woo_product_col_val = 'col-sm-6 col-md-6 col-lg-6 col-xl-3';
                    }elseif( $wellnez_woo_product_col == '5' ) {
                        $wellnez_woo_product_col_val = 'col-xl col-lg-6 col-sm-6';
                    } elseif( $wellnez_woo_product_col == '6' ) {
                        $wellnez_woo_product_col_val = 'col-sm-6 col-md-6 col-lg-6 col-xl-2';
                    }
                } else {
                    $wellnez_woo_product_col_val = 'col-sm-6 col-md-6 col-lg-6 col-xl-3';
                }

                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();

                        echo '<div class="'.esc_attr( $wellnez_woo_product_col_val ).'">';
                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action( 'woocommerce_shop_loop' );

                            wc_get_template_part( 'content', 'product' );

                        echo '</div>';
                    }
                    wp_reset_postdata();
                }

                woocommerce_product_loop_end();
            echo '</div>';
        echo '<!-- End Grid -->';
    }
}

// wellnez list tab content hook function
if( !function_exists('wellnez_list_tab_content_cb') ) {
    function wellnez_list_tab_content_cb( ) {
        echo '<!-- List -->';
        echo '<div class="tab-pane fade" id="tab-list" role="tabpanel" aria-labelledby="tab-shop-list">';
            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();
                    echo '<div class="col-sm-6 col-lg-6 col-xl-4">';
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );

                        wc_get_template_part( 'content-horizontal', 'product' );
                    echo '</div>';
                }
                wp_reset_postdata();
            }

            woocommerce_product_loop_end();
        echo '</div>';
        echo '<!-- End List -->';
    }
}

// wellnez woocommerce get sidebar hook function
if( ! function_exists('wellnez_woocommerce_get_sidebar') ) {
    function wellnez_woocommerce_get_sidebar( ) {
        if( class_exists('ReduxFramework') ) {
            $wellnez_woo_shoppage_sidebar = wellnez_opt('wellnez_woo_shoppage_sidebar');
        } else {
            if( is_active_sidebar('wellnez-woo-sidebar') ) {
                $wellnez_woo_shoppage_sidebar = '2';
            } else {
                $wellnez_woo_shoppage_sidebar = '1';
            }
        }

        if( is_shop() ) {
            if( $wellnez_woo_shoppage_sidebar != '1' ) {
                get_sidebar('shop');
            }
        }
    }
}

// wellnez loop product thumbnail hook function
if( !function_exists('wellnez_loop_product_thumbnail') ) {
    function wellnez_loop_product_thumbnail( ) {
        global $product;

        echo '<div class="product-img">';
            if( $product->is_on_sale() && $product->get_type() == 'simple' ) {
                echo '<div class="onsale product-label">'.esc_html__( 'Sale', 'wellnez' ).'</div>';
            }
            if( $product->is_featured() ) {
                echo '<div class="featured woocommerce-badge product-label">'.esc_html__( 'Hot', 'wellnez' ).'</div>';
            }
            if( ! $product->is_in_stock() ) {
                echo '<div class="outofstock woocommerce-badge product-label">'.esc_html__( 'Stock Out', 'wellnez' ).'</div>';
            }
            if( has_post_thumbnail() ){
                echo '<a href="'.esc_url( get_permalink() ).'">';
                    the_post_thumbnail();
                echo '</a>';
            }
            echo '<div class="actions">';
                // Wishlist Button
                if( class_exists( 'TInvWL_Admin_TInvWL' ) ){
                    echo do_shortcode( '[ti_wishlists_addtowishlist]' );
                }
                // Quick View Button
                if( class_exists( 'WPCleverWoosq' ) ){
                    echo do_shortcode('[woosq]');
                }
                // Cart Button
                woocommerce_template_loop_add_to_cart();
                
                
            echo '</div>';
        echo '</div>';
    }
}

// shop loop product summary
if( ! function_exists('wellnez_loop_product_summary') ) {
    function wellnez_loop_product_summary( ) {
        global $product;
        echo '<div class="product-body">';
            echo '<div class="product-content">';
                // Product Title
                echo '<h3 class="product-title"><a class="text-inherit" href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></h3>';
                echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-category">' . _n( '', '', count( $product->get_category_ids() ), 'wellnez' ) . ' ', '</div>' );
            echo '</div>';
            // Product Price
            echo '<div class="product-price">';
                echo woocommerce_template_loop_price();
            echo '</div>';
        echo '</div>';
    }
}

// shop loop horizontal product summary
if( ! function_exists( 'wellnez_horizontal_loop_product_summary' ) ) {
    function wellnez_horizontal_loop_product_summary() {
        global $product;
        echo '<div class="product-content">';
            echo '<div>';
                // Product Title
                echo '<h4 class="product-title h5"><a href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></h4>';
                // Product Price
                echo woocommerce_template_loop_price();
                // Product Rating
                woocommerce_template_loop_rating();

            echo '</div>';
        echo '</div>';
    }
}

// single product price rating hook function
if( !function_exists('wellnez_woocommerce_single_product_price_rating') ) {
    function wellnez_woocommerce_single_product_price_rating() {
        global $product;
        
        // Product Rating
        echo '<div class="product-rating">';
            $average_rating = $product->get_average_rating();
            $review_count   = $product->get_review_count();
            if( $review_count == '0' ){
                echo esc_html__( 'There are no reviews yet', 'wellnez' );
            }else{
                woocommerce_template_loop_rating();
                if( $review_count > 1 ) {
                    echo esc_html__('Reviews','wellnez');
                } else {
                    echo esc_html__('Review','wellnez');
                };
                echo '<span class="count">'.esc_html( ' ('.$review_count ).')'.' </span> ';
            }
        echo '</div>';
    }
}

// single product title hook function
if( !function_exists('wellnez_woocommerce_single_product_title') ) {
    function wellnez_woocommerce_single_product_title( ) {
       

        if( class_exists( 'ReduxFramework' ) ) {
            $producttitle_position = wellnez_opt('wellnez_product_details_title_position');
        } else {
            $producttitle_position = 'header';
        }

        if( $producttitle_position != 'header' ) {
            echo '<!-- Product Title -->';
            echo '<h2 class="product-title">'.esc_html( get_the_title() ).'</h2>';
            echo '<!-- End Product Title -->';
        }

        echo '<!-- Product Price -->';
        woocommerce_template_single_price();
        echo '<!-- End Product Price -->';

    }
}

// single product title hook function
if( !function_exists('wellnez_woocommerce_quickview_single_product_title') ) {
    function wellnez_woocommerce_quickview_single_product_title( ) {
        echo '<!-- Product Title -->';
        echo '<h2 class="product-title">'.esc_html( get_the_title() ).'</h2>';
        echo '<!-- End Product Title -->';
    }
}

// single product excerpt hook function
if( !function_exists('wellnez_woocommerce_single_product_excerpt') ) {
    function wellnez_woocommerce_single_product_excerpt( ) {
        echo '<!-- Product Description -->';
        woocommerce_template_single_excerpt();
        echo '<!-- End Product Description -->';
    }
}

// single product availability hook function
if( !function_exists('wellnez_woocommerce_single_product_availability') ) {
    function wellnez_woocommerce_single_product_availability( ) {
        global $product;
        $availability = $product->get_availability();

        if( class_exists( 'ReduxFramework' ) ){
            $wellnez_stock_quantity = wellnez_opt( 'wellnez_woo_stock_quantity_show_hide' );
        }else{
            $wellnez_stock_quantity = 1;
        }

        if( $wellnez_stock_quantity ){
            if( $availability['class'] != 'out-of-stock' ) {
                echo '<!-- Product Availability -->';
                    echo '<div class="mt-2 link-inherit fs-xs">';
                        echo '<p>';
                            echo '<strong class="text-title me-3 font-theme">'.esc_html__( 'Availability:', 'wellnez' ).'</strong>';
                            if( $product->get_stock_quantity() ){
                                echo '<span class="stock in-stock"><i class="far fa-check-square me-2"></i>'.esc_html( $product->get_stock_quantity() ).'</span>';
                            }else{
                                echo '<span class="stock in-stock"><i class="far fa-check-square me-2"></i>'.esc_html__( 'In Stock', 'wellnez' ).'</span>';
                            }
                        echo '</p>';
                    echo '</div>';
                echo '<!--End Product Availability -->';
            } else {
                echo '<!-- Product Availability -->';
                echo '<div class="mt-2 link-inherit fs-xs">';
                    echo '<p>';
                        echo '<strong class="text-title me-3 font-theme">'.esc_html__( 'Availability:', 'wellnez' ).'</strong>';
                        echo '<span class="stock out-of-stock"><i class="far fa-check-square me-2"></i>'.esc_html__( 'Out Of Stock', 'wellnez' ).'</span>';
                    echo '</p>';
                echo '</div>';
                echo '<!--End Product Availability -->';
            }
        }
    }
}

// single product add to cart fuunction
if( !function_exists('wellnez_woocommerce_single_add_to_cart_button') ) {
    function wellnez_woocommerce_single_add_to_cart_button( ) {
        woocommerce_template_single_add_to_cart();
    }
}

// single product ,eta hook function
if( !function_exists('wellnez_woocommerce_single_meta') ) {
    function wellnez_woocommerce_single_meta( ) {
        global $product;
        echo '<div class="product_meta">';
            if( ! empty( $product->get_sku() ) ){
                echo '<span class="sku_wrapper">'.esc_html__( 'SKU:', 'wellnez' ).'<span class="sku">'.$product->get_sku().'</span></span>';
            }
            echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'wellnez' ) . ' ', '</span>' );
        echo '</div>';
    }
}

// single produt sidebar hook function
if( !function_exists('wellnez_woocommerce_single_product_sidebar_cb') ) {
    function wellnez_woocommerce_single_product_sidebar_cb(){
        if( class_exists('ReduxFramework') ) {
            $wellnez_woo_singlepage_sidebar = wellnez_opt('wellnez_woo_singlepage_sidebar');
            if( ( $wellnez_woo_singlepage_sidebar == '2' || $wellnez_woo_singlepage_sidebar == '3' ) && is_active_sidebar('wellnez-woo-sidebar') ) {
                get_sidebar('shop');
            }
        } else {
            if( is_active_sidebar('wellnez-woo-sidebar') ) {
                get_sidebar('shop');
            }
        }
    }
}

// reviewer meta hook function
if( !function_exists('wellnez_woocommerce_reviewer_meta') ) {
    function wellnez_woocommerce_reviewer_meta( $comment ){
        $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
        if ( '0' === $comment->comment_approved ) { ?>
            <em class="woocommerce-review__awaiting-approval">
                <?php echo esc_html__( 'Your review is awaiting approval', 'wellnez' ); ?>
            </em>

        <?php } else { ?>
                <h4 class="name h4"><?php echo ucwords( get_comment_author() ); ?> </h4>
                
                <span class="commented-on"><time class="woocommerce-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>"> <?php printf( esc_html__('%1$s at %2$s', 'wellnez'), get_comment_date(wc_date_format()),  get_comment_time() ); ?> </time></span>
           
                <?php
                if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
                    echo '<em class="woocommerce-review__verified verified">(' . esc_attr__( 'verified owner', 'wellnez' ) . ')</em> ';
                }

                ?>
        <?php
        }

        woocommerce_review_display_rating();
    }
}

// woocommerce proceed to checkout hook function
if( !function_exists('wellnez_woocommerce_button_proceed_to_checkout') ) {
    function wellnez_woocommerce_button_proceed_to_checkout() {
        echo '<a href="'.esc_url( wc_get_checkout_url() ).'" class="checkout-button button alt wc-forward vs-btn shadow-none">';
            echo esc_html__( 'Proceed to checkout', 'wellnez' );
        echo '</a>';
    }
}

// wellnez woocommerce cross sell display hook function
if( !function_exists('wellnez_woocommerce_cross_sell_display') ) {
    function wellnez_woocommerce_cross_sell_display( ){
        woocommerce_cross_sell_display();
    }
}

// wellnez minicart view cart button hook function
if( !function_exists('wellnez_minicart_view_cart_button') ) {
    function wellnez_minicart_view_cart_button() {
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button checkout wc-forward vs-btn style1">' . esc_html__( 'View cart', 'wellnez' ) . '</a>';
    }
}

// wellnez minicart checkout button hook function
if( !function_exists('wellnez_minicart_checkout_button') ) {
    function wellnez_minicart_checkout_button() {
        echo '<a href="' .esc_url( wc_get_checkout_url() ) . '" class="button wc-forward vs-btn style1">' . esc_html__( 'Checkout', 'wellnez' ) . '</a>';
    }
}

// wellnez woocommerce before checkout form
if( !function_exists('wellnez_woocommerce_before_checkout_form') ) {
    function wellnez_woocommerce_before_checkout_form() {
        echo '<div class="row">';
            if ( ! is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder') ) {
                echo '<div class="col-lg-12">';
                    woocommerce_checkout_login_form();
                echo '</div>';
            }

            echo '<div class="col-lg-12">';
                woocommerce_checkout_coupon_form();
            echo '</div>';
        echo '</div>';
    }
}

// add to cart button
function woocommerce_template_loop_add_to_cart( $args = array() ) {
    global $product;

		if ( $product ) {
			$defaults = array(
				'quantity'   => 1,
				'class'      => implode(
					' ',
					array_filter(
						array(
							'icon-btn',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);

			$args = wp_parse_args( $args, $defaults );

			if ( isset( $args['attributes']['aria-label'] ) ) {
				$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
            }
        }

        echo sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'cart-button' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            '<i class="far fa-shopping-cart"></i>'
        );
}

// add to cart button Text
function woocommerce_template_loop_add_to_cart_text_style( $args = array() ) {
    global $product;

		if ( $product ) {
			$defaults = array(
				'quantity'   => 1,
				'class'      => implode(
					' ',
					array_filter(
						array(
							'icon-btn',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);

			$args = wp_parse_args( $args, $defaults );

			if ( isset( $args['attributes']['aria-label'] ) ) {
				$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
            }
        }

        echo sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'cart-button' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            esc_html__( 'Add To Cart', 'wellnez' )
        );
}

// product searchform
add_filter( 'get_product_search_form' , 'wellnez_custom_product_searchform' );
function wellnez_custom_product_searchform( $form ) {

    $form = '<form class="search-form" role="search" method="get" action="' . esc_url( home_url( '/'  ) ) . '">
        <label class="screen-reader-text" for="s">' . __( 'Search for:', 'wellnez' ) . '</label>
        <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search', 'wellnez' ) . '" />
        <button class="submit-btn" type="submit"><i class="far fa-eye"></i></button>
        <input type="hidden" name="post_type" value="product" />
    </form>';

    return $form;
}

// cart empty message
add_action('woocommerce_cart_is_empty','wellnez_wc_empty_cart_message',10);
function wellnez_wc_empty_cart_message( ) {
    echo '<h3 class="cart-empty d-none">'.esc_html__('Your cart is currently empty.','wellnez').'</h3>';
}