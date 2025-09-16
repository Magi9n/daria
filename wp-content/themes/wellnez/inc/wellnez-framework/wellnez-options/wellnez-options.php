<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "wellnez_opt";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }


    $alowhtml = array(
        'p' => array(
            'class' => array()
        ),
        'span' => array()
    );


    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Wellnez Options', 'wellnez' ),
        'page_title'           => esc_html__( 'Wellnez Options', 'wellnez' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );


    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'wellnez' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'wellnez' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'wellnez' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'wellnez' )
        )
    );
    Redux::set_help_tab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'wellnez' );
    Redux::set_help_sidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */


    // -> START General Fields

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'General', 'wellnez' ),
        'id'               => 'wellnez_general',
        'customizer_width' => '450px',
        'icon'             => 'el el-cog',
        'fields'           => array(
            array(
                'id'       => 'wellnez_theme_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Theme Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Theme Color', 'wellnez' )
            ),
            array(
                'id'       => 'wellnez_secondary_theme_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Secondary Theme Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Secondary Theme Color', 'wellnez' )
            ),
            array(
                'id'          => 'title_typography',
                'type'        => 'typography', 
                'title'       => __( 'Title Typography', 'wellnez' ),
                'google'      => true, 
                'font-backup' => true,
                'output'      => array('
                    h1, h2, h3, h4, h5, h6,.h1, .h2, .h3, .h4, .h5, .h6,
                    .vs-btn.style13, .vs-btn.style12,
                    .watch-btn .btn-text,
                    .user-id-link,
                    .sec-description,
                    .sec-subtitle4,
                    .sec-subtitle5,
                    .sec-text2,
                    .big-letter,
                    .font-title,
                    .widgettitle,
                    .sidebar-area .wp-block-group__inner-container > h2,
                    .widget_search .wp-block-search__label:not(.screen-reader-text),
                    .widget_title,
                    .recent-post .post-title,
                    .info-media1 .media-label,
                    .widget_recent_entries ul li > a,
                    .widget_rss ul .rsswidget,
                    .header-links li,
                    .blog-style3 .blog-number,
                    .woocommerce-Reviews .woocommerce-noreviews,
                    .img-box2 .img-text,
                    .img-box3 .product-title,
                    .img-box3 .text-shape,
                    .media-style2 .media-title,
                    .media-style3 .media-title,
                    .media-style5 .media-number,
                    .media-style6 .media-title,
                    .quote-text,
                    .vs-info,
                    .package-style1 .package-price,
                    .package-style1.layout2 .vs-btn,
                    .testi-style2 .testi-text,
                    .vs-skillbar .skillbar-number,
                    .gallery-bar .bar-info,
                    .woocommerce-grouped-product-list.group_table .quantity label,
                    .product-style1 .product-price,
                    .product-style2 .product-price,
                    .cart_table td:before,
                    .cart_table th,
                    .tinv-wishlist th,
                    .tinv-wishlist td.product-name,
                    .tinv-wishlist .social-buttons > span,
                    .vs-pricing-layout2 .vs-price,
                    .vs-blog-layout3 .blog-number,
                    .about-image-box4 .experance-box .sec-title-style1,
                    .about-image-box4 .experance-box p.text-md
                '),
                'units'       =>'px',
                'subtitle'    => __('Typography option with each property can be called individually.', 'wellnez'),
            ),
            array(
                'id'          => 'body_typography',
                'type'        => 'typography', 
                'title'       => __( 'Body Typography', 'wellnez' ),
                'google'      => true, 
                'font-backup' => true,
                'output'      => array('
                    body,
                    label,
                    .title-area .sub-title,
                    .sec-subtitle2,
                    .sec-subtitle,
                    .font-body,
                    .latest-product .product-title,
                    .widget_rss ul cite,
                    .main-menu a,
                    .breadcumb-menu li,
                    .breadcumb-menu a,
                    .vs-pagination span,
                    .vs-pagination a,
                    .share-links-title,
                    .img-box3 .text-shape,
                    .form-style1 input,
                    .form-style10 select,
                    .form-style10 input,
                    .form-style8 select,
                    .form-style8 input,
                    .testi-style1 .testi-title,
                    .testi-style1 .testi-name,
                    .gallery-bar .bar-title,
                    .banner-style1 .banner-title,
                    .category-style1 .category-name,
                    .info-box .info-title,
                    .product_meta,
                    .cart_table .cart-productname,
                    p
                '),
                'units'       =>'px',
                'subtitle'    => __('Typography option with each property can be called individually.', 'wellnez'),
            ),
        )

    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Back To Top', 'wellnez' ),
        'id'               => 'wellnez_backtotop',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'wellnez_display_bcktotop',
                'type'     => 'switch',
                'title'    => esc_html__( 'Back To Top Button', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display back to top button.', 'wellnez' ),
                'default'  => true,
                'on'       => esc_html__( 'Enabled', 'wellnez' ),
                'off'      => esc_html__( 'Disabled', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_bcktotop_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Button Background Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Back to top button Background Color.', 'wellnez' ),
                'required' => array('wellnez_display_bcktotop','equals','1'),
                'output'   => array( 'background-color' =>'.scrollToTop' ),
            ),
            array(
                'id'       => 'wellnez_bcktotop_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Icon Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Back to top Icon Color.', 'wellnez' ),
                'required' => array('wellnez_display_bcktotop','equals','1'),
                'output'   => array( '.scrollToTop i' ),
            ),
            array(
                'id'       => 'wellnez_bcktotop_border_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Button Border Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Back to top button border Color.', 'wellnez' ),
                'required' => array('wellnez_display_bcktotop','equals','1'),
                'output'   => array( 'border-color' =>'.border-before-theme:before' ),
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Preloader', 'wellnez' ),
        'id'               => 'wellnez_preloader',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'wellnez_display_preloader',
                'type'     => 'switch',
                'title'    => esc_html__( 'Preloader', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch Enabled to Display Preloader.', 'wellnez' ),
                'default'  => true,
                'on'       => esc_html__('Enabled','wellnez'),
                'off'      => esc_html__('Disabled','wellnez'),
            ),
            array(
                'id'       => 'wellnez_preloader_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Preloader Color One', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Preloader Color One', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_preloader_color_two',
                'type'     => 'color',
                'title'    => esc_html__( 'Preloader Color Two', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Preloader Color Two', 'wellnez' ),
            ),
        )
    ));

    

    /* End General Fields */

    /* Admin Lebel Fields */
    Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Admin Label', 'wellnez' ),
        'id'                => 'wellnez_admin_label',
        'customizer_width'  => '450px',
        'subsection'        => true,
        'fields'            => array(
            array(
                'title'     => esc_html__( 'Admin Login Logo', 'wellnez' ),
                'subtitle'  => esc_html__( 'It belongs to the back-end of your website to log-in to admin panel.', 'wellnez' ),
                'id'        => 'wellnez_admin_login_logo',
                'type'      => 'media',
            ),
            array(
                'title'     => esc_html__( 'Custom CSS For admin', 'wellnez' ),
                'subtitle'  => esc_html__( 'Any CSS your write here will run in admin.', 'wellnez' ),
                'id'        => 'wellnez_theme_admin_custom_css',
                'type'      => 'ace_editor',
                'mode'      => 'css',
                'theme'     => 'chrome',
                'full_width'=> true,
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'icon'             => 'el  el-foursquare',
        'title'            => esc_html__( 'Logo', 'wellnez' ),
        'id'               => 'logo_settings',
        'customizer_width' => '450px',
        'fields'           => array(
            array(
                'id'       => 'logo',
                'type'     => 'media',
                'title'    => esc_html__( 'Logo', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose the site logo', 'wellnez' ),
                'default'  => array(
                    'url' => 'https://wordpress.vecurosoft.com/wellnez/wp-content/uploads/2023/03/logo.svg',
                ),
            ),
            array(
                'id'       => 'white_logo',
                'type'     => 'media',
                'title'    => esc_html__( 'White Logo', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose the site Dark logo', 'wellnez' ),
                'default'  => array(
                    'url' => 'https://wordpress.vecurosoft.com/wellnez/wp-content/uploads/2023/03/logo-4.svg',
                ),
            ),
    
        ),
    ) );

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header', 'wellnez' ),
        'id'               => 'wellnez_header',
        'customizer_width' => '400px',
        'icon'             => 'el el-credit-card',
        'fields'           => array(
            array(
                'id'       => 'wellnez_header_options',
                'type'     => 'button_set',
                'default'  => '1',
                'options'  => array(
                    "1"   => esc_html__('Prebuilt','wellnez'),
                    "2"      => esc_html__('Header Builder','wellnez'),
                ),
                'title'    => esc_html__( 'Header Options', 'wellnez' ),
                'subtitle' => esc_html__( 'Select header options.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_header_select_options',
                'type'     => 'select',
                'data'     => 'posts',
                'args'     => array(
                    'post_type'     => 'wellnez_header',
                    'posts_per_page' => -1,
                ),
                'title'    => esc_html__( 'Header', 'wellnez' ),
                'subtitle' => esc_html__( 'Select header.', 'wellnez' ),
                'required' => array( 'wellnez_header_options', 'equals', '2' )
            ),

        ),
    ) );
    // -> START Header Logo
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Logo', 'wellnez' ),
        'id'               => 'wellnez_header_logo_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'wellnez_site_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Site Logo', 'wellnez' ),
                'compiler' => 'true',
                'subtitle' => esc_html__( 'Upload your site logo for header ( recommendation png format ).', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_site_logo_dimensions',
                'type'     => 'dimensions',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Dimensions (Width/Height).', 'wellnez'),
                'output'   => array('.header-logo .logo img'),
                'subtitle' => esc_html__('Set logo dimensions to choose width, height, and unit.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_site_logomargin_dimensions',
                'type'     => 'spacing',
                'mode'     => 'margin',
                'output'   => array('.header-logo .logo img'),
                'units_extended' => 'false',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Top and Bottom Margin.', 'wellnez'),
                'left'     => false,
                'right'    => false,
                'subtitle' => esc_html__('Set logo top and bottom margin.', 'wellnez'),
                'default'            => array(
                    'units'           => 'px'
                )
            ),
            array(
                'id'       => 'wellnez_text_title',
                'type'     => 'text',
                'validate' => 'html',
                'title'    => esc_html__( 'Text Logo', 'wellnez' ),
                'subtitle' => esc_html__( 'Write your logo text use as logo ( You can use span tag for text color ).', 'wellnez' ),
            )
        )
    ) );
    // -> End Header Logo

    // -> START Header Menu
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Menu', 'wellnez' ),
        'id'               => 'wellnez_header_menu_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'wellnez_header_menu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Menu Color', 'wellnez' ),
                'output'   => array( 'color'    =>  '.menu-style1 > ul > li > a' ),
            ),
            array(
                'id'       => 'wellnez_header_menu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Hover Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Menu Hover Color', 'wellnez' ),
                'output'   => array( 'color'    =>  '.menu-style1 > ul > li > a:hover' ),
            ),
            array(
                'id'       => 'wellnez_header_submenu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Submenu Color', 'wellnez' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a' ),
            ),
            array(
                'id'       => 'wellnez_header_submenu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Hover Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Submenu Hover Color', 'wellnez' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a:hover' ),
            ),
            array(
                'id'       => 'wellnez_header_submenu_icon_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Icon Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Submenu Icon Color', 'wellnez' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a:after' ),
            ),
            array(
                'id'       => 'wellnez_header_submenu_border_top_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Border Top Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Submenu Border Top Color', 'wellnez' ),
                'output'   => array( 'border-top-color'    =>  '.main-menu ul.sub-menu' ),
            ),
            array(
                'id'       => 'wellnez_header_searchbar_switcher',
                'type'     => 'switch',
                'default'  => '0',
                'on'       => esc_html__( 'Show', 'wellnez' ),
                'off'      => esc_html__( 'Hide', 'wellnez' ),
                'title'    => esc_html__( 'Header Searchbar?', 'wellnez' ),
                'subtitle' => esc_html__( 'Control Header Searchbar By Show Or Hide System.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_searchbar_placeholder_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Placeholder Text', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Placeholder Text', 'wellnez' ),
                'default'  => esc_html__( 'Search Your Product', 'wellnez' ),
                'required' => array( "wellnez_header_searchbar_switcher", "equals", "1" )
            )
        )
    ) );
    // -> End Header Menu

     // -> START Mobile Menu
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Cart Offcanvas', 'wellnez' ),
        'id'               => 'wellnez_cart_offcanvas_menu',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'wellnez_offcanvas_panel_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Offcanvas Panel Background', 'wellnez' ),
                'output'   => array('.sidemenu-wrapper .sidemenu-content'),
                'subtitle' => esc_html__( 'Set Offcanvas Panel Background Color', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_offcanvas_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Offcanvas Title Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Offcanvas Title color.', 'wellnez' ),
                'output'   => array( '.sidemenu-content h3.widget_title' )
            ),
            array(
                'id'       => 'wellnez_offcanvas_product_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Product Title Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Product Title color.', 'wellnez' ),
                'output'   => array( '.widget_shopping_cart .cart_list li.mini_cart_item a' )
            ),
        )
    ) );
    // -> End Mobile Menu

    // -> START Blog Page
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Blog', 'wellnez' ),
        'id'         => 'wellnez_blog_page',
        'icon'  => 'el el-blogger',
        'fields'     => array(

            array(
                'id'       => 'wellnez_blog_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Layout', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose blog layout from here. If you use this option then you will able to change three type of blog layout ( Default Left Sidebar Layour ). ', 'wellnez' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'wellnez_blog_grid',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Post Column', 'wellnez' ),
                'subtitle' => esc_html__( 'Select your blog post column from here. If you use this option then you will able to select three type of blog post layout ( Default Two Column ).', 'wellnez' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/1column.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2column.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3column.png' )
                    ),

                ),
                'default'  => '1'
            ),

            array(
                'id'      => 'wellnez_blog_style',
                'type'     => 'select',
                'options'  => array(
                    'blog_style_one' => esc_html__('Blog Style One','wellnez'),
                    'blog_style_two' => esc_html__('Blog Style Two','wellnez'),
                ),
                'default'  => 'blog_style_one',
                'title'   => esc_html__('Blog Style', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_blog_page_title_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__('Show','wellnez'),
                'off'      => esc_html__('Hide','wellnez'),
                'title'    => esc_html__('Blog Page Title', 'wellnez'),
                'subtitle' => esc_html__('Control blog page title show / hide. If you use this option then you will able to show / hide your blog page title ( Default Setting Show ).', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_blog_page_title_setting',
                'type'     => 'button_set',
                'title'    => esc_html__('Blog Page Title Setting', 'wellnez'),
                'subtitle' => esc_html__('Control blog page title setting. If you use this option then you can able to show default or custom blog page title ( Default Blog ).', 'wellnez'),
                'options'  => array(
                    "predefine"   => esc_html__('Default','wellnez'),
                    "custom"      => esc_html__('Custom','wellnez'),
                ),
                'default'  => 'predefine',
                'required' => array("wellnez_blog_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'wellnez_blog_page_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Custom Title', 'wellnez'),
                'subtitle' => esc_html__('Set blog page custom title form here. If you use this option then you will able to set your won title text.', 'wellnez'),
                'required' => array('wellnez_blog_page_title_setting','equals','custom')
            ),
            array(
                'id'            => 'wellnez_blog_postExcerpt',
                'type'          => 'slider',
                'title'         => esc_html__('Blog Posts Excerpt', 'wellnez'),
                'subtitle'      => esc_html__('Control the number of characters you want to show in the blog page for each post.. If you use this option then you can able to control your blog post characters from here ( Default show 10 ).', 'wellnez'),
                "default"       => 46,
                "min"           => 0,
                "step"          => 1,
                "max"           => 100,
                'resolution'    => 1,
                'display_value' => 'text',
            ),
            array(
                'id'       => 'wellnez_blog_readmore_setting',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Read More Text Setting', 'wellnez' ),
                'subtitle' => esc_html__( 'Control read more text from here.', 'wellnez' ),
                'options'  => array(
                    "default"   => esc_html__('Default','wellnez'),
                    "custom"    => esc_html__('Custom','wellnez'),
                ),
                'default'  => 'default',
            ),
            array(
                'id'       => 'wellnez_blog_custom_readmore',
                'type'     => 'text',
                'title'    => esc_html__('Read More Text', 'wellnez'),
                'subtitle' => esc_html__('Set read moer text here. If you use this option then you will able to set your won text.', 'wellnez'),
                'required' => array('wellnez_blog_readmore_setting','equals','custom')
            ),
            array(
                'id'       => 'wellnez_blog_title_color',
                'output'   => array( '.vs-blog .blog-title a'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Blog Title Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_title_hover_color',
                'output'   => array( '.vs-blog .blog-title a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Hover Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Blog Title Hover Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_contant_color',
                'output'   => array( '.blog-content p'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Excerpt / Content Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Blog Excerpt / Content Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_read_more_button_color',
                'output'   => array( '.blog-content .link-btn'),
                'type'     => 'color',
                'title'    => esc_html__( 'Read More Button Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Read More Button Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_read_more_button_hover_color',
                'output'   => array( '.blog-content .link-btn:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Read More Button Hover Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Read More Button Hover Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_pagination_color',
                'output'   => array( '.pagination li a,.pagination a i'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Color', 'wellnez'),
                'subtitle' => esc_html__('Set Blog Pagination Color.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_blog_pagination_active_color',
                'output'   => array( '.pagination li span.current'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Active Color', 'wellnez'),
                'subtitle' => esc_html__('Set Blog Pagination Active Color.', 'wellnez'),
                'required'  => array('wellnez_blog_pagination', '=', '1')
            ),
            array(
                'id'       => 'wellnez_blog_pagination_hover_color',
                'output'   => array( '.pagination li a:hover,.pagination a i:hover'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Hover Color', 'wellnez'),
                'subtitle' => esc_html__('Set Blog Pagination Hover Color.', 'wellnez'),
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Blog Page', 'wellnez' ),
        'id'         => 'wellnez_post_detail_styles',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'wellnez_blog_single_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Layout', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose blog single page layout from here. If you use this option then you will able to change three type of blog single page layout ( Default Left Sidebar Layour ). ', 'wellnez' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'wellnez_post_details_title_position',
                'type'     => 'button_set',
                'default'  => 'header',
                'options'  => array(
                    'header'        => esc_html__('On Header','wellnez'),
                    'below'         => esc_html__('Below Thumbnail','wellnez'),
                ),
                'title'    => esc_html__('Blog Post Title Position', 'wellnez'),
                'subtitle' => esc_html__('Control blog post title position from here.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_post_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Details Custom Title', 'wellnez'),
                'subtitle' => esc_html__('This title will show in Breadcrumb title.', 'wellnez'),
                'required' => array('wellnez_post_details_title_position','equals','below')
            ),
            array(
                'id'       => 'wellnez_display_post_tags',
                'type'     => 'switch',
                'title'    => esc_html__( 'Tags', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display Tags.', 'wellnez' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','wellnez'),
                'off'       => esc_html__('Disabled','wellnez'),
            ),
            array(
                'id'       => 'wellnez_post_details_share_options',
                'type'     => 'switch',
                'title'    => esc_html__('Share Options', 'wellnez'),
                'subtitle' => esc_html__('Control post share options from here. If you use this option then you will able to show or hide post share options.', 'wellnez'),
                'on'        => esc_html__('Show','wellnez'),
                'off'       => esc_html__('Hide','wellnez'),
                'default'   => '0',
            ),
            array(
                'id'       => 'wellnez_post_details_author_desc_trigger',
                'type'     => 'switch',
                'title'    => esc_html__('Biography Info', 'wellnez'),
                'subtitle' => esc_html__('Control biography info from here. If you use this option then you will able to show or hide biography info ( Default setting Show ).', 'wellnez'),
                'on'        => esc_html__('Show','wellnez'),
                'off'       => esc_html__('Hide','wellnez'),
                'default'   => '0',
            ),
            array(
                'id'       => 'wellnez_post_details_post_navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Post Navigation', 'wellnez'),
                'subtitle' => esc_html__('Control post navigation from here. If you use this option then you will able to show or hide post navigation ( Default setting Show ).', 'wellnez'),
                'on'        => esc_html__('Show','wellnez'),
                'off'       => esc_html__('Hide','wellnez'),
                'default'   => true,
            ),
            array(
                'id'       => 'wellnez_post_details_related_post',
                'type'     => 'switch',
                'title'    => esc_html__('Related Post', 'wellnez'),
                'subtitle' => esc_html__('Control related post from here. If you use this option then you will able to show or hide related post ( Default setting Show ).', 'wellnez'),
                'on'        => esc_html__('Show','wellnez'),
                'off'       => esc_html__('Hide','wellnez'),
                'default'   => false,
            ),
        )
    ));

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Meta Data', 'wellnez' ),
        'id'         => 'wellnez_common_meta_data',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'wellnez_blog_meta_icon_color',
                'output'   => array( '.blog-meta span i'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Meta Icon Color', 'wellnez'),
                'subtitle' => esc_html__('Set Blog Meta Icon Color.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_blog_meta_text_color',
                'output'   => array( '.blog-meta a,.blog-meta span'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Text Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Blog Meta Text Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_blog_meta_text_hover_color',
                'output'   => array( '.blog-meta a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Hover Text Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Blog Meta Hover Text Color.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_display_post_date',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Date', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Date.', 'wellnez' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','wellnez'),
                'off'       => esc_html__('Disabled','wellnez'),
            ),
            array(
                'id'       => 'wellnez_display_post_author',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Auhtor', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Author.', 'wellnez' ),
                'default'  => true,
                'on'        => esc_html__( 'Enabled', 'wellnez'),
                'off'       => esc_html__( 'Disabled', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_display_post_comment',
                'type'     => 'switch',
                'title'    => esc_html__( 'Comment Count', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display Comment Count.', 'wellnez' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','wellnez'),
                'off'       => esc_html__('Disabled','wellnez'),
            ),
            array(
                'id'       => 'wellnez_display_post_category',
                'type'     => 'switch',
                'title'    => esc_html__( 'Category', 'wellnez' ),
                'subtitle' => esc_html__( 'Switch On to Display Category.', 'wellnez' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','wellnez'),
                'off'       => esc_html__('Disabled','wellnez'),
            ),
        )
    ));

    /* Sidebar Start */
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Sidebar Options', 'wellnez' ),
        'id'         => 'wellnez_sidebar_options',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'      => 'wellnez_sidebar_bg_color',
                'type'    => 'color',
                'title'   => esc_html__('Widgets Background Color', 'wellnez'),
                'output'  => array('background-color'   => '.blog-sidebar')
            ),
            array(
                'id'      => 'wellnez_sidebar_padding_margin_box_shadow_trigger',
                'type'    => 'switch',
                'title'   => esc_html__('Widgets Custom Box Shadow/Padding/Margin/border', 'wellnez'),
                'on'      => esc_html__('Show','wellnez'),
                'off'     => esc_html__('Hide','wellnez'),
                'default' => false
            ),
            array(
                'id'      => 'box-shadow',
                'type'    => 'box_shadow',
                'title'   => esc_html__('Box Shadow', 'wellnez'),
                'units'   => array( 'px', 'em', 'rem' ),
                'output'  => ( '.blog-sidebar .widget' ),
                'opacity' => true,
                'rgba'    => true,
                'required'=> array( 'wellnez_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_margin',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Margin', 'wellnez'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'mode'    => 'margin',
                'required'=> array( 'wellnez_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_padding',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Padding', 'wellnez'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'mode'    => 'padding',
                'required'=> array( 'wellnez_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_border',
                'type'    => 'border',
                'title'   => esc_html__('Widget Border', 'wellnez'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'all'     => false,
                'required'=> array( 'wellnez_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_title_heading_tag',
                'type'     => 'select',
                'options'  => array(
                    'h1'        => esc_html__('H1','wellnez'),
                    'h2'        => esc_html__('H2','wellnez'),
                    'h3'        => esc_html__('H3','wellnez'),
                    'h4'        => esc_html__('H4','wellnez'),
                    'h5'        => esc_html__('H5','wellnez'),
                    'h6'        => esc_html__('H6','wellnez'),
                ),
                'default'  => 'h4',
                'title'   => esc_html__('Widget Title Tag', 'wellnez'),
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_title_margin',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Title Margin', 'wellnez'),
                'mode'    => 'margin',
                'output'  => array('.blog-sidebar .widget .widget_title'),
                'units'   => array('em', 'px'),
            ),
            array(
                'id'      => 'wellnez_sidebar_widget_title_padding',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Title Padding', 'wellnez'),
                'mode'    => 'padding',
                'output'  => array('.blog-sidebar .widget .widget_title'),
                'units'   => array('em', 'px'),
            ),
            array(
                'id'       => 'wellnez_sidebar_widget_title_color',
                'output'   =>  array('.blog-sidebar .widget .widget_title h1,.blog-sidebar .widget .widget_title h2,.blog-sidebar .widget .widget_title h3,.blog-sidebar .widget .widget_title h4,.blog-sidebar .widget .widget_title h5,.blog-sidebar .widget .widget_title h6'),
                'type'     => 'color',
                'title'    => esc_html__('Widget Title Color', 'wellnez'),
                'subtitle' => esc_html__('Set Widget Title Color.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_sidebar_widget_text_color',
                'output'   => array('.blog-sidebar .widget'),
                'type'     => 'color',
                'title'    => esc_html__('Widget Text Color', 'wellnez'),
                'subtitle' => esc_html__('Set Widget Text Color.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_sidebar_widget_anchor_color',
                'type'     => 'color',
                'output'   => array('.blog-sidebar .widget a'),
                'title'    => esc_html__('Widget Anchor Color', 'wellnez'),
                'subtitle' => esc_html__('Set Widget Anchor Color.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_sidebar_widget_anchor_hover_color',
                'type'     => 'color',
                'output'   => array('.blog-sidebar .widget a:hover'),
                'title'    => esc_html__('Widget Hover Color', 'wellnez'),
                'subtitle' => esc_html__('Set Widget Anchor Hover Color.', 'wellnez'),
            )
        )
    ));
    /* Sidebar End */

    /* End blog Page */

    // -> START Page Option
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page', 'wellnez' ),
        'id'         => 'wellnez_page_page',
        'icon'  => 'el el-file',
        'fields'     => array(
            array(
                'id'       => 'wellnez_page_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select layout', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose your page layout. If you use this option then you will able to choose three type of page layout ( Default no sidebar ). ', 'wellnez' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'wellnez_page_layoutopt',
                'type'     => 'button_set',
                'title'    => esc_html__('Sidebar Settings', 'wellnez'),
                'subtitle' => esc_html__('Set page sidebar. If you use this option then you will able to set three type of sidebar ( Default no sidebar ).', 'wellnez'),
                //Must provide key => value pairs for options
                'options' => array(
                    '1' => esc_html__( 'Page Sidebar', 'wellnez' ),
                    '2' => esc_html__( 'Blog Sidebar', 'wellnez' )
                 ),
                'default' => '1',
                'required'  => array('wellnez_page_sidebar','!=','1')
            ),
            array(
                'id'       => 'wellnez_page_title_switcher',
                'type'     => 'switch',
                'title'    => esc_html__('Title', 'wellnez'),
                'subtitle' => esc_html__('Switch enabled to display page title. Fot this option you will able to show / hide page title.  Default setting Enabled', 'wellnez'),
                'default'  => '1',
                'on'        => esc_html__('Enabled','wellnez'),
                'off'       => esc_html__('Disabled','wellnez'),
            ),
            array(
                'id'       => 'wellnez_page_title_tag',
                'type'     => 'select',
                'options'  => array(
                    'h1'        => esc_html__('H1','wellnez'),
                    'h2'        => esc_html__('H2','wellnez'),
                    'h3'        => esc_html__('H3','wellnez'),
                    'h4'        => esc_html__('H4','wellnez'),
                    'h5'        => esc_html__('H5','wellnez'),
                    'h6'        => esc_html__('H6','wellnez'),
                ),
                'default'  => 'h1',
                'title'    => esc_html__( 'Title Tag', 'wellnez' ),
                'subtitle' => esc_html__( 'Select page title tag. If you use this option then you can able to change title tag H1 - H6 ( Default tag H1 )', 'wellnez' ),
                'required' => array("wellnez_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'wellnez_allHeader_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Title Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Title Color', 'wellnez' ),
                'output'   => array( 'color' => '.breadcumb-title' ),
            ),
            array(
                'id'       => 'wellnez_allHeader_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background', 'wellnez' ),
                'output'   => array('.breadcumb-wrapper'),
                'subtitle' => esc_html__( 'Setting page header background. If you use this option then you will able to set Background Color, Background Image, Background Repeat, Background Size, Background Attachment, Background Position.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_enable_breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__( 'Breadcrumb Hide/Show', 'wellnez' ),
                'subtitle' => esc_html__( 'Hide / Show breadcrumb from all pages and posts ( Default settings hide ).', 'wellnez' ),
                'default'  => '1',
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wellnez_allHeader_breadcrumbtextcolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text color here.If you user this option then you will able to set page breadcrumb color.', 'wellnez' ),
                'required' => array("wellnez_page_title_switcher","equals","1"),
                'output'   => array( 'color' => '.breadcumb-layout1 .breadcumb-content ul li a' ),
            ),
            array(
                'id'       => 'wellnez_allHeader_breadcrumbtextactivecolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Active Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text active color here.If you user this option then you will able to set page breadcrumb active color.', 'wellnez' ),
                'required' => array( "wellnez_page_title_switcher", "equals", "1" ),
                'output'   => array( 'color' => '.breadcumb-layout1 .breadcumb-content ul li.active' ),
            ),
            array(
                'id'       => 'wellnez_allHeader_dividercolor',
                'type'     => 'color',
                'output'   => array( 'color'=>'.breadcumb-layout1 .breadcumb-content ul li:after' ),
                'title'    => esc_html__( 'Breadcrumb Divider Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose breadcrumb divider color.', 'wellnez' ),
            ),
        ),
    ) );
    /* End Page option */

    // -> START 404 Page

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( '404 Page', 'wellnez' ),
        'id'         => 'wellnez_404_page',
        'icon'       => 'el el-ban-circle',
        'fields'     => array(

            array(
                'title'     => esc_html__( '404 Page Background', 'wellnez' ),
                'subtitle'  => esc_html__( 'Add Your 404 Page Background Image', 'wellnez' ),
                'id'        => 'wellnez_error_bg',
                'type'      => 'media',
            ),

            array(
                'id'       => 'wellnez_fof_error_number',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Error Number', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Page Error Number', 'wellnez' ),
                'default'  => esc_html__( '404', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_fof_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Title', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Page Title', 'wellnez' ),
                'default'  => esc_html__( 'Oops! That page can\'t be found.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_fof_subtitle',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Subtitle', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Page Subtitle ', 'wellnez' ),
                'default'  => esc_html__( 'The page you\'ve requested is not available.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_fof_btn_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Button Text', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Button Text ', 'wellnez' ),
                'default'  => esc_html__( 'Return To Home', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_fof_text_color',
                'type'     => 'color',
                'output'   => array( '.error-content .error-number' ),
                'title'    => esc_html__( 'Title Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Pick a title color', 'wellnez' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'wellnez_fof_subtitle_color',
                'type'     => 'color',
                'output'   => array( '.error-content .error-title' ),
                'title'    => esc_html__( 'Subtitle Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Pick a subtitle color', 'wellnez' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'wellnez_fof_btn_color',
                'type'     => 'color',
                'output'   => array( '.vs-btn' ),
                'title'    => esc_html__('Button Color', 'wellnez'),
                'subtitle' => esc_html__('Pick Button Color', 'wellnez'),
                'validate' => 'color'
            ),
            array(
                'id'       => 'wellnez_fof_btn_color_hover',
                'type'     => 'color',
                'output'   => array( '.vs-btn:hover' ),
                'title'    => esc_html__( 'Button Color', 'wellnez'),
                'subtitle' => esc_html__( 'Pick Button Color', 'wellnez'),
                'validate' => 'color'
            ),
            array(
                'id'       => 'wellnez_fof_btn_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Background Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Pick Button Background Color', 'wellnez' ),
                'output'   => array( 'background-color' => '.vs-btn' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'wellnez_fof_btn_hover_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Hover Background Color', 'wellnez' ),
                'subtitle' => esc_html__( 'Pick Button Hover Background Color', 'wellnez' ),
                'output'   => array( 'background-color' => '.vs-btn:after' ),
                'validate' => 'color'
            ),
        ),
    ) );

    /* End 404 Page */
    // -> START Woo Page Option

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Woocommerce Page', 'wellnez' ),
        'id'         => 'wellnez_woo_page_page',
        'icon'  => 'el el-shopping-cart',
        'fields'     => array(
             array(
                'title'     => esc_html__( 'Shop Page Background', 'wellnez' ),
                'subtitle'  => esc_html__( 'Add Your Shop Page Background Image', 'wellnez' ),
                'id'        => 'wellnez_shop_bg',
                'type'      => 'media',
            ),
            array(
                'id'       => 'wellnez_woo_shoppage_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Set Shop Page Sidebar.', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose shop page sidebar', 'wellnez' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'wellnez_woo_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Column', 'wellnez' ),
                'subtitle' => esc_html__( 'Set your woocommerce product column.', 'wellnez' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '2' => array(
                        'alt' => esc_attr__('2 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('3 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '4' => array(
                        'alt' => esc_attr__('4 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '5' => array(
                        'alt' => esc_attr__('5 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/5col.png')
                    ),
                    '6' => array(
                        'alt' => esc_attr__('6 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),
                    '5' => array(
                        'alt' => esc_attr__('5 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/5col.png')
                    ),
                    '6' => array(
                        'alt' => esc_attr__('6 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),),
                'default'  => '4'
            ),
			array(
                'id'       => 'wellnez_woo_product_perpage',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Per Page', 'wellnez' ),
				'default' => '10'
            ),
            array(
                'id'       => 'wellnez_woo_singlepage_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Single Page sidebar', 'wellnez' ),
                'subtitle' => esc_html__( 'Choose product single page sidebar.', 'wellnez' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'wellnez_product_details_title_position',
                'type'     => 'button_set',
                'default'  => 'header',
                'options'  => array(
                    'header'        => esc_html__('On Header','wellnez'),
                    'below'         => esc_html__('Below Thumbnail','wellnez'),
                ),
                'title'    => esc_html__('Product Details Title Position', 'wellnez'),
                'subtitle' => esc_html__('Control product details title position from here.', 'wellnez'),
            ),
            array(
                'id'       => 'wellnez_product_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Details Title', 'wellnez' ),
                'default'  => esc_html__( 'Shop Details', 'wellnez' ),
                'required' => array('wellnez_product_details_title_position','equals','below'),
            ),
            array(
                'id'       => 'wellnez_woo_stock_quantity_show_hide',
                'type'     => 'switch',
                'title'    => esc_html__( 'Stock Quantity Show Hide', 'wellnez' ),
                'subtitle' => esc_html__( 'Set Stock Quantity Show Hide?', 'wellnez' ),
                'default'  => '1',
                'on'       => esc_html__('Show','wellnez'),
                'off'      => esc_html__('Hide','wellnez')
            ),
            array(
                'id'       => 'wellnez_woo_relproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Related product Hide/Show', 'wellnez' ),
                'subtitle' => esc_html__( 'Hide / Show related product in single page (Default Settings Show)', 'wellnez' ),
                'default'  => '1',
                'on'       => esc_html__('Show','wellnez'),
                'off'      => esc_html__('Hide','wellnez')
            ),
			array(
                'id'       => 'wellnez_woo_relproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Related products number', 'wellnez' ),
                'subtitle' => esc_html__( 'Set how many related products you want to show in single product page.', 'wellnez' ),
                'default'  => 4,
                'required' => array('wellnez_woo_relproduct_display','equals',true)
            ),

            array(
                'id'       => 'wellnez_woo_related_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Related Product Column', 'wellnez' ),
                'subtitle' => esc_html__( 'Set your woocommerce related product column.', 'wellnez' ),
                'required' => array('wellnez_woo_relproduct_display','equals',true),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'wellnez_woo_upsellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Upsell product Hide/Show', 'wellnez' ),
                'subtitle' => esc_html__( 'Hide / Show upsell product in single page (Default Settings Show)', 'wellnez' ),
                'default'  => '1',
                'on'       => esc_html__('Show','wellnez'),
                'off'      => esc_html__('Hide','wellnez'),
            ),
            array(
                'id'       => 'wellnez_woo_upsellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Upsells products number', 'wellnez' ),
                'subtitle' => esc_html__( 'Set how many upsells products you want to show in single product page.', 'wellnez' ),
                'default'  => 3,
                'required' => array('wellnez_woo_upsellproduct_display','equals',true),
            ),

            array(
                'id'       => 'wellnez_woo_upsell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Upsells Product Column', 'wellnez' ),
                'subtitle' => esc_html__( 'Set your woocommerce upsell product column.', 'wellnez' ),
                'required' => array('wellnez_woo_upsellproduct_display','equals',true),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
            array(
                'id'       => 'wellnez_woo_crosssellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Cross sell product Hide/Show', 'wellnez' ),
                'subtitle' => esc_html__( 'Hide / Show cross sell product in single page (Default Settings Show)', 'wellnez' ),
                'default'  => '1',
                'on'       => esc_html__( 'Show', 'wellnez' ),
                'off'      => esc_html__( 'Hide', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_woo_crosssellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Cross sell products number', 'wellnez' ),
                'subtitle' => esc_html__( 'Set how many cross sell products you want to show in single product page.', 'wellnez' ),
                'default'  => 3,
                'required' => array('wellnez_woo_crosssellproduct_display','equals',true),
            ),

            array(
                'id'       => 'wellnez_woo_crosssell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Cross sell Product Column', 'wellnez' ),
                'subtitle' => esc_html__( 'Set your woocommerce cross sell product column.', 'wellnez' ),
                'required' => array( 'wellnez_woo_crosssellproduct_display', 'equals', true ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','wellnez'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','wellnez'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
        ),
    ) );

    /* End Woo Page option */

    // -> START Subscribe
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Subscribe', 'wellnez' ),
        'id'         => 'wellnez_subscribe_page',
        'icon'       => 'el el-eject',
        'fields'     => array(

            array(
                'id'       => 'wellnez_subscribe_apikey',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp API Key', 'wellnez' ),
                'subtitle' => esc_html__( 'Set mailchimp api key.', 'wellnez' ),
            ),
            array(
                'id'       => 'wellnez_subscribe_listid',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp List ID', 'wellnez' ),
                'subtitle' => esc_html__( 'Set mailchimp list id.', 'wellnez' ),
            ),
        ),
    ) );

    /* End Subscribe */

    // -> START Social Media

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Social', 'wellnez' ),
        'id'         => 'wellnez_social_media',
        'icon'      => 'el el-globe',
        'desc'      => esc_html__( 'Social', 'wellnez' ),
        'fields'     => array(
            array(
                'id'          => 'wellnez_social_links',
                'type'        => 'slides',
                'title'       => esc_html__('Social Profile Links', 'wellnez'),
                'subtitle'    => esc_html__('Add social icon and url.', 'wellnez'),
                'show'        => array(
                    'title'          => true,
                    'description'    => true,
                    'progress'       => false,
                    'facts-number'   => false,
                    'facts-title1'   => false,
                    'facts-title2'   => false,
                    'facts-number-2' => false,
                    'facts-title3'   => false,
                    'facts-number-3' => false,
                    'url'            => true,
                    'project-button' => false,
                    'image_upload'   => false,
                ),
                'placeholder'   => array(
                    'icon'          => esc_html__( 'Icon (example: fa fa-facebook) ','wellnez'),
                    'title'         => esc_html__( 'Social Icon Class', 'wellnez' ),
                    'description'   => esc_html__( 'Social Icon Title', 'wellnez' ),
                ),
            ),
        ),
    ) );

    /* End social Media */


    // -> START Footer Media
    Redux::setSection( $opt_name , array(
       'title'            => esc_html__( 'Footer', 'wellnez' ),
       'id'               => 'wellnez_footer',
       'desc'             => esc_html__( 'wellnez Footer', 'wellnez' ),
       'customizer_width' => '400px',
       'icon'              => 'el el-photo',
   ) );

   Redux::setSection( $opt_name, array(
       'title'      => esc_html__( 'Pre-built Footer / Footer Builder', 'wellnez' ),
       'id'         => 'wellnez_footer_section',
       'subsection' => true,
       'fields'     => array(
            array(
               'id'       => 'wellnez_footer_builder_trigger',
               'type'     => 'button_set',
               'default'  => 'prebuilt',
               'options'  => array(
                   'footer_builder'        => esc_html__('Footer Builder','wellnez'),
                   'prebuilt'              => esc_html__('Pre-built Footer','wellnez'),
               ),
               'title'    => esc_html__( 'Footer Builder', 'wellnez' ),
            ),
            array(
               'id'       => 'wellnez_footer_builder_select',
               'type'     => 'select',
               'required' => array( 'wellnez_footer_builder_trigger','equals','footer_builder'),
               'data'     => 'posts',
               'args'     => array(
                   'post_type'     => 'wellnez_footer',
                   'posts_per_page' => -1,
               ),
               'on'       => esc_html__( 'Enabled', 'wellnez' ),
               'off'      => esc_html__( 'Disable', 'wellnez' ),
               'title'    => esc_html__( 'Select Footer', 'wellnez' ),
               'subtitle' => esc_html__( 'First make your footer from footer custom types then select it from here.', 'wellnez' ),
            ),
           array(
               'id'       => 'wellnez_disable_footer_bottom',
               'type'     => 'switch',
               'title'    => esc_html__( 'Footer Bottom?', 'wellnez' ),
               'default'  => 1,
               'on'       => esc_html__('Enabled','wellnez'),
               'off'      => esc_html__('Disable','wellnez'),
               'required' => array('wellnez_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
               'id'       => 'wellnez_footer_bottom_background',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Bottom Background Color', 'wellnez' ),
               'required' => array( 'wellnez_disable_footer_bottom','=','1' ),
               'output'   => array( 'background-color'   =>   '.footer-copyright' ),
            ),
            array(
               'id'       => 'wellnez_copyright_text',
               'type'     => 'text',
               'title'    => esc_html__( 'Copyright Text', 'wellnez' ),
               'subtitle' => esc_html__( 'Add Copyright Text', 'wellnez' ),
               'default'  => sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a class="text-white" href="%s">%s</a> All Rights Reserved by <a class="text-white" href="%s">%s</a>',date('Y'),esc_url('#'),__( 'Wellnez.','wellnez' ),esc_url('#'),__( 'Vecuro', 'wellnez' ) ),
               'required' => array( 'wellnez_disable_footer_bottom','equals','1' ),
            ),
            array(
               'id'       => 'wellnez_footer_copyright_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Text Color', 'wellnez' ),
               'subtitle' => esc_html__( 'Set footer copyright text color', 'wellnez' ),
               'required' => array( 'wellnez_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p' ),
            ),
            array(
               'id'       => 'wellnez_footer_copyright_acolor',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Ancor Color', 'wellnez' ),
               'subtitle' => esc_html__( 'Set footer copyright ancor color', 'wellnez' ),
               'required' => array( 'wellnez_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p a' ),
            ),
            array(
               'id'       => 'wellnez_footer_copyright_a_hover_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Ancor Hover Color', 'wellnez' ),
               'subtitle' => esc_html__( 'Set footer copyright ancor Hover color', 'wellnez' ),
               'required' => array( 'wellnez_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p a:hover' ),
            ),

       ),
   ) );


    /* End Footer Media */

    // -> START Custom Css
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Custom Css', 'wellnez' ),
        'id'         => 'wellnez_custom_css_section',
        'icon'  => 'el el-css',
        'fields'     => array(
            array(
                'id'       => 'wellnez_css_editor',
                'type'     => 'ace_editor',
                'title'    => esc_html__('CSS Code', 'wellnez'),
                'subtitle' => esc_html__('Paste your CSS code here.', 'wellnez'),
                'mode'     => 'css',
                'theme'    => 'monokai',
            )
        ),
    ) );

    /* End custom css */



    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'wellnez' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'wellnez' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'wellnez' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }