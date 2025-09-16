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


if( class_exists( 'ReduxFramework' )){
    $wellnez_blog_style = wellnez_opt('wellnez_blog_style');

    if('blog_style_one' == $wellnez_blog_style ){
        get_template_part( 'templates/blog-style-one' );
    }elseif('blog_style_two' == $wellnez_blog_style ){
        get_template_part( 'templates/blog-style-two' );
    }
}else{
    get_template_part( 'templates/blog-style-one' );
}