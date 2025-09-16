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

    if( class_exists( 'ReduxFramework' ) ) {
        $wellnezerrortitle   = wellnez_opt( 'wellnez_fof_error_number' );
        $wellnez404title     = wellnez_opt( 'wellnez_fof_title' );
        $wellnez404subtitle  = wellnez_opt( 'wellnez_fof_subtitle' );
        $wellnez404btntext   = wellnez_opt( 'wellnez_fof_btn_text' );
        $wellnez404placehol  = wellnez_opt( 'wellnez_fof_placeholder_text' );
    } else {
        $wellnezerrortitle   = __( '404', 'wellnez' );
        $wellnez404title     = __( 'OOOPS, PAGE NOT FOUND', 'wellnez' );
        $wellnez404subtitle  = __( 'We Can\'t Seem to find the page you\'re looking for.', 'wellnez' );
        $wellnez404btntext   = __( 'Return To Home', 'wellnez' );
        $wellnez404placehol  = __( 'Enter Your Keyword....', 'wellnez' );
    }


    // get header
    get_header();

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
        'sup'       => array(),
        'sub'       => array(),
    );

    if( !empty( wellnez_opt("wellnez_error_bg") ) ){
        $wellnez_error_bg_image = wellnez_opt("wellnez_error_bg");
        $error_img_url = $wellnez_error_bg_image['url'];
    }else{
        $error_img_url = '';
    }

    echo '<section class="vs-error-wrapper space-top space-extra-bottom background-image" style="background-image: url('. $error_img_url .')">';
        echo '<div class="container">';
            echo '<div class="error-content text-center">';
                if( ! empty( $wellnezerrortitle ) ){
                    echo '<h2 class="error-number">'.wp_kses( $wellnezerrortitle, $allowhtml ).'</h2>';
                }
                if( ! empty( $wellnez404title ) ){
                    echo '<h3 class="error-title">'.esc_html( $wellnez404title ).'</h3>';
                }
                if( ! empty( $wellnez404subtitle ) ){
                    echo '<p class="error-text">'.esc_html( $wellnez404subtitle ).'</p>';
                }
                echo '<form action="'.esc_url( home_url('/') ).'" class="search-inline">';
                    echo '<input name="s" type="text" class="form-control" placeholder="'.esc_attr( $wellnez404placehol ).'">';
                    echo '<button type="submit"><i class="far fa-search"></i></button>';
                echo '</form>';
                if( ! empty( $wellnez404btntext ) ){
                    echo '<a href="'.esc_url( home_url('/') ).'" class="vs-btn">'.esc_html( $wellnez404btntext ).'</a>';
                }
            echo '</div>';
        echo '</div>';
    echo '</section>';

    //footer
    get_footer();