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
class LBN_Metaboxes {

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
		// Remove default publish metabox.
		add_action( 'do_meta_boxes', array( $this, 'remove_publish_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_publish_box' ) );
	}

	/**
	 * Remove default publish metabox. We'll add our own.
	 *
	 * @return void
	 */
	public function remove_publish_box() {
		remove_meta_box( 'submitdiv', array( 'post', 'page' ), 'side' );
	}

	public function add_publish_box() {
		add_meta_box(
			'some_meta_box_name',
			__( 'Publish', 'littlebot-netlify' ),
			array( $this, 'render_meta_box_content' ),
			array( 'post', 'page' ),
			'side',
			'high'
		);
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
		include_once $this->plugin->path . 'views/view-metabox-publish.php';
	}

}
