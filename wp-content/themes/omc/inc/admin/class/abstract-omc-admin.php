<?php
/**
 * omc admin abstract
 */
abstract class OMC_Admin {

	/**
	 * Name of the page hook when the menu is registered.
	 * @var string Page hook
	 */
	public $pagehook;

	/**
	 * ID of the admin menu and settings page.
	 * @var string
	 */
	public $page_id;

	/**
	 * Name of the settings field in the options table.
	 * @var string
	 */
	public $settings_field;

	/**
	 * Associative array (field name => values) for the default settings on this
	 * admin page.
	 * @var array
	 */
	public $default_settings;

	/**
	 * Associative array of configuration options for the admin menu(s).
	 * @var array
	 */
	public $menu_ops;

	/**
	 * Associative array of configuration options for the settings page.
	 * @var array
	 */
	public $page_ops;

	/**
	 * Call this method in a subclass constructor to create an admin menu and settings page.
	 *
	 * @param string $page_id ID of the admin menu and settings page
	 * @param array $menu_ops Optional. Config options for admin menu(s). Default is empty array
	 * @param array $page_ops Optional. Config options for settings page. Default is empty array
	 * @param string $settings_field Optional. Name of the settings field. Default is an empty string
	 * @param array $default_settings Optional. Field name => values for default settings. Default is empty array
	 * @return null Return early if page ID is not set
	 */
	public function create( $page_id = '', array $menu_ops = array(), array $page_ops = array(), $settings_field = '', array $default_settings = array() ) {

		//* Set the properties
		$this->page_id          = $this->page_id          ? $this->page_id          : $page_id;
		$this->menu_ops         = $this->menu_ops         ? $this->menu_ops         : (array) $menu_ops;
		$this->page_ops         = $this->page_ops         ? $this->page_ops         : (array) $page_ops;
		$this->settings_field   = $this->settings_field   ? $this->settings_field   : $settings_field;
		$this->default_settings = $this->default_settings ? $this->default_settings : (array) $default_settings;

		$this->page_ops = wp_parse_args(
			$this->page_ops,
			array(
				'screen_icon'       => 'options-general',
				'save_button_text'  => 'Save Settings',
				'reset_button_text' => 'Reset Settings',
				'saved_notice_text' => 'Settings saved.',
				'reset_notice_text' => 'Settings reset.',
				'error_notice_text' => 'Error saving settings.',
			)
		);

		//* Do nothing if page_id not set
		if ( ! $this->page_id )
			return;

		//* Check to make sure there we are only creating one menu per subclass
		if ( isset( $this->menu_ops['submenu'] ) && ( isset( $this->menu_ops['main_menu'] ) || isset( $this->menu_ops['first_submenu'] ) ) )
			wp_die( sprintf( 'You cannot use %s to create two menus in the same subclass. Please use separate subclasses for each menu.', 'OMC_Admin' ) );

		//* Create the menu(s). Conditional logic happens within the separate methods
		add_action( 'admin_menu', array( $this, 'maybe_add_main_menu' ), 5 );
		add_action( 'admin_menu', array( $this, 'maybe_add_first_submenu' ), 5 );
		add_action( 'admin_menu', array( $this, 'maybe_add_submenu' ) );

		//* Set up settings and notices
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'notices' ) );

		//* Load the page content (metaboxes or custom form)
		add_action( 'admin_init', array( $this, 'settings_init' ) );

		//* Add a sanitizer/validator
		add_filter( 'pre_update_option_' . $this->settings_field, array( $this, 'save' ), 10, 2 );

	}

	/**
	 * Possibly create a new top level admin menu
	 */
	public function maybe_add_main_menu() {

		//* Maybe add a menu separator
		if ( isset( $this->menu_ops['main_menu']['sep'] ) ) {
			$sep = wp_parse_args(
				$this->menu_ops['main_menu']['sep'],
				array(
					'sep_position'   => '',
					'sep_capability' => '',
				)
			);

			if ( $sep['sep_position'] && $sep['sep_capability'] )
				$GLOBALS['menu'][$sep['sep_position']] = array( '', $sep['sep_capability'], 'separator', '', 'omc-separator wp-menu-separator' );
		}

		//* Maybe add main menu
		if ( isset( $this->menu_ops['main_menu'] ) && is_array( $this->menu_ops['main_menu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['main_menu'],
				array(
					'page_title' => '',
					'menu_title' => '',
					'capability' => 'edit_theme_options',
					'icon_url'   => '',
					'position'   => '',
				)
			);

			$this->pagehook = add_menu_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, array( $this, 'admin' ), $menu['icon_url'], $menu['position'] );
		}

	}

	/**
	 * Possibly create the first submenu item
	 */
	public function maybe_add_first_submenu() {

		//* Maybe add first submenu
		if ( isset( $this->menu_ops['first_submenu'] ) && is_array( $this->menu_ops['first_submenu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['first_submenu'],
				array(
					'page_title' => '',
					'menu_title' => '',
					'capability' => 'edit_theme_options',
				)
			);

			$this->pagehook = add_submenu_page( $this->page_id, $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, array( $this, 'admin' ) );
		}

	}

	/**
	 * Possibly create a submenu item
	 */
	public function maybe_add_submenu() {

		//* Maybe add submenu
		if ( isset( $this->menu_ops['submenu'] ) && is_array( $this->menu_ops['submenu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['submenu'],
				array(
					'parent_slug' => '',
					'page_title'  => '',
					'menu_title'  => '',
					'capability'  => 'edit_theme_options',
				)
			);

			$this->pagehook = add_submenu_page( $menu['parent_slug'], $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, array( $this, 'admin' ) );
		}

	}

	/**
	 * Register the database settings for storage.
	 *
	 * @since 1.8.0
	 *
	 * @return null Return early if not on the correct admin page.
	 */
	public function register_settings() {

		//* If this page doesn't store settings, no need to register them
		if ( ! $this->settings_field )
			return;

		register_setting( $this->settings_field, $this->settings_field );
		// Create option if not exists
		add_option( $this->settings_field, $this->default_settings );

		if ( ! is_menu_page( $this->page_id ) )
			return;
		
		// If need to reset
		if ( omc_get_option( 'reset', $this->settings_field ) ) {
			if ( update_option( $this->settings_field, $this->default_settings ) )
				omc_admin_redirect( $this->page_id, array( 'reset' => 'true' ) );
			else
				omc_admin_redirect( $this->page_id, array( 'error' => 'true' ) );
			exit;
		}

	}

	/**
	 * Display notices on the save or reset of settings
	 *
	 * @return null Return early if not on the correct admin page.
	 */
	public function notices() {

		if ( ! is_menu_page( $this->page_id ) )
			return;

		if ( isset( $_REQUEST['settings-updated'] ) && 'true' === $_REQUEST['settings-updated'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['saved_notice_text'] . '</strong></p></div>';
		elseif ( isset( $_REQUEST['reset'] ) && 'true' === $_REQUEST['reset'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['reset_notice_text'] . '</strong></p></div>';
		elseif ( isset( $_REQUEST['error'] ) && 'true' === $_REQUEST['error'] )
			echo '<div id="message" class="updated"><p><strong>' . $this->page_ops['error_notice_text'] . '</strong></p></div>';

	}

	/**
	 * Save method.
	 *
	 * Override this method to modify form data (for validation, sanitization, etc.) before it gets saved
	 *
	 * @param string $newvalue
	 * @param string $oldvalue
	 * @return string
	 */
	public function save( $newvalue, $oldvalue ) {

		return $newvalue;

	}

	/**
	 * Initialize the settings page.
	 *
	 * This method must be re-defined in the extended classes, to hook in the
	 * required components for the page.
	 *
	 * @since 1.8.0
	 */
	abstract public function settings_init();

	/**
	 * Output the main admin page.
	 *
	 * This method must be re-defined in the extended class, to output the main
	 * admin page content.
	 *
	 * @since 1.8.0
	 */
	abstract public function admin();

	/**
	 * Helper function that constructs name attributes for use in form fields.
	 *
	 * Within omc pages, the id attributes of form fields are the same as
	 * the name attribute, as since HTML5, [ and ] characters are valid, so this
	 * function is also used to construct the id attribute value too.
	 *
	 * Other page implementation classes may wish to construct and use a
	 * get_field_id() method, if the naming format needs to be different.
	 *
	 * @since 1.8.0
	 *
	 * @param string $name Field name base
	 * @return string Full field name
	 */
	protected function get_field_name( $name ) {

		return sprintf( '%s[%s]', $this->settings_field, $name );

	}

	/**
	 * Helper function that constructs id attributes for use in form fields.
	 *
	 * @since 1.8.0
	 *
	 * @param string $id Field id base
	 * @return string Full field id
	 */
	protected function get_field_id( $id ) {

		return sprintf( '%s[%s]', $this->settings_field, $id );

	}

	/**
	 * Helper function that returns a setting value from this form's settings
	 * field for use in form fields.
	 *
	 * @since 1.8.0
	 *
	 * @param string $key Field key
	 * @return string Field value
	 */
	protected function get_field_value( $key ) {

		return omc_get_option( $key, $this->settings_field );

	}

}

/**
 * Abstract subclass of OMC_Admin which adds support for displaying a form
 */
abstract class OMC_Admin_Form extends OMC_Admin {

	/**
	 * Output settings page form elements.
	 *
	 * Must be overridden in a subclass, or it obviously won't work.
	 *
	 * @since 1.8.0
	 */
	abstract public function form();

	/**
	 * Normal settings page admin.
	 *
	 * Includes the necessary markup, form elements, etc.
	 * Hook to {$this->pagehook}_settings_page_form to insert table and settings form.
	 *
	 * Can be overridden in a child class to achieve complete control over the settings page output.
	 *
	 * @since 1.8.0
	 */
	public function admin() {

		?>
		<div class="wrap">
		<form method="post" action="options.php" class="ajax-save">

			<?php settings_fields( $this->settings_field ); ?>

			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<p class="top-buttons">
				<?php
				submit_button( $this->page_ops['save_button_text'], 'primary', 'submit', false );
				?>
			</p>

			<?php do_action( $this->pagehook . '_settings_page_form', $this->pagehook ); ?>

		</form>
		</div>
		<?php

	}

	/**
	 * Initialize the settings page, by hooking the form into the page.
	 *
	 * @since 1.8.0
	 */
	public function settings_init() {

		add_action( $this->pagehook . '_settings_page_form', array( $this, 'form' ) );
		if ( method_exists( $this, 'help' ) )
			add_action( 'load-' . $this->pagehook, array( $this, 'help' ) );

	}

}

/**
 * Abstract subclass of OMC_Admin which adds support for registering and
 * displaying metaboxes.
 *
 * This class must be extended when creating an admin page with meta boxes, and
 * the settings_metaboxes() method must be defined in the subclass.
 */
abstract class OMC_Admin_Boxes extends OMC_Admin {

	/**
	 * Register the metaboxes.
	 *
	 * Must be overridden in a subclass, or it obviously won't work.
	 */
	abstract public function metaboxes();

	/**
	 * Include the necessary sortable metabox scripts.
	 */
	public function scripts() {

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );

	}

	/**
	 * Make the sortable UI a single column.
	 *
	 * @param integer $columns
	 * @param string $screen The unique ID of the screen
	 * @return array
	 */
	public function layout_columns( $columns, $screen ) {

		if ( $screen === $this->pagehook ) {
			//* This page should only have 1 column option
			$columns[$this->pagehook] = 1;
		}

		return $columns;

	}

	/**
	 * Use this as the settings admin callback to create an admin page with sortable metaboxes.
	 * Create a 'settings_boxes' method to add metaboxes.
	 */
	public function admin() {

		?>
		<div class="wrap omc-metaboxes">
		<form method="post" action="options.php" class="ajax-save">

			<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
			<?php settings_fields( $this->settings_field ); ?>

			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<p class="top-buttons">
				<?php
				submit_button( $this->page_ops['save_button_text'], 'primary', 'submit', false, array( 'id' => '' ) );
				?>
			</p>

			<?php do_action( $this->pagehook . '_settings_page_boxes', $this->pagehook ); ?>

		</form>
		</div>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function ($) {
				// close postboxes that should be closed
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				// postboxes setup
				postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
			});
			//]]>
		</script>
		<?php

	}

	/**
	 * Echo out the do_meta_boxes() and wrapping markup.
	 *
	 * This method can be overwritten in a child class, to adjust the markup surrounding the metaboxes, and optionally
	 * call do_meta_boxes() with other contexts. The overwritten method MUST contain div elements with classes of
	 * metabox-holder and postbox-container.
	 */
	public function do_metaboxes() {

		global $wp_meta_boxes;

		?>
		<div class="metabox-holder">
			<div class="postbox-container">
				<?php
				do_action( 'omc_admin_before_metaboxes', $this->pagehook );
				do_meta_boxes( $this->pagehook, 'main', null );
				if ( isset( $wp_meta_boxes[$this->pagehook]['column2'] ) )
					do_meta_boxes( $this->pagehook, 'column2', null );
				do_action( 'omc_admin_after_metaboxes', $this->pagehook );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Initialize the settings page, by enqueuing scripts
	 */
	public function settings_init() {

		add_action( 'load-' . $this->pagehook, array( $this, 'scripts' ) );
		add_action( 'load-' . $this->pagehook, array( $this, 'metaboxes' ) );
		add_filter( 'screen_layout_columns', array( $this, 'layout_columns' ), 10, 2 );
		add_action( $this->pagehook . '_settings_page_boxes', array( $this, 'do_metaboxes' ) );
		if ( method_exists( $this, 'help' ) )
			add_action( 'load-' . $this->pagehook, array( $this, 'help' ) );

	}

}

/**
 * Abstract subclass of omc_Admin which adds support for creating a basic
 * admin page that doesn't make use of a Settings API form or metaboxes.
 *
 * This class must be extended when creating a basic admin page and the admin()
 * method must be redefined.
 */
abstract class omc_Admin_Basic extends OMC_Admin {

	/**
	 * Satisfies the abstract requirements of omc_Admin.
	 *
	 * This method can be redefined within the page-specific implementation
	 * class if you need to hook something into admin_init.
	 *
	 * @since 1.8.0
	 */
	public function settings_init() {

		if ( method_exists( $this, 'help' ) )
			add_action( 'load-' . $this->pagehook, array( $this, 'help' ) );

	}

}

do_action( 'omc_admin_init' );
