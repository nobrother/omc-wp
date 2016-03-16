<?php
global $theme_config;

$theme_config = array(
	'loader' => array(
		'general' => array( 
			'inc/constants.php',
			'inc/functions/general.php',
			'inc/functions/options.php',
			'inc/functions/formatting.php',
			'inc/functions/html-helper.php',
			'inc/functions/general-template.php',
			'inc/class/class-wp-exception.php',
			'inc/load-scripts/load-scripts.php',
			'inc/load-scripts/load-styles.php',
			'inc/post-type/abstract-post-type-object.php',
			'inc/post-type/abstract-post-type-custom-settings.php',			
			'inc/post-type/page.php',
			'inc/post-type/post.php',
			'inc/mainframe/mainframe.php',			
		),
		'frontend' => array(
			'inc/structure/header.php',
			'inc/structure/footer.php',
			'templates/common/widgets/tags.php',			
			'templates/common/helper/template-tags.php',
		),
		'backend' => array(
			'inc/admin/functions.php',
			'inc/admin/add-admin-notice.php',
			'inc/admin/add-admin-menu.php',
			'inc/admin/class/abstract-omc-admin.php',
			'inc/admin/class/abstract-omc-admin-file-editor.php',
			'inc/post-type/admin/abstract-post-type-admin.php',
			'inc/post-type/admin/page.php',
		),
		'ajax' => array(
			'inc/post-type/abstract-post-type-ajax.php',
			'inc/post-type/post-ajax.php',
		),
	),
);