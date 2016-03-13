<?php
/**
 *===================
 * Helper functions
 *===================
 * load_external_font( $handler = '', $url = '' )
 * load_theme_less( $filename = '', $slug = '' )
 * 
 *
 *===================
 * Script functions
 *===================
 * - omc_load_fontawesome
 * - omc_load_opensans
 * - omc_load_fonthead
 */

/**
 *===================
 * Hook sequence
 *===================
 *-----------------------------
 * wp_enqueue_scripts sequence
 *-----------------------------
 * 1			- omc_enqueue_fonts
 * 10 		- omc_enqueue_js_plugin: load all js plugin, js and css
 * 20 		- omc_enqueue_theme_stylesheet
 * 30			- omc_load_page_stylesheet
 *
 *--------------------
 * wp_head sequence
 *--------------------
 * 1			- wp_enqueue_scripts (head)
 * 7			- omc_before_print_styles
 * 8			- wp_print_styles
 * 8			- omc_embed_css
 * 8			- omc_last_css
 */

/**
 * Load font
 */
add_action( 'wp_enqueue_scripts', 'omc_enqueue_fonts', 1 );
function omc_enqueue_fonts(){
	omc_load_fontawesome();
	omc_load_opensans();
	
	// Add extra fonts
	do_action( 'omc_enqueue_fonts' );
}

/**
 * Enqueue main style sheet.
 */
add_action( 'wp_enqueue_scripts', 'omc_enqueue_theme_stylesheet', 20 );
function omc_enqueue_theme_stylesheet() {
	wp_enqueue_style( 'omc-style', get_stylesheet_uri() );
	
	if( is_tablet() )
		omc_add_theme_less( 'tablet', OMC_CSS_THEME_DIR.'/devices/tablet.less' );
	else if( is_mobile() )
		omc_add_theme_less( 'mobile', OMC_CSS_THEME_DIR.'/devices/mobile.less' );
	else
		omc_add_theme_less( 'pc', OMC_CSS_THEME_DIR.'/devices/pc.less' );
}

/**
 * Load page specific stylesheet
 */
add_action( 'wp_enqueue_scripts', 'omc_load_page_stylesheet_hook', 30 );
function omc_load_page_stylesheet_hook(){
	do_action( 'omc_load_page_stylesheet' );
}

/**
 * Place omc_before_print_styles hook
 */
add_action( 'wp_head', 'omc_before_print_styles_hook', 7 );
function omc_before_print_styles_hook(){
	do_action( 'omc_before_print_styles' );
}

/**
 * Place omc_embed_css hook
 */
add_action( 'wp_head', 'omc_embed_css_hook', 8 );
function omc_embed_css_hook(){
	do_action( 'omc_embed_css' );
}

/**
 * Place omc_last_css hook
 */
add_action( 'wp_head', 'omc_last_css_hook', 8 );
function omc_last_css_hook(){
	do_action( 'omc_last_css' );
}

/**
 * Enqueue OMC admin styles.
 */
add_action( 'admin_enqueue_scripts', 'omc_load_admin_styles' );
function omc_load_admin_styles() {	
	omc_add_theme_less( 'admin', OMC_CSS_THEME_DIR.'/others/admin.less', array() );
}



/**
 * Helper function to load external font
 */
function load_external_font( $handler = '', $url = '' ){

	// Check input
	if( 
		empty( $handler ) ||
		empty( $url ) ||
		!is_url( $url )
	){
		return false;
	}
	
	add_action( 'omc_enqueue_fonts', function() use( $handler, $url ){
		wp_enqueue_style( $handler, $url );
	} );
	
	return true;
}

/**
 * Helper function to load theme css
 */
function load_theme_less( $handler, $file = '' ){

	// Check input
	if( empty( $file ) )
		return false;
	
	if( !is_array( $file ) )
		$file = omc_pathinfo( $file );
	
	// Filename options
	if( empty( $file['dirname'] ) ){
		$file['dirname'] = OMC_CSS_THEME_DIR;
		$file['file'] = OMC_CSS_THEME_DIR.'/'.$file['basename'];
	}
	if( empty( $file['extention'] ) ){
		$file['extention'] = 'less';
		$file['file'] .= 'less';
	}

	$files = omc_add_device_suffix( $file['dirname'].'/'.$file['filename'], 'less' );
	
	// Choose file
	$file = locate_file( $files );
	
	// Enqueue
	if( !empty( $file ) ){
	
		add_action( 'wp_enqueue_scripts', function() use( $handler, $file ){
			omc_add_theme_less( $handler, $file );
		}, 20 );
		
		return true;
	}
}

/**
 * Enqueue theme less helper function
 */
function omc_add_theme_less( $handler, $file, $depandancy = array( 'omc-style' ) ){
	$depandancy = (array) $depandancy;
	
	if( !is_array( $file ) )
		$file = omc_pathinfo( $file );
	
	if( empty( $file['filename'] ) )
		return false;
	
	// Minfied path
	$min_file = omc_get_min_theme_less_path( $file['file'] );

	if( file_exists( $min_file ) )
		wp_enqueue_style( $handler, omc_path_to_url( $min_file ), $depandancy );
}
 
/**
 * Convert theme less file path to min file path
 */
function omc_get_min_theme_less_path( $file = '' ){
	return OMC_CSS_THEME_DIR.'/min/style'.md5( wp_normalize_path( $file ) ).'.css';
}


/**
 * Helper function to load font awesome
 */
function omc_load_fontawesome(){
	wp_enqueue_style( 'fa', OMC_FONTS_URL.'/font-awesome/font-awesome.min.css' );	
}

/**
 * Helper function to load font head
 */
function omc_load_fonthead(){
	wp_enqueue_style( 'fh', OMC_FONTS_URL.'/font-heads/font-heads.css' );	
}

/**
 * Helper function to load opensan
 */
function omc_load_opensans(){
	wp_enqueue_style( 'opensans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,300,600,700' );	
}