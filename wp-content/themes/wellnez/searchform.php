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
?>

<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form">
    <input name="s" required value="<?php echo esc_html( get_search_query() ); ?>" type="search" class="form-control" placeholder="<?php echo esc_attr__( 'Search Here', 'wellnez' ); ?>">
    <button type="submit" class="submit-btn"><i class="fal fa-search"></i></button>
</form>