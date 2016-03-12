<?php

namespace omc_tinymce_extra;

/*
 * Match and replace constants
 */
function replace_constants( $string = '' ){
	
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