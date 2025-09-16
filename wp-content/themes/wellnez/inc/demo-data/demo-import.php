<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( );
}
/**
 * @Packge    : wellnez
 * @version   : 1.0
 * @Author    : Vecurosoft
 * @Author URI: https://www.vecurosoft.com/
 */

// demo import file
function wellnez_import_files() {

	$demoImg = '<img src="'. WELLNEZ_DEMO_DIR_URI  .'screen-image.png" alt="'.esc_attr__('Demo Preview Imgae','wellnez').'" />';
    return array(
        array(
            'import_file_name'             => esc_html__('Wellnez Demo','wellnez'),
            'local_import_file'            =>  WELLNEZ_DEMO_DIR_PATH  . 'wellnez-demo.xml',
            'local_import_widget_file'     =>  WELLNEZ_DEMO_DIR_PATH  . 'wellnez-widgets-demo.json',
            'local_import_redux'           => array(
                array(
                    'file_path'   =>  WELLNEZ_DEMO_DIR_PATH . 'redux_options_demo.json',
                    'option_name' => 'wellnez_opt',
                ),
            ),
            'import_notice' => $demoImg,
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'wellnez_import_files' );

// demo import setup
function wellnez_after_import_setup() {
	// Assign menus to their locations.
	$main_menu   = get_term_by( 'name', 'Header Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary-menu'    => $main_menu->term_id,
			'mobile-menu'     =>  $main_menu ->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id 	= get_page_by_title( 'Home 01' );
	$blog_page_id  	= get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );
    
    if( class_exists( 'LS_Sliders' ) ){
        include LS_ROOT_PATH.'/classes/class.ls.importutil.php';
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/sliderone.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/slidertwo.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/sliderthree.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/sliderfour.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/sliderfive.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/slidersix.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/sliderseven.zip');
        new LS_ImportUtil( WELLNEZ_DEMO_DIR_PATH  . 'slider/slidereight.zip');
    }
    
}
add_action( 'pt-ocdi/after_import', 'wellnez_after_import_setup' );


//disable the branding notice after successful demo import
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

//change the location, title and other parameters of the plugin page
function wellnez_import_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Wellnez Demo Import' , 'wellnez' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Data' , 'wellnez' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'wellnez-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'wellnez_import_plugin_page_setup' );

// Enqueue scripts
function wellnez_demo_import_custom_scripts(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'wellnez-demo-import' ){
		// style
		wp_enqueue_style( 'wellnez-demo-import', WELLNEZ_DEMO_DIR_URI.'css/wellnez.demo.import.css', array(), '1.0', false );
	}
}
add_action( 'admin_enqueue_scripts', 'wellnez_demo_import_custom_scripts' );