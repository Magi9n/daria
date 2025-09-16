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
    exit;
}

 // theme option callback
function wellnez_opt( $id = null, $url = null ){
    global $wellnez_opt;

    if( $id && $url ){

        if( isset( $wellnez_opt[$id][$url] ) && $wellnez_opt[$id][$url] ){
            return $wellnez_opt[$id][$url];
        }
    }else{
        if( isset( $wellnez_opt[$id] )  && $wellnez_opt[$id] ){
            return $wellnez_opt[$id];
        }
    }
}


// theme logo
function wellnez_theme_logo() {
    // escaping allow html
    $allowhtml = array(
        'a'    => array(
            'href' => array()
        ),
        'span' => array(),
        'i'    => array(
            'class' => array()
        )
    );
    $siteUrl = home_url('/');
    if( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $siteLogo = '';
        $siteLogo .= '<a class="logo" href="'.esc_url( $siteUrl ).'">';
        $siteLogo .= wellnez_img_tag( array(
            "class" => "img-fluid logo-img",
            "url"   => esc_url( wp_get_attachment_image_url( $custom_logo_id, 'full') )
        ) );
        $siteLogo .= '</a>';

        return $siteLogo;
    } elseif( !wellnez_opt('wellnez_text_title') && wellnez_opt('wellnez_site_logo', 'url' )  ){

        $siteLogo = '<img class="img-fluid logo-img" src="'.esc_url( wellnez_opt('wellnez_site_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'wellnez' ).'" />';
        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';


    }elseif( wellnez_opt('wellnez_text_title') ){
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( wellnez_opt('wellnez_text_title'), $allowhtml ).'</a></h2>';
    }else{
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';
    }
}

// Wellnez Coming Soon Logo
function wellnez_coming_soon_logo() {
    // escaping allow html
    $allowhtml = array(
        'a'    => array(
            'href' => array()
        ),
        'span' => array(),
        'i'    => array(
            'class' => array()
        )
    );
    $siteUrl = home_url('/');
    // site logo
    if( wellnez_opt( 'wellnez_coming_logo', 'url' )  ){

        $siteLogo = '<img src="'.esc_url( wellnez_opt('wellnez_coming_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'wellnez' ).'" />';

        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';

    }elseif( wellnez_opt('wellnez_coming_site_title') ){
        return '<h2 class="mb-0"><a class="text-logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( wellnez_opt('wellnez_coming_site_title'), $allowhtml ).'</a></h2>';
    }else{
        return '<h2 class="mb-0"><a class="text-logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';
    }
}

// custom meta id callback
function wellnez_meta( $id = '' ){
    $value = get_post_meta( get_the_ID(), '_wellnez_'.$id, true );
    return $value;
}


// Blog Date Permalink
function wellnez_blog_date_permalink() {
    $year  = get_the_time('Y');
    $month_link = get_the_time('m');
    $day   = get_the_time('d');
    $link = get_day_link( $year, $month_link, $day);
    return $link;
}

//audio format iframe match
function wellnez_iframe_match() {
    $audio_content = wellnez_embedded_media( array('audio', 'iframe') );
    $iframe_match = preg_match("/\iframe\b/i",$audio_content, $match);
    return $iframe_match;
}


//Post embedded media
function wellnez_embedded_media( $type = array() ){
    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );
    $embed   = get_media_embedded_in_content( $content, $type );


    if( in_array( 'audio' , $type) ){
        if( count( $embed ) > 0 ){
            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );
        }else{
           $output = '';
        }

    }else{
        if( count( $embed ) > 0 ){
            $output = $embed[0];
        }else{
           $output = '';
        }
    }
    return $output;
}


// WP post link pages
function wellnez_link_pages(){
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wellnez' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'wellnez' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
}


// Data Background image attr
function wellnez_data_bg_attr( $imgUrl = '' ){
    return 'data-bg-img="'.esc_url( $imgUrl ).'"';
}

// image alt tag
function wellnez_image_alt( $url = '' ){
    if( $url != '' ){
        // attachment id by url
        $attachmentid = attachment_url_to_postid( esc_url( $url ) );
       // attachment alt tag
        $image_alt = get_post_meta( esc_html( $attachmentid ) , '_wp_attachment_image_alt', true );
        if( $image_alt ){
            return $image_alt ;
        }else{
            $filename = pathinfo( esc_url( $url ) );
            $alt = str_replace( '-', ' ', $filename['filename'] );
            return $alt;
        }
    }else{
       return;
    }
}


// Flat Content wysiwyg output with meta key and post id

function wellnez_get_textareahtml_output( $content ) {
    global $wp_embed;

    $content = $wp_embed->autoembed( $content );
    $content = $wp_embed->run_shortcode( $content );
    $content = wpautop( $content );
    $content = do_shortcode( $content );

    return $content;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */

function wellnez_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'wellnez_pingback_header' );


// Excerpt More
function wellnez_excerpt_more( $more ) {
    return '...';
}

add_filter( 'excerpt_more', 'wellnez_excerpt_more' );


// wellnez comment template callback
function wellnez_comment_callback( $comment, $args, $depth ) {
        $add_below = 'comment';
    ?>
    <li <?php comment_class( array('vs-comment') ); ?>>
        <div id="comment-<?php comment_ID() ?>" class="vs-post-comment">
            <?php
                if( get_avatar( $comment, 110 )  ) :
            ?>
            <!-- Author Image -->
            <div class="comment-avater">
                <?php
                    if ( $args['avatar_size'] != 0 ) {
                        echo get_avatar( $comment, 110 );
                    }
                ?>
            </div>
            <!-- Author Image -->
            <?php
                endif;
            ?>
            <!-- Comment Content -->
            <div class="comment-content">
                <h4 class="name h5"><?php echo esc_html( ucwords( get_comment_author() ) ); ?></h4>
                <span class="commented-on"> 
                    <?php echo '<i class="fal fa-calendar-alt"></i>'; ?>
                    <?php printf( esc_html__('%1$s', 'wellnez'), get_comment_date() ); ?> 
                </span>
                <?php comment_text(); ?>
                <div class="reply_and_edit">
                    <?php
                        comment_reply_link(array_merge( $args, array( 'add_below' => $add_below, 'depth' => 1, 'max_depth' => 5, 'reply_text' => '<i class="fas fa-reply"></i>Reply' ) ) );
                    ?>
                    <span class="comment-edit-link pl-10"><?php edit_comment_link( '<i class="fas fa-pencil"></i>'.esc_html__( 'Edit', 'wellnez' ), '  ', '' ); ?></span>
                </div>

                <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'wellnez' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Comment Content -->
<?php
}

//body class
add_filter( 'body_class', 'wellnez_body_class' );
function wellnez_body_class( $classes ) {
    if( class_exists('ReduxFramework') ) {
        $wellnez_blog_single_sidebar = wellnez_opt('wellnez_blog_single_sidebar');
        if( ($wellnez_blog_single_sidebar != '2' && $wellnez_blog_single_sidebar != '3' ) || ! is_active_sidebar('wellnez-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
    } else {
        if( !is_active_sidebar('wellnez-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
    }
    return $classes;
}


function wellnez_footer_global_option(){

    // Wellnez Footer Bottom Enable Disable
    if( class_exists( 'ReduxFramework' ) ){
        $wellnez_footer_bottom_active = wellnez_opt( 'wellnez_disable_footer_bottom' );
    }else{
        $wellnez_footer_bottom_active = '1';
    }

    $allowhtml = array(
        'p'         => array(
            'class'     => array()
        ),
        'span'      => array(
            'class'     => array(),
        ),
        'a'         => array(
            'href'      => array(),
            'title'     => array()
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
    );

    if( $wellnez_footer_bottom_active == '1' ){
        echo '<!-- Footer -->';
        echo '<footer class="footer-wrapper footer-layout1">';

            if( $wellnez_footer_bottom_active == '1' ){
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
                echo '<div class="copyright-wrap">';
                    echo '<div class="container">';
                        echo '<div class="row align-items-center">';
                            if( ! empty( wellnez_opt( 'wellnez_copyright_text' ) ) ){
                                echo '<p class="copyright-text">'.wp_kses( wellnez_opt( 'wellnez_copyright_text' ), $allowhtml ).'</p>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</footer>';
        echo '<!-- End Footer -->';
    }
}

function wellnez_social_icon(){
    $wellnez_social_icon = wellnez_opt( 'wellnez_social_links' );
    if( ! empty( $wellnez_social_icon ) && isset( $wellnez_social_icon ) ){
        echo '<div class="author-links">';
        foreach( $wellnez_social_icon as $social_icon ){
            if( ! empty( $social_icon['title'] ) ){
                echo '<a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i>'.esc_html( $social_icon['description'] ).'</a>';
            }
        }
        echo '</div>';
    }
}

// global header
function wellnez_global_header_option() {
    wellnez_global_header();
    echo '<header class="vs-header header-layout1 header-default">';
        echo '<div class="sticky-active py-3 py-lg-0">';
            echo '<div class="container position-relative">';
                echo '<div class="row align-items-center justify-content-between">';
                    echo '<div class="col-auto align-self-center">';
                        echo '<div class="header-logo">';
                            echo wellnez_theme_logo();
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="col-auto">';
                        if( has_nav_menu( 'primary-menu' ) ){
                            echo '<nav class="main-menu menu-style1 d-none d-lg-block">';
                                wp_nav_menu( array(
                                    "theme_location"    => 'primary-menu',
                                    "container"         => '',
                                    "menu_class"        => ''
                                ) );
                            echo '</nav>';
                        }
                        echo '<!-- Mobile Menu Toggler -->';
                        echo '<button class="vs-menu-toggle d-inline-block d-lg-none"><i class="far fa-bars"></i></button>';
                    echo '</div>';
                    echo '<div class="col-auto">';
                        echo '<div class="header-btns">';
                            echo '<button class="searchBoxTggler"><i class="far fa-search"></i></button>';
                        echo '</div>';
                        echo '<div class="popup-search-box">';
                                echo '<button class="searchClose"><i class="fal fa-times"></i></button>';
                            echo '<form action="'.esc_url( home_url() ).'" class="header-search">';
                                echo '<input name="s" type="text" placeholder="'.esc_html( 'What are you looking for', 'wellnez' ).'">';
                                echo '<button type="submit" aria-label="search-button"><i class="far fa-search"></i></button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        if( ! empty( wellnez_opt( 'wellnez_notice_text' ) ) ){
            echo '<div class="header-notice d-none d-md-block">';
                echo '<div class="container">';
                    echo '<p class="mb-0 fs-xs text-title">'.esc_html( wellnez_opt( 'wellnez_notice_text' ) ).'</p>';
                echo '</div>';
            echo '</div>';
        }
    echo '</header>';

}

// Wellnez Default Header
if( ! function_exists( 'wellnez_global_header' ) ){
    function wellnez_global_header(){
        // Mobile Menu
        echo '<div class="vs-menu-wrapper">';
            echo '<div class="vs-menu-area">';
                echo '<button class="vs-menu-toggle"><i class="fal fa-times"></i></button>';
                echo '<div class="mobile-logo">';
                    echo wellnez_theme_logo();
                echo '</div>';
                if( has_nav_menu( 'mobile-menu' ) ){
                    echo '<div class="vs-mobile-menu link-inherit">';
                        wp_nav_menu( array(
                            "theme_location"    => 'mobile-menu',
                            "container"         => '',
                            "menu_class"        => ''
                        ) );
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
    }
}

// wellnez woocommerce breadcrumb
function wellnez_woo_breadcrumb( $args ) {
    return array(
        'delimiter'   => '',
        'wrap_before' => '<ul class="breadcumb-menu text-white pt-1">',
        'wrap_after'  => '</ul>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => _x( 'Home', 'breadcrumb', 'wellnez' ),
    );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'wellnez_woo_breadcrumb' );

function wellnez_custom_search_form( $class ) {
    echo '<!-- Search Form -->';
    echo '<form role="search" method="get" action="'.esc_url( home_url( '/' ) ).'" class="'.esc_attr( $class ).'">';
        echo '<label class="searchIcon">';
            echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'wellnez').'">';
        echo '</label>';
    echo '</form>';
    echo '<!-- End Search Form -->';
}



//Fire the wp_body_open action.
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

//Remove Tag-Clouds inline style
add_filter( 'wp_generate_tag_cloud', 'wellnez_remove_tagcloud_inline_style',10,1 );
function wellnez_remove_tagcloud_inline_style( $input ){
   return preg_replace('/ style=("|\')(.*?)("|\')/','',$input );
}

// password protected form
add_filter('the_password_form','wellnez_password_form',10,1);
function wellnez_password_form( $output ) {
    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post"><div class="theme-input-group">
        <input name="post_password" type="password" class="theme-input-style" placeholder="'.esc_attr__( 'Enter Password','wellnez' ).'">
        <button type="submit" class="submit-btn btn-fill">'.esc_html__( 'Enter','wellnez' ).'</button></div></form>';
    return $output;
}

function wellnez_setPostViews( $postID ) {
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    }else{
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

function wellnez_getPostViews( $postID ){
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
        return __( '0', 'wellnez' );
    }
    return $count;
}


/* This code filters the Categories archive widget to include the post count inside the link */
add_filter( 'wp_list_categories', 'wellnez_cat_count_span' );
function wellnez_cat_count_span( $links ) {
    $links = str_replace('</a> (', '</a> <span class="category-number">(', $links);
    $links = str_replace(')', ')</span>', $links);
    return $links;
}

/* This code filters the Archive widget to include the post count inside the link */
add_filter( 'get_archives_link', 'wellnez_archive_count_span' );
function wellnez_archive_count_span( $links ) {
    $links = str_replace('</a>&nbsp;(', '</a> <span class="category-number">(', $links);
    $links = str_replace(')', ')</span>', $links);
	return $links;
}


if( ! function_exists( 'wellnez_blog_category' ) ){
    function wellnez_blog_category(){
        if( class_exists( 'ReduxFramework' ) ){
            $wellnez_display_post_category =  wellnez_opt('wellnez_display_post_category');
        }else{
            $wellnez_display_post_category = '1';
        }

        if( $wellnez_display_post_category ){
            $wellnez_post_categories = get_the_category();
            if( is_array( $wellnez_post_categories ) && ! empty( $wellnez_post_categories ) ){
                echo '<div class="blog-category ">';
                    echo ' <a href="'.esc_url( get_term_link( $wellnez_post_categories[0]->term_id ) ).'">'.esc_html( $wellnez_post_categories[0]->name ).'</a> ';
                echo '</div>';
            }
        }
    }
}

// Add Extra Class On Comment Reply Button
function wellnez_custom_comment_reply_link( $content ) {
    $extra_classes = 'replay-btn';
    return preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $extra_classes, $content);
}

add_filter('comment_reply_link', 'wellnez_custom_comment_reply_link', 99);

// Add Extra Class On Edit Comment Link
function wellnez_custom_edit_comment_link( $content ) {
    $extra_classes = 'replay-btn';
    return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $extra_classes, $content);
}

add_filter('edit_comment_link', 'wellnez_custom_edit_comment_link', 99);


function wellnez_post_classes( $classes, $class, $post_id ) {
    if ( get_post_type() === 'post' ) {
        if( ! is_single() ){
            if( wellnez_opt( 'wellnez_blog_style' ) == '3' ){
                $classes[] = "vs-blog blog-grid grid-wide";
            }else{
                $classes[] = "vs-blog blog-single";
            }
        }else{
            $classes[] = "vs-blog blog-single";
        }
    }elseif( get_post_type() === 'product' ){
        // Return Class
    }elseif( get_post_type() === 'page' ){
        $classes[] = "page--item";
    }

    return $classes;
}
add_filter( 'post_class', 'wellnez_post_classes', 10, 3 );