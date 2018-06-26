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

		// Are we deploying to stage/production.
		$deploy_stage = isset( $_POST['lbn_deploy_stage'] ) ? true : false;
		$deploy_production = isset( $_POST['lbn_deploy_production'] ) ? true : false;

		// Prev deploy states.
		$prev_deploy_stage = (bool) get_post_meta( $post_id, 'lbn_deploy_stage', true );
		$prev_deploy_production = (bool) get_post_meta( $post_id, 'lbn_deploy_production', true );

		// Update deploy status.
		update_post_meta( $post_id, 'lbn_deploy_stage', $deploy_stage );
		update_post_meta( $post_id, 'lbn_deploy_production', $deploy_production );

		// Maybe deploy to stage?
		if ( $deploy_stage || $deploy_stage !== $prev_deploy_stage ) {
			// $netlifly_stage = new LBN_Netlifly( 'stage' );
			// $netlifly_stage->call_build_hook();
		}

		// Maybe deploy to production?
		if ( $deploy_production || $deploy_production !== $prev_deploy_production ) {
			// $netlifly_stage = new LBN_Netlifly( 'production' );
			// $netlifly_stage->call_build_hook();
		}

	}

}
