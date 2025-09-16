<?php
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/wellnez-constants.php';

//theme setup
require_once WELLNEZ_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once WELLNEZ_DIR_PATH_INC . 'essential-scripts.php';

if( class_exists( 'WooCommerce' ) ){
    // Woo Hooks
    require_once WELLNEZ_DIR_PATH_INC . 'woo-hooks/wellnez-woo-hooks.php';

    // Woo Hooks Functions
    require_once WELLNEZ_DIR_PATH_INC . 'woo-hooks/wellnez-woo-hooks-functions.php';
}


// plugin activation
require_once WELLNEZ_DIR_PATH_FRAM . 'plugins-activation/wellnez-active-plugins.php';

// meta options
require_once WELLNEZ_DIR_PATH_FRAM . 'wellnez-meta/wellnez-config.php';

// page breadcrumbs
require_once WELLNEZ_DIR_PATH_INC . 'wellnez-breadcrumbs.php';

// sidebar register
require_once WELLNEZ_DIR_PATH_INC . 'wellnez-widgets-reg.php';

//essential functions
require_once WELLNEZ_DIR_PATH_INC . 'wellnez-functions.php';

// theme dynamic css
require_once WELLNEZ_DIR_PATH_INC . 'wellnez-commoncss.php';

// helper function
require_once WELLNEZ_DIR_PATH_INC . 'wp-html-helper.php';

// Demo Data
require_once WELLNEZ_DEMO_DIR_PATH . 'demo-import.php';

// pagination
require_once WELLNEZ_DIR_PATH_INC . 'wp_bootstrap_pagination.php';

// wellnez options
require_once WELLNEZ_DIR_PATH_FRAM . 'wellnez-options/wellnez-options.php';

// hooks
require_once WELLNEZ_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once WELLNEZ_DIR_PATH_HOOKS . 'hooks-functions.php';