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
			'lbn-netlifly',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property.
		$this->options = get_option( 'lbn_netlifly' );

		?>
		<div class="wrap">
			<h1>My Settings</h1>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'my_option_group' );
				do_settings_sections( 'lbn-netlifly' );
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
			'my_option_group', // Option group
			'lbn_netlifly', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'My Custom Settings', // Title
			false, // Callback
			'lbn-netlifly' // Page
		);

		add_settings_field(
			'prod_webook', // ID
			'Production Webhook', // Title
			array( $this, 'prod_callback' ), // Callback
			'lbn-netlifly', // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'stage_webook',
			'Staging Webhook',
			array( $this, 'stage_callback' ),
			'lbn-netlifly',
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
		if( isset( $input['prod_webhook'] ) )
			$new_input['prod_webhook'] = sanitize_text_field( $input['prod_webhook'] );

		if( isset( $input['stage_webhook'] ) )
			$new_input['stage_webhook'] = sanitize_text_field( $input['stage_webhook'] );

		return $new_input;
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function prod_callback() {
		printf(
			'<input type="text" id="prod_webhook" name="lbn_netlifly[prod_webhook]" value="%s" style="min-width:450px;"/>',
			isset( $this->options['prod_webhook'] ) ? esc_attr( $this->options['prod_webhook']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function stage_callback() {
		printf(
			'<input type="text" id="stage_webhook" name="lbn_netlifly[stage_webhook]" value="%s" style="min-width:450px;" />',
			isset( $this->options['stage_webhook'] ) ? esc_attr( $this->options['stage_webhook']) : ''
		);
	}
}
