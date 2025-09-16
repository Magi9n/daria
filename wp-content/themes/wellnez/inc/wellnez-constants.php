<?php
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Define constant
 *
 */

// Base URI
if ( ! defined( 'WELLNEZ_DIR_URI' ) ) {
    define('WELLNEZ_DIR_URI', get_parent_theme_file_uri().'/' );
}

// Assist URI
if ( ! defined( 'WELLNEZ_DIR_ASSIST_URI' ) ) {
    define( 'WELLNEZ_DIR_ASSIST_URI', get_theme_file_uri('/assets/') );
}


// Css File URI
if ( ! defined( 'WELLNEZ_DIR_CSS_URI' ) ) {
    define( 'WELLNEZ_DIR_CSS_URI', get_theme_file_uri('/assets/css/') );
}

// Skin Css File
if ( ! defined( 'WELLNEZ_DIR_SKIN_CSS_URI' ) ) {
    define( 'WELLNEZ_DIR_SKIN_CSS_URI', get_theme_file_uri('/assets/css/skins/') );
}


// Js File URI
if (!defined('WELLNEZ_DIR_JS_URI')) {
    define('WELLNEZ_DIR_JS_URI', get_theme_file_uri('/assets/js/'));
}


// External PLugin File URI
if (!defined('WELLNEZ_DIR_PLUGIN_URI')) {
    define('WELLNEZ_DIR_PLUGIN_URI', get_theme_file_uri( '/assets/plugins/'));
}

// Base Directory
if (!defined('WELLNEZ_DIR_PATH')) {
    define('WELLNEZ_DIR_PATH', get_parent_theme_file_path() . '/');
}

//Inc Folder Directory
if (!defined('WELLNEZ_DIR_PATH_INC')) {
    define('WELLNEZ_DIR_PATH_INC', WELLNEZ_DIR_PATH . 'inc/');
}

//WELLNEZ framework Folder Directory
if (!defined('WELLNEZ_DIR_PATH_FRAM')) {
    define('WELLNEZ_DIR_PATH_FRAM', WELLNEZ_DIR_PATH_INC . 'wellnez-framework/');
}

//Classes Folder Directory
if (!defined('WELLNEZ_DIR_PATH_CLASSES')) {
    define('WELLNEZ_DIR_PATH_CLASSES', WELLNEZ_DIR_PATH_INC . 'classes/');
}

//Hooks Folder Directory
if (!defined('WELLNEZ_DIR_PATH_HOOKS')) {
    define('WELLNEZ_DIR_PATH_HOOKS', WELLNEZ_DIR_PATH_INC . 'hooks/');
}

//Demo Data Folder Directory Path
if( !defined( 'WELLNEZ_DEMO_DIR_PATH' ) ){
    define( 'WELLNEZ_DEMO_DIR_PATH', WELLNEZ_DIR_PATH_INC.'demo-data/' );
}
    
//Demo Data Folder Directory URI
if( !defined( 'WELLNEZ_DEMO_DIR_URI' ) ){
    define( 'WELLNEZ_DEMO_DIR_URI', WELLNEZ_DIR_URI.'inc/demo-data/' );
}