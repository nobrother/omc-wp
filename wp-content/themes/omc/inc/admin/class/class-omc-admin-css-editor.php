<?php
/**
 * OMC CSS Editor
 * Registers a admin theme settings/options page, providing content and corresponding menu item for it
 */

class OMC_CSS_Editor_Settings extends OMC_File_Editor_Settings {
	
	public $_less = null;
	
	/**
	 * Create an admin menu item and settings page.
	 */
	function __construct() {

		$this->page_id = 'omc_css_editor';

		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'omc_theme_settings',
				'page_title'  => 'Edit CSS',
				'menu_title'  => 'Edit CSS',
			),
		);

		$this->create( $this->page_id, $menu_ops );
		
		// Load action for save, add, and delete files
		$this->add_actions();
		
		// Print editor
		add_action( $this->page_id.'_file_editor_html', function(){
			$this->print_editor( 'Edit CSS / LESS', array( 'css', 'less' ), OMC_CSS_DIR.'/theme' );
		});
		
		// Compile less
		add_action( 'omc_admin_file_editor_after_file_saved', array( $this, 'compile_less' ) );
		
		// Not compile less
		add_filter( 'omc_admin_file_editor_skip_compile_less', array( $this, 'skip_compile' ), 10, 2 );
		
		// Related file
		add_filter( 'omc_admin_file_editor_reload_related_less_files', array( $this, 'add_related_files' ), 10, 2 );
	}
	
	// Compile less
	function compile_less( $current_file ){
		
		if( 
			is_menu_page( $this->page_id ) &&
			!empty( $current_file ) &&
			!empty( $current_file['extension'] ) &&
			in_array( $current_file['extension'], array( 'css', 'less' ) )
		){
			
			// Add related file
			$queue_files = array_merge( 
				array( $current_file ),
				apply_filters( 'omc_admin_file_editor_reload_related_less_files', array(), $current_file )
			);
			
			// Loop
			foreach( $queue_files as $file ){
				
				if( apply_filters( 'omc_admin_file_editor_skip_compile_less', true, $file ) )
					continue;
				
				$filename = $file['file'];
				$min_file = omc_get_min_theme_less_path( $filename );
				
				if ( file_exists( $filename ) && is_writeable( $filename ) )
					$this->less()->compileFile( $filename, $min_file );
			}
		}
	}
	
	// Not compile less
	function skip_compile( $return, $file ){
		return empty( $file['filename'] ) || strpos( $file['filename'], 'mixin-' ) === 0 ;
	}
	
	// Filter related files
	function add_related_files( $related_files, $current_file ){
		
		if( strpos( $current_file['filename'], 'mixin-' ) === 0 ){
						
			foreach( scandir_recursive( OMC_CSS_THEME_DIR, true ) as $file ){ // True means with pathinfo
				if( isset( $file['extension'] ) && $file['extension'] == 'less' )
					$related_files[] = $file;
			}
			foreach( scandir_recursive( OMC_TEMPLATE_DIR, true ) as $file ){ // True means with pathinfo
				if( isset( $file['extension'] ) && $file['extension'] == 'less' )
					$related_files[] = $file;
			}
			
			// Return
			return $related_files;
		}
		
		// Return original
		return $related_files;
	}
	
	/**
	 * Register Less Custom Functions
	 */
	function register_less_custom_functions( & $less ){
		
		// double(@var)
		$less->registerFunction( 'double' , function( $arg ) {
			list( $type, $value, $unit ) = $arg;
			return array( $type, $value*2, $unit );
		} );
		
		// roundup(@var, @multiple)
		$less->registerFunction( 'roundup' , function( $arg ) {
			
			// Exit is arguement is not correct
			if( empty( $arg[2] ) || empty( $arg[2][0] ) || empty( $arg[2][1] ) || 
					empty( $arg[2][0][0] ) || empty( $arg[2][1][0] ) || 
					$arg[2][0][0] !== 'number' || $arg[2][1][0] !== 'number' 
				)
				return $arg;
			
			// Get argument
			$var = $arg[2][0];
			$multiple = floor( $arg[2][1][1] ); // Make sure multiple is integer
			
			// Output
			list( $type, $value, $unit ) = $var;
			return array( $type, $multiple * ceil( $value / $multiple ), $unit );
		} );
	}
	
	// Get less compiler
	function less(){
		if( !class_exists( 'lessc' ) )
			require_once OMC_APPS_DIR.'/lessc/lessc.inc.php';
			
		$this->_less = new lessc;
		$this->_less->setFormatter( 'compressed' );
		$this->_less->addImportDir( OMC_JS_PLUGIN_DIR );
		$this->_less->addImportDir( OMC_CSS_THEME_DIR.'/mixin' );
		$this->register_less_custom_functions( $this->_less );
		
		return $this->_less;
	}
}

new OMC_CSS_Editor_Settings;