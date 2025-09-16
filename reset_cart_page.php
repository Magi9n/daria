<?php
require_once 'wp-load.php';

// Get the ID of the page that Tutor LMS uses as its cart page.
$tutor_cart_page_id = get_option('tutor_cart_page_id');

if ($tutor_cart_page_id) {
    // Get the ID of the page that WooCommerce uses as its cart page.
    $woocommerce_cart_page_id = get_option('woocommerce_cart_page_id');

    if ($tutor_cart_page_id == $woocommerce_cart_page_id) {
        // If both plugins are trying to use the same page, ensure it has the correct WooCommerce shortcode.
        $cart_page = array(
            'ID'           => $woocommerce_cart_page_id,
            'post_content' => '[woocommerce_cart]',
        );
        wp_update_post($cart_page);
        echo 'The cart page content has been successfully updated to use the WooCommerce shortcode.';
    } else {
        // If the pages are different, it's safer to just point WooCommerce to the right page if it's misconfigured.
        // This part is a fallback, the main issue is usually the content of the page.
        echo 'Tutor LMS and WooCommerce are using different cart pages. Please ensure the correct page is assigned in WooCommerce settings.';
    }
} else {
    echo 'Could not find the Tutor LMS cart page ID. Please check your settings.';
}
?>
