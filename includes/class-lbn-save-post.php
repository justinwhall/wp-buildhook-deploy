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
		add_action( 'wp_insert_post_data', array( $this, 'insert_post' ), 10, 3 );
	}

	public function insert_post( $data, $post ) {
		if (
			isset( $post['post_status'] ) && 'auto-draft' === $post['post_status'] ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return $data;
		}

		// If it's a deploy, make sure it's set to publish.
		if ( isset( $post['deploy'] ) ) {
			$data['post_status'] = 'publish';
		}

		return $data;
	}

	/**
	 * Save post callback
	 *
	 * @param int     $post_id The post ID.
	 * @param object  $post    The post object.
	 * @param boolean $update  Is this an update.
	 * @return void
	 */
	public function save_post( $post_id, $post, $update ) {
		// Bail if it's a auto-draft, we're doing auto save or ajax.
		if (
			isset( $post->post_status ) && 'auto-draft' === $post->post_status ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return;
		}

		// Is this post published.
		$published_stage = isset( $_POST['lbn_published_stage'] ) ? true : false;
		$published_production = isset( $_POST['lbn_published_production'] ) ? true : false;

		// Update published status.
		update_post_meta( $post->ID, 'lbn_published_stage', $published_stage );
		update_post_meta( $post->ID, 'lbn_published_production', $published_production );

		// Maybe deploy to stage?
		if ( $published_stage ) {
			$netlifly_stage = new LBN_Netlifly( 'stage' );
			$netlifly_stage->call_build_hook();
		}

		// Maybe deploy to production?
		if ( $published_production ) {
			$netlifly_stage = new LBN_Netlifly( 'production' );
			$netlifly_stage->call_build_hook();
		}

	}

}
