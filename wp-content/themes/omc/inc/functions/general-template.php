<?php
/**
 * General template tags that can go anywhere in a template.
 */

/**
 * Make full path to relative to theme
 */
function omc_relative_path( $path, $root = PARENT_DIR ) {
	return str_replace( $root, '', $path );
} 

/*
 * Helper function to add device suffix
 */
function omc_add_device_suffix( $templates, $ext = '' ){
	
	$tmp = array();
	
	if( $ext )
		$ext = '.'.$ext;
	
	foreach( (array) $templates as $template ){		
		if( is_tablet() )
			$tmp[] = "{$template}-tablet{$ext}";
		if( is_phone() )
			$tmp[] = "{$template}-phone{$ext}";
		if( is_mobile() )
			$tmp[] = "{$template}-mobile{$ext}";
		else
			$tmp[] = "{$template}-pc{$ext}";
		$tmp[] = "{$template}{$ext}";
	}
	
	return $tmp;
}


/*
 * Match and replace constants
 */
function omc_replace_constants( $string = '' ){
	
	return preg_replace_callback(
		'/\[\[([^\[]+)\]\]/',
		function( $matches ){
			if( defined( $matches[1] ) )
				return constant( $matches[1] );
			else
				return $matches[0];
		},
		$string
	);
	
}

/*
 * Get most relevent file with omc style
 * Consider the device suffix
 */
function omc_include_file( $file, $slug = "" ){
	if( !is_array( $file ) )
		$file = omc_pathinfo( $file );
	
	extract( $file );
	
	$files = array();
	if( !empty( $slug ) )	
		$files = omc_add_device_suffix( "$dirname/$filename-$slug", $extension );
	$files = array_merge( $files, omc_add_device_suffix( "$dirname/$filename", $extension ) );
	
	$file = locate_file( $files );
	
	if( $file )
		include $file['file'];
}