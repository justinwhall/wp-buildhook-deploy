<?php
/**
 * LittleBot Netlifly
 *
 * A class custome fields.
 *
 * @version   0.9.0
 * @category  Class
 * @package   LittleBotNetlifly
 * @author    Justin W Hall
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register our meta fields.
 */
class LBN_Meta_Fields {

	/**
	 * Kick it off.
	 *
	 * @param object $plugin the parent class.
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
		add_action( 'init', array( $this, 'register_meta_fields' ) );
	}

	/**
	 * Register customer meta fields and show in REST.
	 *
	 * @return void
	 */
	public function register_meta_fields() {

		$args = array(
			'type'         => 'boolean',
			'description'  => 'Has this post been published to stage',
			'single'       => true,
			'show_in_rest' => true,
		);

		register_meta( 'post', 'lbn_published_stage', $args );

		$args['description'] = 'Has this post been published to production';
		register_meta( 'post', 'lbn_published_production', $args );
	}

}
