<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin metaboxs.
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
 * Hooks saving and updating posts.
 */
class LBN_Save_Post {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 */
	protected $plugin = null;

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
		add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );
	}


	public function save_post( $post_id, $post, $update ) {
		// var_dump( $post );
		// var_dump( $_POST );die;
	}

	public function call_netlifly() {

	}

}
