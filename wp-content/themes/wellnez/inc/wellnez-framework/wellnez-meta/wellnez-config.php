<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

 /**
 * Only return default value if we don't have a post ID (in the 'post' query variable)
 *
 * @param  bool  $default On/Off (true/false)
 * @return mixed          Returns true or '', the blank default
 */
function wellnez_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_action( 'cmb2_admin_init', 'wellnez_register_metabox' );

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */

function wellnez_register_metabox() {

	$prefix = '_wellnez_';

	$prefixpage = '_wellnezpage_';

	$wellnez_service_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'service_page_control',
		'title'         => esc_html__( 'Service Page Controller', 'wellnez' ),
		'object_types'  => array( 'wellnez_service' ), // Post type
		'closed'        => true
	) );
	$wellnez_service_meta->add_field( array(
		'name' => esc_html__( 'Write Flaticon Class', 'wellnez' ),
	   	'desc' => esc_html__( 'Write Flaticon Class For The Icon', 'wellnez' ),
	   	'id'   => $prefix . 'flat_icon_class',
		'type' => 'text',
    ) );

	$wellnez_post_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'blog_post_control',
		'title'         => esc_html__( 'Post Thumb Controller', 'wellnez' ),
		'object_types'  => array( 'post' ), // Post type
		'closed'        => true
	) );
	$wellnez_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Video', 'wellnez' ),
		'desc' => esc_html__( 'Use This Field When Post Format Video', 'wellnez' ),
		'id'   => $prefix . 'post_format_video',
        'type' => 'text_url',
    ) );
	$wellnez_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Audio', 'wellnez' ),
		'desc' => esc_html__( 'Use This Field When Post Format Audio', 'wellnez' ),
		'id'   => $prefix . 'post_format_audio',
        'type' => 'oembed',
    ) );
	$wellnez_post_meta->add_field( array(
		'name' => esc_html__( 'Post Thumbnail For Slider', 'wellnez' ),
		'desc' => esc_html__( 'Use This Field When You Want A Slider In Post Thumbnail', 'wellnez' ),
		'id'   => $prefix . 'post_format_slider',
        'type' => 'file_list',
    ) );

	$wellnez_page_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_meta_section',
		'title'         => esc_html__( 'Page Meta', 'wellnez' ),
		'object_types'  => array( 'page' ), // Post type
        'closed'        => true
    ) );

    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Area', 'wellnez' ),
		'desc' => esc_html__( 'check to display page breadcrumb area.', 'wellnez' ),
		'id'   => $prefix . 'page_breadcrumb_area',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','wellnez'),
            '2'     => esc_html__('Hide','wellnez'),
        )
    ) );


    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Settings', 'wellnez' ),
		'id'   => $prefix . 'page_breadcrumb_settings',
        'type' => 'select',
        'default'   => 'global',
        'options'   => array(
            'global'   => esc_html__( 'Global Settings', 'wellnez' ),
            'page'     => esc_html__( 'Page Settings', 'wellnez' ),
        )
	) );

	$wellnez_page_meta->add_field( array(
	    'name'    => esc_html__( 'Breadcumb Image', 'wellnez' ),
	    'desc'    => esc_html__( 'Upload an image or enter an URL.', 'wellnez' ),
	    'id'      => $prefix . 'breadcumb_image',
	    'type'    => 'file',
	    // Optional:
	    'options' => array(
	        'url' => false, // Hide the text input for the url
	    ),
	    'text'    => array(
	        'add_upload_file_text' => __( 'Add File', 'wellnez' ) // Change upload button text. Default: "Add or Upload File"
	    ),
	    'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );

    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title', 'wellnez' ),
		'desc' => esc_html__( 'check to display Page Title.', 'wellnez' ),
		'id'   => $prefix . 'page_title',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   	=> esc_html__( 'Show','wellnez'),
            '2'     => esc_html__( 'Hide','wellnez'),
        )
	) );

    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title Settings', 'wellnez' ),
		'id'   => $prefix . 'page_title_settings',
        'type' => 'select',
        'options'   => array(
            'default'  => esc_html__('Default Title','wellnez'),
            'custom'  => esc_html__('Custom Title','wellnez'),
        ),
        'default'   => 'default'
    ) );

    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Custom Page Title', 'wellnez' ),
		'id'   => $prefix . 'custom_page_title',
        'type' => 'text'
    ) );

    $wellnez_page_meta->add_field( array(
		'name' => esc_html__( 'Breadcrumb', 'wellnez' ),
		'desc' => esc_html__( 'Select Show to display breadcrumb area', 'wellnez' ),
		'id'   => $prefix . 'page_breadcrumb_trigger',
        'type' => 'switch_btn',
        'default' => wellnez_set_checkbox_default_for_new_post( true ),
    ) );

    $wellnez_layout_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_layout_section',
		'title'         => esc_html__( 'Page Layout', 'wellnez' ),
        'context' 		=> 'side',
        'priority' 		=> 'high',
        'object_types'  => array( 'page' ), // Post type
        'closed'        => true
	) );

	$wellnez_layout_meta->add_field( array(
		'desc'       => esc_html__( 'Set page layout container,container fluid,fullwidth or both. It\'s work only in template builder page.', 'wellnez' ),
		'id'         => $prefix . 'custom_page_layout',
		'type'       => 'radio',
        'options' => array(
            '1' => esc_html__( 'Container', 'wellnez' ),
            '2' => esc_html__( 'Container Fluid', 'wellnez' ),
            '3' => esc_html__( 'Fullwidth', 'wellnez' ),
        ),
	) );

	$wellnez_product_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'product_meta_section',
		'title'         => esc_html__( 'Swap Image', 'wellnez' ),
		'object_types'  => array( 'product' ), // Post type
		'closed'        => true,
		'context'		=> 'side',
		'priority'		=> 'default'
	) );

	$wellnez_product_meta->add_field( array(
		'name' 		=> esc_html__( 'Product Swap Image', 'wellnez' ),
		'desc' 		=> esc_html__( 'Set Product Swap Image', 'wellnez' ),
		'id'   		=> $prefix.'product_swap_image',
		'type'    	=> 'file',
		// Optional:
		'options' 	=> array(
			'url' 		=> false, // Hide the text input for the url
		),
		'text'    	=> array(
			'add_upload_file_text' => __( 'Add Swap Image', 'wellnez' ) // Change upload button text. Default: "Add or Upload File"
		),
	) );
}

add_action( 'cmb2_admin_init', 'wellnez_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function wellnez_register_taxonomy_metabox() {

    $prefix = '_wellnez_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$wellnez_term_meta = new_cmb2_box( array(
		'id'               => $prefix.'term_edit',
		'title'            => esc_html__( 'Category Metabox', 'wellnez' ),
		'object_types'     => array( 'term' ),
		'taxonomies'       => array( 'category'),
	) );
	$wellnez_term_meta->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'wellnez' ),
		'id'       => $prefix.'term_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$wellnez_term_meta->add_field( array(
		'name' => esc_html__( 'Category Image', 'wellnez' ),
		'desc' => esc_html__( 'Set Category Image', 'wellnez' ),
		'id'   => $prefix.'term_avatar',
        'type' => 'file',
        'text'    => array(
			'add_upload_file_text' => esc_html__('Add Image','wellnez') // Change upload button text. Default: "Add or Upload File"
		),
	) );
}