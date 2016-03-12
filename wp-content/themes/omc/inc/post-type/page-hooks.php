<?php

// [REMOVE] Remove page editor
//add_action( 'init', 'omc_remove_page_support' );
function omc_remove_page_support() {
	remove_post_type_support( 'page', 'editor' );
}