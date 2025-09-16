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
    exit;
}

function wellnez_widgets_init() {

    if( class_exists('ReduxFramework') ) {
        $wellnez_sidebar_widget_title_heading_tag = wellnez_opt('wellnez_sidebar_widget_title_heading_tag');
    } else {
        $wellnez_sidebar_widget_title_heading_tag = 'h3';
    }

    //sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'wellnez' ),
        'id'            => 'wellnez-blog-sidebar',
        'description'   => esc_html__( 'Add Blog Sidebar Widgets Here.', 'wellnez' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<'.esc_attr($wellnez_sidebar_widget_title_heading_tag).' class="widget_title">',
        'after_title'   => '</'.esc_attr($wellnez_sidebar_widget_title_heading_tag).'>',
    ) );

    // page sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Page Sidebar', 'wellnez' ),
        'id'            => 'wellnez-page-sidebar',
        'description'   => esc_html__( 'Add Page Sidebar Widgets Here.', 'wellnez' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget_title">',
        'after_title'   => '</h3>',
    ) );
    // page sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Offcanvas Sidebar', 'wellnez' ),
        'id'            => 'wellnez-offcanvas-sidebar',
        'description'   => esc_html__( 'Add Offcanvas Sidebar Widgets Here.', 'wellnez' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget_title">',
        'after_title'   => '</h3>',
    ) );
    if( class_exists('woocommerce') ) {
        register_sidebar(
            array(
                'name'          => esc_html__( 'WooCommerce Sidebar', 'wellnez' ),
                'id'            => 'wellnez-woo-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in your woocommerce page sidebar.', 'wellnez' ),
                'before_widget' => '<div class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title"><h4>',
                'after_title'   => '</h4></div>',
            )
        );
    }

}

add_action( 'widgets_init', 'wellnez_widgets_init' );