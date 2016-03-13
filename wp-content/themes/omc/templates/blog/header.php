<?php
/**
 * The header for our page.
 */ 
namespace OMC\Page; 

global $post;

$page_obj = new Page( $post );

// Load custom stylesheet
foreach( explode( ',', $page_obj->get_custom_settings( 'stylesheet' ) ) as $stylesheet )
	load_theme_less( '_pages/'.trim( $stylesheet ) );
	
// Load extra font here
load_external_font( 'lato', 'https://fonts.googleapis.com/css?family=Lato:400,300,700,300italic' );
load_external_font( 'montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700' );

//var_dump($page_obj);

// Load body attribute
add_filter( 'omc_body_attribute', function() use( $page_obj ){ return 'id="page-'.$page_obj->slug.'"'; }, 5 );

// Load facebook snippet
add_action( 'omc_beginning_of_body', function(){ ?>
	<!-- Facebook snippet -- >
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=711111255619304";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- / Facebook snippet -->	
<?php }, 5);

// Load general header
include( OMC_TEMPLATE_DIR.'/header.php' ); the_post();