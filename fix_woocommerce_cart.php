<?php
require_once 'wp-load.php';

if (!class_exists('WooCommerce')) {
    echo 'WooCommerce is not active. Please activate WooCommerce.';
    exit;
}

$cart_page_id = get_option('woocommerce_cart_page_id');

if ($cart_page_id && get_post($cart_page_id)) {
    $cart_page_update = array(
        'ID'           => $cart_page_id,
        'post_content' => '[woocommerce_cart]',
    );

    $result = wp_update_post($cart_page_update, true);

    if (is_wp_error($result)) {
        echo 'Error updating the cart page: ' . $result->get_error_message();
    } else {
        echo 'The WooCommerce cart page has been successfully updated with the correct shortcode.';
    }
} else {
    $cart_page_data = array(
        'post_title'    => 'Cart',
        'post_content'  => '[woocommerce_cart]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
    );

    $new_page_id = wp_insert_post($cart_page_data);

    if ($new_page_id) {
        update_option('woocommerce_cart_page_id', $new_page_id);
        echo 'A new cart page was created and assigned to WooCommerce successfully.';
    } else {
        echo 'Failed to create a new cart page.';
    }
}
?>
