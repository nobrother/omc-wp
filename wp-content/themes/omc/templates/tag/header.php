<?php

// Load page css
load_theme_less( 'tag', __DIR__.'/style.less' );

// Load body attribute
add_filter( 'omc_body_attribute', function(){ return 'id="tag"'; }, 5 );

// Load general header
include( OMC_COMMON_DIR.'/header/header.php' ); the_post();