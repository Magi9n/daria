<?php
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function wellnez_essential_scripts() {

	wp_enqueue_style( 'wellnez-style', get_stylesheet_uri() ,array(), wp_get_theme()->get( 'Version' ) );

    // google font
    wp_enqueue_style( 'wellnez-fonts', wellnez_google_fonts() ,array(), wp_get_theme()->get( 'Version' ) );

    // Font Awesome Five
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/assets/css/fontawesome.min.css' ) ,array(), '5.9.0' );

    // Slick css
    wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ) ,array(), '4.0.13' );

    // Bootstrap Min
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ) ,array(), '4.3.1' );

    // Magnific Popup
    wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.min.css' ), array(), '1.0' );

    // wellnez app style
    wp_enqueue_style( 'wellnez-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), wp_get_theme()->get( 'Version' ) );

    // Load Js

    // Bootstrap
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '4.3.1', true );

    // Slick
    wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/slick.min.js' ), array('jquery'), '1.0.0', true );

    // magnific popup
    wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.0.0', true );

    // Isotope
    wp_enqueue_script( 'isototpe-pkgd', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0.0', true );

    // Isotope Imagesloaded
    wp_enqueue_script( 'imagesloaded' );

    // wow script
    wp_enqueue_script( 'wellnez-wow', get_theme_file_uri( '/assets/js/wow.min.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // main script
    wp_enqueue_script( 'wellnez-main-script', get_theme_file_uri( '/assets/js/main.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wellnez_essential_scripts',99 );


function wellnez_block_editor_assets( ) {
    // Add custom fonts.
	wp_enqueue_style( 'wellnez-editor-fonts', wellnez_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'wellnez_block_editor_assets' );


function wellnez_google_fonts() {
    $font_families = array(
        'Marcellus:400,500,600,700',
        'DM Sans:400,500',
    );

    $familyArgs = array(
        'family' => urlencode( implode( '|', $font_families ) ),
        'subset' => urlencode( 'latin,latin-ext' ),
    );

    $fontUrl = add_query_arg( $familyArgs, '//fonts.googleapis.com/css' );

    return esc_url_raw( $fontUrl );
}