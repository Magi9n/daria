<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

// If Elementor Pro Theme Builder has a "Single" template, render it.
// Otherwise, fall back to normal content.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
    // Elementor handled the output.
} else {
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
}

get_footer();