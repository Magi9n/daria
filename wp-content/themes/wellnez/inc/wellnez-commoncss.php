<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Wellnez
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// enqueue css
function wellnez_common_custom_css(){
	wp_enqueue_style( 'wellnez-color-schemes', get_template_directory_uri().'/assets/css/color.schemes.css' );

    $CustomCssOpt  = wellnez_opt( 'wellnez_css_editor' );
	if( $CustomCssOpt ){
		$CustomCssOpt = $CustomCssOpt;
	}else{
		$CustomCssOpt = '';
	}

    $customcss = "";

    if( get_header_image() ){
        $wellnez_header_bg =  get_header_image();
    }else{
        if( wellnez_meta( 'page_breadcrumb_settings' ) == 'page' && is_page() ){
            if( ! empty( wellnez_meta( 'breadcumb_image' ) ) ){
                $wellnez_header_bg = wellnez_meta( 'breadcumb_image' );
            }
        }
    }

    if( !empty( $wellnez_header_bg ) ){
        $customcss .= ".breadcumb-wrapper{
            background-image:url('{$wellnez_header_bg}')!important;
        }";
    }

	// theme color
	$wellnezthemecolor 			= wellnez_opt('wellnez_theme_color');
	$wellnezsecondarythemecolor = wellnez_opt('wellnez_secondary_theme_color');
	$preloadercolor    			= wellnez_opt('wellnez_preloader_color');
	$preloadercolortwo 			= wellnez_opt('wellnez_preloader_color_two');

	if( !empty( $wellnezthemecolor ) ) {
		$customcss .= ":root {
		  --theme-color: {$wellnezthemecolor};
		}";
	}

	if( !empty( $wellnezsecondarythemecolor ) ) {
		$customcss .= ":root {
			--vs-secondary-color: {$wellnezsecondarythemecolor};
		}";
	}

	if( !empty( $preloadercolor ) ) {
		$customcss .= ".loader {
            --theme-color: {$preloadercolor};
		}";
	}

	if( !empty( $preloadercolortwo ) ) {
		$customcss .= ".loader {
            --title-color: {$preloadercolortwo};
		}";
	}

	if( !empty( $CustomCssOpt ) ){
		$customcss .= $CustomCssOpt;
	}

    wp_add_inline_style( 'wellnez-color-schemes', $customcss );
}
add_action( 'wp_enqueue_scripts', 'wellnez_common_custom_css', 100 );