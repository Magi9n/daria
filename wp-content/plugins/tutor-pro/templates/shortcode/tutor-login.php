<?php
/**
 * Login page shortcode
 *
 * @author themeum
 * @link https://themeum.com
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ! is_admin() ) {
    tutor_load_template( 'dashboard.logged-in' );
	return;
}

add_filter( 'tutor_after_login_redirect_url', function( $redirect_url ) {
    if ( class_exists( 'WooCommerce' ) ) {
        // Check if a redirect URL is provided and it points to the checkout page.
        if ( ! empty( $_REQUEST['redirect_to'] ) && strpos( $_REQUEST['redirect_to'], wc_get_checkout_url() ) !== false ) {
            return esc_url( $_REQUEST['redirect_to'] );
        } 

        // Fallback for when redirect_to is not set, but the referer is the checkout page.
        $referer = wp_get_referer();
        if ( $referer && strpos( $referer, wc_get_checkout_url() ) !== false ) {
            return esc_url( $referer );
        }
    }
    return tutor_utils()->tutor_dashboard_url();
});
?>

<?php do_action( 'tutor/template/login/before/wrap' ); ?>
<div <?php tutor_post_class( 'tutor-page-wrap' ); ?>>
	<div class="tutor-template-segment tutor-login-wrap">
        <div class="tutor-login-form-wrapper">
			<div class="tutor-fs-5 tutor-color-black tutor-mb-32">
				<?php esc_html_e( 'Hi, Welcome back!', 'tutor' ); ?>
			</div>
			<?php
				$login_form = trailingslashit( tutor()->path ) . 'templates/login-form.php';
				tutor_load_template_from_custom_path( $login_form, false );
			?>
			<?php do_action( 'tutor_after_login_form' ); ?>
		</div>
		<?php do_action( 'tutor_after_login_form_wrapper' ); ?>
	</div>
</div>
<?php do_action( 'tutor/template/login/after/wrap' ); ?>
