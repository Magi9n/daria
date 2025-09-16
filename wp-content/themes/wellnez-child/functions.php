<?php
/**
 *
 * @Packge      Wellnez
 * @Author      Vecuro
 * @version     1.0
 *
 */

/**
 * Enqueue style of child theme
 */
function wellnez_child_enqueue_styles() {

    wp_enqueue_style( 'wellnez-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'wellnez-child-style', get_stylesheet_directory_uri() . '/style.css',array( 'wellnez-style' ),wp_get_theme()->get('Version'));

    wp_enqueue_script( 'custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'wellnez_child_enqueue_styles', 100000 );