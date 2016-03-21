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
		$init['wpautop'] = false;
		$style_formats = array(
			array(  
				'title' => 'Headings',
				'items' => array(
					array( 'title' => 'Heading 1', 'format' => 'h1' ),
					array( 'title' => 'Heading 2', 'format' => 'h2' ),
					array( 'title' => 'Heading 3', 'format' => 'h3' ),
					array( 'title' => 'Heading 4', 'format' => 'h4' ),
					array( 'title' => 'Heading 5', 'format' => 'h5' ),
					array( 'title' => 'Heading 6', 'format' => 'h6' ),
				)
			),
			array(
				'title' => 'Inline',
				'items' => array(
					array( 'title' => 'Dropcap', 				'inline' => 'span', 				'classes' => 'dropcap' ),
					array( 'title' => 'Bold', 					'icon' => 'bold', 					'format' => 'bold' ),
					array( 'title' => 'Italic', 				'icon' => 'italic', 				'format' => 'italic' ),
					array( 'title' => 'Underline', 			'icon' => 'underline', 			'format' => 'underline' ),
					array( 'title' => 'Strikethrough', 	'icon' => 'strikethrough',	'format' => 'strikethrough' ),
					array( 'title' => 'Superscript', 		'icon' => 'superscript', 		'format' => 'superscript' ),
					array( 'title' => 'Subscript', 			'icon' => 'subscript', 			'format' => 'subscript' ),
					array( 'title' => 'Code', 					'icon' => 'code', 					'format' => 'code' ),
				)
			),
			array(
				'title' => 'Blocks',
				'items' => array(
					array( 'title' => 'Paragraph', 		'format' => 'p' ),
					array( 'title' => 'Blockquote', 	'format' => 'blockquote' ),
					array( 'title' => 'Div', 					'format' => 'div' ),
					array( 'title' => 'Pre', 					'format' => 'pre' ),
				)
			),
			array(
				'title' => 'Alignment',
				'items' => array(
					array( 'title' => 'Left', 		'icon' => 'alignleft', 		'format' => 'alignleft' ),
					array( 'title' => 'Center', 	'icon' => 'aligncenter', 	'format' => 'aligncenter' ),
					array( 'title' => 'Right', 		'icon' => 'alignright', 	'format' => 'alignright' ),
					array( 'title' => 'Justify',	'icon' => 'alignjustify', 'format' => 'alignjustify' ),
				)
			)
		);
		
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init['style_formats'] = json_encode( $style_formats );
		
		return $init;
	}
	
	// Hook: 2nd row buttons
	function buttons_2( $buttons ){
		$buttons = array( 
			'styleselect', 
			'underline', 
			'alignjustify', 
			'forecolor', 
			'pastetext', 
			'removeformat', 
			'charmap', 
			'outdent', 
			'indent', 
			'undo', 
			'redo', 
			'wp_help' 
		);
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