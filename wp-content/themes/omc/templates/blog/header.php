<?php

// Load page css
load_theme_less( 'blog', __DIR__.'/style.less' );

// Load body attribute
add_filter( 'omc_body_attribute', function(){ return 'id="blog"'; }, 5 );

// Load general header
include( OMC_COMMON_DIR.'/header/header.php' );