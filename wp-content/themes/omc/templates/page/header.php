<?php
/**
 * The header for our page.
 */ 
namespace OMC\Page; 

global $post;
$slug = $post->post_name;
	
// Load extra font here
load_external_font( 'lato', 'https://fonts.googleapis.com/css?family=Lato:400,300,700,300italic' );
load_external_font( 'montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700' );

// Load body attribute
add_filter( 'omc_body_attribute', function() use( $slug ){ return 'id="page-'.$slug.'"'; }, 5 );

// Load general header
omc_inject( 'header', false ); the_post();