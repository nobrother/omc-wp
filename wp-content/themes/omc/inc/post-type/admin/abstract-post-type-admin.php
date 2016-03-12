<?php

namespace OMC;

/*
 * Post Object Metabox Class
 */
abstract class Post_Object_Metabox {
	
	// Should define in child class
	public $post_type;
	public $metabox_title = 'Settings';
	public static $custom_settings;
	
	protected static $declared = false;
	
	/*
	 * Construct
	 */
	function __construct(){		
		
		// Cannot redeclared
		if( static::$declared )
			return;
		static::$declared = true;
		
		// Hook
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}
	
	/*
	 * Add meta box
	 */
	function add_metabox() {
		
		$screen = get_current_screen();		
		if( $screen->action === 'add' )
			return;		
		
		$settings = &static::$custom_settings;
		add_meta_box( "omc_{$this->post_type}_{$settings->key}_metabox", $this->metabox_title, array( $this, 'html' ), $this->post_type, 'normal', 'high' );
	}

	// Metabox html
	abstract function html( $post ); 
	
	/**
	 * Save settings
	 */
	function save( $post_id, $post ){
		
		// Post type check
		if( $post->post_type != $this->post_type )
			return;
		
		// Status check
		if( $post->post_status == 'auto-draft' )
			return;
		
		// First time save
		if( $post->post_date == $post->post_modified )
			$this->first_time_save( $post_id, $post );
		
		// Subsequence save
		$this->subsequence_save( $post_id, $post );		
	}
	
	/**
	 * First time save
	 */
	abstract function first_time_save( $post_id, $post );	
	
	/**
	 * Subsequnce save
	 */
	function subsequence_save( $post_id, $post ){
		
		$settings = &static::$custom_settings;
		
		// Check variable
		if( !isset( $_POST[$settings->key] ) || !is_array( $_POST[$settings->key] ) )
			return;
		
		// Security check
		if( !wp_verify_nonce( $_POST[$settings->nonce_field], $settings->nonce_action ) ){
			omc_add_admin_notice( 'security not pass' );
			return;		
		}
		
		// Save settings
		$settings->set( $_POST[$settings->key], $post );
	}
	
	
}