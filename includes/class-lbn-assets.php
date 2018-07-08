<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin assets.
 *
 * @version   0.9
 * @category  Class
 * @package   LittleBotNetlifly/Assets
 * @author    Justin W Hall
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post Metabox.
 */
class LBN_Assets {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 * @since 0.9.0
	 */
	protected $plugin = null;

	/**
	 * Kick it off.
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Attach hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
	}

	/**
	 * Enqueue assets
	 *
	 * @return void
	 */
	public function enqueue_scripts_styles() {
		wp_enqueue_style( 'lbn-styles', $this->plugin->url . '/assets/littlebot-netlifly.css', array(), $this->plugin->__get( 'version' ), false );
		wp_enqueue_script( 'lbn-scripts', $this->plugin->url . '/assets/littlebot-netlifly.js', array(), $this->plugin->__get( 'version' ), true );
	}

}
