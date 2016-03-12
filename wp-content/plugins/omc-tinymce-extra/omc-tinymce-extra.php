<?php
/*
 * Plugin Name: OMC TinyMCE Extra
 */
 
namespace omc_tinymce_extra;

class main{
	
	static $instance;
	
	/*
	 * Construct
	 */
	function __construct(){
		
		// Load script
		require_once( 'constants.php' );
		require_once( 'functions.php' );
		if( is_admin() ){
			require_once( ADMIN_DIR.'settings.php' );
		}
		
		// Hook
		add_filter( 'mce_external_plugins', array( $this, 'load_plugins' ) );
		add_filter( 'mce_buttons_2', array( $this, 'buttons_2' ) );
		add_action( 'admin_init', array( $this, 'add_style' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'tinymce_init' ) );  
	}
	
	// Hook: Add plugin js
	function load_plugins( $plugins ){
		$plugins[NAME] = JS_URL.'plugins.js';
		return $plugins;
	}
	
	// Hook: Tinymce object
	function tinymce_init( $init ){
		
		// Add menubar
		$init['menubar'] = true;
		
		// Format array
		$headings = array(
			array( 'title' => 'H1 Intro Head', 'block' => 'h1', 'classes' => 'fs-intro-head'),
		);
		
		// Add typography
		$init['typography'] = json_encode( array(
			array( 
				'title' => 'Headings',
				'items' => $headings,
			),
		));
		
		// Add Headings
		$init['headings'] = json_encode( $headings );
		
		return $init;
	}
	
	// Hook: 2nd row buttons
	function buttons_2( $buttons ){
		$buttons = array( 'typography', 'headings','underline', 'alignjustify', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'removeformat' );
		return $buttons;
	}
	
	// Hook: Add style
	function add_style(){
		//add_editor_style( CSS_URL.'editor-style.css' );
		$settings = settings::get_settings();
		if( empty( $settings['editor_style'] ) )
			return;
		
		foreach( explode( PHP_EOL, $settings['editor_style'] ) as $stylesheet )
			add_editor_style( replace_constants( trim( $stylesheet ) ) );

	}
}

// Initialize
$main = new main();