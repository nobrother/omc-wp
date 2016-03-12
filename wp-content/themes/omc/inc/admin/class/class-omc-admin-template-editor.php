<?php
/**
 * OMC Template Editor
 * Registers a admin theme settings/options page, providing content and corresponding menu item for it
 */

class OMC_Template_Editor_Settings extends OMC_File_Editor_Settings {

	/**
	 * Create an admin menu item and settings page.
	 */
	function __construct() {

		$this->page_id = 'omc_template_editor';

		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'omc_theme_settings',
				'page_title'  => 'Edit Template',
				'menu_title'  => 'Edit Template',
			),
		);

		$this->create( $this->page_id, $menu_ops );
		
		// Load action for save, add, and delete files
		$this->add_actions();
		
		// Print editor
		add_action( $this->page_id.'_file_editor_html', function(){
			$this->print_editor( 'Edit Template', array( 'php' ), OMC_TEMPLATE_DIR );
		});
	}
}

new OMC_Template_Editor_Settings;