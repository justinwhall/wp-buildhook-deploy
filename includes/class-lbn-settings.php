<?php
/**
 * LittleBot settings pages
 *
 * @author      Justin W Hall
 * @category    Settings
 * @package     LittleBot Invoices/Settings
 * @version     0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings & Settings page
 */
class LBN_Settings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings".
		add_options_page(
			'Settings Admin',
			'My Settings',
			'manage_options',
			'lb-netlifly',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property.
		$this->options = get_option( 'lb_netlifly' );

		?>
		<div class="wrap">
			<h1>LittleBot Netlifly Settings</h1>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'build_group' );
				do_settings_sections( 'lb-netlifly' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'build_group', // Option group
			'lb_netlifly', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'Build Hooks', // Title
			false, // Callback
			'lb-netlifly' // Page
		);

		add_settings_field(
			'production_buildhook', // ID
			'Production', // Title
			array( $this, 'prod_callback' ), // Callback
			'lb-netlifly', // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'stage_buildhook',
			'Stage',
			array( $this, 'stage_callback' ),
			'lb-netlifly',
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['production_buildhook'] ) )
			$new_input['production_buildhook'] = sanitize_text_field( $input['production_buildhook'] );

		if( isset( $input['stage_buildhook'] ) )
			$new_input['stage_buildhook'] = sanitize_text_field( $input['stage_buildhook'] );

		return $new_input;
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function prod_callback() {
		printf(
			'<input type="text" id="prod_buildhook" name="lb_netlifly[production_buildhook]" value="%s" style="min-width:450px;"/>',
			isset( $this->options['production_buildhook'] ) ? esc_attr( $this->options['production_buildhook']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function stage_callback() {
		printf(
			'<input type="text" id="stage_buildhook" name="lb_netlifly[stage_buildhook]" value="%s" style="min-width:450px;" />',
			isset( $this->options['stage_buildhook'] ) ? esc_attr( $this->options['stage_buildhook']) : ''
		);
	}
}
