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

    wellnez_setPostViews( get_the_ID() );

    ?>
    <div <?php post_class(); ?> >
    <?php
        if( class_exists('ReduxFramework') ) {
            $wellnez_post_details_title_position = wellnez_opt('wellnez_post_details_title_position');
        } else {
            $wellnez_post_details_title_position = 'header';
        }

        $allowhtml = array(
            'p'         => array(
                'class'     => array()
            ),
            'span'      => array(),
            'a'         => array(
                'href'      => array(),
                'title'     => array(),
                'class'     => array(),
            ),
            'br'        => array(),
            'em'        => array(),
            'strong'    => array(),
            'b'         => array(),
        );

        // Blog Post Thumbnail
        do_action( 'wellnez_blog_post_thumb' );
        

        echo '<div class="blog-content">';

            if( $wellnez_post_details_title_position != 'header' ) {
                echo '<h2 class="blog-title">'.wp_kses( get_the_title(), $allowhtml ).'</h2>';
            }
            // Blog Post Meta
            do_action( 'wellnez_blog_post_meta' );

            // Blog COntent
            the_content();
            // Link Pages
            wellnez_link_pages();

        echo '</div>';



        $wellnez_post_tag = get_the_tags();

        if( class_exists('ReduxFramework') ) {
            $wellnez_post_details_share_options = wellnez_opt('wellnez_post_details_share_options');
        } else {
            $wellnez_post_details_share_options = false;
        }

        if( ! empty( $wellnez_post_tag ) || ( function_exists( 'wellnez_social_sharing_buttons' ) && $wellnez_post_details_share_options ) ){
            echo '<!-- Share Links Area -->';
            echo '<div class="share-links clearfix">';
                echo '<div class="row justify-content-between">';
                    if( function_exists( 'wellnez_social_sharing_buttons' ) && $wellnez_post_details_share_options ){
                        $wellnez_tag_col = "col-md-auto text-xl-end";
                    }else{
                        $wellnez_tag_col = "col-md-auto";
                    }
                    if( is_array( $wellnez_post_tag ) && ! empty( $wellnez_post_tag ) ){
                        echo '<div class="'.esc_attr( $wellnez_tag_col ).'">';
                            if( count( $wellnez_post_tag ) > 1 ){
                                $tag_text = __( 'Tags:', 'wellnez' );
                            }else{
                                $tag_text = __( 'Tag:', 'wellnez' );
                            }
                            echo '<span class="share-links-title">'.$tag_text.'</span>';
                            echo '<div class="tagcloud">';
                                foreach( $wellnez_post_tag as $tags ){
                                    echo '<a href="'.esc_url( get_tag_link( $tags->term_id ) ).'">'.esc_html( $tags->name ).'</a>';
                                }
                            echo '</div>';
                        echo '</div>';
                    }
                    /**
                    *
                    * Hook for Blog Details Share Options
                    *
                    * Hook wellnez_blog_details_share_options
                    *
                    * @Hooked wellnez_blog_details_share_options_cb 10
                    *
                    */
                    do_action( 'wellnez_blog_details_share_options' );

                    echo '<!-- Share Links Area end -->';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';

    /**
    *
    * Hook for Blog Details Post Navigation Options
    *
    * Hook wellnez_blog_details_post_navigation
    *
    * @Hooked wellnez_blog_details_post_navigation_cb 10
    *
    */
    do_action( 'wellnez_blog_details_post_navigation' );

    /**
    *
    * Hook for Blog Details Author Bio
    *
    * Hook wellnez_blog_details_author_bio
    *
    * @Hooked wellnez_blog_details_author_bio_cb 10
    *
    */
    do_action( 'wellnez_blog_details_author_bio' );

    /**
    *
    * Hook for Blog Details Related Post
    *
    * Hook wellnez_blog_details_related_post
    *
    * @Hooked wellnez_blog_details_related_post_cb 10
    *
    */
    do_action( 'wellnez_blog_details_related_post' );

    /**
    *
    * Hook for Blog Details Comments
    *
    * Hook wellnez_blog_details_comments
    *
    * @Hooked wellnez_blog_details_comments_cb 10
    *
    */
    do_action( 'wellnez_blog_details_comments' );