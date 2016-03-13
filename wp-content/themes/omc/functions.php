<?php
/**
 * omc framework.
 * 
 * @theme omc
 * @version 2.00
 * @author  Chang
 */
//var_dump($_SERVER);

//* Run the omc_pre Hook
do_action( 'omc_pre' );


// Timezone
global $timezone;
$timezone = new DateTimeZone( timezone_name_from_abbr( '', get_option('gmt_offset') * HOUR_IN_SECONDS, 0 ) );


/******************
 * LOAD  FRAMEWORK & SETUP
 ******************/

/**
 * Activates default theme features.
 */
add_action( 'omc_init', 'omc_theme_support' );
function omc_theme_support() {
	
	// Let WordPress manage the document title, no hard-coded <title> tag
	add_theme_support( 'title-tag' );
	
	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	
	// Support HTML5
	//add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Support for Post Formats
	//add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	
}

/**
 * Register menu location
 */
add_action( 'omc_init', 'omc_menu_location' );
function omc_menu_location() {
	register_nav_menus( array( 'top-right' => 'Top Right' ) );
	add_filter('nav_menu_css_class' , function( $classes, $item ){
		if( in_array( 'current-menu-item', $classes ) )
			$classes[] = 'active ';
    return $classes;
	},10 ,2 );
}


/**
 * Defines the omc theme constants
 */
add_action( 'omc_init', 'omc_constants' );
function omc_constants() {
	
	// Define development mode
	define( 'WP_DEV', true );
	
	//* Define Directory Location Constants
	define( 'PARENT_DIR', wp_normalize_path( get_template_directory() ) );
	define( 'CHILD_DIR', wp_normalize_path( get_stylesheet_directory() ) );
	define( 'OMC_IMG_DIR', PARENT_DIR . '/assets/img' );
	define( 'OMC_JS_DIR', PARENT_DIR . '/assets/js' );
	define( 'OMC_JS_THEME_DIR', PARENT_DIR . '/assets/js/theme' );
	define( 'OMC_JS_PLUGIN_DIR', PARENT_DIR . '/assets/js/plugin' );
	define( 'OMC_BOOTSTRAP_DIR', PARENT_DIR . '/assets/js-plugin/bootstrap/' );
	define( 'OMC_CSS_DIR', PARENT_DIR . '/assets/css' );
	define( 'OMC_CSS_THEME_DIR', OMC_CSS_DIR . '/theme' );
	define( 'OMC_INC_DIR', PARENT_DIR . '/inc' );
	define( 'OMC_ADMIN_DIR', OMC_INC_DIR . '/admin' );
	define( 'OMC_ADMIN_CLASS_DIR', OMC_INC_DIR . '/admin/class' );
	define( 'OMC_CLASS_DIR', OMC_INC_DIR . '/class' );
	define( 'OMC_POST_TYPE_DIR', OMC_INC_DIR . '/post-type' );
	define( 'OMC_COOKIES_DIR', OMC_INC_DIR . '/cookies' );
	define( 'OMC_STRUCTURE_DIR', OMC_INC_DIR . '/structure' );
	define( 'OMC_FUNCTION_DIR', OMC_INC_DIR . '/functions' );
	define( 'OMC_SHORTCODE_DIR', OMC_INC_DIR . '/shortcodes' );
	define( 'OMC_APPS_DIR', OMC_INC_DIR . '/apps' );
	define( 'OMC_TEMPLATE_DIR', PARENT_DIR . '/templates' );
	define( 'OMC_SAMPLE_DIR', PARENT_DIR . '/samples' );
	
	// Define component directory
	define( 'OMC_COMPONENT_DIR', PARENT_DIR . '/component' );
	define( 'OMC_COMMON_DIR', OMC_COMPONENT_DIR . '/common' );
	define( 'OMC_HEADER_DIR', OMC_COMMON_DIR . '/header' );
	define( 'OMC_FOOTER_DIR', OMC_COMMON_DIR . '/footer' );

	//* Define URL Location Constants	
	define( 'HOME_URL', home_url() );
	define( 'PARENT_URL', get_template_directory_uri() );
	define( 'CHILD_URL', get_stylesheet_directory_uri() );
	define( 'OMC_IMG_URL', PARENT_URL . '/assets/img' );
	define( 'OMC_JS_URL', PARENT_URL . '/assets/js' );
	define( 'OMC_JS_PLUGIN_URL', PARENT_URL . '/assets/js/plugin' );
	define( 'OMC_CSS_URL', PARENT_URL . '/assets/css' );
	define( 'OMC_FONTS_URL', OMC_CSS_URL . '/fonts' );
	define( 'OMC_INC_URL', PARENT_URL . '/inc' );
	define( 'OMC_ADMIN_URL', OMC_INC_URL . '/admin' );
	define( 'OMC_CLASS_URL', OMC_INC_URL . '/class' );
	define( 'OMC_STRUCTURE_URL', OMC_INC_URL . '/structure' );
	define( 'OMC_FUNCTION_URL', OMC_INC_URL . '/functions' );
	define( 'OMC_SHORTCODE_URL', OMC_INC_URL . '/shortcodes' );
	$upload_dir = wp_upload_dir();
	define( 'UPLOAD_URL', $upload_dir['baseurl'] );
	
	//* Define Settings Field Constants (for DB storage)
	define( 'OMC_SETTINGS_FIELD', 'omc-settings' );
	define( 'OMC_CSS_SETTINGS_FIELD', 'omc-css-settings' );
	
	// Hash
	define( 'OMC_FRONTEND_AJAX_HASH', md5( 'omc ajax hash' ) );
	
	// Define extra constants
	do_action( 'omc_extra_contants' );
	
}

/**
 * Loads apps
 */
add_action( 'omc_init', 'omc_load_apps' );
function omc_load_apps() {
	
}

/**
 * Loads all the framework files and features.
 */
add_action( 'omc_init', 'omc_load_framework' );
function omc_load_framework() {

	// Before framework load
	do_action( 'omc_pre_framework' );
	
	/*
	 * Load in both front and backend
	 */
	
	// Load Functions
	require_once OMC_FUNCTION_DIR . '/general.php';
	require_once OMC_FUNCTION_DIR . '/options.php';
	require_once OMC_FUNCTION_DIR . '/formatting.php';
	require_once OMC_FUNCTION_DIR . '/html-helper.php';
	require_once OMC_FUNCTION_DIR . '/general-template.php';
	
	// Load class
	require_once OMC_CLASS_DIR . '/class-wp-exception.php';
	
	
	// Load Javascript
	require_once OMC_INC_DIR . '/load-scripts/load-scripts.php';
	
	// Load CSS
	require_once OMC_INC_DIR . '/load-scripts/load-styles.php';
	
	
	// Load post type class
	require_once OMC_POST_TYPE_DIR.'/abstract-post-type-object.php';
	require_once OMC_POST_TYPE_DIR.'/abstract-post-type-custom-settings.php';
	require_once OMC_POST_TYPE_DIR.'/abstract-post-type-ajax.php';
	
	// Load default post type class
	require_once OMC_POST_TYPE_DIR.'/page.php';	
	
	/*
	 * Load in frontend only
	 */
	if( !is_admin() || omc_is_ajax() == 'front'	){
	
		// Load structure
		require_once OMC_STRUCTURE_DIR . '/header.php';
		require_once OMC_STRUCTURE_DIR . '/footer.php';
	} 
	
	/*
	 * Load in backend only
	 */
	else {
	
		// Load Admin
		require_once OMC_ADMIN_DIR . '/functions.php';
		require_once OMC_ADMIN_DIR . '/add-admin-notice.php';
		require_once OMC_CLASS_DIR . '/class-omc-sanitization.php';
		require_once OMC_ADMIN_DIR . '/add-admin-menu.php';
		
		// Load post type class
		require_once OMC_POST_TYPE_DIR.'/admin/abstract-post-type-admin.php';
		
		// Load default post type class
		require_once OMC_POST_TYPE_DIR.'/admin/page.php';		
	}
	
	
	require_once OMC_POST_TYPE_DIR.'/page.php';
	
	
	/**
	 * Loads mainframe
	 */
	require_once OMC_INC_DIR.'/mainframe/mainframe.php';
	main();
	
}


/*******************
 * DISABLE DEFAULT
 *******************/
 
/**
 * Disable pingback
 */
add_filter( 'xmlrpc_methods', 'omc_block_xmlrpc_attacks' );
add_filter( 'wp_headers', 'omc_disable_pingback' );
function omc_disable_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
function omc_block_xmlrpc_attacks( $methods ){
	unset( $methods['pingback.ping'] );
	unset( $methods['pingback.extensions.getPingbacks'] );
	return $methods;
}

/**
 * [REMOVE] Disable rich editing or visual editor
 * 
 * Refer to wp function user_can_richedit() at /wp-include/genaral-template.php line 2376
 */
//add_filter( 'user_can_richedit', 'omc_disable_rich_editing' );
function omc_disable_rich_editing( $wp_rich_edit ){
	// 'disable_rich_editing' option is set in theme setting page
	if( omc_get_option( 'disable_rich_editing' ) )
		return false;
	return $wp_rich_edit;
}

/*
 * Set Unique user cookies
 * Login type and annonymous type
 */
add_action( 'init', 'omc_cookie_routine' );
function omc_cookie_routine(){
	require_once OMC_APPS_DIR.'/cookie/cookie.php';
	
	if( is_user_logged_in() ){
		$user_hash = md5( get_current_user_id() );
		Cookie::Set('login_user_id', $user_hash, Cookie::Lifetime, '/' );
	} 
	
	elseif( !isset( $_COOKIE['annonymous_user_id'] ) ){
		$user_hash = md5( rand() . uniqid() . rand() );
		Cookie::Set('annonymous_user_id', $user_hash, Cookie::Lifetime, '/' );
	}
	
	if( isset( $user_hash ) )
		Cookie::Set('unique_user_id', $user_hash, Cookie::Lifetime, '/' );
}

/**
 * omc framework initiates here
 */
do_action( 'omc_init' );

/**
 * All omc framework is initiated
 */
do_action( 'omc_initiated' );

// Start session
maybe_start_session();

// Template
add_filter( 'page_template', function(){ return OMC_TEMPLATE_DIR.'/pages/index.php'; } );
add_filter( 'home_template', function(){ return OMC_TEMPLATE_DIR.'/blog/index.php'; } );

/**
 * Contact Form 7
 */
//add_filter( 'wpcf7_load_js', '__return_false' );	// Disable JS
//add_filter( 'wpcf7_load_css', '__return_false' ); // Disable CSS