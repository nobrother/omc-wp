<?php

namespace OMC\Post;
use OMC\Post_Object_Custom_Settings;
use OMC\Post_Object;
use OMC\Post_Object_Ajax;
use \WP_Exception as WP_Exception;

/*
 * Main
 */
class Post extends Post_Object {
	
	// Define variable
	protected static $name = 'post';
	const HASH = 'asdasbsifn912g'; 
	public static $default_meta = array(
		'like_count' => 0,
		'like_user_hash' => '',
		'view_count' => 0,
	);
	
	
	/*
	 * Construct
	 */
	function __construct( $id = 0 ){
		
		parent::__construct( $id );
		
		// Register custom settings
		//self::$custom_settings = new Custom_Settings();			
	}
	
	/*
	 * Populate post object
	 * $obj is WP_Post
	 */
	function populate( $obj = '' ){
		parent::populate( $obj );
	}
	
	/*
	 * Check Is liked
	 */
	function is_liked(){
		if( empty( $this->id ) )
			return new WP_Error( 'post_not_loaded', 'Post is empty' );
			
		if( empty( $_COOKIE['unique_user_id'] ) )
			return new WP_Error( 'unique_user_id_not_set', 'Unknown user' );
		
		return strpos( $this->like_user_hash, '|'.$_COOKIE['unique_user_id'].'|' ) !== false;
		
	}
	
	/*
	 * Plus view count
	 * Use this before header is sent
	 */
	function plus_view_count(){
		
		if( empty( $this->id ) )
			return false;
		
		// Start session if not yet start
		maybe_start_session();
		
		// Had view in this session		
		if( !empty( $_SESSION['post_'.$this->id]['viewed'] ) )
			return false;
		
		// Set session
		$_SESSION['post_'.$this->id]['viewed'] = 1;
		
		global $wpdb;
		
		// Start transaction
		$wpdb->query( 'START TRANSACTION' );
		
		$this->view_count = get_post_meta( $this->id, 'view_count', true );
		update_post_meta( $this->id, 'view_count', ++$this->view_count );
		
		// Commit transaction
		$wpdb->query( 'COMMIT' );
		
		
		return $this->view_count;
	}
	
	/*
	 * Toggle like
	 */
	function toggle_like(){
		$liked = $this->is_liked();
		if( is_wp_error( $liked ) )
			return $liked;
		
		$needle = '|'.$_COOKIE['unique_user_id'].'|';
		
		global $wpdb;
		
		// Start transaction
		$wpdb->query( 'START TRANSACTION' );
		
		// Reload Meta
		$this->like_user_hash = get_post_meta( $this->id, 'like_user_hash', true );
		$this->like_count = get_post_meta( $this->id, 'like_count', true );
		
		// Unlike it
		if( $liked ){
			$this->like_user_hash = str_replace( $needle, '', $this->like_user_hash );
			$this->like_count = max( --$this->like_count, 0 );
		}
		
		// Like it
		else {
			$this->like_user_hash .= $needle;
			++$this->like_count;
		}
		
		// Store
		update_post_meta( $this->id, 'like_user_hash', $this->like_user_hash );
		update_post_meta( $this->id, 'like_count', $this->like_count );
		
		// Commit transaction
		$wpdb->query( 'COMMIT' );
		
		return !$liked;
	}
}