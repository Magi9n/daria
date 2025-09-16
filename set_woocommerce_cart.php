<?php
require_once 'wp-load.php';

if (function_exists('tutor_utils')) {
    tutor_utils()->update_option('monetize_by', 'wc');
    echo 'Tutor LMS monetization option updated successfully to use WooCommerce.';
} else {
    echo 'Tutor LMS plugin does not appear to be active.';
}
?>
