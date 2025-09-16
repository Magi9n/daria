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

    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ) {
        if( is_page() || is_page_template('template-builder.php') ) {
            $wellnez_post_id = get_the_ID();

            // Get the page settings manager
            $wellnez_page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $wellnez_page_settings_model = $wellnez_page_settings_manager->get_model( $wellnez_post_id );

            // Retrieve the color we added before
            $wellnez_header_style = $wellnez_page_settings_model->get_settings( 'wellnez_header_style' );
            $wellnez_header_builder_option = $wellnez_page_settings_model->get_settings( 'wellnez_header_builder_option' );

            if( $wellnez_header_style == 'header_builder'  ) {

                if( !empty( $wellnez_header_builder_option ) ) {
                    $wellnezheader = get_post( $wellnez_header_builder_option );
                    echo '<header class="header">';
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $wellnezheader->ID );
                    echo '</header>';
                }
            } else {
                // global options
                $wellnez_header_builder_trigger = wellnez_opt('wellnez_header_options');
                if( $wellnez_header_builder_trigger == '2' ) {
                    echo '<header>';
                    $wellnez_global_header_select = get_post( wellnez_opt( 'wellnez_header_select_options' ) );
                    $header_post = get_post( $wellnez_global_header_select );
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_post->ID );
                    echo '</header>';
                } else {
                    // wordpress Header
                    wellnez_global_header_option();
                }
            }
        } else {
            $wellnez_header_options = wellnez_opt('wellnez_header_options');
            if( $wellnez_header_options == '1' ) {
                wellnez_global_header_option();
            } else {
                $wellnez_header_select_options = wellnez_opt('wellnez_header_select_options');
                $wellnezheader = get_post( $wellnez_header_select_options );
                echo '<header class="header">';
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $wellnezheader->ID );
                echo '</header>';
            }
        }
    } else {
        wellnez_global_header_option();
    }