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

if( class_exists( 'ReduxFramework' ) ){
    get_template_part( 'templates/blog-style-one' );
}else{
    get_template_part( 'templates/blog-style-one' );
}