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

if ( ! is_active_sidebar( 'wellnez-blog-sidebar' ) ) {
    return;
}
?>

<div class="col-lg-4 col-xxl-3">
    <div class="sidebar-area sticky-sidebar mt-50 mt-lg-0">
    <?php dynamic_sidebar( 'wellnez-blog-sidebar' ); ?>
    </div>
</div>