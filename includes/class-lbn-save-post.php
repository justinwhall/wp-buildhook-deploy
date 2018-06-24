<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin metaboxs.
 *
 * @version   0.9
 * @category  Class
 * @author    Justin W Hall
 */
class LBN_Save_Post {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 * @since 0.1.0
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
		var_dump( $_POST );die;
	}


}
