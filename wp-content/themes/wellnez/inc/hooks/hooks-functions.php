<?php
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

    // Block direct access
    if( ! defined( 'ABSPATH' ) ){
        exit();
    }


    // preloader hook function
    if( ! function_exists( 'wellnez_preloader_wrap_cb' ) ) {
        function wellnez_preloader_wrap_cb() {
            $preloader_display              =  wellnez_opt('wellnez_display_preloader');

            if( class_exists('ReduxFramework') ){
                if( $preloader_display ){
                    echo '<div class="preloader">';
                        echo '<button class="vs-btn preloaderCls">'.esc_html__( 'Cancel Preloader', 'wellnez' ).'</button>';
                        echo '<div class="preloader-inner">';
                            echo '<span class="loader"></span>';
                        echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="preloader">';
                    echo '<button class="vs-btn preloaderCls">'.esc_html__( 'Cancel Preloader', 'wellnez' ).'</button>';
                    echo '<div class="preloader-inner">';
                        echo '<span class="loader"></span>';
                    echo '</div>';
                echo '</div>';
            }
        }
    }

    // Header Hook function
    if( !function_exists('wellnez_header_cb') ) {
        function wellnez_header_cb( ) {
            get_template_part('templates/header');
            get_template_part('templates/header-menu-bottom');
        }
    }

    // back top top hook function
    if( ! function_exists( 'wellnez_back_to_top_cb' ) ) {
        function wellnez_back_to_top_cb( ) {
            $backtotop_trigger = wellnez_opt('wellnez_display_bcktotop');
            $custom_bcktotop   = wellnez_opt('wellnez_custom_bcktotop');
            $custom_bcktotop_icon   = wellnez_opt('wellnez_custom_bcktotop_icon');
            if( class_exists( 'ReduxFramework' ) ) {
                if( $backtotop_trigger ) {
                    if( $custom_bcktotop ) {
                        echo '<!-- Back to Top Button -->';
                        echo '<a href="#" class="scrollToTop scroll-btn">';
                            echo '<i class="fa '.esc_attr( $custom_bcktotop_icon ).'"></i>';
                        echo '</a>';
                        echo '<!-- End of Back to Top Button -->';
                    } else {
                        echo '<!-- Back to Top Button -->';
                        echo '<a href="#" class="scrollToTop scroll-btn">';
                            echo '<i class="far fa-arrow-up"></i>';
                        echo '</a>';
                        echo '<!-- End of Back to Top Button -->';
                    }
                }
            }

        }
    }

    // Blog Start Wrapper Function
    if( !function_exists('wellnez_blog_start_wrap_cb') ) {
        function wellnez_blog_start_wrap_cb() {
            echo '<section class="vs-blog-wrapper space-top space-extra-bottom arrow-wrap">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'wellnez-blog-sidebar' ) ){
                        $wellnez_gutter_class = 'gx-50';
                    }else{
                        $wellnez_gutter_class = '';
                    }
                    echo '<div class="row '.esc_attr( $wellnez_gutter_class ).'">';
        }
    }

    // Blog End Wrapper Function
    if( !function_exists('wellnez_blog_end_wrap_cb') ) {
        function wellnez_blog_end_wrap_cb() {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // Blog Column Start Wrapper Function
    if( !function_exists('wellnez_blog_col_start_wrap_cb') ) {
        function wellnez_blog_col_start_wrap_cb() {
            if( class_exists('ReduxFramework') ) {
                $wellnez_blog_sidebar = wellnez_opt('wellnez_blog_sidebar');
                if( $wellnez_blog_sidebar == '2' && is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 order-lg-last">';
                } elseif( $wellnez_blog_sidebar == '3' && is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 col-xxl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 col-xxl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }
    // Blog Column End Wrapper Function
    if( !function_exists('wellnez_blog_col_end_wrap_cb') ) {
        function wellnez_blog_col_end_wrap_cb() {
            echo '</div>';
        }
    }

    // Blog Sidebar
    if( !function_exists('wellnez_blog_sidebar_cb') ) {
        function wellnez_blog_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_blog_sidebar = wellnez_opt('wellnez_blog_sidebar');
            } else {
                $wellnez_blog_sidebar = 2;
            }
            if( $wellnez_blog_sidebar != 1 && is_active_sidebar('wellnez-blog-sidebar') ) {
                // Sidebar
                get_sidebar();
            }
        }
    }


    if( !function_exists('wellnez_blog_details_sidebar_cb') ) {
        function wellnez_blog_details_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_blog_single_sidebar = wellnez_opt('wellnez_blog_single_sidebar');
            } else {
                $wellnez_blog_single_sidebar = 4;
            }
            if( $wellnez_blog_single_sidebar != 1 ) {
                // Sidebar
                get_sidebar();
            }

        }
    }

    // Blog Pagination Function
    if( !function_exists('wellnez_blog_pagination_cb') ) {
        function wellnez_blog_pagination_cb( ) {
            get_template_part('templates/pagination');
        }
    }

    // Blog Content Function
    if( !function_exists('wellnez_blog_content_cb') ) {
        function wellnez_blog_content_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_blog_grid = wellnez_opt('wellnez_blog_grid');
            } else {
                $wellnez_blog_grid = '1';
            }

            if( $wellnez_blog_grid == '1' ) {
                $wellnez_blog_grid_class = 'col-lg-12';
            } elseif( $wellnez_blog_grid == '2' ) {
                $wellnez_blog_grid_class = 'col-sm-6';
            } else {
                $wellnez_blog_grid_class = 'col-lg-4 col-sm-6';
            }

            echo '<div class="row">';
                if( have_posts() ) {
                    while( have_posts() ) {
                        the_post();
                        echo '<div class="'.esc_attr($wellnez_blog_grid_class).'">';
                            get_template_part('templates/content',get_post_format());
                        echo '</div>';
                    }
                    wp_reset_postdata();
                } else{
                    get_template_part('templates/content','none');
                }
            echo '</div>';
        }
    }

    // footer content Function
    if( !function_exists('wellnez_footer_content_cb') ) {
        function wellnez_footer_content_cb( ) {

            if( class_exists('ReduxFramework') && did_action( 'elementor/loaded' )  ){
                if( is_page() || is_page_template('template-builder.php') ) {
                    $post_id = get_the_ID();

                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                    // Get the settings model for current post
                    $page_settings_model = $page_settings_manager->get_model( $post_id );

                    // Retrieve the Footer Style
                    $footer_settings = $page_settings_model->get_settings( 'wellnez_footer_style' );

                    // Footer Local
                    $footer_local = $page_settings_model->get_settings( 'wellnez_footer_builder_option' );

                    // Footer Enable Disable
                    $footer_enable_disable = $page_settings_model->get_settings( 'wellnez_footer_choice' );

                    if( $footer_enable_disable == 'yes' ){
                        if( $footer_settings == 'footer_builder' ) {
                            // local options
                            $wellnez_local_footer = get_post( $footer_local );
                            echo '<footer>';
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $wellnez_local_footer->ID );
                            echo '</footer>';
                        } else {
                            // global options
                            $wellnez_footer_builder_trigger = wellnez_opt('wellnez_footer_builder_trigger');
                            if( $wellnez_footer_builder_trigger == 'footer_builder' ) {
                                echo '<footer>';
                                $wellnez_global_footer_select = get_post( wellnez_opt( 'wellnez_footer_builder_select' ) );
                                $footer_post = get_post( $wellnez_global_footer_select );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                                echo '</footer>';
                            } else {
                                // wordpress widgets
                                wellnez_footer_global_option();
                            }
                        }
                    }
                } else {
                    // global options
                    $wellnez_footer_builder_trigger = wellnez_opt('wellnez_footer_builder_trigger');
                    if( $wellnez_footer_builder_trigger == 'footer_builder' ) {
                        echo '<footer>';
                        $wellnez_global_footer_select = get_post( wellnez_opt( 'wellnez_footer_builder_select' ) );
                        $footer_post = get_post( $wellnez_global_footer_select );
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                        echo '</footer>';
                    } else {
                        // wordpress widgets
                        wellnez_footer_global_option();
                    }
                }
            } else {
                echo '<div class="footer-copyright text-center bg-black py-3 link-inherit z-index-common">';
                    echo '<div class="container">';
                        echo '<p class="mb-0 text-white">'.sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a href="%s">%s</a> All Rights Reserved by <a href="%s">%s</a>',date('Y'),esc_url('#'),__( 'Wellnez.','wellnez' ),esc_url('#'),__( 'Vecuro', 'wellnez' ) ).'</p>';
                    echo '</div>';
                echo '</div>';
            }

        }
    }

    // blog details wrapper start hook function
    if( !function_exists('wellnez_blog_details_wrapper_start_cb') ) {
        function wellnez_blog_details_wrapper_start_cb( ) {
            echo '<section class="vs-blog-wrapper blog-details space-top space-extra-bottom">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'wellnez-blog-sidebar' ) ){
                        $wellnez_gutter_class = 'gx-50';
                    }else{
                        $wellnez_gutter_class = '';
                    }
                    echo '<div class="row '.esc_attr( $wellnez_gutter_class ).'">';
        }
    }

    // blog details column wrapper start hook function
    if( !function_exists('wellnez_blog_details_col_start_cb') ) {
        function wellnez_blog_details_col_start_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_blog_single_sidebar = wellnez_opt('wellnez_blog_single_sidebar');
                if( $wellnez_blog_single_sidebar == '2' && is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 order-last">';
                } elseif( $wellnez_blog_single_sidebar == '3' && is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 col-xxl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('wellnez-blog-sidebar') ) {
                    echo '<div class="col-lg-8 col-xxl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }

    // blog details post meta hook function
    if( !function_exists('wellnez_blog_post_meta_cb') ) {
        function wellnez_blog_post_meta_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_display_post_date      =  wellnez_opt('wellnez_display_post_date');
                $wellnez_display_post_author    =  wellnez_opt('wellnez_display_post_author');
                $wellnez_display_post_comment   =  wellnez_opt('wellnez_display_post_comment');

            } else {
                $wellnez_display_post_date      = '1';
                $wellnez_display_post_author    = '1';
                $wellnez_display_post_comment   = '1';
            }

            echo '<!-- Blog Meta -->';
            echo '<div class="blog-meta">';
                if( $wellnez_display_post_author ){
                    echo wellnez_anchor_tag( array(
                        "text"  => wp_kses_post( '<i class="fas fa-user"></i>'.ucwords( get_the_author() ) ),
                        "url"   => esc_url( get_author_posts_url( get_the_author_meta('ID') ) )
                    ) );
                }
                if( $wellnez_display_post_date ){
                    echo '<span><a href="'.esc_url( wellnez_blog_date_permalink() ).'"><i class="fas fa-calendar-alt"></i>';
                        echo '<time datetime="'.esc_attr( get_the_date( DATE_W3C ) ).'">'.esc_html( get_the_date() ).'</time>';
                    echo '</a></span>';
                }
                
                if( $wellnez_display_post_comment ){
                    if( get_comments_number() == 1 ){
                        $comment_text = __( ' Comment', 'wellnez' );
                    }else{
                        $comment_text = __( ' Comments', 'wellnez' );
                    }
                    echo '<span><a href="'.esc_url( get_comments_link( get_the_ID() ) ).'"><i class="far fa-comments"></i>'.esc_html( get_comments_number() ).''.$comment_text.'</a></span>';
                }

                

            echo '</div>';

        }
    }

    // blog details share options hook function
    if( !function_exists('wellnez_blog_details_share_options_cb') ) {
        function wellnez_blog_details_share_options_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_post_details_share_options = wellnez_opt('wellnez_post_details_share_options');
            } else {
                $wellnez_post_details_share_options = false;
            }
            if( function_exists( 'wellnez_social_sharing_buttons' ) && $wellnez_post_details_share_options ) {
                echo '<div class="col-md-7 d-sm-flex justify-content-md-end align-items-center">';
                    echo '<span class="share-links-title">'.esc_html__( 'Social Network:', 'wellnez' ).'</span>';
                    echo '<ul class="social-links">';
                        echo wellnez_social_sharing_buttons();
                    echo '</ul>';
                    echo '<!-- End Social Share -->';
                echo '</div>';
            }
        }
    }

    // blog details post navigation hook function
    if( !function_exists('wellnez_blog_details_post_navigation_cb') ) {
        function wellnez_blog_details_post_navigation_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_post_details_post_navigation = wellnez_opt('wellnez_post_details_post_navigation');
            } else {
                $wellnez_post_details_post_navigation = true;
            }

            $prevpost = get_previous_post();
            $nextpost = get_next_post();

            if( $wellnez_post_details_post_navigation && ! empty( $prevpost ) || !empty( $nextpost ) ) {
                echo '<!-- Post Navigation -->';
                echo '<div class="post-pagination">';
                    echo '<div class="row justify-content-between align-items-center">';
                        echo '<div class="col-6">';
                        if( ! empty( $prevpost ) ) {
                            echo '<!-- Nav Previous -->';
                            
                            echo '<div class="post-pagi-box prev">';
                                echo '<a href="'.esc_url( get_permalink( $prevpost->ID ) ).'">';
                                    echo  '<i class="fas fa-angle-double-left"></i>';
                                    echo esc_html__( 'Previous Post', 'wellnez' );
                                echo '</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<!-- End Nav Previous -->';
                        
                        echo '<div class="col-6">';
                        if( !empty( $nextpost ) ) {
                                echo '<div class="post-pagi-box next">';
                                    echo '<a href="'.esc_url( get_permalink( $nextpost->ID ) ).'">';
                                        echo  '<i class="fas fa-angle-double-right"></i>';
                                        echo esc_html__( 'Next Post', 'wellnez' );
                                    echo '</a>';
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                echo '<!-- End Post Navigation -->';
            }
        }
    }
    // blog details author bio hook function
    if( !function_exists('wellnez_blog_details_author_bio_cb') ) {
        function wellnez_blog_details_author_bio_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $postauthorbox =  wellnez_opt( 'wellnez_post_details_author_desc_trigger' );
            } else {
                $postauthorbox = '1';
            }
            if( !empty( get_the_author_meta('description')  ) && $postauthorbox == '1' ) {
                echo '<!-- Post Author -->';
                echo '<div class="blog-author">';
                    echo '<!-- Author Thumb -->';
                    echo '<div class="media-img">';
                        echo wellnez_img_tag( array(
                            "url"   => esc_url( get_avatar_url( get_the_author_meta('ID'), array(
                            "size"  => 189
                            ) ) ),
                        ) );
                    echo '</div>';
                    echo '<!-- End of Author Thumb -->';
                    echo '<div class="media-body">';
                        echo wellnez_heading_tag( array(
                            "tag"   => "h3",
                            "text"  => wellnez_anchor_tag( array(
                                "text"  => esc_html( ucwords( get_the_author() ) ),
                                "url"   => esc_url( get_author_posts_url( get_the_author_meta('ID') ) ),
                                'class' => 'text-reset',
                            ) ),
                            'class' => 'author-name h4',
                        ) );
                        
                        if( ! empty( get_the_author_meta('description') ) ) {
                            echo '<p class="author-text">';
                                echo esc_html( get_the_author_meta('description') );
                            echo '</p>';
                        };
                        if( function_exists( 'wellnez_social_icon' ) ){
                            wellnez_social_icon();
                        }
                    echo '</div>';
                echo '</div>';
                echo '<!-- End of Post Author -->';
            }

        }
    }

    // Blog Details Comments hook function
    if( !function_exists('wellnez_blog_details_comments_cb') ) {
        function wellnez_blog_details_comments_cb( ) {
            if ( ! comments_open() ) {
                echo '<div class="blog-comment-area">';
                    echo wellnez_heading_tag( array(
                        "tag"   => "h3",
                        "text"  => esc_html__( 'Comments are closed', 'wellnez' ),
                        "class" => "inner-title"
                    ) );
                echo '</div>';
            }

            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        }
    }

    // Blog Details Related Post hook function
    if( !function_exists('wellnez_blog_details_related_post_cb') ) {
        function wellnez_blog_details_related_post_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_excerpt_length = '4';
                $wellnez_post_details_related_post = wellnez_opt('wellnez_post_details_related_post');
            } else {
                $wellnez_excerpt_length = '4';
                $wellnez_post_details_related_post = false;
            }
            $relatedpost = new WP_Query( array(
                "post_type"         => "post",
                "posts_per_page"    => "3",
                "category__in"      => wp_get_post_categories(get_the_ID()),
                "post__not_in"      =>  array( get_the_ID() )
            ) );
            if( $relatedpost->have_posts() && $wellnez_post_details_related_post ) {
                echo '<!-- Related Post -->';
                echo '<div class="related-post-wrapper pt-40">';
                    echo '<h2 class="inner-title1 h1">'.esc_html__( 'Relatetd', 'wellnez' ).' <span class="text-theme">'.esc_html__( 'Post', 'wellnez' ).'</span></h2>';
                    echo '<div class="row text-center">';
                        while( $relatedpost->have_posts() ) {
                            $relatedpost->the_post();
                            echo '<div class="col-lg-4">';
                                echo '<!-- Single Post -->';
                                echo '<div class="vs-blog blog-grid">';
                                    if( has_post_thumbnail(  ) ){
                                        the_post_thumbnail( 'wellnez-related-post-size', [ 'class'  => 'w-100' ] );
                                    }
                                    echo '<div class="blog-content">';
                                        if( get_the_title() ){
                                            echo '<!-- Post Title -->';
                                            echo '<h4 class="blog-title fw-semibold"><a href="'.esc_url( get_permalink() ).'">'.esc_html( wp_trim_words( get_the_title(), '3', '' ) ).'</a></h4>';
                                            echo '<!-- End Post Title -->';
                                        }
                                        echo '<span><a href="'.esc_url( wellnez_blog_date_permalink() ).'">';
                                            echo '<time datetime="'.esc_attr( get_the_date( DATE_W3C ) ).'">'.esc_html( get_the_date() ).'</time>';
                                        echo '</a></span>';
                                        echo '<span>';
                                            echo wellnez_getPostViews( get_the_ID() );
                                            echo esc_html__( ' Views', 'wellnez' );
                                        echo '</span>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<!-- End Single Post -->';
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    echo '</div>';
                echo '</div>';
                echo '<!-- End Related Post -->';
            }
        }
    }

    // Blog Details Column end hook function
    if( !function_exists('wellnez_blog_details_col_end_cb') ) {
        function wellnez_blog_details_col_end_cb( ) {
            echo '</div>';
        }
    }

    // Blog Details Wrapper end hook function
    if( !function_exists('wellnez_blog_details_wrapper_end_cb') ) {
        function wellnez_blog_details_wrapper_end_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page start wrapper hook function
    if( !function_exists('wellnez_page_start_wrap_cb') ) {
        function wellnez_page_start_wrap_cb( ) {
            if( is_page( 'cart' ) ){
                $section_class = "vs-cart-wrapper space-top space-extra-bottom";
            }elseif( is_page( 'checkout' ) ){
                $section_class = "vs-checkout-wrapper space-top space-extra-bottom";
            }else{
                $section_class = "space-top space-extra-bottom";
            }
            echo '<section class="'.esc_attr( $section_class ).'">';
                echo '<div class="container">';
                    echo '<div class="row">';
        }
    }

    // page wrapper end hook function
    if( !function_exists('wellnez_page_end_wrap_cb') ) {
        function wellnez_page_end_wrap_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page column wrapper start hook function
    if( !function_exists('wellnez_page_col_start_wrap_cb') ) {
        function wellnez_page_col_start_wrap_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_page_sidebar = wellnez_opt('wellnez_page_sidebar');
            }else {
                $wellnez_page_sidebar = '1';
            }
            if( $wellnez_page_sidebar == '2' && is_active_sidebar('wellnez-page-sidebar') ) {
                echo '<div class="col-lg-8 order-last">';
            } elseif( $wellnez_page_sidebar == '3' && is_active_sidebar('wellnez-page-sidebar') ) {
                echo '<div class="col-lg-8">';
            } else {
                echo '<div class="col-lg-12">';
            }

        }
    }

    // page column wrapper end hook function
    if( !function_exists('wellnez_page_col_end_wrap_cb') ) {
        function wellnez_page_col_end_wrap_cb( ) {
            echo '</div>';
        }
    }

    // page sidebar hook function
    if( !function_exists('wellnez_page_sidebar_cb') ) {
        function wellnez_page_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $wellnez_page_sidebar = wellnez_opt('wellnez_page_sidebar');
            }else {
                $wellnez_page_sidebar = '1';
            }

            if( class_exists('ReduxFramework') ) {
                $wellnez_page_layoutopt = wellnez_opt('wellnez_page_layoutopt');
            }else {
                $wellnez_page_layoutopt = '3';
            }

            if( $wellnez_page_layoutopt == '1' && $wellnez_page_sidebar != 1 ) {
                get_sidebar('page');
            } elseif( $wellnez_page_layoutopt == '2' && $wellnez_page_sidebar != 1 ) {
                get_sidebar();
            }
        }
    }

    // page content hook function
    if( !function_exists('wellnez_page_content_cb') ) {
        function wellnez_page_content_cb( ) {
            if(  class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_page('wishlist') || is_account_page() )  ) {
                echo '<div class="woocommerce--content">';
            } else {
                echo '<div class="page--content clearfix">';
            }

                the_content();

                // Link Pages
                wellnez_link_pages();

            echo '</div>';
            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        }
    }
    if( !function_exists('wellnez_blog_post_thumb_cb') ) {
        function wellnez_blog_post_thumb_cb( ) {
            if( get_post_format() ) {
                $format = get_post_format();
            }else{
                $format = 'standard';
            }

            $wellnez_post_slider_thumbnail = wellnez_meta( 'post_format_slider' );

            if( !empty( $wellnez_post_slider_thumbnail ) ){
                echo '<div class="blog-img">';
                    echo '<div class="vs-blog-carousel" data-arrows="true" data-slide-show="1" data-fade="true">';
                        foreach( $wellnez_post_slider_thumbnail as $single_image ){
                            if( ! is_single() ){
                                echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                            }
                            echo wellnez_img_tag( array(
                                'url'   => esc_url( $single_image )
                            ) );
                            if( ! is_single() ){
                                echo '</a>';
                            }
                        }
                    echo '</div>';
                    wellnez_blog_category();
                echo '</div>';
            }elseif( has_post_thumbnail() && $format == 'standard' ) {
                echo '<!-- Post Thumbnail -->';
                echo '<div class="blog-img">';
                    if( ! is_single() ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                    }
                    the_post_thumbnail();
                    if( ! is_single() ){
                        echo '</a>';
                    }
                    wellnez_blog_category();
                echo '</div>';
                echo '<!-- End Post Thumbnail -->';
            }elseif( $format == 'video' ){
                if( has_post_thumbnail() && !empty ( wellnez_meta( 'post_format_video' ) ) ){
                    echo '<div class="blog-video blog-img">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            the_post_thumbnail();
                        if( ! is_single() ){
                            echo '</a>';
                        }
                        echo '<a href="'.esc_url( wellnez_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';
                          echo '<i class="fas fa-play"></i>';
                        echo '</a>';
                        wellnez_blog_category();
                    echo '</div>';
                }elseif( ! has_post_thumbnail() && ! is_single() ){
                    echo '<div class="blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            echo wellnez_embedded_media( array( 'video', 'iframe' ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                        wellnez_blog_category();
                    echo '</div>';
                }
            }elseif( $format == 'audio' ){
                $wellnez_audio = wellnez_meta( 'post_format_audio' );
                if( !empty( $wellnez_audio ) ){
                    echo '<div class="blog-audio blog-image">';
                            echo wp_oembed_get( $wellnez_audio );
                            wellnez_blog_category();
                    echo '</div>';
                }elseif( !is_single() ){
                    echo '<div class="blog-audio blog-image">';
                            echo wellnez_embedded_media( array( 'audio', 'iframe' ) );
                            wellnez_blog_category();
                    echo '</div>';
                }
            }

        }
    }

    if( !function_exists( 'wellnez_blog_post_content_cb' ) ) {
        function wellnez_blog_post_content_cb( ) {
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
                'sup'       => array(),
                'sub'       => array(),
            );
            echo '<!-- blog-content -->';

            echo '<div class="blog-content ">';

                if( ! is_single() ){
                    echo '<!-- Post Title -->';
                    echo '<h2 class="blog-title"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title(), $allowhtml ).'</a></h2>';
                    echo '<!-- End Post Title -->';
                }

                // Blog Post Meta
                do_action( 'wellnez_blog_post_meta' );

                // Excerpt And Read More Button
                do_action( 'wellnez_blog_postexcerpt_read_content' );

            echo '</div>';
            echo '<!-- End Post Content -->';
        }
    }

    if( ! function_exists( 'wellnez_blog_postexcerpt_read_content_cb') ) {
        function wellnez_blog_postexcerpt_read_content_cb( ) {
            if( class_exists( 'ReduxFramework' ) ) {
                $wellnez_excerpt_length = wellnez_opt('wellnez_blog_postExcerpt');
            } else {
                $wellnez_excerpt_length = '24';
            }
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            );

            if( class_exists( 'ReduxFramework' ) ) {
                $wellnez_blog_admin = wellnez_opt( 'wellnez_blog_post_author' );
                $wellnez_blog_readmore_setting_val = wellnez_opt('wellnez_blog_readmore_setting');
                if( $wellnez_blog_readmore_setting_val == 'custom' ) {
                    $wellnez_blog_readmore_setting = wellnez_opt('wellnez_blog_custom_readmore');
                } else {
                    $wellnez_blog_readmore_setting = __( 'Read More', 'wellnez' );
                }
            } else {
                $wellnez_blog_readmore_setting = __( 'Read More', 'wellnez' );
                $wellnez_blog_admin = true;
            }

            echo '<!-- Post Summary -->';
                echo wellnez_paragraph_tag( array(
                    "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $wellnez_excerpt_length, '' ), $allowhtml ),
                    "class" => 'blog-text',
                ) );
            echo '<!-- End Post Summary -->';

            if( $wellnez_blog_admin || !empty( $wellnez_blog_readmore_setting ) ){
                if( !empty( $wellnez_blog_readmore_setting ) ){
                    echo '<!-- Button -->';
                        echo '<a href="'.esc_url( get_permalink() ).'" class="vs-btn">'.esc_html( $wellnez_blog_readmore_setting ).'</a>';
                    echo '<!-- End Button -->';
                }
            }
        }
    }