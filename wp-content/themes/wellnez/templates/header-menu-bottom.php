<?php
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit();
    }

    if( defined( 'CMB2_LOADED' )  ){
        if( !empty( wellnez_meta('page_breadcrumb_area') ) ) {
            $wellnez_page_breadcrumb_area  = wellnez_meta('page_breadcrumb_area');
        } else {
            $wellnez_page_breadcrumb_area = '1';
        }
    }else{
        $wellnez_page_breadcrumb_area = '1';
    }

    $allowhtml = array(
        'p'         => array(
            'class'     => array()
        ),
        'span'      => array(
            'class'     => array(),
        ),
        'a'         => array(
            'href'      => array(),
            'title'     => array()
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
        'sub'       => array(),
        'sup'       => array(),
    );

    if(  is_page() || is_page_template( 'template-builder.php' )  ) {
        if( $wellnez_page_breadcrumb_area == '1' ) {
            echo '<!-- Page title -->';
            echo '<div class="breadcumb-wrapper background-image">';
                echo '<div class="container z-index-common">';
                    echo '<div class="breadcumb-content">';
                        if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {
                            if( wellnez_meta('page_breadcrumb_settings') == 'page' ) {
                                $wellnez_page_title_switcher = wellnez_meta('page_title');
                            } elseif( wellnez_opt('wellnez_page_title_switcher') == true ) {
                                $wellnez_page_title_switcher = wellnez_opt('wellnez_page_title_switcher');
                            }else{
                                $wellnez_page_title_switcher = '1';
                            }
                        } else {
                            $wellnez_page_title_switcher = '1';
                        }

                        if( $wellnez_page_title_switcher == '1' ){
                            if( class_exists( 'ReduxFramework' ) ){
                                $wellnez_page_title_tag    = wellnez_opt('wellnez_page_title_tag');
                            }else{
                                $wellnez_page_title_tag    = 'h1';
                            }

                            if( defined( 'CMB2_LOADED' )  ){
                                if( !empty( wellnez_meta('page_title_settings') ) ) {
                                    $wellnez_custom_title = wellnez_meta('page_title_settings');
                                } else {
                                    $wellnez_custom_title = 'default';
                                }
                            }else{
                                $wellnez_custom_title = 'default';
                            }

                            if( $wellnez_custom_title == 'default' ) {
                                echo wellnez_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $wellnez_page_title_tag ),
                                        "text"  => esc_html( get_the_title( ) ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            } else {
                                echo wellnez_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $wellnez_page_title_tag ),
                                        "text"  => esc_html( wellnez_meta('custom_page_title') ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            }

                        }
                        if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {

                            if( wellnez_meta('page_breadcrumb_settings') == 'page' ) {
                                $wellnez_breadcrumb_switcher = wellnez_meta('page_breadcrumb_trigger');
                            } else {
                                $wellnez_breadcrumb_switcher = wellnez_opt('wellnez_enable_breadcrumb');
                            }

                        } else {
                            $wellnez_breadcrumb_switcher = '1';
                        }

                        if( $wellnez_breadcrumb_switcher == '1' && (  is_page() || is_page_template( 'template-builder.php' ) )) {
                            wellnez_breadcrumbs(
                                array(
                                    'breadcrumbs_classes' => 'nav',
                                )
                            );
                        }
                        echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<!-- End of Page title -->';
        }
    } else {
        echo '<!-- Page title -->';
        echo '<div class="breadcumb-wrapper background-image">';
            echo '<div class="container z-index-common">';
                echo '<div class="breadcumb-content">';
                    if( class_exists( 'ReduxFramework' )  ){
                        $wellnez_page_title_switcher  = wellnez_opt('wellnez_page_title_switcher');
                    }else{
                        $wellnez_page_title_switcher = '1';
                    }

                    if( $wellnez_page_title_switcher ){
                        if( class_exists( 'ReduxFramework' ) ){
                            $wellnez_page_title_tag    = wellnez_opt('wellnez_page_title_tag');
                        }else{
                            $wellnez_page_title_tag    = 'h1';
                        }
                        if( class_exists('woocommerce') && is_shop() ) {
                            echo wellnez_heading_tag(
                                array(
                                    "tag"   => esc_attr( $wellnez_page_title_tag ),
                                    "text"  => wp_kses( woocommerce_page_title( false ), $allowhtml ),
                                    'class' => 'breadcumb-title'
                                )
                            );
                        }elseif ( is_archive() ){
                            echo wellnez_heading_tag(
                                array(
                                    "tag"   => esc_attr( $wellnez_page_title_tag ),
                                    "text"  => wp_kses( get_the_archive_title(), $allowhtml ),
                                    'class' => 'breadcumb-title'
                                )
                            );
                        }elseif ( is_home() ){
                            $wellnez_blog_page_title_setting = wellnez_opt('wellnez_blog_page_title_setting');
                            $wellnez_blog_page_title_switcher = wellnez_opt('wellnez_blog_page_title_switcher');
                            $wellnez_blog_page_custom_title = wellnez_opt('wellnez_blog_page_custom_title');
                            if( class_exists('ReduxFramework') ){
                                if( $wellnez_blog_page_title_switcher ){
                                    echo wellnez_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $wellnez_page_title_tag ),
                                            "text"  => !empty( $wellnez_blog_page_custom_title ) && $wellnez_blog_page_title_setting == 'custom' ? esc_html( $wellnez_blog_page_custom_title) : esc_html__( 'Blog', 'wellnez' ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }
                            }else{
                                echo wellnez_heading_tag(
                                    array(
                                        "tag"   => "h1",
                                        "text"  => esc_html__( 'Blog', 'wellnez' ),
                                        'class' => 'breadcumb-title',
                                    )
                                );
                            }
                        }elseif( is_search() ){
                            echo wellnez_heading_tag(
                                array(
                                    "tag"   => esc_attr( $wellnez_page_title_tag ),
                                    "text"  => esc_html__( 'Search Result', 'wellnez' ),
                                    'class' => 'breadcumb-title'
                                )
                            );
                        }elseif( is_404() ){
                            echo wellnez_heading_tag(
                                array(
                                    "tag"   => esc_attr( $wellnez_page_title_tag ),
                                    "text"  => esc_html__( '404 PAGE', 'wellnez' ),
                                    'class' => 'breadcumb-title'
                                )
                            );
                        }elseif( is_singular( 'product' ) ){
                            $posttitle_position  = wellnez_opt('wellnez_product_details_title_position');
                            $postTitlePos = false;
                            if( class_exists( 'ReduxFramework' ) ){
                                if( $posttitle_position && $posttitle_position != 'header' ){
                                    $postTitlePos = true;
                                }
                            }else{
                                $postTitlePos = false;
                            }

                            if( $postTitlePos != true ){
                                echo wellnez_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $wellnez_page_title_tag ),
                                        "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            } else {
                                if( class_exists( 'ReduxFramework' ) ){
                                    $wellnez_post_details_custom_title  = wellnez_opt('wellnez_product_details_custom_title');
                                }else{
                                    $wellnez_post_details_custom_title = __( 'Shop Details','wellnez' );
                                }

                                if( !empty( $wellnez_post_details_custom_title ) ) {
                                    echo wellnez_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $wellnez_page_title_tag ),
                                            "text"  => wp_kses( $wellnez_post_details_custom_title, $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }
                            }
                        }else{
                            $posttitle_position  = wellnez_opt('wellnez_post_details_title_position');
                            $postTitlePos = false;
                            if( is_single() ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    if( $posttitle_position && $posttitle_position != 'header' ){
                                        $postTitlePos = true;
                                    }
                                }else{
                                    $postTitlePos = false;
                                }
                            }
                            if( is_singular( 'product' ) ){
                                $posttitle_position  = wellnez_opt('wellnez_product_details_title_position');
                                $postTitlePos = false;
                                if( class_exists( 'ReduxFramework' ) ){
                                    if( $posttitle_position && $posttitle_position != 'header' ){
                                        $postTitlePos = true;
                                    }
                                }else{
                                    $postTitlePos = false;
                                }
                            }

                            if( $postTitlePos != true ){
                                echo wellnez_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $wellnez_page_title_tag ),
                                        "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            } else {
                                if( class_exists( 'ReduxFramework' ) ){
                                    $wellnez_post_details_custom_title  = wellnez_opt('wellnez_post_details_custom_title');
                                }else{
                                    $wellnez_post_details_custom_title = __( 'Blog Details','wellnez' );
                                }

                                if( !empty( $wellnez_post_details_custom_title ) ) {
                                    echo wellnez_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $wellnez_page_title_tag ),
                                            "text"  => wp_kses( $wellnez_post_details_custom_title, $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }
                            }
                        }
                    }
                    if( class_exists('ReduxFramework') ) {
                        $wellnez_breadcrumb_switcher = wellnez_opt( 'wellnez_enable_breadcrumb' );
                    } else {
                        $wellnez_breadcrumb_switcher = '1';
                    }
                    if( $wellnez_breadcrumb_switcher == '1' ) {
                        wellnez_breadcrumbs(
                            array(
                                'breadcrumbs_classes' => 'nav',
                            )
                        );
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';
        echo '<!-- End of Page title -->';
    }