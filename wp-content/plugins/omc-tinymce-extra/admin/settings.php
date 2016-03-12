<?php

namespace omc_tinymce_extra;

class settings{
	
	static $default_settings;
	static $instance;
	
	/*
	 * Construct
	 */
	function __construct(){
		
		// Default
		static::$default_settings = array(
			'editor_style' => '',
		);
		
		// Hook
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}
	
	// Hook: Admin menu
	function admin_menu(){
		add_options_page(
			LABEL,
			LABEL,
			'manage_options',
			SETTINGS,
			array( $this, 'html' )
		);
	}
	
	// Hook: Register settings
	function settings_init() { 
		register_setting( NAME, SETTINGS );
	}
	
	// Hook: HTML
	function html(){ 
		$settings = static::get_settings();
		
	?>
		<div class="wrap">
			<h1><?php echo LABEL ?></h1>
			<form action="options.php" method="post">
				<?php settings_fields( NAME ) ?>
				
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Editor stylesheet</th>
							<td>
								<textarea name="<?php echo SETTINGS ?>[editor_style]" rows="3" class="large-text code"><?php echo $settings['editor_style'] ?></textarea>
								<?php if( !empty( $settings['editor_style'] ) ) var_dump( replace_constants( explode( PHP_EOL, $settings['editor_style'] ) ) ) ?>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
			</form>
    </div>
    <?php
	}
	
	// Helper: Get settings
	static function get_settings(){
		$settings = (array) get_option( SETTINGS );
		$settings = array_intersect_key( $settings, static::$default_settings );
		$settings = array_merge( static::$default_settings, $settings );
		return $settings;
	}
}

// Initialize
new settings();