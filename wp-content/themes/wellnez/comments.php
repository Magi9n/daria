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

    if ( post_password_required() ) {
        return;
    }


    if( have_comments() ) :
?>
<!-- Comments -->
<div class="vs-comments-wrap">
    <h3 class="blog-inner-title">
        <?php printf( _nx( 'Comment (1)', 'Comments (%1$s)', get_comments_number(), 'comments title', 'wellnez' ), number_format_i18n( get_comments_number() ) ); ?>
    </h3>
    <ul class="comment-list">
        <?php
            the_comments_navigation();
                wp_list_comments( array(
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 100,
                    'callback'    => 'wellnez_comment_callback'
                ) );
            the_comments_navigation();
        ?>
    </ul>
</div>

<!-- End of Comments -->
<?php
    endif;
?>

<?php
    $commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
    $aria_req = ( $req ? "required" : '' );

    $consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
    
	$fields =  array(
	  'author'  => '<div class="row"><div class="col-md-6 form-group"><input class="form-control" type="text" name="author" placeholder="'. esc_attr__( 'Enter Your Name *', 'wellnez' ) .'" value="'. esc_attr( $commenter['comment_author'] ).'" '.esc_attr( $aria_req ).'></div>',
	  'email'   => '<div class="col-md-6 form-group"><input class="form-control" type="email" name="email"  value="' . esc_attr(  $commenter['comment_author_email'] ) .'" placeholder="'. esc_attr__( 'Enter E-mail Address *', 'wellnez' ) .'" '.esc_attr( $aria_req ).'></div></div>',
      'url'     => '',
      'cookies' => '<div class="row"><div class="col-12"><div class="custom-checkbox notice"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . esc_attr( $consent ) . ' />' . '<label for="wp-comment-cookies-consent">'  . esc_html__( ' Save my name, email, and website in this browser for the next time I comment.','wellnez' ) .  '<span class="checkmark"></span> </label> </div></div></div>'
    );

	$args = array(
    	'comment_field'         =>'<div class="col-md-12 form-group"><textarea class="form-control" name="comment" placeholder="'. esc_attr__( 'Write Your Comment *', 'wellnez' ) .'" '.esc_attr( $aria_req ).'></textarea></div>',
        'class_form'            => 'comment-form form-inner',
    	'title_reply'           => esc_html__( 'Leave A Comment', 'wellnez' ),
    	'title_reply_before'    => '<div class="form-title"><h3 class="h3">',
        'title_reply_after'     => '</h3></div>',
        'comment_notes_before'  => '<p class="comment-notes"><span>'.esc_html__('Your email address will not be published. Required fields are marked *','wellnez').'</span></p>',
        'logged_in_as'          => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','wellnez' ), admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
        'class_submit'          => 'vs-btn style1',
        'submit_field'          => '<div class="row"><div class="col-12 form-group pt-xl-20 mb-0">%1$s %2$s</div></div>',
    	'submit_button'         => '<button type="submit" name="%1$s" id="%2$s" class="%3$s">'.esc_html__('Post Comment','wellnez').'</button>',
    	'fields'                => $fields,
	);

    if(  comments_open() ) {
        echo '<!-- Comment Form -->';
        echo '<div id="comments" class="vs-comment-form">';
            comment_form( $args );
        echo '</div>';
        echo '<!-- End of Comment Form -->';
    }