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

    if( !empty( wellnez_pagination() ) ) :
?>
<!-- Post Pagination -->
<div class="vs-pagination pb-30">
    <ul>
        <?php
            $prev 	= esc_html__( 'Prev', 'wellnez' );
            $next 	= esc_html__( 'Next', 'wellnez' );
            // previous
            if( get_previous_posts_link() ){
                echo '<li>';
                previous_posts_link( $prev );
                echo '</li>';
            }

            echo wellnez_pagination();

            // next
            if( get_next_posts_link() ){
                echo '<li>';
                next_posts_link( $next );
                echo '</li>';
            }
        ?>
    </ul>
</div>
<!-- End of Post Pagination -->
<?php 
    endif;