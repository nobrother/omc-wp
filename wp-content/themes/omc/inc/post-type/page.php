<?php

namespace OMC\Page;
use OMC\Post_Object_Custom_Settings;
use OMC\Post_Object;
use OMC\Post_Object_Ajax;
use \WP_Exception as WP_Exception;

/*
 * Custom settings
 */
class Custom_Settings extends Post_Object_Custom_Settings {
	
	public $post_type = 'page';
	public $key = 'settings';
	public $nonce_field = 'omc_page_settings_nonce';
	public $nonce_action = 'omc_page_settings_save';
	public $default = array( 
		'stylesheet' => '',
	);
	public $sanitize_type = array( 
		'stylesheet' => 'sanitize_title',
	);
	
	/*
	 * Construct
	 */
	function _construct(){
		
		
	}
}

/*
 * Main
 */
class Page extends Post_Object {
	
	// Define variable
	protected static $name = 'page';
	const HASH = 'asdasfafadge'; 
	public static $default_meta = array();
	public static $samples = array();
	public $placeholder = array();		
	public $placeholder_cache;
	
	
	/*
	 * Construct
	 */
	function __construct( $id = 0 ){
		
		parent::__construct( $id );
		
		// Define sample
		self::$samples[OMC_TEMPLATE_DIR.'/page/'.'##slug##'] = array(
			'page-layout.php' => 'layout.php', 
			'page-content.php' => 'content.php', 
			'page-style.less' => 'style.less',
		);
		
		// Register custom settings
		self::$custom_settings = new Custom_Settings();			
	}
	
	/*
	 * Populate post object
	 * $obj is WP_Post
	 */
	function populate( $obj = '' ){
		parent::populate( $obj );
		
		// Register placeholder
		$this->register_placeholder( array(	'slug', 'post_type', 'title' ) );
	}
	
	/*
	 * Helper function to Register placeholder
	 */
	function register_placeholder( array $data ){		
		$this->placeholder = array_merge( $this->placeholder, $data );
	}
	
	/*
	 * Replace the placeholder to something meaningful
	 */
	function replace_placeholder( $content, $use_cache = true ){
		
		// The object must be loaded
		$this->check_is_loaded();
		
		// Check placeholder property
		if( empty( $this->placeholder ) || !is_array( $this->placeholder ) )
			return $content;
		
		// Check cache
		if( $use_cache && empty( $this->placeholder_cache ) || !$use_cache ){
			
			// Create cache
			foreach( $this->placeholder as $key => $value ){
				// Direct replace
				if( is_string( $key ) )
					$this->placeholder_cache["##{$key}##"] = $value;
				
				elseif( property_exists( $this, $value ) )
					$this->placeholder_cache["##{$value}##"] = $this->$value;					
			}			
		}
		
		// Replace it
		if( !empty( $this->placeholder_cache ) )
			return strtr( $content, $this->placeholder_cache );
		
		return $content;
	}
	
	/*
	 * Create standard template file for the page
	 * It won't override the existing file
	 */
	function create_template_files(){
		
		// The object must be loaded
		$this->check_is_loaded();
		
		// Targeting sample files
		if( !is_array( self::$samples ) )
			throw new WP_Exception( "Wrong format of 'samples' property", "'samples' property expect to be an array" );
		
		// Create
		foreach( self::$samples as $target_dir => $sample ){
			
			$target_dir = $this->replace_placeholder( $target_dir );
			
			foreach( $sample as $sample_name => $target_name ){
				// Sample file check
				$sample_file_path = OMC_SAMPLE_DIR.'/'.$sample_name;
				if( !file_exists( $sample_file_path ) )
					throw new WP_Exception( "Missing Sample File", "Could not find the file: $sample_file_path." );
				
				// Template file check
				$target_name = $this->replace_placeholder( $target_name );
				$target_file_path = $target_dir.'/'.$target_name;
				if( file_exists( $target_file_path ) )
					continue;
				
				if( !is_dir( $target_dir ) )
					mkdir( $target_dir );
				
				// Create file
				if( file_put_contents( $target_file_path, $this->replace_placeholder( file_get_contents( $sample_file_path ) ) ) === false )
					throw new WP_Exception( "Could not create a template file", "Could not create the file: $target_file_path." );
			}			
		}		
	}	
}